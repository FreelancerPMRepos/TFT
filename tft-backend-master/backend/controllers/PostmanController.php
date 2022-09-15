<?php
namespace backend\controllers;

use Yii;
use common\models\DreamTeam;
use frontend\controllers\BlogController;
use common\models\DreamTeamSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class PostmanController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [                    
                    [
                        'actions' => ['index','resp'],
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

    /**
     * Lists all DreamTeam models.
     * @return mixed
     */
    public function curl($controller,$action){             
        $url = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].'/v1/user/postman?controller='.$controller.'&action='.$action;
        $url = str_replace("localhost",$_SERVER['REMOTE_ADDR'],$url);
        $curl = curl_init();        
        curl_setopt_array($curl, array(
          CURLOPT_URL =>$url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",        
        ));
        $response = curl_exec($curl);
        $response = json_decode($response,true);
        $err = curl_error($curl);        
        curl_close($curl);        
        if ($err) {
            return [];
        } else {
           
            return isset($response[0])?$response[0]:[];
        }
    }
    function json_validate($string)
    {
        // decode the JSON data
        $result = json_decode($string);
    
        // switch and check possible JSON errors
        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                $error = ''; // JSON is valid // No error has occurred
                break;
            case JSON_ERROR_DEPTH:
                $error = 'The maximum stack depth has been exceeded.';
                break;
            case JSON_ERROR_STATE_MISMATCH:
                $error = 'Invalid or malformed JSON.';
                break;
            case JSON_ERROR_CTRL_CHAR:
                $error = 'Control character error, possibly incorrectly encoded.';
                break;
            case JSON_ERROR_SYNTAX:
                $error = 'Syntax error, malformed JSON.';
                break;
            // PHP >= 5.3.3
            case JSON_ERROR_UTF8:
                $error = 'Malformed UTF-8 characters, possibly incorrectly encoded.';
                break;
            // PHP >= 5.5.0
            case JSON_ERROR_RECURSION:
                $error = 'One or more recursive references in the value to be encoded.';
                break;
            // PHP >= 5.5.0
            case JSON_ERROR_INF_OR_NAN:
                $error = 'One or more NAN or INF values in the value to be encoded.';
                break;
            case JSON_ERROR_UNSUPPORTED_TYPE:
                $error = 'A value of a type that cannot be encoded was given.';
                break;
            default:
                $error = 'Unknown JSON error occured.';
                break;
        }
    
        if ($error !== '') {
            // throw the Exception or exit // or whatever :)
            return false;
        }
    
        // everything is OK
        return $result;
    }
    public function actionResp(){
        $model = new \app\models\PostmanForm();
        if ($model->load(Yii::$app->request->post())) {
            $ch = curl_init();     
            $params =  isset($_POST)?$_POST:$_GET;
           
            
            if($params){
                unset($params['PostmanForm']);
                unset($params['_csrf']);
            }
            
            $headers = [];
            if($model->user_id){
                $user = \common\models\User::find()->where(['id'=>$model->user_id])->one(); 
                
                $user->generateAccessTokenAfterUpdatingClientInfo(true);  
                // $user->generateAccessToken();  
                $token = $user->access_token;  
                $headers = ['authorization:Bearer '.$token];
            }
            if($model->method == "POST"){ 
                    $params1= $params;   
                    $p = [];       
                    if(!empty($_FILES)){                       
                        foreach($_FILES as $file_field => $file){
                            foreach($file['tmp_name'] as $tmp_k => $tmp_val){                                
                                if($file['tmp_name'][$tmp_k]){                                                   
                                    $p[$file_field.'['.$tmp_k.']'] = new \CURLFile($file['tmp_name'][$tmp_k],$file['type'][$tmp_k],$file['name'][$tmp_k]);
                                }
                            }             
                        }
                    } 
                    if(!empty($params1)){                       
                        foreach($params1 as $file_field => $posts){  
                            if(is_array($posts)){  
                                foreach($posts as $file_field_2 => $post){                              
                                    $p[$file_field.'['.$file_field_2.']'] = $post;
                                }
                            }else{
                                $p[$file_field] = $posts;
                            }
                        }
                    }
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                    CURLOPT_URL =>$model->url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => $p,
                    CURLOPT_HTTPHEADER => $headers
                    ));
                    $response = curl_exec($curl);
                    $err = curl_error($curl);
                    curl_close($curl);
                    if ($err) {                       
                        echo "cURL Error #:" . $err;
                    } else {
                        if($this->json_validate($response)){
                            $response  =  json_decode($response,true);
                            echo json_encode($response, JSON_PRETTY_PRINT);  die; 
                        }else{
                            print_r($response);die;
                        }
                    }
                    die;
            }else{
                $data = http_build_query($params);
                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => $model->url.'?'.$data,
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => "",
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 30,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => "GET",
                  CURLOPT_HTTPHEADER => array(
                    "cache-control: no-cache",
                    "authorization: Bearer ".$token
                  ),
                ));                
            }
            $response = curl_exec($curl);
            $err = curl_error($curl);                
            curl_close($curl);                
            if ($err) {
                echo  "cURL Error #:" . $err;;die;
            } else {
                if($this->json_validate($response)){
                    $response  =  json_decode($response,true);
                    echo json_encode($response, JSON_PRETTY_PRINT);  die; 
                }else{
                    print_r($response);die;
                }
            } 
        }
        
    }

    public function actionIndex()
    {    
        // echo 11;die;
        $controllerDirs = [];
        $controllerDirs[] = Yii::$app->basePath.'/../api/modules/v1/controllers/';
        $actions = '';
        foreach ($controllerDirs as $moduleId => $cDir) {
            $actions = $this->Getcontrollersandactions($cDir);
        }

        $model = new \app\models\PostmanForm();
        return $this->render('index',['data'=>$actions,'model'=>$model]);
    }  
    function from_camel_case($input) {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
          $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }
        return implode('-', $ret);
    }
    public function Getcontrollersandactions($controllerDir) {
        $controllerlist = [];
        if ($handle = opendir($controllerDir)) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != ".." && substr($file, strrpos($file, '.') - 10) == 'Controller.php') {
                    $controllerlist[] = $file;
                }
            }
            closedir($handle);
        }
        asort($controllerlist);
        $fulllist = [];
        foreach ($controllerlist as $controller):
            $handle = fopen($controllerDir . '/' . $controller, "r");
            if ($handle) {
                while (($line = fgets($handle)) !== false) {
                    if (preg_match('/public function action(.*?)\(/', $line, $display)):
                        if (strlen($display[1]) > 2 && $display[1]!="Options"):             
                              $controller_ = substr($controller, 0, -4);                              
                              $data = $this->curl($controller_,'action'.$display[1]);
                              $ac =  $this->from_camel_case($display[1]);
                            
                              $c  = substr($controller_, 0, strpos($controller_, "Controller"));
                              $c  = strtolower($this->from_camel_case($c));
                              $url  = \yii\helpers\Url::to('v1/'.$c.'/'.strtolower($ac), $schema = true);                             
                              $url = str_replace("localhost",$_SERVER['REMOTE_ADDR'],$url);
                              $d    = ['name'=>$display[1],'data'=>$data,'url'=>$url];
                              $fulllist[$controller_][] = $d;                          
                        endif;
                    endif;
                }
                
            }
            fclose($handle);
        endforeach;

        return $fulllist;
    }
    
}
