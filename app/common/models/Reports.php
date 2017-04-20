<?php

namespace common\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "xmp_reports".
 *
 * @property string  $id
 * @property string  $report_date
 * @property integer $id_campaign
 * @property integer $id_provider
 * @property integer $id_operator
 * @property integer $lp_hits
 * @property integer $lp_msisdn_hits
 * @property integer $mo
 * @property integer $mo_uniq
 * @property integer $mo_success
 * @property integer $pixels
 */
class Reports extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%reports}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'id',
                    'id_campaign',
                    'id_provider',
                    'id_operator',
                    'lp_hits',
                    'lp_msisdn_hits',
                    'mo',
                    'mo_uniq',
                    'mo_success',
                    'pixels',
                ],
                'required',
            ],
            [
                [
                    'id',
                    'id_provider',
                ],
                'string',
            ],
            [['report_date'], 'safe'],
            [
                [
                    'id_campaign',
                    'id_operator',
                    'lp_hits',
                    'lp_msisdn_hits',
                    'mo',
                    'mo_uniq',
                    'mo_success',
                    'pixels',
                ],
                'integer',
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
            'report_date' => 'Report Date',
            'id_campaign' => 'Campaign ID',
            'id_provider' => 'Provider ID',
            'id_operator' => 'Operator ID',
            'lp_hits' => 'LP Hits',
            'lp_msisdn_hits' => 'LP Hits (with MSISDN)',
            'mo' => 'Valid Transactions',
            'mo_uniq' => 'Unique Transactions',
            'mo_success' => 'Success Transactions',
            'pixels' => 'Pixels Sent',
        ];
    }
}
