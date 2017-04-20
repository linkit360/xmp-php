<?php

namespace common\models;

use yii\db\ActiveRecord;
use common\helpers\LogsHelper;

/**
 * @property integer $id
 * @property string  $name
 * @property string  $name_alias
 * @property integer $id_country
 * @property integer $status
 */
class Providers extends ActiveRecord
{
    private $countries = [];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%providers}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'name_alias', 'id_country'], 'required'],
            [['id_country', 'status'], 'integer'],
            [['name', 'name_alias',], 'string', 'max' => 255],
            [['name', 'name_alias',], 'unique'],
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
            'id_country' => 'Country',
        ];
    }

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
