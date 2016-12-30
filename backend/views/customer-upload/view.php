<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\models\Customer;
use backend\models\User;

/* @var $this yii\web\View */
/* @var $model backend\models\CustomerUpload */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Customer Uploads'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customer-upload-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute'=>'customer_id',
                'value'=>$model->customer->name,
            ],  
            'file_path',
            'file_name',
            'file_extension',
            'file_type',
            'title',
            'description:ntext',
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
                'value'=>date('d.m.Y H:i:s', strtotime($model->changed)),
            ], 
            [
                'attribute'=>'changed_by',
                'value'=>$model->changedBy->username,
            ],  
        ],
    ]) ?>

</div>
