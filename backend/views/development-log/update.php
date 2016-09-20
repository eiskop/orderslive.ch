<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\DevelopmentLog */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Development Log',
]) . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Development Logs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="development-log-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
