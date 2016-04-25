<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\OfferItemType */

$this->title = 'Create Offer Item Type';
$this->params['breadcrumbs'][] = ['label' => 'Offer Item Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="offer-item-type-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
