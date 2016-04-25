<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "so_item".
 *
 * @property string $id
 * @property string $so_id
 * @property string $so_item_no
 * @property double $qty
 * @property double $value
 * @property integer $created_by
 * @property string $created
 * @property integer $changed_by
 * @property string $changed
 *
 * @property User $changedBy
 * @property User $createdBy
 */
class SoItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'so_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['so_id', 'so_item_no', 'qty', 'value', 'created_by', 'created', 'changed_by', 'changed'], 'required'],
            [['so_id', 'created_by', 'changed_by'], 'integer'],
            [['qty', 'value'], 'number'],
            [['created', 'changed'], 'safe'],
            [['so_item_no'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'so_id' => 'So ID',
            'so_item_no' => 'So Item No',
            'qty' => 'Qty',
            'value' => 'Value',
            'created_by' => 'Created By',
            'created' => 'Created',
            'changed_by' => 'Changed By',
            'changed' => 'Changed',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChangedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'changed_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }
}
