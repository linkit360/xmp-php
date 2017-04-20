<?php

namespace common\models\RBAC;

use yii\db\ActiveRecord;
use common\helpers\LogsHelper;

/**
 * @property string $parent
 * @property string $child
 *
 * @property Items  $parent0
 * @property Items  $child0
 */
class ItemsChilds extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%rbac_items_childs}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent', 'child'], 'required'],
            [['parent', 'child'], 'string', 'max' => 64],
            [
                ['parent'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Items::className(),
                'targetAttribute' => ['parent' => 'name'],
            ],
            [
                ['child'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Items::className(),
                'targetAttribute' => ['child' => 'name'],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'parent' => 'Parent',
            'child' => 'Child',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent0()
    {
        return $this->hasOne(Items::className(), ['name' => 'parent']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChild0()
    {
        return $this->hasOne(Items::className(), ['name' => 'child']);
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
