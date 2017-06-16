<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use dosamigos\datepicker\DatePicker;
use yii\widgets\ActiveForm;
use common\models\User;

/* @var $this yii\web\View */
/* @var $model backend\models\OfferStatusLog */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="offer-status-log-form">

    <?php $form = ActiveForm::begin(); 
        if (isset($model->contact_date)) {
            $model->contact_date = date('d-m-Y',strtotime($model->contact_date));    
        }
        if (isset($model->next_followup_date)) {
            $model->next_followup_date = date('d-m-Y',strtotime($model->next_followup_date));    
        }
        
    ?>

   

    <?= $form->field($model, 'followup_by_id')->textInput() ?>

    <?= $form->field($model, 'customer_id')->textInput() ?>

    <?= $form->field($model, 'customer_contact')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'contact_date')->widget(
        DatePicker::className(), [
            // inline too, not bad
             'inline' => false, 
             // modify template for custom rendering
            //'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
            'clientOptions' => [
                'autoclose' => true,
                'format' => 'dd-mm-yyyy'
            ]
    ]);?>


    <?= $form->field($model, 'topics')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'next_steps')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'next_followup_date')->widget(
        DatePicker::className(), [
            // inline too, not bad
             'inline' => false, 
             // modify template for custom rendering
            //'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
            'clientOptions' => [
                'autoclose' => true,
                'format' => 'dd-mm-yyyy'
            ]
    ]);?>    
     

    <?= $form->field($model, 'comments')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'assigned_to')->dropDownList(ArrayHelper::map(User::find()->where(['active'=>1, 'show_in_lists'=>1])->orderBy(['last_name' => SORT_ASC])->all(), 'id', 'last_name'), [
        'prompt'=>'Wählen ',
        'onchange'=>'
        $.post("index.php?r=user/index&id='.'"+$(this).val(), function (data) {
            $("select#user-id").html(data);
        });'

    ]) ?>

    <?php
        $form->field($model, 'created_by')->textInput();
        $form->field($model, 'created')->textInput();
        $form->field($model, 'updated_by')->textInput();
        $form->field($model, 'updated')->textInput();
    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
