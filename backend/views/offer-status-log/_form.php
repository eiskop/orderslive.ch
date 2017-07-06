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
        else {
            $model->contact_date = date('d-m-Y',time());       
        }

        if (isset($model->next_followup_date)) {
            $model->next_followup_date = date('d-m-Y',strtotime($model->next_followup_date));    
        }
        
    ?>

   

   <div class="row">
        <div class="col-md-2">                
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
        </div>
        <div class="col-md-2">                
            <?= $form->field($model, 'followup_by_id')->dropDownList(ArrayHelper::map(User::find()->where(['active'=>1, 'show_in_lists'=>1])->orderBy(['last_name' => SORT_ASC])->all(), 'id', 'last_name'), [
                'prompt'=>'Select ',
                'onchange'=>'
                $.post("index.php?r=user/index&id='.'"+$(this).val(), function (data) {
                    $("select#user-id").html(data);
                });'

            ]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'customer_contact')->textInput() ?>
        </div>
        <div class="col-md-5">
             <?= $form->field($model, 'topics')->textArea(['rows' => '3']) ?>
        </div>        
    </div>
    <div class="row">
        <div class="col-md-2">                
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
        </div>
        <div class="col-md-2">                
            <?= $form->field($model, 'assigned_to')->dropDownList(ArrayHelper::map(User::find()->where(['active'=>1, 'show_in_lists'=>1])->orderBy(['last_name' => SORT_ASC])->all(), 'id', 'last_name'), [
                'prompt'=>'Select ',
                'onchange'=>'
                $.post("index.php?r=user/index&id='.'"+$(this).val(), function (data) {
                    $("select#user-id").html(data);
                });'

            ]) ?>
        </div>
        <div class="col-md-4">
             <?= $form->field($model, 'next_steps')->textArea(['rows' => '3']) ?>
        </div> 
        <div class="col-md-4">
             <?= $form->field($model, 'comments')->textArea(['rows' => '3']) ?>
        </div>
                 
    </div> 

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
