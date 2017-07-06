<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\models\Offer;
use backend\models\OfferStatus;

/* @var $this yii\web\View */
/* @var $model backend\models\OfferStatusLog */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Offer Status Logs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="offer-status-log-view">

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
            'offer_no',
            [
                'attribute'=>'followup_by_id',
                'value'=>($model->followupBy->first_name.' '.$model->followupBy->last_name),
            ],
            [
                'attribute'=>'assigned_to',
                'value'=>($model->assignedTo->first_name.' '.$model->assignedTo->last_name),
            ], 
            [
                'attribute'=>'status_id',
                'value'=>$model->status->name,
            ],             
            'customer.name',
            'customer_contact',
            'contact_date:date',
            'topics:ntext',
            'next_steps:ntext',
            'next_followup_date:date',
            'comments:ntext',
            [
                'attribute'=>'created_by',
                'value'=>($model->followupBy->first_name.' '.$model->followupBy->last_name),
            ], 
            [
                'attribute'=>'created',
                'value'=>date('d.m.Y H:i:s', strtotime($model->created)),
            ],
            [
                'attribute'=>'updated_by',
                'value'=>($model->updatedBy->first_name.' '.$model->updatedBy->last_name),
            ],  
            [
                'attribute'=>'updated',
                'value'=> call_user_func (
                    function ($data) {
                        if ($data->updated != '0000-00-00 00:00:00' AND !is_null($data->updated)) {
                            return date('d.m.Y H:i:s', strtotime($data->updated));            
                        }
                        else {
                            return '-';
                        }
                        
                    //date('d.m.Y H:i:s', strtotime($model->updated)),    
                    }
                , $model),
                
            ],

        ],
    ]) ?>

</div>
