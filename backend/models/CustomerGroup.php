<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "customer_group".
 *
 * @property integer $id
 * @property string $name
 * @property string $contact
 * @property string $street
 * @property integer $zip_code
 * @property string $city
 * @property string $province
 * @property string $fax_no
 * @property string $tel_no
 * @property integer $created
 * @property integer $created_by
 * @property string $updated
 * @property integer $updated_by
 *
 * @property Customer[] $customers
 * @property User $createdBy
 * @property User $updatedBy
 */
class CustomerGroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'customer_group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'contact', 'street', 'zip_code', 'city', 'province', 'fax_no', 'tel_no', 'created', 'created_by', 'updated_by'], 'required'],
            [['zip_code', 'created', 'created_by', 'updated_by'], 'integer'],
            [['updated'], 'safe'],
            [['name', 'contact', 'fax_no', 'tel_no'], 'string', 'max' => 100],
            [['street', 'city', 'province'], 'string', 'max' => 255]
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
            'contact' => 'Contact',
            'street' => 'Street',
            'zip_code' => 'Zip Code',
            'city' => 'City',
            'province' => 'Province',
            'fax_no' => 'Fax No',
            'tel_no' => 'Tel No',
            'created' => 'Created',
            'created_by' => 'Created By',
            'updated' => 'Updated',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomers()
    {
        return $this->hasMany(Customer::className(), ['customer_group_id' => 'id']);
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
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }
}
