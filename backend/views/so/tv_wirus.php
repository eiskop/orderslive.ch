<?php
namespace backend\models;

use Yii;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use backend\models\Customer;
use backend\models\So;
use backend\models\CustomerPriority;
use yii\data\ArrayDataProvider;

//use kartik\datecontrol\DateControl;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\SoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */







date_default_timezone_set('Europe/Zurich');
$this->title = 'WIRUS - Offene Aufträge';

//$this->params['breadcrumbs'][] = $this->title;
    $orders = So::find()->where('status_id != 3 AND status_id != 4 and so.product_group_id = 2');
    $orders2 = So::find()->where('status_id = 3 AND product_group_id = 2 AND DATE(updated) = DATE(NOW())');
    $orders3 = So::find()->where('status_id = 3 AND product_group_id = 2 AND WEEK(updated, 3) = WEEK(NOW(), 3) AND YEAR(updated) = YEAR(NOW())');
    echo '<table class="table" style="font-size:1.7em; width: 100%;">
    		<tr>
    			<td style="white-space: nowrap;">Offen: </td>
    			<td style="text-align: right; white-space: nowrap;">'.$orders->count().' ('.$orders->sum('qty').')</td>
    			<td rowspan="2" style="padding-left: 5%; text-align: right; font-size:0.7em;">
    				<img src="../pic/jw_logo_600w.png" >
    			</td>
    			<td rowspan="2" style="width: 60%; padding-left: 5%; text-align: right; font-size:0.8em;">'.date('d.m.Y').'<br>KW'.date('W').'<br>'.date('H:i').'</td>
    		</tr>
    		<tr>
    			<td>Erfasst:</td><td style="text-align: right; white-space: nowrap;">H '.$orders2->count().' ('.$orders2->sum('qty').'), W '.$orders3->count().' ('.$orders3->sum('qty').')</td>
    		</tr>
    		<tr>
    			<td></td>
    			<td></td>
    			<td></td>
    			<td></td>
    		</tr>
    	</table>';
    

?>
<style>
::-webkit-scrollbar {
    -webkit-appearance: none;
    width: 7px;
}
::-webkit-scrollbar-thumb {
    border-radius: 4px;
    background-color: rgba(0,0,0,.5);
    -webkit-box-shadow: 0 0 1px rgba(255,255,255,.5);
}
</style>
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

$posts = $db->createCommand('SELECT so.id, product_group.name as product_group_name, order_received, customer.name as customer_name, customer_order_no, confirmation_no, surface, qty, comments, so.customer_priority_id, so_status.name as status_name, status_id, user.username as assigned_to, deadline, prio1, CONCAT(requested_delivery_year, "-", requested_delivery_week) as requested_delivery, 1 as ordering FROM so 
								LEFT JOIN customer ON
								customer_id = customer.id
								LEFT JOIN product_group ON
								product_group_id = product_group.id
								LEFT JOIN user ON 
								so.assigned_to = user.id
								LEFT JOIN so_status ON 
								so.status_id = so_status.id
							WHERE prio1 = 1 AND status_id != 3 AND status_id != 4 and so.product_group_id = 2
								UNION 
							SELECT so.id, product_group.name as product_group_name, order_received, customer.name as customer_name, customer_order_no, confirmation_no, surface, qty, comments, so.customer_priority_id, so_status.name as status_name, status_id, user.username as assigned_to, deadline, prio1, CONCAT(requested_delivery_year, "-", requested_delivery_week) as requested_delivery, 2 as ordering FROM so 
								LEFT JOIN customer ON
								customer_id = customer.id
								LEFT JOIN product_group ON
								product_group_id = product_group.id
								LEFT JOIN user ON 
								so.assigned_to = user.id
								LEFT JOIN so_status ON 
								so.status_id = so_status.id
							WHERE prio1 = 0 AND deadline <= UNIX_TIMESTAMP(NOW()) AND qty >= 30 AND status_id != 3 AND status_id != 4 and so.product_group_id = 2
								UNION 
							SELECT so.id, product_group.name as product_group_name, order_received, customer.name as customer_name, customer_order_no, confirmation_no, surface, qty, comments, so.customer_priority_id, so_status.name as status_name, status_id, user.username as assigned_to, deadline, prio1, CONCAT(requested_delivery_year, "-", requested_delivery_week) as requested_delivery, 3 as ordering FROM so 
								LEFT JOIN customer ON
								customer_id = customer.id
								LEFT JOIN product_group ON
								product_group_id = product_group.id
								LEFT JOIN user ON 
								so.assigned_to = user.id
								LEFT JOIN so_status ON 
								so.status_id = so_status.id
							WHERE prio1 = 0 AND deadline <= UNIX_TIMESTAMP(NOW()) AND qty < 30 AND status_id != 3 AND status_id != 4 and so.product_group_id = 2
								UNION
							SELECT so.id, product_group.name as product_group_name, order_received, customer.name as customer_name, customer_order_no, confirmation_no, surface, qty, comments, so.customer_priority_id, so_status.name as status_name, status_id, user.username as assigned_to, deadline, prio1, CONCAT(requested_delivery_year, "-", requested_delivery_week) as requested_delivery, 4 as ordering FROM so 
								LEFT JOIN customer ON
								customer_id = customer.id
								LEFT JOIN product_group ON
								product_group_id = product_group.id
								LEFT JOIN user ON 
								so.assigned_to = user.id
								LEFT JOIN so_status ON 
								so.status_id = so_status.id
							WHERE prio1 = 0 AND deadline <= (UNIX_TIMESTAMP(NOW())+60*60*24) AND deadline > UNIX_TIMESTAMP(NOW()) AND qty >= 30 AND status_id != 3 AND status_id != 4 and so.product_group_id = 2
								UNION
							SELECT so.id, product_group.name as product_group_name, order_received, customer.name as customer_name, customer_order_no, confirmation_no, surface, qty, comments, so.customer_priority_id, so_status.name as status_name, status_id, user.username as assigned_to, deadline, prio1, CONCAT(requested_delivery_year, "-", requested_delivery_week) as requested_delivery, 5 as ordering FROM so 
								LEFT JOIN customer ON
								customer_id = customer.id
								LEFT JOIN product_group ON
								product_group_id = product_group.id
								LEFT JOIN user ON 
								so.assigned_to = user.id
								LEFT JOIN so_status ON 
								so.status_id = so_status.id
							WHERE prio1 = 0 AND deadline <= (UNIX_TIMESTAMP(NOW())+60*60*24) AND deadline > UNIX_TIMESTAMP(NOW()) AND qty < 30 AND status_id != 3 AND status_id != 4 and so.product_group_id = 2					
								UNION
							SELECT so.id, product_group.name as product_group_name, order_received, customer.name as customer_name, customer_order_no, confirmation_no, surface, qty, comments, so.customer_priority_id, so_status.name as status_name, status_id, user.username as assigned_to, deadline, prio1, CONCAT(requested_delivery_year, "-", requested_delivery_week) as requested_delivery, 6 as ordering FROM so 
								LEFT JOIN customer ON
								customer_id = customer.id
								LEFT JOIN product_group ON
								product_group_id = product_group.id
								LEFT JOIN user ON 
								so.assigned_to = user.id
								LEFT JOIN so_status ON 
								so.status_id = so_status.id
							WHERE prio1 = 0 AND deadline > (UNIX_TIMESTAMP(NOW())+60*60*24) AND qty >= 30 AND status_id != 3 AND status_id != 4 and so.product_group_id = 2					
								UNION
							SELECT so.id, product_group.name as product_group_name, order_received, customer.name as customer_name, customer_order_no, confirmation_no, surface, qty, comments, so.customer_priority_id, so_status.name as status_name, status_id, user.username as assigned_to, deadline, prio1, CONCAT(requested_delivery_year, "-", requested_delivery_week) as requested_delivery, 7 as ordering FROM so 
								LEFT JOIN customer ON
								customer_id = customer.id
								LEFT JOIN product_group ON
								product_group_id = product_group.id
								LEFT JOIN user ON 
								so.assigned_to = user.id
								LEFT JOIN so_status ON 
								so.status_id = so_status.id
							WHERE prio1 = 0 AND deadline > (UNIX_TIMESTAMP(NOW())+60*60*24) AND qty <= 30 AND status_id != 3 AND status_id != 4 and so.product_group_id = 2					
															
							ORDER BY ordering ASC, deadline ASC, qty DESC' )
            ->queryAll();

          // echo var_dump($posts);
          // exit;

//echo var_dump($dataProvider->getData());
//$data = array_merge($orders, $orders2, $orders3);
$provider = new ArrayDataProvider([
    'allModels' => $posts,
//    'pagination' => [
//        'pageSize' => 30,
//    ],
    'sort' => [
        'attributes' => ['id', 'deadline'],
    ],

]);
// get the rows in the currently requested page
$rows = $provider->getModels();
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


            

			//	$prio = CustomerPriority::findOne(['id'=>$model->customer_priority_id]);

				//echo var_dump($prio->days_to_process);
            	//$deadline = strtotime($model->order_received)+$prio->days_to_process*60*60*24;
            	//$warning = strtotime($model->order_received)+$prio->days_to_process*60*60*24-60*60*24;
               	if ($model['prio1'] == '1') {
               		return ['class' => 'info'];
               	} 

            	$deadline = $model['deadline'];
            	$warning = $deadline-60*60*24;
                if($model['status_id'] == 1) { // status being processed
                	return ['class'=>'success'];
                }
                elseif ($model['status_id'] == 2) { // status stand by
					return ['class'=>'warning'];
                }
                elseif ($warning < time() and $deadline < time()) {
                	return ['class'=>'danger'];	
                }
            }
        ,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

		    [
		    	'header' => 'ID',
	            'attribute' => 'id',
	            'contentOptions' => ['style' => 'width:50px'],
			],
            //'product_group_id',
           // 'ordering',
		//	'deadline',        
		    [
		    	'header' => 'Eingang',
	            'attribute' => 'order_received',
	            'contentOptions' => ['style' => 'width:220px'],
			],
		    [
		    	'header' => 'Kunde',
	            'attribute' => 'customer_name',
	            'contentOptions' => ['style' => 'width:%'],
			],
		    [
		    	'header' => 'Komission',
	            'attribute' => 'customer_order_no',
	            'contentOptions' => ['style' => 'width:50px; text-align: right;'],
			],
//        	[
//		    	'header' => 'AB',
//	            'attribute' => 'confirmation_no',
//	            'contentOptions' => ['style' => 'width:50px'],
//			],            
// quantity from so_items table 
            
        	[
		    	'header' => 'Oberfläche',
	            'attribute' => 'surface',
	            'contentOptions' => ['style' => 'width:50px;'],
			], 
        	[
		    	'header' => 'Stk',
	            'attribute' => 'qty',
	            'contentOptions' => ['style' => 'width:50px; text-align: right;'],
			],  
            
            // 'value',
           	[
		    	'header' => 'Zug.',
	            'attribute' => 'assigned_to',
	            'contentOptions' => ['style' => 'width:50px'],
			],   
           	[
		    	'header' => 'W.termin',
	            'attribute' => function ($data) {
	            	if ($data['requested_delivery'][0] != 0) {
	            		return $data['requested_delivery'];
	            	}
	            },
	            'contentOptions' => ['style' => 'width:50px; text-align: right;'],
			],			
            [
                'header'=>'Prio',
                'attribute'=>'customer_priority_id',
                'value'=>CustomerPriority::findOne('customer.customer_priority_id'),
                'contentOptions' => ['style' => 'width:50px'],
            ],
            [
		    	'header' => 'Status',
	            'attribute' => 'status_name',
	            'value' => 'status_name',
	            'contentOptions' => ['style' => 'width:70px;white-space: nowrap;'],
			],   
//        	[
//		    	'header' => 'Kommentar',
//	            'attribute' => 'comments',
//			],               //$plays = Play::find()->joinWith(['userPlays'])->where(['user_play.user_id' => Yii::$app->user->id])->all();
            // 'created',
            // 'changed_by',
            // 'changed',

        //    ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
