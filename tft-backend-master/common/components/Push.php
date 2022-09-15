<?php
namespace common\components;

use common\models\UserToken;
use common\models\UserData;
use common\models\Notification;
use yii\base\Component;
use Yii;

class Push extends Component {

     public function getUuid($UserId="",$type=""){

          if(empty($type)){
               $Data = UserToken::find()->where(['user_id'=>$UserId])->asArray()->all();
          }else{
               $Data = UserToken::find()->where(['user_id'=>$UserId,'app_type'=>$type])->asArray()->all();
          }
         
          $UUIDS = array();
          if(!empty($Data)){          
              foreach($Data as $uuid){
                  array_push($UUIDS,$uuid['uuid']);
              }
          }
          return $UUIDS;
      }
      
      public function badgeCount($UserId){
        return Notification::find()->where(['user_id'=>$UserId,'is_read'=>'N','is_sent'=>1])->count();;
      }
      public function storeAndSend($items){  
       $d =  Yii::$app->db
        ->createCommand()
        ->batchInsert('notification',
         [
            'user_id', 
            'uuid', 
            'title', 
            'message', 
            'type', 
            'app_type',          
            'is_read', 
            'is_sent',
            'badge_count',
            'from_user_id', 
            'created_at', 
            'created_by',
            'updated_by', 
            'push_request', 
            'push_response'
        ],$items)
        ->execute();
        return $d;
      }
      public function sendPush($Notification){
       
        $uuid = json_decode($Notification->uuid);
        if(count($uuid) > 0){
            $msg = array(
                'message'  =>$Notification->message,
                'title'  => $Notification->title,
                'body'  => $Notification->message,
                "sound"=> "notifsound.mp3",
                'vibrate'=> array(1000, 1000, 1000, 1000, 1000),
                'vibration'  => 1000,
                'badge'      =>    (int)$Notification->badge_count+1,
            );    
            $fields = array
            (
                'registration_ids' => $uuid,
                'notification' => $msg,
                'data'=>array(
                    'title'            =>   $Notification->title,
                    'message'          =>   $Notification->message,
                    'id'               =>   $Notification->id,
                    'type'             =>   $Notification->type,
                    'is_read'          =>   'N',
                    'created_at'       =>   time(),
                    "image"            =>   'www/res/android/48x48.png',
                    'badge'            =>    (int)$Notification->badge_count+1,
                    'user_id'          =>   $Notification->from_user_id,
                ),
                'priority'=> 'high',
                'vibrate'=> array(1000, 1000, 1000, 1000, 1000),
                'vibration'  => 1000,
                'content_available'=> true,
                'show_in_foreground'=>true,
                'badge'      =>   (int)$Notification->badge_count+1,
            );
            $headers = array
            (
                'Authorization: key=' . Yii::$app->setting->val('push_api'),
                'Content-Type: application/json'
            );
            $ch = curl_init("https://fcm.googleapis.com/fcm/send");
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            $res = curl_exec($ch);
            $Notification->push_request  = json_encode($fields);
            $Notification->push_response = $res;
        }else{
            $Notification->push_request  =  'UUID is not available or Notification status off';
            $Notification->push_response  = 'UUID is not available or Notification status off';
        }
        $Notification->is_sent         = 1;
        if($Notification->save()){                  
            return[
                'success'=>true,
            ];
        }else{
            $Notification->validate();
            return[
                'success'=>false,
                'errors'=>$Notification->errors
            ];
        }
      }
      public function send($input){  

          $app_type                      = !empty($input['app_type'])?$input['app_type']:"User";      
          $Notification                  = new Notification;
          $Notification->user_id         = !empty($input['user_id'])?$input['user_id']:"";    
          $Notification->uuid            = json_encode($this->getUuid($Notification->user_id,$app_type)); 
          $Notification->title           = !empty($input['title'])?$input['title']:"";
          $Notification->message         = !empty($input['message'])?$input['message']:"";
          $Notification->type            = !empty($input['type'])?$input['type']:"static";
          $Notification->app_type        = $app_type;
          $Notification->is_sent         = 1;
          $Notification->from_user_id    = !empty($input['from_user_id'])?$input['from_user_id']:""; 
          $Notification->badge_count     = $this->badgeCount($Notification->user_id);   
          if ($Notification->validate()  && $Notification->save()){              
              $user = UserData::find()->joinWith(['userAdditionalInfos'])->where(['user.id'=>$Notification->user_id])->asArray()->one();  
              $notification_status = isset($user['userAdditionalInfos']['notification_status'])?$user['userAdditionalInfos']['notification_status']:1;
                        
              return $this->sendPush($Notification);             
          }else{      
              $Notification->validate();          
              return[
                  'success'=>false,
                  'errors'=>$Notification->errors
              ];
          }
      }
  
}
?>