<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use yii\widgets\Breadcrumbs;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $searchModel common\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $title;
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="row">
    <div class="content-wrapper-before gradient-45deg-indigo-purple"></div>
    <div class="breadcrumbs-dark pb-0 pt-4" id="breadcrumbs-wrapper">
        <!-- Search for small screen-->
        <div class="container">
            <div class="row">
                <div class="col s10 m6 l6">
                    <h5 class="breadcrumbs-title mt-0 mb-0"><?= $this->title;?></h5>
                    <?php 
                    echo Breadcrumbs::widget([
                        'itemTemplate' => '<li class="breadcrumb-item">{link}</li>',
                        'tag' => 'ol',
                        'options' => [
                            'class' => 'breadcrumbs mb-0'
                        ],
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                    ]);
                    ?>
                </div>
                <!-- <div class="col s2 m6 l6">
                    <?php if(isset($_GET['id'])): ?>
                        <a href = "<?= Url::to(['user/create','id'=>$_GET['id']], $schema = true)?>" class="btn dropdown-settings waves-effect waves-light breadcrumbs-btn right" >
                            Add <?= $this->title = $title ?>
                        </a>
                    <?php else: ?>
                        <a href = "<?= Url::to(['user/create'], $schema = true)?>" class="btn dropdown-settings waves-effect waves-light breadcrumbs-btn right" >
                            Add <?= $this->title = $title ?>
                        </a>
                    <?php endif; ?>
                </div> -->
            </div>
        </div>
    </div>   
    <div class="col s12">
        <div class="container">
            <div class="section">           
                <div class="row">
                    <div class="col s12 m12 l12">
                        <div id="icon-sizes" class="card card-default">
                            <div class="card-content">
                                <div class="row">
                                    <div class="col s12 m12 l12">
                                        <?php Pjax::begin(); ?>
                                        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

                                        <?= GridView::widget([
                                            'dataProvider' => $dataProvider,
                                            'filterModel' => $searchModel,
                                            'columns' => [
                                                ['class' => 'yii\grid\SerialColumn'],
                                                [
                                                    'label' => 'Image',
                                                    'format' => ['image', ['width' => '50']],
                                                    'value' => function ($model) {
                                                        $default = Yii::$app->request->baseUrl."/../img_assets/upload/nophotoavailable.jpg";
                                                        if($model->userAdditionalInfos && $model->userAdditionalInfos->thum_photo){
                                                            $basePath =  Yii::getAlias('@webroot/../../img_assets/users/'.$model->userAdditionalInfos->thum_photo);
                                                            if(file_exists($basePath)){
                                                                return Yii::$app->request->baseUrl."/../img_assets/users/".$model->userAdditionalInfos->thum_photo."?r=".rand();
                                                            }else{
                                                                return $default;
                                                            }
                                                        }else{
                                                            return $default;
                                                        }
                                                    },
                                                ],
                                                [
                                                    'attribute'=>'username',
                                                    'format'=>'html',
                                                    'value' => function ($model) {
                                                        $H = "";
                                                        if($model->user_type == "Trainer"){
                                                            $H = '<br>'.Html::a('My Trainee', ['user/index','trainer_id'=>$model->id],
                                                             ['class'=>'','data-pjax'=>0]);
                                                        }
                                                        return '<p><b>'.$model->username.'</b></p>
                                                        <small>Since from - '.date('d M Y',$model->created_at).'</small>'.$H;
                                                        
                                                    },
                                                ],
                                                'email',

                                                [
                                                    'label' => 'User Type',
                                                    'attribute'=>'user_type',
                                                    'filter'=>[''=>'All','Admin'=>'Admin','User'=>'User','Trainer'=>'Trainer'],
                                                    'value' => function ($model) {
                                                        return $model->user_type;
                                                    },
                                                ],
                                                [
                                                    'label' => 'Status',
                                                    'attribute'=>'status',
                                                    'filter'=>[''=>'All','10'=>'Active','1'=>'Blocked'],
                                                    'value' => function ($model) {
                                                        return $model->status==10?"Active":"Blocked";
                                                    },
                                                ],
                                                [
                                                    'label' => 'Login Type',
                                                    'attribute'=>'social_type',
                                                    'filter'=>[''=>'All','facebook'=>'Facebook','google'=>'Google','apple'=>'Apple'],
                                                    'value' => function ($model) {
                                                        return $model->social_type?$model->social_type:"Normal";
                                                    },
                                                ],
                                                //'date_of_birth',
                                                //'gender',
                                                [
                                                    'class' => 'yii\grid\ActionColumn',
                                                    'template' => '{view}&nbsp{update}&nbsp{delete}',
                                                    'header'=>'Action',
                                                    'buttons' => [
                                                        'log'=>function ($url,$model) {
                                                            return Html::a('Logs', ['user-log/index','user_id'=>$model->id], ['class'=>'btn btn-sm btn-blue','data-pjax'=>0]);
                                                        },
                                                        'cRoutin'=>function ($url,$model) {
                                                            return isset($_GET['id'])? Html::a('Cardio Routine', ['routines/cardio','id'=>$_GET['id'],'user_id'=>$model->id,'name'=>$model['user']['username']], ['class'=>'btn btn-lg btn-blue','data-pjax'=>0]) : Html::a('Cardio Routine', ['routines/cardio','user_id'=>$model->id,'name'=>$model['user']['username']], ['class'=>'btn btn-lg btn-blue','data-pjax'=>0]);
                                                        },
                                                        'sRoutin'=>function ($url,$model) {
                                                            return Html::a('Strength Routine', ['routines/index','user_id'=>$model->id], ['class'=>'btn btn-small btn-blue','data-pjax'=>0]);
                                                        },
                                                        'view'=>function ($url,$model) {
                                                            if(isset($_GET['id'])){
                                                                return Html::a('<i class="material-icons">visibility</i>', "view?id=".$model->id."&t_id=".$_GET['id'], ['class'=>'btn btn-small btn-blue']);
                                                            }else{
                                                                return Html::a('<i class="material-icons">visibility</i>', "view?id=".$model->id, ['class'=>'btn btn-small btn-blue']);
                                                            }
                                                        },    
                                                        'update'=>function ($url,$model) {
                                                            if(isset($_GET['id'])){
                                                                return Html::a('<i class="material-icons">edit</i>', "update?id=".$model->id."&t_id=".$_GET['id'], ['class'=>'btn btn-small btn-blue','data'=>['method'=>'post']]);
                                                            }else{
                                                                return Html::a('<i class="material-icons">edit</i>', "update?id=".$model->id, ['class'=>'btn btn-small btn-blue','data'=>['method'=>'post']]);
                                                            }
                                                        },
                                                        'delete'=>function ($url,$model) {
                                                            return  Html::a('<i class="material-icons">delete</i>', "delete?id=".$model->id, [
                                                                'class' => 'btn btn-small btn-danger',
                                                                'data' => [
                                                                    'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                                                    'method' => 'post',
                                                                ],
                                                            ]);
                                                            // return Html::a('<i class="material-icons">delete</i>', "delete?id=".$model->user_id, ['class'=>'btn btn-lg btn-blue','data'=>['method'=>'post']]);
                                                        },   
                                                    ],
                                                ],
                                            ],
                                        ]); ?>

                                        <?php Pjax::end(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>