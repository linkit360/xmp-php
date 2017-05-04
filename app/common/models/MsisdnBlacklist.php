<?php

namespace common\models;

use yii\db\ActiveRecord;
use common\helpers\LogsHelper;

/**
 * This is the model class for table "xmp_msisdn_blacklist".
 *
 * @property string  $id
 * @property string  $msisdn
 * @property string  $provider_name
 * @property integer $operator_code
 * @property string  $created_at
 * @property string  $id_user
 */
class MsisdnBlacklist extends ActiveRecord
{
    # Data
    private $countries = [];
    private $operators = [];
    private $providers = [];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%msisdn_blacklist}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'msisdn',
                    'id_provider',
                    'id_operator',
                    'id_user',
                ],
                'required',
            ],
            [
                [
                    'id_provider',
                    'id_operator',
                ],
                'integer',
            ],
            [
                [
                    'msisdn',
                ],
                'string',
                'max' => 32,
            ],
        ];
    }

    # Countries
    public function getCountries()
    {
        if (!count($this->countries)) {
            $this->countries = Countries::find()
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
                ->indexBy('id')
                ->column();
        }

        return $this->countries;
    }

    # Providers
    public function getProviders()
    {
        if (!count($this->providers)) {
            $this->providers = Providers::find()
                ->select([
                    'name',
                    'id',
                    'id_country',
                ])
                ->orderBy([
                    'name' => SORT_ASC,
                ])
                ->indexBy('id')
                ->all();
        }

        return $this->providers;
    }

    # Operators
    public function getOperators()
    {
        if (!count($this->operators)) {
            $this->operators = Operators::find()
                ->select([
                    'name',
                    'id',
                    'id_provider',
                ])
                ->where([
                    'status' => 1,
                ])
                ->orderBy([
                    'name' => SORT_ASC,
                ])
                ->indexBy('id')
                ->all();
        }

        return $this->operators;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'msisdn' => 'MSISDN',
            'id_user' => 'Created By',
            'id_provider' => 'Provider',
            'id_operator' => 'Operator',
            'created_at' => 'Created At',
        ];
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

    public function afterDelete()
    {
        $logs = new LogsHelper();
        $logs->logDelete($this);
        return parent::afterDelete();
    }
}
