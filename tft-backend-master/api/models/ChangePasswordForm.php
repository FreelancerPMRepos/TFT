<?php

namespace app\models;

use Yii;
use yii\base\Model;
use lajax\translatemanager\helpers\Language as Lx;

/**
 * User Edit form
 */
class ChangePasswordForm extends Model
{
    public $id;
    public $password;
    public $confirm_password;
    /** @var User */
    private $_user = false;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                'id',
                'exist',
                'targetClass' => '\app\models\User',
                'filter' => [
                    'and',
                    ['status' => User::STATUS_ACTIVE],
                    'blocked_at IS NULL'
                ],
                'message' => 'The ID is not valid.'
            ], 
            [['id','password','confirm_password'], 'required'],
            ['password', 'string', 'min' => 8],
            ['confirm_password', 'compare', 'compareAttribute'=>'password', 'message'=>Lx::t('change-password-model', "Passwords don't match" )]
        ];
    }

    /**
     * Signs user up.
     *
     * @return boolean the saved model or null if saving fails
     */
    public function save()
    {
        if ($this->validate()) {
            $this->getUserByID();
            $this->_user->setPassword($this->password); 
            if ($this->_user->save(false)) {
                return true;
            } else {
                $this->addError('generic', Lx::t('app', 'The system could not update the information.'));
            }
        }
        return false;
    }

    /**
     * Finds user by [[id]]
     *
     * @return User|null
     */
    public function getUserByID()
    {
        if ($this->_user === false) {
            $this->_user = User::findOne($this->id);
        }

        return $this->_user;
    }

}
