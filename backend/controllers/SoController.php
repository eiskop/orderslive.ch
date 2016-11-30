<?php

namespace backend\controllers;

use Yii;
use backend\models\So;
use backend\models\SoSearch;
use backend\models\SoItem;
use backend\models\Customer;
use backend\models\CustomerPriority;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;


/**
 * SoController implements the CRUD actions for So model.
 */
class SoController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all So models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort = ['defaultOrder' => ['id' => 'DESC']];        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Lists all So models.
     * @return mixed
     */
    public function actionTvkellpax()
    {
        $searchModel = new SoSearch();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort = ['defaultOrder' => ['deadline' => 'DESC']];        

        $this->layout = "tv";

        return $this->render('tv_kellpax', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ], false, true);
    }
    /**
     * Lists all So models.
     * @return mixed
     */
    public function actionTvwirus()
    {
        $searchModel = new SoSearch();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort = ['defaultOrder' => ['deadline' => 'DESC']];        

        $this->layout = "tv";

        return $this->render('tv_wirus', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ], false, true);
    }




    /**
     * Displays a single So model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new So model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {

        if (Yii::$app->user->can('create-so')) 
        {
            $model = new So();

            if ($model->load(Yii::$app->request->post())) {

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
                $assigned_to = User::findOne(['id'=>$model->assigned_to]);


                $model->customer_priority_id = $customer->customer_priority_id;
                $model->days_to_process = $prio->days_to_process;            

                $model->deadline = strtotime($model->order_received)+$prio->days_to_process*60*60*24;  

                $uts_received = strtotime($model->order_received);
                $model->order_received = date('Y-m-d', $uts_received);
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

                
                     

                $model->save(false);
                return $this->redirect(['create', 'id' => $model->id]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
        else 
        {
            throw new ForbiddenHttpException;
        }
    }

    /**
     * Updates an existing So model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
     

        if (Yii::$app->user->can('change-so') OR Yii::$app->user->can('update-so')) 
        {
            $model = $this->findModel($id);


            if ($model->load(Yii::$app->request->post())) {

                $model->updated = date('Y-m-d H:i:s');
                $model->updated_by = Yii::$app->user->id;

                $uts_received = strtotime($model->order_received);
                $model->order_received = date('Y-m-d', $uts_received);
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

                



                $model->save(false);

                return $this->redirect(['index']);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
        else {
            throw new ForbiddenHttpException;
        }
    }

    /**
     * Deletes an existing So model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if (Yii::$app->user->can('delete-so')) {

            $this->findModel($id)->delete();

            return $this->redirect(['index']);
        }
        else {
            throw new ForbiddenHttpException;
        }
    }

    /**
     * Finds the So model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return So the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = So::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
