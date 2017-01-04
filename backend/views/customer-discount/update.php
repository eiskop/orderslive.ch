<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\CustomerDiscount */

$this->title = Yii::t('app', ' {modelClass} ', [
    'modelClass' => 'Kondition Nr.',
]) . $model->id.' '.$this->title = Yii::t('app', 'ändern');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Konditionen'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Ändern');
?>
<div class="customer-discount-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
