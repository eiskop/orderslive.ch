<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use backend\models\ProductGroup;
use backend\models\Customer;
use backend\models\CustomerPriority;
use backend\models\CustomerDiscount;
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
            $model->processed_by_id = Yii::$app->user->identity->id;    
            $model->offer_received = date('d-m-Y', time());
        }
        // for redirecting to form with a predefined customer_id
        if ($model->customer_id != TRUE AND isset($_GET['customer_id'])) {
            $model->customer_id = $_GET['customer_id'];
        }
        //END: for redirecting to form with a predefined customer_id         
        
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
                <?= $form->field($model, 'processed_by_id')->dropDownList(ArrayHelper::map(User::find()->where(['active'=>1, 'show_in_lists'=>1])->orderBy(['last_name' => SORT_ASC])->all(), 'id', 'last_name'), [
                    'prompt'=>'Wählen ',
                    'onchange'=>'
                    $.post("index.php?r=user/index&id='.'"+$(this).val(), function (data) {
                        $("select#user-id").html(data);
                    });'

                ]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'customer_id')->dropDownList(ArrayHelper::map(Customer::find()->where(['active'=>1])->orderBy(['name'=>SORT_ASC])->all(), 'id', 'nameAndStreet', 'name'), [
                    'prompt'=>'Kunde wählen',
                    'options'=> [
                        $model->customer_id => ['Selected'=>true],
                    ],
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
            <div class="col-md-2">
                <?= $form->field($model, 'customer_contact')->textInput() ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'customer_order_no')->textInput(['maxlength' => true]) ?>
            </div>        
            <div class="col-md-2">
                <?= $form->field($model, 'offer_wir_id')->textInput(['maxlength' => 20]) ?>
            </div>
            <div class="col-md-2">
                <?= $form->field($model, 'confirmation_no')->textInput(['maxlength' => true]) ?>
            </div>              
        </div>        
        <div class="row">
            <div class="col-md-4 hidden">
                <?= $form->field($model, 'product_group_id')->dropDownList(ArrayHelper::map(ProductGroup::find()->all(), 'id', 'name'), [
                    'prompt'=>'Wählen ',
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
            <div class="col-md-6">
               <?= $form->field($model, 'carpenter')->textInput() ?>
            </div>

            <div class="col-md-2">
                <?= $form->field($model, 'status_id')->dropDownList(ArrayHelper::map(OfferStatus::find()->orderBy('name')->all(), 'id', 'name'), [
                    'onchange'=>'
                    $.post("index.php?r=offer-status/index&id='.'"+$(this).val(), function (data) {
                        $("select#status-id").html(data);
                    });'

                ]) ?>
            </div>
            <div class="col-md-2">
                <?= $form->field($model, 'assigned_to')->dropDownList(ArrayHelper::map(User::find()->where(['active'=>1, 'show_in_lists'=>1])->orderBy(['last_name' => SORT_ASC])->all(), 'id', 'last_name'), [
                    'prompt'=>'Wählen ',
                    'onchange'=>'
                    $.post("index.php?r=user/index&id='.'"+$(this).val(), function (data) {
                        $("select#user-id").html(data);
                    });'

                ]) ?>
            </div> 
            <div class="col-md-2">
                <?= $form->field($model, 'followup_by_id')->dropDownList(ArrayHelper::map(User::find()->where(['active'=>1, 'show_in_lists'=>1])->orderBy(['last_name' => SORT_ASC])->all(), 'id', 'last_name'), [
                    'prompt'=>'Wählen ',
                    'onchange'=>'
                    $.post("index.php?r=user/index&id='.'"+$(this).val(), function (data) {
                        $("select#user-id").html(data);
                    });'

                ]) ?>
            </div> 
        <div class="row">
            <div class="col-md-12">
                <?= $form->field($model, 'comments')->textArea(['rows' => '5']) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?= $form->field($model, 'uploadedFiles[]')->fileInput(['multiple' => true]) ?>
            </div>
        </div>                   
    </div>

</div>

<?php 
/*Html::a('Your Link name','index.php?r=customer/index', [
'title' => Yii::t('yii', 'Close'),
    'onclick'=>"
     $.ajax({
    type     :'POST',
    cache    : false,
    url  : 'index.php?r=customer/index',
    success  : function(response) {
       console.log(response);
           }
    });return false;",
                ]);
*/?>




<div class="table">

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Kopfdaten erfassen' : 'Ändern', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

<?php
$script = <<< JS
        $(document).ready(function () {

            $('#check_discount').click(
                function () {
                    console.log($("[id='-offer_item_type_id']"));
                    $("[id$='-offer_item_type_id']").each(
                        function () {
                            var customer_id = $("#offer-customer_id").val();
                            var offer_item_type_id = $(this).val();
                            var item = $(this).attr("id").split("-"); 
                            var data;

                            $.get("index.php?r=offer/get-product-discount", {customer_id : customer_id, offer_item_type_id: offer_item_type_id}, 
                                function(data) {
                                    var data = $.parseJSON(data);
                                    $('body').data('data', data);
                                    


                                }
                            ).always(function(){
                                    if (jQuery.isEmptyObject($('body').data('data'))) {
                                        discount = 0;
                                    }
                                    else {
                                        var discount = $('body').data('data').base_discount_perc;
                                    }
                                    

                                    $('#offeritem-'+item[1]+'-base_discount_perc').val(discount);
                                    var bruto_value = $('#offeritem-'+item[1]+'-value_total').val();
                                    bruto_value = (Math.round(bruto_value * 20) / 20).toFixed(2);
                                    var bd_perc = (100-discount)/100;
                                   
                                    $('#offeritem-'+item[1]+'-value_total').attr('value', bruto_value);

                                    var net_value_bd = bruto_value*bd_perc;

                                    var net_value_bd_rounded = (Math.round(net_value_bd * 20) / 20).toFixed(2);

                                    $('#offeritem-'+item[1]+'-value_total_net').val(net_value_bd_rounded);
                                    var project_discount_perc = (100-$('#offeritem-'+item[1]+'-project_discount_perc').val())/100
                                    var net_value_total = net_value_bd_rounded*project_discount_perc;
                                    var number = (Math.round(net_value_total * 20) / 20).toFixed(2);
                                    $('#offeritem-'+item[1]+'-order_line_net_value').val(number);
                            });                    
                        }
                    ); 
                }
            );
        });
JS;
$this->registerJs($script, \yii\web\View::POS_END);

?>


</div>