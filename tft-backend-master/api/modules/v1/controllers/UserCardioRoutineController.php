<?php
namespace app\modules\v1\controllers;
use app\filters\auth\HttpBearerAuth;

use Yii;
use common\models\UserCardioRoutineWeeks;
use yii\filters\AccessControl;
use yii\filters\auth\CompositeAuth;
use yii\rest\ActiveController;

class UserCardioRoutineController extends ActiveController
{
    public $modelClass = 'common\models\UserCardioRoutine';

    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);
    }

    public function actions()
    {
        return [];
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => CompositeAuth::className(),
            'authMethods' => [
                HttpBearerAuth::className(),
            ],

        ];

        $behaviors['verbs'] = [
            'class' => \yii\filters\VerbFilter::className(),
            'actions' => [
                'create' => ['post'],
                'create-cardio-weeks' => ['post'],
            ],
        ];

        // remove authentication filter
        $auth = $behaviors['authenticator'];
        unset($behaviors['authenticator']);

        // add CORS filter
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
            ],
        ];

        // re-add authentication filter
        $behaviors['authenticator'] = $auth;
        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        $behaviors['authenticator']['except'] = [
           // '',
        ];
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'only' => ['create','delete-routine','create-cardio-weeks','cardio-workout-list','save-cardio-workout','add-exe','log-workout'], //only be applied to
            'rules' => [
               
                [
                    'allow' => true,
                    'actions' => ['create','delete-routine','create-cardio-weeks','cardio-workout-list','save-cardio-workout','add-exe','log-workout'],
                    'roles' => ['user'],
                ],
            ],
        ];
        return $behaviors;
    }
    public function actionLogWorkout(){
       $model =  new \common\models\UserCardioRoutineLog;
       if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $extra_attributes        =  json_decode($_POST['UserCardioRoutineLog']['extra_attributes'],true);
            $model->status           =   "Completed";
            $model->is_close         =   1;
            $model->extra_attributes =   json_encode($extra_attributes);
            $model->save();
            return [
                'status'=>true
            ];
       }else{
            return [
                'status'=>false,
                'message'=>\Yii::$app->general->error($model->errors)
            ];
       }
    }
    public function actionCardioWorkoutList($cardio_routine_id)
    {
        $cardio_routine  = \common\models\UserCardioRoutine::find()
        ->where(['user_id'=>\Yii::$app->user->id,'user_cardio_routine.id'=>$cardio_routine_id])->asArray()->one();
        if($cardio_routine){
            $cardioExes  = \common\models\Exercise::find()->where(['exe_category_id'=>11])->orderBy('name ASC')->asArray()->all();
            $cardioExes  = \yii\helpers\ArrayHelper::map($cardioExes,'id','name');
            $workout_map = ['1'=>'A','2'=>'B','3'=>'C','4'=>'D','5'=>'E','6'=>'F','7'=>'G'];
            $data['routine']           =  $cardio_routine['name'].' ('.$cardio_routine['cardio_type'].')';
            $j=0;
            for ($week=0; $week < 4 ; $week++) {                   
                for($day = 0;$day < $cardio_routine['how_many_day_per_week'];$day++){
                    $do_no =  $day+1;
                    $UserCardioRoutineExe     = \common\models\UserCardioRoutineExe::find()->joinWith(['exercise'])                    
                                                ->where(['user_cardio_routine_id'=>$cardio_routine_id,'day'=>$do_no])
                                                ->asArray()->all();
                                                
                    $UserCardioRoutineLog     = \common\models\UserCardioRoutineLog::find()->select(['exe_id'])
                                                ->where(['user_cardio_routine_id'=>$cardio_routine_id,'week_no'=>$week+1,'day_no'=>$do_no])
                                                ->andWhere('MONTH(FROM_UNIXTIME(created_at)) = MONTH(CURDATE()) AND YEAR(FROM_UNIXTIME(created_at)) = YEAR(CURDATE())')->asArray()
                                                ->all();

                    $UserCardioRoutineLog     =  \yii\helpers\ArrayHelper::getColumn($UserCardioRoutineLog,'exe_id');   
                                 
                    $UserCardioRoutineExeData = [];    
                    foreach ($UserCardioRoutineExe as $key => $value) { 
                        $UserCardioRoutineExeData[$key]['user_cardio_routine_id'] =  $value['user_cardio_routine_id'];   
                        $UserCardioRoutineExeData[$key]['exe_id']                 =  $value['exe_id'];   
                        $UserCardioRoutineExeData[$key]['week']                   =  $week+1;   
                        $UserCardioRoutineExeData[$key]['day']                    =  $value['day'];   
                        $UserCardioRoutineExeData[$key]['exercise']               =  $value['exercise']['name'];   
                        $UserCardioRoutineExeData[$key]['start']                  =  in_array($value['exe_id'],$UserCardioRoutineLog)?0:1;     
                    }
                    $data['workout'][$j]['title']          = "Day ".$do_no." of Week ".($week+1);   
                    $data['workout'][$j]['workout_title']  = 'Workout '.$workout_map[$do_no];
                    $data['workout'][$j]['exe_list']       = $UserCardioRoutineExeData;   
                    $j++;    
                }                       
            }
            return [
                'status'=>true,
                'data'=>$data
            ];
        }else{
            return [
                'status'=>false,
                'message'=> 'There are no exercise'
            ];
        }
    }
    private function workout($user_cardio_id,$no_of_days,$action,$cardio_type){        
        $cardioExes     = \common\models\Exercise::find()
        ->where(['OR',['exe_category_id'=>11,'user_id'=>1],['exe_category_id'=>11,'user_id'=>\Yii::$app->user->id]])
        ->orderBy('name ASC')->asArray()->all();   
        if($cardio_type =="Interval Training"){
            $otherExe =  \common\models\Exercise::find()
            ->joinWith(['category'])
            ->where(['user_id'=>1])
            ->andWhere(['!=','exe_category_id',11])
            ->orderBy('name ASC')->asArray()->all();  
            $cardioExes = array_merge($cardioExes,$otherExe);
        }  
       
        $workout_map    = ['1'=>'A','2'=>'B','3'=>'C','4'=>'D','5'=>'E','6'=>'F','7'=>'G'];
        $workout_data = [];
        for($day = 0;$day < $no_of_days;$day++){
            $do_no = $day+1;            
            if($action == "update"){
                $UserCardioRoutineExe = \common\models\UserCardioRoutineExe::find()
                ->where(['user_cardio_routine_id'=>$user_cardio_id,'day'=>$do_no])
                ->asArray()->all();
                $UserCardioRoutineExe = \yii\helpers\ArrayHelper::getColumn($UserCardioRoutineExe,'exe_id');
                $cardioExes_list      = \yii\helpers\ArrayHelper::getColumn($cardioExes,'id');
                $un_selected_exes     = array_intersect($cardioExes_list,$UserCardioRoutineExe);
            }else{
                $un_selected_exes = [];
            }
            $cardioExesData = [];$custom = [];$extra = [];$d = [];
            foreach($cardioExes as $k => $ele){                
                $ax['value']          =  $ele['id'];
                $ax['label']          =  $ele['name'];
                $ax['category_id']    =  $ele['exe_category_id'];
                $ax['category_name']    =  $ele['category']['name'];
                if (in_array($ele['id'], $un_selected_exes)) {
                    $ax['checked']    = true;
                }else{
                    $ax['checked']    = false;
                }
                if($ele['exe_category_id']== 11){
                    if($ele['user_id'] == 1){
                        array_push($cardioExesData,$ax);
                    }else{
                        array_push($custom,$ax);
                    }
                }else{
                    array_push($extra,$ax);
                }
                
            }
            $result_ = array();
            foreach ($extra as $element) {
                $result_[$element['category_name']]['title'] = $element['category_name'];
                $result_[$element['category_name']]['category_id'] = $element['category_id'];
                $result_[$element['category_name']]['content'][] = $element;
            }
            ksort($result_);
            $workout_data[$day]['title']     = 'Workout '.$workout_map[$do_no];
            $workout_data[$day]['day_title'] = 'Day '.$do_no;
            $main = [];
            if($cardio_type =="Interval Training"){
                
                $workout_data[$day]['exe']       =  array(
                        [   
                            'name'=>'Cardio Exercises ',
                            'list'=>$cardioExesData
                        ],
                        [
                            'name'=>'Resistance Cardio Exercises',
                            'list'=>array_values($result_)
                        ],
                        [
                            'name'=>'Custom Exercises',
                            'list'=>$custom
                        ]
                    );
            }else{
                $workout_data[$day]['exe']       = 
                 array(['name'=>'Cardio Exercises','list'=>$cardioExesData],
                      ['name'=>'Custom Exercises','list'=>$custom]
                    );
            }
            
        }
        return $workout_data;
    }
    public function actionCreate($cardio_routine_id=0)
    {
        
        if($cardio_routine_id){
            $action                 =  "update";
            $user_routine           =  \common\models\UserCardioRoutine::find()->where(['id'=>$cardio_routine_id,'user_id'=>Yii::$app->user->id])->one();
        }else{
            $action                 =  "create";
            $user_routine           =  new \common\models\UserCardioRoutine;
            $user_routine_count     =  \common\models\UserCardioRoutine::find()
            ->where(['user_id'=>Yii::$app->user->id])->count();
            if($user_routine_count > 7){
                return [
                    'status'=>false,
                    'message'=>"Sorry you can not create cardio routine more than 7. If you still want add then you should have to delete one of them."
                ];
            }

        }  
        $user_routine->load(Yii::$app->request->post());
        if($action == "create"){
            $user_routine_count     =  \common\models\UserCardioRoutine::find()
            ->where(['name'=>$user_routine->name,'user_id'=>\Yii::$app->user->id,'cardio_type'=>$user_routine->cardio_type])->count();
            if($user_routine_count){
                return [
                    'status'=>false,
                    'message'=>"Routine name is already been taken, Please use different name for this Cardio Routine."
                ];
            }
        }else if($action == "update"){
            $user_routine_count     =  \common\models\UserCardioRoutine::find()
            ->where(['name'=>$user_routine->name,'user_id'=>\Yii::$app->user->id,'cardio_type'=>$user_routine->cardio_type])
            ->andWhere(['!=','id',$cardio_routine_id])
            ->count();
            if($user_routine_count){
                return [
                    'status'=>false,
                    'message'=>"Routine name is already been taken, Please use different name for this Cardio Routine."
                ];
            }
        }
        $user_routine->user_id = Yii::$app->user->id;
        if ($user_routine->load(Yii::$app->request->post()) && $user_routine->validate() && $user_routine->save()){

                \common\models\UserCardioRoutineTime::deleteAll(['cardio_routine_id'=>$cardio_routine_id]);
                foreach ($user_routine->user_routine_day_and_time as $key => $days_and_time) {
                    $cardio_routine_time               = new \common\models\UserCardioRoutineTime;
                    $cardio_routine_time->cardio_routine_id   = $user_routine->id;
                    $cardio_routine_time->day_no       = $days_and_time['day'];
                    $cardio_routine_time->day_time     = $days_and_time['time'];                  
                    $cardio_routine_time->save();
                }
                $workout_data = $this->workout($user_routine->id,$user_routine->how_many_day_per_week,$action,$user_routine->cardio_type);
                return [
                    'status'=>true,
                    'data'=>['user_cardio_routine_id'=>$user_routine->id,
                    'workout_data'=>$workout_data]
                ];
        }else{
            return [
                'status'=>false,
                'message'=>\Yii::$app->general->error($user_routine->errors)
            ];
        }
    }
    public function actionAddExe($user_cardio_routine_id,$day,$exe_id){
        $UserCardioRoutineExe   = \common\models\UserCardioRoutineExe::find()
        ->where(['user_cardio_routine_id'=>$user_cardio_routine_id,'day'=>$day,'exe_id'=>$exe_id])
        ->one();
        if($UserCardioRoutineExe){
            $UserCardioRoutineExe->delete();
            return['status'=>true];
        }else{  
               
            $UserCardioRoutineExe                         =  new \common\models\UserCardioRoutineExe;
            $UserCardioRoutineExe->user_cardio_routine_id =  $user_cardio_routine_id;
            $UserCardioRoutineExe->day                    =  $day;
            $UserCardioRoutineExe->exe_id                 =  $exe_id;
         
            if($UserCardioRoutineExe->save()){
                return['status'=>true];
            }else{
                return [
                    'status'=>false,
                    'message'=>\Yii::$app->general->error($UserCardioRoutineExe->errors)
                ];
            }
        }      
    }
   
    public function actionDeleteRoutine($routine_id){
        $model    =  \common\models\UserCardioRoutine::find()->where(['id'=>$routine_id,'user_id'=>Yii::$app->user->id])->one();
        if($model && $model->delete()){
            return [
                'status'=>true
            ];
        }else{
            return [
                'status' => false,
                'message'=> 'Invalid object.'
            ];
        }
    }

}
?>