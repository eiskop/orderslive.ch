<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "offer_item_type".
 *
 * @property integer $id
 * @property string $name
 * @property integer $active
 * @property integer $sorting
 * @property string $created
 * @property string $changed
 *
 * @property OfferItem[] $offerItems
 */
class OfferItemType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'offer_item_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'active', 'sorting', 'created'], 'required'],
            [['active', 'sorting'], 'integer'],
            [['created', 'changed'], 'safe'],
            [['name'], 'string', 'max' => 20],
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
            'sorting' => 'Sorting',
            'created' => 'Created',
            'changed' => 'Changed',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOfferItems()
    {
        return $this->hasMany(OfferItem::className(), ['offer_item_type_id' => 'id']);
    }
    public function getCustomerDiscount($customer_id)
    {
        if ($customer_id == FALSE) {
            return $this->hasMany(CustomerDiscount::className(), ['offer_item_type_id' => 'id']);    
        }
        else {
            if (is_numeric($customer_id)) {
                return $this->hasOne(CustomerDiscount::className(), ['offer_item_type_id' => 'id'])->onCondition(['customer_id' => $customer_id]);           
            }
            else {
                return $this->hasOne(CustomerDiscount::className(), ['offer_item_type_id' => 'id'])->onCondition(['customer_id' => 0]);              
            }
        }

        
    }    
}
