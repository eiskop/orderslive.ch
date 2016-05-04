<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\SelectMenu */

$this->title = 'Create Select Menu';
$this->params['breadcrumbs'][] = ['label' => 'Select Menus', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="select-menu-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
