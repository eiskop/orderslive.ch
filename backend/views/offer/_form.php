<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use backend\models\ProductGroup;
use backend\models\Customer;
use backend\models\CustomerPriority;
use backend\models\OfferStatus;
use backend\models\Offer;
use backend\models\OfferItem;
use backend\models\OfferItemType;
use backend\models\Change;
use backend\models\SelectMenu;
use backend\models\Model;
use common\models\User;
use kartik\select2\Select2;
use dosamigos\datepicker\DatePicker;
use kartik\typeahead\Typeahead;
use wbraganca\dynamicform\DynamicFormWidget;

   
/* @var $this yii\web\View */
/* @var $model backend\models\Offer */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="offer-form">


    <?php 
        $form = ActiveForm::begin(['options'=>['enableClientValidation' => true, 'enableAjaxValidation' => false, 'validateOnChange'=> false, 'id' => 'dynamic-form', 'enctype' => 'multipart/form-data']]); 
        
        if ($_GET['r'] == 'offer/create') { // check if action is create ... so on update it wouldn't change the product group.
            $model->product_group_id = Yii::$app->user->identity->product_group_id;    
            $model->offer_received = date('d-m-Y', time());
        }
        

    ?>

  
    <div class="table"> 
        <div class="row">
            <div class="col-md-2">    
                <?= $form->field($model, 'offer_received')->widget(
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
                <?= $form->field($model, 'processed_by_id')->dropDownList(ArrayHelper::map(User::find()->all(), 'id', 'username'), [
                    'prompt'=>'Select ',
                    'onchange'=>'
                    $.post("index.php?r=user/index&id='.'"+$(this).val(), function (data) {
                        $("select#user-id").html(data);
                    });'

                ]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'customer_id')->dropDownList(ArrayHelper::map(Customer::find()->all(), 'id', 'nameAndStreet', 'name'), [
                    'prompt'=>'Select Customer',
                    'onchange'=>'
                        $.post("index.php?r=customer/index&id='.'"+$(this).val(), function (data) {
                            $("select#customer-id").html(data);
                        });'

                ]) ?>
            </div>
            <div class="col-md-2" style="font-size: 2em; margin-top: 1.5%;">
                <?= $form->field($model, 'prio1')->checkBox(['label' => 'Prio.1.', 'uncheck' => '0', 'checked' => '1']) ?>
            </div>           
        </div>
        <div class="row">
            <div class="col-md-3">
                <?= $form->field($model, 'customer_contact')->textInput() ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'customer_order_no')->textInput(['maxlength' => true]) ?>
            </div>        
            <div class="col-md-3">
                <?= $form->field($model, 'offer_wir_id')->textInput(['maxlength' => 20]) ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'confirmation_no')->textInput(['maxlength' => true]) ?>
            </div>              
        </div>        
        <div class="row">
            <div class="col-md-4 hidden">
                <?= $form->field($model, 'product_group_id')->dropDownList(ArrayHelper::map(ProductGroup::find()->all(), 'id', 'name'), [
                    'prompt'=>'Select ',
                    'onchange'=>'
                    $.post("index.php?r=product-group/index&id='.'"+$(this).val(), function (data) {
                        $("select#product-group-id").html(data);
                    });'

                ]) ?>
            </div>
            <div class="col-md-8 hidden">
                <?= $form->field($model, 'qty')->textInput() ?>
            </div>             
        </div>
        <div class="row">
            <div class="col-md-3">
                <?= $form->field($model, 'status_id')->dropDownList(ArrayHelper::map(OfferStatus::find()->orderBy('name')->all(), 'id', 'name'), [
                    'onchange'=>'
                    $.post("index.php?r=offer-status/index&id='.'"+$(this).val(), function (data) {
                        $("select#status-id").html(data);
                    });'

                ]) ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'followup_by_id')->dropDownList(ArrayHelper::map(User::find()->all(), 'id', 'username'), [
                    'prompt'=>'Select ',
                    'onchange'=>'
                    $.post("index.php?r=user/index&id='.'"+$(this).val(), function (data) {
                        $("select#user-id").html(data);
                    });'

                ]) ?>
            </div>            
        </div>    
        <div class="row">
            <div class="col-md-12">
                <?= $form->field($model, 'comments')->textArea(['rows' => '5']) ?>
            </div>
        </div>        
    </div>


<div class="table">
  <div class="panel panel-default">
        <div class="panel-heading"><h4><i class="glyphicon glyphicon-list-alt"></i>Offerten Positonen</h4></div>
        <div class="panel-body">
             <?php 
//echo '<pre>';
//echo var_dump($modelsOfferItem);
//echo '</pre>';
             DynamicFormWidget::begin([
                'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
                'widgetBody' => '.container-items', // required: css class selector
                'widgetItem' => '.item', // required: css class
                'limit' => 100, // the maximum times, an element can be cloned (default 999)
                'min' => 1, // 0 or 1 (default 1)
                'insertButton' => '.add-item', // css class
                'deleteButton' => '.remove-item', // css class
                'model' => $modelsOfferItem[0],
                'formId' => 'dynamic-form',
                'formFields' => [
                    'offer_item_type_id',
                    'qty',
                    'value',
                    'project_discount_perc',
                    'value_net',
                ],
            ]); ?>
            <div class="container-items"><!-- widgetContainer -->
            <?php foreach ($modelsOfferItem as $i => $modelOfferItem): ?>
                <div class="item panel panel-default"><!-- widgetBody -->
                    <div class="panel-body">
                        <?php
                            // necessary for update action.
                            if (! $modelOfferItem->isNewRecord) {
                                echo Html::activeHiddenInput($modelOfferItem, "[{$i}]id");
                            }
                        ?>
                       
                        <div class="row">
                            <div class="col-sm-2">
                                <?= $form->field($modelOfferItem, "[{$i}]offer_item_type_id")->dropDownList(ArrayHelper::map(OfferItemType::find()->all(), 'id', 'name'), [
                                    'prompt'=>'Select ',
                                    'onchange'=>'
                                    $.post("index.php?r=offer-item-type/index&id='.'"+$(this).val(), function (data) {
                                        $("select#product-group-id").html(data);
                                    });'

                                ]) ?>

                            </div>
                            <div class="col-sm-1">
                                <?= $form->field($modelOfferItem, "[{$i}]qty")->textInput(['maxlength' => true]) ?>
                            </div>
                            <div class="col-sm-2">
                                <?= $form->field($modelOfferItem, "[{$i}]value")->textInput(['maxlength' => true]) ?>
                            </div>
                            <div class="col-sm-2">
                                <?= $form->field($modelOfferItem, "[{$i}]project_discount_perc")->textInput(['maxlength' => true]) ?>
                            </div>
                            <div class="col-sm-2">
                                <?= $form->field($modelOfferItem, "[{$i}]value_net")->textInput(['maxlength' => true, 'readonly' => true]) ?>
                            </div>  
                            <div class="col-sm-2">
                                <?= $form->field($modelOfferItem, "[{$i}]value_total_net")->textInput(['maxlength' => true, 'readonly' => true]) ?>
                            </div>  
                            <div class="col-sm-1">
                                <button type="button" class="add-item btn btn-success btn-sm" style="margin-top: 10%;"><i class="glyphicon glyphicon-plus"></i></button>
                                <button type="button" class="remove-item btn btn-danger btn-sm" style="margin-top: 10%;"><i class="glyphicon glyphicon-minus"></i></button>
                            </div>
                        </div>

                    </div>
                </div>
            <?php endforeach; ?>
            </div>
            <?php DynamicFormWidget::end(); ?>
        </div>
    </div>
</div>
<div class="table">
    <h2>Dateien hinzufügen</h2>
    <div class="row">
        <div class="col-md-12">
            
             <?= $form->field($model, 'uploadedFiles[]')->fileInput(['multiple' => true]) ?>

        </div>
    </div>
</div>
<div class="table">
    <h2>Informationen zur Änderung</h2>
    <div class="row">
        <div class="col-md-4">                
            <?= $form->field($modelChange, 'change_type')->dropDownList(ArrayHelper::map(SelectMenu::find()->where(['model_name' => 'offer'])->andWhere(['select_name' => 'change_type'])->andWhere(['status'=>1])->orderBy('option_name')->all(), 'id', 'option_name'), [
                'prompt'=>'Select ',
                'onchange'=>'
                $.post("index.php?r=select_menu/index&id='.'"+$(this).val(), function (data) {
                    $("select#select_menu-id").html(data);
                });'

            ]) ?>
        </div>
        <div class="col-md-4">                
            <?= $form->field($modelChange, 'change_time')->dropDownList(ArrayHelper::map(SelectMenu::find()->where(['model_name' => 'offer'])->andWhere(['select_name' => 'change_time'])->andWhere(['status'=>1])->orderBy('option_name')->all(), 'id', 'option_name'), [
                'prompt'=>'Select ',
                'onchange'=>'
                $.post("index.php?r=select_menu/index&id='.'"+$(this).val(), function (data) {
                    $("select#select_menu-id").html(data);
                });'

            ]) ?>
        </div>            
        <div class="col-md-4">                
            <?= $form->field($modelChange, 'change_reason')->dropDownList(ArrayHelper::map(SelectMenu::find()->where(['model_name' => 'offer'])->andWhere(['select_name' => 'change_reason'])->andWhere(['status'=>1])->orderBy('option_name')->all(), 'id', 'option_name'), [
                'prompt'=>'Select ',
                'onchange'=>'
                $.post("index.php?r=select_menu/index&id='.'"+$(this).val(), function (data) {
                    $("select#select_menu-id").html(data);
                });'

            ]) ?>
        </div>               
    </div>
   <div class="row">
        <div class="col-md-6">                
            <?= $form->field($modelChange, 'responsible')->dropDownList(ArrayHelper::map(User::find()->all(), 'id', 'username'), [
                'prompt'=>'Select ',
                'onchange'=>'
                $.post("index.php?r=user/index&id='.'"+$(this).val(), function (data) {
                    $("select#user-id").html(data);
                });'

            ]) ?>
        </div>
        <div class="col-md-6">                
            <?= $form->field($modelChange, "duration_min")->textInput(['maxlength' => true]) ?>
        </div>            
    </div>    
    <div class="row">
        <div class="col-md-6">                
                <?= $form->field($modelChange, 'measure')->textArea(['rows' => '3']) ?>
        </div>                       
        <div class="col-md-6">
            <?= $form->field($modelChange, 'comment')->textArea(['rows' => '3']) ?>
        </div>
    </div>  
</div>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Hinzufügen' : 'Ändern', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); 
        $this->registerJs('
            $(document).ready(function () {
                $("#offer-customer_id").focus();
            });
            

        ');
    ?>


</div>