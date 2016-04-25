<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\SoStatus */

$this->title = 'Create So Status';
$this->params['breadcrumbs'][] = ['label' => 'So Statuses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="so-status-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
