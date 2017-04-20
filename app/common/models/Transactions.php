<?php

namespace common\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "xmp_transactions".
 *
 * @property string              $id
 * @property string              $created_at
 * @property string              $sent_at
 * @property string              $tid
 * @property string              $msisdn
 * @property integer             $id_country
 * @property integer             $id_service
 * @property integer             $id_campaign
 * @property integer             $id_subscription
 * @property integer             $id_content
 * @property integer             $id_operator
 * @property string              $operator_token
 * @property integer             $price
 * @property string              $result
 *
 * @property TransactionsResults $result0
 */
class Transactions extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%transactions}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'msisdn', 'operator_token', 'price', 'result'], 'required'],
            [['id'], 'string'],
            [['created_at', 'sent_at'], 'safe'],
            [
                ['id_country', 'id_service', 'id_campaign', 'id_subscription', 'id_content', 'id_operator', 'price'],
                'integer'
            ],
            [['tid', 'result'], 'string', 'max' => 127],
            [['msisdn'], 'string', 'max' => 32],
            [['operator_token'], 'string', 'max' => 511],
            [
                ['result'],
                'exist',
                'skipOnError' => true,
                'targetClass' => TransactionsResults::className(),
                'targetAttribute' => ['result' => 'name']
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => 'Created At',
            'sent_at' => 'Sent At',
            'tid' => 'Tid',
            'msisdn' => 'Msisdn',
            'id_country' => 'Id Country',
            'id_service' => 'Id Service',
            'id_campaign' => 'Id Campaign',
            'id_subscription' => 'Id Subscription',
            'id_content' => 'Id Content',
            'id_operator' => 'Id Operator',
            'operator_token' => 'Operator Token',
            'price' => 'Price',
            'result' => 'Result',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResult0()
    {
        return $this->hasOne(TransactionsResults::className(), ['name' => 'result']);
    }
}
