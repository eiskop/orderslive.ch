<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "development_log_comment".
 *
 * @property integer $id
 * @property integer $development_log_id
 * @property string $comment
 * @property string $created
 * @property integer $created_by
 * @property string $changed
 * @property integer $changed_by
 *
 * @property User $changedBy
 * @property DevelopmentLog $developmentLog
 * @property User $createdBy
 */
class DevelopmentLogComment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'development_log_comment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['development_log_id', 'comment', 'created', 'created_by', 'changed_by'], 'required'],
            [['development_log_id', 'created_by', 'changed_by'], 'integer'],
            [['comment'], 'string'],
            [['created', 'changed'], 'safe'],
            [['changed_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['changed_by' => 'id']],
            [['development_log_id'], 'exist', 'skipOnError' => true, 'targetClass' => DevelopmentLog::className(), 'targetAttribute' => ['development_log_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'development_log_id' => Yii::t('app', 'Development Log ID'),
            'comment' => Yii::t('app', 'Comment'),
            'created' => Yii::t('app', 'Created'),
            'created_by' => Yii::t('app', 'Created By'),
            'changed' => Yii::t('app', 'Changed'),
            'changed_by' => Yii::t('app', 'Changed By'),
        ];
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
    public function getDevelopmentLog()
    {
        return $this->hasOne(DevelopmentLog::className(), ['id' => 'development_log_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }
}
