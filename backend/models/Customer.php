<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

/**
 * This is the model class for table "customer".
 *
 * @property integer $id
 * @property string $name
 * @property integer $customer_group_id
 * @property string $customer_priority_id
 * @property string $contact
 * @property string $street
 * @property string $zip_code
 * @property string $region
 * @property string $city
 * @property string $province
 * @property string $fax_no
 * @property string $tel_no
 * @property integer $active
 * @property integer $created
 * @property integer $created_by
 * @property string $updated
 * @property integer $updated_by
 *
 * @property CustomerPriority $customerPriority
 * @property User $createdBy
 * @property User $updatedBy
 * @property CustomerGroup $customerGroup
 * @property So[] $sos
 * @property UploadedFile[] $uploadedFiles 
 */
class Customer extends \yii\db\ActiveRecord
{
    public $uploadedFiles;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'customer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'street', 'zip_code', 'province'], 'required'],
            [['customer_group_id', 'created', 'created_by', 'updated_by', 'active'], 'integer'],
            [['updated','customer_group_id', 'customer_priority_id', 'fax_no', 'tel_no', 'city', 'created', 'created_by', 'updated_by', 'region', 'uploadedFiles'], 'safe'],
            [['name', 'contact', 'city', 'province', 'tel_no'], 'string', 'max' => 100],
            [['customer_priority_id'], 'string', 'max' => 1],
            [['street'], 'string', 'max' => 255],
            [['zip_code'], 'string', 'max' => 20],
            [['fax_no'], 'string', 'max' => 40],
            [['uploadedFiles'], 'file', 'skipOnEmpty' => true, 'maxFiles' => 10],
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
            'customer_group_id' => 'Gruppe',
            'customer_priority_id' => 'Kunden Priorität',
            'contact' => 'Kontaktperson',
            'street' => 'Strasse',
            'zip_code' => 'PLZ',
            'city' => 'Stadt',
            'province' => 'Canton',
            'fax_no' => 'Fax No',
            'tel_no' => 'Tel No',
            'region' => 'Region',            
            'created' => Yii::t('app', 'Erstellt'),
            'created_by' => Yii::t('app', 'Ersteller'),
            'updated' => Yii::t('app', 'Anpassung am'),
            'updated_by' => Yii::t('app', 'Angepasst von'),
            'uploadedFiles' => 'Dokumente hinzufügen',
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
    public function getCustomerGroup()
    {
        return $this->hasOne(CustomerGroup::className(), ['id' => 'customer_group_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSos()
    {
        return $this->hasMany(So::className(), ['customer_id' => 'id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */

    public function getOffers()
    {
        return $this->hasMany(Offer::className(), ['customer_id' => 'id']);
    }
     /**
     * @return \yii\db\ActiveQuery
     */
    public function getNameAndStreet()
    {
        return $this->name.', '.$this->street;
    }  
    /**
    * @return \yii\db\ActiveQuery
    */
    public function getCustomerDiscount()
    {
        return $this->hasMany(CustomerDiscount::className(), ['customer_id' => 'id']);
    }    

    /**
     * @return \yii\db\ActiveQuery
    * @var UploadedFile[]
    * @var $offer_id
     */
    public function upload()
    {
        //$model = $this->findModel($this->id);
        function fixDb($str) {
            return '"'.htmlentities($str).'"';
        }
        $uploadDir = 'uploads/customer/'.$this->id;
        $this->uploadedFiles = UploadedFile::getInstances($this, 'uploadedFiles');
        $valid = $this->validate();
        
    //  echo 'file upload model  is called';
        if ($valid) {

            foreach ($this->uploadedFiles as $file) {
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, octdec('0775'), true);
                }
       //         echo '<pre>', var_dump($file), '</pre>';
                    
                $file_name = $uploadDir.'/'.$file->name;
                $pinfo = pathinfo($file_name);
    //                      echo '<pre>', var_dump($pinfo);
    //                      exit;
                while(file_exists($file_name)) {
                    $file_name = $uploadDir.'/'.$pinfo['filename'].'_'.date('d-m-Y_H-i-s', time()).'.'.$pinfo['extension'];
                    //$file_name.'<br>';
                }
                $pinfo2 = pathinfo($file_name);
                if ($file->saveAs($file_name)) {
                    $sql = 'INSERT INTO customer_upload SET '.
                            'customer_id='.fixDb($this->id).', '.
                            'file_path='.fixDb($file_name).', '.
                            'file_name='.fixDb($pinfo2['filename']).', '.
                            'file_extension='.fixDb($file->extension).', '.
                            'file_type='.fixDb($file->type).', '.
                            'file_size='.fixDb($file->size).', '.
                            'created=NOW(), '.
                            'created_by='.fixDb(Yii::$app->user->id);
//echo $sql;
                    $command = Yii::$app->db->createCommand($sql);
                    if (!$command->execute()) {
                        unlink($uploadDir.'/'.$file->baseName.'.'.$file->extension);
                    }
                    
                }

            }

        //  echo 'file upload model all files processed';
      //    exit;
            return true;
        }
        else {
            return false;
        }
    }    

    /**
     * Finds the Offer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Offer the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Customer::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }




}

