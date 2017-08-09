<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Formatter;
use yii\helpers\ArrayHelper;
use backend\models\Customer;
use backend\models\User;
use backend\models\Offer;
use backend\models\OfferStatus;
use backend\models\CustomerPriority;
use dosamigos\datepicker\DatePicker;
use kartik\export\ExportMenu;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\OfferSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Offerten';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="offer-index">
<?php 
    $offer_statuses = OfferStatus::find()->where('active = 1')->all();

    
?>

    <?php echo '<p><div style="display:inline; vertical-align: middle;" class="h1">'.Html::encode($this->title).'</div> 
    <div class=" pull-right"><table class="table table-bordered" style="display:inline; border-color:white;">';
    foreach ($offer_statuses as $k=>$v) {
        echo '<tr style="display:inline-block;" class="'.$v['class'].'"><td style="font-size:0.9em;">'.$v['name'].'</td></tr>';   
    }
    echo '
    </table></div><p>';
    ?>

    <?php //echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php
        
            if (Yii::$app->user->can('create-offer')) 
            {
                echo Html::a('Offerte hinzufügen', ['create'], ['class' => 'btn btn-success']).' ';
            } 
        ?>
        <?= Html::a('Filter rücksetzen', ['index'], ['class' => 'btn btn-success']) ?>    


    </p>
        <?php 
            echo '<div class="pull-right ">'.ExportMenu::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,            
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute'=>'offer_no',
                    'contentOptions' => ['style' => 'width:50px; text-align: right;'],
                ],
                [
                    'attribute'=>'offer_received',
                    'value'=>'offer_received',
                    'contentOptions' => ['style' => 'width:80px; text-align: right;'],
                    'format' => ['date', 'php:d.m.y'],
                ],            
                [
                    'attribute'=>'customer_id',
                    'value'=>'customer.name',
                    'contentOptions' => ['style' => 'width:300px'],
                ],
                'customer_order_no',
                [
                    'attribute'=>'carpenter',
                    'value'=>'carpenter',
                    'contentOptions' => ['style' => 'width:300px'],
                ],
                [
                    'attribute'=>'qty',
                    'contentOptions' => ['style' => 'width:50px; text-align: right;'],
                ],
                [
                    'attribute'=>'assigned_to',
                    'value'=>'assignedTo.last_name',
                ],              
                [
                    'attribute'=>'followup_by_id',
                    'value'=>'followupBy.last_name',
                ],        
                [
                    'attribute'=>'value',
                    'value'=>'value',
                    'contentOptions' => ['style' => 'width:50px; text-align: right;'],                
                    'format'=>['decimal', '2'],
                ],  
                [
                    'attribute'=>'value_net',
                    'value'=>'value_net',
                    'contentOptions' => ['style' => 'width:50px; text-align: right;'],                
                    'format'=>['decimal', '2'],
                ],  
                [
                    'attribute'=>'customer_priority_id',
                    'value'=>'customerPriority.id',
                ],   
                [
                    'attribute'=>'status_id',
                    'value'=>'status.name',
                    'contentOptions' => ['style' => 'width:50px; text-align: right;'],                
                ],     
            ],
        ]); 
            echo '</div>';
        ?>
<?php Pjax::begin(); ?>   

    <?=  GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table table-bordered'],
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

               // if ($model['prio1'] == '1') {
               //     return ['class' => 'info'];
              //  } 

                $deadline = $model['deadline'];
                $warning = $deadline-60*60*24;
                if($model['status_id'] == 1) { // status being processed
                    return ['class'=>'active'];
                }
                elseif ($model['status_id'] == 2) { // status stand by
                    return ['class'=>'default'];
                }
                elseif ($model['status_id'] == 3) { // status offer won
                    return ['class'=>'success'];
                }                
                elseif ($model['status_id'] == 4) { // status deleted
                    return ['class'=>'default'];
                }                    
                elseif ($model['status_id'] == 5) { // status offer lost
                    return ['class'=>'danger'];
                }                                    
                elseif ($model['status_id'] == 6) { // status followed up 
                    return ['class'=>'info'];
                } 
                elseif ($model['status_id'] == 7) { // status offer made
                    return ['class'=>'warning'];
                }    
//                elseif ($warning < time() and $deadline < time()) {
//                    return ['class'=>'danger']; 
//                }


            }
        ,   
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute'=>'offer_no',
                'contentOptions' => ['style' => 'width:50px; text-align: right;'],
            ],
            [
                'attribute'=>'offer_received',
                'value'=>'offer_received',
                'contentOptions' => ['style' => 'width:80px; text-align: right;'],
                'format' => ['date', 'php:d.m.y'],
                'filter'=>DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'offer_received',
                 //   'template' => '{addon}{input}',
                        'clientOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd'
                        ]
                ]),
            ],            
//            'offer_wir_id',
//            'processed_by_id',
//            'followup_by_id',

            [
                'attribute'=>'customer_id',
                'value'=>'customer.name',
                'contentOptions' => ['style' => 'width:300px'],
            ],
            'customer_order_no',
//            'customer_contact',
            [
                'attribute'=>'carpenter',
                'value'=>'carpenter',
                'contentOptions' => ['style' => 'width:300px'],
            ],
            [
                'attribute'=>'qty',
                'contentOptions' => ['style' => 'width:50px; text-align: right;'],
            ],
            [
                'attribute'=>'assigned_to',
                'value'=>'assignedTo.last_name',
                'filter'=>Html::activeDropDownList($searchModel, 'assigned_to', ArrayHelper::map(User::find()->where(['active'=>1, 'show_in_lists'=>1])->asArray()->orderBy(['last_name' => SORT_ASC])->all(), 'id', 'last_name'), ['class'=>'', 'prompt' => 'Alle']),                
            ],              
            [
                'attribute'=>'followup_by_id',
                'value'=>'followupBy.last_name',
                'filter'=>Html::activeDropDownList($searchModel, 'followup_by_id', ArrayHelper::map(User::find()->where(['active'=>1, 'show_in_lists'=>1])->asArray()->orderBy(['last_name' => SORT_ASC])->all(), 'id', 'last_name'), ['class'=>'', 'prompt' => 'Alle']),                
            ],        
            [
                'attribute'=>'value',
                'value'=>'value',
                'contentOptions' => ['style' => 'width:50px; text-align: right;'],                
                'format'=>['decimal', '2'],
            ],  
            [
                'attribute'=>'value_net',
                'value'=>'value_net',
                'contentOptions' => ['style' => 'width:50px; text-align: right;'],                
                'format'=>['decimal', '2'],
            ],  
            [
                'attribute'=>'customer_priority_id',
                'value'=>'customerPriority.id',
                'filter'=>Html::activeDropDownList($searchModel, 'customer_priority_id', ArrayHelper::map(CustomerPriority::find()->asArray()->all(), 'id', 'id'), ['class'=>'form-control', 'prompt' => 'Alle']),

            ],   
            [
                'attribute'=>'status_id',
                'value'=>'status.name',
                'filter'=>Html::activeDropDownList($searchModel, 'status_id', ArrayHelper::map(OfferStatus::find()->asArray()->all(), 'id', 'name'), ['class'=>'form-control', 'prompt' => 'Alle']),                
                'contentOptions' => ['style' => 'width:50px; text-align: right;'],                
            ],            
         //   'customer_priority_id',
          
//            'confirmation_no',
            
            //'days_to_process',
            //'deadline',
            //'comments:ntext',
            //'created_by',
            //'created',            
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
                                 //  'data-pjax' => 'w0',
                                    'data-pjax' => '0',
                        ]);
                    },
                    'update' => function ($url, $model) {

                        if ($model->locked_by == 0) { // not locked
                            if (Yii::$app->user->can('change-offer')) {
                                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                                    'title' => Yii::t('app', 'Update'),                              
                                ]);
                            }
                          //AND $model->locked_by == 0) (Yii::$app->user->can('change-offer') AND $model->locked_by == Yii::$app->user->id)) OR Yii::$app->user->can('admin'))    
                        } 
                        else {
                            if (Yii::$app->user->can('change-offer')) {
                                
                                if ($model->locked_by == Yii::$app->user->id OR Yii::$app->user->can('admin')) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"><span class="glyphicon glyphicon-lock" style="color: gold;" title="'.$model->lockedBy->username.' '.date('d.m.Y H:i', $model->locked).'"></span>', $url, [
                                        'title' => Yii::t('app', 'Update'),                              
                                    ]);
                                }
                                else {
                                    return '<span class="glyphicon glyphicon-lock" style="color: gold;" title="'.$model->lockedBy->username.' '.date('d.m.Y H:i', $model->locked).'"></span></span>';                  
                                                
                                }
                            }
                        }
                    },
                    'delete' => function ($url, $model) {
                        if (Yii::$app->user->can('delete-offer')) 
                        {
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                                        'title' => Yii::t('app', 'Delete'),
                                        'data-confirm' => 'Are you sure you want to delete this item?',
                                        'data-method' => 'post',

                            ]);
                        }
                    },                                           
                ],
                'urlCreator' => function ($action, $model, $key, $index) {
                    if ($action === 'view') {
                        return Url::to(['offer/view', 'id'=>$model->id]);
                    }
                    if ($action === 'update') {
                        return Url::to(['offer/update', 'id'=>$model->id]);
                    }
                    if ($action === 'delete') {
                        return Url::to(['offer/delete', 'id'=>$model->id]);
                    }                    
                }
            ],
        ],
    ]); 
?>
<?php Pjax::end(); ?>

</div>
