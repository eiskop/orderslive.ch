<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\Customer;
use backend\models\CustomerGroup;
use backend\models\CustomerPriority;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model backend\models\Customer */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="customer-form">

    <?php $form = ActiveForm::begin(); ?>
        <div class="container-fluid" style="margin-top: 40px;">

            <div class="row panel"></div>
            <div class="row panel">

                <div class="col-sm-9">
                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="row panel">            
                <div class="col-sm-6">
                    <?= $form->field($model, 'customer_group_id')->dropDownList(ArrayHelper::map(CustomerGroup::find()->all(), 'id', 'name'), [
                        'onchange'=>'
                            $.post("index.php?r=customer-group/index&id='.'"+$(this).val(), function (data) {
                                $("select#customer-group-id").html(data);
                            });'

                    ]) ?>
                </div>
                <div class="col-sm-3">
                    <?= $form->field($model, 'customer_priority_id')->dropDownList(ArrayHelper::map(CustomerPriority::find()->all(), 'id', 'id'), [
                        'onchange'=>'
                            $.post("index.php?r=customer-priority/index&id='.'"+$(this).val(), function (data) {
                                $("select#customer-priority-id").html(data);
                            });'

                    ]) ?>
                </div>
            </div>            
            <div class="row panel">     
                <h3>Kontakte</h3>
                <div class="col-sm-3"><?= $form->field($model, 'street')->textInput(['maxlength' => true]) ?></div>
                <div class="col-sm-3"><?= $form->field($model, 'city')->textInput(['maxlength' => true]) ?></div>
                <div class="col-sm-1"><?= $form->field($model, 'zip_code')->textInput(['maxlength' => true]) ?></div>
                <div class="col-sm-3"><?= $form->field($model, 'province')->textInput(['maxlength' => true]) ?></div>
            </div>
            <div class="row panel">     
                <div class="col-sm-4"><?= $form->field($model, 'contact')->textInput(['maxlength' => true]) ?></div>
                <div class="col-sm-2"><?= $form->field($model, 'tel_no')->textInput(['maxlength' => true]) ?></div>
                <div class="col-sm-2"><?= $form->field($model, 'fax_no')->textInput(['maxlength' => true]) ?></div>
            </div>
        </div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
