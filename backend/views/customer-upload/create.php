<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\CustomerUpload */

$this->title = Yii::t('app', 'Kundendatei hinzufügen');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Kundendateien'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customer-upload-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form_create', [
        'model' => $model,
    ]) ?>

</div>
