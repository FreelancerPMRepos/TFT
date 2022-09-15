<?php

namespace app\models;

use Yii;
use yii\base\Model;
use lajax\translatemanager\helpers\Language as Lx;

/**
 * User Edit form
 */
class UserEditForm extends Model
{
    public $username;
    public $email;
    public $first_name;
    public $last_name;
    public $phone;
    public $business;
    public $business_address;
    public $business_phone;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [           
            ['username', 'trim'],
            [['username','first_name','last_name','phone'], 'required'],
            ['username','usernameValidation'],            
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
            ['email','emailValidation'],
            [['first_name','last_name','phone','business','business_address','business_phone'], 'string', 'max' => 255],
        ];
    }
    public function usernameValidation(){
        if($this->username != \Yii::$app->user->identity->username){
            $User= User::find()->where(['username'=>$this->username])->one();
            if($User){
                $this->addError('username','This username is already been taken.');
            }
        }
    }
    public function emailValidation(){
        if($this->email != \Yii::$app->user->identity->email){
            $User= User::find()->where(['email'=>$this->email])->one();
            if($User){
                $this->addError('email','This email is already been taken.');
            }
        }
    }
}
