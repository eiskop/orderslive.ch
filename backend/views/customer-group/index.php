<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CustomerGroupSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Customer Groups';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customer-group-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Customer Group', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'contact',
            'street',
            'zip_code',
            // 'city',
            // 'province',
            // 'fax_no',
            // 'tel_no',
            // 'created',
            // 'created_by',
            // 'updated',
            // 'updated_by',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
<?php
$db = new yii\db\Connection([
    'dsn' => 'mysql:host=localhost;dbname=orderslive',
    'username' => 'orderslive',
    'password' => 'KqPKFye73MPSsWF7',
    'charset' => 'utf8',
]);
date_default_timezone_set('Europe/Zurich');

?>
