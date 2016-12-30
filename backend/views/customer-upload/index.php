<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use dosamigos\datepicker\DatePicker;

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

    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'customer_id',
                'value' => 'customer.name'
            ],
  //          'file_path',
            'file_name',
            'file_extension',
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

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
