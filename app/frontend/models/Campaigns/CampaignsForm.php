<?php

namespace frontend\models\Campaigns;

use const false;
use function count;

use Yii;
use yii\helpers\ArrayHelper;

use common\models\Lps;
use common\models\Services;
use common\models\Campaigns;

class CampaignsForm extends Campaigns
{
    # Data
    private $lps = [];
    private $services = [];

    # LPs
    public function getLps()
    {
        if (!count($this->lps)) {
            $where = [
                "status" => 1,
                "id_user" => Yii::$app->user->id,
            ];

            if (Yii::$app->user->can('Admin')) {
                unset($where["id_user"]);
            }

            $data = Lps::find()
                ->select([
                    'title',
                    'id',
                ])
                ->where($where)
                ->orderBy([
                    'title' => SORT_ASC,
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

            $this->lps = $tmp +
                ArrayHelper::map(
                    $data,
                    'id',
                    'title'
                );
        }

        return $this->lps;
    }

    # Services
    public function getServices()
    {
        if (!count($this->services)) {
            $where = [
                "status" => 1,
                "id_user" => Yii::$app->user->id,
            ];

            if (Yii::$app->user->can('Admin')) {
                unset($where["id_user"]);
            }

            $data = Services::find()
                ->select([
                    'title',
                    'id',
                ])
                ->where($where)
                ->orderBy([
                    'title' => SORT_ASC,
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

            $this->services = $tmp +
                ArrayHelper::map(
                    $data,
                    'id',
                    'title'
                );
        }

        return $this->services;
    }

    public function commit()
    {
        if (!$this->validate()) {
            return false;
        }

        return $this->save();
    }
}
