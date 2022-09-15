<?php

namespace app\modules\v1\controllers;

use app\filters\auth\HttpBearerAuth;
use common\models\UserData;
use common\models\Video;
use common\models\VideoPayment;
use common\models\CategoriesLivePrice;

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
use yii\web\UploadedFile;
use google\apiclient; 
class PaymentController extends ActiveController
{
    public $modelClass = '';
	public $public_key = "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAqdBhyECW9j7ODoYswFUNf6HCkom8tt4b8X/W9TDKN46qA7I7erDO61JpVmSdtI30oVsej6mmTkAJLilRWrA3MQ+lJI36BLQsSChSyz8GdtglNcUAeoVVPK19HWUxopGm/j+B5m2sdRCDi2yNqaeZPZ73DcDZALONISF1rXG6YVKrszSBNQjHcraeHis76CAhvzGhhai/CP+bPUOte1Xfhl4sUTOiDYtKeFSyVBgPO7UbZ8oYH9z9DVfW+TNtH0msIdh4vgsl72GAvay0OqA3SMCTosqDA/s6on7/2IbCkpI/sLeWBRvE7GscuZuHNBrPeusJbipXeoaRXcSuTrhf7wIDAQAB";
	
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
				'in-app-verification'=>['post'],  
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
			'cron-for-subscription',
			'up-sell',
			'send-reminder'
        ];
        //setup access
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'only' => ['subscribe'], //only be applied to
            'rules' => [                
				[
                    'allow' => true,
					'actions' => ['subscribe'],
                    'roles' => ['user','trainer'],
                ],
			],
			
        ];
        return $behaviors;
	}
	public function actionSubscribe(){
		$verification = $this->verification();		
		if($verification['status'] == false){
			return $verification;
		}
		$user_id 				 = Yii::$app->user->id;
		$User 					 = \common\models\UserAdditionalInfo::find()
									->where(['IN','user_id',$user_id])->one();
		$User->subscription_json = $_POST['json'];
		$User->save(false);
		$data = [];
		array_push($data,$User);
		return $this->subscription($data);
	}
	public function actionSendReminder($day = 5){
		$next_5_day =  time() + $day*86400;
		$next_5_day =  date("Y-m-d",$next_5_day);

		$users = \yii\helpers\ArrayHelper::getColumn(\common\models\UserAdditionalInfo::find()
        ->select(['user_id'])
        ->where("DATE_FORMAT(FROM_UNIXTIME(user_additional_info.subscription_end),'%Y-%m-%d') = '".$next_5_day."'")
		->asArray()->all(),'user_id');

        $UserData = \common\models\User::find()->select(['email'])->where(['IN','id',$users])->asArray()->all();
		foreach($UserData as $k => $user){
				$html =  Yii::$app->emailtemplate->replace_string_email([
					'{{name}}'=>$user['email'],
				     '{{days}}'=>$day
				] ,"reminder_email");

				$email =  Yii::$app->mailer->compose()
                ->setTo($user['email'])
                ->setFrom([\Yii::$app->setting->val('senderEmail') => \Yii::$app->name])
                ->setSubject('TFT - Reminder email for subscription renewal.')
                ->setHtmlBody($html)->send();
		}
	}
	public function actionUpSell(){
		$users = \yii\helpers\ArrayHelper::getColumn(\common\models\UserAdditionalInfo::find()
        ->select(['user_id'])
        ->where(['!=','sports_interest',''])
		->asArray()->all(),'user_id');
		

        $usersSSTR = \yii\helpers\ArrayHelper::getColumn(\common\models\UserSport::find()
        ->select(['user_id'])
		->asArray()->all(),'user_id');
		
		$users =  array_diff($users,$usersSSTR);
        $UserData = \common\models\User::find()->select(['email'])->where(['IN','id',$users])->asArray()->all();

        foreach($UserData as $k => $user){
            if($k < 100){
                $url =  \yii\helpers\Url::toRoute(['/site/pay?email='.$user['email']],true);
				$html =  Yii::$app->emailtemplate->replace_string_email([
                    '{{name}}'=>$user['email'],
                    '{{link}}'=>$url,
				] ,"up_sell_email");
				
				$email =  Yii::$app->mailer->compose()
                ->setTo($user['email'])
                ->setFrom([\Yii::$app->setting->val('senderEmail') => \Yii::$app->name])
                ->setSubject('TFT - Buy Sport Specific Strength Training Routine Program.')
                ->setHtmlBody($html)->send();
            }
		}
	}
	public function actionCronForSubscription(){
		$Users 		= \common\models\UserAdditionalInfo::find()
		->where(['<=','subscription_end',time()])
		->all();
		$this->subscription($Users);
	}
	private function getRefreshToken(){
		$clientID     = "572638839432-8ungk7gv6ke2s91ckka4i0243gjgusbt.apps.googleusercontent.com";
		$clientSecret = "ZtEXMRd6aiSWDMUDSrNOJp49";
		$redirectUri  = "http://localhost/tft/";

		$client = new \Google_Client();
		$client->setApplicationName("TFT");
		$client->setAccessType('offline');
		$client->setClientId($clientID);
		$client->setClientSecret($clientSecret);
		$client->setRedirectUri($redirectUri);
		$client->addScope("https://www.googleapis.com/auth/androidpublisher");

		$access_token = Yii::$app->setting->val('refresh_token');
		$access_token = json_decode($access_token,true);

		$client->setAccessToken($access_token['access_token']);
		$client->refreshToken($access_token['refresh_token']);
		$tokenArray 	  = $client->getAccessToken();
		$tokenArrayJson   = json_encode($tokenArray);
		$model 			= \common\models\Setting::find()->where(['meta_key'=>'refresh_token'])->one();
		if($model){	
			$model->meta_value = $tokenArrayJson;
			$model->save(false);
		}
		return $tokenArray['refresh_token'];
	}
	private function subscription($Users){
		$AppRent =  Yii::$app->setting->val('app_rent');
		if($Users){
			foreach($Users as $user){
				if($user->subscription_type == "Stripe"){
					$subscription = Yii::$app->stripePayment->retrieveSubscription($user->stripe_subscription_id);
					if($subscription->status == "active"){
						$user->subscription_start     = $subscription->current_period_start;
						$user->subscription_end       = $subscription->current_period_end;
						$user->subscription_on        = 1;
						$user->subscription_type      = "Stripe";
						$user->subscription_json      = json_encode($subscription);
						$user->stripe_subscription_id = $subscription->id;
					}else{
						$user->subscription_on        = 0;
						$user->subscription_type      = "Stripe";
						$user->subscription_json      = json_encode($subscription);
						$user->stripe_subscription_id = $subscription->id;
					}
					if($user->save(false)){
						if($user->subscription_on){
							$UserInAppTransaction 				= new \common\models\UserInAppTransaction;
							$UserInAppTransaction->user_id 		= $user->user_id;
							$UserInAppTransaction->description  = "Subscription start from ".				date('d F,Y H:i A',$user->subscription_start).' to '.date('d F,Y H:i',$user->subscription_end).' Using Stripe';
							$UserInAppTransaction->t_type  = "App Rent";
							$UserInAppTransaction->value   = $AppRent;
							$UserInAppTransaction->save(false);
						}
					}
				}else{
					if(empty($user->subscription_json) || $user->stripe_subscription_id){
						continue;
					}
					if($user->subscription_json){
						$dataAndroid   = json_decode($user->subscription_json,true);
						$dataAndroid   = json_decode($dataAndroid['dataAndroid'],true);
		
						$appid 		   = $dataAndroid['packageName'];
						$productID 	   = $dataAndroid['productId'];
						$purchaseToken = $dataAndroid['purchaseToken'];
				
						$ch 		   = curl_init();
						$TOKEN_URL     = "https://accounts.google.com/o/oauth2/token";
						$VALIDATE_URL  = "https://www.googleapis.com/androidpublisher/v3/applications/".
						$appid."/purchases/subscriptions/".
						$productID."/tokens/".$purchaseToken;
				
						$clientID     = "572638839432-8ungk7gv6ke2s91ckka4i0243gjgusbt.apps.googleusercontent.com";
						$clientSecret = "ZtEXMRd6aiSWDMUDSrNOJp49";
						$redirectUri  = "http://localhost/tft/";
						$refreshToken = $this->getRefreshToken();
				
						$input_fields = 'refresh_token='.$refreshToken.
							'&client_secret='.$clientSecret.
							'&client_id='.$clientID.
							'&redirect_uri='.$redirectUri.
							'&grant_type=refresh_token';
					
						//Request to google oauth for authentication
						curl_setopt($ch, CURLOPT_URL, $TOKEN_URL);
						curl_setopt($ch, CURLOPT_POST, 1);
						curl_setopt($ch, CURLOPT_POSTFIELDS, $input_fields);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						$result = curl_exec($ch);
						$result = json_decode($result, true);
						if (isset($result["error"])) {
							return ['status'=>false,
							'message'=>$result["error_description"],'data'=>$result];
						}
						//request to play store with the access token from the authentication request
						$ch 	= 	curl_init();
									curl_setopt($ch,CURLOPT_URL,$VALIDATE_URL."?access_token=".$result["access_token"]);
									curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
						$result =   curl_exec($ch);
						$result =   json_decode($result, true);
						if (!empty($result["error"])) {
							return ['status'=>false,
							'message'=>$result["error"]['message'],'data'=>$result["error"]];
						}
						$user->subscription_type  =  "Google";
						$user->subscription_end   =  floor($result['expiryTimeMillis'] / 1000);
						$user->subscription_start =  floor($result['startTimeMillis'] / 1000);
					}
					$user->subscription_on    =  $user->subscription_end > time() ? 1:0;
					if($user->save(false)){
						if($user->subscription_on){
							$UserInAppTransaction 				= new \common\models\UserInAppTransaction;
							$UserInAppTransaction->user_id 		= $user->user_id;
							$UserInAppTransaction->description  = "Subscription start from ".				date('d F,Y H:i A',$user->subscription_start).' to '.date('d F,Y H:i',$user->subscription_end);
							$UserInAppTransaction->t_type  = "App Rent";
							$UserInAppTransaction->value   = $AppRent;
							$UserInAppTransaction->save(false);
						}
					}
				}
				$userDaa = \common\models\User::find()->where(['id'=>$user->user_id])->asArray()->one();
				if($userDaa){
					$html =  Yii::$app->emailtemplate->replace_string_email([
						'{{name}}'=>$userDaa['email'],
						'message'=>'Thank you for Subscribing us.'
					] ,"thank_you_for_business");
					$email =  Yii::$app->mailer->compose()
					->setTo($userDaa['email'])
					->setFrom([\Yii::$app->setting->val('senderEmail') => \Yii::$app->name])
					->setSubject('TFT - Thanks for your business.')
					->setHtmlBody($html)->send();
				}
				
			}
			return [
				'status'=>true
			];
		}else{
			return [
				'status'=>false,
				'message'=>'Users Can not be empty'
			];
		}
	}
	function isJson($string) {
		json_decode($string);
		return (json_last_error() == JSON_ERROR_NONE);
	}
	private function verification(){
		if(empty($_POST['json'])){
            return ['status'=>false,
                    'message'=>'JSON is missing.'];
		}
		if(!$this->isJson($_POST['json'])){
			return ['status'=>false,
                    'message'=>'JSON is invalid.','data'=>$_POST['json']];
		}

		$postJson    			= $_POST['json'];
		$upgradeFrom 			= !empty($_POST['platform'])?$_POST['platform']:"";
		$responseData		    = json_decode($postJson,true);	
		if($upgradeFrom == 'Android'){		
			$productId 				= !empty($responseData['productId']) ? $responseData['productId'] : '';
			$signedData				= !empty($responseData['transactionReceipt']) ? $responseData['transactionReceipt'] : '';;
			$signature 				= !empty($responseData['signatureAndroid']) ? $responseData['signatureAndroid'] : '';
			$verifyResult 			= $this->verify_market_in_app($signedData, $signature, $this->public_key);			
			if($verifyResult){				
				return['status'=>true,'productId'=>$productId];
			}else{
				return ['status'=>false,'message'=>'Failed at verify_market_in_app'];
			}
		}elseif($upgradeFrom == 'Ios'){	
			$sData 			=	$responseData['transactionReceipt'];
			$productId 		= 	!empty($responseData['productId']) ? $responseData['productId'] : '';
			$receiptData 	= 	$this->checkReceipt($sData);		
			$verifyResult 	=   !empty($receiptData) && $receiptData['status'] == 0 ? true : false; 
			if($verifyResult){
				return['status'=>true,'productId'=>$productId];		
			}else {
				return $verifyResult;			
			}
		}else{
            return ['status'=>false,
                    'message'=>'Please select platform.'];
		}
	}
    public function verify_market_in_app($signed_data, $signature, $public_key_base64) {
		$key =	"-----BEGIN PUBLIC KEY-----\n".
		chunk_split($public_key_base64, 64,"\n").
		'-----END PUBLIC KEY-----';
		$key = openssl_get_publickey($key);
		$signature = base64_decode($signature);
		$result = openssl_verify(
				$signed_data,
				$signature,
				$key,
				OPENSSL_ALGO_SHA1);
		if (0 === $result){
			return false;
		}else if (1 !== $result){
			return false;
		}else{
			return true;
		}
	}	
	public function checkReceipt($receiptId){

		$applesharedsecret = "ef5e720fc34547948e684019976994f7";				
		$receiptbytes      =  $receiptId;
		
		
		$appleurl =  "https://sandbox.itunes.apple.com/verifyReceipt"; //for testing with sandbox receipt
		//} else {
			//$appleurl          = "https://buy.itunes.apple.com/verifyReceipt"; // for production
		//}
		$request = json_encode(array("receipt-data" => $receiptbytes,"password"=>$applesharedsecret));
		$ch = curl_init($appleurl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
		$jsonresult = curl_exec($ch);
		$errno    = curl_errno($ch);
		$errmsg   = curl_error($ch);
		curl_close($ch);
		$response = json_decode($jsonresult,true);
		if ($errno != 0) {
			 $response = json_decode(array('message'=>$errmsg,'errorno'=>$errno),true);
			 return ['status'=>false,'message'=>['errors'=>$errmsg]];
		}
		return $response;
	}
    public function actionOptions($id = null)
    {
        return 'ok';
    }
}
