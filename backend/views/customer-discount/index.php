<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\CustomerDiscountSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Konditionen');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customer-discount-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
       
        <?php // echo $this->render('_search', ['model' => $searchModel]); 
            if (Yii::$app->user->can('create-customerdiscount')) 
            {
                echo Html::a('Kondition erfassen', ['create'], ['class' => 'btn btn-success']).' ';
            }
            echo Html::a('Filter rücksetzen', ['index'], ['class' => 'btn btn-success']).' ';           
            echo Html::a('Kunden', ['customer/index'], ['class' => 'btn btn-primary']).' ';

        ?>        
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
