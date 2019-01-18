<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\models\Customer;
use backend\models\User;

/* @var $this yii\web\View */
/* @var $model backend\models\CustomerUpload */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Kundendateien'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customer-upload-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>

        
        <?php
            if (Yii::$app->user->can('change-customerupload')) 
            {
           //     echo Html::a(Yii::t('app', 'Ändern'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']).' ';
            }        
            if (Yii::$app->user->can('delete-customerupload')) 
            {
             /*   echo Html::a('Stornieren', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Bist du sicher, dass du diese Datei löschen willst?',
                        'method' => 'post',
                    ],
                ]).' ';*/
            }

         ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
        'attributes' => [
            'id',
            [
                'attribute'=>'customer_id',
                'value'=>Html::a($model->customer->name, ['customer/view', 'id' => $model->customer_id]),
                'format' => 'raw'
            ],  
            [
                'attribute'=>'file_name',
                'value'=>Html::a($model->file_name, $model->file_path),
                'format' => 'raw'
            ],  
            'title',
            'description:ntext',            
            'file_extension',
            'file_type',

            'file_size',
            [
                'attribute'=>'valid_from',
                'value'=>date('d.m.Y', strtotime($model->valid_from)),
            ],   
            [
                'attribute'=>'valid_to',
                'value'=>date('d.m.Y', strtotime($model->valid_to)),
            ],   
            [
                'attribute'=>'created',
                'value'=>date('d.m.Y H:i:s', strtotime($model->created)),
            ], 
            [
                'attribute'=>'created_by',
                'value'=>$model->createdBy->username,
            ],  
            [
                'attribute'=>'changed',
                'value'=>call_user_func(function($model) {
                            if (!is_null($model->changed)) {
                                return date('d.m.Y H:i:s', strtotime($model->changed));
                            }
                            else {
                                return ' ';
                            }
                        }, $model),
            ], 
            'changedBy.username',
        ],
    ]) ?>

</div>
