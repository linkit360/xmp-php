<?php

namespace frontend\models\Services;

use yii\base\Model;

class MobilinkForm extends Model
{
    # Fields
    public $sms_on_content;
    public $sms_on_subscribe;
    public $sms_on_unsubscribe;
    public $periodic_days;

    public $retry_days = 0;
    public $inactive_days = 0;
    public $grace_days = 0;
    public $minimal_touch_times = 0;
    public $trial_days = 0;
    public $purge_after_days = 0;

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
                    "trial_days",
                    "purge_after_days",
                ],
                "required",
            ],
            [
                [
                    "sms_on_content",
                    "sms_on_subscribe",
                    "sms_on_unsubscribe",
                ],
                "string",
            ],
            [
                [
                    "retry_days",
                    "inactive_days",
                    "grace_days",
                    "minimal_touch_times",
                    "trial_days",
                    "purge_after_days",
                ],
                "integer",
            ],
            [
                [
                    "trial_days",
                    "purge_after_days",
                    "periodic_days",
                ],
                "safe",
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
        $int_fields = [
            "retry_days",
            "inactive_days",
            "grace_days",
            "minimal_touch_times",
            "trial_days",
            "purge_after_days",
        ];

        foreach ($int_fields as $field) {
            if ($this->{$field}) {
                $this->{$field} = (int)$this->{$field};
            } else {
                $this->{$field} = 0;
            }
        }

        return parent::beforeValidate();
    }

}
