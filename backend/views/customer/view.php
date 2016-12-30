<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use backend\models\Customer;
use backend\models\So;
use backend\models\SoStatus;
use backend\models\CustomerPriority;
use dosamigos\datepicker\DatePicker;

/* @var $this yii\web\View */
/* @var $model backend\models\Customer */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Customers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customer-view">

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
            'name',
            [
                'attribute' => 'customer_group_id',
                'value' => $model->customerGroup->name,
            ],
            'customer_priority_id',
            'contact',
            'street',
            'zip_code',
            'region',
            'city',
            'province',
            'fax_no',
            'tel_no',
            [
                'attribute'=>'created',
                'value'=>date('d.m.Y H:i:s', strtotime($model->created)),
            ], 
            [
                'attribute'=>'created_by',
                'value'=>$model->createdBy->username,
            ],  
            [
                'attribute'=>'updated',
                'value'=>date('d.m.Y H:i:s', strtotime($model->updated)),
            ], 
            [
                'attribute'=>'updated_by',
                'value'=>$model->updatedBy->username,
            ],  
        ],
    ]) ?>

        <h2>Aufträge</h2>
        <hr />
    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'pager' => [
            'firstPageLabel' => '<<<',
            'lastPageLabel' => '>>>',
        ],

        'filterModel' => $searchModel,
        'layout'=>"{pager} {summary} {items} {pager}",
        'rowOptions' => 

            function ($model) {

// SELECT * FROM `order` WHERE `subtotal` > 200 ORDER BY `id`

                //echo var_dump($orders);

                //echo var_dump(Yii::$app->formatter->asDate($model->order_received, 'php:Y-m-d'));
                //echo var_dump($model->order_received);
         //   $customer = Customer::findOne(['id'=>$model->customer_id]);
          //  echo var_dump($customer);
            //$prio = CustomerPriority::findOne(['id'=>$customer->customer_priority_id]);
        //  echo var_dump($prio);

                if ($model['prio1'] == '1') {
                    return ['class' => 'info'];
                } 
            
            

                 $prio = CustomerPriority::findOne(['id'=>$model->customer_priority_id]);

               // echo var_dump($prio->days_to_process);
                $deadline = $model->deadline;
                $warning = $deadline-60*60*24;
                
                if($deadline > time() and $warning > time() AND $model->status_id != 3 AND $model->status_id != 4) {
                    return ['class'=>'success'];
                }
                elseif ($warning < time() and $deadline > time() AND $model->status_id != 3 AND $model->status_id != 4) {
                    return ['class'=>'warning'];
                }
                elseif ($warning < time() and $deadline < time() AND $model->status_id != 3 AND $model->status_id != 4) {
                    return ['class'=>'danger']; 
                }
                else {

                }
            }
        ,        
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute'=>'id',
                'contentOptions' => ['style' => 'width:50px'],
            ],
            //'product_group_id',
            [
                'attribute'=>'product_group_id',
                'value'=>'productGroup.name',
            ],
            [
                'attribute'=>'order_received',
                'value'=>'order_received',
                'contentOptions' => ['style' => 'width:150px'],
                'format' => ['date', 'php:d-m-Y'],
                'filter'=>DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'order_received',
                 //   'template' => '{addon}{input}',
                        'clientOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd'
                        ]
                ]),
            ],
            [
                'attribute'=>'customer_id',
                'value'=>'customer.name',
                'contentOptions' => ['style' => 'width:300px'],
            ],
            'customer_order_no',
            'confirmation_no',
            'offer_no',            
// quantity from so_items table 

            'surface',
            [
                'attribute'=>'qty',
                'contentOptions' => ['style' => 'width:50px'],
            ],
            
            // 'value',
            
            [
                'attribute'=>'created_by',
                'value'=>'createdBy.username',
            ],
            'customer_priority_id',
            [
                'attribute'=>'status_id',
                'value'=>'soStatus.name',
            ],
            // 'created',
            // 'updated_by',
            // 'updated',


            [  
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['style' => 'width:50px;'],
                'header'=>'',
                'template' => '{view} {update}',
                'buttons' => 
                [

                    //view button
                    'view' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                                    'title' => Yii::t('app', 'View'),                              
                        ]);
                    },
                    'update' => function ($url, $model) {
                        if (Yii::$app->user->can('update-so')) 
                        {
                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                                        'title' => Yii::t('app', 'Update'),                              
                            ]);
                        }
                    },
                    'delete' => function ($url, $model) {
                        if (Yii::$app->user->can('delete-so')) 
                        {
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                                        'title' => Yii::t('app', 'Delete'),
                                        'data-confirm' => 'Are you sure you want to delete this item?',
                                        'data-method' => 'post'
                            ]);
                        }
                    },                                           
                ],
                'urlCreator' => function ($action, $model, $key, $index) {
                    if ($action === 'view') {
                        return Url::to(['so/view', 'id'=>$model->id]);
                    }
                    if ($action === 'update') {
                        return Url::to(['so/update', 'id'=>$model->id]);
                    }
                    if ($action === 'delete') {
                        return Url::to(['so/delete', 'id'=>$model->id]);
                    }                    
                }
            ],

        ],
    ]); ?>

    <?php Pjax::end(); ?>
</div>
