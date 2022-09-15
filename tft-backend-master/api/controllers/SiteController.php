<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;

use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\SignupForm;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\ResendVerificationEmailForm;
use app\models\PasswordResetRequestForm;
use app\models\PasswordResetForm;
use yii\helpers\Url;

use lajax\translatemanager\helpers\Language as Lx;


class SiteController extends Controller
{
   
        public $layout ="main";
        public function behaviors()
        {
            return [
                'access' => [
                    'class' => AccessControl::className(),
                    'only' => ['logout', 'signup'],
                    'rules' => [
                        [
                            'actions' => ['signup'],
                            'allow' => true,
                            'roles' => ['?'],
                        ],
                        [
                            'actions' => ['logout'],
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                        [
                            'actions' => ['change-language'],
                            'allow' => true,
                            'roles' => ['?'],
                        ],
                        [
                            'actions' => ['success','pay'],
                            'allow' => true,
                            'roles' => ['*'],
                        ],
                        
                    ],
                ],
            ];
        }
    
        public function actionError()
        {
            $this->context->layout = 'error-layout';
            return [
                'error' => [
                    'class' => 'yii\web\ErrorAction',
                ],
                'captcha' => [
                    'class' => 'yii\captcha\CaptchaAction',
                    'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                ],
            ];
            // $response = new Response();
            // $response->statusCode = 400;
            // $response->data = json_encode(
            //     [
            //         'name' => 'Bad Request',
            //         'message' => Yii::t('app', 'The system could not process your request. Please check and try again.'),
            //         'code' => 0,
            //         'status' => 400,
            //         'type' => 'yii\\web\\BadRequestHttpException'
            //     ]
            // );

            // return $response;
        }
        /**
         * {@inheritdoc}
         */
        public function actions()
        {
            return [
                'error' => [
                    'class' => 'yii\web\ErrorAction',
                ],
                'captcha' => [
                    'class' => 'yii\captcha\CaptchaAction',
                    'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                ],
            ];
        } 
        public function actionLogin()
        {
            if (!Yii::$app->user->isGuest) {
                return $this->redirect(['client/index']);
            }
    
            $model = new LoginForm();
            if ($model->load(Yii::$app->request->post()) && $model->login()) {
                if(!empty(\Yii::$app->user->identity->id) && \Yii::$app->user->identity->user_type != "Trainer"){
                    return $this->redirect(['site/logout']);
                }
                return $this->redirect(['client/index']);
            } else {
                $model->password = '';
    
                return $this->render('login', [
                    'model' => $model,
                ]);
            }
        }
    
        /**
         * Logs out the current user.
         *
         * @return mixed
         */
        public function actionLogout()
        {
            Yii::$app->user->logout();
            return $this->redirect(['site/login']);
        }
        public function actionSuccess($id){
              $this->layout = false;
              $msg = "Unknown Error";
              $stripe = new \Stripe\StripeClient(
                Yii::$app->setting->val('stripe_secrete_key')
              );
              $token     =  !empty($_GET['stripeToken'])?$_GET['stripeToken']:"";
              $userEmail =  !empty($_GET['stripeEmail'])?$_GET['stripeEmail']:"";
              $user      = \common\models\User::find()->where(['email'=>$userEmail])->one();
              $sport     = \common\models\Sports::find()->where(['id'=>$id])->one();
              $ssstr_product_price = \Yii::$app->setting->val('ssstr_product_price');
              if($token && $ssstr_product_price > 0 && $user && $sport){

                $sportName = $sport->name;
                $user_id   =  $user->id;
                
                $workout_json = \Yii::$app->general->sstrPlan($id,'In','Regular');
                $routine                          =  new \common\models\Routines;
                $routine->pathway_id              =  5;
                $routine->day                     =  '3';
                $routine->time_between_last_sets  =  240;
                $routine->sport_id                =  $id;
                $routine->season                  =  "In";
                $routine->mode                    =  1;
                $routine->user_id                 =  $user_id;
                $routine->routine_weight_unit     =  'kg';
                $routine->routine_workout_list    =  $workout_json;   
                if($routine->save()){
                   for($i=0;$i<$routine->day;$i++) {
                        $routine_time               = new \common\models\RoutineTime;
                        $routine_time->routine_id   = $routine->id;
                        $routine_time->day_no       = $i+1;
                        $routine_time->day_time     = "6:00 AM";
                        $routine_time->save(false);
                    } 
                    try{
                        $d = $stripe->charges->create([
                          'amount' => $ssstr_product_price*100,
                          'currency' => 'USD',
                          'source' => $token,
                          'description' => 'Buying SSSTR Routine Program For '.$sportName,
                        ]);
                        if($d && !empty($d->id)){
                          $UserSport = \common\models\UserSport::find()->where(['user_id'=>$user_id,'sport_id'=>$id])->one();
                          $UserSport = $UserSport?$UserSport:new \common\models\UserSport;
                          $UserSport->user_id  = $user_id;
                          $UserSport->sport_id = $id;
                          $UserSport->response = json_encode($d);
                          if($UserSport->save()){

                            $html =  Yii::$app->emailtemplate->replace_string_email([
                                '{{name}}'=>$userEmail,
                                '{{message}}'=>'Buying SSSTR Routine Program For '.$sportName,
                            ] ,"thank_you_for_business");

                            $email =  Yii::$app->mailer->compose()
                            ->setTo($userEmail)
                            ->setFrom([\Yii::$app->setting->val('senderEmail') => \Yii::$app->name])
                            ->setSubject('TFT - Thanks for your business.')
                            ->setHtmlBody($html)->send();

                              return $this->redirect($d->receipt_url);
                          }else{
                              $msg = json_encode($UserSport->errors);
                          }
                        }else{
                          $msg = json_encode($d);
                        }
                    } catch(\Exception $e) {
                      $msg = $e->getMessage();
                    }
                } else{
                    $msg = json_encode($routine->errors);
                }
                  
              }else{
                $msg = "Token or Sport or User or SSSTR Price object not found";
              }
              return $this->render('failed',['msg'=>$msg]);
        } 
        public function actionPay($email){  
            $this->layout= false;
            $User = \common\models\User::find()->joinWith(['userAdditionalInfos'])
            ->where(['email'=>$email])->asArray()->one();
            if($User && !empty($User['userAdditionalInfos']['sports_interest'])){
                $sports =  json_decode($User['userAdditionalInfos']['sports_interest'],true);
                $price  =  Yii::$app->setting->val('ssstr_product_price')*100;
                $html   = '<table style="border-collapse: collapse;width: 100%;border-color: #eaeaea;" border="1">';
                $sportList = \common\models\Sports::find()->where(1)->asArray()->all();
                $sportList = \yii\helpers\ArrayHelper::map($sportList,'id','name');

                $usersSSTR = \yii\helpers\ArrayHelper::getColumn(\common\models\UserSport::find()
                ->select(['sport_id'])
                ->asArray()->where(['user_id'=>$User['userAdditionalInfos']['user_id']])->all(),'sport_id');
                foreach($sports as $sportId){
                    // if(in_array($sportId,$usersSSTR)){
                    //     continue;
                    // }
                    $Name =  !empty($sportList[$sportId])?$sportList[$sportId]:"Unknown";
                    $form = '<form action="'.\yii\helpers\Url::toRoute(['/success/'.$sportId],true).'" method="GET">
                                <script
                                    src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                                    data-key="'.Yii::$app->setting->val('stripe_public_key').'"
                                    data-amount="'.$price.'"
                                    data-email="'.$email.'"
                                    data-name=""
                                    data-description=""
                                    data-image="https://targetedfitnesstraining.com/img_assets/logo.png"
                                    data-locale="auto"
                                    data-currency="USD">
                                </script>
                            </form>';
                    $html .= "<td style='width: 70%;text-align:center'>
                    <strong>Buy ".$Name." SSSTR Routine</strong></td>
                    <td style='width: 30%;text-align:center'><br>".$form."<br></td></tr>";
                }
                $html .= "</tbody></table>";
                echo Yii::$app->emailtemplate->replace_string_email([
                    '{{name}}'=>'Sagar',
                    '{{stripe_form}}'=>$html,
                ] ,"email_template_for_admin_only");die;
            }else{
               echo '<h1>This email "'.$email.'" is not registered with us or no sport interest.</h1>';die;
            }
        } 
        public function actionIndex(){  
            return $this->redirect(['site/login']);
        } 
    }
