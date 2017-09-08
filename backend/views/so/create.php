<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\So */

$this->title = 'Auftrag hinzufügen';
$this->params['breadcrumbs'][] = ['label' => 'Aufträge', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
if (isset($_GET['id']) AND $_GET['id'] != '' AND $_GET['id'] != FALSE) {
	echo '<h3 class="bg-success">Auftrag mit ID '.$_GET['id'].' wurde hinzugefügt!</h3>';	
}
if ($model->requested_delivery_year != TRUE ){
	$model->requested_delivery_year = date('Y', time());
}
if ($model->requested_delivery_week != TRUE ){
	$model->requested_delivery_week = date('W', time());
}
?>
<div class="so-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
