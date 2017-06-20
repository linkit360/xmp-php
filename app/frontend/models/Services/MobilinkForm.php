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
                ],
                "integer",
            ],
            [
                [
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
        // retry_days
        if ($this->retry_days) {
            $this->retry_days = (int)$this->retry_days;
        } else {
            unset($this->retry_days);
        }

        // inactive_days
        if ($this->inactive_days) {
            $this->inactive_days = (int)$this->inactive_days;
        } else {
            unset($this->inactive_days);
        }

        // grace_days
        if ($this->grace_days) {
            $this->grace_days = (int)$this->grace_days;
        } else {
            unset($this->grace_days);
        }

        // minimal_touch_times
        if ($this->minimal_touch_times) {
            $this->minimal_touch_times = (int)$this->minimal_touch_times;
        } else {
            unset($this->minimal_touch_times);
        }

        return parent::beforeValidate();
    }

}
