<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "so_status".
 *
 * @property integer $id
 * @property string $name
 * @property integer $active
 * @property string $created
 * @property string $changed
 *
 * @property So[] $sos
 */
class SoStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'so_status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'active', 'created'], 'required'],
            [['active'], 'integer'],
            [['created', 'changed'], 'safe'],
            [['name'], 'string', 'max' => 20]
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
            'active' => 'Active',
            'created' => 'Created',
            'changed' => 'Changed',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSos()
    {
        return $this->hasMany(So::className(), ['status_id' => 'id']);
    }
}
