<?php

namespace backend\controllers;

use Yii;
use backend\models\OfferStatusLog;
use backend\models\OfferStatusLogSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OfferStatusLogController implements the CRUD actions for OfferStatusLog model.
 */
class OfferStatusLogController extends Controller
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
     * Lists all OfferStatusLog models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OfferStatusLogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OfferStatusLog model.
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
     * Creates a new OfferStatusLog model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new OfferStatusLog();

        if ($model->load(Yii::$app->request->post())) {
            $model->created = date('Y-m-d H:i:s');
            $model->created_by = Yii::$app->user->id;
            if (isset($model->contact_date)) {
                $model->contact_date = date('Y-m-d',strtotime($model->contact_date));    
            }
            if (isset($model->next_followup_date)) {
                $model->next_followup_date = date('Y-m-d',strtotime($model->next_followup_date));    
            }
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing OfferStatusLog model.
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
            if (isset($model->contact_date)) {
                $model->contact_date = date('Y-m-d',strtotime($model->contact_date));    
            }
            if (isset($model->next_followup_date)) {
                $model->next_followup_date = date('Y-m-d',strtotime($model->next_followup_date));    
            }
            $model->save();

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing OfferStatusLog model.
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
     * Finds the OfferStatusLog model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OfferStatusLog the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OfferStatusLog::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
