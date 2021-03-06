<?php

namespace backend\controllers;

use Yii;
use backend\models\Offer;
use backend\models\OfferSearch;
use backend\models\OfferItem;
use backend\models\OfferItemSearch;
use backend\models\OfferStatusLog;
use backend\models\OfferStatusLogSearch;
use backend\models\Customer;
use backend\models\CustomerPriority;
use backend\models\CustomerDiscount;
use backend\models\Change;
use backend\models\ChangeSearch;
use backend\models\OfferUpload;
use backend\models\OfferUploadSearch;
use backend\models\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\UploadForm;
use yii\web\UploadedFile;
use yii\base\ErrorException;

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

		$searchModel2 = new OfferStatusLogSearch();
		$dataProvider2 = $searchModel2->search(Yii::$app->request->queryParams);
		$dataProvider2->query->where(['offer_id' => $id])->orderBy(['contact_date'=>SORT_DESC])->all();		

		$searchModel3 = new OfferUploadSearch();
		$dataProvider3 = $searchModel3->search(Yii::$app->request->queryParams);
		$dataProvider3->query->where(['offer_id'=>$id])->orderBy(['file_name'=>SORT_ASC])->all();		

		return $this->render('view', [
			'model' => $this->findModel($id),
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'searchModel2' => $searchModel2,
			'dataProvider2' => $dataProvider2,	
			'searchModel3' => $searchModel3,
			'dataProvider3' => $dataProvider3,			
		]);
	}

	/**
	 * Creates a new Offer model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		function round5 ($value) {
			$number = round($value*20)/20;
			return $number;
		}


		if (Yii::$app->user->can('create-offer')) 
		{


			$model = new Offer();
			$modelsOfferItem = [new OfferItem];
return $this->redirect(['view', 'id' => $model->id]);

			if ($model->load(Yii::$app->request->post())) {


				$max = Offer::find() // CREATE A OFFER NO
			    ->select('max(offer_no)')->where(['LEFT(offer_no, 4)' =>date('y').date('m')]) // we need only one column
			    ->scalar();
			    if (!is_null($max)) {
			    	$model->offer_no = $max+1;
			    }
			    else {
			    	$model->offer_no = date('y').date('m').'0001';
			    }	

			    if ($model->carpenter == NULL) {
					$model->carpenter = 0;
			    }	
			    if ($model->followup_by_id == NULL) {
					$model->followup_by_id = 0;
			    }
			    if ($model->assigned_to == NULL) {
					$model->assigned_to = 0;
			    }
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

				$model->product_group_id = 1;
				$model->qty = 0;

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
								$a = CustomerDiscount::findOne(['offer_item_type_id'=>$modelOfferItem->offer_item_type_id, 'customer_id'=>$model->customer_id]);
								if ($a != NULL) {
									$modelOfferItem->base_discount_perc = $a->base_discount_perc;
								}
								else {
									$modelOfferItem->base_discount_perc = 0;
								}

								$modelOfferItem->value_net = round5((100-$modelOfferItem->base_discount_perc)*$modelOfferItem->value/100);
								$modelOfferItem->value_total_net = round5(((100-$modelOfferItem->base_discount_perc)*$modelOfferItem->value_total)/100);
								$modelOfferItem->order_line_net_value = round5(((100-$modelOfferItem->project_discount_perc)*$modelOfferItem->value_total_net)/100);
								$modelOfferItem->value = $modelOfferItem->value_total/$modelOfferItem->qty;

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

				            // upload files

				            $model->uploadedFiles = UploadedFile::getInstances($model, 'uploadedFiles');
				            if (!is_null($model->uploadedFiles)) {
				            	
//echo var_dump($model->uploadedFiles);
//echo var_dump($model->errors);
//exit;
				            	$uploadDir = 'uploads/offer/'.$model->id;
				            	if (!file_exists($uploadDir)) {
				            		mkdir($uploadDir);
				            	}
				            	foreach ($model->uploadedFiles as $file) {
				            		$modelUpload = new OfferUpload();

					            	$pinfo = pathinfo($file);
					            
					            	$file_name = 'uploads/offer/'.$model->id.'/'.$file->name;
			//		            	echo '<pre>', var_dump($pinfo);
			//		            	exit;
					            	while(file_exists($file_name)) {
				             			$file_name = 'uploads/offer/'.$model->id.'/'.$pinfo['filename'].'_'.date('d-m-Y_H-i-s', time()).'.'.$pinfo['extension'];
				             			//$file_name.'<br>';
				             		}
				             		//echo $file_name.'<br>';	
				             		
				             		$file->saveAs($file_name);	
				             		$pinfo2 = pathinfo($file_name);
									//Save file Data to DB
									$modelUpload->offer_id = $model->id;
					            	$modelUpload->file_path = $file_name;
					            	$modelUpload->file_name = $pinfo2['basename'];
					            	if ($modelUpload->title != TRUE)  {
			                            $modelUpload->title = $pinfo['basename'];    
			                        }
					            	$modelUpload->file_extension = $pinfo['extension'];
					            	$modelUpload->file_type = $file->type;
					            	$modelUpload->file_size = $file->size;
					            	$modelUpload->created = date('Y-m-d H:i:s');
					            	$modelUpload->created_by = Yii::$app->user->id;
					            	$modelUpload->save();

					            	//END: Save file Data to DB
				            	} 
				            }
			             	
				         //   if ($model->upload($model->id)) {
				            	//upload successful
				           // }
			

		            	
							//END: upload files

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
					'modelsOfferItem' => (empty($modelsOfferItem)) ? [new OfferItem] : $modelsOfferItem,                  
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

		function round5 ($value) {
			$number = round($value*20)/20;
			return $number;
		}

		if (Yii::$app->user->can('change-offer') OR Yii::$app->user->can('update-offer')) 
		{
			

			$model = $this->findModel($id);

return $this->redirect(['view', 'id' => $model->id]);			
			$modelsOfferItem = $model->offerItems;
			$modelOfferStatusLog = new OfferStatusLog();
			$modelChange = New Change();

			$modelOfferStatusLog->load(Yii::$app->request->post());

			if (((Yii::$app->user->can('change-offer') AND $model->locked_by == 0) OR (Yii::$app->user->can('change-offer') AND $model->locked_by == Yii::$app->user->id)) OR Yii::$app->user->can('admin'))  {
				//lock Offer for editing
				Offer::updateAll(['locked_by' => Yii::$app->user->id, 'locked'=>date(time())], ['id'=>$model->id]);
			}
			else {
				return $this->redirect(['index']);
			}



			if ($model->load(Yii::$app->request->post())) {

	            // upload files
	            $model->uploadedFiles = UploadedFile::getInstances($model, 'uploadedFiles');
	            if (!is_null($model->uploadedFiles)) {
	            	//echo '<pre>', var_dump($model->uploadedFiles);
	            	$uploadDir = 'uploads/offer/'.$model->id;
	            	if (!file_exists($uploadDir)) {
	            		mkdir($uploadDir);
	            	}
	            	foreach ($model->uploadedFiles as $file) {
	            		$modelUpload = new OfferUpload();

		            	$pinfo = pathinfo($file);
		            
		            	$file_name = 'uploads/offer/'.$model->id.'/'.$file->name;
//		            	echo '<pre>', var_dump($pinfo);
//		            	exit;
		            	while(file_exists($file_name)) {
	             			$file_name = 'uploads/offer/'.$model->id.'/'.$pinfo['filename'].'_'.date('d-m-Y_H-i-s', time()).'.'.$pinfo['extension'];
	             			//$file_name.'<br>';
	             		}
	             		//echo $file_name.'<br>';	
	             		
	             		$file->saveAs($file_name);	
	             		$pinfo2 = pathinfo($file_name);
						//Save file Data to DB
						$modelUpload->offer_id = $model->id;
		            	$modelUpload->file_path = $file_name;
		            	$modelUpload->file_name = $pinfo2['basename'];
		            	if ($modelUpload->title != TRUE)  {
                            $modelUpload->title = $pinfo['basename'];    
                        }
		            	$modelUpload->file_extension = $pinfo['extension'];
		            	$modelUpload->file_type = $file->type;
		            	$modelUpload->file_size = $file->size;
		            	$modelUpload->created = date('Y-m-d H:i:s');
		            	$modelUpload->created_by = Yii::$app->user->id;
		            	$modelUpload->save();

		            	//END: Save file Data to DB
	            	}
	            }
             	
	         //   if ($model->upload($model->id)) {
	            	//upload successful
	           // }
				//END: upload files		

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
				if (is_null($model->carpenter)) {
					$model->carpenter = 0;
				}

				$modelOfferStatusLog->created_by = Yii::$app->user->id;
				$modelOfferStatusLog->created = date('Y-m-d H:i:s');
				$modelOfferStatusLog->offer_id = $model->id;
				$modelOfferStatusLog->offer_no = $model->offer_no;
				$modelOfferStatusLog->customer_id = $model->customer_id;
				$modelOfferStatusLog->status_id = $model->status_id;
				$modelOfferStatusLog->created = date('Y-m-d H:i:s');
	            $modelOfferStatusLog->created_by = Yii::$app->user->id;

				$modelChange->change_object = 'offer';
				$modelChange->change_object_id = $model->id;
            	$modelChange->created = date('Y-m-d H:i:s');
            	$modelChange->created_by = Yii::$app->user->id;


	            if (isset($modelOfferStatusLog->contact_date)) {
	                $modelOfferStatusLog->contact_date = date('Y-m-d',strtotime($modelOfferStatusLog->contact_date));    
	            }
	            if (isset($modelOfferStatusLog->next_followup_date)) {
	                $modelOfferStatusLog->next_followup_date = date('Y-m-d',strtotime($modelOfferStatusLog->next_followup_date));    
	            }



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
								$a = CustomerDiscount::findOne(['offer_item_type_id'=>$modelOfferItem->offer_item_type_id, 'customer_id'=>$model->customer_id]);
								if ($a != NULL) {
									$modelOfferItem->base_discount_perc = $a->base_discount_perc;
								}
								else {
									$modelOfferItem->base_discount_perc = 0;
								}
								

								$modelOfferItem->value_net = round5((100-$modelOfferItem->base_discount_perc)*$modelOfferItem->value_total/100);
								$modelOfferItem->value_total_net = round5(((100-$modelOfferItem->base_discount_perc)*$modelOfferItem->value_total)/100);
								$modelOfferItem->order_line_net_value = round5(((100-$modelOfferItem->project_discount_perc)*$modelOfferItem->value_total_net)/100);
								if ($modelOfferItem->qty != 0) {
									$modelOfferItem->value = $modelOfferItem->value_total/$modelOfferItem->qty;
								}
								else {
									$modelOfferItem->value = 0;
								}
								$list = array('offer_item_type_id', 'qty', 'value_total', 'project_discount_perc');
								foreach ($list as $key => $value) {
									if ($modelOfferItem->{$value} != TRUE) {
										$modelOfferItem->{$value} = 0;
									}	
								}
								
								

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
							$modelChange->save(false);
							$model->save(false);
							
							$transaction->commit();
							//lock Offer for editing
							Offer::updateAll(['locked_by' => 0, 'locked'=>NULL], ['id'=>$model->id]);
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
						'modelsOfferItem' => (empty($modelsOfferItem)) ? [new OfferItem] : $modelsOfferItem,
						'modelOfferStatusLog' => $modelOfferStatusLog,
			
					]
				);
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
		
//	$this->findModel($id)->delete();

		return $this->redirect(['index']);
	}

    /**
     * Lists all So models.
     * @return mixed
     */
    public function actionTvOffer()
    {
        $searchModel = new OfferSearch();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
//        $dataProvider->sort = ['defaultOrder' => ['deadline' => 'DESC']];        

        $this->layout = "tv_offer";

        return $this->render('tv_offer', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ], false, true);
    }

	public function actionGetProductDiscount($customer_id, $offer_item_type_id) 
	{
		$discount = CustomerDiscount::findOne(['customer_id'=>$customer_id, 'offer_item_type_id'=>$offer_item_type_id, 'active'=>'1']);	
		echo Json::encode($discount);

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