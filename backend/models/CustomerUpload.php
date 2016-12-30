<?php


namespace backend\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;
/**
 * This is the model class for table "customer_upload".
 *
 * @property integer $id
 * @property integer $customer_id
 * @property string $file_path
 * @property string $file_name
 * @property string $file_extension
 * @property string $file_type
 * @property string $title
 * @property string $description
 * @property integer $file_size
 * @property string $valid_from
 * @property string $valid_to
 * @property string $created
 * @property integer $created_by
 * @property string $changed
 * @property integer $changed_by
 *
 * @property User $createdBy
 * @property User $changedBy
 * @property Customer $customer
 * @property UploadedFiles[] $uploadedFiles 
 */
class CustomerUpload extends \yii\db\ActiveRecord
{
 
    public $uploadedFiles;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'customer_upload';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'valid_from', 'valid_to'], 'required'],
            [['customer_id', 'file_size', 'created_by', 'changed_by'], 'integer'],
            [['description', 'title'], 'string'],
            [['valid_from', 'valid_to', 'created', 'changed', 'uploadedFiles', 'created', 'created_by', 'created_by', 'changed_by', 'file_size', 'file_path', 'file_name', 'file_extension', 'file_type', 'title', 'description'], 'safe'],
            [['file_path', 'file_name'], 'string', 'max' => 255],
            [['file_extension'], 'string', 'max' => 255],
            [['file_type', 'title'], 'string', 'max' => 255],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['changed_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['changed_by' => 'id']],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['customer_id' => 'id']],
            [['uploadedFiles'], 'file', 'skipOnEmpty' => true, 'maxFiles' => 10],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'customer_id' => Yii::t('app', 'Kunde'),
            'file_path' => Yii::t('app', 'Datei Pfad'),
            'file_name' => Yii::t('app', 'Datei Name'),
            'file_extension' => Yii::t('app', 'Dateierweiterung'),
            'file_type' => Yii::t('app', 'Datei Typ'),
            'title' => Yii::t('app', 'Titel'),
            'description' => Yii::t('app', 'Beschreibung'),
            'file_size' => Yii::t('app', 'Datei Grösse'),
            'valid_from' => Yii::t('app', 'Gültig ab'),
            'valid_to' => Yii::t('app', 'Gültig bis'),
            'created' => Yii::t('app', 'Erstellt'),
            'created_by' => Yii::t('app', 'Ersteller'),
            'changed' => Yii::t('app', 'Anpassung am'),
            'changed_by' => Yii::t('app', 'Angepasst von'),
            'uploadedFiles' => Yii::t('app', 'Dokumente hinzufügen'),            
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
    public function getCustomer()
    {
        return $this->hasOne(Customer::className(), ['id' => 'customer_id']);
    }
}
