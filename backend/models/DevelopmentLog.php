<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "development_log".
 *
 * @property integer $id
 * @property integer $priority
 * @property integer $completion_perc
 * @property string $task_name
 * @property string $task_description
 * @property integer $developer_id
 * @property string $estimated_start_time
 * @property string $estimated_completion_time
 * @property integer $approved_by_id
 * @property string $approved_date
 * @property string $created
 * @property integer $created_by
 * @property string $changed
 * @property integer $changed_by
 *
 * @property User $createdBy
 * @property User $developer
 * @property User $approvedBy
 * @property User $changedBy
 * @property DevelopmentLogComments[] $developmentLogComments
 */
class DevelopmentLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'development_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['priority', 'task_name', 'task_description', 'developer_id', 'approved_by_id', 'approved_date'], 'required'],
            [['priority', 'developer_id', 'approved_by_id', 'created_by', 'changed_by'], 'integer'],
            [['task_description'], 'string'],
            [['estimated_start_time', 'estimated_completion_time', 'approved_date', 'created', 'changed', 'created_by', 'changed_by', 'estimated_start_time', 'estimated_completion_time', 'completion_perc'], 'safe'],
            [['task_name'], 'string', 'max' => 255],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['developer_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['developer_id' => 'id']],
            [['approved_by_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['approved_by_id' => 'id']],
            [['changed_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['changed_by' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'priority' => Yii::t('app', 'Priority'),
            'completion_perc' => Yii::t('app', 'Completion %'),
            'task_name' => Yii::t('app', 'Task Name'),
            'task_description' => Yii::t('app', 'Task Description'),
            'developer_id' => Yii::t('app', 'Developer ID'),
            'estimated_start_time' => Yii::t('app', 'Estimated Start Time'),
            'estimated_completion_time' => Yii::t('app', 'Estimated Completion Time'),
            'approved_by_id' => Yii::t('app', 'Approved By ID'),
            'approved_date' => Yii::t('app', 'Approved Date'),
            'created' => Yii::t('app', 'Created'),
            'created_by' => Yii::t('app', 'Created By'),
            'changed' => Yii::t('app', 'Changed'),
            'changed_by' => Yii::t('app', 'Changed By'),
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
    public function getDeveloper()
    {
        return $this->hasOne(User::className(), ['id' => 'developer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApprovedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'approved_by_id']);
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
    public function getDevelopmentLogComments()
    {
        return $this->hasMany(DevelopmentLogComments::className(), ['development_log_id' => 'id']);
    }
}
