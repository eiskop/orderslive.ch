<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "product_group".
 *
 * @property integer $id
 * @property string $name
 * @property string $name_short
 * @property string $no
 * @property string $created
 * @property integer $created_by
 * @property string $changed
 * @property integer $changed_by
 *
 * @property User $createdBy
 * @property User $changedBy
 * @property SalesItem[] $salesItems
 * @property So[] $sos
 * @property User[] $users
 */
class ProductGroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'name_short', 'no', 'created', 'changed'], 'required'],
            [['created', 'changed'], 'safe'],
            [['created_by', 'changed_by'], 'integer'],
            [['name', 'no'], 'string', 'max' => 255],
            [['name_short'], 'string', 'max' => 5]
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
            'name_short' => 'Name Short',
            'no' => 'No',
            'created' => 'Created',
            'created_by' => 'Created By',
            'changed' => 'Changed',
            'changed_by' => 'Changed By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
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
    public function getSalesItems()
    {
        return $this->hasMany(SalesItem::className(), ['product_group_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSos()
    {
        return $this->hasMany(So::className(), ['product_group_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['product_group_id' => 'id']);
    }
}
