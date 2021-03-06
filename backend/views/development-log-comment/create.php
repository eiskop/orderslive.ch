<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\DevelopmentLogComment */

$this->title = Yii::t('app', 'Create Development Log Comment');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Development Log Comments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="development-log-comment-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
