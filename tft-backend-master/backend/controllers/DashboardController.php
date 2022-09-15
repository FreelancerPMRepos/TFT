<?php


namespace backend\controllers;
use common\models\UserAdditionalInfo;
use common\models\UserToken;
use common\models\User;
use common\models\Admin;
use yii;
use yii\web\Controller;
use yii\common\Models;
use common\models\Exercise;
use common\models\ExerciseCategory;

class DashboardController extends Controller
{
    public function actionIndex()
    {
        //print_r($otherusers);die();
        return $this->render('index',[
            'exercise' => Exercise::find()->count(),
            'exeCategory' => ExerciseCategory::find()->count(),
            'trainer' => Admin::find()->where(['role'=>50])->count(),
            'users' => Admin::find()->where(['role'=>10])->count(),
            'androiduser' => 1,
            'iosuser' => 1,
            'googleuser' => 1,
            'facebookuser' => 1,
        ]);
    }
}