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
use app\models\Admin;
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
class UserController extends ActiveController
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
                'index' => ['get'],
                'add-token' => ['post'],
                'logout'=>['get'],
                'login' => ['post'],
                'signup' => ['post'],
                'social-login'=> ['get'],
                'verification' => ['post'],
                'resend-otp' => ['post'],
                'me' => ['get', 'post'],
                'me-update' => ['post'],
                'photo' => ['post'],
                'get-languages' => ['get'],
                'set-language' => ['get'],
                'set-notification-status' => ['get'],
                'follow'=>['post'],
                'follower'=>['get'],
                'following'=>['get'],
                'view-user'=>['get'],                
                'view-user-login'=>['get'],                
                'validate-data'=>['post'],
                'change-password'=>['post'],
                'get-countries'=>['get'],
                'set-country'=>['get'],
                'create-client'=>['post'],
                'client-list'=>['post'],
                'trainee-list'=>['get'],
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
            'social-login',
            'validate-data',
            'login',
            'signup',
            'confirm',
            'password-reset-request',
            'password-reset-token-verification',
            'password-reset',
            'verification',
            'resend-otp',
            'view-user',  
            'index',
            'postman'
        ];

        // setup access
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'only' => ['photo','me','logout','change-password','change-username','add-token','me-update','trainee-list','create-client','delete-trainee'], //only be applied to
            'rules' => [               
                [
                    'allow' => true,
                    'actions' => ['photo','me','logout','change-password','change-username','add-token','me-update'],
                    'roles' => ['user','trainer'],
                ],
                [
                    'allow' => true,
                    'actions' => ['trainee-list','create-client','delete-trainee'],
                    'roles' => ['trainer'],
                ],
            ],
        ];

        return $behaviors;
    }
    public function actionPostman($controller,$action){       
        $d              = '\app\modules\v1\controllers'.'\\';
        $obj_controller = $d.$controller;  
        $r              = new \ReflectionMethod(new $obj_controller("",""), $action);
        $params         = $r->getParameters();
        $d              = [];
        foreach ($params as $param) {
            $arguments = $param->getDefaultValue();
            array_push($d,$arguments);
        }
        echo json_encode($d);
        die;
    }    
   
    public function actionLogout($params = ['attributes'=>[['name'=>'uuid','type'=>'text']],'method'=>'GET']){
        $uuid = !empty($_GET['uuid'])?$_GET['uuid']:"";       
        $UserToken    = \common\models\UserToken::find()->where(['user_id'=>\Yii::$app->user->id,'uuid'=>$uuid])->one();
        if($UserToken){
            $UserToken->delete();
        }   
        return ['status'=> true]; 
    }
    public function actionGetCountry(){
        $appCountries =  \common\models\AppsCountries::find()->where(['status'=>1])->all();
        return ['status'=>true,'data'=>$appCountries];
    }
    /**
     * Follow & Un follow
     *
     * @return array
     * @throws BadRequestHttpException
     */
     
    public function actionSocialLogin($params=['attributes'=>[['name'=>'Provider','type'=>'text'],['name'=>'Token','type'=>'text']],'method'=>'GET','auth'=>1]){
        $Provider = !empty($_GET['Provider'])?$_GET['Provider']:"";
        $Token    = !empty($_GET['Token'])?$_GET['Token']:"";
        $UserType = !empty($_GET['UserType'])?$_GET['UserType']:"";
        if($Provider!="facebook" && $Provider!="google" && $Provider!="instagram" && $Provider!="apple"){
            return [
                'status'=>false,
                'message'=>'You can either login with Facebook or Google or Instagram or Apple.'
            ];
        }
        if($Token == ""){
            return [
                'status'=>false,
                'message'=>'Auth token is missing or invalid.'
            ];
        }
        if($Provider =="facebook"){
            $user_details = "https://graph.facebook.com/me?access_token=".$Token.'&fields=name';           
            try{
                $response     = file_get_contents($user_details);
                $response     = json_decode($response);
                if(empty($response)){
                    return [
                        'status'=>false,
                        'message'=>'Unable to fetch your details.'
                    ];
                }
                $username   =   "";
                $email      =   $response->id.'@facebook.com';
                $id         =   $response->id;   
                $name       =   $id;            
            }catch(\Exception $e){
                return [
                    'status'=>false,
                    'message'=>'Sorry, We are unable to fetch user detail from this facebook access token.'
                ];
            }

        }else if($Provider =="google"){
            try{
                $user_details = 'https://www.googleapis.com/oauth2/v3/tokeninfo?id_token='.urlencode($Token);
                $response     = file_get_contents($user_details);     
                $response     = json_decode($response);
                if(empty($response) || empty($response->email)){
                    return [
                        'status'=>false,
                        'message'=>'Sorry, We are unable to fetch your email address.'
                    ];
                } 
                $arr = explode("@", $response->email);

                $email      =  $response->email;
                $name       =  $arr[0].'_'.time();
                $id         =  $response->sub;
                // $username   =  $arr[0].'_gl';
                $username   =   "";
            }catch(\Exception $e){
                return [
                    'status'=>false,
                    'message'=>'Sorry, We are unable to fetch user detail from this google access token.'
                ];
            }
        }else if($Provider =="apple"){
            try {
                //If the exception is thrown, this text will not be shown
                $appleSignInPayload = ASDecoder::getAppleSignInPayload($Token);
                if(!empty($appleSignInPayload)){
                    $email = $appleSignInPayload->getEmail();
                    $user  = $appleSignInPayload->getUser();
                    
                    if(!empty($email)){

                        $arr = explode("@", $email);
                        $name      =  !empty($arr[0]) ? $arr[0] : '-';
                        $id         =  $user;
                        $username   =  $arr[0].'_ap';

                    } else {
                        return ['status'=>false, 'message'=>'ApiErrors.social_login_google_email_error'];
                    }
                } else {
                    return array('status'=>false,'message'=> 'ApiErrors.social_login_apple_login_error');
                }
            }catch(\Exception $e) {                
                return [
                    'status'=>false,
                    'message'=>$e->getMessage()
                ];
            }            
        }       
        $User = User::find()->where(['social_provider_id'=>$id])->andWhere(['social_type'=>$Provider])->one();     
        if(empty($User)) { 
            $User                       = User::find()->where(['email'=>$email])->one();
            if($User){
                $User->social_type          = $Provider;
                $User->social_provider_id   = $id; 
                $User->save(false);
                return $this->makelogin($User);
            }else{            
                $SignupForm            = new SignupForm;
                $SignupForm->username  = strtolower($name);
                $SignupForm->password  = strtolower($id);
                $SignupForm->email     = strtolower($email);
                $SignupForm->user_type = $UserType;
                if ($SignupForm->validate()) {
                    $user                       = new User(['scenario' => 'register']);
                    $user->social_type          = $Provider;
                    $user->social_provider_id   = $id;            
                    $user->username             = strtolower($name);
                    $user->email                = strtolower($email);
                    $user->unconfirmed_email    = strtolower($email);
                    $user->user_type            = $UserType;
                    $user->role                 = $UserType=="Trainer"?50:10;
                    $user->status               = 10;
                    $user->password             = $id;
                    $user->auth_key             = Yii::$app->security->generateRandomString();
                    $user->registration_ip      = Yii::$app->request->userIP;
                    if ($user->save(false)) { 
                        $UserAdditionalInfo                       = new UserAdditionalInfo;
                        $UserAdditionalInfo->user_id              = $user->id;     
                        $UserAdditionalInfo->subscription_on      = 1;       
                        $UserAdditionalInfo->subscription_start   = time();       
                        $UserAdditionalInfo->subscription_end     = strtotime('+1 month');            
                        $UserAdditionalInfo->save(false);
                        if($UserType=="Trainer"){
                            \Yii::$app->general->templateForWelcomeEmail($email);
                        }
                        return $this->makelogin($user);
                    }else{
                        $user->validate();
                        return array('status'=>false,'message'=>$user->errors);
                    }
                }else{
                    $SignupForm->validate();
                    return array('status'=>false,'message'=>Yii::$app->general->error($SignupForm->errors));
                }
            }
        } else{            
            return $this->makelogin($User);
        }
    }
    private function getCountryData($country_id) {
        $country            = \common\models\AppsCountries::find()->where(['id'=>$country_id])->asArray()->one();
        $code               = !empty($country['country_code']) ?$country['country_code']: strtoupper(\Yii::$app->params['default_country_code']);
        $image              = Url::base(true).'/img_assets/flags/'.strtolower($code).'.png';
        return[
            'country_code'=> $code,     
            'country_name'=> !empty($country['country_name']) ?$country['country_name']: strtoupper(\Yii::$app->params['default_country_name']),
            'country_img'=> $image  
        ];
    }
    private function makelogin($user) {
        if($user->status == 10){    
            $user->generateAccessTokenAfterUpdatingClientInfo(true);
            $id = implode(',', array_values($user->getPrimaryKey(true)));
            $UserAdditionalInfo = UserAdditionalInfo::find()->where(['user_id'=>$id])->one();
            // $UserAdditionalInfo = $UserAdditionalInfo + ['product_id_for_inapp'=>\Yii::$app->setting->val('inapp_product')];
            return  [
                'status'=>true,
                'data'=>[
                    'id' => (int)$id,
                    'access_token' => $user->access_token,
                    'access_token_expired_at' => $user->access_token_expired_at,
                    'on_verification' => 0,
                    'on_setup'=>!empty($UserAdditionalInfo->date_of_birth) && $user->user_type!="Trainer"?0:1,
                    'is_trainer'=>$user->user_type=="Trainer"?'Yes':'No',
                    'email'=>$user->email,               
                    'username'=>$user->username,          
                    'other_info'=>$UserAdditionalInfo,   
                ]
            ];
        }else if($user->status == 1){ 
            $SignupForm = new SignupForm();
            $data       = $SignupForm->sendOtp($user);
            if($data['status']){
                return[
                    'status'=>true,
                    'message'=>$data['message'],
                    'data'=>[
                        'on_verification' => 1                        
                    ]
                ]; 
            }else{
                return $data;
            }                
        }else{
            return[
                'status'=>false,
                'message'=>'Your account was deleted.'
                
            ]; 
        }
    }   
    public function actionLogin($params=['attributes'=>[['name'=>'LoginForm[username]','type'=>'text','description'=>''],['name'=>'LoginForm[password]','type'=>'text','description'=>'']],'auth'=>0,'method'=>'POST'])
    {   
        $model = new LoginForm();
        $model->roles = [
            User::ROLE_USER,
        ];
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $user = $model->getUser();
            $responseData = $this->makelogin($user);
            return $responseData;
        } else {
            // Validation error
            return array('status'=>false,'message'=>Yii::$app->general->error($model->errors));
        }
    }    
    public function actionSignup($params=['attributes'=>[['name'=>'SignupForm[username]','type'=>'text','description'=>''],['name'=>'SignupForm[password]','type'=>'text','description'=>''],['name'=>'SignupForm[email]','type'=>'text','description'=>'']],'auth'=>0,'method'=>'POST'])
    {      
        $model = new SignupForm();
        $model->load(Yii::$app->request->post());
        if ($model->validate()) {           
            return $model->signup();
        } else {
            // Validation error
            return array('status'=>false,'message'=>Yii::$app->general->error($model->errors));
        }
    }   
    public function actionResendOtp($params=['attributes'=>[['name'=>'ResendOTPForm[username]','type'=>'text','description'=>'']],'method'=>'POST','auth'=>0])
    {
        $model = new ResendOTPForm();        
        if($model->load(Yii::$app->request->post()) && $model->validate()){        
            $User = User::find()->where(['OR',['email'=>$model->username],['username'=>$model->username]])->one();
            if($User){  
                if( $User->status == 1){
                    $SignupForm = new SignupForm();  
                    return $SignupForm->sendOtp($User);
                }else{
                    if($User->status == 10){
                        return array('status'=>false,'message'=>'Your account is already verified.');                        
                    }else{
                        return array('status'=>false,'message'=>'Oops!, Your account was deleted');                        
                    }
                } 
            } else {
                // Validation error
                return array('status'=>false,'message'=>'Sorry, You are not registered with us before. Please try to sign-up first.');
            }
        }else{
            return array('status'=>false,'message'=>Yii::$app->general->error($model->errors));
        }
    }
    public function actionVerification($params=['attributes'=>[['name'=>'code','type'=>'text','description'=>'']],'method'=>'POST','auth'=>0])
    {
        $response = \Yii::$app->getResponse();
        $post      = Yii::$app->request->post();
        if (!empty($post['code'])) {
            $UserVerificationCode =  UserVerificationCode::find()->where(['code'=>$post['code']])->one();
            if($UserVerificationCode){
                if($UserVerificationCode->expired_at > time()){
                    $User = User::find()->where(['id'=>$UserVerificationCode->user_id])->one();
                    if($User){
                        $User->confirmed_at =  time();
                        $User->status       =  User::STATUS_ACTIVE;
                        if($User->save()){
                            //########### Delete the used code ##################
                            $UserVerificationCode->delete();
                            $user = User::findByUsername($User->username);
                            if($user->user_type=="Trainer"){
                                \Yii::$app->general->templateForWelcomeEmail($user->email);
                            }
                            return $this->makelogin($user);
                            //return array('status'=>true);
                        }else{
                            return array('status'=>false,'message'=>Yii::$app->general->error($model->errors));
                        }
                    }else{
                        return array('status'=>false,'message'=>'Sorry, User is not exist.');
                    }
                }else{
                    return array('status'=>false,'message'=>'Your OTP has been expired.');
                }
            }else{
                return array('status'=>false,'message'=>'Sorry, You have entered invalid OTP.');
            }
        } else {
            // Validation error
            return array('status'=>false,'message'=>'Please enter your 4-digit OTP.');
        }
    }
    public function actionPasswordResetRequest($params=['attributes'=>[['name'=>'PasswordResetRequestForm[email]','type'=>'text','description'=>'']],'method'=>'POST','auth'=>0])
    {
        $model = new PasswordResetRequestForm();
        $model->load(Yii::$app->request->post());
        if ($model->validate() && $model->sendPasswordResetEmail()) {
            return array('status'=>true,'message' => Lx::t('user-controller','We have just sent you a temporary password on your registered email.'));
        } else {
            // Validation error
            return array('status'=>false,'message'=>\Yii::$app->general->error($model->errors));
        }
    }
    public function actionChangeUsername(){
        $user = User::findIdentity(\Yii::$app->user->getId());     
        $UserAdditionalInfo              = UserAdditionalInfo::find()->where(['user_id'=>\Yii::$app->user->getId()])->one();  
        if ($user) {
            $model      = new UserEditForm();
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $User= User::find()->where(['id'=>\Yii::$app->user->getId()])->one();
                $User->username         = $model->username;
                $User->email            = $model->email;
                $User->first_name       = $model->first_name;
                $User->last_name        = $model->last_name;
                $User->phone            = $model->phone;
                $User->business         = $model->business;
                $User->business_address = $model->business_address;
                $User->business_phone   = $model->business_phone;
                $User->save(false);     
                $user->generateAccessTokenAfterUpdatingClientInfo(true);          
                return array('status'=>true,
                'data'=>[
                    'id' => (int)$user->id,
                    'access_token' => $user->access_token,
                    'access_token_expired_at' => $user->access_token_expired_at,
                    'on_verification' => 0,
                    'on_setup'=>0,
                    'is_trainer'=>$user->user_type=="Trainer"?'Yes':'No',
                    'email'=>$User->email,      
                    'username'=>$User->username,          
                    'first_name'=>$User->first_name,          
                    'last_name'=>$User->last_name,          
                    'phone'=>$User->phone,          
                    'business'=>$User->business,          
                    'business_address'=>$User->business_address,          
                    'business_phone'=>$User->business_phone,          
                    'other_info'=>$UserAdditionalInfo,     
                ],
                'message'=>Lx::t('user-controller','Your profile has been successfully changed.'));
            } else {
                return array('status'=>false,'message'=>\Yii::$app->general->error($model->errors));
            }
        } else {
            // Validation error
            return array('status'=>false,'message'=>'Object not found');
        }
    }
    public function actionUserData(){
        return $this->getUseData(\Yii::$app->user->id);
    }
    private function getUseData($user_id){
        $user               =  UserData::find()->joinWith(['userAdditionalInfos'])->where(['user.id'=>$user_id])->asArray()->one();          
        if ($user) {   
            $user['userAdditionalInfos']['sports_interest'] =  json_decode($user['userAdditionalInfos']['sports_interest'],true)  ;  
            return [
                'status'=>true,
                'data'=>[
                    'email'=>$user['email'],
                    'username'=>$user['username'],
                    'other_data'=>$user['userAdditionalInfos']
                ]
            ];
        } else {
            // Validation error
            return array('status'=>false,'message'=>'Object not found');
        }
    }      
   
    public function actionChangePassword($params=['attributes'=>[['name'=>'ChangePasswordForm[password]','type'=>'text','description'=>''],['name'=>'ChangePasswordForm[confirm_password]','type'=>'text','description'=>'']],'method'=>'POST','auth'=>0]){
        $user = User::findIdentity(\Yii::$app->user->getId());       
        if ($user) {
            $model      = new ChangePasswordForm();
            $model->id  = \Yii::$app->user->getId();
            $model->load(Yii::$app->request->post());
            if ($model->validate() && $model->save()) {
                return array('status'=>true,'message'=>Lx::t('user-controller','Your password has been successfully changed.'));
            } else {
                return array('status'=>false,'message'=>\Yii::$app->general->error($model->errors));
                
            }
        } else {
            // Validation error
            return array('status'=>false,'message'=>'Object not found');
        }
    }
    public function actionPhoto($params=['attributes'=>[['name'=>'ImageForm[photo]','type'=>'file','description'=>'']],'method'=>'POST','auth'=>0])
    {
        $oldPhoto   = "";
        $UserAdditionalInfo              = UserAdditionalInfo::find()->where(['user_id'=>\Yii::$app->user->getId()])->one();
        if(!$UserAdditionalInfo){
            $UserAdditionalInfo          = new UserAdditionalInfo;
            $UserAdditionalInfo->user_id = \Yii::$app->user->getId();
        }else if($UserAdditionalInfo->photo){
            $oldPhoto   = $UserAdditionalInfo->photo;
        }         
        $model    =  new ImageForm();
        if (Yii::$app->request->isPost) {
            $model->photo = UploadedFile::getInstance($model, 'photo');
            if(!$model->photo){
                return array('status'=>false,'message'=>'Please select a photo.');
            }
            if ($model->photo && $model->validate()) {     
                $BasePath       =  Yii::$app->basePath.'/../img_assets/users/';      
                $filename       =  time().'-'.$model->photo->baseName . '.' . $model->photo->extension;
                $thumb_image    = 'thumb_'.$filename;     
                 
                ;      
                if(Image::thumbnail($model->photo->tempName, 150, 150)->save(Yii::getAlias($BasePath.$thumb_image), ['quality' => 90])){       
                    $UserAdditionalInfo->photo       =  $thumb_image;
                    $UserAdditionalInfo->thum_photo  =  $thumb_image;
                    if($UserAdditionalInfo->validate() && $UserAdditionalInfo->save()){ 
                              
                        $user = User::findIdentity(\Yii::$app->user->getId()); 
                        $user->generateAccessTokenAfterUpdatingClientInfo(true); 
                        return array('status'=>true,
                            'message'=>'Profile picture updated.',
                            'data'=>[
                                'id' => (int)$user->id,
                                'access_token' => $user->access_token,
                                'access_token_expired_at' => $user->access_token_expired_at,
                                'on_verification' => 0,
                                'on_setup'=>0,
                                'is_trainer'=>$user->user_type=="Trainer"?'Yes':'No',
                                'email'=>$user->email,      
                                'username'=>$user->username,          
                                'other_info'=>$UserAdditionalInfo,     
                            ],
                        );                          
                    }else{
                        return array('status'=>false,'message'=>\Yii::$app->general->error($UserAdditionalInfo->errors));
                    }
                }else{
                    return array('status'=>false,'message'=>'Unable to upload photo.');
                }
                
            }else{
                return array('status'=>false,'message'=>\Yii::$app->general->error($model->errors));
            }
        }else{
            return array('status'=>false,'message'=>['errors'=>[Lx::t('user-controller','Image is missing.')]]);
        }
    }    
    
    public function actionAddToken($params=['attributes'=>[['name'=>'UserToken[uuid]','type'=>'text','description'=>''],['name'=>'UserToken[platform]','type'=>'text','description'=>'Android | Ios ']],'method'=>'POST','auth'=>0]){		
        $model             =  new \common\models\UserToken;		
        if($model->load(Yii::$app->request->post()) && $model->validate()){		
            $model->user_id    =  \Yii::$app->user->id;  		
            $model->app_type   =  'User'; 		
            $UserToken         =  \common\models\UserToken::find()->where([		
                                    'user_id'=>$model->user_id,		
                                    'platform'=>$model->platform,		
                                    'uuid'=>(string)$model->uuid,		
                                    'app_type'=>$model->app_type])->one();		
            if(!empty($UserToken)){		
                return array('status'=>true,'data'=>['uuid'=>(string)$model->uuid]);		
            }     		
            if($model->save()){		
                return array('status'=>true);		
            }else{		
                return array('status'=>false,'message'=>\Yii::$app->general->error($model->errors));		
            }		
        }else{		
            return array('status'=>false,'message'=>\Yii::$app->general->error($model->errors));		
        }		        
    }

    public function actionCreateClient($params=['attributes'=>[
        ['name'=>'Admin[username]','type'=>'text','description'=>''],
        ['name'=>'Admin[email]','type'=>'text','description'=>''],
        ['name'=>'Admin[user_type]','type'=>'text','description'=>''],
        ['name'=>'UserAdditionalInfo[date_of_birth]','type'=>'text','description'=>''],
        ['name'=>'UserAdditionalInfo[gender]','type'=>'text','description'=>''],
        ['name'=>'UserAdditionalInfo[units_of_measurement]','type'=>'text','description'=>''],
        ['name'=>'UserAdditionalInfo[weight]','type'=>'text','description'=>''],
        ['name'=>'UserAdditionalInfo[weight_unit]','type'=>'text','description'=>''],
        ['name'=>'UserAdditionalInfo[height]','type'=>'text','description'=>''],
        ['name'=>'UserAdditionalInfo[height_unit]','type'=>'text','description'=>''],
        ['name'=>'UserAdditionalInfo[address]','type'=>'text','description'=>''],
        ['name'=>'UserAdditionalInfo[contact_data]','type'=>'text','description'=>''],
    ],'auth'=>1,'method'=>'POST'])
    {
        $model   = new Admin();
        $model_1 = new UserAdditionalInfo();

        if($model->load(Yii::$app->request->post()) && $model_1->load(Yii::$app->request->post()))
        {
            $temp                       = rand(11111111,99999999);
            $user                       = new User(['scenario' => 'register']);
            $user->username             = strtolower($model->username);
            $user->email                = strtolower($model->email);
            $user->unconfirmed_email    = strtolower($model->email);
            $user->user_type            = "User";
            $user->role                 = 10;
            $user->status               = 10;
            $user->password             = $temp;
            $user->auth_key             = Yii::$app->security->generateRandomString();
            $user->registration_ip      = Yii::$app->request->userIP;
            if($user->validate() && $user->save()){
                $model_1->user_id              = $user->id;                
                if($model_1->validate() && $model_1->save()){
                    $user_trainee             = new \common\models\UserTrainee;
                    $user_trainee->trainer_id = \Yii::$app->user->id;
                    $user_trainee->trainee_id = $user->id;
                    $user_trainee->created_at = time();
                    $user_trainee->save();
                    $email =  Yii::$app->mailer->compose()
                                ->setTo($user->email)
                                ->setFrom([\Yii::$app->setting->val('senderEmail') => \Yii::$app->name])
                                ->setSubject(\Yii::$app->name.' Account Detail')
                                ->setHtmlBody(Yii::$app->emailtemplate->replace_string_email([
                                    '{{username}}'=>$user->username,
                                    '{{password}}'=>$temp,
                                ] ,"sent-login-data"))->send();
                    return array('status'=>true,'message' => "We have just sent the Username and Password to the Client's registered email.");
                }else{
                    $user->delete();
                    return array('status'=>false,'message'=>\Yii::$app->general->error($model_1->errors));
                }                
            }else{
                return array('status'=>false,'message'=>\Yii::$app->general->error($user->errors));
            }
        }else{
            return array('status'=>false,'message'=>[\Yii::$app->general->error($model->errors),\Yii::$app->general->error($model_1->errors)]);
        }
    }
    public function actionDeleteTrainee($trainee_id)
    {
        $trainee  = \common\models\UserTrainee::find()->where(['trainer_id'=>\Yii::$app->user->id,'trainee_id'=>$trainee_id])->one();
        if($trainee && $trainee->delete()){
            $userLicense = \common\models\UserLicenses::find()->where(['trainee_id'=>$trainee_id,'trainer_id'=>\Yii::$app->user->id])->one();
            if($userLicense){
                $userLicense->trainee_id = 0;
                $userLicense->expired_at = 0;
                if($userLicense->save(false)){
                    return array('status'=>true);
                }else{
                    return array('status'=>false,'message'=>\Yii::$app->general->error($userLicense->errors)); 
                }
            }
            return array('status'=>true);
        }else{
            return array('status'=>false,'message'=>'Invalid trainee');
        }
    }
    public function actionTraineeList()
    {
        $trainee_list  = \common\models\UserTrainee::find()->joinWith([
            'traineeDetail',
            'traineeAdditionalDetail',
        ])->where(['user_trainee.trainer_id'=>\Yii::$app->user->id])->all();
        $t = [];
        foreach($trainee_list as $k=>$trainee){
            $t[$k]['trainee_id'] = $trainee->traineeDetail->id;
            $t[$k]['username'] =   $trainee->traineeDetail->username;
            $t[$k]['email'] =   $trainee->traineeDetail->email;
            if($trainee->traineeAdditionalDetail->subscription_on){
                $t[$k]['license'] =  "Subscription From ".date("d M ",
                $trainee->traineeAdditionalDetail->subscription_start).' - '.date("d M ",$trainee->traineeAdditionalDetail->subscription_end);
            }else{
                $t[$k]['license'] =  "";
            }
            $t[$k]['lic_expired_at'] =  "";
            $t[$k]['photo']= !empty($trainee->traineeDetail->photo)?
            \yii\helpers\Url::base(true).'/img_assets/users/'.$trainee->traineeAdditionalDetail->photo:\yii\helpers\Url::base(true).'/img_assets/users/default.png';
            $t[$k]['loginData']= $this->makelogin($trainee->traineeDetail);
        }       
        return [
            'status'=>true,
            'data'=>[
                    'trainee_list' => $t,
            ]
        ];
    }
   
    /**
     * Handle OPTIONS
     *
     * @param null $id
     * @return string
     */
    public function actionOptions($id = null)
    {
        return 'ok';
    }
}
