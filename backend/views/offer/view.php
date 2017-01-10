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
use backend\models\SelectMenu;
use backend\models\OfferUpload;
use backend\models\OfferUploadSearch;
use backend\models\Customer;
use backend\models\CustomerDiscount;


/* @var $this yii\web\View */
/* @var $model backend\models\Offer */
/* @var $searchModel backend\models\OfferSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $model->offer_no.' - '.$model->customer->name.', Komission: '.$model->customer_order_no;
$this->params['breadcrumbs'][] = ['label' => 'Offerten', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="offer-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php 
            if (Yii::$app->user->can('create-offer')) 
            {
                echo Html::a('Offerte Hinzufügen', ['create'], ['class' => 'btn btn-success']);
            }
        
        ?>
        <?php 
            if (Yii::$app->user->can('change-offer')) 
            {
                echo Html::a('Ändern', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']);
            }
        
        ?>        
        <?php 
            if (Yii::$app->user->can('delete-offer')) 
            {
                echo Html::a('Löschen', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Bist du sicher, dass du diese Offerte löschen willst?',
                        'method' => 'post',
                    ],
                ]);
            }
        
        ?>

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
            'confirmation_no',
            'offer_wir_id',
            [
                'attribute'=>'status_id',
                'value'=>$model->status->name,
            ],       
            [
                'attribute'=>'customer_id',
                'value'=>Html::a($model->customer->name, ['customer/view', 'id' => $model->customer_id]),
                'format' => 'raw'
            ],  
            'customer_order_no',
            'customer_contact',
            'carpenter',  
            'prio1',
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
                'value'=>  (($model->value > 0) ? number_format((100-$model->value_net/$model->value*100), 2, '.', '') : "0"),
                //(($model->voucher_category ==0) ? "Income Voucher": (($model->voucher_category ==1)? "Exepense Voucher" : "General Voucher")),
            ],                           
            
            [
                'attribute'=>'offer_received',
                'value'=>date('d.m.Y', strtotime($model->offer_received)),
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
                'value'=>($model->followupBy->first_name.' '.$model->followupBy->last_name),
            ],          
            [
                'attribute'=>'updated',
                'value'=> call_user_func (
                    function ($data) {
                        if ($data->updated != '0000-00-00 00:00:00') {
                            return date('d.m.Y H:i:s', strtotime($data->updated));            
                        }
                        else {
                            return '-';
                        }
                        
                    //date('d.m.Y H:i:s', strtotime($model->updated)),    
                    }
                , $model),
                
            ],
            [
                'attribute'=>'updated_by',
                'value'=>($model->updatedBy->first_name.' '.$model->updatedBy->last_name),
            ],  
        ],
    ]) ?>

<?php Pjax::begin(); ?> 
        <h2>Positionen für Offerte #<?= $model->offer_no ?></h2>
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
                ],
                [
                    'attribute'=>'qty',
                    'value'=>'qty',
                    'contentOptions' => ['style' => 'text-align:right;'],
                ],                              
                [
                    'attribute'=>'value',
                    'value'=>'value',
                    'contentOptions' => ['style' => 'text-align:right;'],
                    'format'=>['decimal', 2],
                ],
                [
                    'attribute'=>'value_total',
                    'value'=>'value_total',
                    'contentOptions' => ['style' => 'text-align:right;'],
                    'format'=>['decimal', 2],
                ], 
                [
                    'attribute' => 'base_discount_perc',
                    'value' => 'base_discount_perc',
                    'contentOptions' => ['style' => 'text-align:right;'],
                ],
                [
                    'attribute'=>'value_total_net',
                    'value'=>'value_total_net',
                    'contentOptions' => ['style' => 'text-align:right;'],
                    'format'=>['decimal', 2],
                ],                                        
                [
                    'attribute'=>'project_discount_perc',
                    'value'=>'project_discount_perc',
                    'contentOptions' => ['style' => 'text-align:right;'],
                ], 
                [
                    'attribute'=>'order_line_net_value',
                    'value'=>'order_line_net_value',
                    'contentOptions' => ['style' => 'text-align:right;'],
                    'format'=>['decimal', 2],
                ], 
                               
            ],
        ]); ?>
<?php Pjax::end(); ?> 

<?php Pjax::begin(); ?> 

    <h2>Dateien</h2>
        <hr />
           

        <?= GridView::widget([
        'dataProvider' => $dataProvider3,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'file_extension',
            [
                'attribute'=>'file_name',
                'format'=>'raw',
                'value'=> function ($data) {
                    $a = OfferUploadSearch::findOne(['offer_id'=>$data->offer_id, 'file_name'=>$data->file_name]);
                    $file = basename($a->file_name);
                    return '<a href="'.$a->file_path.'" target="_blank" data-pjax="0">'.$a->file_name.'</a>';
                }

            ],            
            'title',
            'description',
            [
                'attribute'=>'created',
                'format' => ['date', 'php:d.m.y H:i'],
            ],
            [
                'attribute'=>'created_by',
                'value'=>'createdBy.username',
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
                                 //  'data-pjax' => 'w0',
                                    'data-pjax' => '0',
                        ]);
                    },
                    'update' => function ($url, $model) {
                        if (Yii::$app->user->can('change-offerupload')) 
                        {
                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                                        'title' => Yii::t('app', 'Update'),                              
                            ]);
                        }
                    },
                    'delete' => function ($url, $model) {
                        if (Yii::$app->user->can('change-offerupload')) 
                        {
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                                        'title' => Yii::t('app', 'Delete'),
                                        'data-confirm' => 'Are you sure you want to delete this item?',
                                        'data-method' => 'post',
                                        'data-pjax' => '0',

                            ]);
                        }
                    },                                           
                ],
                'urlCreator' => function ($action, $model, $key, $index) {
                    if ($action === 'view') {
                        return Url::to(['offer-upload/view', 'id'=>$model->id]);
                    }
                    if ($action === 'update') {
                        return Url::to(['offer-upload/update', 'id'=>$model->id]);
                    }
                    if ($action === 'delete') {
                        return Url::to(['offer-upload/delete', 'id'=>$model->id]);
                    }                    
                }
            ],            
        ],
    ]); ?>
    <h5 style ="font-color: grey; font-size:0.9em;">Internet Explorer Benutzer müssen fürs Download mit der Rechten Mausknopf auf dem Link klicken und "Ziel speichern unter..." wählen</h5>
    <?php Pjax::end(); ?>





<?php Pjax::begin(); ?> 
<?php
   $duration_sum = 0;
    if (!empty($dataProvider2->getModels())) {
        foreach ($dataProvider2->getModels() as $key => $val) {
            $duration_sum += $val->duration_min;
        }
    }
?>
    <h2>Änderungen für Offerte #<?= $model->offer_no.' - '.$duration_sum.' Minuten' ?></h2>
        <hr />
           

        <?= GridView::widget([
        'dataProvider' => $dataProvider2,
        'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute'=>'created',
                'format' => ['date', 'php:d.m.y H:i'],
            ],
            [
                'attribute'=>'change_time',
                'value'=> function($data) {
                    if (!is_null($data->change_time)) {
                        $values = ArrayHelper::map(SelectMenu::find()->where(['model_name' => 'offer'])->andWhere(['select_name' => 'change_time'])->andWhere(['status'=>1])->orderBy('option_name')->all(), 'id', 'option_name');
                        return $values[$data->change_time];
                    }
                },
            ],
            [
                'attribute'=>'change_type',
                'value'=> function($data) {
                    if (!is_null($data->change_type)) {
                        $values = ArrayHelper::map(SelectMenu::find()->where(['model_name' => 'offer'])->andWhere(['select_name' => 'change_type'])->andWhere(['status'=>1])->orderBy('option_name')->all(), 'id', 'option_name');
                        return $values[$data->change_type];
                    }
                    
                },
            ],
            [
                'attribute'=>'change_reason',
                'value'=> function($data) {
                    if (!is_null($data->change_reason)) {
                        $values = ArrayHelper::map(SelectMenu::find()->where(['model_name' => 'offer'])->andWhere(['select_name' => 'change_reason'])->andWhere(['status'=>1])->orderBy('option_name')->all(), 'id', 'option_name');
                        return $values[$data->change_reason];
                    }
                    
                },
            ],
            [
                'attribute'=>'duration_min',
                'value'=>'duration_min',
                'contentOptions' => ['style' => 'width:50px; text-align: right;'],
            ],
            [
                'attribute'=>'responsible',
                'value'=>'responsible0.username',
            ],
            'measure',
            'comment',
            [
                'attribute'=>'created_by',
                'value'=>'createdBy.username',
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>



</div>
