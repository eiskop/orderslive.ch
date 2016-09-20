<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "offer_upload".
 *
 * @property integer $id
 * @property integer $offer_id
 * @property string $file_path
 * @property string $file_name
 * @property string $file_extension
 * @property string $file_type
 * @property string $title
 * @property string $description
 * @property integer $file_size
 * @property string $created
 * @property integer $created_by
 * @property string $changed
 * @property integer $changed_by
 *
 * @property User $createdBy
 * @property User $changedBy
 * @property Offer $offer
 */
class OfferUpload extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'offer_upload';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[], 'required'],
            [['offer_id', 'file_size', 'created_by', 'changed_by'], 'integer'],
            [['description'], 'string'],
            [['offer_id', 'file_path', 'file_name', 'file_extension', 'file_type', 'title', 'description', 'file_size', 'created', 'created_by'], 'safe'],
            [['file_path', 'file_name'], 'string', 'max' => 255],
            [['file_extension'], 'string', 'max' => 10],
            [['file_type', 'title'], 'string', 'max' => 100],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['changed_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['changed_by' => 'id']],
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
            'file_path' => Yii::t('app', 'Datei Pfad'),
            'file_name' => Yii::t('app', 'Datei Name'),
            'file_extension' => Yii::t('app', 'Datei'),
            'file_type' => Yii::t('app', 'Datei Tüp'),
            'title' => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Beschreibung'),
            'file_size' => Yii::t('app', 'Grösse'),
            'created_by' => Yii::t('app', 'Ersteller'),
            'created' => Yii::t('app', 'Erstellt am'),
            'updated_by' => Yii::t('app', 'Geändert von'),
            'updated' => Yii::t('app', 'Geändert am'),
        ];
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOffer()
    {
        return $this->hasOne(Offer::className(), ['id' => 'offer_id']);
    }
}
