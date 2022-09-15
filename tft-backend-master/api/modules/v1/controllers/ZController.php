<?php
namespace app\modules\v1\controllers;
use app\filters\auth\HttpBearerAuth;
use common\models\UserData;
use common\models\Video;
use Yii;
use yii\filters\auth\CompositeAuth;
use yii\rest\ActiveController;
ini_set('memory_limit', '-1');
class ZController extends ActiveController
{
    public $modelClass = '';
    

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
                'index' => ['get'],  
                'add-video' => ['post'],  
                'add-vote'=> ['get'], 
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
            'options',
            'index',
            'users',
            'comments',
            'add-video',
            'add-vote',
            'video',
            'team','add-player'
        ];
        return $behaviors;
    }  
    public function curl($url,$post){
       
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        
        // execute!
        $response = curl_exec($ch);
        // close the connection, release resources used
        curl_close($ch);
        return $response;
        

    }
    public function actionAddPlayer(){
        $userIds = array_keys(\Yii::$app->general->player());
        foreach($userIds as $id){
            $model               = new \common\models\ScouterPlayer();
            $model->scouter_id   = "20418";
            $model->player_id    = $id;
            $model->save(false);
        }
    }
    public function actionTeam(){
        $userIds = array_keys(\Yii::$app->general->player());
        $types =  ['Linebacker','Corner Back 1','Corner Back 2','Running Back','Goal Keeper','Center Midfielder 1','Center Midfielder 2','Left Midfielder','Right Midfielder','Center Forward 1','Center Forward 2'];
        foreach($types as $player_type){
            $model              = new \common\models\DreamTeam();
            $model->month       = "04";
            $model->year        = 2020;
            $model->team_for    = 0;
            $model->country_id     = 106;
            $model->user_id     = $this->arrayVal($userIds);
            $model->player_type = $player_type;
            $model->save(false);
        }
      
    }
    public function arrayVal($array){
        $k =  array_rand($array);;
        return $array[$k];
    }
    private function addTag($model){
        $str = $model->description;
        preg_match_all('/#([^\s]+)/', $str, $matches);  
        if($matches[1]){
            foreach($matches[1] as $tag){
                $tag_id = "";
                $tagRecord = \common\models\Tag::find()->where(['tag_name'=>$tag])->one();
                if(empty($tagRecord)){
                    $tagModel           = new \common\models\Tag;
                    $tagModel->tag_name = $tag;
                    if($tagModel->save()){
                        $tag_id = $tagModel->id;
                    }
                }else{
                    $tag_id = $tagRecord->id;
                }
                if($tag_id){
                    $videoTagRecord = \common\models\VideoTag::find()->where(['video_id'=>$model->video_id,'tag_id'=>$tag_id])->one();;
                    if(!$videoTagRecord){
                        $videoTagModel            = new \common\models\VideoTag;
                        $videoTagModel->video_id  = $model->video_id;
                        $videoTagModel->tag_id    = $tag_id;
                        $videoTagModel->save();
                    }
                }
            }
        }
    }    
    public function actionAddVote(){ 
        $userIds = \yii\helpers\ArrayHelper::getColumn(\common\models\UserData::find()->all(),'id');
        $videoIds = \yii\helpers\ArrayHelper::getColumn(\common\models\Video::find()->all(),'video_id');
        for($i=0;$i<2000;$i++){
                $model           = new \common\models\VideoRate();
                $model->user_id  = $this->arrayVal($userIds);
                $model->video_id = $this->arrayVal($videoIds);
                $model->rate     = 1;
                $VideoVote = \common\models\VideoRate::find()->where(['user_id'=>$model->user_id,'video_id'=>$model->video_id])->one();
                if($VideoVote){
                   continue;            
                }
                if($model->save()){
                    // Add score to ranking table
                    $videoAutherId         = \Yii::$app->general->getVideoOwnerId($model->video_id);
                    $month                 = 1;
                    $year                  = (int)date('Y');
                    // $country_id            = \common\models\UserAdditionalInfo::find()->where(['user_id'=>$model->user_id])->one()->country_id;

                    $videoRankingRecord    = \common\models\Ranking::find()->where(['user_id'=>$videoAutherId,'month'=>$month,'year'=>$year,'country_id'=>106])->one();
                    if($videoRankingRecord){
                        $videoRankingRecord->total_score = $videoRankingRecord->total_score + $model->rate;
                    }else{
                        $videoRankingRecord              = new \common\models\Ranking;
                        $videoRankingRecord->user_id     = $videoAutherId;
                        $videoRankingRecord->month       = $month;
                        $videoRankingRecord->year        = $year;
                        $videoRankingRecord->country_id  = 106;
                        $videoRankingRecord->total_score = $videoRankingRecord->total_score + $model->rate;
                    }
                    if($videoRankingRecord->save()){

                    }
                
                }else{
                    continue; 
                }                    
                
        }  
    }
    public function actionAddVideo() {
        $model              = new Video();      
        $model->month       = (int)date('n');      
        $model->year        = (int)date('Y'); 
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {  
            $model->save();
            $this->addTag($model);
            return array('status'=>true);      
            // $model->video_url   =  UploadedFile::getInstance($model, 'video_url');          
            // return $this->video_pre_requirement($model);             
        }else{
            return array('status'=>false,'message'=>Yii::$app->general->error($model->errors));
        }
    }
    public function generateComment(){
        $pronoun = array(
            "I'm",
            "You're",
            "He's",
            "She's",
            "They're"
           );           
           $action = array(
            "stacking",
            "overflowing",
            "confused",
            "bewildered",
            "wondering how many more of these I can make up",
            "getting bored... So that's enough for now..."
        );
        return $pronoun[array_rand($pronoun)] . ' ' . $action[array_rand($action)];
    }
    public function actionIndex(){ 
        // $Followers           = \common\models\FollowerFollowing::find()->select(['follower_following.follower_id'])
        // ->where(['user_id'=>148,'is_follow'=>1])->asArray()->all();
        // $Followers           = \yii\helpers\ArrayHelper::getColumn($Followers,'follower_id');
        
        // // if(isset($Followers)){
        // //     foreach($Followers as $follower_id){                                       
        // //             \Yii::$app->push->send(
        // //                 array(
        // //                     'app_type' => 'User',
        // //                     'user_id'  => $follower_id,
        // //                     'from_user_id'=> $model->user_id,
        // //                     'video_id' => $model->video_id,
        // //                     'title' => 'New video to watch.',
        // //                     'message' => \Yii::t('video-controller', '{username} has just upload a video.',['username'=>\Yii::$app->user->identity->username]),
        // //                     'type' => 'view_video',
        // //                 )
        // //             );
        // //     }
        // // }

        $userIds = \yii\helpers\ArrayHelper::getColumn(\common\models\UserData::find()->all(),'id');
        foreach($userIds as $id){
            foreach($userIds as $user_id){
                $model              = new \common\models\FollowerFollowing();
                $model->is_follow   = 1;
                $model->user_id     = $id;
                $model->follower_id = $user_id;
                if($model->save()){
                    echo 1;
                }else{
                    $model->validate();
                }
            }
        }
      
    }
    public function actionComments(){
        $userIds = \yii\helpers\ArrayHelper::getColumn(\common\models\UserData::find()->all(),'id');
        $videoIds = \yii\helpers\ArrayHelper::getColumn(\common\models\Video::find()->all(),'video_id');
        for($i=0;$i<200;$i++){
                $model           = new \common\models\VideoComment();
                $model->user_id  = $this->arrayVal($userIds);
                $model->video_id = $this->arrayVal($videoIds);
                $model->comment  = $this->generateComment();
                $model->save();              
        }
      
    }    
    public function getUrl($url){      
        $file_name = 'dummy_'.rand(1111,99999).time().'.mp4';
        $file =  Yii::$app->basePath.'/../img_assets/videos/'.$file_name;
        // echo $file;die;
        if(file_put_contents( $file,file_get_contents($url))) { 
            $url = \yii\helpers\Url::to('img_assets/videos/'.$file_name, $schema = true);
            return $url;
        } 
        else { 
            return "";
        }
    }
    public function actionVideo(){         
        $strJsonFileContents = file_get_contents(\Yii::$app->basePath.'/web/dummydata/video_1.json');
        $data = json_decode($strJsonFileContents,true);
        $userIds = array_keys(\Yii::$app->general->player());
            foreach($data['body']['itemListData'] as $ele){
                $video = $ele['itemInfos'];
             
                $VideoData = \common\models\Video::find()->where(['video_image'=>$video['covers'][0]])->one();
                if($VideoData){
                    $model              =  $VideoData;
                    $model->video_url   =  $this->getUrl($video['video']['urls'][0]);;
                }else{
                    $model  =  new Video();   
                    $model->month       = (int)date('n');      
                    $model->year        = (int)date('Y'); 
                    $model->tutorial_id = $this->arrayVal(array(1,2,3,4,5,6,7));
                    $model->video_image = $video['covers'][0];  
                    $model->video_url   =  $this->getUrl($video['video']['urls'][0]);;                 
                    $model->description =  $video['text'];
                    $model->user_id = $this->arrayVal($userIds);
                    $model->country_id = 106;
                }   
               
                if ($model->save()) {  
                    $this->addTag($model);          
                }else{
                   print_r(Yii::$app->general->error($model->errors));
                }                             
            }
    }
    public function array_rand($a){
        return $a[mt_rand(0, count($a) - 1)];
    }  
    public function randomName() {
        $firstname = array(
            'Johnathon',
            'Anthony',
            'Erasmo',
            'Raleigh',
            'Nancie',
            'Tama',
            'Camellia',
            'Augustine',
            'Christeen',
            'Luz',
            'Diego',
            'Lyndia',
            'Thomas',
            'Georgianna',
            'Leigha',
            'Alejandro',
            'Marquis',
            'Joan',
            'Stephania',
            'Elroy',
            'Zonia',
            'Buffy',
            'Sharie',
            'Blythe',
            'Gaylene',
            'Elida',
            'Randy',
            'Margarete',
            'Margarett',
            'Dion',
            'Tomi',
            'Arden',
            'Clora',
            'Laine',
            'Becki',
            'Margherita',
            'Bong',
            'Jeanice',
            'Qiana',
            'Lawanda',
            'Rebecka',
            'Maribel',
            'Tami',
            'Yuri',
            'Michele',
            'Rubi',
            'Larisa',
            'Lloyd',
            'Tyisha',
            'Samatha',
        );
    
        $lastname = array(
            'Mischke',
            'Serna',
            'Pingree',
            'Mcnaught',
            'Pepper',
            'Schildgen',
            'Mongold',
            'Wrona',
            'Geddes',
            'Lanz',
            'Fetzer',
            'Schroeder',
            'Block',
            'Mayoral',
            'Fleishman',
            'Roberie',
            'Latson',
            'Lupo',
            'Motsinger',
            'Drews',
            'Coby',
            'Redner',
            'Culton',
            'Howe',
            'Stoval',
            'Michaud',
            'Mote',
            'Menjivar',
            'Wiers',
            'Paris',
            'Grisby',
            'Noren',
            'Damron',
            'Kazmierczak',
            'Haslett',
            'Guillemette',
            'Buresh',
            'Center',
            'Kucera',
            'Catt',
            'Badon',
            'Grumbles',
            'Antes',
            'Byron',
            'Volkman',
            'Klemp',
            'Pekar',
            'Pecora',
            'Schewe',
            'Ramage',
        );
    
        $name = $firstname[rand ( 0 , count($firstname) -1)];
       // $name .= '';
       // $name .= $lastname[rand ( 0 , count($lastname) -1)];
    
        return $name;
    } 
    public function actionUsers(){ 
        $dir = Yii::$app->basePath.'/../images/dummyusers/';
        if(is_dir($dir)){
            $files1 = scandir($dir);
            unset($files1[0]);
            unset($files1[1]);
            $image_array= array_values($files1);
        }
        Yii::$app->basePath.'/web/dummydata/';
        $dir    = '/tmp';
        $files1 = scandir($dir);
        $files2 = scandir($dir, 1);
      
        $users       = \common\models\UserAdditionalInfo::find()->where(1)->asArray()->all();
        $u_img       = \yii\helpers\ArrayHelper::getColumn($users,'photo');
        $usersname   = \yii\helpers\ArrayHelper::getColumn($users,'full_name');
        $bds         = \yii\helpers\ArrayHelper::getColumn($users,'date_of_birth');
        // dummydata/users_1.json       
         $strJsonFileContents = file_get_contents(\Yii::$app->basePath.'/web/dummydata/user.json');
         if(file_exists(\Yii::$app->basePath.'/web/dummydata/user.json')){
             
         }
         $data = json_decode($strJsonFileContents,true);
        // foreach($data['data']['user']['edge_followed_by']['edges'] as $ele){
            for($i=1;$i<101;$i++){
             $user                       = new \app\models\User(['scenario' => 'register']);
             $user->social_type          = "";
             $user->social_provider_id   = "";         
             $user->username             =  $this->randomName();
             $user->email                =  $user->username .'@mailinator.com';
             $user->unconfirmed_email    =  $user->username .'@mailinator.com';
             $user->role                 =  10;
             $user->confirmed_at         =  time();
             $user->status               =  10;
             $user->setPassword(12345678);
             $user->generateAuthKey();
             $user->registration_ip     = Yii::$app->request->userIP;
             if ($user->save(false)) {  
                
                if(isset($image_array[$i])){
                    $photo = $image_array[$i];
                }else{
                    $photo =  $i;
                }

                 $UserAdditionalInfo                       = new \common\models\UserAdditionalInfo;
                 $UserAdditionalInfo->user_id              = $user->id;
                 $UserAdditionalInfo->photo                = "http://edreamacademy.com/images/dummyusers/".$photo;;
                 $UserAdditionalInfo->small_photo          = $UserAdditionalInfo->photo ;
                 $UserAdditionalInfo->thum_photo           = $UserAdditionalInfo->photo ;
                 $UserAdditionalInfo->full_name            = $user->username;
                 $UserAdditionalInfo->date_of_birth        = $this->array_rand($bds);
                 $UserAdditionalInfo->country_id           = 106;
                 $UserAdditionalInfo->city                 = $this->array_rand(['Genoa','Rome','Milan','Venice','Naples','Turin','Palermo','Verona','Pisa','Siena','Lucca']);
                 $UserAdditionalInfo->language_id          = "en-US";
                 $UserAdditionalInfo->u_type               = 2;
                 $UserAdditionalInfo->height               = $this->array_rand([170,171,172,173,175,180,179,182]);
                 $UserAdditionalInfo->weight               = $this->array_rand([70,90,72,73,75,80,79,82]);
                 $UserAdditionalInfo->position             = $this->array_rand(['Striker','Goalkeeper','Midfielder','Defender']);
                 $UserAdditionalInfo->playing_team_name    = $this->array_rand(['Sampdoria','Torino','Atalanta','Bologna','Cagliari','Genoa','Hellas Verona','Juventus','Lazio','Napoli','Parma']);
                 $UserAdditionalInfo->coach_name           = $this->array_rand(['Mahesh','Suresh','Naresh','Deelan','Congo Bongo']);
                 $UserAdditionalInfo->foot_priviledge      = $this->array_rand(['Left','Right','Both']);
                 $UserAdditionalInfo->main_skilll          = $this->array_rand(['Air Slip','Banana','Travalio']);
                 $UserAdditionalInfo->target               = $this->array_rand(['Be Professional','Playing in Italy Football Team','Fame']);
                 $UserAdditionalInfo->can_see_profile               = 1;
                 $UserAdditionalInfo->ball_to_view               =  24;
                 $UserAdditionalInfo->save(false);
             }
         }
         
     }
   
    public function actionOptions($id = null)
    {
        return 'ok';
    }
}
