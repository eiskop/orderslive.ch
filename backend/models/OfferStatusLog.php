<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "offer_status_log".
 *
 * @property integer $id
 * @property integer $offer_id
 * @property integer $followup_by_id
 * @property integer $customer_id
 * @property string $customer_contact
 * @property string $contact_date
 * @property string $topics
 * @property string $next_steps
 * @property string $next_followup_date
 * @property integer $status_id
 * @property string $comments
 * @property integer $assigned_to
 * @property integer $created_by
 * @property string $created
 * @property integer $updated_by
 * @property string $updated
 *
 * @property User $updatedBy
 * @property User $followupBy
 * @property Customer $customer
 * @property Offer $status
 * @property User $createdBy
 * @property Offer $offer
 */
class OfferStatusLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'offer_status_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['contact_date', 'topics', 'followup_by_id','customer_contact'], 'required'],
            [['contact_date', 'next_followup_date', 'created', 'updated', 'offer_id', 'customer_contact', 'status_id', 'comments', 'created', 'followup_by_id','status_id', 'assigned_to', 'offer_id', 'created_by', 'updated_by', 'customer_id','next_steps', 'next_followup_date'], 'safe'],
            [['topics', 'next_steps', 'comments'], 'string'],
            [['customer_contact'], 'string', 'max' => 255],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
            [['followup_by_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['followup_by_id' => 'id']],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['customer_id' => 'id']],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => OfferStatus::className(), 'targetAttribute' => ['status_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['offer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Offer::className(), 'targetAttribute' => ['offer_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'offer_id' => Yii::t('app', 'Offer ID'),
            'followup_by_id' => Yii::t('app', 'Nachverfolger'),
            'customer_id' => Yii::t('app', 'Kunde'),
            'customer_contact' => Yii::t('app', 'Nachfass Ansprechpartner'),
            'contact_date' => Yii::t('app', 'Nachfass-datum'),
            'topics' => Yii::t('app', 'Besprechungspunkte'),
            'next_steps' => Yii::t('app', 'Weiteres vorgehen'),
            'next_followup_date' => Yii::t('app', 'Nächster Nachfass'),
            'status_id' => Yii::t('app', 'Status ID'),
            'comments' => Yii::t('app', 'Kommentare'),
            'assigned_to' => Yii::t('app', 'Zugeteilt an'),
            'created_by' => Yii::t('app', 'Ersteller'),
            'created' => Yii::t('app', 'Erstellt am'),
            'updated_by' => Yii::t('app', 'Geändert von'),
            'updated' => Yii::t('app', 'Geändert am'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFollowupBy()
    {
        //return $this->hasOne(User::className(), ['id' => 'followup_by_id']);
        return $this->hasOne(User::className(), ['id' => 'followup_by_id'])
        ->from(['user_followup_by_id' => User::tableName()]);        
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAssignedTo()
    {
        //return $this->hasOne(User::className(), ['id' => 'assigned_to']);
       return $this->hasOne(User::className(), ['id' => 'assigned_to'])
        ->from(['user_assigned_to' => User::tableName()]);          
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
    public function getOffer()
    {
        return $this->hasOne(Offer::className(), ['status_id' => 'offer_id']);
    }
}
