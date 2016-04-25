<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\SoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="so-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'product_group_id') ?>

    <?= $form->field($model, 'customer.name') ?>

    <?= $form->field($model, 'customer_order_no') ?>

    <?= $form->field($model, 'confirmation_no') ?>

    <?php // echo $form->field($model, 'surface') ?>

    <?php // echo $form->field($model, 'status_id') ?>

    <?php // echo $form->field($model, 'value') ?>

    <?php // echo $form->field($model, 'order_received') ?>

    <?php // echo $form->field($model, 'customer_priority_id') ?>

    <?php // echo $form->field($model, 'days_to_process') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'created') ?>

    <?php // echo $form->field($model, 'changed_by') ?>

    <?php // echo $form->field($model, 'changed') ?>

    <div class="form-group">
        <?= Html::submitButton('Suchen', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Rücksetzen', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
