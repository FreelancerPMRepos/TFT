<?php
namespace backend\controllers;

use Yii;
use common\models\EmailTemplate;
use common\models\EmailTemplateSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\helpers\Url;
use yii\filters\AccessControl;

/**
 * EmailController implements the CRUD actions for EmailTemplate model.
 */
class EmailController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [                    
                    [
                        'actions' => ['create','update','delete','index','uploadimage'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                    [
                        'actions' => ['create','update','index','uploadimage'],
                        'allow' => true,
                        'roles' => ['trainer'],
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
    public function beforeAction($action) 
    { 
        $this->enableCsrfValidation = false; 
        return parent::beforeAction($action); 
    }

    /**
     * Lists all EmailTemplate models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new EmailTemplateSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single EmailTemplate model.
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
     * Creates a new EmailTemplate model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new EmailTemplate();

        if ($model->load(Yii::$app->request->post())) {
            $model->email_slug = $this->create_slug(strtolower($model->emai_template_name));
            $model->save();
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    function create_slug($string){
        $slug=preg_replace('/[^A-Za-z0-9-]+/', '_', $string);
        return $slug;
     }

    /**
     * Updates an existing EmailTemplate model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing EmailTemplate model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the EmailTemplate model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return EmailTemplate the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = EmailTemplate::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    private function url(){
        if(isset($_SERVER['HTTPS'])){
            $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
        }
        else{
            $protocol = 'http';
        }
        return $protocol . "://" . $_SERVER['HTTP_HOST'];
    }
    public function actionUploadimage(){       
        $file = UploadedFile::getInstanceByName('file');
        $uploadPath = \Yii::$app->basePath .'/../img_assets/mai_assets/';
        if(!is_dir($uploadPath)){
            mkdir($uploadPath);   
        }
        $allowed =  array('gif','png' ,'jpg','jpeg');
        $filename = $file->baseName;
       
        $ext = pathinfo($file->name, PATHINFO_EXTENSION);
        if(!in_array($ext,$allowed) ) {
            throw new NotFoundHttpException('Image is not valid.');
        }
        $original_name = $file->baseName;  
        $newFileName = $original_name.'_'.time().'.'.$file->extension;
        if ($file->saveAs($uploadPath . '/' . $newFileName)) {
            $file_location = 'img_assets/mai_assets/'. $newFileName;   
            return json_encode(array("location"=>$this->url().'/'.$file_location));die;;
        }
    }
}
