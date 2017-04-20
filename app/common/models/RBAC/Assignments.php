<?php

namespace common\models\RBAC;

use yii\db\ActiveRecord;
use common\helpers\LogsHelper;

/**
 * @property string  $item_name
 * @property string  $user_id
 * @property integer $created_at
 *
 * @property Items   $itemName
 */
class Assignments extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%rbac_assignments}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_name', 'user_id'], 'required'],
            [['user_id'], 'string'],
            [['created_at'], 'integer'],
            [['item_name'], 'string', 'max' => 64],
            [
                ['item_name'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Items::className(),
                'targetAttribute' => ['item_name' => 'name'],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'item_name' => 'Item Name',
            'user_id' => 'User ID',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemName()
    {
        return $this->hasOne(Items::className(), ['name' => 'item_name']);
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
