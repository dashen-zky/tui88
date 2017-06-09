<?php
namespace common\widgets\message;

/**
 * Created by PhpStorm.
 * User: johnny
 * Date: 17-1-4
 * Time: 上午11:30
 */
class Message extends BaseRecord
{
    const TypeSystem = 1; // 系统消息
    const TypeArticleMonitor = 2; // 文章监控

    const ShowPopUp = 1; // 弹出
    const ShowBlankWindow = 2; // 打开新页面
    const ShowSkipToUrl = 3; // 跳转到链接

    public static function tableName()
    {
        return self::MessageTable;
    }

    public function attributes()
    {
        return [
            'id', 'title','body','create_time','type','priority','queue_id','show_style'
        ];
    }

    public function rules()
    {
        return [];
    }

    public function insertRecord($formData) {
        $this->loadData($formData, $this);
        return $this->insert();
    }

    public function deleteRecord($id) {
        return self::deleteAll(['id'=>$id]);
    }
}