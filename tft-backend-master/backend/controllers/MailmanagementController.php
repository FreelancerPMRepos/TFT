<?php

namespace backend\controllers;

use Yii;
use common\models\MailManagement;
use common\models\MailManagementSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MailmanagementController implements the CRUD actions for MailManagement model.
 */
class MailmanagementController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [                    
                    [
                        'actions' => ['create','update','index','view','delete','delete-selected','user-list','mark-as-read'],
                        'allow' => true,
                        'roles' => ['admin','trainer'],
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
    public function actionMarkAsRead(){
        if(!empty($_POST['mailId'])){
            $getDetails = MailManagement::updateAll(['reply_of'=>1],['IN', 'id', $_POST['mailId']]);
            $res['status'] = true;
          
        } else {
            $res['status'] = false;
            $res['message'] = 'Please select at least one item.';
        }

        echo json_encode($res);
        die;
    }
    /**
     * Lists all MailManagement models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MailManagementSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $model = new MailManagement();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }

    /**
     * Displays a single MailManagement model.
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
     * Creates a new MailManagement model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MailManagement();

        if ($model->load(Yii::$app->request->post())) {
            $postData   = Yii::$app->request->post();
            $emails     = $postData['MailManagement']['email'];
            if(!empty($emails)){
                foreach($emails as $email){
                    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $mailData = [
                            'mailTo' => $email,
                            'mailFrom' => Yii::$app->params['adminEmail'],
                            'mailFromName' => 'MTC',
                            'mailSubject' => $postData['MailManagement']['subject'],
                            'mailBody' => $postData['MailManagement']['body'],
                        ];
                        $mailResult = $this->sendEmail($mailData);
                    }else{
                        $getUserDetails = \common\models\User::find()->select('id, username, email')->where(['id' => $email])->asArray()->one();                   
                    
                        if(!empty($getUserDetails)){
                            $mailData = [
                                'mailTo' => $getUserDetails['email'],
                                'mailFrom' => Yii::$app->params['adminEmail'],
                                'mailFromName' => 'MTC',
                                'mailSubject' => $postData['MailManagement']['subject'],
                                'mailBody' => $postData['MailManagement']['body'],
                            ];
                            $mailResult = $this->sendEmail($mailData);
                            
                            if($mailResult){
                                $model = new MailManagement();
                                $model->email = $getUserDetails['email'];
                                $model->subject = $postData['MailManagement']['subject'];
                                $model->body = $postData['MailManagement']['body'];

                                $model->user_id = $getUserDetails['id'];
                                $model->name = $getUserDetails['username'];
                                $model->reply_of = 0;
                                $model->email_type = 'sent';
                                $model->created_at  = time();
                                $model->save(false);
                            }
                        }
                    }
                }
            }
            return $this->redirect(['index', 'MailManagementSearch[email_type]' => 'sent']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    //send mail to uset
    public function sendEmail($mailData){


        $emailTo = $mailData['mailTo'];
        $emailSubject = $mailData['mailSubject'];
        $emailBody = $mailData['mailBody'];

        $email =  Yii::$app->mailer->compose()
        ->setTo($emailTo)
        ->setFrom([\Yii::$app->setting->val('supportEmail') => \Yii::$app->name])
        ->setSubject(\Yii::$app->name.':'.$emailSubject)
        ->setHtmlBody($mailData['mailBody'])->send();

        return $email;
    }

    /**
     * Updates an existing MailManagement model.
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
     * Deletes an existing MailManagement model.
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
     * Finds the MailManagement model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MailManagement the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MailManagement::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionDeleteSelected(){
        if(!empty($_POST['deleteId'])){
            $getDetails = MailManagement::find()->where(['IN', 'id', $_POST['deleteId']])->all();
            if(!empty($getDetails)){
                foreach($getDetails as $detail){
                    $detail->delete();
                }
                $res['status'] = true;
                $res['message'] = 'Mail has been successfully deleted.';
            } else {
                $res['status'] = false;
                $res['message'] = 'Your selected items are not found.';
            }
        } else {
            $res['status'] = false;
            $res['message'] = 'Please select atleast one item.';
        }

        echo json_encode($res);
        die;
    }

    public function actionUserList($q){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {

            // $query = new Query;
            // $query->select('id, name AS text')
            //     ->from('user')
            //     ->where(['like', 'name', $q])
            //     ->limit(20);
            // $command = $query->createCommand();
            // $data = $command->queryAll();
            $data = Yii::$app->db->createCommand('SELECT id, email, username AS text FROM user WHERE username LIKE "%'.$q.'%" limit 20')->queryAll();
            $out['results'] = array_values($data);
        }
        elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => City::find($id)->name];
        }
        return $out;
    }

    public function beforeAction($action) {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }
}
