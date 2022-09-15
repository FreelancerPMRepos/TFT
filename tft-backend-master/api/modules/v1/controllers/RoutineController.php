<?php
namespace app\modules\v1\controllers;
use app\filters\auth\HttpBearerAuth;

use Yii;
use app\models\RoutineForm;
use common\models\Routines;
use common\models\RoutineTime;

use yii\filters\AccessControl;
use yii\filters\auth\CompositeAuth;
use yii\helpers\Url;
use yii\rest\ActiveController;
use yii\web\BadRequestHttpException;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

class RoutineController extends ActiveController
{
    public $modelClass = 'common\models\ExerciseCategory';
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
                'add-routine' => ['post'],
                'pathways' => ['GET'],
                'my-routine' => ['GET'],
                'sports-list' => ['GET'],
                'ssgst-workout-plan' => ['POST'],   
                'add-ssgst-workout-plan' => ['POST'], 
                'check-day-time'=> ['POST'], 
                             
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
           'for-admin-panel-routine-plan',
           'for-admin-panel-ssgst-workout-plan',
           'for-admin-panel-sstr-workout-plan',
        ];
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'only' => ['avail-ssstr','check-day-time','delete-routine','sstr-workout-plan','ssgst-workout-plan','routine-plan','workout-list','done-exercise','add-set','remove-set','set-list','routine-exe-list','add-routine','pathways', 'my-routine','my-routine-list','workout-exercise-detail','start-workout', 'sports-list','done-workout','update-routine','schedule-exe-list','save-workout-exercises','update-workout'], //only be applied to
            'rules' => [
               
                [
                    'allow' => true,
                    'actions' => ['avail-ssstr','check-day-time','delete-routine','sstr-workout-plan','ssgst-workout-plan','routine-plan','workout-list','start-workout','done-exercise','done-workout','add-set','remove-set','set-list','workout-exercise-detail','routine-exe-list','add-routine','pathways', 'schedule-exe-list','my-routine','my-routine-list', 'sports-list','add-ssgst-workout-plan','update-routine','update-workout','save-workout-exercises'],
                    'roles' => ['user'],
                ],
            ],
        ];
        return $behaviors;
    } 
    public function actionAvailSsstr(){
       $UserSport = \common\models\UserSport::find()->where(['user_id'=>\Yii::$app->user->id])->one();
       return [
        'status'=>true,
        'data'=>[
            'available'=>true,
            'sport_id'=>1
        ]
     ];
       if($UserSport){
            return [
                'status'=>true,
                'data'=>[
                    'available'=>true,
                    'sport_id'=>1
                ]
            ];
       }else{
           return [
               'status'=>true,
               'data'=>[
                    'available'=>false,
                ]
           ];
       }
    }
    // Exe and Exe Category List dropdown for Modify Workout
    private function data(){
        $body_part_list         = \common\models\ExerciseCategory::find()->select(['id','name'])->where(['!=','name','CARDIO'])->orderBy('name ASC')->asArray()->all();;
        $body_part_data         =  Yii::$app->general->optionsForApp($body_part_list,'id','name');
        $body_exe_list          =  \yii\helpers\ArrayHelper::index(\common\models\Exercise::find()->select(['id','name','exe_category_id'])
                                    ->where(['OR',['for_routine'=>1,'user_id'=>1],['for_routine'=>1,'user_id'=>\Yii::$app->user->id]])
                                    ->andWhere(['is_deleted'=>0])
                                    ->orderBy('name ASC')->asArray()->all(),null,'exe_category_id');;
        $body_exe_data          = [];
        foreach ($body_exe_list as $exe_category_id => $exe_list) {           
            $body_exe_data[$exe_category_id] =  Yii::$app->general->optionsForApp($exe_list,'id','name');
        }
        return [
            'body_part_list'=>$body_part_data,
            'body_exe_list'=>$body_exe_data
        ];
    }
    // Routine Plan for PoST | SST | PrST
    private function routinePlan($pathway_exercises,$pathway){
        $pathways                 = \common\models\Pathways::find()->asArray()->all();
        $pathways                 = \yii\helpers\ArrayHelper::index($pathways, null, 'name');
        $attributes               = $pathways[$pathway][0]; 
        $x = 0;$data =[];
        for ($week=1; $week <= 4 ; $week++) {                    
            foreach ($pathway_exercises as $day=>$exe_list) { 
                foreach($exe_list as $k=>$e){                    
                    $exes[$k]['routine']             = $pathway;
                    $exes[$k]['exe_id']              = $e['exe_id'];
                    $exes[$k]['exe_category_id']     = $e['exe_category_id'];                  
                    $exes[$k]['exe_name']            = $e['exe_name'];
                    $exes[$k]['exe_category']        = $e['exe_category'];
                    $exes[$k]['day']                 = $e['day'];
                    $exes[$k]['week_no']             = $week;


                    
                    $exes[$k]['sets']                       = $attributes['sets'];                   
                    $exes[$k]['reps']                       = $attributes['reps'];                   
                    $exes[$k]['lifting_time']               = $attributes['lifting_time'];
                    $exes[$k]['coutdown_timer']             = $attributes['countdown_timer'];
                    $exes[$k]['time_between_set']           = $attributes['time_between_set'];
                    $exes[$k]['time_between_body_part']     = $attributes['time_between_body_part'];                      
                    $exes[$k]['time_unit_countdown']        = $attributes['time_unit_countdown_timer'];

                  
                  
                }
                $workout_map = ['1'=>'A','2'=>'B','3'=>'C','4'=>'D','5'=>'E'];
                $workout_map = ['1'=>'A','2'=>'B','3'=>'C','4'=>'D','5'=>'E'];
                $data[$x]['title']          =  'Day '.$day.' of Week '.$week;
                $data[$x]['week']           =   $week;
                $data[$x]['day']            =   $day;
                $data[$x]['workout_title']  =  'Workout '.$workout_map[$day];
                $data[$x]['exe_list']       =  $exes;
                $x++;
            }
        }
        return $data;
    }
    public function actionForAdminPanelSstrWorkoutPlan(){
        $model               = new \app\models\SSSTRForm();        
        if ($model->load(Yii::$app->request->post()) && $model->validate()){ 
            $sport                = \common\models\Sports::find()->where(['id'=>$model->user_selected_sport_id])->asArray()->one();
            $user_selected_sport  = !empty($sport)?$sport['name']:"-";

            $routinePlan = "";
            if(!empty($_GET['routine_id'])){
                $routineModel = \common\models\Routines::find()->where(['user_id'=>\Yii::$app->user->id,'id'=>$_GET['routine_id']])->asArray()->one();
                if(!empty($routineModel['routine_workout_list'])){
                    $routinePlan = json_decode($routineModel['routine_workout_list'],true);
                }
            }

            $data_1['workout_list']                        = !empty($routinePlan)?$routinePlan:$this->sstrPlan($model->user_selected_sport_id,$model->user_selected_season,$model->user_selected_start);  
            $data_1['routine_name']                        = 'SSSTR -  '.$user_selected_sport.'('.$model->user_selected_season.'-Season) - '.$model->user_selected_start;
            $data_1['day_per_week']                        = 3;
            $data_1['day_time_list']                       = $model->routine_day_and_time; 

            // $data_1['sets_dropdown']                       = Yii::$app->general->dataFor('sets','SSSTR');
            // $data_1['reps_dropdown']                       = Yii::$app->general->dataFor('reps','SSSTR');
            // $data_1['lifting_time_dropdown']               = Yii::$app->general->dataFor('lifting_time','SSSTR');
            // $data_1['coutdown_timer_dropdown']             = Yii::$app->general->dataFor('coutdown_timer','SSSTR');
            // $data_1['time_between_set_dropdown']           = Yii::$app->general->dataFor('time_between_set','SSSTR');                 
            // $data_1['time_between_body_part_dropdown']     = Yii::$app->general->dataFor('time_between_body_part','SSSTR');             
            $data_1                                        = $data_1+$this->data();
            $x_r                                           =  ['PoST','SST','PrST'];
            $x_rdata = [];
            foreach($x_r as $x_routine){
                $x_rdata[$x_routine]=[
                    'sets_dropdown'                     => Yii::$app->general->dataFor('sets',$x_routine,'SSSTR'),
                    'reps_dropdown'                     => Yii::$app->general->dataFor('reps',$x_routine,'SSSTR'),
                    'lifting_time_dropdown'             => Yii::$app->general->dataFor('lifting_time',$x_routine,'SSSTR'),
                    'coutdown_timer_dropdown'           => Yii::$app->general->dataFor('coutdown_timer',$x_routine,'SSSTR'),
                    'time_between_set_dropdown'         => Yii::$app->general->dataFor('time_between_set',$x_routine,'SSSTR'),                 
                    'time_between_body_part_dropdown'   => Yii::$app->general->dataFor('time_between_body_part',$x_routine,'SSSTR'),   
                ];
            }
            $data_1['dropdown'] = $x_rdata;
            return [
                'status'=>true,
                'data'=>$data_1,
            ];           
        }else{
            return [
                'status'=>false,
                'message'=>\Yii::$app->general->error($model->errors)
            ]; 
        } 

    }
    public function actionForAdminPanelSsgstWorkoutPlan(){
        $model = new \app\models\SSGSTForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()){
            $sp = \common\models\Sports::find()->where(['id'=>$model->user_selected_sport_id])->one();
            $user_selected_sport =  $sp->name;   
            $day                      =  $model->how_many_day_per_week;
            $data['routine_name']     = 'SSGST -  '.$user_selected_sport;
            $data['day_per_week']     = $day;
            $data['day_time_list']    = $model->routine_day_and_time;  
            $routinePlan = "";
            if(!empty($_GET['routine_id'])){
                $routineModel = \common\models\Routines::find()->where(['user_id'=>\Yii::$app->user->id,'id'=>$_GET['routine_id']])->asArray()->one();
                if(!empty($routineModel['routine_workout_list'])){
                    $routinePlan = json_decode($routineModel['routine_workout_list'],true);
                }
            }

            $data['workout_list']  = !empty($routinePlan)?$routinePlan:$this->ssgstPlan($model->user_selected_sport_id,$day);
            $data                  = $data+$this->data();
            $x_r                                         = ['PoST','SST','PrST'];
            $x_rdata = [];
            foreach($x_r as $x_routine){
                $x_rdata[$x_routine]=[
                    'sets_dropdown'                     => Yii::$app->general->dataFor('sets',$x_routine,'SSGST'),
                    'reps_dropdown'                     => Yii::$app->general->dataFor('reps',$x_routine,'SSGST'),
                    'lifting_time_dropdown'             => Yii::$app->general->dataFor('lifting_time',$x_routine,'SSGST'),
                    'coutdown_timer_dropdown'           => Yii::$app->general->dataFor('coutdown_timer',$x_routine,'SSGST'),
                    'time_between_set_dropdown'         => Yii::$app->general->dataFor('time_between_set',$x_routine,'SSGST'),                 
                    'time_between_body_part_dropdown'   => Yii::$app->general->dataFor('time_between_body_part',$x_routine,'SSGST'),   
                ];
            }
            $data['dropdown'] = $x_rdata;
            return [
                'status'=>true,
                'data'=>$data,
            ];
        }else{
            $model->validate();
            return [
                'status'=>false,
                'message'=>\Yii::$app->general->error($model->errors)
            ];
        }
    }
    public function actionForAdminPanelRoutinePlan(){
        $model = new RoutineForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()){ 
            $pathway_exercises = \common\models\PathwayExercises::find()->where(['<=','day',$model->how_many_day_per_week])->asArray()->all();
            $pathway_exercises = \yii\helpers\ArrayHelper::index($pathway_exercises, null, 'day');
            if($pathway_exercises){ 
                                
                $data['routine_name']                        = $model->pathway;
                $data['day_per_week']                        = $model->how_many_day_per_week;
                $data['day_time_list']                       = $model->routine_day_and_time; 

                $data['sets_dropdown']                       = Yii::$app->general->dataFor('sets',$model->pathway,$model->pathway);
                $data['reps_dropdown']                       = Yii::$app->general->dataFor('reps',$model->pathway,$model->pathway);
                $data['lifting_time_dropdown']               = Yii::$app->general->dataFor('lifting_time',$model->pathway,$model->pathway);
                $data['coutdown_timer_dropdown']             = Yii::$app->general->dataFor('coutdown_timer',$model->pathway,$model->pathway);
                $data['time_between_set_dropdown']           = Yii::$app->general->dataFor('time_between_set',$model->pathway,$model->pathway);                 
                $data['time_between_body_part_dropdown']     = Yii::$app->general->dataFor('time_between_body_part',$model->pathway,$model->pathway);   

                $routinePlan = "";
                if(!empty($_GET['routine_id'])){
                    $routineModel = \common\models\Routines::find()->where(['user_id'=>\Yii::$app->user->id,'id'=>$_GET['routine_id']])->asArray()->one();
                    if(!empty($routineModel['routine_workout_list'])){
                        $routinePlan = json_decode($routineModel['routine_workout_list'],true);
                    }
                }

                $data['workout_list']                        = !empty($routinePlan)?$routinePlan:$this->routinePlan($pathway_exercises,$model->pathway,$model->pathway);
                $data                                        = $data+$this->data();
                return [
                    'status'=>true,
                    'data'=>$data
                ]; 
            } else{
                return [
                    'status'=>false,
                    'message'=>'Invalid Routine You Select'
                ]; 
            }
            
        }else{
            return [
                'status'=>false,
                'message'=>\Yii::$app->general->error($model->errors)
            ];
        }
    }
    public function actionRoutinePlan(){
        $model = new RoutineForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()){ 
            $pathway_exercises = \common\models\PathwayExercises::find()->where(['<=','day',$model->how_many_day_per_week])->asArray()->all();
            $pathway_exercises = \yii\helpers\ArrayHelper::index($pathway_exercises, null, 'day');
            if($pathway_exercises){ 
                                
                $data['routine_name']                        = $model->pathway;
                $data['day_per_week']                        = $model->how_many_day_per_week;
                $data['day_time_list']                       = $model->routine_day_and_time; 

                $data['sets_dropdown']                       = Yii::$app->general->dataFor('sets',$model->pathway,$model->pathway);
                $data['reps_dropdown']                       = Yii::$app->general->dataFor('reps',$model->pathway,$model->pathway);
                $data['lifting_time_dropdown']               = Yii::$app->general->dataFor('lifting_time',$model->pathway,$model->pathway);
                $data['coutdown_timer_dropdown']             = Yii::$app->general->dataFor('coutdown_timer',$model->pathway,$model->pathway);
                $data['time_between_set_dropdown']           = Yii::$app->general->dataFor('time_between_set',$model->pathway,$model->pathway);                 
                $data['time_between_body_part_dropdown']     = Yii::$app->general->dataFor('time_between_body_part',$model->pathway,$model->pathway);   

                $routinePlan = "";
                if(!empty($_GET['routine_id'])){
                    $routineModel = \common\models\Routines::find()
                    ->joinWith(['pathway'])
                    ->where(['user_id'=>\Yii::$app->user->id,'routines.id'=>$_GET['routine_id']])
                    ->asArray()->one();
                    if(!empty($routineModel['routine_workout_list'])){
                        $routinePlan = json_decode($routineModel['routine_workout_list'],true);
                    }
                }
                if(!empty($routinePlan) 
                    && $routineModel['pathway']['name'] == $model->pathway
                    && $routineModel['day'] == $model->how_many_day_per_week
                    ){
                    $data['workout_list']  =  $routinePlan;
                }else{
                    $data['workout_list']  =  $this->routinePlan($pathway_exercises,$model->pathway,$model->pathway);
                }
                
                $data                                        = $data+$this->data();
                return [
                    'status'=>true,
                    'data'=>$data
                ]; 
            } else{
                return [
                    'status'=>false,
                    'message'=>'Invalid Routine You Select'
                ]; 
            }
            
        }else{
            return [
                'status'=>false,
                'message'=>\Yii::$app->general->error($model->errors)
            ];
        }
    }
    // Routine Plan for SSGST
    private function ssgstPlan($user_selected_sport_id,$how_many_day_per_week){
        $sp = \common\models\Sports::find()->where(['id'=>$user_selected_sport_id])->one();
        $user_selected_sport =  $sp->name;
        $day                 =  $how_many_day_per_week;
        $file = Yii::$app->basePath.'/../img_assets/csv/ssgst/'.$day.'-day-sport.csv';  
        $workout_map = ['1'=>'A','2'=>'B','3'=>'C','4'=>'D','5'=>'E'];

        $pathway_exercises = \common\models\PathwayExercises::find()->where(['<=','day',$how_many_day_per_week])->asArray()->all();
        $pathway_exercises = \yii\helpers\ArrayHelper::index($pathway_exercises, null, 'day');

        $pathways = \common\models\Pathways::find()->asArray()->all();
        $pathways = \yii\helpers\ArrayHelper::index($pathways, null, 'name');

       
       
        if(file_exists($file)){

            $sports = array_map('str_getcsv', file($file));
            unset($sports[0]);
            $data = [];           

            foreach($sports as $sport){ 
                $sport_name  =  $sport[0];
                if($user_selected_sport != $sport_name){
                    continue;
                }
                unset($sport[0]);          
                $sport_weeks = array_chunk($sport,$day); 
                $j = 0;                                 
                foreach($sport_weeks as $week_no => $week_days){
                    $week_no            = $week_no+1;                            
                    $pre_routine_array  = ['PoST'=>'1','SST'=>'2','PrST'=>'3']; 
                    foreach($week_days as $week_day => $week_day_routine){ 
                        $attributes                                =  $pathways[$week_day_routine][0];                                  
                        $week_day                                  =  $week_day+1;
                        $data[$j]['title']                         = "Day ".$week_day." of Week ".$week_no;   
                        $data[$j]['workout_title']                 = 'Workout '.$workout_map[$week_day];
                        $data[$j]['week']                          =  $week_no;
                        $data[$j]['day']                           =  $week_day;
                        $path_id                                   =  $pre_routine_array[$week_day_routine];                            
                        $pathway_exercises                         = \common\models\PathwayExercises::find()->where(['day'=>$week_day])->asArray()->all();                            
                        foreach($pathway_exercises as $kk => $exe){
                            $data[$j]['exe_list'][$kk]['routine']                    = $week_day_routine;
                            $data[$j]['exe_list'][$kk]['exe_id']                     = $exe['exe_id'];
                            $data[$j]['exe_list'][$kk]['exe_category_id']            = $exe['exe_category_id'];
                            $data[$j]['exe_list'][$kk]['week_no']                    = $week_no;
                            $data[$j]['exe_list'][$kk]['day']                        = $exe['day'];
                            $data[$j]['exe_list'][$kk]['exe_name']                   = $exe['exe_name']; 
                            
                            $data[$j]['exe_list'][$kk]['sets']                       = $attributes['sets'];

                            $data[$j]['exe_list'][$kk]['reps']                       = $attributes['reps'];
                            $data[$j]['exe_list'][$kk]['lifting_time']               = $attributes['lifting_time'];
                            $data[$j]['exe_list'][$kk]['coutdown_timer']             = $attributes['countdown_timer'];
                            $data[$j]['exe_list'][$kk]['time_between_set']           = $attributes['time_between_set'];         
                            $data[$j]['exe_list'][$kk]['time_unit_countdown']        = $attributes['time_unit_countdown_timer'];
                            $data[$j]['exe_list'][$kk]['time_between_body_part']     = $attributes['time_between_body_part'];
                            
                            
                        }
                        $j++;
                    } 
                }      
            }
            return $data;
        }else{
            throw new \yii\web\NotFoundHttpException("Invalid day selection.");
        }
    }
    public function actionSsgstWorkoutPlan(){
        $model = new \app\models\SSGSTForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()){
            $sp = \common\models\Sports::find()->where(['id'=>$model->user_selected_sport_id])->one();
            $user_selected_sport =  $sp->name;   
            $day                      =  $model->how_many_day_per_week;
            $data['routine_name']     = 'SSGST -  '.$user_selected_sport;
            $data['day_per_week']     = $day;
            $data['day_time_list']    = $model->routine_day_and_time;  
            $routinePlan = "";
            if(!empty($_GET['routine_id'])){
                $routineModel = \common\models\Routines::find()->where(['user_id'=>\Yii::$app->user->id,'id'=>$_GET['routine_id']])->asArray()->one();
                if(!empty($routineModel['routine_workout_list'])){
                    $routinePlan = json_decode($routineModel['routine_workout_list'],true);
                }
            }

            $data['workout_list']  = !empty($routinePlan)?$routinePlan:$this->ssgstPlan($model->user_selected_sport_id,$day);
            $data                  = $data+$this->data();
            $x_r                                         = ['PoST','SST','PrST'];
            $x_rdata = [];
            foreach($x_r as $x_routine){
                $x_rdata[$x_routine]=[
                    'sets_dropdown'                     => Yii::$app->general->dataFor('sets',$x_routine,'SSGST'),
                    'reps_dropdown'                     => Yii::$app->general->dataFor('reps',$x_routine,'SSGST'),
                    'lifting_time_dropdown'             => Yii::$app->general->dataFor('lifting_time',$x_routine,'SSGST'),
                    'coutdown_timer_dropdown'           => Yii::$app->general->dataFor('coutdown_timer',$x_routine,'SSGST'),
                    'time_between_set_dropdown'         => Yii::$app->general->dataFor('time_between_set',$x_routine,'SSGST'),                 
                    'time_between_body_part_dropdown'   => Yii::$app->general->dataFor('time_between_body_part',$x_routine,'SSGST'),   
                ];
            }
            $data['dropdown'] = $x_rdata;
            return [
                'status'=>true,
                'data'=>$data,
            ];
        }else{
            $model->validate();
            return [
                'status'=>false,
                'message'=>\Yii::$app->general->error($model->errors)
            ];
        }
    }
    // Routine Plan for SSSTR
    private function sstrPlan($user_selected_sport_id,$user_selected_season,$user_selected_start){
      
            $pathway_id             = 5;            
            $pathways = \common\models\Pathways::find()->asArray()->all();
            $pathways = \yii\helpers\ArrayHelper::index($pathways, null, 'name');
    
            $sport                = \common\models\Sports::find()->where(['id'=>$user_selected_sport_id])->asArray()->one();
            $user_selected_sport  = !empty($sport)?$sport['name']:"-";
            $exe_list    = [];
            $exe_list_db = \common\models\SportExe::find()->joinWith(['exe'])
            ->where(['sport_id'=>$user_selected_sport_id,'season'=>$user_selected_season])->asArray()->all();
    
            foreach($exe_list_db as $k => $exe){
                $exe_list[$k] = $exe['exe'];
            }

            // $sets_dropdown                   = Yii::$app->general->dataFor('sets');
            // $reps_dropdown                   = Yii::$app->general->dataFor('reps');
            // $lifting_time_dropdown           = Yii::$app->general->dataFor('lifting_time');
            // $coutdown_timer_dropdown         = Yii::$app->general->dataFor('coutdown_timer');
            // $time_between_set_dropdown       = Yii::$app->general->dataFor('time_between_set');
            // $time_between_body_part_dropdown = Yii::$app->general->dataFor('time_between_body_part');
    
            $file                = Yii::$app->basePath.'/../img_assets/csv/ssstr/workout.csv';  
            if(file_exists($file)){
                $sports = array_map('str_getcsv', file($file));
                unset($sports[0]);
                $data = [];        
                foreach($sports as $sport){         
                    $sport_name  =  $sport[0];
                    $start_type  =  $sport[1];
                    $total_week  =  $sport[2];
    
                    $post_count  =  $sport[3];
                    $sst_count   =  $sport[4];
                    $prst_count  =  $sport[5];
                    if($user_selected_sport == $sport_name && $user_selected_start == $start_type){
                        unset($sport[0]);          
                        unset($sport[1]);                          
                        $sport_weeks = array_chunk($sport,3);        
                        $j = 0;  
                        for ($week_no=1; $week_no <= $total_week; $week_no++) { 
                            if($week_no <= $post_count){
                                $week_day_routine = "PoST";
                            }else if($post_count < $week_no && $week_no <= $sst_count){
                                $week_day_routine = "SST";
                            }else if($sst_count < $week_no && $week_no <= $prst_count){
                                $week_day_routine = "PrST";
                            }                       
                            for ($day_no=1; $day_no <= 3; $day_no++) {                        
                                $workout_map = ['1'=>'A','2'=>'B','3'=>'C','4'=>'D','5'=>'E'];
                                $data[$j]['week_day_routine']   =  $week_day_routine; 
                                $data[$j]['title']              =  'Day '.$day_no.' of Week '.$week_no;
                                $data[$j]['workout_title']      =  'Workout '.$workout_map[$day_no]; 
                                $data[$j]['week']               =   $week_no;
                                $data[$j]['day']                =   $day_no;
                                $attributes                     =  $pathways[$week_day_routine][0];                   
                                foreach($exe_list as $k=>$e){
                                    $exes[$k]['routine']             = $week_day_routine;
                                    $exes[$k]['exe_id']              = $e['id'];
                                    $exes[$k]['exe_category_id']     = $e['exe_category_id'];
                                    $exes[$k]['week_no']             = $week_no;
                                    $exes[$k]['day']                 = $day_no;
                                    $exes[$k]['exe_name']            = $e['name']; 

                                    $exes[$k]['sets']                       = $attributes['sets'];
                                   // $exes[$k]['sets_dropdown']              = $sets_dropdown;
                                    $exes[$k]['reps']                       = $attributes['reps'];
                                  //  $exes[$k]['reps_dropdown']              = $reps_dropdown;
                                    $exes[$k]['lifting_time']               = $attributes['lifting_time'];
                                  //  $exes[$k]['lifting_time_dropdown']      = $lifting_time_dropdown;
                                    $exes[$k]['coutdown_timer']             = $attributes['countdown_timer'];
                                 //   $exes[$k]['coutdown_timer_dropdown']    = $coutdown_timer_dropdown;
                                    $exes[$k]['time_between_set']           = $attributes['time_between_set'];
                                 //   $exes[$k]['time_between_set_dropdown']  = $time_between_set_dropdown;                   
                                    $exes[$k]['time_unit_countdown']        = $attributes['time_unit_countdown_timer'];
                                    $exes[$k]['time_between_body_part']     = $attributes['time_between_body_part'];
                                //    $exes[$k]['time_between_body_part_dropdown']     = $time_between_body_part_dropdown;
                                }
                                $data[$j]['exe_list']                = $exes;                            
                                $j++;
                            }  
                        }    
                    }else{
                        continue;
                    }  
                }              
                return $data;
            }else{
                throw new \yii\web\NotFoundHttpException("Invalid day selection.");
            }
    }
    public function actionSstrWorkoutPlan(){
        $model               = new \app\models\SSSTRForm();        
        if ($model->load(Yii::$app->request->post()) && $model->validate()){ 
            $sport                = \common\models\Sports::find()->where(['id'=>$model->user_selected_sport_id])->asArray()->one();
            $user_selected_sport  = !empty($sport)?$sport['name']:"-";

            $routinePlan = "";
            if(!empty($_GET['routine_id'])){
                $routineModel = \common\models\Routines::find()->where(['user_id'=>\Yii::$app->user->id,'id'=>$_GET['routine_id']])->asArray()->one();
                if(!empty($routineModel['routine_workout_list'])){
                    $routinePlan = json_decode($routineModel['routine_workout_list'],true);
                }
            }

            $data_1['workout_list']                        = !empty($routinePlan)?$routinePlan:$this->sstrPlan($model->user_selected_sport_id,$model->user_selected_season,$model->user_selected_start);  
            $data_1['routine_name']                        = 'SSSTR -  '.$user_selected_sport.'('.$model->user_selected_season.'-Season) - '.$model->user_selected_start;
            $data_1['day_per_week']                        = 3;
            $data_1['day_time_list']                       = $model->routine_day_and_time; 

            // $data_1['sets_dropdown']                       = Yii::$app->general->dataFor('sets','SSSTR');
            // $data_1['reps_dropdown']                       = Yii::$app->general->dataFor('reps','SSSTR');
            // $data_1['lifting_time_dropdown']               = Yii::$app->general->dataFor('lifting_time','SSSTR');
            // $data_1['coutdown_timer_dropdown']             = Yii::$app->general->dataFor('coutdown_timer','SSSTR');
            // $data_1['time_between_set_dropdown']           = Yii::$app->general->dataFor('time_between_set','SSSTR');                 
            // $data_1['time_between_body_part_dropdown']     = Yii::$app->general->dataFor('time_between_body_part','SSSTR');             
            $data_1                                        = $data_1+$this->data();
            $x_r                                           =  ['PoST','SST','PrST'];
            $x_rdata = [];
            foreach($x_r as $x_routine){
                $x_rdata[$x_routine]=[
                    'sets_dropdown'                     => Yii::$app->general->dataFor('sets',$x_routine,'SSSTR'),
                    'reps_dropdown'                     => Yii::$app->general->dataFor('reps',$x_routine,'SSSTR'),
                    'lifting_time_dropdown'             => Yii::$app->general->dataFor('lifting_time',$x_routine,'SSSTR'),
                    'coutdown_timer_dropdown'           => Yii::$app->general->dataFor('coutdown_timer',$x_routine,'SSSTR'),
                    'time_between_set_dropdown'         => Yii::$app->general->dataFor('time_between_set',$x_routine,'SSSTR'),                 
                    'time_between_body_part_dropdown'   => Yii::$app->general->dataFor('time_between_body_part',$x_routine,'SSSTR'),   
                ];
            }
            $data_1['dropdown'] = $x_rdata;
            return [
                'status'=>true,
                'data'=>$data_1,
            ];           
        }else{
            return [
                'status'=>false,
                'message'=>\Yii::$app->general->error($model->errors)
            ]; 
        } 

    }
    private function time_validation_msg($routine_type,$mainModel,$attribute,$attribute_other,$user_routines_id_other,$user_routines_id,$otherModel){
        $week_days = [2=>'Monday',3=>'Tuesday',4=>'Wednesday',5=>'Thursday',6=>'Friday',7=>'Saturday',1=>'Sunday'];
        foreach($_POST['routine_day_and_time'] as $each){
            $r = $mainModel::find()->select(['day_time','day_no'])->where(['day_no'=>$each['day']])
            ->andWhere(['IN',$attribute,$user_routines_id])->asArray()->all();
            foreach($r as $er){
                $first_time =  strtotime('2020-08-18 '.$er['day_time']);
                $sec_time   =  strtotime('2020-08-18 '.$each['time']);
                $time       =  abs($sec_time - $first_time); 
                if($time < 10800){
                    $rname = $mainModel=="\common\models\UserCardioRoutineTime"?"Cardio":"Strength";
                    // Oops! You have already setup a Strength Routine on (insert the name of the day) at (insert the time).
                    $day_time_txt = isset($week_days[$er['day_no']])?$week_days[$er['day_no']].' at '.$er['day_time'].'.':"";
                    return [
                        'status'=>false,
                        'message'=>'Oops! You have already set-up a '.ucwords($rname).' Routine on '.$day_time_txt. 
                                    '             Do you want to set-up your Workouts with a gap of less than 3 hours between them?'
                    ];  
                }
            }
            $r = $otherModel::find()->select(['day_time','day_no'])->where(['day_no'=>$each['day'],'day_time'=>$each['time']])
            ->andWhere(['IN',$attribute_other,$user_routines_id_other])->asArray()->all();
            foreach($r as $er){
                $first_time =  strtotime('2020-08-18 '.$er['day_time']);
                $sec_time   =  strtotime('2020-08-18 '.$each['time']);
                $time       =  abs($sec_time - $first_time); 
                if($time < 10800){
                    $rname = $otherModel=="\common\models\UserCardioRoutineTime"?"Cardio":"Strength";
                    $day_time_txt = isset($week_days[$er['day_no']])?$week_days[$er['day_no']].' at '.$er['day_time'].'.':"";
                    return [
                        'status'=>false,
                        'message'=>'Oops! You have already set-up a '.ucwords($rname).' Routine on '.$day_time_txt. 
                                    '              Do you want to set-up your Workouts with a gap of less than 3 hours between them?'
                    ];   
                }
            }
        }
        return [
            'status'=>true               
        ];
    }
    // Each Workout Time Validation for Routine
    public function actionCheckDayTime($routine_id = 0,$routine_type ="Strength"){
       if(!empty($_POST['routine_day_and_time'])){

            if($routine_type == "Cardio"||$routine_type == "cardio"){
                $user_routines_id = \common\models\UserCardioRoutine::find()
                ->where(['user_id'=>\Yii::$app->user->id])->andWhere(['!=','id',$routine_id])->asArray()->all();
                $user_routines_id = \yii\helpers\ArrayHelper::getColumn($user_routines_id,'id');
                $mainModel = "\common\models\UserCardioRoutineTime";
                $attribute ="cardio_routine_id";

                $user_routines_id_other = \common\models\Routines::find()
                ->where(['user_id'=>\Yii::$app->user->id])->asArray()->all();
                $user_routines_id_other = \yii\helpers\ArrayHelper::getColumn($user_routines_id_other,'id');
                $otherModel = "\common\models\RoutineTime";
                $attribute_other ="routine_id";
                $other_routine_type = "Strength";

            }else{
                $user_routines_id = \common\models\Routines::find()
                ->where(['user_id'=>\Yii::$app->user->id])->andWhere(['!=','id',$routine_id])->asArray()->all();
                $user_routines_id = \yii\helpers\ArrayHelper::getColumn($user_routines_id,'id');
                $mainModel = "\common\models\RoutineTime";
                $attribute ="routine_id";

                $user_routines_id_other = \common\models\UserCardioRoutine::find()
                ->where(['user_id'=>\Yii::$app->user->id])->asArray()->all();
                $user_routines_id_other = \yii\helpers\ArrayHelper::getColumn($user_routines_id_other,'id');
                $otherModel = "\common\models\UserCardioRoutineTime";
                $attribute_other ="cardio_routine_id";
                $other_routine_type = "Cardio";
            }

            $days       = \yii\helpers\ArrayHelper::getColumn($_POST['routine_day_and_time'],'day');
            $times      = \yii\helpers\ArrayHelper::getColumn($_POST['routine_day_and_time'],'time');
            $day_unique = array_unique($days);    
            $dd = [];
            if(count($days) != count($day_unique) ){
                if(!empty($_POST['single_day'])){  
                        $days_main =  $days;
                        foreach($days as $k=>$day){
                            unset($days_main[$k]);
                            if (in_array($day, $days_main)) {
                                $first_k  = $k;
                                $second_k = array_search($day,$days_main);
                                $first_time =  strtotime('2020-08-18 '.$times[$first_k]);
                                $sec_time   =  strtotime('2020-08-18 '.$times[$second_k]);
                                $time       =  abs($sec_time - $first_time); 
                                if($time < 10800){
                                    $data =  $this->time_validation_msg($routine_type,$mainModel,$attribute,$attribute_other,$user_routines_id_other,$user_routines_id,$otherModel);
                                    if($data['status']==true){
                                        return [
                                            'status'=>false,
                                            'message'=>'Do you want to set-up your workouts with gap of less than 3 hours ?'
                                        ];  
                                    }else{
                                        return $data;
                                    }
                                }
                            }
                        }
                }else{
                    return [
                        'status'=>false,
                        'data'=>'call_again',
                        'message'=>'Are you sure you want to schedule more than one workout on a single day?'
                    ]; 
                }
            }
            return $this->time_validation_msg($routine_type,$mainModel,$attribute,$attribute_other,$user_routines_id_other,$user_routines_id,$otherModel);
       }else{
            return [
                'status'=>false,
                'message'=>'Invalid date and time.'
            ]; 
       }
    }
    //ADD Routine  PoST,SST,PrST,SSGST,SSSTR
    public function actionAddRoutine($type="",$routine_id = 0)
    {   
        if($type == ""){
            return [
                'status'=>false,
                'message'=>'Invalid Object.'
            ];
        }
        if($type == "PoST"||$type == "PrST"||$type == "SST"){
            $model  = new \app\models\RoutineForm(); 
        }
        if($type == "SSSTR"){
            $model  = new \app\models\SSSTRForm();
            $model->how_many_day_per_week = 3;
        }
        if($type == "SSGST"){
            $model  = new \app\models\SSGSTForm();
        }

        if($routine_id == 0){
            $routine_count      =  \common\models\Routines::find()->where(['user_id'=>\Yii::$app->user->id])->count();
            if($routine_count > 2){
                return [
                    'status'=>false,
                    'message'=>"Sorry you can not create strength routine more than 2. If you still want add then you should have to delete one of them."
                ];
            }  
        }       

        if ($model->load(Yii::$app->request->post()) && $model->validate()){
            $workout_json = json_decode($model->workout_json);
            if($workout_json === null) {
                return [
                    'status'=>false,
                    'message'=>'Invalid Json Input.'
                ]; 
            }   
            $pathway                          = \common\models\Pathways::find()->where(['name'=>$type])->one();
            if($routine_id){
                $routine                      =  \common\models\Routines::find()->where(['id'=>$routine_id,'user_id'=>\Yii::$app->user->id])->one();
            }else{
                $routine                      =  new \common\models\Routines;
            }  
            if($pathway->id == 1){
                $time_between_last_sets = 240;
            }else if($pathway->id == 2){
                $time_between_last_sets = 180;
            }else if($pathway->id == 3){
                $time_between_last_sets = 120;
            }else{
                $time_between_last_sets = 240;
            }

            $routine->pathway_id              =  $pathway->id;
            $routine->day                     =  $model->how_many_day_per_week;
            $routine->time_between_last_sets  =  $time_between_last_sets;
            $routine->sport_id                =  ($type == "SSSTR"||$type == "SSGST")?$model->user_selected_sport_id:"";
            $routine->season                  =  ($type == "SSSTR")?$model->user_selected_season:"";
            $routine->mode                    =  1;
            $routine->user_id                 =  \Yii::$app->user->id;
            $routine->routine_weight_unit     =  \Yii::$app->user->identity->userAdditionalInfos->units_of_measurement == "lbs/in"?'lbs':'kg';
            $routine->routine_workout_list    =  $workout_json;            
            if($routine->save()){ // Save Routine
                // Add Routine Time
                \common\models\RoutineTime::deleteAll(['routine_id'=>$routine->id]);
                foreach ($model->routine_day_and_time as $key => $days_and_time) {
                    $routine_time               = new \common\models\RoutineTime;
                    $routine_time->routine_id   = $routine->id;
                    $routine_time->day_no       = $days_and_time['day'];
                    $routine_time->day_time     = $days_and_time['time'];
                    $routine_time->save(false);
                } 
                return [
                    'status'=>true,
                    'data'=>['routine_id'=>$routine->id],
                    'message'=>'Routine added successfully.'
                ];               
            }else{
                return [
                    'status'=>false,
                    'message'=>\Yii::$app->general->error($routine->errors)
                ]; 
            };

        }else{
            return [
                'status'=>false,
                'message'=>\Yii::$app->general->error($model->errors)
            ];
        }
    }  
    public function actionMyRoutineList(){
        $user_id = Yii::$app->user->id;
        $strength_routines   = \common\models\Routines::find()
        ->joinWith(['routinesTimes','pathway','sport'])
        ->where(['routines.user_id'=>$user_id])
        ->orderBy('routines.id DESC')
        ->asArray()->all();
        $cardio_routines = \common\models\UserCardioRoutine::find()
        ->joinWith(['cardioRoutineTime'])->where(['user_id'=>$user_id])->orderBy('id DESC')->asArray()->all();
        return [
            'status'=>true,
            'data'=>['strength_routines'=>$strength_routines,'cardio_routines'=>$cardio_routines]
        ];  
    }
    //List of Sports for SSGST and SSSTR
    public function actionSportsList($active_type='active'){
        $query = \common\models\Sports::find()->where([$active_type => 1])->orderBy('name ASC')->asArray();
        $provider = new \yii\data\ActiveDataProvider([
            'query' => $query
        ]);

        $models   = $provider->getModels(); 

        $data = [];
        foreach ($models as $key => $value) {
            $data[$key] =  $value;

            if(isset($value['images'])){
                $data[$key]['images'] = \yii\helpers\Url::to('tft/img_assets/sports/'.$value['images'], $schema = true);
            }

            if(isset($value['created_at'])){
                $data[$key]['created_at'] = date('d/m/Y', $value['created_at']);
            }

        }

        return [
            'status'=>true,
            'data'=>$data
        ];  
    }
    public function actionWorkoutList($routine_id){
        $data = [];
        $routine = \common\models\Routines::find()->joinWith(['pathway'])
        ->where(['routines.id'=>$routine_id])->asArray()->one();
        if($routine){    
                $week_limit = 4;       
                if($routine['pathway_id'] == 4 || $routine['pathway_id'] == 5){
                    $month         = (int) date("m",$routine['created_at']);
                    $year          = (int) date("Y",$routine['created_at']);
                    $current_month = (int) date("m",time());
                    $current_year  = (int) date("Y",time());
                    if($current_year > $year){
                        $week_limit = 52;       
                    }else{
                        $week_limit = (($current_month - $month)+1)*4;
                    }
                }
                $routine_workout_list      = json_decode($routine['routine_workout_list'],true); 
                $workout_data = [];
                if($routine_workout_list){
                    foreach ($routine_workout_list as $kk=> $workouts) {  
                        if($routine['pathway_id'] == 4 || $routine['pathway_id'] == 5){
                            if($workouts['week'] > $week_limit){
                                continue;
                            }
                        }
                        $workout_data[$kk]['title']          = $workouts['title'];  
                        $workout_data[$kk]['workout_title']  = $workouts['workout_title']; 
                        $RoutineWorkout = \common\models\RoutineWorkout::find()->joinWith(['routineWorkoutExercises'])
                                           ->where([
                                                    'week_no'=>$workouts['week'],
                                                    'day_no'=>$workouts['day'],
                                                    'routine_id'=>$routine_id
                                            ])
                                            ->andWhere('MONTH(FROM_UNIXTIME(routine_workout.workout_date))= MONTH(CURDATE()) AND YEAR(FROM_UNIXTIME(routine_workout.workout_date))= YEAR(CURDATE())')
                                            ->asArray()->one();
                                            
                        if($RoutineWorkout){
                            if($RoutineWorkout['status'] == 1){
                                $workout_data[$kk]['btn_to_show']  = "End Training";
                            }else if($RoutineWorkout['status'] == 2){
                                // continue;
                                $workout_data[$kk]['btn_to_show']  = 'Finished';
                            }else{
                                $workout_data[$kk]['btn_to_show']  = 'Start Training';
                            }
                            $routine_workout_id = $RoutineWorkout['id'];
                            $exe_status_list = \yii\helpers\ArrayHelper::map($RoutineWorkout['routineWorkoutExercises'],'exe_id','status');
                        }else{
                            $workout_data[$kk]['btn_to_show']  = 'Start Training';
                            $routine_workout_id = 0;
                            $exe_status_list = [];
                        }   
                        $workout_data[$kk]['routine_workout_id']  = $routine_workout_id;
                        foreach ($workouts['exe_list'] as $key => $exe) {
                            $workout_data[$kk]['exe_list'][$key] = $exe; 
                            if(isset($exe_status_list[$exe['exe_id']])){
                                $exe_status = $exe_status_list[$exe['exe_id']];
                            }else{
                                $exe_status = 0;
                            }
                           
                            if($exe_status == 0){
                                if($RoutineWorkout['status'] == 2){
                                    $workout_data[$kk]['exe_list'][$key]['status'] = "Not Performed";
                                }else{
                                    $workout_data[$kk]['exe_list'][$key]['status'] = "Start";
                                }
                            }else if($exe_status == 1){
                                $workout_data[$kk]['exe_list'][$key]['status'] = "Ongoing";
                            }else if($exe_status == 2){
                                $workout_data[$kk]['exe_list'][$key]['status'] = "Completed";
                            }
                        }
                    }
                    
                    
                }
                $data['title']            = $routine['day'].' Days '.$routine['pathway']['name'].' Routine';
                $data['workout_list']     = $workout_data;
                return[
                    'status'=>true,
                    'data'=>$data
                ];
        }else{
            return[
                'status'=>false,
                'message'=>'Invalid routine'
            ];
        }
    }
    // When click on start exercising btn
    public function actionSaveWorkoutExercises(){
        if(!empty($_POST['WorkoutExercises']['json'])){    
            $WorkoutExercises =  json_decode($_POST['WorkoutExercises']['json'],true);
            foreach ($WorkoutExercises as $key => $exe) {
                if($key == 0){
                    //Check anyWorkout is Started or Not ?
                    $RoutineWorkout =  \common\models\RoutineWorkout::find()
                    ->where(['routine_id'=>$exe['routine_id'],'status'=>1])
                    ->andWhere('MONTH(FROM_UNIXTIME(routine_workout.workout_date))
                    = MONTH(CURDATE()) AND YEAR(FROM_UNIXTIME(routine_workout.workout_date))= YEAR(CURDATE())')
                    ->one();
                    if($RoutineWorkout){
                        return [
                            'status'=>false,
                            'message'=>'Before you start new workout you have to finish your last workout.'
                        ];
                    }
                    $RoutineWorkout =  \common\models\RoutineWorkout::find()->where([
                                        'routine_id'=>$exe['routine_id'],
                                        'week_no'=>$exe['week_no'],
                                        'day_no'=>$exe['day'],
                                        ])
                                        ->andWhere(
                                            'MONTH(FROM_UNIXTIME(routine_workout.workout_date))= MONTH(CURDATE())
                                             AND YEAR(FROM_UNIXTIME(routine_workout.workout_date))= YEAR(CURDATE())
                                             ')
                                        ->one();
                    if($RoutineWorkout){
                        $RoutineWorkout->status            = 1;
                        if(!$RoutineWorkout->save()){
                            return [
                                'status'=>false,
                                'message'=>\Yii::$app->general->error($RoutineWorkout->errors)
                            ];
                        }
                        return['status'=>true];
                    }
                    $RoutineWorkout                    = new \common\models\RoutineWorkout;
                    $RoutineWorkout->routine_id        = $exe['routine_id'];
                    $RoutineWorkout->week_no           = $exe['week_no'];
                    $RoutineWorkout->day_no            = $exe['day'];
                    $RoutineWorkout->workout_date      = time();
                    $RoutineWorkout->status            = 1;
                    if($RoutineWorkout->save()){
                        $routine_workout_id = $RoutineWorkout->id;
                    }else{
                        return [
                            'status'=>false,
                            'message'=>\Yii::$app->general->error($RoutineWorkout->errors)
                        ];
                    }
                }
                $RoutineWorkoutExercises                        = new \common\models\RoutineWorkoutExercises;
                $RoutineWorkoutExercises->routine_workout_id    = $routine_workout_id;
                $RoutineWorkoutExercises->exe_id                = $exe['exe_id'];
                $RoutineWorkoutExercises->exe_category_id       = $exe['exe_category_id'];
                if($RoutineWorkoutExercises->save()){  
                    for($set = 0; $set < $exe['sets']; $set++){
                        $RoutineWorkoutExercisesSets                                     =  new \common\models\RoutineWorkoutExercisesSets;
                        $RoutineWorkoutExercisesSets->routine_workout_exercise_id        =  $RoutineWorkoutExercises->id;
                        $RoutineWorkoutExercisesSets->reps                               =  $exe['reps'];
                        $RoutineWorkoutExercisesSets->weight                             =  0;
                        $RoutineWorkoutExercisesSets->lifting_time                       =  $exe['lifting_time'];
                        $RoutineWorkoutExercisesSets->one_rm                             =  0;
                        $RoutineWorkoutExercisesSets->countdown_timer                    = $exe['coutdown_timer'];
                        $RoutineWorkoutExercisesSets->time_btn_exe                       = $exe['time_between_body_part'];
                        $RoutineWorkoutExercisesSets->time_btn_set                       = $exe['time_between_set'];
                        $RoutineWorkoutExercisesSets->no_sets                            = $exe['sets'];
                        if(!$RoutineWorkoutExercisesSets->save(false)){
                            return [
                                'status'=>false,
                                'message'=>\Yii::$app->general->error($RoutineWorkoutExercisesSets->errors)
                            ];
                        }
                    }
                }else{
                    return [
                        'status'=>false,
                        'message'=>\Yii::$app->general->error($RoutineWorkoutExercises->errors)
                    ];
                }
            }
            return[
                'status'=>true
            ];
        }else{
            return[
                'status'=>false,
                'message'=>'Json is Missing.'
            ];
        }
    } 
    //Start Workout 
    // To Display workout single exercise with sets.
    public function actionWorkoutExerciseDetail(){
        $model  = new \app\models\WorkoutForm;
        if ($model->load(Yii::$app->request->post()) && $model->validate()){ 
            $sql = "(SELECT id FROM `routine_workout` WHERE routine_id =".$model->routine_id." AND week_no = ".$model->week_no." AND day_no =".$model->day." AND MONTH(FROM_UNIXTIME(routine_workout.workout_date))= MONTH(CURDATE()) AND YEAR(FROM_UNIXTIME(routine_workout.workout_date))= YEAR(CURDATE()))";
            $RoutineWorkoutExercise = \common\models\RoutineWorkoutExercises::find()
            ->joinWith(['routineWorkoutExercisesSets','routineWorkout'])
            ->where('`routine_workout_id` IN '.$sql)
            ->andWhere(['exe_id'=>$model->exe_id,'exe_category_id'=>$model->exe_category_id])
            ->one(); 
            if($RoutineWorkoutExercise){
                $RoutineWorkoutExerciseOngoin                   = \common\models\RoutineWorkoutExercises::find()
                ->where(['routine_workout_id'=>$RoutineWorkoutExercise->routine_workout_id,'status'=>1])
                ->andWhere(['!=','id',$RoutineWorkoutExercise->id])
                ->one();

                if (!isset($_POST['no_save']) || empty($_POST['no_save'])) {
                    if($RoutineWorkoutExerciseOngoin){
                        $RoutineWorkoutExerciseOngoin->status =  2;
                        $RoutineWorkoutExerciseOngoin->save(false);
                    }
                    $RoutineWorkoutExercise->status   = 1;
                }
                
                if($RoutineWorkoutExercise->save()){
                    
                    $exercise                           =  \common\models\Exercise::find()
                                                            ->where(['id'=>$model->exe_id])->asArray()->one();

                    $exercise_data['workout_title']     =   'Day '.$model->day.' Week '.$model->week_no;
                    $exercise_data['workout_title_2']   =   'Workout-'.$model->day;
                    $exercise_data['id']                =   $exercise['id'];
                    $exercise_data['name']              =   $exercise['name'];
                    $exercise_data['images']            =   [
                                                                !empty($exercise['img'])?\yii\helpers\Url::base(true).'/img_assets/exercise/'.$exercise['img']:\yii\helpers\Url::base(true).'/img_assets/exercise/empty.jpg',
                                                                !empty($exercise['gif'])?\yii\helpers\Url::base(true).'/img_assets/exercise/'.$exercise['gif']:\yii\helpers\Url::base(true).'/img_assets/exercise/empty.jpg',
                                                            ];           
                    $exercise_data['routine_weight_unit']         = $model->routine_weight_unit;
                    $exercise_data['routine_workout_id']          = $RoutineWorkoutExercise->routineWorkout->id;
                    $exercise_data['routine_workout_exercise_id'] = $RoutineWorkoutExercise->id;
                    return [
                        'status'=>true,
                        'data'=>[
                            'exercise_data'=>$exercise_data,
                            'sets'=>$RoutineWorkoutExercise->routineWorkoutExercisesSets      
                        ]
                    ];
                }else{
                    return [
                        'status'=>false,
                        'message'=>\Yii::$app->general->error($RoutineWorkoutExercise->errors)
                    ];
                }
            }else{
                return [
                    'status'=>false,
                    'message'=>'Invalid Routine Exercise.',
                ];
            }            
        }else{
            return [
                'status'=>false,
                'message'=>\Yii::$app->general->error($model->errors)
            ];
        }

    } 
    //Remove Set
    public function actionRemoveSet($set_id){
        $set  = \common\models\RoutineWorkoutExercisesSets::find()->where(['id'=>$set_id])->one();
        if($set){ 
            $routine_workout_exercise_id =  $set->routine_workout_exercise_id;
            $set->delete();
            $sets  = \common\models\RoutineWorkoutExercisesSets::find()
            ->where(['routine_workout_exercise_id'=>$routine_workout_exercise_id])->all();
            return [
                'status'=>true,
                'data'=>$sets,
                'message'=>'Set Deleted.'
            ];
        }else{
            return [
                'status'=>false,
                'message'=>'Invalid set'
            ];
        }
    }
    //Add new set
    public function actionAddSet(){
            if(!empty($_POST['set'])){
                $RoutineWorkoutExercisesSets                   = new \common\models\RoutineWorkoutExercisesSets;
                $set                                           = json_decode($_POST['set'],true);
                foreach($set as $key =>$value){
                    if($key !="id"){
                        $RoutineWorkoutExercisesSets->$key =  $value;
                    }
                }
                if($RoutineWorkoutExercisesSets->save()){
                    $sets  = \common\models\RoutineWorkoutExercisesSets::find()
                    ->where(['routine_workout_exercise_id'=>$RoutineWorkoutExercisesSets->routine_workout_exercise_id])->all();
                    return [
                        'status'=>true,
                        'data'=>$sets,
                        'message'=>'Set Created.'
                    ];
                }else{
                    return [
                        'status'=>false,
                        'message'=>\Yii::$app->general->error($RoutineWorkoutExercisesSets->errors)
                    ];
                }
            }else{
                return [
                    'status'=>false,
                    'message'=>'Invalid set'
                ];
            }
    } 
     
    //Save Log
    public function actionLogSet($workout_time = 0){        
        $model  = new \app\models\LogForSet;
        if ($model->load(Yii::$app->request->post()) && $model->validate()){ 
            $set    = \common\models\RoutineWorkoutExercisesSets::find()->joinWith(['routineWorkoutExercise'])
            ->where(['routine_workout_exercises_sets.id'=>$model->set_id])->one();
            if($set){
                $set->reps          = $model->reps;
                $set->weight        = $model->weight;
                $set->lifting_time  = $model->lifting_time;
                $set->one_rm        = \Yii::$app->general->rm($model->weight,$model->reps);              
                if(empty($set->set_completed)){
                    $set->set_completed = isset($model->set_completed)?$model->set_completed:1;
                }
                if($set->save()){
                    $sets       = \common\models\RoutineWorkoutExercisesSets::find()
                                    ->where(['routine_workout_exercise_id'=>$set->routine_workout_exercise_id])
                                    ->all();
                    if(isset($model->set_completed) && $model->set_completed == 0){
                        return [
                            'status'=>true,
                            'data'=>[
                                'sets'=>$sets,
                                'rest_time'=>false,
                                'next_action'=>false,
                                'next_exe_id'=>false,
                                'end_workout'=>false
                            ],
                        ];
                    }
                    //Check is there any un complete set ?
                    $un_completed_set       = \common\models\RoutineWorkoutExercisesSets::find()
                    ->where(['routine_workout_exercise_id'=>$set->routine_workout_exercise_id,'set_completed'=>0])
                    ->count();
                    if($un_completed_set){
                        $action     = "next_set";
                        $rest_time  = $set->time_btn_set- $set->countdown_timer;
                        return [
                            'status'=>true,
                            'data'=>[
                                'sets'=>$sets,
                                'rest_time'=>$rest_time,
                                'next_action'=>$action,
                                'next_exe_id'=>false,
                                'end_workout'=>false
                            ],
                        ];
                    }else{
                       return $this->DoneExercise($set->routine_workout_exercise_id,$workout_time); 
                    }
                }else{
                    return [
                        'status'=>false,
                        'message'=>\Yii::$app->general->error($set->errors)
                    ];
                }
            }else{
                return [
                    'status'=>false,
                    'message'=>'Invalid set'
                ];
            }
        }else{
            return [
                'status'=>false,
                'message'=>\Yii::$app->general->error($model->errors)
            ];
        }
    }    
    //Done Exercise
    private function DoneExercise($routine_workout_exercise_id,$workout_time){        
        $RoutineWorkoutExercises            = \common\models\RoutineWorkoutExercises::find()
                                                ->joinWith(['routineWorkoutExercisesSets','routineWorkout'])
                                                ->where(['routine_workout_exercises.id'=>$routine_workout_exercise_id])->one();
        $RoutineWorkoutExercises->status    = 2;
        if($RoutineWorkoutExercises->save()){
            $un_completed_exe       = \common\models\RoutineWorkoutExercises::find()->joinWith(['exe'])
            ->where(['routine_workout_exercises.routine_workout_id'=>$RoutineWorkoutExercises->routineWorkout->id])
            ->andWhere(['!=','status',2])
            ->all();
            if(!empty($un_completed_exe)){ 
                $next_exercises = [];
                foreach($un_completed_exe as $k => $ele){
                    $ax['value']      =  $ele['exe']['id'];
                    $ax['label']    =  $ele['exe']['name'];
                    array_push($next_exercises,$ax);
                }
                return [
                    'status'=>true,
                    'data'=>[
                         'sets'=>[],
                         'rest_time'=>$RoutineWorkoutExercises->routineWorkoutExercisesSets[0]->time_btn_exe - $RoutineWorkoutExercises->routineWorkoutExercisesSets[0]->countdown_timer,
                         'next_action'=>'next_exe',
                         'next_exercises'=> $next_exercises,
                         'end_workout'=>false
                    ]
                ];
            }else{
                $end_workout =  $this->endWorkout(
                    $RoutineWorkoutExercises->routineWorkout->id, $workout_time
                ); 
                return [
                    'status'=>true,
                    'data'=>[
                        'sets'=>[],
                        'rest_time'=>0,
                        'next_action'=>'end_workout',
                        'next_exe_id'=>false,
                        'end_workout'=>$end_workout
                    ],
                ];
            }
            
        }else{
            return [
                'status'=>false,
                'message'=>\Yii::$app->general->error($RoutineWorkoutExercises->errors)
            ];
        }
    }   
    public function actionDoneExercise($routine_workout_exercise_id,$workout_time){ 
        return $this->DoneExercise($routine_workout_exercise_id,$workout_time);
    } 
    public function getTimeFromHMS($time){
        //$time    = '21:32:32';
        $seconds = 0;
        $parts   = explode(':', $time);

        if (count($parts) > 2) {
            $seconds += $parts[0] * 3600;
        }
        $seconds += $parts[1] * 60;
        $seconds += $parts[2];
        return $seconds;
    }
    // Finish Workout 
    private function endWorkout($routine_workout_id,$workout_time) {
        $RoutineWorkout         = \common\models\RoutineWorkout::find()
        ->where(['routine_workout.id'=>$routine_workout_id])
        ->joinWith(['routineWorkoutExercises','routine','ongoingExes'])->one(); 
        if($RoutineWorkout->ongoingExes){
            foreach($RoutineWorkout->ongoingExes as $ongoingExe){
                $ongoingExe->status=2;
                $ongoingExe->save();
            }
        }
        $workout_time = $this->getTimeFromHMS($workout_time);
        $RoutineWorkout->status = 2;
        $RoutineWorkout->workout_time = $RoutineWorkout->workout_time?$RoutineWorkout->workout_time+$workout_time:$workout_time;
        if($RoutineWorkout->save()){
            $workoutExeIds = \yii\helpers\ArrayHelper::getColumn($RoutineWorkout->routineWorkoutExercises,'id');
            $final_data = \common\models\RoutineWorkoutExercisesSets::find()->select(['SUM(reps) as total_reps','SUM(weight) as total_weight', 'SUM(lifting_time) as total_lifting_time'])
            ->where(['IN','routine_workout_exercise_id',$workoutExeIds])
            ->andWhere(['=','set_completed',1])->asArray()->one();
            $unit = $RoutineWorkout->routine->routine_weight_unit;
            return [
                'status'=>true,
                'message'=>'Your workout has been done',
                'data'=>[
                    'title_1'=>'Workout - '.Yii::$app->general->workoutMap($RoutineWorkout->day_no),
                    'title_2'=>'Day '.$RoutineWorkout->day_no.' Week '.$RoutineWorkout->week_no,
                    'total_weight'=>!empty($final_data['total_weight'])?$final_data['total_weight'].' '.$unit:"0",
                    'total_lifting_time'=>!empty($final_data['total_lifting_time'])?$final_data['total_lifting_time']:"0",
                    'total_reps'=>!empty($final_data['total_reps'])?$final_data['total_reps']:"0",
                ]
            ]; 
        }else{
            return [
                'status'=>false,
                'message'=>\Yii::$app->general->error($RoutineWorkout->errors)
            ];
        }
    }    
    public function actionDoneWorkout($routine_workout_id,$workout_time = 0){
       return $this->endWorkout($routine_workout_id,$workout_time);              
    }
    public function actionDeleteRoutine($routine_id){
        $model = \common\models\Routines::find()->where(['id'=>$routine_id,'user_id'=>\Yii::$app->user->id])->one();
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