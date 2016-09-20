<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\OfferUpload */

$this->title = Yii::t('app', 'Create Offer Upload');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Offer Uploads'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="offer-upload-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
