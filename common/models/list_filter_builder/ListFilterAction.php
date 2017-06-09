<?php
/**
 * Created by PhpStorm.
 * User: johnny
 * Date: 17-3-31
 * Time: 下午10:34
 */

namespace common\models\list_filter_builder;


use yii\base\Action;
use yii\base\Exception;
use yii\db\ActiveRecordInterface;
use Yii;

/**
 * Class ListFilterAction
 * @property $record
 * @package common\models\list_filter_builder
 */
class ListFilterAction extends Action
{
    public $record;
    public $initialCondition;
    public $selector;
    public $returnCallBack;
    public $filter;
    public $exceptionUrl;

    public function init()
    {
        if(!$this->record instanceof ActiveRecordInterface) {
            throw new Exception('invalid record');
        }

        if(empty($this->selector)) {
            throw new Exception('invalid selector, should not be empty');
        }

        if(!is_callable($this->returnCallBack)) {
            throw new Exception('invalid returnCallBack, should be callback');
        }
    }

    public function run() {
        if(Yii::$app->request->isPost) {
            $this->filter = Yii::$app->request->post('ListFilterForm');
        } else {
            $ser_filter = Yii::$app->request->get('ser_filter');
            if(empty($ser_filter)) {
                return $this->controller->redirect($this->exceptionUrl);
            }

            $this->filter = unserialize($ser_filter);
        }

        $this->record->clearEmptyField($this->filter);

        $this->initialCondition = $this->buildInitialCondition($this->initialCondition);
        $recordList = $this->record->listFilter(
            $this->selector,
            $this->filter,
            $this->initialCondition
        );

        return call_user_func($this->returnCallBack, [
            $recordList,
            $this->filter,
        ]);
    }

    protected function buildInitialCondition($initialCondition) {

        if(empty($initialCondition)) {
            return $initialCondition;
        }

        if(is_array($initialCondition)) {
            return $initialCondition;
        }

        if(is_callable($initialCondition)) {
            return call_user_func($initialCondition, $this);
        }

        throw new Exception('invalid initial condition');
    }
}