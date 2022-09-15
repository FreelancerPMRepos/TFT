<?php 
namespace common\models;

use Yii;
use yii\base\Model;
use common\models\User;

class PasswordForm extends Model{
    public $newpass;
    public $repeatnewpass;
    
    public function rules(){
        return [
            [['newpass','repeatnewpass'],'required'],
            [['newpass','repeatnewpass'],'string', 'min' => 6],
            ['repeatnewpass','compare','compareAttribute'=>'newpass'],
        ];
    }

    
    public function validatePassword($password, $password_hash) {
        return Yii::$app->security->validatePassword($password, $password_hash);
    }

    
    public function attributeLabels(){
        return [
            'newpass'=>'New Password',
            'repeatnewpass'=>'Repeat New Password',
        ];
    }
}