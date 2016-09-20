<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Change */

$this->title = 'Create Change';
$this->params['breadcrumbs'][] = ['label' => 'Changes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="change-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
