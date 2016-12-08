<?php
namespace backend\models;

use Yii;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use backend\models\Customer;
use backend\models\So;
use backend\models\Offer;
use backend\models\CustomerPriority;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;

//use kartik\datecontrol\DateControl;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\SoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */





date_default_timezone_set('Europe/Zurich');
$this->title = 'Offerten zu Aufträge auf Basis von Offerte';

//$this->params['breadcrumbs'][] = $this->title;
//
 


?>

<div class="so-index">




    <!--<h1><?= Html::encode($this->title) ?></h1> -->
    <?php // echo $this->render('_search', ['model' => $searchModel]); 
  
/*
    $orders = So::find()	
    ->where([
		'and', 
		['<=', 'deadline', time()+60*60*24],
		['>=', 'qty', 30],
	])
    ->orderBy('deadline', 'asc')->orderBy('qty', 'desc')
    ->all();
   // echo 'deadline';
foreach ($orders as $order) {
	//echo var_dump($order->id.' / deadline '.date('d.m.Y H:i:s', $order->deadline).' / qty '.$order->qty );
}

$orders2 = So::find()
	->where([
		'and', 
		['<=', 'deadline', time()+60*60*24],
		['<=', 'qty', 30],
	])
    ->orderBy('deadline')->orderBy('qty')
    ->all();
//    echo 'qty';
foreach ($orders2 as $order) {
//	echo var_dump($order->id.' / qty '.$order->qty);
}

$orders3 = So::find()
	->where([
		'and', 
		['>=', 'deadline', time()+60*60*24],
		['<=', 'qty', 30],
		])
   ->orderBy('deadline')
   ->all();
  // echo 'rest';
foreach ($orders3 as $order) {
	//echo var_dump($order->id.' / qty '.$order->qty);
}
*/
/*
$db = new yii\db\Connection([
    'dsn' => 'mysql:host=localhost;dbname=orderslive',
    'username' => 'orderslive',
    'password' => 'KqPKFye73MPSsWF7',
    'charset' => 'utf8',
]);*/
$db = new yii\db\Connection([
    'dsn' => 'mysql:host=localhost;dbname=orderslive_dev',
    'username' => 'root',
    'password' => 'tuhatjatuline',
    'charset' => 'utf8',
]);
date_default_timezone_set('Europe/Zurich');
echo '<h2>Offerten zu Aufträge</h2>';
//
// aufträge, stk pro tag
//SELECT date(updated) as datum, product_group.name, Sum(qty) as qty, count(*) as aufträge FROM `so` left join product_group on so.product_group_id = product_group.id WHERE status_id=3 Group by product_group_id, Date(updated) ORDER BY datum, name
// Weekly per product group
//SELECT week(updated) as datum, product_group.name, Sum(qty) as qty, count(*) as aufträge FROM `so` left join product_group on so.product_group_id = product_group.id WHERE status_id=3 and product_group_id = 2 Group by product_group_id, WEEK(updated) ORDER BY datum, name
$offers_all = $db->createCommand('SELECT count(*) AS offers, sum(qty) as qty, product_group.name as product_group_name, customer.region FROM offer 
    left join product_group on offer.product_group_id = product_group.id
    left join customer on offer.customer_id = customer.id
    WHERE status_id != 0 and status_id != 4
        GROUP BY product_group_id, region')->queryAll();


$kellpax_offers = 0;
$wirus_offers = 0;
$kellpax_offers_d_ch = 0;
$kellpax_offers_w_ch = 0;
$wirus_offers_d_ch = 0;
$wirus_offers_w_ch = 0;

foreach ($offers_all as $k=>$v) {
    $offers_var_txt = strtolower($v['product_group_name']).'_offers';
    $offers_region_var_txt = strtolower($v['product_group_name']).'_offers_'.str_replace('-', '_', strtolower($v['region']));
    ${$offers_var_txt} += $v['offers'];
    ${$offers_region_var_txt} += $v['offers'];
} 

$total_offers = ($kellpax_offers+$wirus_offers);
$kellpax_offers_perc = $kellpax_offers/$total_offers;
$wirus_offers_perc = $wirus_offers/$total_offers;


$orders_all = $db->createCommand('SELECT count(*) AS orders, sum(qty) as qty, product_group.name as product_group_name, customer.region FROM offer 
    left join product_group on offer.product_group_id = product_group.id
    left join customer on offer.customer_id = customer.id
    WHERE status_id = 3
        GROUP BY product_group_id, region')->queryAll();

$kellpax_orders = 0;
$wirus_orders = 0;
$kellpax_orders_d_ch = 0;
$kellpax_orders_w_ch = 0;
$wirus_orders_d_ch = 0;
$wirus_orders_w_ch = 0;

foreach ($orders_all as $k=>$v) {
    $orders_var_txt = strtolower($v['product_group_name']).'_orders';
    $orders_region_var_txt = strtolower($v['product_group_name']).'_orders_'.str_replace('-', '_', strtolower($v['region']));
    ${$orders_var_txt} += $v['orders'];
    ${$orders_region_var_txt} += $v['orders'];
} 

$total_orders = ($kellpax_orders+$wirus_orders);
$kellpax_orders_perc = $kellpax_orders/$total_orders;
$wirus_orders_perc = $wirus_orders/$total_orders;


$success_ratio = $total_orders/$total_offers*100;

// PLZ start 1 und 2 ist westschweiz
?>
<div style="text-align: center; width: 90%;">
    <div><h3>Erfolgsquote: <?php echo $total_offers.'/'.$total_orders.' * 100 = '.number_format($success_ratio, 2, '.', '').'%'; ?></h3></div>
    <div id="pie1" class="container" style="width: 500px; display: inline-block;"></div>
    <div id="pie2" class="container" style="width: 500px; display: inline-block;"></div>
    <div id="pie3" class="container" style="width: 500px;"></div>
    <h4>W-CH - Kellpax <?php echo $kellpax_offers_w_ch ?>, Wirus <?php echo $wirus_offers_w_ch ?></h4>
    <h4>D-CH - Kellpax <?php echo $kellpax_offers_d_ch ?>, Wirus <?php echo $wirus_offers_d_ch ?></h4>
</div>
<?php


echo 
\dosamigos\highcharts\HighCharts::widget([
    'clientOptions' => [
        'chart' => [
                'type' => 'pie',
                'renderTo' => 'pie1',
                'width' => '500',
                'height' => '250'                
        ],
        'title' => [
             'text' => 'Aufträge'
             ],
    ]
]);

$this->registerJs("
$(function () {
    $('#highcharts-0').highcharts({
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: 'Gewonnen vs. Verloren'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %<br>{point.y} Stk.',
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                    }
                }
            },
            series: {
                animation: false
            }            
        },
        series: [{
            name: 'Anteil',
            colorByPoint: true,
            data: [{
                name: 'Gewonnen',
                y: ".($total_orders)."
            }, {
                name: 'Verloren',
                y: ".($total_offers-$total_orders)."
            }]
        }]
    });
});
    ");


echo 
\dosamigos\highcharts\HighCharts::widget([
    'clientOptions' => [
        'chart' => [
                'type' => 'pie',
                'renderTo' => 'pie2',
                'width' => '500',
                'height' => '250'
        ],
        'title' => [
             'text' => 'Offerten'
             ],
    ]
]);


$this->registerJs("
$(function () {
    $('#highcharts-2').highcharts({
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: 'Gesamt Aufträge'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %<br>{point.y} Stk.',
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                    }
                }
            },
            series: {
                animation: false
            }            
        },
        series: [{
            name: 'Anteil',
            colorByPoint: true,
            data: [{
                name: 'Kellpax',
                y: ".($kellpax_offers)."
            }, {
                name: 'Wirus',
                y: ".($wirus_offers)."
            }]
        }]
    });
});
    ");

echo 
\dosamigos\highcharts\HighCharts::widget([
    'clientOptions' => [
        'chart' => [
                'type' => 'pie',
                'renderTo' => 'pie3',
                'width' => '500',
                'height' => '250'                
        ],
        'title' => [
             'text' => 'Aufträge'
             ],
    ]
]);
$d_ch_total = $kellpax_offers_d_ch+$wirus_offers_d_ch;
$w_ch_total = $kellpax_offers_w_ch+$wirus_offers_w_ch;

$this->registerJs("
$(function () {
    $('#highcharts-4').highcharts({
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: 'Anteill Offerten W-CH vs D-CH'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %<br>{point.y} Offerten.',
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                    }
                }
            },
            series: {
                animation: false
            }            
        },
        series: [{
            name: 'Anteil',
            colorByPoint: true,
            data: [{
                name: 'D-CH',
                y: ".($d_ch_total)."
            }, {
                name: 'W-CH',
                y: ".($w_ch_total)."
            }]
        }]
    });
});
    ");




$res = $db->createCommand('SELECT offer.id, UNIX_TIMESTAMP(offer.offer_received) as datum_uts, date(offer.offer_received) as datum, WEEK(offer.offer_received, 3) as woche, product_group.name as product_group_name, offer.qty, offer.offer_no, customer.name as customer_name, customer_order_no, offer_status.name as status, offer.status_id as status_id
    FROM `offer` 
    left join product_group on offer.product_group_id = product_group.id 
    left join customer on offer.customer_id = customer.id
    left join offer_status on status_id = offer_status.id
    WHERE status_id = 3 ORDER BY datum DESC, product_group_name, customer_name')
            ->queryAll();

          // echo var_dump($res);
          // exit;



$provider = new ArrayDataProvider([
    'allModels' => $res,
    'pagination' => [
        'pageSize' => 1000000,
    ],
    'sort' => [
        'attributes' => ['datum', 'product_group_name'],
    ],

]);
/*
echo '<pre>alle aufträge<br>';
echo var_dump($orders_all);
echo var_dump($kellpax_orders);
echo '<pre>alle offerten<br>';
echo var_dump($offers_all);
echo '</pre>';
*/
?>


    <?= GridView::widget([
        'summary'=>'', 
        'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
        'dataProvider' => $provider,
        'rowOptions' => 

            function ($model) {

// SELECT * FROM `order` WHERE `subtotal` > 200 ORDER BY `id`

                //echo var_dump($orders);

                //echo var_dump(Yii::$app->formatter->asDate($model->order_received, 'php:Y-m-d'));
                //echo var_dump($model->order_received);
         //   $customer = Customer::findOne(['id'=>$model->customer_id]);
          //  echo var_dump($customer);
            //$prio = CustomerPriority::findOne(['id'=>$customer->customer_priority_id]);
        //  echo var_dump($prio);

                //if ($model['prio1'] == '1') {
                //  return ['class' => 'info'];
                //} 


            }
        ,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'header' => 'ID',
                'attribute' => 'id',
                'contentOptions' => ['style' => 'width:20px; text-align: right;'],
            ],
            [
                'header' => 'Produkt',
                'attribute' => 'product_group_name',
            ],
            [
                'header' => 'Woche',
                'attribute' => 'woche',
                'contentOptions' => ['style' => 'width:50px; text-align: right;'],
            ],            
            [
                'header' => 'Datum',
                'attribute' => 'datum',
                'contentOptions' => ['style' => 'width:100px'],
            ],
            [
                'header'=>'Kunde',
                'attribute'=>'customer_name',
                'value'=>'customer_name'
            ],
            [
                'header'=>'Objekt/Best.-Nr.',
                'attribute'=>'customer_order_no',
                'value'=>'customer_order_no'
            ],            
            [
                'header'=>'Offert-Nr.',
                'attribute'=>'offer_no',
                'value'=>'offer_no'
            ],
            [
                'header'=>'Stk.',
                'attribute'=>'qty',
                'value'=>'qty',
                'contentOptions' => ['style' => 'width:50px; text-align: right;']
            ],
            [
                'header'=>'Status',
                'attribute'=>'status',
                'value'=>'status',
                'contentOptions' => ['style' => 'width:50px;']
            ],
//          [
//              'header' => 'Kommentar',
//              'attribute' => 'comments',
//          ],               //$plays = Play::find()->joinWith(['userPlays'])->where(['user_play.user_id' => Yii::$app->user->id])->all();
            // 'created',
            // 'updated_by',
            // 'updated',

        //    ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); 

?>





</div>
