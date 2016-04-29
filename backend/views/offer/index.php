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
/* @var $this yii\web\View */
/* @var $searchModel backend\models\OfferSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Offerten';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="offer-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php //echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Offerte hinzufügen', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Filter rücksetzen', ['index'], ['class' => 'btn btn-success']) ?>             
    </p>
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
                'attribute'=>'customer_id_2',
                'value'=>'customer.name',
                'contentOptions' => ['style' => 'width:300px'],
            ],
            [
                'attribute'=>'qty',
                'contentOptions' => ['style' => 'width:50px; text-align: right;'],
            ],
            [
                'attribute'=>'updated_by',
                'value'=>'updatedBy.username',
                'filter'=>Html::activeDropDownList($searchModel, 'updated_by', ArrayHelper::map(User::find()->asArray()->all(), 'id', 'username'), ['class'=>'', 'prompt' => 'Alle']),                
            ],        
            [
                'attribute'=>'value',
                'value'=>'value',
                'contentOptions' => ['style' => 'width:50px; text-align: right;'],                
                'format'=>['decimal', '0'],
            ],  
            [
                'attribute'=>'value_net',
                'value'=>'value_net',
                'contentOptions' => ['style' => 'width:50px; text-align: right;'],                
                'format'=>['decimal', '0'],
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
                        ]);
                    },
                    'update' => function ($url, $model) {
                        if (Yii::$app->user->can('change-offer')) 
                        {
                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                                        'title' => Yii::t('app', 'Update'),                              
                            ]);
                        }
                    },
                    'delete' => function ($url, $model) {
                        if (Yii::$app->user->can('delete-offer')) 
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
