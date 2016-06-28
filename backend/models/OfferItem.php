<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "offer_item".
 *
 * @property integer $id
 * @property integer $offer_id
 * @property integer $offer_item_type_id
 * @property double $qty
 * @property double $value
 * @property double $value_net
 * @property double $value_net_base_discount
 * @property double $order_line_net_value 
 * @property double $project_discount_perc
 * @property integer $created_by
 * @property string $created
 * @property integer $changed_by
 * @property string $changed
 *
 * @property OfferItemType $offerItemType
 * @property Offer $offer
 * @property User $createdBy
 * @property User $changedBy
 */
class OfferItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'offer_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['offer_item_type_id', 'qty'], 'required'],
            [['offer_id', 'created_by', 'changed_by'], 'integer'],
            [['qty', 'value_total','project_discount_perc', 'value_net', 'order_line_net_value'], 'number'],
            [['created', 'changed', 'value'], 'safe'],
            [['offer_item_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => OfferItemType::className(), 'targetAttribute' => ['offer_item_type_id' => 'id']],
            [['offer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Offer::className(), 'targetAttribute' => ['offer_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['changed_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['changed_by' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'offer_id' => 'Offertnr.',
            'offer_item_type_id' => 'Modell',
            'qty' => 'Stk.',
            'value' => 'Wert (CHF/stk)',
            'project_discount_perc' => 'Projekt Rabatt (%)',
            'base_discount_perc' => 'Grundrabatt (%)',
            'value_net' => 'Netto Wert (CHF)',
            'value_total' => 'Wert Total (CHF)',
            'value_total_net' => 'Netto Wert GR (CHF)',
            'order_line_net_value' => 'Wert Total Netto (CHF)',
            'created_by' => 'Ersteller',
            'created' => 'Erstellt am',
            'changed_by' => 'Geändert von',
            'changed' => 'Geändert am',
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOfferItemType()
    {
        return $this->hasOne(OfferItemType::className(), ['id' => 'offer_item_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOffer()
    {
        return $this->hasOne(Offer::className(), ['id' => 'offer_id']);
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
}
