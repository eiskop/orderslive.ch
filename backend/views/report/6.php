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
$this->title = 'Unbestätigte Aufträge';

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
]);
*/
$db = new yii\db\Connection([
    'dsn' => 'mysql:host=localhost;dbname=orderslive_dev',
    'username' => 'root',
    'password' => 'tuhatjatuline',
    'charset' => 'utf8',
]);
date_default_timezone_set('Europe/Zurich');
echo '<h2>Unbestätigte Aufträge</h2>';
//
// aufträge, stk pro tag
//SELECT date(updated) as datum, product_group.name, Sum(qty) as qty, count(*) as aufträge FROM `so` left join product_group on so.product_group_id = product_group.id WHERE status_id=3 Group by product_group_id, Date(updated) ORDER BY datum, name
// Weekly per product group
//SELECT week(updated) as datum, product_group.name, Sum(qty) as qty, count(*) as aufträge FROM `so` left join product_group on so.product_group_id = product_group.id WHERE status_id=3 and product_group_id = 2 Group by product_group_id, WEEK(updated) ORDER BY datum, name
$res = $db->createCommand('SELECT so.id, date(so.created) as created, UNIX_TIMESTAMP(order_received) as datum_uts, order_received, product_group.name as product_group_name, qty, concat(order_received, product_group.name ) as link, customer_id, customer_order_no, customer.name as customer_name, so_status.name, confirmation_no, surface, value, prio1, customer.customer_priority_id, user.username as assigned_to, so_status.name as status_name FROM `so` 
    left join product_group on so.product_group_id = product_group.id 
    left join customer on so.customer_id = customer.id 
    left join so_status on so.status_id = so_status.id 
    left join user on so.assigned_to = user.id 
WHERE status_id != 0 and status_id != 3 and status_id != 4 ORDER BY order_received ASC')->queryAll();

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
		//	echo var_dump($prio);

          		//if ($model['prio1'] == '1') {
               	//	return ['class' => 'info'];
               	//} 


            }
        ,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'header' => 'ID',
                'attribute' => 'id',
                'value' => 'id',
                'contentOptions' => ['style' => 'width:10px'],
            ],
            [
                'header' => 'Produkt',
                'attribute' => 'product_group_name',
            ],            
		    [
		    	'header' => 'Eingang',
	            'attribute' => 'order_received',
                'format' => ['date', 'php:d.m.Y'],
	            'contentOptions' => ['style' => 'width:100px'],
			],
            [
                'header' => 'Prio',
                'attribute' => 'customer_priority_id',
            ],
            [
                'header' => 'Kunde',
                'attribute' => 'customer_name',
            ],
            [
                'header' => 'Komission',
                'attribute' => 'customer_order_no',
            ],
            [
                'header' => 'AB',
                'attribute' => 'confirmation_no',   
            ],   
            [
                'header' => 'Oberfläche',
                'attribute' => 'surface',
   
            ],              
            [
                'header' => 'Offertnr.',
                'attribute' => 'offer_no',
   
            ],   
            [
                'header'=>'Stk.',
                'attribute'=>'qty',
                'value'=>'qty',
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
                'header' => 'Erstellt',
                'attribute' => 'created',
                'format' => ['date', 'php:d.m.Y'],
                'contentOptions' => ['style' => 'width:100px'],
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
