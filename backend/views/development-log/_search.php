<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\DevelopmentLogSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="development-log-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'priority') ?>

    <?= $form->field($model, 'task_name') ?>

    <?= $form->field($model, 'task_description') ?>

    <?= $form->field($model, 'developer_id') ?>
    <?= $form->field($model, 'completion_perc') ?>

    <?php // echo $form->field($model, 'estimated_start_time') ?>

    <?php // echo $form->field($model, 'estimated_completion_time') ?>

    <?php // echo $form->field($model, 'approved_by_id') ?>

    <?php // echo $form->field($model, 'approved_date') ?>

    <?php // echo $form->field($model, 'created') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'changed') ?>

    <?php // echo $form->field($model, 'changed_by') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
