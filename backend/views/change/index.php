<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\ChangeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Changes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="change-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Change', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'change_object',
            'change_time:datetime',
            'change_type',
            'change_reason',
            // 'measure',
            // 'responsible',
            // 'duration_min',
            // 'comment',
            // 'created',
            // 'created_by',
            // 'updated',
            // 'updated_by',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
