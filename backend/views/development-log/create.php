<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\DevelopmentLog */

$this->title = Yii::t('app', 'Create Development Log');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Development Logs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="development-log-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
