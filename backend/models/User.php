<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $first_name
 * @property string $last_name
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $status
 * @property integer $product_group_id
 * @property integer $active 
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property AuthAssignment[] $authAssignments
 * @property AuthItem[] $itemNames
 * @property Customer[] $customers
 * @property Customer[] $customers0
 * @property CustomerGroup[] $customerGroups
 * @property CustomerGroup[] $customerGroups0
 * @property Offer[] $offers
 * @property Offer[] $offers0
 * @property Offer[] $offers1
 * @property Offer[] $offers2
 * @property OfferItem[] $offerItems
 * @property OfferItem[] $offerItems0
 * @property ProductGroup[] $productGroups
 * @property ProductGroup[] $productGroups0
 * @property SalesItem[] $salesItems
 * @property SalesItem[] $salesItems0
 * @property So[] $sos
 * @property So[] $sos0
 * @property SoItem[] $soItems
 * @property SoItem[] $soItems0
 * @property ProductGroup $productGroup
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['first_name', 'last_name', 'username', 'auth_key', 'password_hash', 'email', 'product_group_id', 'created_at', 'updated_at'], 'required'],
            [['status', 'product_group_id', 'created_at', 'updated_at'], 'integer'],
            [['first_name', 'last_name'], 'string', 'max' => 50],
            [['username', 'password_hash', 'password_reset_token', 'email'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['password_reset_token'], 'unique'],
            [['product_group_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductGroup::className(), 'targetAttribute' => ['product_group_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'username' => 'Username',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'status' => 'Status',
            'product_group_id' => 'Product Group ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthAssignments()
    {
        return $this->hasMany(AuthAssignment::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemNames()
    {
        return $this->hasMany(AuthItem::className(), ['name' => 'item_name'])->viaTable('auth_assignment', ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomers()
    {
        return $this->hasMany(Customer::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomers0()
    {
        return $this->hasMany(Customer::className(), ['updated_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerGroups()
    {
        return $this->hasMany(CustomerGroup::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerGroups0()
    {
        return $this->hasMany(CustomerGroup::className(), ['updated_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOffers()
    {
        return $this->hasMany(Offer::className(), ['updated_by' => 'id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOffers0()
    {
        return $this->hasMany(Offer::className(), ['processed_by_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOffers1()
    {
        return $this->hasMany(Offer::className(), ['followup_by_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOffers2()
    {
        return $this->hasMany(Offer::className(), ['created_by' => 'id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOffersCreatedCount()
    {
        return $this->hasMany(Offer::className(), ['created_by' => 'id'])->count();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOfferItems()
    {
        return $this->hasMany(OfferItem::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOfferItems0()
    {
        return $this->hasMany(OfferItem::className(), ['changed_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductGroups()
    {
        return $this->hasMany(ProductGroup::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductGroups0()
    {
        return $this->hasMany(ProductGroup::className(), ['updated_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSalesItems()
    {
        return $this->hasMany(SalesItem::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSalesItems0()
    {
        return $this->hasMany(SalesItem::className(), ['changed_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSos()
    {
        return $this->hasMany(So::className(), ['created_by' => 'id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSosCreatedCount()
    {
        return $this->hasMany(So::className(), ['created_by' => 'id'])->count();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSos0()
    {
        return $this->hasMany(So::className(), ['updated_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSoItems()
    {
        return $this->hasMany(SoItem::className(), ['changed_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSoItems0()
    {
        return $this->hasMany(SoItem::className(), ['created_by' => 'id']);
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
    public function getOfferFinishedCount() // 
    {
        //TopicTag::find()->where(['tag_id' => $this->id])->count();
        $count = (new \yii\db\Query())
        ->select('count(*)')
        ->from('offer')
        ->where(['assigned_to' => $this->id])->andWhere(['status_id' => 3])
        ->scalar();
       return $count;
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOfferInProgressCount() // 
    {
        //TopicTag::find()->where(['tag_id' => $this->id])->count();
        $count = (new \yii\db\Query())
        ->select('count(*)')
        ->from('offer')
        ->where(['assigned_to' => $this->id])
        ->andWhere(['or', 
            [
                'status_id'=> [1, 2, 6, 7],
            ]
        ])          
        ->scalar();
       return $count;
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSoFinishedCount() // 
    {
        //TopicTag::find()->where(['tag_id' => $this->id])->count();
        $count = (new \yii\db\Query())
        ->select('count(*)')
        ->from('so')
        ->where(['assigned_to' => $this->id])->andWhere(['status_id' => 3])
        ->scalar();
       return $count;
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSoInProgressCount() // 
    {
        //TopicTag::find()->where(['tag_id' => $this->id])->count();
        $count = (new \yii\db\Query())
        ->select('count(*)')
        ->from('so')
        ->where(['assigned_to' => $this->id])
        ->andWhere(['or', 
            [
                'status_id'=> [1, 2],
            ]
        ])           
        ->scalar();
       return $count;
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSoCreatedCount() // 
    {
        //TopicTag::find()->where(['tag_id' => $this->id])->count();
        $count = (new \yii\db\Query())
        ->select('count(*)')
        ->from('so')
        ->where(['created_by' => $this->id])
        ->andWhere(['or', 
            [
                'status_id'=> [1, 2, 3],
            ]
        ])
        ->scalar();
       return $count;
    }
}
