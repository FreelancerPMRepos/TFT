<?php
namespace app\modules\v1\controllers;
use app\filters\auth\HttpBearerAuth;
use app\models\LoginForm;
use app\models\PasswordResetForm;
use app\models\PasswordResetRequestForm;
use app\models\PasswordResetTokenVerificationForm;
use app\models\SignupConfirmForm;
use app\models\SignupForm;
use app\models\ResendOTPForm;
use app\models\User;
use common\models\UserData;
use common\models\UserBankDetail;
use common\models\FollowerFollowing;
use common\models\FollowerSearch;
use common\models\AppsCountries;
use common\models\UserBlock;

use app\models\UserEditForm;
use app\models\UserSearch;
use app\models\UserVerificationCode;
use app\models\UserSocialAuth;
use common\models\UserAdditionalInfo;
use app\models\ImageForm;
use yii\web\UploadedFile;
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
use yii\imagine\Image;
use app\models\ChangePasswordForm;
use AppleSignIn\ASDecoder;
class UserAccountController extends ActiveController
{
    public $modelClass = 'app\models\User';

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
                'my-profile-detail' => ['post'],
                'view-user'=>['get'],
                'plan-list'=>['get']
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
            'options',
            'view-user',
            'view-user-videos',
        ];

        // setup access
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'only' => ['my-profile-detail','update-player','transaction'], //only be applied to
            'rules' => [
               
                [
                    'allow' => true,
                    'actions' => ['setup'],
                    'roles' => ['user'],
                ],       
                [
                    'allow' => true,
                    'actions' => ['update-player'],
                    'matchCallback' => function ($rule, $action) {
                        if (Yii::$app->user->identity->userAdditionalInfos->u_type != 3) {
                            return true;
                        }
                        throw new \yii\web\HttpException(403, Lx::t('app', 'Your are not player.'));
                    }
                ],          
            ],
        ];

        return $behaviors;
    }      
    
   
    public function actionSetup($params = ['attributes'=>[['name'=>'SetUpProfile[gender]','type'=>'text','description'=>'']],
        ['name'=>'SetUpProfile[date_of_birth]','type'=>'text','description'=>''],
        ['name'=>'SetUpProfile[measurement]','type'=>'text','description'=>''],
        ['name'=>'SetUpProfile[weight]','type'=>'text','description'=>''],
        ['name'=>'SetUpProfile[weight_unit]','type'=>'text','description'=>''],
        ['name'=>'SetUpProfile[height_unit]','type'=>'text','description'=>''],
        ['name'=>'SetUpProfile[height]','type'=>'text','description'=>''],
        ['name'=>'SetUpProfile[sports]','type'=>'text','description'=>''],'method'=>'POST','auth'=>1])
    {
        $model = new \app\models\SetUpProfile;
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            
            $login_user_id = \Yii::$app->user->id;
            $user                         = \common\models\UserAdditionalInfo::find()->where(['user_id'=>$login_user_id])->one();          
            $user->gender                 = $model->gender;
            $user->date_of_birth          = $model->date_of_birth;
            $user->units_of_measurement   = $model->measurement;
            $user->weight                 = $model->weight;
            $user->weight_unit            = $model->weight_unit;
            $user->height_unit            = $model->height_unit;
            $user->height                 = $model->height;
            $user->sports_interest        = !empty($model->sports)?json_encode($model->sports):"";
            $mData =  \Yii::$app->general->bmi($user->weight,$user->weight_unit,$user->height,$user->height_unit,$user->gender);
            $user->lbw                    = !empty($mData['lbw'])?$mData['lbw']:"";
            $user->bmi                    = !empty($mData['bmi'])?$mData['bmi']:"";
            if($user->save()){
                return [
                    'status'=>true,
                    'data'=> $user,   
                    'message'=>'Your profile has been saved.'
                ];               
            }else{
                return [
                    'status'=>false,
                    'message'=>\Yii::$app->general->error($user->errors)
                ]; 
            }
        }else{
            return [
                'status'=>false,
                'message'=>\Yii::$app->general->error($model->errors)
            ]; 
        }    
    }    
    public function actionOptions($id = null)
    {
        return 'ok';
    }	
    
}
