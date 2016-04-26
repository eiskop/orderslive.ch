<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use backend\models\Customer;
use backend\models\CustomerGroup;
use backend\models\So;
use backend\models\SoSearch;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CustomerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Customers';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="customer-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Customer', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            [
                'attribute'=>'customer_group_id',
                'value'=>'customerGroup.name',
            ],
            'customer_priority_id',
            //'contact',
            'street',
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
                'attribute'=>'sos',
                'value'=>function ($data, $c_id = 'id') {
                    return So::find()->where(['customer_id'=>$c_id])->count();
                },
                
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
    ]); ?>

</div>
