<?php
namespace common\components;
use Yii;
use lajax\translatemanager\helpers\Language as Lx;

class General extends \yii\base\Component {
    public function templateForWelcomeEmail($email){
        $html =  Yii::$app->emailtemplate->replace_string_email([
            '{{name}}'=>$email,
             '{{link}}'=>\yii\helpers\Url::toRoute(['/site/login'],true)
        ] ,"welcome_email_for_trainee");
        $email =  Yii::$app->mailer->compose()
        ->setTo($email)
        ->setFrom([\Yii::$app->setting->val('senderEmail') => \Yii::$app->name])
        ->setSubject('TFT - Welcome to TFT.')
        ->setHtmlBody($html)->send();
    }
    public function sstrPlan($user_selected_sport_id,$user_selected_season,$user_selected_start){
      
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
    public function workoutMap($day){
        $d = [1=>'A',2=>'B','3'=>'C','4'=>'D','5'=>'E','6'=>'F','7'=>'G'];
        if(!empty($d[$day])){
            return $d[$day];
        }
        return $day;
    }
    public function rm($weight,$reps){
       $d  = $reps/30;       
       $d1 = ( $d +  1) * $weight;
       return (int)$d1;
    }
    public function loop($start,$end,$ended,$routine,$color){

        $new_array = [];$new_array2 = [];
        for($i=1;$i<=$ended;$i++){         
            $ax['value']    =  $i;
            $ax['label']    =  $i;         
            if($color){
                $ax['color']    =  $i >= $start && $i<=$end?'#4ec274':'#d23535';
            }
            if($i >= $start && $i<=$end){
                array_push($new_array,$ax);
            }else{
                array_push($new_array2,$ax);
            }
        }
       
        return array_merge($new_array,$new_array2);
    }
    public function loop2($start,$end,$ended,$routine,$color){

        $new_array = [];$new_array2 = [];
        for($i=1;$i<=$ended;$i++){         
            $ax['value']    =  $i;
            $ax['label']    =  $i >= $start && $i<=$end?$i:$i;           
            if($color){
                $ax['color']    =  $i >= $start && $i<=$end?'#4ec274':'#d23535';
            }
            if($i >= $start && $i<=$end){
                array_push($new_array,$ax);
            }else{
               array_push($new_array2,$ax);
            }
        }
       
        return array_merge($new_array,$new_array2);
    }
    public function dataFor($attribute,$routine,$routineName=""){
        $attributeValue = [
            'PoST'=>[
                'reps'=>[3,6],
                'lifting_time'=>[25,30],
                'time_between_set'=>[90,210],
                'coutdown_timer'=>[5,20],
                'time_between_body_part'=>[150,300]
            ],
            'SST'=>[
                'reps'=>[6,12],
                'lifting_time'=>[35,40],
                'time_between_set'=>[45,135],
                'coutdown_timer'=>[5,20],
                'time_between_body_part'=>[150,210]
            ],
            'PrST'=>[
                'reps'=>[3,20],
                'lifting_time'=>[45,55],
                'time_between_set'=>[30,120],
                'coutdown_timer'=>[5,20],
                'time_between_body_part'=>[90,150]
            ]
        ];
        if($routine == "PoST" || $routine == "PrST" || $routine == "SST" ){
            $rt = $attributeValue[$routine];
            $d = [            
                'reps'=>$this->loop2($rt['reps'][0],$rt['reps'][1],50,$routineName,1),
                'sets'=>$this->loop(1,20,20,$routineName,0),
                'lifting_time'=>$this->loop2($rt['lifting_time'][0],$rt['lifting_time'][1],100,$routineName,1),
                'time_between_set'=>$this->loop($rt['time_between_set'][0],$rt['time_between_set'][1],300,$routineName,1),
                'coutdown_timer'=>$this->loop($rt['coutdown_timer'][0],$rt['coutdown_timer'][1],50,$routineName,0),
                'time_between_body_part'=>$this->loop($rt['time_between_body_part'][0],$rt['time_between_body_part'][1],400,$routineName,0)            
            ];
        }else{
            $d = [            
                'reps'=>$this->loop(3,20,50,$routine),
                'sets'=>$this->loop(1,50,50,$routine),
                'lifting_time'=>$this->loop(20,60,100,$routine),
                'time_between_set'=>$this->loop(30,240,300,$routine),
                'coutdown_timer'=>$this->loop(5,20,50,$routine),
                'time_between_body_part'=>$this->loop(60,300,400,$routine)            
            ];
        }
        
        return $d[$attribute];
    }
    
    public function optionsForApp($array,$value_key,$label_key){
        $new_array = [];
        foreach($array as $k => $ele){
            $ax['value']    =  $ele[$value_key];
            $ax['label']    =  $ele[$label_key];
            array_push($new_array,$ax);
        }
        return $new_array;
    }
    public function optionsForAppDirect($array){
        $new_array = [];
        foreach($array as $k => $ele){            
            $ax['value']    =  $ele;
            $ax['label']    =  $ele;           
            array_push($new_array,$ax);
        }
        return $new_array;
    }
    public function routineUnit($routine_id){
        return \common\models\Routines::findOne($routine_id)->routine_weight_unit;
    }
    public function getWorkoutLog($user_id,$temp_stemp = 0)
    {
        $sql = 1;      
        if($temp_stemp){
            $beginOfDay = $temp_stemp;
            $endOfDay   = $beginOfDay+86400;   
            $sql = "routine_workout.workout_date >= ".$beginOfDay." AND routine_workout.workout_date <= ".$endOfDay;;
        }
        
        $WorkoutIds = \common\models\RoutineWorkout::find()->joinWith(['routine'])->where(
            'routine_id IN (SELECT routines.id FROM `routines` WHERE routines.user_id = '.$user_id.')'
        )->andWhere($sql)->asArray()->all();
        $routineArray = [];$WorkoutIdsArray=[];
        foreach($WorkoutIds as $element){
            array_push($WorkoutIdsArray,$element['id']);
            $routineArray[$element['routine_id']] = $element['routine'];
        }
        $query = \common\models\RoutineWorkoutExercises::find()
        ->joinWith(['routineWorkout','exe','exeCategory','routineWorkoutExercisesSets'])
        ->where(['IN','routine_workout_exercises.routine_workout_id',$WorkoutIdsArray])
        ->andwhere(['routine_workout_exercises_sets.set_completed'=>1])
        // ->andwhere(["!=",'routine_workout_exercises_sets.weight',0])
        ->orderBy('routine_workout_exercises.id DESC')
        ->asArray();

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
            foreach ($value['routineWorkoutExercisesSets'] as $k => $v) {
                # code...
                $data[$key]['exe']                = $value['exe']['name'];
                $data[$key]['exe_category']       = $value['exeCategory']['name'];
               
                $data[$key]['week_no']            = 'Week '.$value['routineWorkout']['week_no'];
                $data[$key]['workout_date']       =  date('d M Y',$value['routineWorkout']['workout_date']);
                $data[$key]['day']                = 'Day '.$value['routineWorkout']['day_no'];
                $data[$key]['workout_title']      = 'Workout '.$value['routineWorkout']['week_no'];
                $data[$key]['total_sets']         = sizeof($value['routineWorkoutExercisesSets']);
                $data[$key]['total_reps']         = array_sum(\yii\helpers\ArrayHelper::getColumn($value['routineWorkoutExercisesSets'],'reps'));
                $data[$key]['one_rm']             = max(\yii\helpers\ArrayHelper::getColumn($value['routineWorkoutExercisesSets'],'one_rm'));
                $data[$key]['total_weight']       = array_sum(\yii\helpers\ArrayHelper::getColumn($value['routineWorkoutExercisesSets'],'weight'));
                $data[$key]['total_lifting_time'] = array_sum(\yii\helpers\ArrayHelper::getColumn($value['routineWorkoutExercisesSets'],'lifting_time'));
                $data[$key]['completed_sets']     = $value['routineWorkoutExercisesSets'];
                if(isset($routineArray[$value['routineWorkout']['routine_id']])){
                    $routinData = $routineArray[$value['routineWorkout']['routine_id']];
                    $data[$key]['pathways_name']   = \common\models\Pathways::findOne(
                        ['id'=>$routinData['pathway_id']])->name;;
                    $data[$key]['routine_unit']    = $routinData['routine_weight_unit'];
                }else{
                    $data[$key]['pathways_name']   = "-";
                    $data[$key]['routine_unit']   = "-";
                }
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
   
    public function bmi($weight,$weight_unit,$height,$height_unit,$gender = "male",$return = "all"){
        if(!empty($weight) && !empty($height)){
            if($weight_unit == "kg"){
                $weight_in_kg = $weight;  
            }else{
                $weight_in_kg = $weight * 0.45359237;
            }

            if($height_unit == "cm"){
                $height_in_m = $height * 0.01;  
            }else{
                $height_in_m = $height * 0.0254;
            }
            $h =  ($height_in_m * $height_in_m);
            $bmi = $weight_in_kg / ($height_in_m * $height_in_m);
            $bmi =  number_format((float)$bmi, 2, '.', '');

            $height_in_cm =  $height_in_m * 100;
            $height_in_cm_2 =  $height_in_cm * $height_in_cm;

            $weight_in_kg_2 =  $weight_in_kg * $weight_in_kg;
            if($gender == "female"){
                $lbw = (1.07*$weight_in_kg) - (148 * ($weight_in_kg_2/$height_in_cm_2));
            }else{
                $lbw = (1.1*$weight_in_kg) - (128 * ($weight_in_kg_2/$height_in_cm_2));
            }
            $lbw =  number_format((float)$lbw, 2, '.', '');
        }else{
            $lbw = 0;
            $bmi = 0;
        }
        if($return == "all"){
            return[
                'lbw'=> $lbw,
                'bmi'=> $bmi
            ];
        }else if($return == "bmi"){
            return $bmi;
        }else if($return == "lbw"){
            return $lbw;
        }
        
    }   
    public function getUserFromAuth(){
        $request    = Yii::$app->request;
        $authHeader = $request->getHeaders()->get('Authorization');
        if ($authHeader == null && isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION']) && $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] != '') {
            $authHeader = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
        }
        if ($authHeader !== null && preg_match('/^Bearer\s+(.*?)$/', $authHeader, $matches)) {           
            $identity = \app\models\User::findIdentityByAccessToken($matches[1], get_class($this));   
           
            if ($identity === null) {
                return $this->handleFailure($response);
            }          
            return $identity;
        }
        return null;  
    }     
    public function get_country_name($country_id){
        $country = \common\models\AppsCountries::find()->select(['country_name'])->where(['id'=>$country_id])->asArray()->one();
        return !empty($country['country_name'])?$country['country_name']:"";
    }
    public function under18($dateOfBirth){
        $today = date("Y-m-d");
        $diff  = date_diff(date_create($dateOfBirth), date_create($today));
        $age   = $diff->format('%y');
        if($age > 18){
            return false;
        }
        return true;
    }
    public function get_age_cat($dateOfBirth){
        $today = date("Y-m-d");
        $diff  = date_diff(date_create($dateOfBirth), date_create($today));
        $age   = $diff->format('%y');
        return 'U'.$age;
    }
    public function error($errors)
    {
        foreach ($errors as $key => $value) {
            return $value[0];
        }
    }
    public function getLangData(){
        $Sql = "SELECT language_source.category,language_source.id,language_source.message FROM `language_source` WHERE `category` LIKE '%AppTxt%'";
        $data = \Yii::$app->db->createCommand($Sql)->queryAll();
        $d = [];
        foreach($data as $v){
            $result = explode('AppTxt_',$v['category']);
            $key    = $result[1]; 
            $d[$key]= Lx::t($v['category'],$v['message']);
        }
        return $d;
    }
    public function setPrice($price){
        $model = new  \common\models\VideoSearch();
        $p = $model->number_format_short($price);
        return '$ '.$p;
    }
    public function getVideoOwnerId($video_id){
        $video  = \common\models\Video::find()->where(['video_id'=>$video_id])->asArray()->one();
        if(!empty($video)){
            return $video['user_id'];
        }
        return 0;
    }
    public function country(){
        $country  = \yii\helpers\ArrayHelper::map(
            \common\models\AppsCountries::find()->where(['status'=>1])->asArray()->all(),'id','country_name');
        return $country;
    }
    public function getCountryId($country_code){
        $country  = \common\models\AppsCountries::find()->where(['country_code'=>strtolower($country_code),'status'=>1])->asArray()->one();
        if(!empty($country)){
            return $country['id'];
        }
        return \Yii::$app->params['default_country_id'];;
    }
    public function getFollowerAndFollowingCount($user){
        $data = [];
        if(!empty($user)){
            $getFollowerCount   = \common\models\FollowerFollowing::find()->where(['user_id' => $user])->
                                    innerJoin('user','user.id = follower_following.user_id AND user.status = 10')->asArray()->count();

            $getFollowingCount  = \common\models\FollowerFollowing::find()->where(['follower_id' => $user])->
                                    innerJoin('user','user.id = follower_following.follower_id AND user.status = 10')->asArray()->count();
            $getVideoCount      = \common\models\Video::find()->where(['user_id' => $user, 'video_status' => 1])->asArray()->count();
        } else {
            $getFollowerCount    = 0;
            $getFollowingCount   = 0; 
            $getWinnerCount      = 0;
            $getVideoCount       = 0;
        }

        $data['followers'] = $this->thousandsFormat($getFollowerCount);
        $data['following'] = $this->thousandsFormat($getFollowingCount);
        $data['videos'] = $this->thousandsFormat($getVideoCount);
     
        return $data;
    }
    public function player(){
        $data = \common\models\UserAdditionalInfo::find()->join('INNER JOIN','user','user.id = user_additional_info.user_id AND user.status = 10')->where(['user_additional_info.u_type'=>2])->asArray()->all();
        return \yii\helpers\ArrayHelper::map($data,'user_id','full_name');
    } 
    public function userList(){
        $data = \common\models\User::find()->where('user.status = 10')->asArray()->all();
        return \yii\helpers\ArrayHelper::map($data,'id','email');
    } 
    public function tag(){
        $data = \common\models\Tag::find()->where(1)->asArray()->all();
        return \yii\helpers\ArrayHelper::map($data,'id','tag_name');
    }  
    
    public function getUserDetails($user){
        //user details
        $userDetails = \common\models\UserData::find()->joinWith(['userAdditionalInfos'])->where(['user.id' => $user])->asArray()
        ->one();
        return $userDetails;
    }

    public function thousandsFormat($num){
        if($num > 1000) {
            $x = round($num);
            $x_number_format = number_format($x);
            $x_array = explode(',', $x_number_format);
            $x_parts = array('k', 'm', 'b', 't');
            $x_count_parts = count($x_array) - 1;
            $x_display = $x;
            $x_display = $x_array[0] . ((int) $x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');
            $x_display .= $x_parts[$x_count_parts - 1];
    
            return $x_display;
        }
        
        return $num;
    }
    public function tutorial_type(){
        return [0=>'Video Exercise',1=>'Highlight Match'];
    }   
    public function month(){
        return [1=>'01',2=>'02',3=>'03',4=>'04',5=>'05',6=>'06',7=>'07',8=>'08',9=>'09',10=>'10',11=>'11',12=>'12'];
    }
    public function year(){
        $currentYear =  date('Y');
        $year = [];
        for($i=2020;$i<=$currentYear;$i++){
            $year[$i]=$i;
        }
        return $year;
    }
}
?>