<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\OfferItem */

$this->title = 'Update Offer Item: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Offer Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="offer-item-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
