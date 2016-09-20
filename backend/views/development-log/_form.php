<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\DevelopmentLog */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="development-log-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'priority')->textInput() ?>

    <?= $form->field($model, 'completion_perc')->textInput() ?>

    <?= $form->field($model, 'task_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'task_description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'developer_id')->textInput() ?>

    <?= $form->field($model, 'estimated_start_time')->textInput() ?>

    <?= $form->field($model, 'estimated_completion_time')->textInput() ?>

    <?= $form->field($model, 'approved_by_id')->textInput() ?>

    <?= $form->field($model, 'approved_date')->textInput() ?>



    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
