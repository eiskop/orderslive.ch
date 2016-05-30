<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "customer_discount".
 *
 * @property integer $id
 * @property integer $customer_id
 * @property integer $offer_item_type_id
 * @property double $base_discount_perc
 * @property string $valid_from
 * @property string $created
 * @property integer $created_by
 * @property string $updated
 * @property integer $updated_by
 * @property string $approved
 * @property integer $approved_by
 * @property integer $active
 *
 * @property OfferItem $offerItemType
 * @property Customer $customer
 * @property User $createdBy
 * @property User $updatedBy
 * @property User $approvedBy
 */
class CustomerDiscount extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'customer_discount';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'offer_item_type_id', 'base_discount_perc', 'valid_from', 'created', 'created_by', 'updated_by', 'approved', 'approved_by', 'active'], 'required'],
            [['customer_id', 'offer_item_type_id', 'created_by', 'updated_by', 'approved_by', 'active'], 'integer'],
            [['base_discount_perc'], 'double'],
            [['valid_from', 'created', 'updated', 'approved'], 'safe'],
            [['offer_item_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => OfferItem::className(), 'targetAttribute' => ['offer_item_type_id' => 'offer_item_type_id']],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['customer_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
            [['approved_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['approved_by' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'customer_id' => Yii::t('app', 'Customer ID'),
            'offer_item_type_id' => Yii::t('app', 'Offer Item Type ID'),
            'base_discount_perc' => Yii::t('app', 'Discount'),
            'valid_from' => Yii::t('app', 'Valid From'),
            'created' => Yii::t('app', 'Created'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated' => Yii::t('app', 'Updated'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'approved' => Yii::t('app', 'Approved'),
            'approved_by' => Yii::t('app', 'Approved By'),
            'active' => Yii::t('app', 'Active'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOfferItemType()
    {
        return $this->hasOne(OfferItem::className(), ['offer_item_type_id' => 'offer_item_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(Customer::className(), ['id' => 'customer_id']);
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
    public function getApprovedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'approved_by']);
    }
}
