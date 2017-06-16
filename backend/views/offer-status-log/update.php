<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\OfferStatusLog */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Offer Status Log',
]) . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Offer Status Logs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="offer-status-log-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
