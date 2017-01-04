<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\CustomerDiscount */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Customer Discounts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="customer-discount-view">

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
        'formatter' => [
           'class' => 'yii\i18n\Formatter',
           'dateFormat' => 'dd.MM.Y',
           'datetimeFormat' => 'dd.MM.Y H:i:s',
           'timeFormat' => 'H:i:s', 
        ],
        'attributes' => [
            'id',
            [
                'attribute' => 'customer_id',
                'value' => $model->customer->name,
            ],
            [
                'attribute' => 'offer_item_type_id',
                'value' => $model->offerItemType->name,
            ],            
            'base_discount_perc',
            [
                'attribute' => 'valid_from',
                'value' => $model->valid_from,
                'format' => 'date',
            ],
            [
                'attribute' => 'created',
                'value' => $model->created,
                'format' => 'datetime',
            ],            
            [
                'attribute' => 'created_by',
                'value' => $model->createdBy->first_name.' '.$model->createdBy->last_name,
            ],
            [
                'attribute' => 'updated',
                'value' => $model->updated,
                'format' => 'datetime',
            ], 
            [
                'attribute' => 'updated_by',
                'value' => $model->updatedBy->first_name.' '.$model->updatedBy->last_name,
            ],
            'approved',
            [
                'attribute' => 'approved_by',
                'value' => $model->approvedBy->first_name.' '.$model->approvedBy->last_name,
            ],
            [
                'attribute'=>'active',
                'format'=> 'boolean',
            ],
        ],
    ]) ?>

</div>
