<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\DevelopmentLogCommentSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="development-log-comment-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'development_log_id') ?>

    <?= $form->field($model, 'comment') ?>

    <?= $form->field($model, 'created') ?>

    <?= $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'changed') ?>

    <?php // echo $form->field($model, 'changed_by') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
