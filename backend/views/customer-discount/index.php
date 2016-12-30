<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\CustomerDiscountSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Customer Discounts');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customer-discount-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Customer Discount'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute'=>'id',
                'contentOptions'=>['style'=>'width:30px;text-align:right;'],
            ],
            [
                'attribute'=>'customer_id',
                'value'=>'customer.name',
                'contentOptions'=>['style'=>'width:600px;'],
            ],
            [
                'attribute'=>'offer_item_type_id',
                'value'=>'offerItemType.name',
                'contentOptions'=>['style'=>'width:100px;'],
            ],            
            [
                'attribute' => 'base_discount_perc',
                'contentOptions'=>['style'=>'width:30px;text-align:right;'],
                'format' => ['decimal', 1],
            ],
            [
                'attribute'=>'valid_from',
                'contentOptions'=>['style'=>'width:100px;'],
                'format' => ['date'],
            ],              
            // 'created',
            // 'created_by',
            // 'updated',
            // 'updated_by',
            // 'approved',
            // 'approved_by',
            // 'active',

            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions'=>['style'=>'width:70px;'],
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
