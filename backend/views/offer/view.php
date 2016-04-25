<?php

use yii\helpers\Url;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use backend\models\Offer;
use backend\models\OfferStatus;
use backend\models\OfferItem;
use backend\models\OfferItemType;


/* @var $this yii\web\View */
/* @var $model backend\models\Offer */
/* @var $searchModel backend\models\OfferSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Offers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="offer-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'offer_wir_id',
            [
                'attribute'=>'processed_by_id',
                'value'=>$model->processedBy->username,
            ],             
            [
                'attribute'=>'followup_by_id',
                'value'=>$model->followupBy->username,
            ],   
            [
                'attribute'=>'customer_id',
                'value'=>$model->customer->name,
            ],            
            'customer_contact',
            [
                'attribute'=>'customer_id_2',
                'value'=>$model->customer->name,
            ],               
            'customer_order_no',
            'confirmation_no',
            'prio1',
            [
                'attribute'=>'status_id',
                'value'=>$model->status->name,
            ],             
            [
                'attribute'=>'offer_received',
                'value'=>date('d.m.Y', strtotime($model->offer_received)),
            ],            
            'customer_priority_id',             
            [
                'attribute'=>'qty',
                'value'=>number_format(($model->qty), 0, '.', ' '),
            ],             
            [
                'attribute'=>'value',
                'value'=>number_format(($model->value), 2, '.', ' '),
            ],             
            [
                'attribute'=>'value_net',
                'value'=>number_format(($model->value_net), 2, '.', ' '),
            ], 
            [
                'attribute'=>'Rabatt %',
                'value'=>number_format((100-$model->value_net/$model->value*100), 2, '.', ''),
            ],                           
            'days_to_process',
            [
                'attribute'=>'Termin für Erfassung',
                'value'=>date('d.m.Y', $model->deadline),
            ],               
            'comments:ntext',
            [
                'attribute'=>'created',
                'value'=>date('d.m.Y H:i:s', strtotime($model->created)),
            ],
            [
                'attribute'=>'created_by',
                'value'=>$model->createdBy->username,
            ],
            'updatedBy.username',
            [
                'attribute'=>'updated',
                'value'=>date('d.m.Y H:i:s', strtotime($model->updated)),
            ],          
        ],
    ]) ?>

        <h2>Positionen für Offerte #<?= $model->id ?></h2>
        <hr />
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'pager' => [
                'firstPageLabel' => '<<<',
                'lastPageLabel' => '>>>',
            ],
            'layout'=>"{pager} {summary} {items} {pager}",

            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute'=>'offer_item_type_id',
                    'value'=>'offerItemType.name',
                    'contentOptions' => ['style' => 'width:300px'],
                ],          
                'qty',
                'value',
                'value_total',
                'project_discount_perc',
                'value_net',                
                'value_total_net',
                // 'project_discount_perc',
                // 'created_by',
                // 'created',
                // 'changed_by',
                // 'changed',
            ],
        ]); ?>

</div>
