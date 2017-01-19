<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\models\Customer;
use dosamigos\datepicker\DatePicker;
use kartik\datecontrol\Module;

/* @var $this yii\web\View */
/* @var $model backend\models\CustomerUpload */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="customer-upload-form">

    <?php 
        $form = ActiveForm::begin();       

    ?>

    <?= $form->field($model, 'customer_id')->dropDownList(ArrayHelper::map(Customer::find()->where(['active'=>1, 'id'=>$model->customer_id])->orderBy(['customer.name'=>SORT_ASC])->all(), 'id', 'nameAndStreet', 'name'), ['readonly'=>true]) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'valid_from')->widget(
        DatePicker::className(), [
            // inline too, not bad
             'inline' => false, 
             // modify template for custom rendering
            //'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
            'clientOptions' => [
                'autoclose' => true,
                'format' => 'dd.mm.yyyy'
            ]
    ]);?>
    <?= $form->field($model, 'valid_to')->widget(
        DatePicker::className(), [
            // inline too, not bad
             'inline' => false, 
             // modify template for custom rendering
            //'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
            'clientOptions' => [
                'autoclose' => true,
                'format' => 'dd.mm.yyyy'
            ]
    ]);?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
