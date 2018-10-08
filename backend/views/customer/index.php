<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use backend\models\Customer;
use backend\models\CustomerGroup;
use backend\models\CustomerDiscount;
use backend\models\So;
use backend\models\SoSearch;
use backend\models\Offer;
use backend\models\OfferItemType;
use backend\models\OfferSearch;
use yii\widgets\Pjax;
use kartik\export\ExportMenu;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CustomerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Kunden';
$this->params['breadcrumbs'][] = $this->title;


/*
for updating customer discount information
$dbc = Yii::$app->getDb();

$command = $dbc->createCommand('SELECT * FROM customer2 where BOS > 0');
$res = $command->queryAll();
$count = 0;

//echo count($res);
foreach ($res as $k=>$v) {
    //echo 'INSERT INTO customer_discount SET created=NOW(), created_by=1, offer_item_type_id=2, customer_id="'.$v['id'].'", base_discount_perc="'.$v['Kellpax'].'", active=1<br>';
   // $command = $dbc->createCommand('INSERT INTO customer_discount SET created=NOW(), created_by=1, offer_item_type_id=4, customer_id="'.$v['id'].'", base_discount_perc="'.$v['BOS'].'", active=1');
   //$command->execute();
   //echo '<pre>', var_dump($v), '</pre>';
    //$count++;
    //echo $count.'<br>';
}
echo '<pre>', var_dump($res), '</pre>';
*/


?>


<div class="customer-index">
  

    <h1><?= Html::encode($this->title) ?></h1>
  

    <p>
        <?php // echo $this->render('_search', ['model' => $searchModel]); 
            if (Yii::$app->user->can('create-customer')) 
            {
                echo Html::a('Kunde erfassen', ['create'], ['class' => 'btn btn-success']).' ';
            }
            echo Html::a('Filter rücksetzen', ['index'], ['class' => 'btn btn-success']).' ';           
            echo Html::a('Kundendateien', ['customer-upload/index'], ['class' => 'btn btn-primary']).' ';

        ?>
        

    </p>
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
            'columns' => 
            [
                ['class' => 'yii\grid\SerialColumn'],
                'id',
                [
                    'attribute' => 'name',
                    'value' => 'name',
                    'contentOptions' => ['style' => 'width:300px;'], 
                ],
                [
                    'attribute'=>'customer_group_id',
                    'value'=>'customerGroup.name',
                    'contentOptions' => ['style' => 'width:300px;'], 

                ],
                'customer_priority_id',
                //'contact',
                [
                    'attribute' => 'street',
                    'value' => 'street',
                    'contentOptions' => ['style' => 'width:300px;'], 
                ],            
                'zip_code',
                //'city',
                'province',
                // 'fax_no',
                // 'tel_no',
                // 'created',
                // 'created_by',
                // 'updated',
                // 'updated_by',
                [
                    'attribute'=>'Kellpax Rabatt (%)',
                    'value'=>
                        function ($data) {
                           foreach ($data->customerDiscount as $k=>$v) {
                                if ($v['offer_item_type_id'] == 1) {
                                    
                                    return Html::a($v['base_discount_perc'], ['customer-discount/index', 'CustomerDiscountSearch[customer_id]' => $data->name, 'CustomerDiscountSearch[offer_item_type_id]' => $data->customerDiscount[$k]->offerItemType->name]);
                                }
                            }
                        },
                    'contentOptions' => ['style' => 'text-align: right;'], 
                    'format' => 'raw',             
                ],
                [
                    'attribute'=>'Wirus Rabatt (%)',
                    'value'=>
                        function ($data) {
                           foreach ($data->customerDiscount as $k=>$v) {
                                if ($v['offer_item_type_id'] == 2) {
                                    return Html::a($v['base_discount_perc'], ['customer-discount/index', 'CustomerDiscountSearch[customer_id]' => $data->name, 'CustomerDiscountSearch[offer_item_type_id]' => $data->customerDiscount[$k]->offerItemType->name]);
                                }
                            }
                        },
                    'contentOptions' => ['style' => 'text-align: right;'], 
                    'format' => 'raw', 
                ], 
                [
                    'attribute'=>'Moralt Rabatt (%)',
                    'value'=>
                        function ($data) {
                           foreach ($data->customerDiscount as $k=>$v) {
                                if ($v['offer_item_type_id'] == 3) {
                                    return Html::a($v['base_discount_perc'], ['customer-discount/index', 'CustomerDiscountSearch[customer_id]' => $data->name, 'CustomerDiscountSearch[offer_item_type_id]' => $data->customerDiscount[$k]->offerItemType->name]);
                                }
                            }
                        },
                    'contentOptions' => ['style' => 'text-align: right;'], 
                    'format' => 'raw', 
                ],
                [
                    'attribute'=>'BOS Rabatt (%)',
                    'value'=>
                        function ($data) {
                           foreach ($data->customerDiscount as $k=>$v) {
                                if ($v['offer_item_type_id'] == 4) {
                                    return Html::a($v['base_discount_perc'], ['customer-discount/index', 'CustomerDiscountSearch[customer_id]' => $data->name, 'CustomerDiscountSearch[offer_item_type_id]' => $data->customerDiscount[$k]->offerItemType->name]);
                                }
                            }
                        },
                    'format' => 'raw',                     
                    'contentOptions' => ['style' => 'text-align: right;'], 
                    
                ],
                [
                    'attribute'=>'DANA Rabatt (%)',
                    'value'=>
                        function ($data) {
                           foreach ($data->customerDiscount as $k=>$v) {
                                if ($v['offer_item_type_id'] == 5) {
                                    return Html::a($v['base_discount_perc'], ['customer-discount/index', 'CustomerDiscountSearch[customer_id]' => $data->name, 'CustomerDiscountSearch[offer_item_type_id]' => $data->customerDiscount[$k]->offerItemType->name]);
                                }
                            }
                        },
                    'format' => 'raw',   
                    'contentOptions' => ['style' => 'text-align: right;'], 
                    
                ],  
                [
                    'attribute'=>'A',
                    'value'=>function ($data) {
                        return Html::a(So::find()->where(['customer_id'=>$data->id])->count(), ['so/index', 'SoSearch[customer_id]' => $data->name]);
                    },
                    'format' => 'raw',                    
                    'contentOptions' => ['style' => 'text-align: right;'], 
                    
                ],
                [
                    'attribute'=>'O',
                    'value'=>function ($data) {
                        return Html::a(Offer::find()->where(['customer_id'=>$data->id])->count(), ['offer/index', 'OfferSearch[customer_id]' => $data->name]);
                    },
                    'format' => 'raw',
                    'contentOptions' => ['style' => 'text-align: right;'], 

                ],            
                
            ],
        ]); 
            echo '</div>';
//END: export filter 
        ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
        'columns' => 
        [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            [
                'attribute' => 'name',
                'value' => 'name',
                'contentOptions' => ['style' => 'width:300px;'], 
            ],
            [
                'attribute'=>'customer_group_id',
                'value'=>'customerGroup.name',
                'contentOptions' => ['style' => 'width:300px;'], 

            ],
            'customer_priority_id',
            //'contact',
            [
                'attribute' => 'street',
                'value' => 'street',
                'contentOptions' => ['style' => 'width:300px;'], 
            ],            
            'zip_code',
            //'city',
            'province',
            // 'fax_no',
            // 'tel_no',
            // 'created',
            // 'created_by',
            // 'updated',
            // 'updated_by',
            [
                'attribute'=>'Kellpax Rabatt (%)',
                'value'=>
                    function ($data) {
                       foreach ($data->customerDiscount as $k=>$v) {
                            if ($v['offer_item_type_id'] == 1) {
                                
                                return Html::a($v['base_discount_perc'], ['customer-discount/index', 'CustomerDiscountSearch[customer_id]' => $data->name, 'CustomerDiscountSearch[offer_item_type_id]' => $data->customerDiscount[$k]->offerItemType->name]);
                            }
                        }
                    },
                'contentOptions' => ['style' => 'text-align: right;'], 
                'format' => 'raw',             
            ],
            [
                'attribute'=>'Wirus Rabatt (%)',
                'value'=>
                    function ($data) {
                       foreach ($data->customerDiscount as $k=>$v) {
                            if ($v['offer_item_type_id'] == 2) {
                                return Html::a($v['base_discount_perc'], ['customer-discount/index', 'CustomerDiscountSearch[customer_id]' => $data->name, 'CustomerDiscountSearch[offer_item_type_id]' => $data->customerDiscount[$k]->offerItemType->name]);
                            }
                        }
                    },
                'contentOptions' => ['style' => 'text-align: right;'], 
                'format' => 'raw', 
            ], 
            [
                'attribute'=>'Moralt Rabatt (%)',
                'value'=>
                    function ($data) {
                       foreach ($data->customerDiscount as $k=>$v) {
                            if ($v['offer_item_type_id'] == 3) {
                                return Html::a($v['base_discount_perc'], ['customer-discount/index', 'CustomerDiscountSearch[customer_id]' => $data->name, 'CustomerDiscountSearch[offer_item_type_id]' => $data->customerDiscount[$k]->offerItemType->name]);
                            }
                        }
                    },
                'contentOptions' => ['style' => 'text-align: right;'], 
                'format' => 'raw', 
            ],
            [
                'attribute'=>'BOS Rabatt (%)',
                'value'=>
                    function ($data) {
                       foreach ($data->customerDiscount as $k=>$v) {
                            if ($v['offer_item_type_id'] == 4) {
                                return Html::a($v['base_discount_perc'], ['customer-discount/index', 'CustomerDiscountSearch[customer_id]' => $data->name, 'CustomerDiscountSearch[offer_item_type_id]' => $data->customerDiscount[$k]->offerItemType->name]);
                            }
                        }
                    },
                'format' => 'raw',                     
                'contentOptions' => ['style' => 'text-align: right;'], 
                
            ],
            [
                'attribute'=>'DANA Rabatt (%)',
                'value'=>
                    function ($data) {
                       foreach ($data->customerDiscount as $k=>$v) {
                            if ($v['offer_item_type_id'] == 5) {
                                return Html::a($v['base_discount_perc'], ['customer-discount/index', 'CustomerDiscountSearch[customer_id]' => $data->name, 'CustomerDiscountSearch[offer_item_type_id]' => $data->customerDiscount[$k]->offerItemType->name]);
                            }
                        }
                    },
                'format' => 'raw',   
                'contentOptions' => ['style' => 'text-align: right;'], 
                
            ],  
            [
                'attribute'=>'A',
                'value'=>function ($data) {
                    return Html::a(So::find()->where(['customer_id'=>$data->id])->count(), ['so/index', 'SoSearch[customer_id]' => $data->name]);
                },
                'format' => 'raw',                    
                'contentOptions' => ['style' => 'text-align: right;'], 
                
            ],
            [
                'attribute'=>'O',
                'value'=>function ($data) {
                    return Html::a(Offer::find()->where(['customer_id'=>$data->id])->count(), ['offer/index', 'OfferSearch[customer_id]' => $data->name]);
                },
                'format' => 'raw',
                'contentOptions' => ['style' => 'text-align: right;'], 

            ],            
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
                        return Url::to(['customer/view', 'id'=>$model->id]);
                    }
                    if ($action === 'update') {
                        return Url::to(['customer/update', 'id'=>$model->id]);
                    }
                    if ($action === 'delete') {
                        return Url::to(['customer/delete', 'id'=>$model->id]);
                    }                    
                }
            ],
        ],
    ]); 

    ?>

<style type="text/css">
    .grid-view th {
    white-space: normal;
}
.action-column {
    white-space: nowrap;
}
</style>
</div>
