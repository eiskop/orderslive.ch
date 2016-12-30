<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\CustomerUpload */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Customer Upload',
]) . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Customer Uploads'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="customer-upload-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form_update', [
        'model' => $model,
    ]) ?>

</div>
