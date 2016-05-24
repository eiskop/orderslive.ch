<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\DevelopmentLogComment */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Development Log Comment',
]) . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Development Log Comments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="development-log-comment-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
