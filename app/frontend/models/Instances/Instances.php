<?php

namespace frontend\models\Instances;

use yii\helpers\ArrayHelper;

use common\models\Operators;

class Instances extends \common\models\Instances
{
    # Data
    private $operators = [];

    public function attributeLabels()
    {
        return [
            'id_operator' => 'Operator',
        ];
    }

    # Operators
    public function getOperators()
    {
        if (!count($this->operators)) {
            $data = Operators::find()
                ->select([
                    'name',
                    'id',
                ])
                ->where([
                    'status' => 1,
                ])
                ->orderBy([
                    'name' => SORT_ASC,
                ])
                ->asArray()
                ->indexBy('id')
                ->all();

            $tmp = [];
            if ($this->isNewRecord) {
                $tmp = [
                    null => 'Please Select',
                ];
            }

            $this->operators = $tmp +
                ArrayHelper::map(
                    $data,
                    'id',
                    'name'
                );
        }

        return $this->operators;
    }

}
