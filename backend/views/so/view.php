<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\ArrayHelper;
use backend\models\ProductGroup;
use backend\models\Customer;
use backend\models\CustomerPriority;
use backend\models\SoStatus;
use common\models\User;

/* @var $this yii\web\View */
/* @var $model backend\models\So */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Aufträge', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="so-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php 
            if (Yii::$app->user->can('create-so')) 
            {
                echo Html::a('Auftrag Hinzufügen', ['create'], ['class' => 'btn btn-success']);
            }
        
        ?>
        <?= Html::a('Ändern', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Löschen', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Bist du sicher, dass du diesen Auftrag löschen willst?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute'=>'product_group_id',
                'value'=>$model->productGroup->name,
            ],
            [
                'attribute'=>'customer_id',
                'value'=>$model->customer->name,
            ],            
            'customer_order_no',
            'confirmation_no',
            'surface',
            [
                'attribute'=>'status_id',
                'value'=>$model->soStatus->name,
            ],              
            'value',
            'order_received',
            'customer_priority_id',
            'days_to_process',
            [
                'attribute'=>'Termin für Erfassung',
                'value'=>date('d.m.Y H:i:s', $model->deadline),
            ],               
            'comments',
            [
                'attribute'=>'created',
                'value'=>date('d.m.Y H:i:s', strtotime($model->created)),
            ],
            [
                'attribute'=>'assigned_to',
                'value'=>$model->assignedTo->username,
            ],
            [
                'attribute'=>'created_by',
                'value'=>$model->createdBy->username,
            ],
            'updatedBy.username',
            'updated',
        ],
    ]) ?>
</div>
