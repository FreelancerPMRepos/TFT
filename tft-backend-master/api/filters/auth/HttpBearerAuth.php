<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\filters\auth;

use Yii;
/**
 * HttpBearerAuth is an action filter that supports the authentication method based on HTTP Bearer token.
 *
 * You may use HttpBearerAuth by attaching it as a behavior to a controller or module, like the following:
 *
 * ```php
 * public function behaviors()
 * {
 *     return [
 *         'bearerAuth' => [
 *             'class' => \yii\filters\auth\HttpBearerAuth::className(),
 *         ],
 *     ];
 * }
 * ```
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class HttpBearerAuth extends \yii\filters\auth\AuthMethod
{
    
    /**
     * @var string the HTTP authentication realm
     */
    public $realm = 'api';

    /**
     * @inheritdoc
     */
    public function authenticate($user, $request, $response)
    {
      
        $authHeader = $request->getHeaders()->get('Authorization');
        if ($authHeader == null && isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION']) && $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] != '') {
            $authHeader = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
        }
        if ($authHeader !== null && preg_match('/^Bearer\s+(.*?)$/', $authHeader, $matches)) {
            Yii::debug('authenticate matches => ' . print_r($matches, true));
            $identity = $user->loginByAccessToken($matches[1], get_class($this));
            
            if ($identity === null) {
                $this->handleFailure($response);
            }
            /// not subscriber
            if($identity->user_type == "User" && Yii::$app->controller->id != "payment"
            && Yii::$app->controller->action->id!="logout"){
                if($identity->userAdditionalInfos->subscription_on == 0){
                    $userTrainee = \common\models\UserTrainee::find()->where(['trainee_id'=>$identity->userAdditionalInfos->user_id])->one();
                    if($userTrainee){
                        throw new \yii\web\HttpException(404, 'Expired',21);
                    }else{
                        throw new \yii\web\HttpException(404, 'Expired',20);
                    }
                }
            }
            return $identity;
        }   
        return null;
    }

    /**
     * @inheritdoc
     */
    public function challenge($response)
    {
        $response->getHeaders()->set('WWW-Authenticate', "Bearer realm=\"{$this->realm}\"");
    }
}
