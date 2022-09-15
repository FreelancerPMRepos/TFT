<?php

namespace backend\controllers;

use Yii;
use common\models\AppsCountries;
use common\models\AppsCountriesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * CountryController implements the CRUD actions for AppsCountries model.
 */
class CountryController extends Controller
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
                        'actions' => ['create','update','delete','index'],
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

    /**
     * Lists all AppsCountries models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AppsCountriesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionUpdate($id,$status)
    {
        $model          = $this->findModel($id);
        $model->status  = $status;
        $model->save(false);
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = AppsCountries::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
