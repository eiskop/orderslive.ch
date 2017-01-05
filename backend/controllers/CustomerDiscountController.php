<?php

namespace backend\controllers;

use Yii;
use backend\models\CustomerDiscount;
use backend\models\CustomerDiscountSearch;
use backend\models\OfferItemType;
use backend\models\Model;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CustomerDiscountController implements the CRUD actions for CustomerDiscount model.
 */
class CustomerDiscountController extends Controller
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
     * Lists all CustomerDiscount models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CustomerDiscountSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CustomerDiscount model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new CustomerDiscount model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CustomerDiscount();

        if ($model->load(Yii::$app->request->post())) {
            $model->created = date('Y-m-d H:i:s');
            $model->created_by = Yii::$app->user->id;
            $model->valid_from = date('Y-m-d', strtotime($model->valid_from));
            $model->save(false);
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing CustomerDiscount model.
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
            $model->valid_from = date('Y-m-d', strtotime($model->valid_from));
            $model->save(false);
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing CustomerDiscount model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {

       // echo '<pre>', var_dump($_SERVER);
      //  exit;
        $this->findModel($id)->delete();

        return $this->redirect(empty(Yii::$app->request->referrer)?['index']:Yii::$app->request->referrer);    
    }

    public function actionGetProductDiscount($customer_id) 
    {
        $discount = CustomerDiscount::findOne(['customer_id'=>$customer_id, 'active'=>'1']);    
        echo Json::encode($discount);

    }

    /**
     * Finds the CustomerDiscount model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CustomerDiscount the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CustomerDiscount::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
