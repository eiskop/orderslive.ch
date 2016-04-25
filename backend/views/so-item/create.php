<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\SoItem */

$this->title = 'Create So Item';
$this->params['breadcrumbs'][] = ['label' => 'So Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="so-item-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
