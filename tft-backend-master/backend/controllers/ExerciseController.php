<?php

namespace backend\controllers;

use Yii;
use common\models\Exercise;
use common\models\ExerciseUpdate;
use common\models\ExerciseSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\ImageForm;
use yii\imagine\Image;
use yii\web\UploadedFile;

/**
 * ExerciseController implements the CRUD actions for Exercise model.
 */
class ExerciseController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }
    public function actionOne(){
        $file = Yii::$app->basePath.'/../img_assets/csv/RoutineExeList.csv';  
        if(file_exists($file)){
            $sports = array_map('str_getcsv', file($file));
            //unset($sports[0]);
        }
        $header = $sports[0];
        $sports = array_map('str_getcsv', file($file));
        unset($sports[0]);
        $cat_id = [0=>5,1=>8,2=>15,3=>13,4=>17,5=>4,6=>10,7=>3,8=>1,9=>14,10=>16];
        foreach($sports as $exes){
            foreach($exes as $k =>$exe){
                $category_id = $cat_id[$k];
                if($exe != ""){
                    $exeModel         = \common\models\Exercise::find()->where(['name'=>$exe])->one();
                    if(empty($exeModel)){
                        $exeModel        =  new \common\models\Exercise;
                        $exeModel->name  =  $exe;
                        $exeModel->exe_category_id  =  $category_id;
                    }
                    $exeModel->for_routine = 1;
                    if($exeModel->save()){

                    }else{
                        print_r($exeModel->errors);die;
                    }
                }
            }
        }
        
        
    }
    public function actionOne1(){
        $file = Yii::$app->basePath.'/../img_assets/csv/ssstr/exe.csv';  
        if(file_exists($file)){
            $sports = array_map('str_getcsv', file($file));
            unset($sports[0]);
        }
        foreach($sports as $k=>$sport){
           
            $sport_data      = \common\models\Sports::find()->where(['=','name',$sport[0]])->one();
            $exe             = \common\models\Exercise::find()->where(['=','name',$sport[2]])->one();
            if($sport[0] == "Cricket"){
                $model           = new \common\models\SportExe;
                $model->sport_id = $sport_data->id;
                $model->exe_id   = $exe->id;
                $model->season   = $sport[1];
                $model->save(false);
            }
            
        }
        
    }
    // public function actionSportImport(){
    //     $day                 =  2;      
    //     $user_selected_sport =  "Basketball";
    //     $file = Yii::$app->basePath.'/../img_assets/csv/sports/'.$day.'-day-sport.csv';  
    //     if(file_exists($file)){
    //         $sports = array_map('str_getcsv', file($file));
    //         unset($sports[0]);
    //         $data = [];
           
    //         foreach($sports as $sport){ 
    //             $sport_name  =  $sport[0];
    //             if($user_selected_sport != $sport_name){
    //                 continue;
    //             }
    //             unset($sport[0]);          
    //             $sport_weeks = array_chunk($sport,$day);


    //             // $sport_data              = \common\models\Sports::find()->where(['=','name',$sport_name])->one();
    //             // $routines                =  new \common\models\Routines;
    //             // $routines->pathway_id    =  4;
    //             // $routines->created_at    =  time();
    //             // $routines->time_between_last_sets    =  240;
    //             // $routines->day           =  $day;
    //             // $routines->sport_id      =  $sport_data->id;;
    //             // $routines->user_id       =  Yii::$app->user->id;
    //             // if($routines->save(false)){ 
                    
    //                 $j = 0;      
    //                 $four_weeks = array_chunk($sport_weeks,4);
    //                 foreach($four_weeks as $sport_weeks){                      
    //                     foreach($sport_weeks as $week_no => $week_days){
    //                         $week_no = $week_no+1;            
    //                         $pre_routine_array = ['PoST'=>'1','SST'=>'2','PrST'=>'3']; 
    //                         foreach($week_days as $week_day => $week_day_routine){
    //                             $data[$j]['week_no'] =  $week_no;   
    //                             $week_day =  $week_day+1;
    //                             $path_id  =  $pre_routine_array[$week_day_routine];
    //                             $routine_id  = '(SELECT id FROM `routines` WHERE pathway_id =  '.$path_id.' AND day = '.$day.')';
    //                             $routine_weeks  = \common\models\RoutinesWeeks::find()
    //                             ->joinWith(['exerciseName','bodyPartName'])
    //                             ->where('routine_id IN '.$routine_id)->andWhere(['day'=>$week_day,'week_no'=>$week_no])->asArray()->all();; 
    //                             $data[$j]['week_day']           =  $week_day;   
    //                             $data[$j]['week_day_exercises'] =  $routine_weeks;  
    //                             $j++;
    //                         } 
    //                     }
    //                 }
    //                 print_r($data);die;   
    //             //}               
    //         }
    //     }

    // }
    // public function actionSportImport1(){
    //     $file = Yii::$app->basePath.'/../img_assets/csv/sports/2-day-sport.csv';  
    //     if(file_exists($file)){
    //         $array = array_map('str_getcsv', file($file));
    //         unset($array[0]);
    //         foreach($array as $sport){                
    //             $sportd = \common\models\Sports::find()->where(['=','name',$sport[0]])->one();
    //             $routines                =  new \common\models\Routines;
    //             $routines->pathway_id    =  4;
    //             $routines->created_at    =  time();
    //             $routines->time_between_last_sets    =  240;
    //             $routines->day           =  2;
    //             $routines->sport_id      =  $sportd->id;;
    //             if($routines->save(false)){ 
    //                 foreach($sport as $k=>$r){
    //                     if($k > 0){                           
    //                         $rr = ['PoST'=>'1','SST'=>'2','PrST'=>'3'];
    //                         if($rr[$r]){
    //                             $path_id = $rr[$r];
    //                             $myroutine  = \common\models\Routines::find()->where(['pathway_id'=>$path_id,'routines.day'=>$routines->day])->joinWith(['routinesWeeks'])->one();               
    //                             foreach($myroutine->routinesWeeks as $week ){
    //                                 $routine_week               =  new \common\models\RoutinesWeeks;
    //                                 $routine_week->attributes   =  $week->attributes;
    //                                 $routine_week->routine_id   =  $routines->id;
    //                                 if($routine_week->save(false)){
    //                                     $RoutinesWeeksSets = \common\models\RoutinesWeeksSets::find()->where(['routine_week_id'=>$week->id])->all();
    //                                     foreach ($RoutinesWeeksSets as $set_no => $set) {
    //                                         $routine_week_sets                      =  new \common\models\RoutinesWeeksSets;
    //                                         $routine_week_sets->attributes          =  $set->attributes;                       
    //                                         $routine_week_sets->routine_week_id     =  $routine_week->id;                     
    //                                         $routine_week_sets->save(false);
    //                                     }  
    //                                }
    //                             }               

    //                         }
    //                     }
    //                 } 
    //             }               
    //         }
    //     }

    // }
    public function actionImport(){
        $file   = Yii::$app->basePath.'/../img_assets/csv/';
        $files  = scandir($file);
        if($files){
            unset($files[0]);
            unset($files[1]);  
            foreach($files as $f){ 
                $f1  =  explode(".",$f);
                $f11 =  explode("-",$f1[0]);               
                $f1 =  $f11[0];   
                $dayy =  $f11[1];                
                $pathways = \common\models\Pathways::find()->where(['LIKE','name',$f1])->asArray()->one();

                $routines                =  new \common\models\Routines;
                $routines->pathway_id    =  $pathways['id'];
                $routines->created_at    =  time();
                $routines->time_between_last_sets    =  240;
                $routines->day    =  $dayy;
                if($routines->save(false)){                
                    $file = Yii::$app->basePath.'/../img_assets/csv/'.$f;  
                    if(file_exists($file)){
                        $array = array_map('str_getcsv', file($file));
                        $header = $array[0];
                        unset($array[0]);
                        $rows   = $array;
                        $r_id   = $routines->id;
                            $i  =  1;
                            foreach ($rows as $row) {
                                // Week No
                                if($row[0]){ 
                                    if($row[0] == "Week 1"){
                                        $week_no = 1;
                                    }else if($row[0] == "Week 2"){
                                        $week_no = 2;
                                    }else if($row[0] == "Week 3"){
                                        $week_no = 3;
                                    }else if($row[0] == "Week 4"){
                                        $week_no = 4;
                                    }
                                }
                                // Day No
                                if($row[1]){ 
                                    if($row[1] == "A"){
                                        $day_no = 1;
                                    }else if($row[1] == "B"){
                                        $day_no = 2;
                                    }else if($row[1] == "C"){
                                        $day_no = 3;
                                    }else if($row[1] == "D"){
                                        $day_no = 4;
                                    }
                                }
                                // Exe & Exe Category
                                if($row[4]){ 
                                    $exe = $row[4];
                                    $exe_row = \common\models\Exercise::find()->where(['=','name',$exe])->asArray()->one();
                                }
                                $routine_week               =  new \common\models\RoutinesWeeks;
                                $routine_week->week_no      =  $week_no;
                                $routine_week->day          =  $day_no;
                                $routine_week->seq_no       =  $i;
                                $routine_week->exe_id       =  $exe_row['id'];
                                $routine_week->exe_category_id   =  $exe_row['exe_category_id'];
                                $routine_week->routine_id   =  $r_id;
                                if($routine_week->save(false)){
                                    $routine_sets[0]['reps']                = $row[6];
                                    $routine_sets[0]['lifting_time']        = $row[8];
                                    $routine_sets[0]['time_between_set']    = $row[10];
                                    $routine_sets[0]['time_unit_countdown'] = !empty($row[11])?$row[11]:0;
                                    $routine_sets[0]['coutdown_timer']      = !empty($row[12])?$row[12]:0;

                                    $routine_sets[1]['reps']                = $row[13];
                                    $routine_sets[1]['lifting_time']        = $row[15];
                                    $routine_sets[1]['time_between_set']    = $row[17];
                                    $routine_sets[1]['time_unit_countdown'] = !empty($row[18])?$row[18]:0;
                                    $routine_sets[1]['coutdown_timer']      = !empty($row[19])?$row[19]:0;

                                    $routine_sets[2]['reps']                = $row[20];
                                    $routine_sets[2]['lifting_time']        = $row[22];
                                    $routine_sets[2]['time_between_set']    = $row[24];
                                    $routine_sets[2]['time_unit_countdown'] = !empty($row[25])?$row[25]:0;
                                    $routine_sets[2]['coutdown_timer']      = !empty($row[26])?$row[26]:0;

                                    $routine_sets[3]['reps']                = $row[27];
                                    $routine_sets[3]['lifting_time']        = $row[29];
                                    $routine_sets[3]['time_between_set']    = $row[31];
                                    $routine_sets[3]['time_unit_countdown'] = !empty($row[32])?$row[32]:0;
                                    $routine_sets[3]['coutdown_timer']      = !empty($row[33])?$row[33]:0;

                                    $routine_sets[4]['reps']                = $row[34];
                                    $routine_sets[4]['lifting_time']        = $row[36];
                                    $routine_sets[4]['time_between_set']    = $row[38];
                                    $routine_sets[4]['time_unit_countdown'] = !empty($row[39])?$row[39]:0;
                                    $routine_sets[4]['coutdown_timer']      = !empty($row[40])?$row[40]:0;

                                    foreach ($routine_sets as $set_no => $set) {
                                        $routine_week_sets                      =  new \common\models\RoutinesWeeksSets;                           
                                        $routine_week_sets->routine_week_id     =  $routine_week->id;
                                        $routine_week_sets->set_no              =  $set_no+1;
                                        $routine_week_sets->reps                =  $set['reps'];
                                        $routine_week_sets->weight              =  0;
                                        $routine_week_sets->lifting_time        =  $set['lifting_time'];
                                        $routine_week_sets->time_unit_countdown =  $set['time_unit_countdown'];
                                        $routine_week_sets->coutdown_timer      =  $set['coutdown_timer'];
                                        $routine_week_sets->time_between_set    =  $set['time_between_set'];                          
                                        $routine_week_sets->save(false);
                                    }                            
                                }
                                $i++;
                            }
                    }else{
                        echo $f;die;
                    }
                }
            }
        }
    }
    /**
     * Lists all Exercise models.
     * @return mixed
     */
    public function actionIndex()
    {
       
        $searchModel = new ExerciseSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Exercise model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Exercise model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Exercise();
        // $model->scenario = 'scenariocreate';
        $image = UploadedFile::getInstance($model,'image');
        $GIF  = UploadedFile::getInstance($model,'GIF');
        $BasePath = Yii::$app->basePath . '/../img_assets/exercise/';
        
        if ($model->load(Yii::$app->request->post()))
        {
            if($image){
                $filename           =  time().'-'.$image->baseName . '.' . $image->extension;
                $thumb_image        = 'thumb_'.$filename;
                if($image->saveAs($BasePath.$filename)){
                    Image::thumbnail($BasePath.$filename, 600, 600)->save(Yii::getAlias($BasePath.$thumb_image), ['quality' => 90]);
                    $model->img     = $thumb_image;
                }
            }
            else{
                $model->img     = "";
            }

            if($GIF){
                $filename       =  time().'-'.$GIF->baseName . '.' . $GIF->extension;
                $thumb_gif      = 'thumb_'.$filename;
                $model->gif     = $thumb_gif; 
            }
            else{
                $model->gif     = "";
            }

            if($model->save(false))
            // if(1)
            {
                // return $this->redirect(['view', 'id' => $model->id]);
                return $this->redirect(['index']);
            }
            else{
                print_r($model->errors);
                die;
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Exercise model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'scenarioupdate';
        $image = UploadedFile::getInstance($model,'image');
        $GIF  = UploadedFile::getInstance($model,'GIF');
        if ($model->load(Yii::$app->request->post())) { 

            $path = Yii::$app->basePath . '/../img_assets/exercise/';
            if($image){
                $image->saveAs($path.$image->name);
                $model->img = $image->name;
                // $filename           =  time().'-'.$image->baseName . '.' . $image->extension;
                // $thumb_image        = 'thumb_'.$filename;
                // if($image->saveAs($path.$filename)){
                //     Image::thumbnail($path.$filename, 600, 600)->save(Yii::getAlias($path.$thumb_image), ['quality' => 90]);
                //     $model->img     = $thumb_image;
                // }
            }
            if($GIF){
                $GIF->saveAs($path.$GIF->name);
                $model->gif = $GIF->name;
            }
            if($model->save(false))
            {
                // return $this->redirect(['view', 'id' => $model->id]);
                return $this->redirect(['index']);
            }
            else{
                print_r($model->errors);
                die;
            }
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Exercise model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $path = Yii::$app->basePath . '/../img_assets/exercise/';
        // unlink('');
        // $this->findModel($id)->delete();
        // return $this->redirect(['index']);
        $tempModel = $this->findModel($id);
        // unlink($path.$tempModel->img);
        // unlink($path.$tempModel->gif);
        $tempModel->delete();
        // return $this->redirect(['index']);
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Finds the Exercise model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Exercise the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ExerciseUpdate::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
