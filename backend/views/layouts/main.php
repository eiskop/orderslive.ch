<?php

/* @var $this \yii\web\View */
/* @var $content string */


use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <style>
        html {
            overflow: -moz-scrollbars-vertical;
            overflow-y: scroll;
        }

    </style>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'JELD-WEN Schweiz AG',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $menuItems = [
        ['label' => 'Home', 'url' => ['/site/index']],
    ];
    

    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
    } else {
        $menuItems[] = [
            'label' => 'Aufträge',
            'url' => ['/so/index'],
        ];
        $menuItems[] = [
            'label' => 'Offerten',
            'url' => ['/offer/index'],
        ];        
        if (Yii::$app->user->can('view-customer')) 
        {
            $menuItems[] = [
                'label' => 'Kunden', 
                'items' =>[
                    [
                        'label' => 'Kunden', 
                        'url' => ['/customer/index'],
                    ],
                    [
                        'label' => 'Konditionen', 
                        'url' => ['/customer-discount/index'],
                    ],
                    [
                        'label' => 'Dateien', 
                        'url' => ['/customer-upload/index'],
                    ],                    
                ],
            ];
            //echo Html::a('Kunden', ['create'], ['class' => 'btn btn-success']);
        }  
        if (Yii::$app->user->can('view-user')) 
        {
            $menuItems[] = [
                'label' => 'Benutzer', 
                'url' => ['/user/index']
            ];
            //echo Html::a('Kunden', ['create'], ['class' => 'btn btn-success']);
        } 
        if (Yii::$app->user->can('reports')) 
        {
            $menuItems[] = [
                'label' => 'Berichte', 
                'url' => ['/report/index']
            ];
            //echo Html::a('Kunden', ['create'], ['class' => 'btn btn-success']);
        }  

        if(Yii::$app->user->identity->username == 'tae') {
            $menuItems[] = [
                'label' => 'Add user', 
                'url' => ['/site/signup']
            ];
        }    
        $menuItems[] = [
            'label' => 'Logout (' . Yii::$app->user->identity->username . ')',
            'url' => ['/site/logout'],
            'linkOptions' => ['data-method' => 'post']
        ];
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>
<!--
<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>
-->
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
