<?php

namespace common\helpers;

use Yii;
use yii\db\ActiveRecord;
use common\models\Logs;

class LogsHelper
{
    /**
     * @param ActiveRecord $model
     * @param array        $oldAttributes
     */
    public function log($model, $oldAttributes)
    {
        $log = new Logs();
        $log->controller = Yii::$app->requestedAction->controller->id;
        $log->action = Yii::$app->requestedAction->id;
        $ev = [
            'id' => $model->hasProperty('id') ? $model['id'] : $model['name'],
        ];

        if (!$model->getIsNewRecord()) {
            $event = [];
            foreach ($model->attributes as $attribute => $value) {
                if (!array_key_exists($attribute, $oldAttributes)) {
                    continue;
                }

                $valueOld = $oldAttributes[$attribute];
                if ($valueOld === $value) {
                    continue;
                }

                if (is_numeric($valueOld) && $valueOld === (integer)$value) {
                    continue;
                }

                $event[$attribute] = [
                    'from' => $oldAttributes[$attribute],
                    'to' => $value,
                ];
            }

            if (count($event)) {
                $ev['fields'] = $event;
            }
        }

        $log->event = $ev;
        $log->save();
    }

    /**
     * @param ActiveRecord $model
     */
    public function logDelete($model)
    {
        $log = new Logs();
        $log->controller = Yii::$app->requestedAction->controller->id;
        $log->action = Yii::$app->requestedAction->id;

        $ev = [
            'id' => $model->hasProperty('id') ? $model['id'] : $model['name'],
        ];

        $log->event = $ev;
        $log->save();
    }
}
