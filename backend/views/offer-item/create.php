<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\OfferItem */

$this->title = 'Create Offer Item';
$this->params['breadcrumbs'][] = ['label' => 'Offer Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="offer-item-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
