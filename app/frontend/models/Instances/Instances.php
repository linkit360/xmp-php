<?php

namespace frontend\models\Instances;

use yii\helpers\ArrayHelper;

use common\models\Providers;

class Instances extends \common\models\Instances
{
    # Data
    private $providers = [];

    public function attributeLabels()
    {
        return [
            'id_provider' => 'Provider',
        ];
    }

    # Providers
    public function getProviders()
    {
        if (!count($this->providers)) {
            $data = Providers::find()
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

            $this->providers = $tmp +
                ArrayHelper::map(
                    $data,
                    'id',
                    'name'
                );
        }

        return $this->providers;
    }

}
