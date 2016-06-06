<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "customer".
 *
 * @property integer $id
 * @property string $name
 * @property integer $customer_group_id
 * @property string $customer_priority_id
 * @property string $contact
 * @property string $street
 * @property string $zip_code
 * @property string $region
 * @property string $city
 * @property string $province
 * @property string $fax_no
 * @property string $tel_no
 * @property integer $active
 * @property integer $created
 * @property integer $created_by
 * @property string $updated
 * @property integer $updated_by
 *
 * @property CustomerPriority $customerPriority
 * @property User $createdBy
 * @property User $updatedBy
 * @property CustomerGroup $customerGroup
 * @property So[] $sos
 */
class Customer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'customer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'street', 'zip_code', 'province'], 'required'],
            [['customer_group_id', 'created', 'created_by', 'updated_by', 'active'], 'integer'],
            [['updated','customer_group_id', 'customer_priority_id', 'fax_no', 'tel_no', 'city', 'created', 'created_by', 'updated_by', 'region'], 'safe'],
            [['name', 'contact', 'city', 'province', 'tel_no'], 'string', 'max' => 100],
            [['customer_priority_id'], 'string', 'max' => 1],
            [['street'], 'string', 'max' => 255],
            [['zip_code'], 'string', 'max' => 20],
            [['fax_no'], 'string', 'max' => 40]
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
            'customer_group_id' => 'Gruppe',
            'customer_priority_id' => 'Kunden Priorität',
            'contact' => 'Kontaktperson',
            'street' => 'Strasse',
            'zip_code' => 'PLZ',
            'city' => 'Stadt',
            'province' => 'Canton',
            'fax_no' => 'Fax No',
            'tel_no' => 'Tel No',
            //'region' => 'Region',            
    //    'created' => 'Created',
    //    'created_by' => 'Created By',
    //      'updated' => 'Updated',
    //        'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerPriority()
    {
        return $this->hasOne(CustomerPriority::className(), ['id' => 'customer_priority_id']);
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerGroup()
    {
        return $this->hasOne(CustomerGroup::className(), ['id' => 'customer_group_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSos()
    {
        return $this->hasMany(So::className(), ['customer_id' => 'id']);
    }
    public function getNameAndStreet()
    {
        return $this->name.', '.$this->street;
    }  
    /**
    * @return \yii\db\ActiveQuery
    */
}
