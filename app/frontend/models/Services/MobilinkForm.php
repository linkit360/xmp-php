<?php

namespace frontend\models\Services;

use yii\base\Model;

class MobilinkForm extends Model
{
    # Fields
    public $sms_on_content;
    public $sms_on_subscribe;
    public $sms_on_unsubscribe;
    public $retry_days;
    public $inactive_days;
    public $grace_days;
    public $periodic_days;
    public $minimal_touch_times;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    "sms_on_content",
                    "sms_on_subscribe",
                    "sms_on_unsubscribe",
                    "retry_days",
                    "inactive_days",
                    "grace_days",
                    "periodic_days",
                    "minimal_touch_times",
                ],
                'required',
            ],
            [
                [
                    'sms_on_content',
                    "sms_on_subscribe",
                    "sms_on_unsubscribe",
                ],
                'string',
            ],
            [
                [
                    "retry_days",
                    "inactive_days",
                    "grace_days",
                    "minimal_touch_times",
                ],
                'integer',
            ],
            [
                [
                    "periodic_days",
                ],
                'safe',
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            "sms_on_content" => "SMS On Content",
            "sms_on_subscribe" => "SMS On Subscribe",
            "sms_on_unsubscribe" => "SMS On Unsubscribe",
            "retry_days" => "Retry Days",
            "inactive_days" => "Inactive Days",
            "grace_days" => "Grace Days",
            "periodic_days" => "Periodic Days",
            "minimal_touch_times" => "Minimal Touch Times",
        ];
    }

    public function beforeValidate()
    {
        $this->periodic_days = implode(",", $this->periodic_days);

        return parent::beforeValidate();
    }

}
