<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "offer_status".
 *
 * @property integer $id
 * @property string $name
 * @property integer $active
 * @property integer $sorting
 * @property string $created
 * @property string $changed
 *
 * @property Offer[] $offers
 */
class OfferStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'offer_status';
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
    public function getOffers()
    {
        return $this->hasMany(Offer::className(), ['status_id' => 'id']);
    }
}
