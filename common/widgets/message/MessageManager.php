<?php
/**
 * Created by PhpStorm.
 * User: johnny
 * Date: 17-1-4
 * Time: 下午12:01
 */

namespace common\widgets\message;


use yii\data\Pagination;
use yii\base\Component;
use yii\base\Exception;
use Yii;

class MessageManager extends Component
{
    const BeforeSendMessage = 'BeforeSendMessage';
    const AfterSendMessage = 'afterSendMessage';

    const SystemMessageQueue = 'SystemMessageQueue';  // 系统消息队列

    public function beforeSendMessage($event) {
        $this->trigger(self::BeforeSendMessage, $event);
        return $event->isValidate;
    }

    public function afterSendMessage($event) {
        $this->trigger(self::AfterSendMessage, $event);
        return $event->isValidate;
    }

    /**
     * @param $message ['title'=>'xxxx', 'body'=>'ssss', 'type'=>xxx, '']
     * @param $queue
     * @throws Exception
     */
    public function send($message, $queue) {
        if(!is_array($message)) {
            throw new Exception('$message should be array');
        }
        
        if (!isset($message['title']) || empty($message['title'])) {
            throw new Exception('$message title be set');
        }

        if (!isset($message['body']) || empty($message['body'])) {
            throw new Exception('$message body be set');
        }

        if (!is_string($queue)) {
            throw new Exception('$queue should be string');
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $event = new MessageEvent($message);
            if(!$this->beforeSendMessage($event)) {
                throw new Exception('before send message failed');
            }

            $formData = $message + [
                'queue_id'=>$queue,
                'create_time'=>time(),
            ];
            // 生成消息体
            $messageRecord = new Message();
            if(!$messageRecord->insertRecord($formData)) {
                throw new Exception('insert message failed');
            }

            // 获取订阅这个消息队列的用户
            $subscription = new MessageQueueSubscription();
            $subscribers = $subscription->getSubscriber($queue);

            // 分发消息
            $messageUserMap = new MessageUserMap();
            foreach ($subscribers as $subscriber) {
                if(!$messageUserMap->insertRecord([
                    'user_id'=>$subscriber,
                    'message_id'=>$messageRecord->id,
                ])) {
                    throw new Exception('send message failed');
                }
            }

            if(!$this->afterSendMessage($event)) {
                throw new Exception('after send message failed');
            }
        }catch (Exception $e) {
            $transaction->rollBack();
            Yii::trace($e->getMessage());
            return false;
        }

        $transaction->commit();
        return true;
    }

    public function myMessageList() {
        $query = Message::find()->select([
                't1.*',
                't2.checked',
                't2.checked_time',
            ])->alias('t1')
            ->leftJoin(BaseRecord::MessageUserMap . ' t2', 't1.id = t2.message_id')
            ->andWhere(['t2.user_id'=>Yii::$app->getUser()->getId()]);

        $pagination = new Pagination([
            'totalCount'=>$query->count(),
            'pageSize'=>20,
        ]);

        $list = $query->orderBy([
            't2.id'=>SORT_DESC
        ])->offset($pagination->offset)->limit($pagination->limit)->asArray()->all();
        return [
            'list'=>$list,
            'pagination'=>$pagination,
        ];
    }

    public function checkMessage($message_id) {
        return (new MessageUserMap())->checkMessage($message_id);
    }

    public function deleteMessage($message_id) {
        $messageUserMap = new MessageUserMap();
        $messageUserMap->on(MessageUserMap::AfterDeleteMessage, [$this, 'checkMessageValid']);
        return $messageUserMap->deleteMessage($message_id, Yii::$app->getUser()->getId());
    }

    /**
     * 查看一下这个消息体不是否有效，无效的消息是指所有人都删除的消息
     * @param $e
     */
    public function checkMessageValid($e) {
        $message_id = $e->message['id'];
        $record = MessageUserMap::find()->andWhere(['message_id'=>$message_id])->one();
        if(!empty($record)) {
            return true;
        }

        $message = new Message();
        $message->deleteRecord($message_id);
    }
}