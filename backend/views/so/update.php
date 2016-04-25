<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\So */

$this->title = 'Ändern von Auftrag: ' . ' ' . $model->id. '';
$this->params['breadcrumbs'][] = ['label' => 'Aufträge', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Ändern';
?>
<div class="so-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
