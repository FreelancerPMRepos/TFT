<?php
namespace console\controllers;
use yii\console\Controller;
use Yii;
class CronController extends Controller
{

    public function behaviors()
    {
        return [
            'cronLogger' => [
                'class' => 'yii2mod\cron\behaviors\CronLoggerBehavior',
                'actions' => ['*']
            ],
            // Example of usage the `MutexConsoleCommandBehavior`
            'mutexBehavior' => [
               'class' => 'yii2mod\cron\behaviors\MutexConsoleCommandBehavior',
               'mutexActions' => ['*']
            ]
        ];
    } 
    function deleteOld($path)
    {
        $dir = opendir($path);
        while($file = readdir($dir))
        {
            if($file != '..' && $file != '.')
            {    
                $time = filectime($path . $file);  
                $age  = time()-$time;         
                if($age > (86400 * 10)){
                    if(file_exists($path . $file)){
                        unlink($path . $file);
                    }                    
                }   
            }
        }
        closedir($dir);
        return 1;
    }
    //########## command : php yii cron/get-database
    public function actionGetDatabase(){       

        $url = $_SERVER['REQUEST_SCHEME'].'/'.$_SERVER['HTTP_HOST'].'/administration/backuprestore/default/create';
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_POSTFIELDS => "------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"config_id\"\r\n\r\nmain_storage\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW--",
        CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW",
            "postman-token: 1c935aec-00ab-e14c-e853-5dcce98c356e"
        ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $dir = Yii::$app->basePath.'/../backend/_backup/';;
            if(is_dir($dir)){
                $files =  $this->deleteOld($dir);
            }
        }
    }
    //########## command : php yii cron/delete-verification-code
    public function actionDeleteVerificationCode()
    {
       $time_ago = strtotime("-1 day");
       $sql = 'DELETE FROM user_verification_code WHERE expired_at < '.$time_ago;
       \Yii::$app->db->createCommand($sql)->execute();

       $sql = "DELETE FROM `cron_schedule` WHERE dateCreated <= DATE(NOW()) - INTERVAL 4 DAY";
        \Yii::$app->db->createCommand($sql)->execute();
 
        $sql="DELETE FROM `log` WHERE `log_time`< UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 15 DAY))";
        \Yii::$app->db->createCommand($sql)->execute();
    }
    //########## command : php yii cron/remove-log -  Per day one time
    public function actionRemoveLog(){

        $sql = "DELETE FROM `cron_schedule` WHERE dateCreated <= DATE(NOW()) - INTERVAL 4 DAY";
        \Yii::$app->db->createCommand($sql)->execute();

        $sql = "DELETE FROM `cron_schedule` WHERE dateCreated <= DATE(NOW()) - INTERVAL 1 DAY AND jobCode = 'cron/send-notification'";
        \Yii::$app->db->createCommand($sql)->execute();
 
        $sql="DELETE FROM `log` WHERE `log_time`< UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 15 DAY))";
        \Yii::$app->db->createCommand($sql)->execute();
    }  
    //########## command : php yii cron/send-notification -  Per 1 Min 
    public function actionRemoveOldNotification(){
        $time_ago = strtotime("-120 day");
        $sql = 'DELETE FROM notification WHERE is_read ="Y" AND is_sent = 1 AND created_at < '.$time_ago;
        \Yii::$app->db->createCommand($sql)->execute();
    }
      //########## command : php yii cron/check-subscription -  Per 1 hour 
    public function actionCheckSubscription(){
        $url =  $_SERVER['REQUEST_SCHEME'].'/'.$_SERVER['SERVER_NAME'].'/v1/payment/cron-for-subscription';
        // $url =  \yii\helpers\Url::toRoute('/v1/payment/cron-for-subscription', 'https');
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "postman-token: 981024c1-f78c-41ee-cc37-86743ffeda42"
        ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo $response;
        }
    }
    public function actionUpsell(){
        $url =  \yii\helpers\Url::toRoute(['/v1/payment/up-sell'],true);
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
        ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo $response;
        }
    }
    public function actionSendReminder(){
        $url =  \yii\helpers\Url::toRoute(['/v1/payment/send-reminder'],true);
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
        ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo $response;
        }
    }

}
