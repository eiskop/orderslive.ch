<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Offer */

$this->title = 'Offerte hinzufügen';
$this->params['breadcrumbs'][] = ['label' => 'Offerten', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="offer-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form_create', [
        'model' => $model,
        'modelsOfferItem' => $modelsOfferItem,
        'modelChange' => $modelChange,
    ]) ?>

</div>
