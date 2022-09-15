<?php

namespace app\models;

use Yii;
use app\models\UserVerificationCode;
use yii\base\Model;
use lajax\translatemanager\helpers\Language as Lx;
/**
 * ResendOTPForm form
 */
class ResendOTPForm extends Model
{
    public $username;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
        ];
    }

}
