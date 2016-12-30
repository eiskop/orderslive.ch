<?php

namespace backend\controllers;

use Yii;
use backend\models\CustomerUpload;
use backend\models\CustomerUploadSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\UploadForm;
use yii\web\UploadedFile;

/**
 * CustomerUploadController implements the CRUD actions for CustomerUpload model.
 */
class CustomerUploadController extends Controller
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
     * Lists all CustomerUpload models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CustomerUploadSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CustomerUpload model.
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
     * Creates a new CustomerUpload model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CustomerUpload();
        $model->created_by = Yii::$app->user->id;
        $model->created = date('Y-m-d H:i:s');
        $model->valid_from = date('d.m.Y', strtotime($model->valid_from));
        $model->valid_to = date('d.m.Y', strtotime($model->valid_to));        

        if ($model->load(Yii::$app->request->post())) {
            $valid_from = date('Y-m-d', strtotime($model->valid_from));
            $valid_to = date('Y-m-d', strtotime($model->valid_to));
            $model->valid_from = $valid_from;
            $model->valid_to = $valid_to;
                // upload files
                $model->uploadedFiles = UploadedFile::getInstances($model, 'uploadedFiles');
               // echo '<pre>', var_dump($model->uploadedFiles);
               // echo '<pre>', var_dump($_FILES);
             //   exit;
                if (!is_null($model->uploadedFiles)) {
                    //echo '<pre>', var_dump($model->uploadedFiles);
                    $uploadDir = 'uploads/customer/'.$model->customer_id;
                    if (!file_exists($uploadDir)) {
                        mkdir($uploadDir);
                    }
                    foreach ($model->uploadedFiles as $file) {

                        $pinfo = pathinfo($file);
                    
                        $file_name = $uploadDir.'/'.$file->name;
                      
                        while(file_exists($file_name)) {
                            $file_name = $uploadDir.'/'.$pinfo['filename'].'_'.date('d-m-Y_H-i-s', time()).'.'.$pinfo['extension'];
                            //$file_name.'<br>';
                        }
                        //echo $file_name.'<br>';   
                        

                        $pinfo2 = pathinfo($file_name);
                        //echo '<pre>', var_dump($pinfo2);
                     //   exit;

                        //Save file Data to DB
                        $model->customer_id = $model->customer_id;
                        $model->file_path = $file_name;
                        $model->file_name = $pinfo2['basename'];
                        if ($model->title != TRUE)  {
                            $model->title = $pinfo['basename'];    
                        }
                        $model->file_extension = $pinfo['extension'];
                        $model->file_type = $file->type;
                        $model->file_size = $file->size;
                        $model->created = date('Y-m-d H:i:s');
                        $model->created_by = Yii::$app->user->id;
                        //echo '<pre>', var_dump($model->file_path);
                       // echo '<pre>', var_dump($model);
                        //END: Save file Data to DB
                    }
                }
                
             //   if ($model->upload($model->id)) {
                    //upload successful
               // }

            //  echo 'file upload model all files processed';
          //    exit;
    
            if ($model->save(false)) {
                $file->saveAs($file_name);  
            }
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing CustomerUpload model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->valid_from = date('d.m.Y', strtotime($model->valid_from));
        $model->valid_to = date('d.m.Y', strtotime($model->valid_to));
        if ($model->load(Yii::$app->request->post())) {
            $model->changed_by = Yii::$app->user->id;
            $model->changed = date('Y-m-d H:i:s');
            $valid_from = date('Y-m-d', strtotime($model->valid_from));
            $valid_to = date('Y-m-d', strtotime($model->valid_to));
            $model->valid_from = $valid_from;
            $model->valid_to = $valid_to;
        
            $model->save(false);
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing CustomerUpload model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if (file_exists($model->file_path)) {
            unlink($model->file_path);
        }
        $model->delete();


        return $this->redirect(['index']);
    }

    /**
     * Finds the CustomerUpload model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CustomerUpload the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CustomerUpload::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
