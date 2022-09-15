<?php

namespace backend\controllers;

use Yii;
use common\models\Sports;
use common\models\SportsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\ImageForm;
use yii\imagine\Image;
use yii\web\UploadedFile;

/**
 * SportsController implements the CRUD actions for Sports model.
 */
class SportsController extends Controller
{
    /**
     * {@inheritdoc}
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

    public function actionSsstr()
    {
        $searchModel = new SportsSearch();
        $searchModel->sportType = "ssstr";
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionSsgst()
    {
        $searchModel = new SportsSearch();
        $searchModel->sportType = 'ssgst';
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Sports models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SportsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Sports model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Sports model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Sports();

        $image = UploadedFile::getInstance($model,'images');
        $BasePath = Yii::$app->basePath . '/../img_assets/sports/';

        if ($model->load(Yii::$app->request->post())) {

            if($image){
                $filename           =  time().'-'.$image->baseName . '.' . $image->extension;
                $thumb_image        = 'thumb_'.$filename;
                if($image->saveAs($BasePath.$filename)){
                    Image::thumbnail($BasePath.$filename, 600, 600)->save(Yii::getAlias($BasePath.$thumb_image), ['quality' => 90]);
                    $model->images     = $thumb_image;
                }
            }
            else{
                $model->images     = "";
            }
            
            if($model->save(false)){
                return $this->redirect(['index']);
            } else{
                print_r($model->errors);
                die;
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Sports model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $image = UploadedFile::getInstance($model,'images');
        if ($model->load(Yii::$app->request->post())) {
            $path = Yii::$app->basePath . '/../img_assets/sports/';
            if($image){
                $image->saveAs($path.$image->name);
                $model->images = $image->name;
            }
            if($model->save(false))
            {
                // return $this->redirect(['view', 'id' => $model->id]);
                return $this->redirect(['index']);
            }
            else{
                print_r($model->errors);
                die;
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Sports model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Finds the Sports model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Sports the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sports::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
