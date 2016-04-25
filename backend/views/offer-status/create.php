<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\OfferStatus */

$this->title = 'Create Offer Status';
$this->params['breadcrumbs'][] = ['label' => 'Offer Statuses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="offer-status-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
