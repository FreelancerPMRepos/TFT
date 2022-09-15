<?php

namespace app\modules\v1\controllers;

use app\filters\auth\HttpBearerAuth;
use common\models\Notification;
use Yii;
use yii\filters\AccessControl;
use yii\filters\auth\CompositeAuth;
use yii\helpers\Url;
use yii\rest\ActiveController;
use yii\web\BadRequestHttpException;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use lajax\translatemanager\helpers\Language as Lx;
class NotificationController extends ActiveController
{
    public $modelClass = 'common\models\Notification';

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
           // 'get-page',
        ];
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'only' => ['index'], //only be applied to
            'rules' => [
               
                [
                    'allow' => true,
                    'actions' => ['index','remove-all','badge-count'],
                    'roles' => ['user'],
                ],
                [
                    'allow' => true,
                    'actions' => ['remove'],
                    'roles' => ['updateOwnPost'],
                    'roleParams' => function() {
                        return ['post' => Notification::findOne(['id' => Yii::$app->request->get('id')])];
                    },
                ],
            ],
        ];
        return $behaviors;
    }
    public function actionIndex($params = ['attributes'=>[['name'=>'page','type'=>'text','description'=>'']],'method'=>'GET','auth'=>1])
    {
        Notification::updateAll(['is_read'=>"Y"],'user_id = '.Yii::$app->user->id.' AND is_read = "N"');
        $search             = new Notification();
        $search->attributes = \Yii::$app->request->get();  
        $data               =  $search->getItem();
        return ['status'=>true,'data'=>$data];
    }
    public function actionRemove($params = ['attributes'=>[['name'=>'id','type'=>'text','description'=>'']],'method'=>'GET','auth'=>1]){   
        $id = !empty($_GET['id'])?$_GET['id']:0;   
        $Notification = Notification::find()->where(['id'=>$id,'user_id'=>\Yii::$app->user->id])->one();    
        if(!empty($Notification) && $Notification->delete()){
                return[
                    'status'=>true,
                    'data'=>''
                ];
            }else{               
                return ['status'=>false,
                'message'=>'Unable to remove this notification, Please try after sometime.'];
            }
    }
    public function actionRemoveAll(){
        Notification::deleteAll(['user_id'=>\Yii::$app->user->id]);
        return[
            'status'=>true,
            'data'=>''
        ];
    }
    public function actionBadgeCount(){
        $d = Notification::find()->where(['user_id'=>\Yii::$app->user->id,'is_read'=>'N'])->count();
        return[
            'status'=>true,
            'data'=>['badge'=>$d]
        ];
    }

    public function actionOptions($id = null)
    {
        return 'ok';
    }
}
