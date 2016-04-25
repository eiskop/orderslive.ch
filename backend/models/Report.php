<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "report".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $created
 */
class Report extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'report';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'description', 'created'], 'required'],
            [['created'], 'safe'],
            [['name'], 'string', 'max' => 50],
            [['description'], 'string', 'max' => 255]
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
            'description' => 'Description',
            'created' => 'Created',
        ];
    }
}
