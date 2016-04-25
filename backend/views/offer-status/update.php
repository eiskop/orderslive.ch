<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\OfferStatus */

$this->title = 'Update Offer Status: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Offer Statuses', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="offer-status-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
