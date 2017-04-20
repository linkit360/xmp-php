<?php

namespace common\models\RBAC;

use yii\db\ActiveRecord;
use common\helpers\LogsHelper;

/**
 * @property string   $name
 * @property resource $data
 * @property integer  $created_at
 * @property integer  $updated_at
 *
 * @property Items[]  $rbacItems
 */
class Rules extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%rbac_rules}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['data'], 'string'],
            [['created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Name',
            'data' => 'Data',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRbacItems()
    {
        return $this->hasMany(Items::className(), ['rule_name' => 'name']);
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
