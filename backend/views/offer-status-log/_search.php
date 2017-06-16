<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\OfferStatusLogSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="offer-status-log-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'offer_id') ?>

    <?= $form->field($model, 'followup_by_id') ?>

    <?= $form->field($model, 'customer_id') ?>

    <?= $form->field($model, 'customer_contact') ?>

    <?php // echo $form->field($model, 'contact_date') ?>

    <?php // echo $form->field($model, 'topics') ?>

    <?php // echo $form->field($model, 'next_steps') ?>

    <?php // echo $form->field($model, 'next_followup_date') ?>

    <?php // echo $form->field($model, 'status_id') ?>

    <?php // echo $form->field($model, 'comments') ?>

    <?php // echo $form->field($model, 'assigned_to') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'created') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'updated') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
