<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use yii\data\ArrayDataProvider;

use common\models\SSSTR;
use common\models\SSGST;
use common\models\POSTPRSTSST;

class StrengthRoutinesController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [                    
                    [
                        'actions' => ['ssgst','ssstr','post','prst','sst'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }
    public function actionSsstr()
    {
        $url = 'https://'.$_SERVER['HTTP_HOST'].Yii::$app->urlManagerFrontend->createUrl(['/v1/routine/for-admin-panel-sstr-workout-plan']);

        $PathwayTitle = "SSSTR";
        $model = new SSSTR();
        if($model->load(Yii::$app->request->post()))
        {
            $array = array(
                'SSSTRForm[user_selected_season]' => $model->user_selected_season,
                'SSSTRForm[user_selected_sport_id]' => $model->user_selected_sport_id,
                'SSSTRForm[routine_day_and_time][0][day]' => '1',
                'SSSTRForm[routine_day_and_time][0][time]' => '11:00 AM',
                'SSSTRForm[routine_day_and_time][1][day]' => '2',
                'SSSTRForm[routine_day_and_time][1][time]' => '11:00 AM',
                'SSSTRForm[routine_day_and_time][2][day]' => '3',
                'SSSTRForm[routine_day_and_time][2][time]' => '11:00 AM'
            );

            $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $array,
            ));

            $response = json_decode(curl_exec($curl), true);
            curl_close($curl);
           
            return $this->render('view',[
                'provider' => $response['data'],
                'PathwayTitle' => $PathwayTitle,
            ]);
        }

        return $this->render('index',[
            'model' => $model,
            'PathwayTitle' => $PathwayTitle,
        ]);
    }
    public function actionSsgst()
    {
        $url = 'https://'.$_SERVER['HTTP_HOST'].Yii::$app->urlManagerFrontend->createUrl(['/v1/routine/for-admin-panel-ssgst-workout-plan']);

        $model = new SSGST();
        $PathwayTitle = "SSGST";
        if($model->load(Yii::$app->request->post()))
        {
            $curlArray = $this->createArray($model->user_selected_sport_id,$model->how_many_day_per_week);
            $curl = curl_init();

            curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $curlArray,
            ));

            $response = json_decode(curl_exec($curl), true);
            curl_close($curl);
            return $this->render('view',[
                'provider' => $response['data'],
                'PathwayTitle' => $PathwayTitle,
            ]);
        }

        return $this->render('index',[
            'model' => $model,
            'PathwayTitle' => $PathwayTitle,
        ]);
    }
    public function actionPost()
    {
        $url = 'https://'.$_SERVER['HTTP_HOST'].Yii::$app->urlManagerFrontend->createUrl(['/v1/routine/for-admin-panel-routine-plan']);

       
        $PathwayTitle = "PoST";
        $model = new POSTPRSTSST();
        if($model->load(Yii::$app->request->post()))
        {
            $curlArray = $this->postprstsstArray($model->how_many_day_per_week,$PathwayTitle);
            $curl = curl_init();

            curl_setopt_array($curl, array(
            CURLOPT_URL =>$url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $curlArray,
            ));

            $response = json_decode(curl_exec($curl), true);
            curl_close($curl);

            return $this->render('view',[
                'provider' => $response['data'],
                'PathwayTitle' => $PathwayTitle,
            ]);
        }

        return $this->render('index',[
            'model' => $model,
            'PathwayTitle' => $PathwayTitle,

        ]);
    }
    public function actionPrst()
    {
        $PathwayTitle = "PrST";
        $url = 'https://'.$_SERVER['HTTP_HOST'].Yii::$app->urlManagerFrontend->createUrl(['/v1/routine/for-admin-panel-routine-plan']);

        $model = new POSTPRSTSST();
        if($model->load(Yii::$app->request->post()))
        {

            $curlArray = $this->postprstsstArray($model->how_many_day_per_week,$PathwayTitle);
            $curl = curl_init();

            curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $curlArray,
            ));

            $response = json_decode(curl_exec($curl), true);
            curl_close($curl);
            return $this->render('view',[
                'provider' => $response['data'],
                'PathwayTitle' => $PathwayTitle,
            ]);
        }
        return $this->render('index',[
            'model' => $model,
            'PathwayTitle' => $PathwayTitle,
        ]);
    }
    public function actionSst()
    {
        $url = 'https://'.$_SERVER['HTTP_HOST'].Yii::$app->urlManagerFrontend->createUrl(['/v1/routine/for-admin-panel-routine-plan']);
        $PathwayTitle = "SST";
        $model = new POSTPRSTSST();
        if($model->load(Yii::$app->request->post()))
        {
            $curlArray = $this->postprstsstArray($model->how_many_day_per_week,$PathwayTitle);
            $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $curlArray,
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            if ($err) {
              echo "cURL Error #:" . $err;die;
            } else {
                $response = json_decode(curl_exec($curl), true);
            }
            curl_close($curl);
            return $this->render('view',[
                'provider' => $response['data'],
                'PathwayTitle' => $PathwayTitle,
            ]);
        }
        return $this->render('index',[
            'model' => $model,
            'PathwayTitle' => $PathwayTitle,
        ]);
    }
    protected function createArray($sportID,$day)
    {
        if($day == 2){
            $array = array(
                'SSGSTForm[user_selected_sport_id]' => $sportID,
                'SSGSTForm[how_many_day_per_week]' => $day,
                'SSGSTForm[routine_day_and_time][0][day]' => '1',
                'SSGSTForm[routine_day_and_time][0][time]' => "11:00 AM",
                'SSGSTForm[routine_day_and_time][1][day]' => '2',
                'SSGSTForm[routine_day_and_time][1][time]' => "11:00 AM"
            );
        }
        if($day == 3){
            $array = array(
                'SSGSTForm[user_selected_sport_id]' => $sportID,
                'SSGSTForm[how_many_day_per_week]' => $day,
                'SSGSTForm[routine_day_and_time][0][day]' => '1',
                'SSGSTForm[routine_day_and_time][0][time]' => "11:00 AM",
                'SSGSTForm[routine_day_and_time][1][day]' => '2',
                'SSGSTForm[routine_day_and_time][1][time]' => "11:00 AM",
                'SSGSTForm[routine_day_and_time][2][day]' => '3',
                'SSGSTForm[routine_day_and_time][2][time]' => "11:00 AM"
            );
        }
        if($day == 4){
            $array = array(
                'SSGSTForm[user_selected_sport_id]' => $sportID,
                'SSGSTForm[how_many_day_per_week]' => $day,
                'SSGSTForm[routine_day_and_time][0][day]' => '1',
                'SSGSTForm[routine_day_and_time][0][time]' => "11:00 AM",
                'SSGSTForm[routine_day_and_time][1][day]' => '2',
                'SSGSTForm[routine_day_and_time][1][time]' => "11:00 AM",
                'SSGSTForm[routine_day_and_time][2][day]' => '3',
                'SSGSTForm[routine_day_and_time][2][time]' => "11:00 AM",
                'SSGSTForm[routine_day_and_time][3][day]' => '4',
                'SSGSTForm[routine_day_and_time][3][time]' => "11:00 AM"
            );
        }
        if($day == 5){
            $array = array(
                'SSGSTForm[user_selected_sport_id]' => $sportID,
                'SSGSTForm[how_many_day_per_week]' => $day,
                'SSGSTForm[routine_day_and_time][0][day]' => '1',
                'SSGSTForm[routine_day_and_time][0][time]' => "11:00 AM",
                'SSGSTForm[routine_day_and_time][1][day]' => '2',
                'SSGSTForm[routine_day_and_time][1][time]' => "11:00 AM",
                'SSGSTForm[routine_day_and_time][2][day]' => '3',
                'SSGSTForm[routine_day_and_time][2][time]' => "11:00 AM",
                'SSGSTForm[routine_day_and_time][3][day]' => '4',
                'SSGSTForm[routine_day_and_time][3][time]' => "11:00 AM",
                'SSGSTForm[routine_day_and_time][4][day]' => '5',
                'SSGSTForm[routine_day_and_time][4][time]' => "11:00 AM"

            );

        }
        return $array;
    }
    protected function postprstsstArray($day,$pathway)
    {
        if($day == 2){
            $array = array(
                'RoutineForm[pathway]' => $pathway,
                'RoutineForm[how_many_day_per_week]' => $day,
                'RoutineForm[routine_day_and_time][0][day]' => '1',
                'RoutineForm[routine_day_and_time][0][time]' => "11:00 AM",
                'RoutineForm[routine_day_and_time][1][day]' => '2',
                'RoutineForm[routine_day_and_time][1][time]' => "11:00 AM"
            );
        }
        if($day == 3){
            $array = array(
                'RoutineForm[pathway]' => $pathway,
                'RoutineForm[how_many_day_per_week]' => $day,
                'RoutineForm[routine_day_and_time][0][day]' => '1',
                'RoutineForm[routine_day_and_time][0][time]' => "11:00 AM",
                'RoutineForm[routine_day_and_time][1][day]' => '2',
                'RoutineForm[routine_day_and_time][1][time]' => "11:00 AM",
                'RoutineForm[routine_day_and_time][2][day]' => '3',
                'RoutineForm[routine_day_and_time][2][time]' => "11:00 AM"
            );
        }
        if($day == 4){
            $array = array(
                'RoutineForm[pathway]' => $pathway,
                'RoutineForm[how_many_day_per_week]' => $day,
                'RoutineForm[routine_day_and_time][0][day]' => '1',
                'RoutineForm[routine_day_and_time][0][time]' => "11:00 AM",
                'RoutineForm[routine_day_and_time][1][day]' => '2',
                'RoutineForm[routine_day_and_time][1][time]' => "11:00 AM",
                'RoutineForm[routine_day_and_time][2][day]' => '3',
                'RoutineForm[routine_day_and_time][2][time]' => "11:00 AM",
                'RoutineForm[routine_day_and_time][3][day]' => '4',
                'RoutineForm[routine_day_and_time][3][time]' => "11:00 AM"
            );
        }
        if($day == 5){
            $array = array(
                'RoutineForm[pathway]' => $pathway,
                'RoutineForm[how_many_day_per_week]' => $day,
                'RoutineForm[routine_day_and_time][0][day]' => '1',
                'RoutineForm[routine_day_and_time][0][time]' => "11:00 AM",
                'RoutineForm[routine_day_and_time][1][day]' => '2',
                'RoutineForm[routine_day_and_time][1][time]' => "11:00 AM",
                'RoutineForm[routine_day_and_time][2][day]' => '3',
                'RoutineForm[routine_day_and_time][2][time]' => "11:00 AM",
                'RoutineForm[routine_day_and_time][3][day]' => '4',
                'RoutineForm[routine_day_and_time][3][time]' => "11:00 AM",
                'RoutineForm[routine_day_and_time][4][day]' => '5',
                'RoutineForm[routine_day_and_time][4][time]' => "11:00 AM"

            );

        }
        return $array;
    }
}