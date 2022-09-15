<?php

namespace backend\controllers;

use Yii;
use common\models\Winners;
use common\models\WinnersSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use function GuzzleHttp\json_encode;

/**
 * WinnerController implements the CRUD actions for Winners model.
 */
class CronController extends Controller
{
    
    /**
     * Lists all Winners models.
     * @return mixed
     */
    //Cron per day but send email per 4 days
    public function actionIndex()
    {
        $day    =  (int) date('d');
        
         if($day%7==0){    
            $startDateOfmonth = strtotime(date('Y-m-01'));
            $endDateOfmonth   = strtotime(date("Y-m-t", $startDateOfmonth)) + 86400;;
            $userIds = \yii\helpers\ArrayHelper::getColumn(\common\models\Video::find()->select(['user_id'])->where(['video_status'=>1])
                        ->andWhere(['between', 'video.updated_at', $startDateOfmonth, $endDateOfmonth ])
                        ->asArray()->all(),'user_id');

            $userData = \common\models\UserData::find()->where(['IN','id',$userIds])->asArray()->all();
        

            foreach($userData as $ele){
                    Yii::$app->mailer->compose()
                        ->setTo($ele['email'])
                        ->setFrom([\Yii::$app->setting->val('senderEmail') => \Yii::$app->name])
                        ->setSubject(\Yii::$app->name.' : Invite Others to Vote for You')
                        ->setHtmlBody(Yii::$app->emailtemplate->replace_string_email([
                            '{{name}}'=>$ele['username'],
                            '{{app_name}}' => \Yii::$app->name,
                            '{{year}}'=> date('Y'),
                        ] ,"invitation_to_vote_for_me"))->send();

                        $d =   array(
                            'app_type' => 'User',
                            'user_id'  => $ele['id'],
                            'title' => "Invite Others to Vote for You",
                            'message' => "Share your video with more people to get more votes!",
                            'type' => 'static',
                        );
                       
                        \Yii::$app->push->send(
                          $d
                        );
            }
        }        
    }
    public function actionToWinner()
    {
        $day    =  (int) date('d');
        if($day%7==0){ 
            $userIds = \yii\helpers\ArrayHelper::getColumn(\common\models\Winners::find()->select(['user_id'])->asArray()->all(),'user_id');
            $userData = \common\models\UserData::find()->where(['IN','id',$userIds])->asArray()->all();
            foreach($userData as $ele){
                    Yii::$app->mailer->compose()
                        ->setTo($ele['email'])
                        ->setFrom([\Yii::$app->setting->val('senderEmail') => \Yii::$app->name])
                        ->setSubject(\Yii::$app->name.' : Participation Invitation')
                        ->setHtmlBody(Yii::$app->emailtemplate->replace_string_email([
                            '{{name}}'=>$ele['username'],
                            '{{app_name}}' => \Yii::$app->name,
                            '{{year}}'=> date('Y'),
                        ] ,"participation_invitation"))->send();

                        \Yii::$app->push->send(
                            array(
                                'app_type' => 'User',
                                'user_id'  => $ele['id'],
                                'title' => "Participation Invitation",
                                'message' => "Continues Participation will make you a winner.",
                                'type' => 'static',
                            )
                        );
            }
        }
        
    }
   
}
