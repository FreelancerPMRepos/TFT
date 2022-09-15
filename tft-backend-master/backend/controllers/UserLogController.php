<?php

namespace backend\controllers;

use Yii;
use common\models\UserLog;
use common\models\UserLogSearch;
use common\models\WorkoutSearch;
use common\models\UserLogBody;
use common\models\UserLogBodySearch;
use common\models\UserLogNote;
use common\models\UserLogNoteSearch;
use common\models\UserLogPhoto;
use common\models\UserLogPhotoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * UserLogController implements the CRUD actions for UserLog model.
 */
class UserLogController extends Controller
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


    public function actionIndex($user_id = 0)
    {
        $searchModel            = new UserLogSearch();
        $searchModel->user_id   = $user_id;
        $dataProvider           = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel'   => $searchModel,
            'dataProvider'  => $dataProvider,
        ]);
    }

    public function actionWorkouts($user_id = 0)
    {
        $model     =    new WorkoutSearch();
        if ($model->load(Yii::$app->request->post())) 
        {
            list($start_date, $end_date) = explode(' - ', $model->created_at_range);
            echo "start_date: ".$start_date." -- "."end_date: ".$end_date; die;
            return $this->render('view', [
                'provider'   => Yii::$app->general->getWorkoutLog($user_id, $model->created_at_range),
                'model'      => $model,
            ]);
        }
        return $this->render('view', [
            'provider'   => Yii::$app->general->getWorkoutLog($user_id),
            'model'=> $model,
        ]);
    }

    public function actionNotes($user_id = 0)
    {
        $searchModel     =    new UserLogNoteSearch();
        $dataProvider    =    $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('view', [
            'searchModel'   => $searchModel,
            'dataProvider'  => $dataProvider,
        ]);
    }

    public function actionPhotos($user_id = 0)
    {

        $searchModel     =    new UserLogPhotoSearch();
        $dataProvider    =    $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('view', [
            'searchModel'   => $searchModel,
            'dataProvider'  => $dataProvider,
        ]);
    }

    public function actionBodyStats($user_id = 0)
    {
        $searchModel     =    new UserLogBodySearch();
        $dataProvider    =    $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('view', [
            'searchModel'   => $searchModel,
            'dataProvider'  => $dataProvider,
        ]);
    }
}
