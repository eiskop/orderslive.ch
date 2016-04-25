<?php

namespace backend\controllers;

use Yii;
use backend\models\Offer;
use backend\models\OfferSearch;
use backend\models\OfferItem;
use backend\models\OfferItemSearch;
use backend\models\Customer;
use backend\models\CustomerPriority;
use backend\models\Model;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OfferController implements the CRUD actions for Offer model.
 */
class OfferController extends Controller
{
	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'delete' => ['POST'],
				],
			],
		];
	}

	/**
	 * Lists all Offer models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new OfferSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->sort = ['defaultOrder' => ['id' => 'DESC']];
		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

	/**
	 * Displays a single Offer model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{

		$searchModel = new OfferItemSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->query->where('offer_id = '.$id);
		return $this->render('view', [
			'model' => $this->findModel($id),
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

	/**
	 * Creates a new Offer model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		
		if (Yii::$app->user->can('create-offer')) 
		{
			$model = new Offer();
			$modelsOfferItem = [new OfferItem];

			if ($model->load(Yii::$app->request->post())) {




				$modelsOfferItem = Model::createMultiple(OfferItem::classname());
				Model::loadMultiple($modelsOfferItem, Yii::$app->request->post());

				// ajax validation
	 //           if (Yii::$app->request->isAjax) {
	   //             Yii::$app->response->format = Response::FORMAT_JSON;
		 //           return ArrayHelper::merge(
		   //             ActiveForm::validateMultiple($modelsOfferItem),
			 //           ActiveForm::validate($model)
			   //     );
				//}

				// validate all models
				$valid = $model->validate();
				$valid = Model::validateMultiple($modelsOfferItem) && $valid;

				if ($valid) {
					$model->created = date('Y-m-d H:i:s');
					$model->created_by = Yii::$app->user->id;

					if ($model->status_id == 3) {
						$model->updated = $model->created;
						$model->updated_by = $model->created_by;
					}

					$customer = Customer::findOne(['id'=>$model->customer_id]);
					//echo var_dump($customer);
					$prio = CustomerPriority::findOne(['id'=>$customer->customer_priority_id]);
					//echo var_dump($prio);


					$model->customer_priority_id = $customer->customer_priority_id;
					$model->days_to_process = $prio->days_to_process;            

					$model->deadline = strtotime($model->offer_received)+$prio->days_to_process*60*60*24;  

					$uts_received = strtotime($model->offer_received);
					$model->offer_received = date('Y-m-d', $uts_received);
					$uts_deadline = $model->deadline;
					// add weekend as additional time to process
					$count_weekend = 0;
				
					for ($i = 0; $i <= $model->days_to_process; $i++) {
						$date = $uts_received+$i*60*60*24; 
						if (date('D', $date) == 'Sat' OR date('D', $date) == 'Sun') {
							$count_weekend++;
						}
					}
					$model->deadline += $count_weekend*60*60*24;
	//process offer lines
					$transaction = \Yii::$app->db->beginTransaction();
					try {
						if ($flag = $model->save(false)) {
							foreach ($modelsOfferItem as $modelOfferItem) {
								$modelOfferItem->offer_id = $model->id;
								$modelOfferItem->offer_id = $model->id;
								$modelOfferItem->value_net = (100-$modelOfferItem->project_discount_perc)*$modelOfferItem->value/100;
								$modelOfferItem->value_total = $modelOfferItem->value*$modelOfferItem->qty;
								$modelOfferItem->value_total_net = (100-$modelOfferItem->project_discount_perc)*$modelOfferItem->value_total/100;
								$model->qty += $modelOfferItem->qty;         
								$model->value += $modelOfferItem->value_total;
								$model->value_net += $modelOfferItem->value_total_net;                       
								
								if (! ($flag = $modelOfferItem->save(false))) {
									$transaction->rollBack();
									break;
								}
							}
						}
						if ($flag) {
							$model->save(false);
							$transaction->commit();
							return $this->redirect(['view', 'id' => $model->id]);
						}
					} catch (Exception $e) {
						$transaction->rollBack();
					}
	//END:process offer lines                
				}


				return $this->redirect(['create', 'id' => $model->id]);
			} else {
				return $this->render('create', [
					'model' => $model,
					'modelsOfferItem' => (empty($modelsOfferItem)) ? [new OfferItem] : $modelsOfferItem                    
				]);
			}
		}
		else 
		{
			throw new ForbiddenHttpException;
		}
	}

	/**
	 * Updates an existing Offer model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		if (Yii::$app->user->can('change-offer') OR Yii::$app->user->can('update-offer')) 
		{
			$model = $this->findModel($id);
			$modelsOfferItem = $model->offerItems;

			if ($model->load(Yii::$app->request->post())) {

				$oldIDs = ArrayHelper::map($modelsOfferItem, 'id', 'id');
				$modelsOfferItem = Model::createMultiple(OfferItem::classname(), $modelsOfferItem);
				Model::loadMultiple($modelsOfferItem, Yii::$app->request->post());
				$deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelsOfferItem, 'id', 'id')));

				$model->updated = date('Y-m-d H:i:s');
				$model->updated_by = Yii::$app->user->id;

				$uts_received = strtotime($model->offer_received);
				$model->offer_received = date('Y-m-d', $uts_received);
				$uts_deadline = $model->deadline;
				// add weekend as additional time to process
				$count_weekend = 0;
			
				for ($i = 0; $i <= $model->days_to_process; $i++) {
					$date = $uts_received+$i*60*60*24; 
					if (date('D', $date) == 'Sat' OR date('D', $date) == 'Sun') {
						$count_weekend++;
					}
				}
				$model->deadline += $count_weekend*60*60*24;

				// validate all models
				$valid = $model->validate();
				$valid = Model::validateMultiple($modelsOfferItem) && $valid;

				if ($valid) {
					$transaction = \Yii::$app->db->beginTransaction();
					try {
						if ($flag = $model->save(false)) {
							if (! empty($deletedIDs)) {
								OfferItem::deleteAll(['id' => $deletedIDs]);
							}
							$model->qty = 0;
							$model->value = 0;
							$model->value_net = 0;
							foreach ($modelsOfferItem as $modelOfferItem) {
								$modelOfferItem->offer_id = $model->id;
								$modelOfferItem->value_net = (100-$modelOfferItem->project_discount_perc)*$modelOfferItem->value/100;
								$modelOfferItem->value_total = $modelOfferItem->value*$modelOfferItem->qty;
								$modelOfferItem->value_total_net = (100-$modelOfferItem->project_discount_perc)*$modelOfferItem->value_total/100;
								$model->qty += $modelOfferItem->qty;
								$model->value += $modelOfferItem->value_total;
								$model->value_net += $modelOfferItem->value_total_net;

								if (! ($flag = $modelOfferItem->save(false))) {
									$transaction->rollBack();
									break;
								}
							}
						
						}
						if ($flag) {
							$model->save(false);
							$transaction->commit();
							return $this->redirect(['view', 'id' => $model->id]);
						}
					} catch (Exception $e) {
						$transaction->rollBack();
					}
				}
				return $this->redirect(['index']);
			} else {
				return $this->render('update', [
					'model' => $model,
					'modelsOfferItem' => (empty($modelsOfferItem)) ? [new OfferItem] : $modelsOfferItem    
				]);
			}
		}
		else {
			throw new ForbiddenHttpException;
		}
	}

	/**
	 * Deletes an existing Offer model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$this->findModel($id)->delete();

		return $this->redirect(['index']);
	}

	/**
	 * Finds the Offer model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return Offer the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = Offer::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
}
