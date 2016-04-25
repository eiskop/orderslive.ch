<?php


use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use backend\models\ProductGroup;
use backend\models\Customer;
use backend\models\CustomerPriority;
use backend\models\SoStatus;
use backend\models\So;
use common\models\User;
use kartik\select2\Select2;
use dosamigos\datepicker\DatePicker;
use kartik\typeahead\Typeahead;

   



/* @var $this yii\web\View */
/* @var $model backend\models\So */
/* @var $form yii\widgets\ActiveForm */


/*
    <?= $form->field($model, 'created_by')->textInput() ?>

    <?= $form->field($model, 'created')->textInput() ?>

    <?= $form->field($model, 'updated_by')->textInput() ?>

    <?= $form->field($model, 'updated')->textInput() ?>

    <?= $form->field($model, 'value')->textInput() ?>
    <?= $form->field($model, 'order_received')->textInput() ?>


*/

?>

<div class="so-form">

    <?php 
        $form = ActiveForm::begin([ 'enableClientValidation' => true, 'enableAjaxValidation' => false, 'validateOnChange'=> false]); 
        
        if ($_GET['r'] == 'so/create') { // check if action is create ... so on update it wouldn't change the product group.
            $model->product_group_id = Yii::$app->user->identity->product_group_id;    
            $model->order_received = date('d-m-Y', time());
        }
        

    ?>


   
    <table class="table" style="width: 50%"> 
        <tr>
            <td colspan="3">
                <?= $form->field($model, 'customer_id')->dropDownList(ArrayHelper::map(Customer::find()->all(), 'id', 'nameAndStreet', 'name'), [
                    'prompt'=>'Select Customer',
                    'onchange'=>'
                        $.post("index.php?r=customer/index&id='.'"+$(this).val(), function (data) {
                            $("select#customer-id").html(data);
                        });'

                ]) ?>
            </td>        
        </tr>
        <tr>
            <td colspan="2" style="width: 50%;"><?= $form->field($model, 'customer_order_no')->textInput(['maxlength' => true]) ?></td>
            <td>    
                <?= $form->field($model, 'order_received')->widget(
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
            </td>            
        </tr>        
        <tr>
            <td colspan="3">
                <table class="table-noborder" style="width: 100%;">
                    <tr>
                        <td style="padding-right: 2%;">
                            <?= $form->field($model, 'product_group_id')->dropDownList(ArrayHelper::map(ProductGroup::find()->all(), 'id', 'name'), [
                                'prompt'=>'Select ',
                                'onchange'=>'
                                $.post("index.php?r=product-group/index&id='.'"+$(this).val(), function (data) {
                                    $("select#product-group-id").html(data);
                                });'

                            ]) ?>
                        </td>
                        <td style="padding-right: 2%;" >    
                            <?php
                                $surfaces_r = ArrayHelper::map(So::find()->select(['id', 'surface'])->all(), 'id', 'surface');
                                $surfaces = array();
                                foreach ($surfaces_r as $k=>$v) {
                                    $surfaces[] = $v;

                                }
                                echo $form->field($model, 'surface')->widget(Typeahead::classname(), [
                                    'options' => ['placeholder' => ''],
                                    'pluginOptions' => ['highlight'=>true],
                                    'dataset' => [
                                        [
                                            'local' => $surfaces,
                                            'limit' => 10
                                        ]
                                    ]
                                ]); 
                            ?>
                        </td> 


                        <td style="padding-right: 2%;"><?= $form->field($model, 'qty')->textInput() ?></td>             
                    </tr>
                </table>

            </td>
        </tr>
        <tr>
            <td colspan="3">
                <table class="table-noborder" style="width: 100%;">
                    <tr>
                        <td style="padding-right: 2%; width: 50%;">
                            <?= $form->field($model, 'offer_no')->textInput(['maxlength' => 20]) ?>
                        </td>
                        <td style="padding-left: 2%; padding-right: 2%;"><?= $form->field($model, 'confirmation_no')->textInput(['maxlength' => true]) ?></td>           
                    </tr>
                </table>

            </td>
        </tr>    
        <tr>
            <td colspan="3">
                <table class="table-noborder" style="width: 100%;">
                    <tr>
                        <td style="padding-right: 2%; width: 30%;">
                            <?= $form->field($model, 'status_id')->dropDownList(ArrayHelper::map(SoStatus::find()->orderBy('name')->all(), 'id', 'name'), [
                                'onchange'=>'
                                $.post("index.php?r=so-status/index&id='.'"+$(this).val(), function (data) {
                                    $("select#status-id").html(data);
                                });'

                            ]) ?>
                        </td>
                        <td style="font-size: 1.5em; vertical-align: bottom; text-align: center; padding-left: 2%; width: 30%;"><?= $form->field($model, 'prio1')->checkBox(['label' => 'Prio.1.', 'uncheck' => '0', 'checked' => '1']) ?></td>           
                    </tr>
                </table>

            </td>
        </tr>    
        <tr>
            <td colspan="3"><?= $form->field($model, 'comments')->textArea(['rows' => '5']) ?></td>
        </tr>        
    </table>
    

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Hinzufügen' : 'Ändern', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); 
        $this->registerJs('
            $(document).ready(function () {
                $("#so-customer_id").focus();
            });
            

        ');
    ?>

</div>
