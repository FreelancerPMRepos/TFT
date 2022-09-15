<?php

namespace backend\controllers;

use Yii;
use common\models\Log;
use common\models\LogSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;

use yii\filters\VerbFilter;

/**
 * LogController implements the CRUD actions for Log model.
 */
class LogController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [                    
                    [
                        'actions' => ['clear','index','view','delete','api','cron','add-user','clear-cron'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }   
    public function actions()
    {
        return [
            'cron' => '\yii2mod\cron\actions\CronLogAction',
            // Also you can override some action properties in following way:
            'cron' => [
                'class' => '\yii2mod\cron\actions\CronLogAction',
                'searchClass' => [
                    'class' => '\yii2mod\cron\models\search\CronScheduleSearch',
                    'pageSize' => 10
                ],
                'view' => 'cron_log'
            ]
        ];
    }
    public function actionApi()
    {
        $searchModel = new LogSearch();
        $dataProvider = $searchModel->searchApi(Yii::$app->request->queryParams);

        return $this->render('api-log', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * Lists all Log models.
     * @return mixed
     */
    public function actionIndex()
    {
       
        $searchModel = new LogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Log model.
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
     * Deletes an existing Log model.
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
     * Deletes an existing Log model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionClear()
    {
        Yii::$app->db->createCommand()->truncateTable('log')->execute();

        return $this->redirect(['index']);
    }
    public function actionClearCron()
    {
        Yii::$app->db->createCommand()->truncateTable('cron_schedule')->execute();
        return $this->redirect(['log/cron']);
    }


    /**
     * Finds the Log model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Log the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Log::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
