<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\ArrayHelper;
use backend\models\ProductGroup;
use backend\models\Customer;
use backend\models\CustomerPriority;
use backend\models\SoStatus;
use backend\models\SelectMenu;
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
                echo Html::a('Auftrag Hinzufügen', ['create'], ['class' => 'btn btn-success']).' ';
            }
            if (Yii::$app->user->can('change-so') OR Yii::$app->user->can('update-so')) 
            {
                echo Html::a('Ändern', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']).' ';
            }        
            if (Yii::$app->user->can('delete-so')) 
            {
                echo Html::a('Löschen', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Bist du sicher, dass du diesen Auftrag löschen willst?',
                        'method' => 'post',
                    ],
                ]).' ';
            }
         ?>
    </p>
    <div style="width: 70%">
    <?= DetailView::widget([
        'model' => $model,
        'formatter' => [
           'class' => 'yii\i18n\Formatter',
           'dateFormat' => 'dd.MM.Y',
           'datetimeFormat' => 'dd.MM.Y H:i:s',
           'timeFormat' => 'H:i:s', 
           'nullDisplay' => ''
        ],        
        'attributes' => [
            'id',
            [
                'attribute'=>'product_group_id',
                'value'=>$model->productGroup->name,
            ],
            [
                'attribute'=>'customer_id',
                'value'=>Html::a($model->customer->name, ['customer/view', 'id' => $model->customer_id]),
                'format' => 'raw'
            ],             
            'customer_order_no',
            'confirmation_no',
            'surface',
            [
                'attribute'=>'product_type',
                'value'=>call_user_func(function ($data) {
                                if ($data->product_type != 0) {
                                    $res = ArrayHelper::map(SelectMenu::find()->asArray()->where(['model_name'=>'so', 'select_name'=>'product_type'])->all(), 'id', 'option_name');
                                    return $res[$data->product_type];
                                }
                                else {
                                    return " ";                        
                                }
                            }, $model),
            ],                 
            [
                'attribute'=>'status_id',
                'value'=>$model->soStatus->name,
            ],              
            'value',
            'order_received:date',
            'customer_priority_id',
            'days_to_process',
            'deadline:date',
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
            [
                'attribute'=>'updated',
                'value'=>call_user_func(function($model) {
                            if (!is_null($model->updated)) {
                                return date('d.m.Y H:i:s', strtotime($model->updated));
                            }
                            else {
                                return ' ';
                            }
                        }, $model),
            ], 

        ],
    ]) ?>
    </div>
    <style type ="text/css">
        table.detail-view th {
            width: 25%;
    }

    table.detail-view td {
            width: 75%;
    }
    </style>
</div>
