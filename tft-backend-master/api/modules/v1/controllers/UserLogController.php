<?php
namespace app\modules\v1\controllers;
use app\filters\auth\HttpBearerAuth;
use yii\web\UploadedFile;
use Yii;

use common\models\UserLog;
use common\models\UserLogBody;
use common\models\UserLogNote;
use common\models\UserLogPhoto;

use common\models\Pathways;
use common\models\Routines;

use common\models\Exercise;
use common\models\ExerciseCategory;

use yii\filters\AccessControl;
use yii\filters\auth\CompositeAuth;
use yii\helpers\Url;
use yii\rest\ActiveController;
use yii\web\BadRequestHttpException;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use yii\imagine\Image;

class UserLogController extends ActiveController
{
    public $modelClass = 'app\models\UserLog';

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
                'notes'  => ['get'],
                'photos' => ['get'],
                'body-stats' => ['get'],
                'workouts' => ['get'],
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
        //    '',
        ];
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'only' => ['create','delete-log','notes','stat-exe-list','photos','body-stats','workout-log','cardio-logs','graph-for-workout-time'], //only be applied to
            'rules' => [
               
                [
                    'allow' => true,
                    'actions' => ['create','delete-log','stat-exe-list','notes','photos','body-stats','workout-log','cardio-logs','graph-for-workout-time'],
                    'roles' => ['user'],
                ],
            ],
        ];
        return $behaviors;
    }
    public function actionDeletePhoto(){
        if(!empty($_POST['delete_images'])){
            $delete_images =  json_decode($_POST['delete_images'],true);
            foreach($delete_images as $img){
                $pathinfo = pathinfo($img);
                $img      = $pathinfo['filename'].'.'.$pathinfo['extension'];
                \common\models\UserPhotos::deleteAll(['user_id'=>\Yii::$app->user->id,'photo'=>$img]);
            }
        }
        return [
            'status'=>true,
        ];
    }
    public function actionCalendar($timeStamp=""){
        $timeStamp              = empty($timeStamp)?time():$timeStamp/1000;
        $start_date             = date('Y-m-01', $timeStamp);
        $last_date              = date('Y-m-t', $timeStamp);
    
        // Notes & Body State
        $logs = \common\models\UserLog::find()->select(['log_type','log_date'])->where(['user_id'=>\Yii::$app->user->id])
        ->andWhere(['AND',['>=','log_date',strtotime($start_date)],['<=','log_date',strtotime($last_date)]])
        ->groupBy('log_type,log_date')
        ->asArray()->all();
        //Strength Workout
        $user_id = \Yii::$app->user->id;
        $sql = 'SELECT "SW" AS `log_type`, `workout_date` AS `log_date` FROM `routine_workout` 
        WHERE (routine_id IN (SELECT routines.id FROM `routines` WHERE routines.user_id = '.$user_id.')) 
        AND ((`workout_date` >= '.strtotime($start_date).') AND (`workout_date` <= '.strtotime($last_date).')) AND ((`status`=1) 
        OR (`status`=2)) GROUP BY FROM_UNIXTIME(`workout_date`, "%d")';

        $workout = \Yii::$app->db->createCommand($sql)->queryAll();

        $sql = "user_cardio_routine_logs.created_at >= ".strtotime($start_date)." AND 
        user_cardio_routine_logs.created_at <= ".strtotime($last_date);
        
        $cardio_routines      = \common\models\UserCardioRoutine::find()
        ->select(['id'])->where(['user_id'=>Yii::$app->user->id])
        ->asArray()->all();
        $cardio_routines      = \yii\helpers\ArrayHelper::getColumn($cardio_routines,'id');

        $user_cardio_routine_logs =  \common\models\UserCardioRoutineLog::find()
        ->where(['IN','user_cardio_routine_id',$cardio_routines])
        ->andWhere(['is_close'=>1])
        ->andWhere($sql)
        ->groupBy('DATE(user_cardio_routine_logs.created_at)')
        ->orderBy('user_cardio_routine_logs.id DESC')->asArray()->all();

        $cardio = [];
        foreach($user_cardio_routine_logs as $l){
            array_push($cardio,array('log_type'=>'CW','log_date'=>$l['created_at']));
        }
        $logs = array_merge($logs,$workout,$cardio);
        $cal_data =[];
        foreach($logs as $log){
            $date = date("Y-m-d",$log['log_date']);
            if($log['log_type'] == "body"){
                $ele = array(
                    $date => ['key'=>'BS','color'=>'#ffbf00']
                );
            }else if($log['log_type'] == "notes"){
                $ele = array(
                    $date => ['key'=>'NT','color'=>'#ff3232']
                );
            }else if($log['log_type'] == "SW"){
                $ele = array(
                    $date =>['key'=>'SW','color'=>'#ff00ff']
                );
            }else if($log['log_type'] == "CW"){
                $ele = array(
                    $date =>['key'=>'CW','color'=>'#0040ff']
                );
            }
            array_push($cal_data,$ele);
        }
        foreach($cal_data as $log){
               $date = array_keys($log);
               $date = isset($date)?$date[0]:"";
               $data[$date]['dots'] = array_column($cal_data,$date);
        }


        // $data = [
        //     $start_date=>[
        //         'dots'=>[
        //             ['key'=>'SW','color'=>'#ff00ff'],
        //             ['key'=>'CW','color'=>'#0040ff'],
        //         ],
        //         'customStyles'=>[
        //             'container'=>['backgroundColor'=> 'green'],
        //             'text'=> [
        //                 'color'=> '#FFF',
        //                 'fontWeight'=> 'bold',
        //                 'textAlign'=> 'center',
        //             ],
        //         ]
        //     ],
        //     $start_date=>[
        //         'dots'=>[
        //             ['key'=>'NT','color'=>'#ff3232'],
        //             ['key'=>'BS','color'=>'#ffbf00'],
        //         ],
        //         'customStyles'=>[
        //             'container'=>['backgroundColor'=> 'green'],
        //             'text'=> [
        //                 'color'=> '#FFF',
        //                 'fontWeight'=> 'bold',
        //                 'textAlign'=> 'center',
        //             ],
        //         ]
        //     ],
        //     $last_date=>[
        //         'customStyles'=>[
        //             'container'=>['backgroundColor'=> '#0000','borderWidth'=>2,'borderColor'=>'#01B597'],
        //             'text'=> [
        //                 'color'=> '#01B597',
        //                 'fontWeight'=> 'bold',
        //                 'textAlign'=> 'center',
        //             ],
        //         ]
        //     ]
        // ];
        return [
            'status'=>true,
            'data'=>[
                'calendar'=>$data
            ]
        ];
    }
    public function actionPhotos()
    {
        $page       = isset($_GET['page']) && $_GET['page'] > 0 ? ($_GET['page'] - 1) : 0;
        $pageSize   = 10;
        $tag        = !empty($_GET['group_by'])&&$_GET['group_by']=="tag"?"tag":"created_at";
        $query      = \common\models\UserPhotos::find()->where(['user_id'=>\Yii::$app->user->id])
        ->groupBy($tag)->orderBy($tag.' DESC')->asArray();
        $provider   = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'pagination' => [               
                'page'              => $page,
                'pageParam'         => 'page',
                'defaultPageSize'   => $pageSize,
            ]
        ]);

        $models   = $provider->getModels();
        $dates = \yii\helpers\ArrayHelper::getColumn($provider->getModels(),$tag);
        $data = [];
        if($dates){
            foreach($dates as $index => $date){
                $photos = \common\models\UserPhotos::find()
                ->where(['user_id'=>\Yii::$app->user->id,$tag=>$date])
                ->asArray()->all();
                $images = [];
                foreach($photos as $i => $photo){

                    $d['id']  = $i+1;
                    $d['url'] = \yii\helpers\Url::to('img_assets/user_log/'.$photo['photo'], $schema = true);
                    array_push($images,$d);
                }
                $row = array('id'=>$index+1,'date'=>$tag == "tag"?empty($date)?"Others":$date:date("d M Y",strtotime($date)),'Images'=>$images);
                array_push($data,$row);
            }
        }
        $pagination = array_intersect_key(
            (array)$provider->pagination,
            array_flip(
                \Yii::$app->params['paginationParams']
            )
        );

        $totalPage                  = ceil($pagination['totalCount'] / $pageSize);
        $pagination['totalPage']    = $totalPage;
        $pagination['currentPage']  = !empty($_GET['page'])?$_GET['page']:1;
        $pagination['isMore']       = $totalPage <= $pagination['currentPage'] ? false:true;

        return [
            'status'=>true,
            'data'=> [
                'items' => $data,
                'pagination' => $pagination,
            ]
        ];
    }
    public function actionUploadPhoto(){
        $model              = new \common\models\UserPhotos;
        $model->user_id     = Yii::$app->user->id;
        $model->created_at  = date('Y-m-d');
        $model->photo       = UploadedFile::getInstance($model,'photo') ;
        if ($model->validate()){
            $model->load(Yii::$app->request->post());
            $path               = Yii::$app->basePath . '/../img_assets/user_log/';
            $thumb_image        = 'thumb_'.$model->photo->name;
            Image::thumbnail($model->photo->tempName, 600, 600)->save(Yii::getAlias($path.$thumb_image), ['quality' => 90]);
           
            $model->photo = $thumb_image;
            if($model->validate() &&  $model->save()){
                return [
                    'status'=>true,
                    'message' => 'Photo uploaded successfully.'
                ];
            }else{
                return [
                    'status'=>false,
                    'message'=>\Yii::$app->general->error($model->errors)
                ];
            }
        }else{
            return [
                'status'=>false,
                'message'=>\Yii::$app->general->error($model->errors)
            ];
        }
    }
    public function actionCreate()
    {
        $user_log          = new UserLog();
        $user_log->user_id = Yii::$app->user->id;
        if ($user_log->load(Yii::$app->request->post()) && $user_log->validate()){
            $user_log->log_date = strtotime($user_log->log_date);
            if(!$user_log->save()){
                return[
                    'status'=>false,
                    'message'=>'Unable to save logs'
                ];
            }
            $user_log_id        =  $user_log->id;
            if($user_log->log_type == "notes"){
                $user_notes                 = new UserLogNote;
                $user_notes->user_log_id    = $user_log_id;
                $user_notes->notes          = $user_log->notes;
                if($user_notes->validate()){
                    $user_notes->save(false);
                    return [
                        'status'=>true,
                        'message' => 'User Log Created'
                    ];
                }else{
                    return [
                        'status'=>false,
                        'message'=>\Yii::$app->general->error($user_notes->errors)
                    ];
                }

            }else if($user_log->log_type == "body"){
                $user_body                  = new UserLogBody;
                $user_body->user_log_id     = $user_log_id;
                $user_body->body_part       = $user_log->body_part;
                $user_body->value           = $user_log->value;
                $user_body->value_unit      = $user_log->value_unit;
                if($user_body->validate()){
                    $user_body->save(false);
                    return [
                        'status'=>true,
                        'message' => 'User Log Created'
                    ];
                }else{
                    return [
                        'status'=>false,
                        'message'=>\Yii::$app->general->error($user_body->errors)
                    ];
                }
            }

        }else{
            return [
                'status'=>false,
                'message'=>\Yii::$app->general->error($user_log->errors)
            ];
        }
    }
    public function actionDeleteLog($user_log_id){
        $UserLog = \common\models\UserLog::find()->where(['user_id'=>\Yii::$app->user->id,
        'id'=>$user_log_id])->one();
        if($UserLog && $UserLog->delete()){
            return[
                'status'=>true
            ];
        }else{
            return[
                'status'=>false,
                'message'=>'Unable to delete'
            ];
        }
    }
    public function actionNotes()
    {
        $timestamp = !empty($_GET['timestamp'])?$_GET['timestamp']:"";      
        $sql = 1;
        if($timestamp){
            $beginOfDay = strtotime($timestamp);
            $endOfDay   = $beginOfDay+86400;   
            $sql = "log_date >= ".$beginOfDay." AND  log_date < ".$endOfDay;
        }

        $query      = UserLogNote::find()
        ->joinWith([
            'userLog as u' => function($query){ 
                $query->select(['u.log_date','u.id']);
            }
        ])
        ->where(['u.user_id'=>Yii::$app->user->id])
        ->andWhere($sql)
        ->orderBy('user_log_id DESC')->asArray();
        
        $page       = isset($_GET['page']) && $_GET['page'] > 0 ? ($_GET['page'] - 1) : 0;
        $pageSize   = 20;

        $provider   = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'pagination' => [               
                'page'              => $page,
                'pageParam'         => 'page',
                'defaultPageSize'   => $pageSize,
            ]
        ]);

        $models   = $provider->getModels();
        $data = [];
        foreach ($models as $key => $value) {
            # code...
            foreach ($models[$key] as $k => $v) {
                # code...
                $data[$key]['id']    =  $models[$key]['id'];
                $data[$key]['user_log_id']    =  $models[$key]['userLog']['id'];
                $data[$key]['notes']   = $models[$key]['notes'];
                $data[$key]['log_date']   = date('d M Y',$models[$key]['userLog']['log_date']);
            } 
        }


        $pagination = array_intersect_key(
            (array)$provider->pagination,
            array_flip(
                \Yii::$app->params['paginationParams']
            )
        );

        $totalPage                  = ceil($pagination['totalCount'] / $pageSize);
        $pagination['totalPage']    = $totalPage;
        $pagination['currentPage']  = !empty($_GET['page'])?$_GET['page']:1;
        $pagination['isMore']       = $totalPage <= $pagination['currentPage'] ? false:true;

        return [
            'status'=>true,
            'data'=> [
                'items' => $data,
                'pagination' => $pagination,
            ]
        ];
    }
   
    public function actionBodyStats()
    {
        $timestamp = !empty($_GET['timestamp'])?$_GET['timestamp']:"";      
        $sql = 1;
        if($timestamp){
            $beginOfDay = strtotime($timestamp);
            $endOfDay   = $beginOfDay+86400;   
            $sql = "log_date >= ".$beginOfDay." AND  log_date < ".$endOfDay;
        }

        $query      = UserLogBody::find()
        ->joinWith([
            'userLog as u' => function($query){ 
                $query->select(['u.log_date','u.id']);
            }
        ])
        ->where(['u.user_id'=>Yii::$app->user->id])
        ->andWhere($sql)
        ->orderBy('user_log_id DESC')->asArray();
        
        $page       = isset($_GET['page']) && $_GET['page'] > 0 ? ($_GET['page'] - 1) : 0;
        $pageSize   = 20;

        $provider   = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'pagination' => [               
                'page'              => $page,
                'pageParam'         => 'page',
                'defaultPageSize'   => $pageSize,
            ]
        ]);

        $models   = $provider->getModels(); 
        $data = [];
        foreach ($models as $key => $value) {
            # code...
            foreach ($models[$key] as $k => $v) {
                # code...
                $data[$key]['id']           = $models[$key]['id'];
                $data[$key]['user_log_id']    =  $models[$key]['userLog']['id'];
                $data[$key]['body_part']    = $models[$key]['body_part'];
                $data[$key]['value']        = $models[$key]['value'];
                $data[$key]['value_unit']   = $models[$key]['value_unit'];
                $data[$key]['log_date']     = $models[$key]['userLog']['log_date'];
            } 
        }
        $pagination = array_intersect_key(
            (array)$provider->pagination,
            array_flip(
                \Yii::$app->params['paginationParams']
            )
        );

        $totalPage                  = ceil($pagination['totalCount'] / $pageSize);
        $pagination['totalPage']    = $totalPage;
        $pagination['currentPage']  = !empty($_GET['page'])?$_GET['page']:1;
        $pagination['isMore']       = $totalPage <= $pagination['currentPage'] ? false:true;

        return [
            'status'=>true,
            'data'=> [
                'items' => $data,
                'pagination' => $pagination,
            ]
        ];
    }
    public function actionCardioLogs()
    {
        $timestamp = !empty($_GET['timestamp'])?$_GET['timestamp']:"";      
        $sql = 1;
        if($timestamp){
            $beginOfDay = strtotime($timestamp);
            $endOfDay   = $beginOfDay+86400;   
            $sql = "user_cardio_routine_logs.created_at >= ".$beginOfDay." AND user_cardio_routine_logs.created_at < ".$endOfDay;
        }
        $cardio_routines      = \common\models\UserCardioRoutine::find()->select(['id'])->where(['user_id'=>Yii::$app->user->id])->asArray()->all();
        $cardio_routines      = \yii\helpers\ArrayHelper::getColumn($cardio_routines,'id');
        $user_cardio_routine_logs =  \common\models\UserCardioRoutineLog::find()
                                        ->joinWith(['exe','userCardioRoutine'])
        ->where(['IN','user_cardio_routine_id',$cardio_routines])
        ->andWhere(['is_close'=>1])
        ->andWhere($sql)
        ->orderBy('user_cardio_routine_logs.id DESC')->asArray();
        
        $page       = isset($_GET['page']) && $_GET['page'] > 0 ? ($_GET['page'] - 1) : 0;
        $pageSize   = 20;
        $provider   = new \yii\data\ActiveDataProvider([
            'query' => $user_cardio_routine_logs,
            'pagination' => [               
                'page'              => $page,
                'pageParam'         => 'page',
                'defaultPageSize'   => $pageSize,
            ]
        ]);

        $models   = $provider->getModels(); 
        $data = [];
        // foreach ($models[$key] as $k => $v) {
        //     $data[$key]['id']           = $models[$key]['id'];
        //     $data[$key]['body_part']    = $models[$key]['body_part'];
        //     $data[$key]['value']        = $models[$key]['value'];
        //     $data[$key]['value_unit']   = $models[$key]['value_unit'];
        //     $data[$key]['log_date']     = $models[$key]['userLog']['log_date'];
        // } 
        $pagination = array_intersect_key(
            (array)$provider->pagination,
            array_flip(
                \Yii::$app->params['paginationParams']
            )
        );

        $totalPage                  = ceil($pagination['totalCount'] / $pageSize);
        $pagination['totalPage']    = $totalPage;
        $pagination['currentPage']  = !empty($_GET['page'])?$_GET['page']:1;
        $pagination['isMore']       = $totalPage <= $pagination['currentPage'] ? false:true;

        return [
            'status'=>true,
            'data'=> [
                'items' => $models,
                'pagination' => $pagination,
            ]
        ];
    }
    public function actionWorkoutLog()
    {
        $timestamp = !empty($_GET['timestamp'])?strtotime($_GET['timestamp']):"";  
        return Yii::$app->general->getWorkoutLog(Yii::$app->user->id,$timestamp);
    }
    public function actionStatExeList(){
        $user_id = \Yii::$app->user->id;
        $WorkoutIds = \common\models\RoutineWorkout::find()->select(['id'])->where(
            'routine_id IN (SELECT routines.id FROM `routines` WHERE routines.user_id = '.$user_id.')'
        )->asArray()->all();
        $WorkoutIds = \yii\helpers\ArrayHelper::getColumn($WorkoutIds,'id');

        $data = \common\models\RoutineWorkoutExercises::find()
        ->joinWith(['exe','exeCategory'])
        ->where(['IN','routine_workout_exercises.routine_workout_id',$WorkoutIds])
        ->andWhere(['!=','status',0])
        ->orderBy('routine_workout_exercises.id DESC')
        ->asArray()->all();
        $exe_data = [];
        foreach($data as $k => $ele){
            $ax['value']    =  $ele['exe']['id'];
            $ax['label']    =  $ele['exe']['name'];
            array_push($exe_data,$ax);
        }
        $exe_datacat = [];
        foreach($data as $k => $ele){
            $ax['value']    =  $ele['exeCategory']['id'];
            $ax['label']    =  $ele['exeCategory']['name'];
            array_push($exe_datacat,$ax);
        }
        return[
            'status'=>true,
            'data'=>['exe_data'=>$exe_data,'exe_cate_data'=>$exe_datacat]
        ];
    }
    public function actionGraph($exe_id,$value="total_weight",$duration=1){

        $sql = 1;        $user_id = \Yii::$app->user->id;
        if($duration != "ALL"){
            $start_time = time()- ($duration * 30 * 86400);
            $end_time   = time();
            $sql = "routine_workout.workout_date >= ".$start_time." AND routine_workout.workout_date < ".$end_time;;
        }
        $user_unit = Yii::$app->user->identity->userAdditionalInfos->units_of_measurement=="lbs/in"?"lbs":"kg";
        $WorkoutIds = \common\models\RoutineWorkout::find()->joinWith(['routine'])->where(
            'routine_id IN (SELECT routines.id FROM `routines` WHERE routines.user_id = '.$user_id.' AND routines.routine_weight_unit = "'.$user_unit.'")'
        )->andWhere($sql)->asArray()->all();
        $routineArray = [];$WorkoutIdsArray=[];
        foreach($WorkoutIds as $element){
            array_push($WorkoutIdsArray,$element['id']);
            $routineArray[$element['routine_id']] = $element['routine'];
        }
        $WorkoutIdsArray =  implode(",",$WorkoutIdsArray); $data = [];
        if($WorkoutIdsArray){
            $sqlMain ="SELECT 
            MAX(one_rm) AS `one_rm`, 
            SUM(weight) AS `total_weight`, 
            SUM(reps) AS `total_reps`, 
            SUM(lifting_time) AS `total_lifting_time`, 
            routine_workout.workout_date 
            FROM `routine_workout_exercises` 
            LEFT JOIN `routine_workout` ON `routine_workout_exercises`.`routine_workout_id` = `routine_workout`.`id` 
            LEFT JOIN `routine_workout_exercises_sets` ON `routine_workout_exercises`.`id` = `routine_workout_exercises_sets`.`routine_workout_exercise_id` 
            WHERE (`routine_workout_exercises`.`routine_workout_id` IN (".$WorkoutIdsArray.")) 
                AND (`routine_workout_exercises_sets`.`set_completed`=1) 
                AND (`routine_workout_exercises`.`exe_id`='".$exe_id."') 
                GROUP BY DATE(FROM_UNIXTIME(routine_workout.workout_date))";


            $routine = Yii::$app->db->createCommand($sqlMain)->queryAll();
           
            foreach ($routine as $key => $v) {   
                $data['labels'][$key]   = date('d M',$v['workout_date']);
                $data['data'][0]['data'][$key]     = (int)$v[$value];
            }
        }
       
        
        $array = ['total_lifting_time'=>"sec",'total_reps'=>'reps','total_weight'=>$user_unit,'one_rm'=>''];
        $data['unit']  = $value == "total_weight"?$user_unit:$array[$value];
        return[
            'status'=>true,
            'data'=>$data
        ];
    }
    public function actionGraphBodyPart($exe_category_id,$value="total_weight",$duration=1){
        $sql = 1;        $user_id = \Yii::$app->user->id;
        if($duration != "ALL"){
            $start_time = time()- ($duration * 30 * 86400);
            $end_time   = time();
            $sql = "routine_workout.workout_date >= ".$start_time." AND routine_workout.workout_date < ".$end_time;;
        }
        $user_unit = Yii::$app->user->identity->userAdditionalInfos->units_of_measurement=="lbs/in"?"lbs":"kg";
        $WorkoutIds = \common\models\RoutineWorkout::find()->joinWith(['routine'])->where(
            'routine_id IN (SELECT routines.id FROM `routines` WHERE routines.user_id = '.$user_id.' AND routines.routine_weight_unit = "'.$user_unit.'")'
        )->andWhere($sql)->asArray()->all();
        $routineArray = [];$WorkoutIdsArray=[];
        foreach($WorkoutIds as $element){
            array_push($WorkoutIdsArray,$element['id']);
            $routineArray[$element['routine_id']] = $element['routine'];
        }
        $WorkoutIdsArray =  implode(",",$WorkoutIdsArray);      $data = [];
        if($WorkoutIdsArray){
            $sqlMain ="SELECT 
            MAX(one_rm) AS `one_rm`, 
            SUM(weight) AS `total_weight`, 
            SUM(reps) AS `total_reps`, 
            SUM(lifting_time) AS `total_lifting_time`, 
            routine_workout.workout_date 
            FROM `routine_workout_exercises` 
            LEFT JOIN `routine_workout` ON `routine_workout_exercises`.`routine_workout_id` = `routine_workout`.`id` 
            LEFT JOIN `routine_workout_exercises_sets` ON `routine_workout_exercises`.`id` = `routine_workout_exercises_sets`.`routine_workout_exercise_id` 
            WHERE (`routine_workout_exercises`.`routine_workout_id` IN (".$WorkoutIdsArray.")) 
                AND (`routine_workout_exercises_sets`.`set_completed`=1) 
                AND (`routine_workout_exercises`.`exe_category_id`='".$exe_category_id."') 
                GROUP BY DATE(FROM_UNIXTIME(routine_workout.workout_date))";
                
            $routine = Yii::$app->db->createCommand($sqlMain)->queryAll();
      
            foreach ($routine as $key => $v) {   
                $data['labels'][$key]   = date('d M',$v['workout_date']);
                $data['data'][0]['data'][$key]     = (int)$v[$value];
            }
        }
        
        $array = ['total_lifting_time'=>"sec",'total_reps'=>'reps','total_weight'=>$user_unit,'one_rm'=>''];
        $data['unit']  = $value == "total_weight"?$user_unit:$array[$value];
        return[
            'status'=>true,
            'data'=>$data
        ];
    }
    public function actionGraphForWorkoutTime($type,$duration =1){        
        $user_id = \Yii::$app->user->id;
       
        $data = [];
        if($type == "Strength" || $type == "Strengh"){
            $date_sql = 1;
            $user_id = \Yii::$app->user->id;
            if($duration != "ALL"){
                $start_time = time()- ($duration * 30 * 86400);
                $end_time   = time();
                $date_sql = '`workout_date` < '.$end_time.' AND workout_date > '.$start_time;
            } 
            $routine = \common\models\RoutineWorkout::find()
                        ->select(['SUM(workout_time) as total_workout_time','workout_date'])
                        ->where(['status'=>2])
                        ->andWhere('routine_id IN (SELECT routines.id FROM `routines` 
                        WHERE routines.user_id = '.$user_id.')')
                        ->groupBy('DATE(FROM_UNIXTIME(workout_date))')->asArray()->all();  
                        
        }else{    
            $date_sql = 1;
            if($duration != "ALL"){
                $start_time = time()- ($duration * 30 * 86400);
                $end_time   = time();
                $date_sql = 'user_cardio_routine_logs.created_at < '.$end_time.' AND user_cardio_routine_logs.created_at > '.$start_time;
            }         
            $routine = \common\models\UserCardioRoutineLog::find()->select(['SUM(workout_time) as total_workout_time','user_cardio_routine_logs.created_at'])
            ->join('INNER JOIN','user_cardio_routine','user_cardio_routine_logs.user_cardio_routine_id = user_cardio_routine.id AND user_cardio_routine.user_id = '.\Yii::$app->user->id)
            ->where($date_sql)->andWhere(['is_close'=>1])
            ->groupBy('DATE(FROM_UNIXTIME(user_cardio_routine_logs.created_at))')->asArray()->all();  
           
        }
        foreach ($routine as $key => $v) {   
            if($type == "Strength" || $type == "Strengh"){       
                $data['labels'][$key]   = date('d M',$v['workout_date']);
            }else{
                $data['labels'][$key]   = date('d M',$v['created_at']);
            }
            $data['data'][0]['data'][$key]     = (int)$v['total_workout_time'];
        } 
      
        return[
            'status'=>true,
            'data'=>$data
        ];
    }
    public function actionGraphBodyStat($attribute="Weight",$duration=1){

        $user_id = \Yii::$app->user->id;
        $date_sql = 1;
        if($duration != "ALL"){
            $start_time = time()- ($duration * 30 * 86400);
            $end_time   = time();
            $date_sql = '`log_date` < '.$end_time.' AND log_date > '.$start_time;
        }

        $routine = \common\models\UserLog::find()
        ->select(['user_log_body.value','log_date','user_log_body.value_unit'])
        ->join('INNER JOIN','user_log_body','user_log.id = user_log_body.user_log_id')
        ->where(['user_id'=>\Yii::$app->user->id,
        'user_log.log_type'=>'body',
        'user_log_body.body_part'=>$attribute])
        ->andWhere($date_sql)
        ->groupBy('DATE(FROM_UNIXTIME(log_date))')->asArray()->all();        
        $data = [];

      

        foreach ($routine as $key => $v) {     
            $data['labels'][$key]               = date('d M',$v['log_date']);
            $data['data'][0]['data'][$key]      = (int)$v['value'];
            $data['unit']                       = $v['value_unit'];
        }
        return[
            'status'=>true,
            'data'=>$data
        ];
    }
}
?>