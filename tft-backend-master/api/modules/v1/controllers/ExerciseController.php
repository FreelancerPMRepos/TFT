<?php
namespace app\modules\v1\controllers;
use app\filters\auth\HttpBearerAuth;
use yii\web\UploadedFile;

use Yii;
use app\models\Exercise;
use yii\filters\AccessControl;
use yii\filters\auth\CompositeAuth;
use yii\helpers\Url;
use yii\rest\ActiveController;
use yii\web\BadRequestHttpException;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

class ExerciseController extends ActiveController
{
    public $modelClass = 'app\models\Exercise';

    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);
    }

    public function actions()
    {
        return [];
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => CompositeAuth::className(),
            'authMethods' => [
                HttpBearerAuth::className(),
            ],

        ];

        $behaviors['verbs'] = [
            'class' => \yii\filters\VerbFilter::className(),
            'actions' => [
                'index' => ['get'],
                'edit' => ['post'],
            ],
        ];

        // remove authentication filter
        $auth = $behaviors['authenticator'];
        unset($behaviors['authenticator']);

        // add CORS filter
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
            ],
        ];

        // re-add authentication filter
        $behaviors['authenticator'] = $auth;
        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        $behaviors['authenticator']['except'] = [
           // '',
           'view',
        ];
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'only' => ['exe-categories','create','index','add-for-routine'], //only be applied to
            'rules' => [
               
                [
                    'allow' => true,
                    'actions' => ['exe-categories','create','index','add-to-custom','add-for-routine'],
                    'roles' => ['user'],
                ],
                [
                    'allow' => true,
                    'actions' => ['edit'],
                    'roles' => ['user'],
                ],
            ],
        ];
        return $behaviors;
    }
    public function actionAddForRoutine($exe_id = 0){
        $loadModel              = \common\models\Exercise::find()->where(['id'=>$exe_id])->one();
        if(empty($loadModel)){
            return array('status'=>false,'message'=>'Invalid Object');
        }
        $model              = new \common\models\Exercise;
        $model->attributes  = $loadModel->attributes;
        $model->for_routine = 1;
        $model->user_id     = \Yii::$app->user->id;
        $model->record_type = "Weight And Reps";
        if($model->save(false)){
            return['status'=>true];
        }else{
            return array('status'=>false,'message'=>\Yii::$app->general->error($model->errors));
        }
    }
    public function actionRemoveForRoutine($exe_id = 0){
        $loadModel              = \common\models\Exercise::find()->where(['id'=>$exe_id,'user_id'=>\Yii::$app->user->id])->one();
        if(empty($loadModel)){
            return array('status'=>false,'message'=>'Invalid Object');
        }
        $loadModel->is_deleted = 1;
        if($loadModel->save(false)){
            return['status'=>true];
        }else{
            return array('status'=>false,'message'=>'Unable to delete.');
        }
    }
    public function actionIndex()
    {
        $category_id = isset($_GET['category_id']) ? $_GET['category_id'] : null;
        $user_id     = !empty($_GET['custom']) ? \Yii::$app->user->id:1;
        $query    = \common\models\Exercise::find()->joinWith(['category'])
        ->where(['is_active'=>1,'is_deleted'=>0]);
        if(empty($_GET['custom'])){
            $query->andWhere(['for_routine' => 1,'user_id'=>1]);
        }else{
            $query->andWhere(['OR',['for_routine' => 0],['user_id' => \Yii::$app->user->id]]);
        }
        if($category_id){
            $query->andWhere(['exe_category_id' => $category_id]);
        }
        if(empty($_GET['custom'])){
            $query->orderBy('Name ASC')->asArray();
        }else{
            $query->orderBy('user_id DESC,Name ASC')->asArray();
        }

        $page     = isset($_GET['page']) && $_GET['page'] > 0 ? ($_GET['page'] - 1) : 0;
        $pageSize = 200;
        $provider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'pagination' => [               
                'page' => $page,
                'pageParam' => 'page',
                'defaultPageSize' => $pageSize,
            ]
        ]);

        $models   = $provider->getModels();
        $data = []; $exeIds = [];
        foreach ($models as $key => $value) {
            
            if(in_array($value['name'],$exeIds) ){
                continue;
            }
            
            array_push($exeIds,$value['name']);
            $dex =  $value;
            $dex['remove_btn'] =  $value['user_id']==1?false:$value['user_id']==\Yii::$app->user->id?true:false;
            $dex['view_btn']   =  $value['user_id']==1?true:false;
            if(isset($value['img']) && $value['img'] != ''){
                $dex['img'] = \yii\helpers\Url::to('img_assets/exercise/'.$value['img'], $schema = true);
            }else{
                $dex['img'] = \yii\helpers\Url::to('img_assets/exercise/empty.jpg', $schema = true);
            }
            if(isset($value['gif']) && $value['gif'] != ''){
                $dex['gif'] = \yii\helpers\Url::to('img_assets/exercise/'.$value['gif'], $schema = true);
            }else{
                $dex['gif'] = \yii\helpers\Url::to('img_assets/exercise/empty.jpg', $schema = true);
            }
            array_push($data,$dex);

        }
        $pagination = array_intersect_key(
            (array)$provider->pagination,
            array_flip(
                \Yii::$app->params['paginationParams']
            )
        );

        $totalPage                  = ceil($pagination['totalCount'] / $pageSize);   
        $pagination['totalPage']    = $totalPage;
        $pagination['currentPage']  = !empty($_GET['page'])?$_GET['page']:1;
        $pagination['isMore']       = $totalPage <= $pagination['currentPage'] ? false:true;       
        return [
            'status'=>true,
            'data'=>[
                    'items' => $data,
                    'pagination' => $pagination,
            ]
        ];
      
    }

    public function actionCreate(){
        $Exercise               = new \common\models\Exercise;
        $Exercise->user_id      = \Yii::$app->user->id;
        $Exercise->for_routine  =  1;
        $Exercise->record_type  =  'Weight And Reps';
        if ($Exercise->load(Yii::$app->request->post()) && $Exercise->save()) {
            return array('status'=>true);
        }else{
            return array('status'=>false,'message'=>Yii::$app->general->error($Exercise->errors));
        }
    }
    public function actionExeCategories()
    {
        $query    = \common\models\ExerciseCategory::find()->orderBy('name ASC')->asArray()->all();
        $data = [];
        foreach ($query as $key => $value) {
            $data[$key] =  $value;

            if(isset($value['img'])){
                $data[$key]['img'] = \yii\helpers\Url::to('/img_assets/gym/'.$value['img'], $schema = true);
            }

        }

        return [
            'status'=>true,
            'data'=> $data
        ];
    }

    public function actionView($params = ['attributes'=>[['name'=>'id','type'=>'text','description'=>'']],'method'=>'GET','auth'=>1])
    {
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        if($id == null){
            return [
                'status' => false,
                'message' => 'Exercise id is Required.'
            ];
        }
        $query    = \common\models\Exercise::find()->where(['is_active'=>1, 'id' => $id]);
        $provider = new \yii\data\ActiveDataProvider([
            'query' => $query
        ]);
        $models   = $provider->getModels();

        $data = [];
        foreach ($models as $key => $value) {
            $data[$key] =  $value;

            if(isset($value['img']) && $value['img'] != ''){
                $data[$key]['img'] = \yii\helpers\Url::to('img_assets/exercise/'.$value['img'], $schema = true);
            }else{
                $data[$key]['img'] = \yii\helpers\Url::to('img_assets/exercise/empty.jpg', $schema = true);
            }
            if(isset($value['gif']) && $value['gif'] != ''){
                $data[$key]['gif'] = \yii\helpers\Url::to('img_assets/exercise/'.$value['gif'], $schema = true);
            }else{
                $data[$key]['gif'] = \yii\helpers\Url::to('img_assets/exercise/empty.jpg', $schema = true);
            }
        }
        
        if(!empty($data)){
            return [
                'status' => true,
                'data'   => $data[0],
            ];
        }else {
            return [
                'status'  => false,
                'message' => 'No Data Found.',
            ];
        }
      
    }

    protected function findModel($id){
        if (($Exercise = Exercise::findOne($id)) !== null) {
            return $Exercise;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionGetCardio()
    {
        $query    = \common\models\Exercise::find()->where(['record_type'=> 'Cardio'])->orderBy('id ASC')->asArray();

        $page     = isset($_GET['page']) && $_GET['page'] > 0 ? ($_GET['page'] - 1) : 0;
        $pageSize = 20;
        $provider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'pagination' => [               
                'page' => $page,
                'pageParam' => 'page',
                'defaultPageSize' => $pageSize,
            ]
        ]);

        $models   = $provider->getModels();
        $pagination = array_intersect_key(
            (array)$provider->pagination,
            array_flip(
                \Yii::$app->params['paginationParams']
            )
        );

        $totalPage                  = ceil($pagination['totalCount'] / $pageSize);
        $pagination['totalPage']    = $totalPage;
        $pagination['currentPage']  = !empty($_GET['page'])?$_GET['page']:1;
        $pagination['isMore']       = $totalPage <= $pagination['currentPage'] ? false:true;

        return [
            'status'=>true,
            'data'=> [
                'items' => $models,
                'pagination' => $pagination,
        ]
        ];
    }
}
?>