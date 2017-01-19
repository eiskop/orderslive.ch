<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\models\Customer;
use dosamigos\datepicker\DatePicker;

/* @var $this yii\web\View */
/* @var $model backend\models\CustomerUpload */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="customer-upload-form">
    <?php



     $form = ActiveForm::begin(['options'=>['enableClientValidation' => true, 'enableAjaxValidation' => false, 'validateOnChange'=> false, 'id' => 'dynamic-form', 'enctype' => 'multipart/form-data']]); 
        $model->valid_from = date('d.m.Y', time());
        $model->valid_to = date('d.m.Y', strtotime('+ 1 year'));     
        // for redirecting to form with a predefined customer_id
        if ($model->customer_id != TRUE AND isset($_GET['customer_id'])) {
            $model->customer_id = $_GET['customer_id'];
        }
        //END: for redirecting to form with a predefined customer_id        

    ?>

    <?= $form->field($model, 'customer_id')->dropDownList(ArrayHelper::map(Customer::find()->where(['active'=>1])->orderBy(['customer.name'=>SORT_ASC])->all(), 'id', 'nameAndStreet', 'name'), [
        'prompt'=>'Kunde auswählen',
        'options'=>[$model->customer_id => ['Selected'=>true]],
        'onchange'=>'
            $.post("index.php?r=customer/index&id='.'"+$(this).val(), function (data) {
                $("select#customer-id").html(data);
            });'

    ]) ?>

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

    <?= $form->field($model, 'uploadedFiles[]')->fileInput(['multiple' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
