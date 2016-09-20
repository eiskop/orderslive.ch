<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Change */

$this->title = 'Update Change: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Changes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="change-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
