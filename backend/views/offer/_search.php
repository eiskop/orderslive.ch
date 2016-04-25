<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\OfferSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="offer-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'offer_wir_id') ?>

    <?= $form->field($model, 'processed_by_id') ?>

    <?= $form->field($model, 'followup_by_id') ?>

    <?= $form->field($model, 'product_group_id') ?>

    <?= $form->field($model, 'customer.name') ?>

    <?php // echo $form->field($model, 'customer_contact') ?>

    <?= $form->field($model, 'customer.name') ?>

    <?php // echo $form->field($model, 'customer_order_no') ?>

    <?php // echo $form->field($model, 'confirmation_no') ?>

    <?php // echo $form->field($model, 'qty') ?>

    <?php // echo $form->field($model, 'prio1') ?>

    <?= $form->field($model, 'status.name') ?>

    <?php // echo $form->field($model, 'value') ?>

    <?php // echo $form->field($model, 'offer_received') ?>

    <?php // echo $form->field($model, 'customer_priority_id') ?>

    <?php // echo $form->field($model, 'days_to_process') ?>

    <?php // echo $form->field($model, 'deadline') ?>

    <?php // echo $form->field($model, 'comments') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'created') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'updated') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
