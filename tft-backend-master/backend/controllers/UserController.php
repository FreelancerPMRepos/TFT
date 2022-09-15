<?php

namespace backend\controllers;

use Yii;
use common\models\User;
use common\models\Admin;
use common\models\UserData;
use common\models\PasswordForm;
use common\models\AdminSearch;
use common\models\UserAdditionalInfoSearch;
use app\models\ImageForm;
use yii\imagine\Image;
use yii\web\UploadedFile;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\UserAdditionalInfo;
use common\models\UserTrainee;

use lajax\translatemanager\helpers\Language as Lx;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    public $enableCsrfValidation = false;
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
                        'actions' => ['index','user-status','create','update','delete','view','delete-trainer','update-trainer','view-trainer','create-trainer','admin','create-admin','delete-admin','view-admin','update-admin','changepassword','changepassword-tu','trainer'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST', 'GET'],
                    'changepassword-tu' => ['POST'],
                ],
            ],
        ];
    }

    public function actionAdmin()
    {
        $searchModel = new AdminSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('//admin/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'title'=>'Admin',
        ]);
    }

    public function actionCreateAdmin()
    {
        $model = new Admin();
        $model->scenario = 'insert';
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $model->generateAuthKey();
            $model->setPassword($model->password_hash);

            $model->username            = strtolower($model->username);
            $model->unconfirmed_email   = $model->email;
            $model->confirmed_at        = time();
            $model->user_type           = "Admin";
            $model->role                = Admin::ROLE_ADMIN;
            $model->status              = Admin::STATUS_ACTIVE;
            $model->registration_ip      = Yii::$app->request->userIP;
            
            if($model->save(false)){
                return $this->redirect(['view-admin', 'id' => $model->id]);
            }
            else{
                return $this->render('//admin/create', [
                    'model' => $model,
                    'title'=>'Admin',
                ]);
            }
        }
        return $this->render('//admin/create', [
            'model' => $model,
            'title'=>'Admin',
        ]);
    }
    
    public function actionViewAdmin($id)
    {
        return $this->render('//admin/view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionUpdateAdmin($id){

        $model = $this->findModel($id);
        $model_1 = new PasswordForm();
        if ($model->load(Yii::$app->request->post())) {

            $model->username            = strtolower($model->username);
            $model->unconfirmed_email   = $model->email;
            $model->confirmed_at        = time();
            $model->user_type           = "Admin";
            $model->role                = 99;
            $model->status              = Admin::STATUS_ACTIVE;
            // $model->registration_ip      = Yii::$app->request->userIP;

            if($model->save(false)){
                return $this->redirect(['view-admin', 'id' => $model->id]);
            }
            else{
                return $this->render('//admin/update', [
                    'model' => $model,
                    'model_1' => $model_1,
                ]);
            }
        }

        return $this->render('//admin/update', [
            'model' => $model,
            'model_1' => $model_1,
        ]);
    }

    public function actionDeleteAdmin($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect("admin");
    }

    /**
     * Change use password.
     * @return mixed
     */
    //change password admin
     public function actionChangepassword($id){

        $model = $this->findModel($id);
        $postData = Yii::$app->request->post()['PasswordForm'];
        if($postData){
            $model->password_hash = Yii::$app->security->generatePasswordHash($postData['newpass']);
            if($model->save(false)){
                return $this->redirect(['view-admin', 'id' => $model->id]);
            }
            else{
                return $this->redirect(['update-admin', 'id' => $model->id]);
            }
        }
        else{
            return $this->redirect(['update-admin', 'id' => $model->id]);
        }
    }

    /**
     * Change use status - Block or Unblock.
     * @return mixed
     */
    public function actionUserStatus(){

        if(isset($_POST['status']) && !empty($_POST['user'])){
            $blockStatus = $_POST['status'];
            $userId      = $_POST['user'];
            $getUserDetails = User::find()->where(['id' => $userId])->one();
            if(!empty($getUserDetails)){
                if($blockStatus == 0){
                    $getUserDetails->blocked_at = NULL;
                    $getUserDetails->status = 10;
                } else {
                    $getUserDetails->blocked_at = time();;
                    $getUserDetails->status = 1;
                }
                if($getUserDetails->save()){
                    $res['status'] = true;
                    $res['message'] = 'Block status has been successfully changed.';
                } else {
                    $res['status'] = false;
                    $res['message'] = 'Error on data saving, while change block status.';
                }
            } else {
                $res['status'] = false;
                $res['message'] = 'User not found.';
            }
        } else {
            $res['status'] = false;
            $res['message'] = 'Parameter is missing.';
        }

        echo json_encode($res);
        die;
    }

    //trainer
    public function actionTrainer()
    {
        $searchModel            = new UserAdditionalInfoSearch();
        $searchModel->user_type ="Trainer"; 
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('//trainer/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'title'=>'Trainer'
        ]);
    }
    public function actionCreateTrainer()
    {
        $model = new Admin();
        $model_1 = new UserAdditionalInfo;

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model_1->load(Yii::$app->request->post()) && $model_1->validate())
        {
            $model->generateAuthKey();
            $model->setPassword($model->password_hash);

            $model->username            = strtolower($model->username);
            $model->unconfirmed_email   = $model->email;
            $model->confirmed_at        = time();
            $model->user_type           = "Trainer";
            $model->role                = Admin::ROLE_TRAINER;
            $model->status              = Admin::STATUS_ACTIVE;
            $model->registration_ip      = Yii::$app->request->userIP;
            // if(1){
            if($model->save(false)){
                $model_1->user_id      = $model->id;
                $image = UploadedFile::getInstance($model_1,'img');
                $BasePath = Yii::$app->basePath . '/../img_assets/users/';
                if($image){
                    $filename           =  time().'-'.$image->baseName . '.' . $image->extension;
                    $thumb_image        = 'thumb_'.$filename;
                    if($image->saveAs($BasePath.$filename)){
                        Image::thumbnail($BasePath.$filename, 600, 600)->save(Yii::getAlias($BasePath.$thumb_image), ['quality' => 90]);
                        $model_1->thum_photo     = $thumb_image;;
                        $model_1->photo          = $filename;
                    }
                }else{
                    $model_1->photo          = "";
                    $model_1->thum_photo     = "";
                }
                if($model_1->save(false))
                {
                    return $this->redirect(['view-trainer', 'id' => $model->id]);
                }
                else{
                    $model->delete();
                    return $this->render('//trainer/create', [
                        'model' => $model,
                        'model_1' => $model_1,
                    ]);
                }
            }
            else{
                return $this->render('//trainer/create', [
                    'model' => $model,
                    'model_1' => $model_1,
                ]);
            }
            
        }

        return $this->render('//trainer/create', [
            'model' => $model,
            'model_1' => $model_1,
            // 'imageModel' => $imageModel
        ]);
    }
    public function actionViewTrainer($id)
    {
        $model  = UserAdditionalInfo::find()->joinWith(['user'])->where(['user_id'=>$id])->asArray()->one();
        // print_r($model);die;
        return $this->render('//trainer/view', [
            'model' => $model,
        ]);
    }
    public function actionUpdateTrainer($id)
    {
        $model = $this->findModel($id);
        $model_1 = UserAdditionalInfo::findOne(['user_id'=>$model->id]);
        $model_2 = new PasswordForm();

        if($model->load(Yii::$app->request->post()) && $model_1->load(Yii::$app->request->post()))
        {
            $model->username            = strtolower($model->username);
            $model->unconfirmed_email   = $model->email;
            $model->confirmed_at        = time();
            $model->user_type           = "Trainer";
            $model->role                = 50;
            $model->status              = Admin::STATUS_ACTIVE;

            $model_1->scenario = 'update';
            $image = UploadedFile::getInstance($model_1,'img');
            $BasePath = Yii::$app->basePath . '/../img_assets/users/';
            if($image){
                $filename           =  time().'-'.$image->baseName . '.' . $image->extension;
                $thumb_image        = 'thumb_'.$filename;
                if($image->saveAs($BasePath.$filename)){
                    Image::thumbnail($BasePath.$filename, 600, 600)->save(Yii::getAlias($BasePath.$thumb_image), ['quality' => 90]);
                    $model_1->thum_photo     = $thumb_image;;
                    $model_1->photo          = $filename;
                }
            }
            if($model->save(false) && $model_1->save(false)){
                return $this->redirect(['view-trainer', 'id' => $model->id]);
            }else{
                return $this->render('//trainer/update', [
                    'model' => $model,
                    'model_1' => $model_1,
                    'model_2' => $model_2,
                ]);
            }
        }

        return $this->render('//trainer/update', [
            'model' => $model,
            'model_1' => $model_1,
            'model_2' => $model_2,
        ]);
    }
    public function actionDeleteTrainer($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect("trainer");
    }

    //password change for trainer and users both
    public function actionChangepasswordTu($id,$type)
    {
        $model = $this->findModel($id);
        $postData = Yii::$app->request->post()['PasswordForm'];
        if($postData){
            $model->password_hash = Yii::$app->security->generatePasswordHash($postData['newpass']);
            if($model->save(false)){
                if($type == 't'){
                    return $this->redirect(['view-trainer', 'id' => $model->id]);
                }
                if($type == 'u'){
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
            else{
                if($type == 't'){
                    return $this->redirect(['view-trainer', 'id' => $model->id]);
                }
                if($type == 'u'){
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        }
        else{
            return $this->redirect(Yii::$app->request->referrer);
        }
    }

    //users
    public function actionIndex($trainer_id = 0)
    {
        $searchModel            = new \common\models\UserSearch();
        if($trainer_id){
            $trainee = UserTrainee::find()->select(['trainee_id'])->where(['trainer_id'=>$trainer_id])
            ->asArray()->all();
            $trainee                = \yii\helpers\ArrayHelper::getColumn($trainee,'trainee_id');
            $searchModel->user_type = "User";
            $searchModel->id        = $trainee;
            $type                   = 'User';
        }else{
            $searchModel->user_type ="User";
            $type = 'User';

        }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'title'=>$type,
        ]);
    }
    public function actionCreate($id = 0)
    {
        $type = $id ? 'Trainne' : 'User';
        $model = new Admin();
        $model_1 = new UserAdditionalInfo;

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model_1->load(Yii::$app->request->post()) && $model_1->validate())
        {
            $model->generateAuthKey();
            $model->setPassword($model->password_hash);
            $model->username            = strtolower($model->username);
            $model->unconfirmed_email   = $model->email;
            $model->confirmed_at        = time();

            if($id){
                $model->user_type           = "Trainne";
                $model->role                = Admin::ROLE_TRAINEE;
            }else{
                $model->user_type           = "User";
                $model->role                = Admin::ROLE_USER;
            }

            $model->status              = Admin::STATUS_ACTIVE;
            $model->registration_ip      = Yii::$app->request->userIP;
            // if(1){
            if($model->save(false)){

                if($id){
                    $modelTrainee = new UserTrainee;
                    $modelTrainee->trainer_id = $id;
                    $modelTrainee->trainee_id = $model->id;
                    if($modelTrainee->save(false)){}
                    else{
                        $model->delete();
                        return $this->render('create', [
                            'model' => $model,
                            'model_1' => $model_1,
                            'type' => $type,
                        ]);
                    }
                }

                $model_1->user_id      = $model->id;
                $image = UploadedFile::getInstance($model_1,'img');
                $BasePath = Yii::$app->basePath . '/../img_assets/users/';
                if($image){
                    $filename           =  time().'-'.$image->baseName . '.' . $image->extension;
                    $thumb_image        = 'thumb_'.$filename;
                    if($image->saveAs($BasePath.$filename)){
                        Image::thumbnail($BasePath.$filename, 600, 600)->save(Yii::getAlias($BasePath.$thumb_image), ['quality' => 90]);
                        $model_1->thum_photo     = $thumb_image;;
                        $model_1->photo          = $filename;
                    }
                }else{
                    $model_1->photo          = "";
                    $model_1->thum_photo     = "";
                }
                if($model_1->save(false))
                {
                    return $this->redirect(['view', 'id' => $model->id,'t_id'=>$id]);
                }
                else{
                    return $this->render('create', [
                        'model' => $model,
                        'model_1' => $model_1,
                        'type' => $type,
                    ]);
                }
            }
            else{
                return $this->render('create', [
                    'model' => $model,
                    'model_1' => $model_1,
                    'type' => $type,
                ]);
            }
            
        }

        return $this->render('create', [
            'model' => $model,
            'model_1' => $model_1,
            'type' => $type,
        ]);
    }
    public function actionView($id,$t_id = 0)
    {
        $type = $t_id ? 'Trainee' : 'User';
        $model  = UserAdditionalInfo::find()->joinWith(['user'])->where(['user_id'=>$id])->asArray()->one();
        return $this->render('view', [
            'model' => $model,
            'type' => $type,
        ]);
    }
    public function actionUpdate($id,$t_id = 0)
    {
        $type = $t_id ? 'Trainne' : 'User';

        $model = $this->findModel($id);
        $model_1 = UserAdditionalInfo::findOne(['user_id'=>$model->id]);
        $model_2 = new PasswordForm();

        if($model->load(Yii::$app->request->post()) && $model_1->load(Yii::$app->request->post()))
        {
            $model->username            = strtolower($model->username);
            $model->unconfirmed_email   = $model->email;
            $model->confirmed_at        = time();
            // if($t_id){
            //     $model->user_type           = "Trainne";
            //     $model->role                = Admin::ROLE_TRAINEE;
            // }else{
            //     $model->user_type           = "User";
            //     $model->role                = Admin::ROLE_USER;
            // }
            $model->status              = Admin::STATUS_ACTIVE;

            $model_1->scenario = 'update';
            $image = UploadedFile::getInstance($model_1,'img');
            $BasePath = Yii::$app->basePath . '/../img_assets/users/';
            if($image){
                $filename           =  time().'-'.$image->baseName . '.' . $image->extension;
                $thumb_image        = 'thumb_'.$filename;
                if($image->saveAs($BasePath.$filename)){
                    Image::thumbnail($BasePath.$filename, 600, 600)->save(Yii::getAlias($BasePath.$thumb_image), ['quality' => 90]);
                    $model_1->thum_photo     = $thumb_image;;
                    $model_1->photo          = $filename;
                }
            }
            if($model->save(false) && $model_1->save(false)){
                return $this->redirect(['view', 'id' => $model->id,'t_id'=>$id]);
            }else{
                return $this->render('update', [
                    'model' => $model,
                    'model_1' => $model_1,
                    'model_2' => $model_2,
                    'type' => $type,
                ]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'model_1' => $model_1,
            'model_2' => $model_2,
            'type' => $type,
        ]);
    }
    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        // \common\models\Notification::deleteAll(['user_id' => $id]);
        // \common\models\UserToken::deleteAll(['user_id' => $id]);
        // \common\models\UserAdditionalInfo::deleteAll(['user_id' => $id]);
        $this->findModel($id)->delete();
        // Yii::$app->session->setFlash('success', "The user has been deleted permanently!");
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Admin::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
