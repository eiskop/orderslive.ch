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

<script language="javascript">
        function reloadPage() {
            location.reload();        
        }
        setInterval(reloadPage, 60000);
</script>    
</head>
<body >
<?php $this->beginBody() ?>

<!--<div class="wrap"> -->
    <div class="container" style="margin-top: 1%; margin-left: 1%; margin-right: 1%; font-size: 2.3em; width: 98%;">
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
<!--</div> -->

<!--<footer class="footer">
    <div class="container">
        
    </div>
</footer>
-->
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
