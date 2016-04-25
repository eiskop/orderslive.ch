<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\SoItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'So Items';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="so-item-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create So Item', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'so_id',
            'so_item_no',
            'qty',
            'value',
            // 'created_by',
            // 'created',
            // 'changed_by',
            // 'changed',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
