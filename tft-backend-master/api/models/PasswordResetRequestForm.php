<?php
namespace app\models;
use Yii;
use yii\helpers\Url;
use yii\base\Model;
use lajax\translatemanager\helpers\Language as Lx;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public $email;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            [
                'email',
                'exist',
                'targetClass' => '\app\models\User',
                'filter' => ['status' => User::STATUS_ACTIVE],
                'message' => Lx::t('app', 'There is no user with this email address.')
            ],
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return bool whether the email was send
     */
    public function sendPasswordResetEmail()
    {
        /* @var $user User */
        $user = User::findOne([
            'status' => User::STATUS_ACTIVE,
            'email' => $this->email,
        ]);

        if (!$user) {
            return false;
        }
        if($user->blocked_at > 0){
            throw new \yii\web\NotFoundHttpException(Lx::t('app', 'You have no access anymore to forgot password.'));
        }
        // if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
        //     $user->generatePasswordResetToken();
        //     if (!$user->save(false)) {
        //         return false;
        //     }
        // }
        // $resetURL = Url::to(['/site/password-reset','token'=>$user->password_reset_token], $schema = true);     
        $password = rand(11111111,99999999);
        $user->password_hash = Yii::$app->security->generatePasswordHash($password);
        if (!$user->save(false)) {
            return false;
        }  
        $email =  Yii::$app->mailer->compose()
                ->setTo($user->email)
                ->setFrom([\Yii::$app->setting->val('senderEmail') => \Yii::$app->name])
                ->setSubject('Your temporary password.')
                ->setHtmlBody(Yii::$app->emailtemplate->replace_string_email([
                    '{{name}}'=>$user->username,
                    '{{password}}'=>$password,
                ] ,"password-reset"))->send();

            return array('status'=>true,'data'=>$email);

    }
    public function sendEmail()
    {
        /* @var $user User */
        $user = User::findOne([
            'status' => User::STATUS_ACTIVE,
            'email' => $this->email,
        ]);

        if (!$user) {
            return false;
        }
        
        if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->generatePasswordResetToken();
            if (!$user->save()) {
                return false;
            }
        }

        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Password reset for ' . Yii::$app->name)
            ->send();
    }
}
