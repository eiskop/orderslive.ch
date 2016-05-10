<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Offer */

$this->title = 'Ändern von Offerte: ' . $model->offer_no.' - '.$model->customer->name.', Komission: '.$model->customer_order_no;
$this->params['breadcrumbs'][] = ['label' => 'Offerten', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Ändern';


?>
<div class="offer-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'modelsOfferItem' => (empty($modelsOfferItem)) ? [new OfferItem] : $modelsOfferItem,
        'modelChange' => $modelChange    
    ]) ?>

</div>
