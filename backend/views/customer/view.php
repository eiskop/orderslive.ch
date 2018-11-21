<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use backend\models\Customer;
use backend\models\CustomerUpload;
use backend\models\Offer;
use backend\models\OfferStatus;
use backend\models\So;
use backend\models\SoStatus;
use backend\models\CustomerPriority;
use backend\models\ProductGroup;
use backend\models\User;
use dosamigos\datepicker\DatePicker;

/* @var $this yii\web\View */
/* @var $model backend\models\Customer */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Kunden', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;



?>
<div class="customer-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>

        <?php
            if (Yii::$app->user->can('change-so') OR Yii::$app->user->can('update-so')) 
            {
                echo Html::a('Ändern', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']).' ';
            }         
            if (Yii::$app->user->can('create-so')) 
            {
                echo Html::a('Neuer Auftrag', ['so/create', 'customer_id' => $model->id], ['class' => 'btn btn-success']).' ';
            }  
            if (Yii::$app->user->can('create-offer')) 
            {
                echo Html::a('Neue Offerte', ['offer/create', 'customer_id' => $model->id], ['class' => 'btn btn-success']).' ';
            } 
            if (Yii::$app->user->can('create-customerupload')) 
            {
                echo Html::a('Neue Datei', ['customer-upload/create', 'customer_id' => $model->id], ['class' => 'btn btn-success']).' ';
            }
            if (Yii::$app->user->can('create-customerdiscount')) 
            {
                echo Html::a('Neue Kondition', ['customer-discount/create', 'customer_id' => $model->id], ['class' => 'btn btn-success']).' ';
            }             
            if (Yii::$app->user->can('delete-so')) 
            {
                echo Html::a('Stornieren', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Bist du sicher dass du diesen Kunden löschen willst?',
                        'method' => 'post',
                    ],
                ]).' '; 

            }           




        ?>
    </p>
    <div class="table">
        <div class="row">
            <div class="col-lg-6">

                <h2>Informationen</h2>
                <hr style="margin-top: -10px; padding-top: 0; margin-bottom: 5px; padding-bottom: 0;" />                
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
                        'ifas_account',
                        'created:datetime',
                        [
                            'attribute'=>'created_by',
                            'value'=>$model->createdBy->username,
                        ],  
                        'updated:datetime',
                        [
                            'attribute'=>'updated_by',
                            'value'=>$model->updatedBy->username,
                        ],  
                    ],
                ])
                ?>

            </div>
            <div class="col-lg-6">
                <h2>Konditionen</h2>
                <hr style="margin-top: -10px; padding-top: 0; margin-bottom: 5px; padding-bottom: 0;" />

                <?php Pjax::begin(); ?>    <?= GridView::widget([

                    'dataProvider' => $dataProvider4,
                    'filterModel' => $searchModel4,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        [
                            'attribute'=>'id',
                            'contentOptions'=>['style'=>'width:30px;text-align:right;'],
                        ],
                        [
                            'attribute'=>'offer_item_type_id',
                            'value'=>'offerItemType.name',
                            'contentOptions'=>['style'=>'width:100px;'],
                        ],            
                        [
                            'attribute' => 'base_discount_perc',
                            'contentOptions'=>['style'=>'width:30px;text-align:right;'],
                            'format' => ['decimal', 1],
                        ],
                        [
                            'attribute'=>'valid_from',
                            'contentOptions'=>['style'=>'width:100px;'],
                            'format' => ['date'],
                        ],              
                        // 'created',
                        // 'created_by',
                        // 'updated',
                        // 'updated_by',
                        // 'approved',
                        // 'approved_by',
                        
                        [
                            'attribute'=>'active',
                            'format'=>'boolean',
                        ],

                           [  
                            'class' => 'yii\grid\ActionColumn',
                            'contentOptions' => ['style' => 'width:70px; text-align: center;'],
                            'header'=>'',
                            'template' => '{view} {update} {delete}',
                            'buttons' => 
                            [

                                //view button
                                'view' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                                                'title' => Yii::t('app', 'View'),                              
                                    ]);
                                },
                                'update' => function ($url, $model) {
                                    if (Yii::$app->user->can('change-customerdiscount')) 
                                    {
                                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                                                    'title' => Yii::t('app', 'Update'),                              
                                        ]);
                                    }
                                },
                                'delete' => function ($url, $model) {
                                    if (Yii::$app->user->can('delete-customerdiscount')) 
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
                                    return Url::to(['customer-discount/view', 'id'=>$model->id]);
                                }
                                if ($action === 'update') {
                                    return Url::to(['customer-discount/update', 'id'=>$model->id]);
                                }
                                if ($action === 'delete') {
                                    return Url::to(['customer-discount/delete', 'id'=>$model->id]);
                                }                    
                            }
                        ],
                    ],
                ]); ?>

                <?php Pjax::end(); ?>

                <h2>Dateien</h2>
                <hr style="margin-top: -10px; padding-top: 0; margin-bottom: 5px; padding-bottom: 0;" />
                <?php Pjax::begin(); ?>    <?= GridView::widget([
                    'dataProvider' => $dataProvider3,
                    'filterModel' => $searchModel3,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        [
                            'attribute' => 'id',
                            'value' => 'id',
                            'contentOptions' => ['style' => 'width:20px; text-align: right;'],    
                        ],
                        [
                            'attribute' => 'file_name',
                            'value' => function ($model) {
                                return $model->file_name.'.'.$model->file_extension;
                            }
                        ],
              //          'file_path',
                        //'file_name',
                        //'file_extension',
                        // 'file_type',
                         'title',
                         'description:ntext',
                        // 'file_size',
                        // 'valid_from',
                        // 'valid_to',
                        // 'created',
                        // 'created_by',
                        // 'changed',
                        // 'changed_by',

                       [  
                            'class' => 'yii\grid\ActionColumn',
                            'contentOptions' => ['style' => 'width:70px; text-align: center;'],
                            'header'=>'',
                            'template' => '{view} {update} {delete}',
                            'buttons' => 
                            [

                                //view button
                                'view' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                                                'title' => Yii::t('app', 'View'),                              
                                    ]);
                                },
                                'update' => function ($url, $model) {
                                    if (Yii::$app->user->can('change-customerupload')) 
                                    {
                                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                                                    'title' => Yii::t('app', 'Update'),                              
                                        ]);
                                    }
                                },
                                'delete' => function ($url, $model) {
                                    if (Yii::$app->user->can('delete-customerupload')) 
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
                                    return Url::to(['customer-upload/view', 'id'=>$model->id]);
                                }
                                if ($action === 'update') {
                                    return Url::to(['customer-upload/update', 'id'=>$model->id]);
                                }
                                if ($action === 'delete') {
                                    return Url::to(['customer-upload/delete', 'id'=>$model->id]);
                                }                    
                            }
                        ],
                    ],
                ]); ?>
                <?php Pjax::end(); ?>

            </div>
        </div>
    </div>            


<h2>Offerten</h2>
<hr style="margin-top: -10px; padding-top: 0; margin-bottom: 5px; padding-bottom: 0;" />
<!--Offers -->
<?php Pjax::begin(); ?>    
    <?= GridView::widget([
        'dataProvider' => $dataProvider2,
        'pager' => [
            'firstPageLabel' => '<<<',
            'lastPageLabel' => '>>>',
        ],

        'filterModel' => $searchModel2,
        'layout'=>"{pager} {summary} {items} {pager}",
        'rowOptions' => 

            function ($modelOffer) {

                if ($modelOffer['prio1'] == '1') {
                    return ['class' => 'info'];
                } 

                $prio = CustomerPriority::findOne(['id'=>$modelOffer->customer_priority_id]);

                $deadline = $modelOffer->deadline;
                $warning = $deadline-60*60*24;
                
                if($deadline > time() and $warning > time() AND $modelOffer->status_id != 3 AND $modelOffer->status_id != 4) {
                    return ['class'=>'success'];
                }
                elseif ($warning < time() and $deadline > time() AND $modelOffer->status_id != 3 AND $modelOffer->status_id != 4) {
                    return ['class'=>'warning'];
                }
                elseif ($warning < time() and $deadline < time() AND $modelOffer->status_id != 3 AND $modelOffer->status_id != 4) {
                    return ['class'=>'danger']; 
                }
                else {

                }
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
                    'model' => $searchModel2,
                    'attribute' => 'offer_received',
                 //   'template' => '{addon}{input}',
                        'clientOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd'
                        ]
                ]),
            ],            


/*            [
                'attribute'=>'customer_id',
                'value'=>'customer.name',
                'contentOptions' => ['style' => 'width:300px'],
            ],
            */
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
                'attribute'=>'followup_by_id',
                'value'=>'followupBy.last_name',
                'filter'=>Html::activeDropDownList($searchModel2, 'followup_by_id', ArrayHelper::map(User::find()->where(['active'=>1, 'show_in_lists'=>1])->asArray()->orderBy(['last_name' => SORT_ASC])->all(), 'id', 'last_name'), ['class'=>'', 'prompt' => 'Alle']),                
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
                'filter'=>Html::activeDropDownList($searchModel2, 'customer_priority_id', ArrayHelper::map(CustomerPriority::find()->asArray()->all(), 'id', 'id'), ['class'=>'form-control', 'prompt' => 'Alle']),

            ],   
            [
                'attribute'=>'status_id',
                'value'=>'status.name',
                'filter'=>Html::activeDropDownList($searchModel2, 'status_id', ArrayHelper::map(OfferStatus::find()->asArray()->all(), 'id', 'name'), ['class'=>'form-control', 'prompt' => 'Alle']),                
                'contentOptions' => ['style' => 'width:50px; text-align: right;'],                
            ],            
          
            [  
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['style' => 'width:70px; text-align: center;'],
                'header'=>'',
                'template' => '{view} {update}',
                'buttons' => 
                [

                    //view button
                    'view' => function ($url, $modelOffer) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                                    'title' => Yii::t('app', 'View'),                              
                                 //  'data-pjax' => 'w0',
                                    'data-pjax' => '0',
                        ]);
                    },
                    'update' => function ($url, $modelOffer) {
                        if (Yii::$app->user->can('change-offer')) 
                        {
                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                                        'title' => Yii::t('app', 'Update'),                              
                            ]);
                        }
                    },
                    'delete' => function ($url, $modelOffer) {
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
                'urlCreator' => function ($action, $modelOffer, $key, $index) {
                    if ($action === 'view') {
                        return Url::to(['offer/view', 'id'=>$modelOffer->id]);
                    }
                    if ($action === 'update') {
                        return Url::to(['offer/update', 'id'=>$modelOffer->id]);
                    }
                    if ($action === 'delete') {
                        return Url::to(['offer/delete', 'id'=>$modelOffer->id]);
                    }                    
                }
            ],
        ],
    ]); 
?>
<?php Pjax::end(); ?>
<!--END: Offers-->
<h2>Aufträge</h2>
<hr style="margin-top: -10px; padding-top: 0; margin-bottom: 5px; padding-bottom: 0;" />
<?php Pjax::begin(); 
    // orders 
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

            function ($modelSo) {

// SELECT * FROM `order` WHERE `subtotal` > 200 ORDER BY `id`

                //echo var_dump($orders);

                //echo var_dump(Yii::$app->formatter->asDate($modelSo->order_received, 'php:Y-m-d'));
                //echo var_dump($modelSo->order_received);
         //   $customer = Customer::findOne(['id'=>$modelSo->customer_id]);
          //  echo var_dump($customer);
            //$prio = CustomerPriority::findOne(['id'=>$customer->customer_priority_id]);
        //  echo var_dump($prio);

                if ($modelSo['prio1'] == '1') {
                    return ['class' => 'info'];
                } 
            
            

                 $prio = CustomerPriority::findOne(['id'=>$modelSo->customer_priority_id]);

               // echo var_dump($prio->days_to_process);
                $deadline = $modelSo->deadline;
                $warning = $deadline-60*60*24;
                
                if($deadline > time() and $warning > time() AND $modelSo->status_id != 3 AND $modelSo->status_id != 4) {
                    return ['class'=>'success'];
                }
                elseif ($warning < time() and $deadline > time() AND $modelSo->status_id != 3 AND $modelSo->status_id != 4) {
                    return ['class'=>'warning'];
                }
                elseif ($warning < time() and $deadline < time() AND $modelSo->status_id != 3 AND $modelSo->status_id != 4) {
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
/*            [
                'attribute'=>'customer_id',
                'value'=>'customer.name',
                'contentOptions' => ['style' => 'width:300px'],
            ],
*/            
            'customer_order_no',
            'confirmation_no',
            'offer_no',            
// quantity from so_items table 

            'surface',
            [
                'attribute'=>'qty',
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
                'contentOptions' => ['style' => 'width:70px; text-align: center;'],
                'header'=>'',
                'template' => '{view} {update}',
                'buttons' => 
                [

                    //view button
                    'view' => function ($url, $modelSo) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                                    'title' => Yii::t('app', 'View'),                              
                        ]);
                    },
                    'update' => function ($url, $modelSo) {
                        if (Yii::$app->user->can('update-so') OR Yii::$app->user->can('change-so')) 
                        {
                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                                        'title' => Yii::t('app', 'Update'),                              
                            ]);
                        }
                    },
                    'delete' => function ($url, $modelSo) {
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
                'urlCreator' => function ($action, $modelSo, $key, $index) {
                    if ($action === 'view') {
                        return Url::to(['so/view', 'id'=>$modelSo->id]);
                    }
                    if ($action === 'update') {
                        return Url::to(['so/update', 'id'=>$modelSo->id]);
                    }
                    if ($action === 'delete') {
                        return Url::to(['so/delete', 'id'=>$modelSo->id]);
                    }                    
                }
            ],

        ],
    ]); ?>

    <?php Pjax::end(); 
    //end: orders ?>
    
</div>
