<?php

namespace common\models;

use yii\db\ActiveRecord;
use common\helpers\LogsHelper;

/**
 * @property integer $id
 * @property string  $name
 * @property integer $code
 * @property integer $status
 * @property string  $iso
 * @property integer $priority
 * @property string  $flag
 * @property string  $currency
 */
class Countries extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%countries}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'code', 'status', 'iso', 'priority'], 'required'],
            [['code', 'status', 'priority'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['flag'], 'string', 'max' => 64],
            [['iso', 'currency'], 'string', 'max' => 32],
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
            'code' => 'Code',
            'status' => 'Status',
            'iso' => 'Iso',
            'priority' => 'Priority',
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
}
