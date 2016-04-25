<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "customer_priority".
 *
 * @property string $id
 * @property integer $days_to_process
 *
 * @property Customer[] $customers
 */
class CustomerPriority extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'customer_priority';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'days_to_process'], 'required'],
            [['days_to_process'], 'integer'],
            [['id'], 'string', 'max' => 1]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'days_to_process' => 'Days To Process',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomers()
    {
        return $this->hasMany(Customer::className(), ['customer_priority_id' => 'id']);
    }
    
}
