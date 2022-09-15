<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;

use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use lajax\translatemanager\helpers\Language as Lx;


class ClientController extends Controller
{
   
        public $layout ="main";
        public function behaviors()
        {
            return [
                'access' => [
                    'class' => AccessControl::className(),
                    'only' => ['index','subscribe','cancel-subscription','discontinue'],
                    'rules' => [
                        [
                            'actions' => ['index','subscribe','cancel-subscription','discontinue'],
                            'allow' => true,
                            'roles' => ['trainer'],
                        ]
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'logout' => ['post'],
                    ],
                ],
            ];
        }
        public function actionDiscontinue($trainee_id){
            $UserTrainee  = \common\models\UserTrainee::find()->joinWith(['traineeDetail'])
            ->where(['trainee_id'=>$trainee_id,'trainer_id'=>\Yii::$app->user->id])->one();
            if($UserTrainee){
                $trainee =  $UserTrainee->traineeDetail;
                if($UserTrainee->delete()){
                    $html =  Yii::$app->emailtemplate->replace_string_email([
                        '{{name}}'=>$trainee->email,
                    ] ,"discontinue_trainee");
                    $email =  Yii::$app->mailer->compose()
                    ->setTo($trainee->email)
                    ->setFrom([\Yii::$app->setting->val('senderEmail') => \Yii::$app->name])
                    ->setSubject('TFT - Your account has been discontinue with trainer.')
                    ->setHtmlBody($html)->send();
                }
            }
            return $this->redirect(['client/index']);
        }
        public function actionCancelSubscription($trainee_id){
            $UserTrainee  = \common\models\UserTrainee::find()->joinWith(['traineeAdditionalDetail'])
            ->where(['trainee_id'=>$trainee_id,'trainer_id'=>\Yii::$app->user->id])->one();
            if(!empty($UserTrainee->traineeAdditionalDetail->stripe_subscription_id)){
                $d =  \Yii::$app->stripePayment->cancelSubscription($UserTrainee->traineeAdditionalDetail->stripe_subscription_id);
                if($d){
                    $user                           = $UserTrainee->traineeAdditionalDetail;
                    $user->subscription_type        = "";
                    $user->subscription_start       = 0;
                    $user->subscription_end         = 0;
                    $user->stripe_subscription_id   = "";
                    $user->subscription_on          = 0;
                    $user->subscription_json        = "";
                    if($user->save(false)){
                        return $this->redirect(['client/index']);
                    }else{
                        throw new \yii\web\HttpException(404, json_encode($user->errors));
                    }
                }
            }else{
                throw new \yii\web\HttpException(404, 'Invalid trainee or trainee is not under this trainer.');
            }
        }
        public function actionSubscribe($trainee_id){
            $loginUser      =  \Yii::$app->user->identity;
            $trainee        =  \common\models\UserAdditionalInfo::find()->joinWith(['user'])
            ->where(['user_id'=>$trainee_id])->one();
            if(!empty($loginUser->userAdditionalInfos->stripe_customer_id) && $trainee){ 
                $token         =  !empty($_GET['stripeToken'])?$_GET['stripeToken']:"";
                $customer_id   = \Yii::$app->stripePayment->updateCustomerCard($loginUser->userAdditionalInfos->stripe_customer_id,$token); 
                $subscription  = \Yii::$app->stripePayment->createSubscription(
                    $customer_id,
                    Yii::$app->setting->val('stripe_trainee_subscription_plan_id'),
                    ['Trainee Username'=>$trainee->user->username,'Trainee Email'=>$trainee->user->email]
                );
                if($subscription && !empty($subscription->id)){ 
                    $trainee->subscription_start     = $subscription->current_period_start;
                    $trainee->subscription_end       = $subscription->current_period_end;
                    $trainee->subscription_on        = 1;
                    $trainee->subscription_type      = "Stripe";
                    $trainee->subscription_json      = json_encode($subscription);
                    $trainee->stripe_subscription_id = $subscription->id;
                    if($trainee->save()){
                        return $this->redirect(['client/index']);
                    }else{
                        throw new \yii\web\HttpException(404, json_encode($trainee->errors));
                    }
                }else{
                    throw new \yii\web\HttpException(404, 'Subscription has been failed.');
                }
            }else{
                throw new \yii\web\HttpException(404, 'Invalid trainee');
            }
        }
        public function actionIndex(){  
            $trainee_list  = \common\models\UserTrainee::find()->joinWith([
                'traineeDetail',
                'traineeAdditionalDetail',
            ])->where(['user_trainee.trainer_id'=>\Yii::$app->user->id])->orderBy('user.username ASC')->all();
            $t = [];
            foreach($trainee_list as $k=>$trainee){
                $t[$k]['trainee_id'] = $trainee->traineeDetail->id;
                $t[$k]['username'] =   $trainee->traineeDetail->username;
                $t[$k]['email'] =   $trainee->traineeDetail->email;
                $t[$k]['license'] =  !empty( $trainee->traineeLicenseDetail)? $trainee->traineeLicenseDetail->license:"";
                $t[$k]['traineeAdditionalDetail']= $trainee->traineeAdditionalDetail;
                $t[$k]['photo']= !empty($trainee->traineeDetail->photo)
                ?\yii\helpers\Url::base(true).'/img_assets/users/'.$trainee->traineeAdditionalDetail->photo:\yii\helpers\Url::base(true).'/img_assets/users/default.png';
            }  
            return $this->render('index', [
                'data'=>$t,
            ]);
        } 
}
