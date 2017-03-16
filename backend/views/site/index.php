<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
//use backend\models\User;
use backend\models\Offer;
use backend\models\OfferStatus;
use backend\models\So;
use backend\models\SoStatus;

$this->title = 'JELD-WEN Orders Live';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>JELD-WEN Orders Live</h1>

        <h2>Offerten</h2>
        <p>
            <?php 
                if (Yii::$app->user->can('create-offer')) 
                {
                   echo '<a class="btn btn-lg btn-success" href="index.php?r=offer/create">Offerte hinzufügen</a> ';
                }

                echo '<a class="btn btn-lg btn-success" href="index.php?r=offer">Offertenliste</a> ';                
                
            ?>
            <a class="btn btn-lg btn-success" href="index.php?r=offer/tv-offer">TV Offer</a>
        </p>
        <p>
            <?php 
//return User::model()->count('user_type <> :type', array('type' => $user_type);
//return User::app()->count('status_id = 1');
                //$statusCount1 = Yii::$app->db->createCommand()->select('COUNT(*)')->from('offer')->where('status_id = 1')->queryScalar();                
                $statuses = OfferStatus::find()->select(['id', 'name'])->where('active = 1')->orderBy(['sorting'=> SORT_ASC])->all();          
                foreach ($statuses as $k=>$v) {
                    $statusCount = Offer::find()->select('count(*) as cnt')->where('status_id = '.$v['id'])->count();
                    echo Html::a(' '.$v['name'].' ('.$statusCount.')', ['offer/index', 'OfferSearch[status_id]' => $v['id']], ['data-pjax' => '0', 'class' => 'link', 'style'=> 'font-size: 0.7em;']);
                    if (count($statuses) != $k+1) {
                        echo ' ';
                    }
                }   
                

                
            ?>
            

        </p>
        <h2>Aufträge</h2>
        <p>
            <?php 
                if (Yii::$app->user->can('create-so')) 
                {
                   echo '<a class="btn btn-lg btn-success" href="index.php?r=so/create">Auftrag hinzufügen</a>';
                }

                
                
            ?>
            
            <a class="btn btn-lg btn-success" href="index.php?r=so">Auftragsliste</a>
            <a class="btn btn-lg btn-success" href="index.php?r=so/tvkellpax">TV Kellpax</a>
            <a class="btn btn-lg btn-success" href="index.php?r=so/tvwirus">TV Wirus</a>
        </p>
        <p>
            <?php
                $statuses = SoStatus::find()->select(['id', 'name'])->where('active = 1')->orderBy(['id'=> SORT_ASC])->all();          
                foreach ($statuses as $k=>$v) {
                    $statusCount = So::find()->select('count(*) as cnt')->where('status_id = '.$v['id'])->count();
                    echo Html::a(' '.$v['name'].' ('.$statusCount.')', ['so/index', 'SoSearch[status_id]' => $v['id']], ['data-pjax' => '0', 'class' => 'link', 'style'=> 'font-size: 0.7em;']);
                    if (count($statuses) != $k+1) {
                        echo ' ';
                    }
                }
            ?>               
        </p>
    </div>


    <div class="body-content">

        <div class="row hidden" >
            <div class="col-lg-4">
                <h2>Heading</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

                <p><a class="btn btn-default" href="http://www.yiiframework.com/doc/">Yii Documentation &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Heading</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

                <p><a class="btn btn-default" href="http://www.yiiframework.com/forum/">Yii Forum &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Heading</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

                <p><a class="btn btn-default" href="http://www.yiiframework.com/extensions/">Yii Extensions &raquo;</a></p>
            </div>
        </div>

    </div>
</div>
