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
$this->title = 'Offene Offerten';

//$this->params['breadcrumbs'][] = $this->title;


    $offers = Offer::find()->where('status_id != 3 AND status_id != 4 AND status_id != 5');
    $offers2 = Offer::find()->where('status_id = 7 AND DATE(updated) = DATE(NOW())');
    $offers3 = Offer::find()->where('status_id = 7 AND WEEK(updated, 3) = WEEK(NOW(), 3) AND YEAR(updated) = YEAR(NOW())');
	$offers4 = Offer::find()->where('status_id = 3 AND DATE(updated) = DATE(NOW())');
    $offers5 = Offer::find()->where('status_id = 3 AND WEEK(updated, 3) = WEEK(NOW(), 3) AND YEAR(updated) = YEAR(NOW())');    
    echo '<table class="table" style="font-size:1.3em; width: 100%;">
    		<tr>
    			<td style="white-space: nowrap;">Offen: </td>
    			<td style="text-align: right; white-space: nowrap;">'.$offers->count().' ('.number_format($offers->sum('value_net'), 0, ', ', ' ').' CHF)</td>
    			<td rowspan="3" style="padding-left: 5%; text-align: right; font-size:0.7em;">
    				<img src="../pic/jw_logo_600w.png" >
    			</td>
    			<td rowspan="3" style="width: 60%; padding-left: 5%; text-align: right; font-size:0.8em;">'.date('d.m.Y').'<br>KW'.date('W').'<br>'.date('H:i').'</td>
    		</tr>
    		<tr>
    			<td>Offeriert:</td><td style="text-align: right; white-space: nowrap;">H '.$offers4->count().' ('.number_format($offers4->sum('value_net'), 0, ', ', ' ').' CHF), W '.$offers5->count().' ('.number_format($offers5->sum('value_net'), 0, ', ', ' ').' CHF)</td>
    		</tr>
    		<tr>
    			<td>Gewonnen:</td><td style="text-align: right; white-space: nowrap;">H '.$offers2->count().' ('.number_format($offers2->sum('value_net'), 0, ', ', ' ').' CHF), W '.$offers3->count().' ('.number_format($offers3->sum('value_net'), 0, ', ', ' ').' CHF)</td>
    		</tr>    		
    		<tr>
    			<td></td>
    			<td></td>
    			<td></td>
    			<td></td>
    		</tr>
    	</table>';
//    echo '<div class="title" style="font-size: 3em;">Offene Aufträge: '.$offers->count().'</div>';
  //  echo '<div class="title" style="font-size: 3em;">Anzahl Türen: '.$offers->sum('qty').'</div>';
    

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
    $offers = Offer::find()	
    ->where([
		'and', 
		['<=', 'deadline', time()+60*60*24],
		['>=', 'qty', 30],
	])
    ->orderBy('deadline', 'asc')->orderBy('qty', 'desc')
    ->all();
   // echo 'deadline';
foreach ($offers as $order) {
	//echo var_dump($order->id.' / deadline '.date('d.m.Y H:i:s', $order->deadline).' / qty '.$order->qty );
}

$offers2 = Offer::find()
	->where([
		'and', 
		['<=', 'deadline', time()+60*60*24],
		['<=', 'qty', 30],
	])
    ->orderBy('deadline')->orderBy('qty')
    ->all();
//    echo 'qty';
foreach ($offers2 as $order) {
//	echo var_dump($order->id.' / qty '.$order->qty);
}

$offers3 = Offer::find()
	->where([
		'and', 
		['>=', 'deadline', time()+60*60*24],
		['<=', 'qty', 30],
		])
   ->orderBy('deadline')
   ->all();
  // echo 'rest';
foreach ($offers3 as $order) {
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

$posts = $db->createCommand('SELECT offer.id, offer_received, customer.name as customer_name, customer_order_no, confirmation_no, qty, comments, value_net, offer.customer_priority_id, so_status.name as status_name, user.username as assigned_to, deadline, prio1, 1 as ordering FROM offer 
								LEFT JOIN customer ON
								customer_id = customer.id
								LEFT JOIN user ON 
								offer.assigned_to = user.id
								LEFT JOIN so_status ON 
								offer.status_id = so_status.id
							WHERE prio1 = 1 AND status_id != 3 AND status_id != 4 and offer.product_group_id = 1
								UNION 
							SELECT offer.id, offer_received, customer.name as customer_name, customer_order_no, confirmation_no, qty, comments, value_net, offer.customer_priority_id, so_status.name as status_name, user.username as assigned_to, deadline, prio1, 2 as ordering FROM offer 
								LEFT JOIN customer ON
								customer_id = customer.id
								LEFT JOIN user ON 
								offer.assigned_to = user.id
								LEFT JOIN so_status ON 
								offer.status_id = so_status.id
							WHERE prio1 = 0 AND deadline <= UNIX_TIMESTAMP(NOW()) AND qty >= 30 AND status_id != 3 AND status_id != 4 and offer.product_group_id = 1
								UNION 
							SELECT offer.id, offer_received, customer.name as customer_name, customer_order_no, confirmation_no, qty, comments, value_net, offer.customer_priority_id, so_status.name as status_name, user.username as assigned_to, deadline, prio1, 3 as ordering FROM offer 
								LEFT JOIN customer ON
								customer_id = customer.id
								LEFT JOIN user ON 
								offer.assigned_to = user.id
								LEFT JOIN so_status ON 
								offer.status_id = so_status.id
							WHERE prio1 = 0 AND deadline <= UNIX_TIMESTAMP(NOW()) AND qty < 30 AND status_id != 3 AND status_id != 4 and offer.product_group_id = 1
								UNION
							SELECT offer.id, offer_received, customer.name as customer_name, customer_order_no, confirmation_no, qty, comments, value_net, offer.customer_priority_id, so_status.name as status_name, user.username as assigned_to, deadline, prio1, 4 as ordering FROM offer 
								LEFT JOIN customer ON
								customer_id = customer.id
								LEFT JOIN user ON 
								offer.assigned_to = user.id
								LEFT JOIN so_status ON 
								offer.status_id = so_status.id
							WHERE prio1 = 0 AND deadline <= (UNIX_TIMESTAMP(NOW())+60*60*24) AND deadline > UNIX_TIMESTAMP(NOW()) AND qty >= 30 AND status_id != 3 AND status_id != 4 and offer.product_group_id = 1
								UNION
							SELECT offer.id, offer_received, customer.name as customer_name, customer_order_no, confirmation_no, qty, comments, value_net, offer.customer_priority_id, so_status.name as status_name, user.username as assigned_to, deadline, prio1, 5 as ordering FROM offer 
								LEFT JOIN customer ON
								customer_id = customer.id
								LEFT JOIN user ON 
								offer.assigned_to = user.id
								LEFT JOIN so_status ON 
								offer.status_id = so_status.id
							WHERE prio1 = 0 AND deadline <= (UNIX_TIMESTAMP(NOW())+60*60*24) AND deadline > UNIX_TIMESTAMP(NOW()) AND qty < 30 AND status_id != 3 AND status_id != 4 and offer.product_group_id = 1					
								UNION
							SELECT offer.id, offer_received, customer.name as customer_name, customer_order_no, confirmation_no, qty, comments, value_net, offer.customer_priority_id, so_status.name as status_name, user.username as assigned_to, deadline, prio1, 6 as ordering FROM offer 
								LEFT JOIN customer ON
								customer_id = customer.id
								LEFT JOIN user ON 
								offer.assigned_to = user.id
								LEFT JOIN so_status ON 
								offer.status_id = so_status.id
							WHERE prio1 = 0 AND deadline > (UNIX_TIMESTAMP(NOW())+60*60*24) AND qty >= 30 AND status_id != 3 AND status_id != 4 and offer.product_group_id = 1					
								UNION
							SELECT offer.id, offer_received, customer.name as customer_name, customer_order_no, confirmation_no, qty, comments, value_net, offer.customer_priority_id, so_status.name as status_name, user.username as assigned_to, deadline, prio1, 7 as ordering FROM offer 
								LEFT JOIN customer ON
								customer_id = customer.id
								LEFT JOIN user ON 
								offer.assigned_to = user.id
								LEFT JOIN so_status ON 
								offer.status_id = so_status.id
							WHERE prio1 = 0 AND deadline > (UNIX_TIMESTAMP(NOW())+60*60*24) AND qty <= 30 AND status_id != 3 AND status_id != 4 and offer.product_group_id = 1					
															
							ORDER BY ordering ASC, deadline ASC, qty DESC' )
            ->queryAll();

          // echo var_dump($posts);
          // exit;

//echo var_dump($dataProvider->getData());
//$data = array_merge($offers, $offers2, $offers3);
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
    	'formatter' => 
    		[
    			'class' => 'yii\i18n\Formatter',
    			'nullDisplay' => '',
    			'dateFormat' => 'dd.MM.yyyy',
	            'decimalSeparator' => ',',
	            'thousandSeparator' => ' ',
	            'currencyCode' => 'CHF',
	            'defaultTimeZone' => 'Europe/Zurich',
    		],

        'dataProvider' => $provider,
        'rowOptions' => 

            function ($model) {

// SELECT * FROM `order` WHERE `subtotal` > 200 ORDER BY `id`

            	//echo var_dump($offers);

            	//echo var_dump(Yii::$app->formatter->asDate($model->offer_received, 'php:Y-m-d'));
				//echo var_dump($model->offer_received);
         //   $customer = Customer::findOne(['id'=>$model->customer_id]);
          //  echo var_dump($customer);
			//$prio = CustomerPriority::findOne(['id'=>$customer->customer_priority_id]);
		//	echo var_dump($prio);

          		if ($model['prio1'] == '1') {
               		return ['class' => 'info'];
               	} 
            
            

			//	$prio = CustomerPriority::findOne(['id'=>$model->customer_priority_id]);

				//echo var_dump($prio->days_to_process);
            	//$deadline = strtotime($model->offer_received)+$prio->days_to_process*60*60*24;
            	//$warning = strtotime($model->offer_received)+$prio->days_to_process*60*60*24-60*60*24;

            	$deadline = $model['deadline'];
            	$warning = $deadline-60*60*24;
                if($deadline > time() and $warning > time()) {
                	return ['class'=>'success'];
                }
                elseif ($warning < time() and $deadline > time()) {
					return ['class'=>'warning'];
                }
                elseif ($warning < time() and $deadline < time()) {
                	return ['class'=>'danger'];	
                }



            }
        ,
        'columns' => [

		    [
		    	'header' => 'ID',
	            'attribute' => 'id',
	            'contentOptions' => ['style' => 'width:2%'],
			],
            //'product_group_id',
           // 'ordering',
		//	'deadline',        
		    [
		    	'header' => 'Eingang',
	            'attribute' => 'offer_received',
	            'format' => ['date', 'php:d.m.Y'],
	            'contentOptions' => ['style' => 'width:5%'],
			],
		    [
		    	'header' => 'Kunde',
	            'attribute' => 'customer_name',
	            'contentOptions' => ['style' => 'width:20%'],
			],
		    [
		    	'header' => 'Komission',
	            'attribute' => 'customer_order_no',
	            'contentOptions' => ['style' => 'width:30%; text-align: right;'],
			],
//        	[
//		    	'header' => 'AB',
//	            'attribute' => 'confirmation_no',
//	            'contentOptions' => ['style' => 'width:50px'],
//			],            
// quantity FROM offer_items table 
        	[
		    	'header' => 'Stk',
	            'attribute' => 'qty',
	            'contentOptions' => ['style' => 'width:50px; text-align: right;'],
			],  
        	[
		    	'header' => 'CHF',
	            'attribute' => 'value_net',
	            'format' => ['decimal', 0],
	            'contentOptions' => ['style' => 'width:50px; text-align: right;'],
			],              
            // 'value',
           	[
		    	'header' => 'Zug.',
	            'attribute' => 'assigned_to',
	            'contentOptions' => ['style' => 'width:50px'],
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
	            'contentOptions' => ['style' => 'width:70px; white-space: nowrap;'],
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
