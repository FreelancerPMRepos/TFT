<?php

namespace backend\controllers;

use Yii;
use \common\models\User;
use common\models\Notification;
use common\models\NotificationSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;;

/**
 * NotificationController implements the CRUD actions for Notification model.
 */
class NotificationController extends Controller
{
    public $enableCsrfValidation =  false;
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
                        'actions' => ['create','delete','index','view','get-users','send-notification'],
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
    public function actionGetUsers(){
        $type  = $_POST['type'];
        $model = User::find()->select(['id','username'])->where('user_type="'.$type.'"')->asArray()->all();
        $selectHtml = '<option value>--Select Users--</option>';
        foreach($model as $user){
            $selectHtml .= '<option value="'.$user['id'].'">'.$user['username'].'</option>';
        }        
        echo json_encode(array('html'=>$selectHtml));die;
    }
    /**
     * Lists all Notification models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new NotificationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Notification model.
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
    public function actionSendNotification(){
        $notifications = \common\models\Notification::find()->where(['is_sent'=>0,'is_read'=>'N'])->limit(100)->all();
        foreach($notifications as $notification){
            echo 11;die;
            print_r(\Yii::$app->push->sendPush($notification));
        }
    }
    /**
     * Creates a new Notification model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        // print_r($_SERVER);die;
        $model    = new Notification();        
        $postData = Yii::$app->request->post();
        $notification = [];
        if(!empty($postData)){
            $title      = $postData['Notification']['title'];
            $msg        = $postData['Notification']['message'];           
            $usersArray = !empty($postData['users']) ? explode(",",$postData['users']) : [];
            $usersArray = \common\models\UserToken::find()->where(['IN','user_id',$usersArray])->asArray()->all();
            $user_badge_count = \Yii::$app->db->createCommand('SELECT user_id,COUNT(id) as c FROM `notification` WHERE is_read = "N" GROUP BY user_id')->queryAll();
            $user_badge_count = \yii\helpers\ArrayHelper::map($user_badge_count,'user_id','c');
            foreach($usersArray as $user){
                array_push(
                    $notification,
                    array(
                        $user['user_id'],
                        json_encode([$user['uuid']]),
                        $title,
                        $msg, 
                        'static', 
                        'User',                      
                        'N',    
                        0,                   
                        isset($user_badge_count[$user['user_id']])?$user_badge_count[$user['user_id']]:0,
                        '1', 
                        time(), 
                        1,
                        1, 
                        '', 
                        ''
                    )
                );
            }
            Yii::$app->push->storeAndSend($notification);            
        }
        $searchModel            = new \common\models\UserAdditionalInfoSearch();
        $dataProvider           = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('create', [
            'model' => $model,
            'dataProvider'=>$dataProvider,
            'searchModel'=>$searchModel
        ]);      
    }


    /**
     * Updates an existing Notification model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Notification model.
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
     * Finds the Notification model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Notification the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Notification::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
