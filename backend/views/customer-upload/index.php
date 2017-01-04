<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use dosamigos\datepicker\DatePicker;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CustomerUploadSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Kundendateien');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customer-upload-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Datei hochladen'), ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('app', 'Filter rücksetzen'), ['index'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Kunden', ['customer/index'], ['class' => 'btn btn-primary']) ?>

    </p>
    
    <?php Pjax::begin(); ?>    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'id',
                'value' => 'id',
                'contentOptions' => ['style' => 'width:20px; text-align: right;'],    
            ],
            

            [
                'attribute' => 'customer_id',
                'value' => 'customer.name'
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
            [
                'attribute'=>'valid_from',
                'value'=>'valid_from',
                'contentOptions' => ['style' => 'width:80px; text-align: right;'],
                'format' => ['date', 'php:d.m.Y'],
                'filter'=>DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'valid_from',
                 //   'template' => '{addon}{input}',
                        'clientOptions' => [
                            'autoclose' => true,
                            'format' => 'dd.mm.yy'
                        ]
                ]),
            ],            

            [
                'attribute'=>'valid_to',
                'value'=>'valid_to',
                'contentOptions' => ['style' => 'width:80px; text-align: right;'],
                'format' => ['date', 'php:d.m.Y'],
                'filter'=>DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'valid_to',
                 //   'template' => '{addon}{input}',
                        'clientOptions' => [
                            'autoclose' => true,
                            'format' => 'dd.mm.yy'
                        ]
                ]),
            ],            

            // 'created',
            // 'created_by',
            // 'changed',
            // 'changed_by',

           [  
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['style' => 'width:50px;'],
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
<?php Pjax::end(); ?></div>
