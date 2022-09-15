<?php

namespace backend\controllers;

use Yii;
use common\models\ExerciseCategory;
use common\models\ExerciseCategorySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\imagine\Image;
use yii\web\UploadedFile;

/**
 * ExerciseController implements the CRUD actions for ExerciseCategory model.
 */
class ExercisecategoryController extends Controller
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

    /**
     * Lists all ExerciseCategory models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ExerciseCategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ExerciseCategory model.
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
     * Creates a new ExerciseCategory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ExerciseCategory();

        $image = UploadedFile::getInstance($model,'img');
        $BasePath = Yii::$app->basePath . '/../img_assets/gym/';

        if ($model->load(Yii::$app->request->post())) {

            if($image){
                $filename           =  time().'-'.$image->baseName . '.' . $image->extension;
                $thumb_image        = 'thumb_'.$filename;
                if($image->saveAs($BasePath.$filename)){
                    Image::thumbnail($BasePath.$filename, 600, 600)->save(Yii::getAlias($BasePath.$thumb_image), ['quality' => 90]);
                    $model->img     = $thumb_image;
                }
            }
            else{
                $model->img     = "";
            }

            if($model->save(false)){
                return $this->redirect(['view', 'id' => $model->id]);
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
     * Updates an existing ExerciseCategory model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $image = UploadedFile::getInstance($model,'img');

        if ($model->load(Yii::$app->request->post())) {
            $BasePath = Yii::$app->basePath . '/../img_assets/gym/';

            if($image){
                $filename           =  time().'-'.$image->baseName . '.' . $image->extension;
                $thumb_image        = 'thumb_'.$filename;
                $image->saveAs($BasePath.$filename);
                Image::thumbnail($BasePath.$filename, 600, 600)->save(Yii::getAlias($BasePath.$thumb_image), ['quality' => 90]);
                $model->img = $thumb_image;
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
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ExerciseCategory model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ExerciseCategory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ExerciseCategory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ExerciseCategory::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
