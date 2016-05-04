<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "select_menu".
 *
 * @property integer $id
 * @property string $model_name
 * @property string $select_name
 * @property string $option_name
 * @property string $lang
 * @property string $status
 * @property string $created
 * @property string $updated
 */
class SelectMenu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'select_menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['model_name', 'select_name', 'option_name', 'lang', 'status', 'created'], 'required'],
            [['status'], 'string'],
            [['created', 'updated'], 'safe'],
            [['model_name', 'select_name', 'option_name'], 'string', 'max' => 255],
            [['lang'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'model_name' => 'Model Name',
            'select_name' => 'Select Name',
            'option_name' => 'Option Name',
            'lang' => 'Lang',
            'status' => 'Status',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }
}
