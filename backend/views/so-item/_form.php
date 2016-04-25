<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\SoItem */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="so-item-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'so_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'so_item_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'qty')->textInput() ?>

    <?= $form->field($model, 'value')->textInput() ?>

    <?= $form->field($model, 'created_by')->textInput() ?>

    <?= $form->field($model, 'created')->textInput() ?>

    <?= $form->field($model, 'changed_by')->textInput() ?>

    <?= $form->field($model, 'changed')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
