<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\CustomerDiscount */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Customer Discount',
]) . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Customer Discounts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="customer-discount-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
