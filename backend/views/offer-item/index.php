<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\OfferItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Offer Items';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="offer-item-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Offer Item', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'offer_id',
            'offer_item_type_id',
            'qty',
            'value',
            'project_discount_perc',
            'value_net',
            // 'created_by',
            // 'created',
            // 'changed_by',
            // 'changed',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
