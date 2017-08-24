<?php

namespace backend\models;

use Yii;
use common\models\User;

/**
 * This is the model class for table "so".
 *
 * @property integer $id
 * @property integer $product_group_id
 * @property integer $customer_id
 * @property string $customer_order_no
 * @property string $confirmation_no
 * @property string $surface
 * @property integer $product_type
 * @property integer $prio1 
 * @property integer $status_id
 * @property integer $qty 
 * @property double $value
 * @property string $order_received
 * @property string $customer_priority_id
 * @property integer $days_to_process
 * @property integer $assigned_to
 * @property integer $created_by
 * @property string $created
 * @property integer $updated_by
 * @property string $updated
 * @property text $comments
 * @property int $deadline
 *
 * @property CustomerPriority $customerPriority
 * @property Customer $customer
 * @property User $createdBy
 * @property User $updatedBy
 * @property ProductGroup $productGroup
 * @property SoStatus $soStatus
 * @property SoItem[] $soItems
 */
class So extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'so';
    }

    /**
     * @inheritdoc
     */

    public function rules()
    {
        return [
            [['customer_id', 'customer_order_no', 'surface', 'qty', 'status_id', 'order_received'], 'required'],
            [['id', 'deadline', 'prio1', 'product_type'], 'integer'],
            [['value'], 'number'],
            [['product_group_id', 'customer_id', 'created_by', 'updated_by', 'assigned_to', 'order_received', 'created', 'updated', 'confirmation_no', 'value', 'created_by', 'created', 'updated_by', 'updated', 'customer_priority_id', 'deadline', 'offer_no', 'product_type'], 'safe'],
            [['customer_order_no', 'confirmation_no', 'surface'], 'string', 'max' => 30],
            [['comments'], 'string'],
            //[['customer_priority_id'], 'string', 'max' => 1]
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
           // 'id' => 'ID',
            'product_group_id' => 'Produkt',
            'customer_id' => 'Kunde',
            'customer_order_no' => 'Kommission',
            'confirmation_no' => 'AB',
            'offer_no' => 'Offertnr.',
            'customer_priority_id' => 'Prio',
            'prio1' => 'Prio. 1.',
            'surface' => 'Oberfläche',
            'product_type' => 'Artikel',
            'qty' => 'Menge',
            'status_id' => 'Status',
            'value' => 'Wert',
            'order_received' => 'Eingang',
            'deadline' => 'Termin für Erfasung',
            'comments' => 'Kommentar',
            'assigned_to' => 'Zugeteilt an',
            'created_by' => 'Ersteller',
            'created' => 'Erstellt am',
            'updated_by' => 'Geändert von',
            'updated' => 'Geändert am',
            'days_to_process' => 'Tage zum bearbeiten',
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
    public function getAssignedTo()
    {
        return $this->hasOne(User::className(), ['id' => 'assigned_to']);
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
    public function getSoItems()
    {
        return $this->hasMany(SoItem::className(), ['so_id' => 'id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSoStatus()
    {
        return $this->hasOne(SoStatus::className(), ['id' => 'status_id']);
    }    
    public function getDLZ()
    {   
        if ($this->status_id == 3) {// Order complete
            $a = (strtotime($this->updated)-strtotime($this->order_received))/(60*60*24);
            $a =  number_format($a, 1, '.', '');
            return $a;        
        }
    }        
}
