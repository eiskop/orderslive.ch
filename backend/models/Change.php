<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "change".
 *
 * @property integer $id
 * @property string $change_object
 * @property string $change_object_id
 * @property integer $change_time
 * @property integer $change_type
 * @property integer $change_reason
 * @property string $measure
 * @property integer $responsible
 * @property integer $duration_min
 * @property string $comment
 * @property string $created
 * @property integer $created_by
 * @property string $updated
 * @property integer $updated_by
 * @property User $updatedBy 
 * @property User $responsible
 * @property User $createdBy 
 */
class Change extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'change';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['change_object', 'change_object_id', 'change_time', 'change_type', 'change_reason', 'duration_min'], 'required'],
            [['change_object'], 'string'],
            [['change_time', 'change_type', 'change_reason',  'duration_min', 'created_by', 'updated_by'], 'integer'],
            [['created', 'updated','measure', 'responsible', 'comment', 'created', 'created_by', 'updated', 'updated_by'], 'safe'],
            [['measure', 'comment'], 'string', 'max' => 255],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']], 
            [['responsible'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['responsible' => 'id']], 
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
            'change_object' => 'Change Object',
            'change_object_id' => 'Change Object ID',
            'change_time' => 'Änderungszeitpunkt',
            'change_type' => 'Art der Anfrage/Änderung',
            'change_reason' => 'Grund/Ursache ',
            'measure' => 'Massnahme',
            'responsible' => 'Verantwortlicher zur Umsetzung',
            'duration_min' => 'Aufwand Min',
            'comment' => 'Kommentar',
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
   public function getResponsible0() 
   { 
       return $this->hasOne(User::className(), ['id' => 'responsible']); 
   } 
 
   /** 
    * @return \yii\db\ActiveQuery 
    */ 
   public function getCreatedBy() 
   { 
       return $this->hasOne(User::className(), ['id' => 'created_by']); 
   } 
}