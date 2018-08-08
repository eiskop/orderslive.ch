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
use backend\models\CustomerPriority;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;

//use kartik\datecontrol\DateControl;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\SoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */





date_default_timezone_set('Europe/Zurich');
$this->title = 'Erfasst pro Tag';

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

$db = new yii\db\Connection([
    'dsn' => 'mysql:host=localhost;dbname=orderslive',
    'username' => 'orderslive',
    'password' => 'KqPKFye73MPSsWF7',
    'charset' => 'utf8',
]);
date_default_timezone_set('Europe/Zurich');
echo '<h2>Augträge erfasst pro Tag (12 Monate)</h2>';
//
// aufträge, stk pro tag
//SELECT date(updated) as datum, product_group.name, Sum(qty) as qty, count(*) as aufträge FROM `so` left join product_group on so.product_group_id = product_group.id WHERE status_id=3 Group by product_group_id, Date(updated) ORDER BY datum, name
// Weekly per product group
//SELECT week(updated) as datum, product_group.name, Sum(qty) as qty, count(*) as aufträge FROM `so` left join product_group on so.product_group_id = product_group.id WHERE status_id=3 and product_group_id = 2 Group by product_group_id, WEEK(updated) ORDER BY datum, name
$res = $db->createCommand('SELECT * FROM (SELECT UNIX_TIMESTAMP(order_received) as datum_uts, order_received as datum, WEEK(order_received, 3) as woche, product_group.name as product_group_name, Sum(qty) as qty_in, count(*) as orders_in, concat(order_received, product_group.name ) as link FROM `so` left join product_group on so.product_group_id = product_group.id WHERE status_id != 4  AND DATE(order_received) > DATE(CURDATE() - INTERVAL 12 MONTH) Group by product_group_id, order_received ORDER BY datum, name) t1 
INNER JOIN
(SELECT UNIX_TIMESTAMP(updated) as datum_uts, date(updated) as datum, WEEK(updated, 3) as woche, product_group.name as product_group_name, Sum(qty) as qty_processed, count(*) as orders_processed, concat(date(so.updated), product_group.name ) as link FROM `so` left join product_group on so.product_group_id = product_group.id WHERE status_id=3 AND DATE(so.updated) > DATE(CURDATE() - INTERVAL 12 MONTH)  Group by product_group_id, Date(updated) ORDER BY datum, name) t2
ON t1.link = t2.link
ORDER BY t1.link ASC' )
            ->queryAll();
$res_table = $db->createCommand('SELECT * FROM (SELECT UNIX_TIMESTAMP(order_received) as datum_uts, order_received as datum, WEEK(order_received, 3) as woche, product_group.name as product_group_name, Sum(qty) as qty_in, count(*) as orders_in, concat(order_received, product_group.name ) as link FROM `so` left join product_group on so.product_group_id = product_group.id WHERE status_id != 4 AND DATE(order_received) > DATE(CURDATE() - INTERVAL 12 MONTH) Group by product_group_id, order_received ORDER BY datum, name) t1 
INNER JOIN
(SELECT UNIX_TIMESTAMP(updated) as datum_uts, date(updated) as datum, WEEK(updated, 3) as woche, product_group.name as product_group_name, Sum(qty) as qty_processed, count(*) as orders_processed, concat(date(so.updated), product_group.name ) as link FROM `so` left join product_group on so.product_group_id = product_group.id WHERE status_id=3 AND DATE(so.updated) > DATE(CURDATE() - INTERVAL 12 MONTH) Group by product_group_id, Date(updated) ORDER BY datum, name) t2
ON t1.link = t2.link
ORDER BY t1.link DESC' )
            ->queryAll();



          // echo var_dump($res);
          // exit;



$provider = new ArrayDataProvider([
    'allModels' => $res,
    'pagination' => [
        'pageSize' => 0,
    ],
    'sort' => [
        'attributes' => ['datum', 'product_group_name'],
    ],

]);
$provider_table = new ArrayDataProvider([
    'allModels' => $res_table,
    'pagination' => [
        'pageSize' => 0,
    ],
]);



// get the rows in the currently requested page
$rows = $provider->getModels();
//echo '<pre>';
//echo var_dump($rows);
$kellpax_dates = array();
$kellpax_qtys_in = array();
$kellpax_qtys_processed = array();
$kellpax_orders_in = array();
$kellpax_orders_processed = array();
$wirus_dates = array();
$wirus_qtys_in = array();
$wirus_qtys_processed = array();
$wirus_orders_in = array();
$wirus_orders_processed = array();
$daten = array();
//$test = ArrayHelper::map($rows, 'datum', 'datum');
foreach ($rows as $k=>$v) {
	$date_var = strtolower($v['product_group_name']).'_'.'dates';
	$qty_in_var = strtolower($v['product_group_name']).'_'.'qtys_in';
    $qty_processed_var = strtolower($v['product_group_name']).'_'.'qtys_processed';
	$orders_in_var = strtolower($v['product_group_name']).'_'.'orders_in';
    $orders_processed_var = strtolower($v['product_group_name']).'_'.'orders_processed';
	${$date_var}[] = date('d.m.Y', $v['datum_uts']);
	${$qty_in_var}[] = $v['qty_in'];
    ${$qty_processed_var}[] = $v['qty_processed'];
	${$orders_in_var}[] = $v['orders_in'];
    ${$orders_processed_var}[] = $v['orders_processed'];
	$daten[] = date('d.m.Y', $v['datum_uts']);
	
	
}
//echo var_dump($kellpax_orders);
function arrayToHighchartData($array) {
	$array = join($array, ', ');
	$array = '['.str_replace('"', '', $array).']';
	return $array;
}



//echo $wirus_qtys;
$daten = join($daten, '", "');
$daten = '["'.$daten.'"]';
$kellpax_dates = join($kellpax_dates, '", "');
$kellpax_dates = '["'.$kellpax_dates.'"]';
$wirus_dates = join($wirus_dates, '", "');
$wirus_dates = '["'.$wirus_dates.'"]';


$kellpax_qtys_in = arrayToHighchartData($kellpax_qtys_in);
$kellpax_qtys_processed = arrayToHighchartData($kellpax_qtys_processed);
$wirus_qtys_in = arrayToHighchartData($wirus_qtys_in);
$wirus_qtys_processed = arrayToHighchartData($wirus_qtys_processed);
$kellpax_orders_in = arrayToHighchartData($kellpax_orders_in);
$kellpax_orders_processed = arrayToHighchartData($kellpax_orders_processed);
$wirus_orders_in = arrayToHighchartData($wirus_orders_in);
$wirus_orders_processed = arrayToHighchartData($wirus_orders_processed);
//echo $kellpax_qtys_in;
//$kellpax_qtys = json_encode($kellpax_qtys);
echo 
\dosamigos\highcharts\HighCharts::widget([
    'clientOptions' => [
        'chart' => [
                'type' => 'line'
        ],
        'title' => [
             'text' => 'Aufträge'
             ],
    ]
]);

echo 
\dosamigos\highcharts\HighCharts::widget([
    'clientOptions' => [
        'chart' => [
                'type' => 'line'
        ],
        'title' => [
             'text' => 'Aufträge'
             ],
    ]
]);

        //'series' => [
           // ['name' => 'Jane', 'data' => [1, 0, 4]],
          //  ['name' => 'John', 'data' => [5, 7, 3]]
        //]

    ?>


    <?= GridView::widget([
    	'summary'=>'', 
    	'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
        'dataProvider' => $provider_table,
        'rowOptions' => 

            function ($model) {

// SELECT * FROM `order` WHERE `subtotal` > 200 ORDER BY `id`

            	//echo var_dump($orders);

            	//echo var_dump(Yii::$app->formatter->asDate($model->order_received, 'php:Y-m-d'));
				//echo var_dump($model->order_received);
         //   $customer = Customer::findOne(['id'=>$model->customer_id]);
          //  echo var_dump($customer);
			//$prio = CustomerPriority::findOne(['id'=>$customer->customer_priority_id]);
		//	echo var_dump($prio);

          		//if ($model['prio1'] == '1') {
               	//	return ['class' => 'info'];
               	//} 


            }
        ,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
		    [
		    	'header' => 'Datum',
	            'attribute' => 'datum',
	            'contentOptions' => ['style' => 'width:100px'],
			],
 		    [
		    	'header' => 'Produkt',
	            'attribute' => 'product_group_name',
			],
            [
                'header'=>'Aufträgseingang ',
                'attribute'=>'orders_in',
                'value'=>'orders_in'
            ],
 			[
                'header'=>'Aufträgseingang Türe',
                'attribute'=>'qty_in',
                'value'=>'qty_in'
            ],
            [
                'header'=>'Erfasste Aufträge',
                'attribute'=>'orders_processed',
                'value'=>'orders_processed'
            ],
            [
                'header'=>'Erfasste Türe',
                'attribute'=>'qty_processed',
                'value'=>'qty_processed'
            ],
//        	[
//		    	'header' => 'Kommentar',
//	            'attribute' => 'comments',
//			],               //$plays = Play::find()->joinWith(['userPlays'])->where(['user_play.user_id' => Yii::$app->user->id])->all();
            // 'created',
            // 'updated_by',
            // 'updated',

        //    ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); 

$this->registerJs("
$(function () {
    $('#highcharts-0').highcharts({
        plotOptions: {
            series: {
                animation: false
            }
        },
        title: {
            text: '',
            x: -20 //center
        },
        subtitle: {
            text: '',
            x: -20
        },
        xAxis: {
            categories: ".$kellpax_dates."
        },
        yAxis: {
            title: {
                text: 'Stk.'
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }],
            min: 0
        },
        tooltip: {
            valueSuffix: ''
        },
        legend: {
            layout: 'horizontal',
            align: 'center',
            verticalAlign: 'top',
            borderWidth: 0
        },
        series: [{
            name: 'Kellpax Bestelleingang',
            data: ".$kellpax_orders_in."
        }, {
            name: 'Kellpax Bestelleingang Türe',
            data: ".$kellpax_qtys_in."
        },{
            name: 'Kellpax Erfasste Aufträge',
            data: ".$kellpax_orders_processed."
        }, {
            name: 'Kellpax Erfasste Türe',
            data: ".$kellpax_qtys_processed."
        },]
    });
});
 	");
$this->registerJs("
$(function () {
    $('#highcharts-2').highcharts({
        plotOptions: {
            series: {
                animation: false
            }
        },        
        title: {
            text: '',
            x: -20 //center
        },
        subtitle: {
            text: '',
            x: -20
        },
        xAxis: {
            categories: ".$wirus_dates."
        },
        yAxis: {
            title: {
                text: 'Stk.'
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }],
            min: 0
        },
        tooltip: {
            valueSuffix: ''
        },
        legend: {
            layout: 'horizontal',
            align: 'center',
            verticalAlign: 'top',
            borderWidth: 0
        },
        series: [{
            name: 'Wirus Bestelleingang',
            data: ".$wirus_orders_in."
        }, {
            name: 'Wirus Bestelleingang Türe',
            data: ".$wirus_qtys_in."
        },{
            name: 'Wirus Erfasste Aufträge',
            data: ".$wirus_orders_processed."
        }, {
            name: 'Wirus Erfasste Türe',
            data: ".$wirus_qtys_processed."
        },]
    });
});
 	");
   
?>



</div>
