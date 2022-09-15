<?php

namespace app\modules\v1\controllers;

use app\filters\auth\HttpBearerAuth;
use common\models\Cms;
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
class PageController extends ActiveController
{
    public $modelClass = 'common\models\Cms';

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
                'get-page' => ['get'],
                'app-txt'=>['get'],
                'common'=>['get'],
                'contact-us'=>['post']
                
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
            'get-page',
            'app-txt',
            'common',
            'contact-us'
        ];

        return $behaviors;
    }
    public function actionAppTxt(){
        $d = \Yii::$app->general->getLangData();
        if($d){
            return [
                'status'=>true,
                'data'=> $d
            ];
        }else{
            return [
                'status'=>false,
                'message'=>'No translation for this language.'
            ];
        }
    }
    /**
     * Get Page
     *
     * @return array
     * @throws BadRequestHttpException
     */
    public function actionContactUs($params=['attributes'=>[['name'=>'ContactForm[name]','type'=>'text','description'=>''],['name'=>'ContactForm[email]','type'=>'text','description'=>''],['name'=>'ContactForm[subject]','type'=>'text','description'=>''],['name'=>'ContactForm[body]','type'=>'text','description'=>'']],'auth'=>0,'method'=>'POST'])
    {
        $model = new \app\models\ContactForm();
        $adminEmail = \Yii::$app->setting->val('adminEmail');
        if ($model->load(Yii::$app->request->post()) && $model->sendEmail($adminEmail)) {
            return array('status'=>true,'message' => Lx::t('user-controller','Thank you for contacting us!'));
        } else {
           $model->validate();
            return array('status'=>false,'message'=>\Yii::$app->general->error($model->errors));
        }
    }
    public function actionGetPage($params=['attributes'=>[['name'=>'slug','type'=>'text','description'=>'']],'auth'=>0,'method'=>'GET'])
    {
        $slug =  !empty($_GET['slug'])?$_GET['slug']:"";
        if($slug == ""){
            return [
                'status'=>false,
                'message'=>'Missing required parameters: slug'
            ];
        }
        $Page = Cms::find()->select(['title','app_body'])->where(['slug'=>$slug])->one();
        if($Page){
            return [
                'status'=>true,
                'data' => $Page
            ];
        }else{
            return [
                'status'=>false,
                'message'=>'Sorry, There is no any pages with this slug.'
            ];
        }
    }
    public function actionCommon($params=['attributes'=>[],'auth'=>0,'method'=>'GET']){      
        $sports      = \common\models\Sports::find()->where(1)->orderBy('name ASC')->asArray()->all();
        $sports_data = [];
        foreach($sports as $k => $ele){
                $a['id']    =  $ele['id'];
                $a['title'] =  $ele['name'];
                $a['check'] =  false;
                array_push($sports_data,$a);
        }
        
        $cardio_exes      = \common\models\Exercise::find()->where(['record_type'=>'Cardio'])->orderBy('name ASC')->asArray()->all();
        $cardio_exes_data = Yii::$app->general->optionsForApp($cardio_exes,'id','name');
        
       
        $path_exes_data      = \common\models\Exercise::find()->where(['!=','record_type','Cardio'])->orderBy('name ASC')->asArray()->all();
        $path_exes_data = Yii::$app->general->optionsForApp($path_exes_data,'id','name'); 
        
        $body_part_data      = \common\models\ExerciseCategory::find()->where(['!=','id',11])->orderBy('name ASC')->asArray()->all();
        $body_part_data = Yii::$app->general->optionsForApp($body_part_data,'id','name');
       
        $body_attributes = ['Weight',
        'Arm - Right','Arm - Left','Body Fat','Calf - Right','Calf - Left','Chest','Forearm - Right',
        'Forearm - Left','Heart Rate','Hip','Neck','Shoulders','Thigh - Right','Thigh - Left','Waist','Waist-Hip Ratio (calculated)'];
        sort($body_attributes);
        $body_attributes_data = [];
        foreach($body_attributes as $k => $ele){
            $ax['value']    =  $ele;
            $ax['label']    =  $ele;
            array_push($body_attributes_data,$ax);
        }
        return[
            'status'=>true,
            'data'=>[
                'sports_data'=>$sports_data,
                'cardio_exes'=>$cardio_exes_data,
                'path_exes_data'=>$path_exes_data,
                'body_part_data'=>$body_part_data,
                'body_attributes_data'=>$body_attributes_data

               // 'country'=>$country_data,
            ]
        ];
    }
    public function actionOptions($id = null)
    {
        return 'ok';
    }
}
