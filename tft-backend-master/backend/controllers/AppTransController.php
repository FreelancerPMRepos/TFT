<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;

use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\SignupForm;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\ResendVerificationEmailForm;
use app\models\PasswordResetRequestForm;
use app\models\PasswordResetForm;
use yii\helpers\Url;

use lajax\translatemanager\helpers\Language as Lx;



class AppTransController extends Controller
{
   
        public function behaviors()
        {
            return [
                'access' => [
                    'class' => AccessControl::className(),
                    'rules' => [                    
                        [
                            'actions' => ['index'],
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
        public function actionIndex(){
            $file    =  file_get_contents(Yii::$app->basePath.'/../messages/main.json');
            $json    =  json_decode($file,true);
            if(file_exists('/app/web/api/views/trans/app-text.php')){
                $my_file =  '/app/web/api/views/trans/app-text.php';
                $handle  =  fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);
                $h       =  "<?php use lajax\\translatemanager\helpers\Language as Lx; ?>";
                $c       =  "";
                foreach ($json as $key => $value) {       
                    $c .=  "Lx::t('AppTxt_".$key."','".$value."');";    
                }
                $h .= "<?php ".$c."?>";
                fwrite($handle, $h);
                fclose($handle);
            }
            $this->redirect(['/translatemanager/language/scan']);
        } 

    
}
