<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\CustomerUpload */

$this->title = Yii::t('app', '{modelClass}: ', [
    'modelClass' => 'Kundendatei ändern',
]) . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Kundendateien'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="customer-upload-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form_update', [
        'model' => $model,
    ]) ?>

</div>
