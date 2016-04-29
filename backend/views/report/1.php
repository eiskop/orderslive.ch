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
$this->title = 'Durchlaufzeit (Tage)';

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
echo '<h2>Durchlaufzeit von '.date('d.m.Y', strtotime('11. April 2016')).' bis '.date('d.m.Y', time()).'</h2>';

$res = $db->createCommand('SELECT customer_id, customer.name as customer_name, customer.city as city, customer.street as street, customer.zip_code as zip_code, product_group.name as product_group_name, so.customer_priority_id, customer_priority.days_to_process, avg(UNIX_TIMESTAMP(so.updated)-UNIX_TIMESTAMP(order_received))/(60*60*24) as durchlaufzeit_tage, count(*) as aufträge FROM `so` 
        LEFT JOIN customer on so.customer_id = customer.id  
    	LEFT JOIN product_group on so.product_group_id = product_group.id  
    	LEFT JOIN customer_priority on so.customer_priority_id = customer_priority.id  
  	WHere status_id = 3 AND UNIX_TIMESTAMP(so.updated) != 0 AND UNIX_TIMESTAMP(so.updated) > '.strtotime('11. April 2016').' group by customer_id, product_group_id ORDER BY customer.name ASC, product_group.name asc' ) //    WHere status_id = 3 AND UNIX_TIMESTAMP(so.updated) != 0 AND UNIX_TIMESTAMP(so.updated) > '.strtotime('-1 Year').' group by customer_id, product_group_id ORDER BY customer.name ASC, product_group.name asc' )
            ->queryAll();

          // echo var_dump($res);
          // exit;

//echo var_dump($dataProvider->getData());
//$data = array_merge($orders, $orders2, $orders3);
$provider = new ArrayDataProvider([
    'allModels' => $res,
    'pagination' => [
        'pageSize' => 10000000,
    ],
    'sort' => [
        'attributes' => ['customer.name', 'product_group.name', 'durchlaufzeit_tage'],
    ],

]);
// get the rows in the currently requested page
$rows = $provider->getModels();

$sum_dlz = 0;    
$sum_dlz_kpx = 0;  
$sum_dlz_wir = 0;  
$count_rows = 0;
$count_rows_kpx = 0;
$count_rows_wir = 0;
foreach ($rows as $k=>$v) {
    $count_rows++;
    $sum_dlz += $v['durchlaufzeit_tage'];
    if ($v['product_group_name'] == "Kellpax") {
        $sum_dlz_kpx += $v['durchlaufzeit_tage'];
        $count_rows_kpx++;
    }
    if ($v['product_group_name'] == "Wirus") {
        $sum_dlz_wir += $v['durchlaufzeit_tage'];
        $count_rows_wir++;
    }
    
}
?>
<h3>
<?= "Durchschnittliche Durchlaufzeit: ".number_format(($sum_dlz/$count_rows), 2, '.', ' ').' ('."Kellpax: ".number_format(($sum_dlz_kpx/$count_rows_kpx), 2, '.', ' ').", Wirus: ".number_format(($sum_dlz_wir/$count_rows_wir), 2, '.', ' ').')' ?>
</h3>
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
		    	'header' => 'Kunde',
	            'attribute' => 'customer_name',
	            'contentOptions' => ['style' => 'width:250px'],
			],
            [
                'header' => 'PLZ',
                'attribute' => 'zip_code',
            ],
            [
                'header' => 'Stadt',
                'attribute' => 'city',
            ],
            [
                'header' => 'Strasse',
                'attribute' => 'street',
            ],            
 		    [
		    	'header' => 'Produkt',
	            'attribute' => 'product_group_name',
			],
            [
                'header'=>'DLZ (Tage)',
                'attribute'=>'durchlaufzeit_tage',
                'value'=>'durchlaufzeit_tage',
                'contentOptions' => ['style' => 'width:50px; text-align: right;'],
                'format'=>['decimal', 1],
            ],
            [
                'header'=>'Prio',
                'attribute'=>'customer_priority_id',
                'value'=>CustomerPriority::findOne('customer.customer_priority_id'),
                'contentOptions' => ['style' => 'width:50px'],
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
    ]); ?>


</div>
