<?php

namespace backend\controllers;

use Yii;
use backend\models\Customer;
use backend\models\CustomerSearch;
use backend\models\CustomerUpload;
use backend\models\CustomerUploadSearch;
use backend\models\CustomerDiscountSearch;
use backend\models\So;
use backend\models\SoSearch;
use backend\models\Offer;
use backend\models\OfferSearch;
use backend\models\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\UploadForm;
use yii\web\UploadedFile;
/**
 * CustomerController implements the CRUD actions for Customer model.
 */
class CustomerController extends Controller
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
     * Lists all Customer models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CustomerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        if (Yii::$app->user->can('delete-customer')) {
            $dataProvider->query->andWhere('customer.active > -1');
        }
        else {
            $dataProvider->query->andWhere('customer.active = 1');
        }
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Customer model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {

        $searchModel = new SoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere('customer_id = '.$id);
        $searchModel2 = new OfferSearch();
        $dataProvider2 = $searchModel2->search(Yii::$app->request->queryParams);
        $dataProvider2->query->andWhere('customer_id = '.$id);        
        $searchModel3 = new CustomerUploadSearch();
        $dataProvider3 = $searchModel3->search(Yii::$app->request->queryParams);
        $dataProvider3->query->andWhere('customer_id = '.$id);   
        $searchModel4 = new CustomerDiscountSearch();
        $dataProvider4 = $searchModel4->search(Yii::$app->request->queryParams);
        $dataProvider4->query->andWhere('customer_id = '.$id);   
        return $this->render('view', [
            'model' => $this->findModel($id),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'searchModel2' => $searchModel2,
            'dataProvider2' => $dataProvider2,
            'searchModel3' => $searchModel3,
            'dataProvider3' => $dataProvider3,
            'searchModel4' => $searchModel4,
            'dataProvider4' => $dataProvider4,            
        ]);        
    }

    /**
     * Creates a new Customer model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Customer();

        if ($model->load(Yii::$app->request->post())) {
            $model->created = date('Y-m-d H:i:s');
            $model->created_by = Yii::$app->user->id;            
            if ($model->zip_code != FALSE) {
                if ($model->zip_code[0] == 1 OR $model->zip_code[0] == 2) {
                    $model->region = 'W-CH';
                }
                else {
                    $model->region = 'D-CH';   
                }
            }
            $model->active = 1;
            $model->save(false);            
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Customer model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
            $model->updated = date('Y-m-d H:i:s');
            $model->updated_by = Yii::$app->user->id;            
            if ($model->zip_code != FALSE) {
                if ($model->zip_code[0] == 1 OR $model->zip_code[0] == 2) {
                    $model->region = 'W-CH';
                }
                else {
                    $model->region = 'D-CH';   
                }
            }
            
            
            $model->save(false);

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }


    /**
     * Deletes an existing Customer model.
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
     * Finds the Customer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Customer the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Customer::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
