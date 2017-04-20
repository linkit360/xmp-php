<?php

namespace common\models\RBAC;

use yii\db\ActiveRecord;
use common\helpers\LogsHelper;

/**
 * This is the model class for table "{{%rbac_items}}".
 *
 * @property string        $name
 * @property integer       $type
 * @property string        $description
 * @property string        $rule_name
 * @property resource      $data
 * @property integer       $created_at
 * @property integer       $updated_at
 *
 * @property Assignments[] $rbacAssignments
 * @property Rules         $ruleName
 * @property ItemsChilds[] $rbacItemsChilds
 * @property ItemsChilds[] $rbacItemsChilds0
 * @property Items[]       $children
 * @property Items[]       $parents
 */
class Items extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%rbac_items}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'type'], 'required'],
            [['type', 'created_at', 'updated_at'], 'integer'],
            [['description', 'data'], 'string'],
            [['name', 'rule_name'], 'string', 'max' => 64],
            [
                ['rule_name'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Rules::className(),
                'targetAttribute' => ['rule_name' => 'name'],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Name',
            'type' => 'Type',
            'description' => 'Description',
            'rule_name' => 'Rule Name',
            'data' => 'Data',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRbacAssignments()
    {
        return $this->hasMany(Assignments::className(), ['item_name' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRuleName()
    {
        return $this->hasOne(Rules::className(), ['name' => 'rule_name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRbacItemsChilds()
    {
        return $this->hasMany(ItemsChilds::className(), ['parent' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRbacItemsChilds0()
    {
        return $this->hasMany(ItemsChilds::className(), ['child' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChildren()
    {
        return $this
            ->hasMany(
                Items::className(),
                ['name' => 'child']
            )
            ->viaTable(
                '{{%rbac_items_childs}}',
                ['parent' => 'name']
            );
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParents()
    {
        return $this
            ->hasMany(
                Items::className(),
                ['name' => 'parent']
            )
            ->viaTable(
                '{{%rbac_items_childs}}',
                ['child' => 'name']
            );
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
