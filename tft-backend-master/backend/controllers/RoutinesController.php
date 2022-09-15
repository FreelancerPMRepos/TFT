<?php

namespace backend\controllers;

use Yii;

use common\models\UserCardioRoutine;
use common\models\UserCardioRoutineTime;

use common\models\Exercise;
use common\models\Routines;
use common\models\RoutinesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;

/**
 * RoutinesController implements the CRUD actions for Routines model.
 */
class RoutinesController extends Controller
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
                        'actions' => ['cardio-view','cardio','exercise','index','view','create','mapping','sets','mapping-edit','sets-edit','update','mapping-delete','sets-delete','delete'],
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
    //ajax
    public function actionExercise()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $id = $data['id'];
            $exe_name = Exercise::find()->where(['exe_category_id'=>$id])->orderBy("name ASC")->asArray()->all();
            
            if($exe_name){
                $exe_name = ArrayHelper::map($exe_name,'id','name');
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return $exe_name;
            }else{
                return 0;
            }
        }else{
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    //cardio
    public function actionCardio($user_id = 0,$name = "")
    {
        $model = UserCardioRoutine::find()->where(['user_id'=>$user_id])->one();
        return $this->render('//cardio/index', [
            'model' => $model,
            'name'  => $name, 
        ]);
    }
    public function actionCardioView($id = 0,$user_id = 0)
    {
        $model = UserCardioRoutineTime::findAll(['cardio_routine_id'=>$id]);
        return $this->render('//cardio/view', [
            'model' => $model,
            // 'name'  => $name, 
        ]);
    }


    /**
     * Lists all Routines models.
     * @return mixed
     */
    public function actionIndex($user_id = 0)
    {
        $searchModel            = new RoutinesSearch();
        if($user_id){            
            $searchModel->user_id   = $user_id;
        }else{
            $searchModel->user_id   = 0;
        }
        $dataProvider               =    $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Routines model.
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
     * Creates a new Routines model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($user_id = 0)
    {
        // die;
        $model = new Routines();
        if ($model->load(Yii::$app->request->post())) {
            if($user_id){
                $model->user_id = $user_id;
                if($model->save()){
                    return $this->redirect(['index','user_id'=>$user_id]);
                }
            }
            else{
                if($model->save()){
                    return $this->redirect(['index']);
                }
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }
    // mapping
    // public function actionMapping($id) 
    // {
    //     $model = new RoutinesWeeks();
    //     $searchModel = new RoutinesWeeksSearch();
    //     $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$id);

    //     $model->routine_id = $id;
    //     if($model->load(Yii::$app->request->post())){
    //         if($model->save()){ $this->refresh(); } //clear field after save
    //         else{
    //             // print_r($model->errors);die;
    //         }
    //     }

    //     return $this->render('//routines-weeks/create', [
    //         'model' => $model,
    //         'searchModel' => $searchModel,
    //         'dataProvider' => $dataProvider,
    //     ]);
    // }
    //SETS
    // public function actionSets($id)
    // {
    //     $model       = new RoutinesWeeksSets();
    //     $searchModel = new RoutinesWeeksSetsSearch();
    //     $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$id);

    //     $model->routine_week_id = $id;
    //     if($model->load(Yii::$app->request->post())){
    //         if($model->save()){ $this->refresh(); }
    //         else{
                
    //         }
    //     }

    //     return $this->render('//routines-weeks-sets/create', [
    //         'model' => $model,
    //         'searchModel' => $searchModel,
    //         'dataProvider' => $dataProvider,
    //     ]);
    // }

    //mapping-edit
    // public function actionMappingEdit($id,$sets_id)
    // {
    //     $model = new RoutinesWeeks();
    //     $searchModel = new RoutinesWeeksSearch();
    //     $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$id);
    //     $model = $this->findModelWeeks($sets_id);

    //     // if ($model->load(Yii::$app->request->post()) && $model->save()) {}
    //     if($model->load(Yii::$app->request->post())){
    //         if($model->save()){ 
    //             $this->refresh(); 
    //             $this->redirect(array('mapping', 'id'=>$id));
    //         } 
    //         else{
    //             // print_r($model->errors);die;
    //         }
    //     }

    //     return $this->render('//routines-weeks/create', [
    //         'model' => $model,
    //         'searchModel' => $searchModel,
    //         'dataProvider' => $dataProvider,
    //     ]);
    // }
    //sets-edit
    // public function actionSetsEdit($id,$exeID,$c_id,$_id)
    // {
    //     $model = new RoutinesWeeksSets();
    //     $searchModel = new RoutinesWeeksSetsSearch();
    //     $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$id);
    //     $model = $this->findModelSets($c_id);

    //     // if ($model->load(Yii::$app->request->post()) && $model->save()) {}
    //     if($model->load(Yii::$app->request->post())){
    //         if($model->save()){ 
    //             $this->refresh(); 
    //             $this->redirect(array('sets', 'id'=>$id, 'exeID'=>$exeID, '_id'=>$_id));
    //         } 
    //         else{
    //             print_r($model->errors);die;
    //         }
    //     }

    //     return $this->render('//routines-weeks-sets/create', [
    //         'model' => $model,
    //         'searchModel' => $searchModel,
    //         'dataProvider' => $dataProvider,
    //     ]);
    // }

    /**
     * Updates an existing Routines model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Routines model.
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

    public function actionMappingDelete($id)
    {
        $this->findModelWeeks($id)->delete();
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionSetsDelete($id)
    {
        $this->findModelSets($id)->delete();
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Finds the Routines model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Routines the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Routines::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    protected function findModelWeeks($id)
    {
        if (($model = RoutinesWeeks::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    protected function findModelSets($id)
    {
        if (($model = RoutinesWeeksSets::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
