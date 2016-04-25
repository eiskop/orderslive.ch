<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\SoStatus */

$this->title = 'Update So Status: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'So Statuses', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="so-status-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
