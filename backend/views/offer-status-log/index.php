<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\Pjax;
use backend\models\User;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\OfferStatusLogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Offer Status Logs');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="offer-status-log-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Offer Status Log'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            [
                'attribute'=>'customer_id',
                'value'=>'customer.name',
                'contentOptions' => ['style' => 'width:300px'],
            ],
            'offer.offer_no',
            [
                'attribute'=>'followup_by_id',
                'value'=>'followupBy.last_name',
                'filter'=>Html::activeDropDownList($searchModel, 'followup_by_id', ArrayHelper::map(User::find()->where(['active'=>1, 'show_in_lists'=>1])->asArray()->orderBy(['last_name' => SORT_ASC])->all(), 'id', 'last_name'), ['class'=>'', 'prompt' => 'Alle']),                
            ],  
            'contact_date:date',
            'customer_contact',
            'topics:ntext',
            'next_steps:ntext',
            'next_followup_date:date',
            // 'status_id',
            // 'comments:ntext',
            [
                'attribute'=>'assigned_to',
                'value'=>'assignedTo.last_name',
                'filter'=>Html::activeDropDownList($searchModel, 'assigned_to', ArrayHelper::map(User::find()->where(['active'=>1, 'show_in_lists'=>1])->asArray()->orderBy(['last_name' => SORT_ASC])->all(), 'id', 'last_name'), ['class'=>'', 'prompt' => 'Alle']),                
            ],  
            //'created_by',
            //'created',
            // 'updated_by',
            // 'updated',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
