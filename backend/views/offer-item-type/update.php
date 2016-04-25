<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\OfferItemType */

$this->title = 'Update Offer Item Type: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Offer Item Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="offer-item-type-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
