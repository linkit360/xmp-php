<?php

namespace frontend\models\Campaigns;

use function count;
use const false;

use Yii;
use yii\helpers\ArrayHelper;

use common\models\Lps;
use common\models\Campaigns;
use common\models\Operators;
use common\models\Services;

/**
 * Campaigns Form
 */
class CampaignsForm extends Campaigns
{
    # Data
    private $lps = [];
    private $services = [];
    private $operators = [];

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

    # LPs
    public function getLps()
    {
        if (!count($this->lps)) {
            $data = Lps::find()
                ->select([
                    'title',
                    'id',
                ])
                ->where([
                    'status' => 1,
                    'id_user' => Yii::$app->user->id,
                ])
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
            $data = Services::find()
                ->select([
                    'title',
                    'id',
                ])
                ->where([
                    'status' => 1,
                    'id_user' => Yii::$app->user->id,
                ])
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
