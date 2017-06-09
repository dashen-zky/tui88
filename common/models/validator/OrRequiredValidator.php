<?php
namespace common\models\validator;
use yii\validators\Validator;
use Yii;

/**
 * Created by PhpStorm.
 * User: johnny
 * Date: 17-2-10
 * Time: 上午12:35
 */
class OrRequiredValidator extends Validator
{
    public $or_attributes;
    public $requiredValue;
    public $strict = false;

    /**
     * @inheritdoc
     */
    public function validateAttribute($model, $attribute)
    {
        if (!empty($model->$attribute)) {
            return null;
        }

        if (empty($this->or_attributes) || !is_array($this->or_attributes)) {
            return [$this->message, []];
        }

        foreach ($this->or_attributes as $or_attribute) {
            if (!empty($model->$or_attribute)) {
                return null;
            }
        }

        return [$this->message, []];
    }

    /**
     * @inheritdoc
     */
    public function clientValidateAttribute($model, $attribute, $view)
    {
        $options = [];
        if ($this->requiredValue !== null) {
            $options['message'] = Yii::$app->getI18n()->format($this->message, [
                'requiredValue' => $this->requiredValue,
            ], Yii::$app->language);
            $options['requiredValue'] = $this->requiredValue;
        } else {
            $options['message'] = $this->message;
        }
        if ($this->strict) {
            $options['strict'] = 1;
        }

        $options['message'] = Yii::$app->getI18n()->format($options['message'], [
            'attribute' => $model->getAttributeLabel($attribute),
        ], Yii::$app->language);

        $options['or_attributes'] = $this->or_attributes;
        preg_match('/\w+$/', get_class($model), $matches);
        $options['id_prefix'] = strtolower($matches[0]);

        ValidationAsset::register($view);

        return 'yii.customerValidation.orRequired(value, messages, ' . json_encode($options, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . ');';
    }
}