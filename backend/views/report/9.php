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
$this->title = 'Erfasst pro Woche';

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
//  echo var_dump($order->id.' / qty '.$order->qty);
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
    'class'=>'CDbConnection',
    'dsn' => 'sqlsrv:host=JW-SRV01;dbname=KPXIfas3000',
    'username' => 'sa_teiskop',
    'password' => 'bodenSee99',
    'charset' => 'utf8',
]);
date_default_timezone_set('Europe/Zurich');
echo '<h2>Augträge erfasst pro Woche (18 Monate)</h2>';
//

$res = $db->createCommand('SELECT * FROM dbo.vTerminuebersicht2 LIMIT 10')->queryAll();
$provider = new ArrayDataProvider([
    'allModels' => $res,
    'pagination' => [
        'pageSize' => 0,
    ]    
]);




// get the rows in the currently requested page
$rows = $provider->getModels();

echo '<pre>', var_dump($rows);

exit;

?>



</div>
