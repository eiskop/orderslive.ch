<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use dosamigos\datepicker\DatePicker;
use kartik\datecontrol\Module;
use backend\models\Customer;
use backend\models\OfferItemType;


/* @var $this yii\web\View */
/* @var $model backend\models\CustomerDiscount */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="customer-discount-form">

    <?php 
        $form = ActiveForm::begin(); 
        // for redirecting to form with a predefined customer_id
        if ($model->customer_id != TRUE AND isset($_GET['customer_id'])) {
            $model->customer_id = $_GET['customer_id'];
        }
        //END: for redirecting to form with a predefined customer_id      
        if ($model->valid_from != FALSE) {
            $model->valid_from = date('d.m.Y', strtotime($model->valid_from));
        }  
    ?>

    <?= $form->field($model, 'customer_id')->dropDownList(ArrayHelper::map(Customer::find()->where(['active'=>1])->orderBy(['customer.name'=>SORT_ASC])->all(), 'id', 'nameAndStreet', 'name'), [
        'prompt'=>'Kunde auswählen',
        'options' => [$model->customer_id => ['Selected'=>true]],
        'onchange'=>'
            $.post("index.php?r=customer/index&id='.'"+$(this).val(), function (data) {
                $("select#customer-id").html(data);
            });'

    ]) ?>
    <?= $form->field($model, 'offer_item_type_id')->dropDownList(ArrayHelper::map(OfferItemType::find()->where(['active'=>1])->orderBy(['sorting'=>SORT_ASC])->all(), 'id', 'name'), [
        'prompt'=>'Positionstyp auswählen',
        'options' => [$model->offer_item_type_id => ['Selected'=>true]],
        'onchange'=>'
            $.post("index.php?r=customer/index&id='.'"+$(this).val(), function (data) {
                $("select#customer-id").html(data);
            });'

    ]) ?>


    <?= $form->field($model, 'base_discount_perc')->textInput() ?>

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

    <?= $form->field($model, 'active')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Speichern') : Yii::t('app', 'Speichern'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
