<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\OfferItem */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Offer Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="offer-item-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'offer_id',
            'offer_item_type_id',
            'qty',
            'value',
            'project_discount_perc',
            'value_net',
            'created_by',
            'created',
            'changed_by',
            'changed',
        ],
    ]) ?>

</div>
