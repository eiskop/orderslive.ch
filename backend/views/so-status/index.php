<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\SoStatusSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'So Statuses';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="so-status-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create So Status', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'active',
            'created',
            'changed',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
