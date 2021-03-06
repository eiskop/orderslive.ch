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
use backend\models\Offer;
use backend\models\CustomerPriority;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;

//use kartik\datecontrol\DateControl;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\SoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */





date_default_timezone_set('Europe/Zurich');
$this->title = 'Dauer vom Erhalten bis Offerte';

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
echo '<h2>Dauer vom Erhalten bis Offerte</h2>';
//
// aufträge, stk pro tag
//SELECT date(updated) as datum, product_group.name, Sum(qty) as qty, count(*) as aufträge FROM `so` left join product_group on offer.product_group_id = product_group.id WHERE status_id=3 Group by product_group_id, Date(updated) ORDER BY datum, name
// Weekly per product group
//SELECT week(updated) as datum, product_group.name, Sum(qty) as qty, count(*) as aufträge FROM `so` left join product_group on offer.product_group_id = product_group.id WHERE status_id=3 and product_group_id = 2 Group by product_group_id, WEEK(updated) ORDER BY datum, name
$res = $db->createCommand('SELECT offer.id, offer_no, processed_by_id, customer.name as customer_name, customer_contact, carpenter, customer_order_no, confirmation_no, qty, value, format(value_net, 2) as value_net, offer.customer_priority_id, comments, user_assigned_to.last_name as assigned_to, offer.created, user_created_by.last_name as created_by, user_updated_by.last_name as updated_by, offer_received, offer.updated, offer_status.name as status_name, CAST(((UNIX_TIMESTAMP(offer.updated)-UNIX_TIMESTAMP(offer.offer_received))/60/60/24) as Decimal(5, 1)) as duration  FROM `offer` 
    left join product_group on offer.product_group_id = product_group.id 
    left join customer on offer.customer_id = customer.id 
    left join offer_status on offer.status_id = offer_status.id 
    left join user as user_assigned_to on offer.assigned_to = user_assigned_to.id 
    left join user as user_created_by on offer.created_by = user_created_by.id 
    left join user as user_updated_by on offer.updated_by = user_updated_by.id 

WHERE status_id = 7
ORDER BY duration DESC')->queryAll();

          // echo var_dump($res);
          // exit;



$dataProvider = new ArrayDataProvider([
    'allModels' => $res,
    'pagination' => [
        'pageSize' => 1000000,
    ],
    'sort' => [
        'attributes' => ['datum', 'product_group_name'],
    ],

]);



// get the rows in the currently requested page
$rows = $dataProvider->getModels();
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
?>

    <?= GridView::widget([
    	'summary'=>'', 
    	'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'header' => 'Offer No',
                'attribute' => 'offer_no',
                'value' => 'offer_no',
                'value'=> function ($data) {
                    return Html::a($data['offer_no'], ['offer/view', 'id' => $data['id']], ['data-pjax' => '0']);
                },
                'format' => 'raw', 
                'format' => 'raw',                 
                'contentOptions' => ['style' => 'width:10px'],
            ],
            [
                'header' => 'Prio',
                'attribute' => 'customer_priority_id',
            ],
            [
                'header' => 'Kunde',
                'attribute' => 'customer_name',
                'value' => 'customer_name',                
            ],
            [
                'header' => 'Komission',
                'attribute' => 'customer_order_no',
            ],
            [
                'header'=>'Stk.',
                'attribute'=>'qty',
                'value'=>'qty',
                'contentOptions' => ['style' => 'text-align: right;'],
            ],
            [
                'header' => 'Wert Neto',
                'attribute' => 'value_net',
                'contentOptions' => ['style' => 'text-align: right;'],                
            ],            
            [
                'header'=>'Zugestellt an',
                'attribute'=>'assigned_to',
                'value'=>'assigned_to'
            ],               
            [
                'header'=>'Status.',
                'attribute'=>'status_name',
                'value'=>'status_name'
            ],
			[
		    	'header' => 'Eingang',
	            'attribute' => 'offer_received',
                'format' => ['date', 'php:d.m.Y H:i:s'],
	            'contentOptions' => ['style' => 'width:100px'],
			],
            [
                'header'=>'Ersteller',
                'attribute'=>'created_by',
                'value'=>'created_by'
            ],
            [
                'header' => 'Geändert',
                'attribute' => 'updated',
                'format' => ['date', 'php:d.m.Y H:i:s'],
                'contentOptions' => ['style' => 'width:100px'],
            ],			
            [
                'header'=>'Geändert von',
                'attribute'=>'updated_by',
            ],             
			[
                'header' => 'Dauer (Tage)',
                'attribute' => 'duration',
                'contentOptions' => ['style' => 'width:100px; text-align: right;'],
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

?>



</div>
