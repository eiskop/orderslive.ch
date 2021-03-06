<?php


//Raporten 
// aufträge, stk pro tag
//SELECT date(updated) as datum, product_group.name, Sum(qty) as qty, count(*) as aufträge FROM `so` left join product_group on so.product_group_id = product_group.id WHERE status_id=3 Group by product_group_id, Date(updated) ORDER BY datum, name
// Weekly per product group
//SELECT week(updated) as datum, product_group.name, Sum(qty) as qty, count(*) as aufträge FROM `so` left join product_group on so.product_group_id = product_group.id WHERE status_id=3 and product_group_id = 2 Group by product_group_id, WEEK(updated) ORDER BY datum, name
//durchlaufzeit tage
//SELECT customer_id, customer.name, product_group.name, so.customer_priority_id, customer_priority.days_to_process, avg(UNIX_TIMESTAMP(so.updated)-UNIX_TIMESTAMP(order_received))/(60*60*24) as durchlaufzeit_tage, count(*) as aufträge FROM `so` 
  //      LEFT JOIN customer on so.customer_id = customer.id  
    //    LEFT JOIN product_group on so.product_group_id = product_group.id  
      //  LEFT JOIN customer_priority on so.customer_priority_id = customer_priority.id  
  //WHere status_id = 3 AND UNIX_TIMESTAMP(so.updated) != 0 group by customer_id, product_group_id ORDER BY customer.name ASC, product_group.name ASC



use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use backend\models\User;
use backend\models\Customer;
use backend\models\So;
use backend\models\SoStatus;
use backend\models\CustomerPriority;
use backend\models\ProductGroup;
use backend\models\SelectMenu;
use dosamigos\datepicker\DatePicker;
use kartik\export\ExportMenu;


/* @var $this yii\web\View */
/* @var $searchModel backend\models\SoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Aufträge';
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="so-index" >

    <h1><?= Html::encode($this->title) ?></h1>
    <?php //echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php 
            if (Yii::$app->user->can('create-so')) 
            {
                echo Html::a('Auftrag Hinzufügen', ['create'], ['class' => 'btn btn-success']);
            }
        
        ?>

        
        <?= Html::a('Filter rücksetzen', ['index'], ['class' => 'btn btn-success']) ?>        
    </p>


    <?php //Pjax::begin(); ?>

     <?php 
     //export filter 
            echo '<div class="pull-right ">'.ExportMenu::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'exportConfig' => [
                ExportMenu::FORMAT_TEXT => false,
                ExportMenu::FORMAT_PDF => false,
                ExportMenu::FORMAT_EXCEL => false,
            ],
            'formatter' => [
                'class' => 'yii\i18n\Formatter',
                'thousandSeparator' => '',
                'decimalSeparator' => '.',
                //'currencyCode' => 'CHF'
            ],            
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute'=>'id',
                    'contentOptions' => ['style' => 'width:50px; text-align: right;'],
                ],
                [
                    'attribute'=>'product_group_id',
                    'value'=>'productGroup.name',
                ],
                [
                    'attribute'=>'order_received',
                    'value'=>'order_received',
                    'contentOptions' => ['style' => 'width:80px; text-align: right;'],
                    'format' => ['date', 'php:d.m.y'],
                ],
                [
                    'attribute'=>'customer_id',
                    'value'=>'customer.name',
                    'contentOptions' => ['style' => 'width:300px'],
                ],
                'customer_order_no',
                'confirmation_no',
                'offer_no',            
                'surface',
                'product_type',
                [
                    'attribute'=>'qty',
                    'contentOptions' => ['style' => 'width:50px; text-align: right;'],
                ],
                [
                    'attribute'=>'requested_delivery_year',
                    'value'=>function($data) {
                        if ($data['requested_delivery_year'] != 0) {
                            return $data['requested_delivery_year'].'-'.$data['requested_delivery_week'];    
                        }
                        else {
                            return "";
                        }
                        
                    },
                    'contentOptions' => ['style' => 'width:50px; text-align: right;'],
                ],
                
                [
                    'attribute'=>'created_by',
                    'value'=>'createdBy.username',
                ],
                [
                    'attribute'=>'customer_priority_id',
                    'value'=>'customerPriority.id',
                ],
                [
                    'attribute'=>'status_id',
                    'value'=>'soStatus.name',
                ],
                [
                    'attribute'=>'assigned_to',
                    'value'=>'assignedTo.username',
                ],            
                [
                    'attribute'=>'DLZ_Tage',
                    'value'=>'dLZ',
                    'contentOptions'=>['style'=>'text-align: right;'],
                ],    
            ],
        ]); 
            echo '</div>';
//END: export filter 
        ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'pager' => [
            'firstPageLabel' => '<<<',
            'lastPageLabel' => '>>>',
        ],

        'filterModel' => $searchModel,
        'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],

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
                
                if ($model['prio1'] == '1') {
                    return ['class' => 'info'];
                } 

                $deadline = $model['deadline'];
                $warning = $deadline-60*60*24;
                if($model['status_id'] == 1) { // status being processed
                    return ['class'=>'success'];
                }
                elseif ($model['status_id'] == 2) { // status stand by
                    return ['class'=>'warning'];
                }
                elseif ($model['status_id'] == 3) { // status complete
                    return ['class'=>'default'];
                }                
                elseif ($warning < time() and $deadline < time() AND $model->status_id != 3 AND $model->status_id != 4) {
                    return ['class'=>'danger']; 
                }




            }
        ,        
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute'=>'id',
                'contentOptions' => ['style' => 'width:50px; text-align: right;'],

            ],
            //'product_group_id',
            [
                'attribute'=>'product_group_id',
                'value'=>'productGroup.name',
                'filter'=>Html::activeDropDownList($searchModel, 'product_group_id', ArrayHelper::map(ProductGroup::find()->asArray()->all(), 'id', 'name'), ['class'=>'form-control', 'prompt' => 'Alle']),

            ],
            [
                'attribute'=>'order_received',
                'value'=>'order_received',
                'contentOptions' => ['style' => 'width:80px; text-align: right;'],
                'format' => ['date', 'php:d.m.y'],
                'filter'=>DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'order_received',
                 //   'template' => '{addon}{input}',
                        'clientOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd',

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
                'attribute'=>'product_type',
                'filter'=>Html::activeDropDownList($searchModel, 'product_type', ArrayHelper::map(SelectMenu::find()->asArray()->where(['model_name'=>'so', 'select_name'=>'product_type'])->all(), 'id', 'option_name'), ['class'=>'form-control', 'prompt' => 'Alle']),

                'value'=> function ($data) {
                    if ($data->product_type != 0) {
                        $res = ArrayHelper::map(SelectMenu::find()->asArray()->where(['model_name'=>'so', 'select_name'=>'product_type'])->all(), 'id', 'option_name');
                        return $res[$data->product_type];
                    }
                },
                'contentOptions' => ['style' => 'width:50px'],
            ],
            [
                'attribute'=>'qty',
                'contentOptions' => ['style' => 'width:50px; text-align: right;'],
            ],
            [
                'label' => 'Wunschtermin',
                'attribute'=>'requested_delivery_year',

                'value'=>function($data) {
                    if ($data['requested_delivery_year'] != 0) {
                        return $data['requested_delivery_year'].'-'.$data['requested_delivery_week'];    
                    }
                    else {
                        return "";
                    }
                },
                'contentOptions' => ['style' => 'width:50px; text-align: right;'],
            ],
            
            // 'value',
            
            [
                'attribute'=>'created_by',
                'value'=>'createdBy.username',
                'filter'=>Html::activeDropDownList($searchModel, 'created_by', ArrayHelper::map(User::find()->asArray()->all(), 'id', 'username'), ['class'=>'form-control', 'prompt' => 'Alle']),
            ],
            [
                'attribute'=>'customer_priority_id',
                'value'=>'customerPriority.id',
                'filter'=>Html::activeDropDownList($searchModel, 'customer_priority_id', ArrayHelper::map(CustomerPriority::find()->asArray()->all(), 'id', 'id'), ['class'=>'form-control', 'prompt' => 'Alle']),
            ],
            [
                'attribute'=>'status_id',
                'value'=>'soStatus.name',
                'filter'=>Html::activeDropDownList($searchModel, 'status_id', ArrayHelper::map(SoStatus::find()->asArray()->all(), 'id', 'name'), ['class'=>'form-control', 'prompt' => 'Alle']),
            ],
            [
                'attribute'=>'assigned_to',
                'value'=>'assignedTo.username',
                'filter'=>Html::activeDropDownList($searchModel, 'assigned_to', ArrayHelper::map(User::find()->asArray()->all(), 'id', 'username'), ['class'=>'form-control', 'prompt' => 'Alle']),
            ],            
            [
                'attribute'=>'DLZ_Tage',
                'value'=>'dLZ',
                'contentOptions'=>['style'=>'text-align: right;'],
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
                        if (Yii::$app->user->can('update-so') OR Yii::$app->user->can('change-so')) 
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

    <?php //Pjax::end(); ?>

</div>
