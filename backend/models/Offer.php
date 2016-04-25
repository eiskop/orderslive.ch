<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "offer".
 *
 * @property integer $id
 * @property string $offer_wir_id
 * @property integer $processed_by_id
 * @property integer $followup_by_id
 * @property integer $product_group_id
 * @property integer $customer_id
 * @property string $customer_contact
 * @property integer $customer_id_2
 * @property string $customer_order_no
 * @property string $confirmation_no
 * @property integer $qty
 * @property integer $prio1
 * @property integer $status_id
 * @property double $value
 * @property string $offer_received
 * @property string $customer_priority_id
 * @property integer $days_to_process
 * @property integer $deadline
 * @property string $comments
 * @property integer $created_by
 * @property string $created
 * @property integer $updated_by
 * @property string $updated
 *
 * @property User $updatedBy
 * @property User $processedBy
 * @property User $followupBy
 * @property ProductGroup $productGroup
 * @property Customer $customer
 * @property OfferStatus $status
 * @property CustomerPriority $customerPriority
 * @property User $createdBy
 * @property OfferItem[] $offerItems
 */
class Offer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'offer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['processed_by_id', 'customer_id', 'customer_contact', 'customer_order_no', 'status_id', 'offer_received'], 'required'],
            [['processed_by_id', 'followup_by_id', 'product_group_id', 'prio1', 'status_id', 'days_to_process', 'deadline', 'created_by', 'updated_by'], 'integer'],
            [['value'], 'number'],
            [['offer_received','customer_id', 'customer_id_2', 'created', 'updated'], 'safe'],
            [['comments', 'qty'], 'string'],
            [['offer_wir_id'], 'string', 'max' => 100],
            [['customer_contact'], 'string', 'max' => 150],
            [['customer_order_no', 'confirmation_no'], 'string', 'max' => 30],
            [['customer_priority_id'], 'string', 'max' => 1],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
            [['processed_by_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['processed_by_id' => 'id']],
            [['followup_by_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['followup_by_id' => 'id']],
            [['product_group_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductGroup::className(), 'targetAttribute' => ['product_group_id' => 'id']],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['customer_id' => 'id']],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => OfferStatus::className(), 'targetAttribute' => ['status_id' => 'id']],
            [['customer_priority_id'], 'exist', 'skipOnError' => true, 'targetClass' => CustomerPriority::className(), 'targetAttribute' => ['customer_priority_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'offer_wir_id' => 'Offer Wirus ID',
            'processed_by_id' => 'Sachbearbeiter',
            'followup_by_id' => 'Nachverfolger',
            'product_group_id' => 'Product Group ID',
            'customer_id' => 'Kunde',
            'customer_contact' => 'Kunden Kontakt',
            'customer_id_2' => 'Schreiner',
            'customer_order_no' => 'Komission',
            'confirmation_no' => 'AB',
            'qty' => 'Menge',
            'prio1' => 'Prio. 1',
            'status_id' => 'Status',
            'value' => 'Wert (CHF)',
            'value_net' => 'Netto Wert (CHF)',
            'offer_received' => 'Eingang',
            'customer_priority_id' => 'Prio',
            'days_to_process' => 'Max Bearbeitungszeit',
            'deadline' => 'Termin',
            'comments' => 'Kommentar',
            'created_by' => 'Ersteller',
            'created' => 'Erstellt am',
            'updated_by' => 'Geändert von',
            'updated' => 'Geändert am',
        ];
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
    public function getProcessedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'processed_by_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFollowupBy()
    {
        return $this->hasOne(User::className(), ['id' => 'followup_by_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductGroup()
    {
        return $this->hasOne(ProductGroup::className(), ['id' => 'product_group_id']);
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
    public function getStatus()
    {
        return $this->hasOne(OfferStatus::className(), ['id' => 'status_id']);
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
    public function getOfferItems()
    {
        return $this->hasMany(OfferItem::className(), ['offer_id' => 'id']);
    }
}
