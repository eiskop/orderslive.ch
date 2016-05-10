<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Change */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="change-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'change_object')->dropDownList([ 'offer' => 'Offer', 'so' => 'So', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'change_time')->textInput() ?>

    <?= $form->field($model, 'change_type')->textInput() ?>

    <?= $form->field($model, 'change_reason')->textInput() ?>

    <?= $form->field($model, 'measure')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'responsible')->textInput() ?>

    <?= $form->field($model, 'duration_min')->textInput() ?>

    <?= $form->field($model, 'comment')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created')->textInput() ?>

    <?= $form->field($model, 'created_by')->textInput() ?>

    <?= $form->field($model, 'updated')->textInput() ?>

    <?= $form->field($model, 'updated_by')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
