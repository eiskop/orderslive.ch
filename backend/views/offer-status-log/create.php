<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\OfferStatusLog */

$this->title = Yii::t('app', 'Create Offer Status Log');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Offer Status Logs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="offer-status-log-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
