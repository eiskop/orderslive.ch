<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\Pjax;
use backend\models\Offer;
use backend\models\OfferSearch;
use backend\models\So;
use backend\models\SoSearch;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
$dataProvider->pagination->pageSize = 0;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php

        if(Yii::$app->user->identity->username == 'tae') {
            echo Html::a('Create User', ['create'], ['class' => 'btn btn-primary']).' ';
        }  
        echo Html::a('Filter rücksetzen', ['index'], ['class' => 'btn btn-success']);       
    ?>
    

<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute'=>'id',
                'value'=>'id',
                'contentOptions' => ['style' => 'width:50px; text-align: right; '],
            ],
            'first_name',
            'last_name',
            'username',
            [
                'attribute' => 'offers_taken_no',
                'label' => 'Offerten<br>geholt',
                'encodeLabel' => false,
                'value'=> function ($data) {
                    return Html::a($data->offerFinishedCount, ['offer/index', 'OfferSearch[assigned_to]' => $data->id, 'OfferSearch[status_id]' => [3]], ['data-pjax' => '0']);
                },
                'format' => 'raw', 
                'contentOptions' => ['style' => 'width:50px; text-align: right; '],
            ],
            [
                'attribute' => 'offers_in_progress_no',
                'label' => 'Offerten im<br>Bearbeitung',
                'encodeLabel' => false,
                'value'=> function ($data) {
                    return Html::a($data->offerInProgressCount, ['offer/index', 'OfferSearch[assigned_to]' => $data->id, 'OfferSearch[status_id]' => [1, 2, 6, 7]], ['data-pjax' => '0']);
                },
                'format' => 'raw', 
                'contentOptions' => ['style' => 'width:50px; text-align: right; '],
            ],
            [
                'attribute' => 'offers_created_no',
                'label' => 'Offerten <br>erstellt',
                'encodeLabel' => false,
                'value'=> function ($data) {
                    return Html::a($data->offersCreatedCount, ['offer/index', 'OfferSearch[created_by]' => $data->id, 'OfferSearch[status_id]' => [1, 2, 3, 5, 6, 7]], ['data-pjax' => '0']);
                },
                'value'=> 'offersCreatedCount',
                'format' => 'raw',                 
                'contentOptions' => ['style' => 'width:50px; text-align: right; '],
            ],              
            [
                'attribute' => 'so_complete_no',
                'label' => 'Auftrag<br>erledigt',
                'encodeLabel' => false,
                'value'=> function ($data) {
                    return Html::a($data->soFinishedCount, ['so/index', 'SoSearch[assigned_to]' => $data->id, 'SoSearch[status_id]' => 3], ['data-pjax' => '0']);
                },
                'format' => 'raw',                    
                'contentOptions' => ['style' => 'width:50px; text-align: right; '],
            ],
            [
                'attribute' => 'so_in_progress_no',
                'label' => 'Aufträge im<br>Bearbeitung',
                'encodeLabel' => false,

                'value'=> function ($data) {
                    return Html::a($data->soInProgressCount, ['so/index', 'SoSearch[assigned_to]' => $data->id, 'SoSearch[status_id]' => [1,2]], ['data-pjax' => '0']);
                },
                'format' => 'raw',                    
                'contentOptions' => ['style' => 'width:50px; text-align: right; '],
            ],     
            [
                'attribute' => 'so_created_no',
                'label' => 'Aufträge<br>erstellt',
                'encodeLabel' => false,

                'value'=> function ($data) {
                    return Html::a($data->soCreatedCount, ['so/index', 'SoSearch[created_by]' => $data->id, 'SoSearch[status_id]' => [1,2,3]], ['data-pjax' => '0']);
                },
                'format' => 'raw',                 
                'contentOptions' => ['style' => 'width:50px; text-align: right; '],
            ],               

                           
            //'username',

            // 'auth_key',
            // 'password_hash',
            // 'password_reset_token',
            // 'email:email',
            // 'status',
            // 'product_group_id',
            // 'created_at',
            // 'updated_at',

            [  
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['style' => 'max-width:70px; min-width:50px;'],
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
                        if (Yii::$app->user->can('admin')) 
                        {
                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                                        'title' => Yii::t('app', 'Update'),                              
                            ]);
                        }
                    },
                    'delete' => function ($url, $model) {
                        if (Yii::$app->user->can('admin')) 
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
                        return Url::to(['user/view', 'id'=>$model->id]);
                    }
                    if ($action === 'update') {
                        return Url::to(['user/update', 'id'=>$model->id]);
                    }
                    if ($action === 'delete') {
                        return Url::to(['user/delete', 'id'=>$model->id]);
                    }                    
                }
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
