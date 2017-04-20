<?php

namespace common\models;

use common\helpers\LogsHelper;
use yii\db\ActiveRecord;

/**
 * @property integer $id
 * @property string  $name
 * @property integer $id_provider
 * @property string  $isp
 * @property string  $msisdn_prefix
 * @property string  $mcc
 * @property string  $mnc
 * @property integer $status
 * @property integer $code
 * @property string  $created_at
 */
class Operators extends ActiveRecord
{
    private $providers = [];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%operators}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'code'], 'required'],
            [['id_provider', 'status', 'code'], 'integer'],
            [['created_at'], 'safe'],
            [['name', 'isp', 'msisdn_prefix', 'mcc', 'mnc'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'id_provider' => 'Provider',
            'isp' => 'Isp',
            'msisdn_prefix' => 'Msisdn Prefix',
            'mcc' => 'Mcc',
            'mnc' => 'Mnc',
            'status' => 'Status',
            'code' => 'Code',
            'created_at' => 'Created At',
        ];
    }

    public function getProviders()
    {
        if (!count($this->providers)) {
            $this->providers = Providers::find()
                ->select([
                    'name',
                    'id',
                ])
                ->orderBy([
                    'name' => SORT_ASC,
                ])
                ->indexBy('id')
                ->column();
        }

        return $this->providers;
    }

    public function afterSave($insert, $oldAttributes)
    {
        $logs = new LogsHelper();
        $logs->log(
            $this,
            $oldAttributes
        );

        return parent::afterSave(
            $insert,
            $oldAttributes
        );
    }
}
