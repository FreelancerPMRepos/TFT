<?php

namespace app\models;

use Yii;
use app\models\UserVerificationCode;
use common\models\UserAdditionalInfo;
use common\models\AppsCountries;
use yii\base\Model;
use lajax\translatemanager\helpers\Language as Lx;
use yii\imagine\Image;
/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $photo;
    public $email;
    public $password;
    public $date_of_birth;
    public $city;
    public $country_id;
    public $social_type =  "";
    public $social_provider_id =  "";
    public $user_type;
    /** @var User */
    private $_user = false;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['user_type','required'],
            ['username', 'trim'],
            ['username', 'required'],
            [
                'username',
                'unique',
                'targetClass' => '\app\models\User',
                'message' => Lx::t('model', 'This username has already been taken.')
            ],
            ['username', 'string', 'length' => [4, 25]],
            [
                'username',
                'match',
                'pattern' => '/^[A-Za-z0-9._-]{3,25}$/',
                'message' => Lx::t(
                    'model',
                    'Your username can only contain alphanumeric characters, underscores and dashes.'
                )
            ],
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            [
                'email',
                'unique',
                'targetClass' => '\app\models\User',
                'message' => Lx::t('model', 'This email has already been taken.')
            ],

            ['password', 'required'],
            ['password', 'string', 'min' => 8],

            [['social_type','social_provider_id'],'safe']
                      
        ];
    }
    // public function validateParentEmail($attribute,$params){
    //     $is_under18  = \Yii::$app->general->under18($this->date_of_birth);
    //     if($is_under18){
    //         if(!$this->parent_email){
    //             $this->addError('parent_email','You must have to add your parent email for permission.');
    //         }            
    //     }
    // }
    /**
     * Signs user up.
     *
     * @return boolean the saved model or null if saving fails
     */
    
    public function signup()
    {
        if ($this->validate()) {
            
            $user                       = new User(['scenario' => 'register']);
            $user->social_type          = $this->social_type;
            $user->social_provider_id   = $this->social_provider_id;            
            $user->username             = strtolower($this->username);
            $user->email                = $this->email;
            $user->unconfirmed_email    = $this->email;
            $user->user_type            = $this->user_type;
            // $user->parent_email         = $this->parent_email;
            $user->role                 = $this->user_type=="Trainer"?50:10;
            $user->status               = User::STATUS_PENDING;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            $user->registration_ip = Yii::$app->request->userIP;
            if ($user->save(false)) {  
                $this->_user = $user;
                $UserAdditionalInfo                       = new UserAdditionalInfo;
                $UserAdditionalInfo->user_id              = $user->id; 
                $UserAdditionalInfo->subscription_on      = 1;       
                $UserAdditionalInfo->subscription_start   = time();       
                $UserAdditionalInfo->subscription_end     = strtotime('+1 month');              
                $UserAdditionalInfo->save(false);
                return $this->sendOtp($user);           
            }else{
                $user->validate();
                return array('status'=>false,'message'=>$user->errors);
            }
        }else{
            return array('status'=>false,'message'=>$this->errors);
        }
        
    }
    // public function is_over_fourteen($date_of_birth){
    //     $birthDate = explode("/", $date_of_birth);
    //     $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[1], $birthDate[2], $birthDate[0]))) > date("md")
    //         ? ((date("Y") - $birthDate[0]) - 1)
    //         : (date("Y") - $birthDate[0]));
    //     if($age > 14){
    //         return true;
    //     }else{
    //         return false;
    //     }
    // }
    public function makeOtp($size) {
        $alpha_key = '';
        $keys = range('A', 'Z');
        
        for ($i = 0; $i < 6; $i++) {
            $alpha_key .= $keys[array_rand($keys)];
        }
        
        $length = $size - 2;
        
        // $key = '';
        // $keys = range(0, 9);
        
        // for ($i = 0; $i < $length; $i++) {
        //     $key .= $keys[array_rand($keys)];
        // }
        
        return $alpha_key . $key;
    }
    public function sendOtp($user){
        $UserVerificationCode                   =  UserVerificationCode::find()->where(['user_id'=>$user->id])->one();
        if($UserVerificationCode){
            $UserVerificationCode->expired_at   =  strtotime("+15 minutes", time());
        }else{
            $UserVerificationCode               = new UserVerificationCode;
            $UserVerificationCode->user_id      = $user->id;
            $UserVerificationCode->code         = $this->makeOtp(6);
            $UserVerificationCode->expired_at   = strtotime("+15 minutes", time());
        }
        if($UserVerificationCode->validate() && $UserVerificationCode->save()){
            $string_array = array(
                '{{code}}'=>$UserVerificationCode->code,
            ); 
            $email =  Yii::$app->mailer->compose()
                        ->setTo($user->email)
                        ->setFrom([\Yii::$app->setting->val('senderEmail') => \Yii::$app->name])
                        ->setSubject(\Yii::$app->name.' Registration OTP')
                        ->setHtmlBody(Yii::$app->emailtemplate->replace_string_email([
                            '{{name}}'=>$user->username,
                            '{{code}}'=>$UserVerificationCode->code,
                            '{{app_name}}' => \Yii::$app->name,
                            '{{year}}'=> date('Y')
                        ] ,"verification"))->send(); 
            
            if(YII_ENV_DEV){
                return array('status'=>true,'message'=>'We are in development mode so we can not send otp on email please use this otp = '.$UserVerificationCode->code);
            }else{
                return array('status'=>true,'message'=>'We have just sent you an OTP to complete your account verification process.');
            }  
        }else{
            return array('status'=>false,'message'=>$UserVerificationCode->errors);
        }            
    }

    /**
     * Return User object
     *
     * @return User
     */
    public function getUser()
    {
        return $this->_user;
    }

}
