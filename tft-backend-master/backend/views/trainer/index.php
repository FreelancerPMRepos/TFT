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
                <div class="col s2 m6 l6">
                    <a href = "<?= Url::to(['user/create-trainer'], $schema = true)?>" class="btn dropdown-settings waves-effect waves-light breadcrumbs-btn right" >
                        Add Trainer
                    </a>
                </div>
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
                                                    'class' => 'yii\grid\ActionColumn',
                                                    'template' => '{trainee}&nbsp{view}&nbsp{update}&nbsp{delete}',
                                                    'header'=>'Action',
                                                    'buttons' => [
                                                        'trainee'=>function ($url,$model) {
                                                            return Html::a('My Trainee', ['user/index','id'=>$model['user']['id']], ['class'=>'btn btn-lg btn-blue','data-pjax'=>0]);
                                                        }, 
                                                        'view'=>function ($url,$model) {
                                                            return Html::a('<i class="material-icons">visibility</i>', "view-trainer?id=".$model->user_id, ['class'=>'btn btn-lg btn-blue']);
                                                        },    
                                                        'update'=>function ($url,$model) {
                                                            return Html::a('<i class="material-icons">edit</i>', "update-trainer?id=".$model->user_id, ['class'=>'btn btn-lg btn-blue','data'=>['method'=>'post']]);
                                                        },
                                                        'delete'=>function ($url,$model) {
                                                            return  Html::a('<i class="material-icons">delete</i>', "delete-trainer?id=".$model->user_id, [
                                                                'class' => 'btn btn-danger',
                                                                'data' => [
                                                                    'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                                                    'method' => 'post',
                                                                ],
                                                            ]);
                                                            // return Html::a('<i class="material-icons">delete</i>', "delete-trainer?id=".$model->user_id, ['class'=>'btn btn-lg btn-blue','data'=>['method'=>'post']]);
                                                        },   
                                                    ],
                                                ],
                                                
                                                // 'id',
                                                // 'user_id',
                                                // 'photo:ntext',
                                                // 'thum_photo:ntext',
                                                [
                                                    'label' => 'Image',
                                                    'format' => ['image', ['width' => '200']],
                                                    'value' => function ($model) {
                                                        $default = Yii::$app->request->baseUrl."/../img_assets/upload/nophotoavailable.jpg";
                                                        if($model->thum_photo){
                                                            $basePath =  Yii::getAlias('@webroot/../../img_assets/users/'.$model->thum_photo);
                                                            if(file_exists($basePath)){
                                                                return Yii::$app->request->baseUrl."/../img_assets/users/".$model->thum_photo."?r=".rand();
                                                            }else{
                                                                return $default;
                                                            }
                                                        }else{
                                                            return $default;
                                                        }
                                                    },
                                                ],
                                                'user.username',
                                                // 'date_of_birth',
                                                [
                                                    'attribute' => 'date_of_birth',
                                                    'value' => function($model){
                                                        if($model->date_of_birth){
                                                            return $model->date_of_birth;
                                                        }else{
                                                            return "Not available!";
                                                        }
                                                    },
                                                ],
                                                // 'gender',
                                                //'units_of_measurement',
                                                //'height',
                                                //'height_unit',
                                                //'weight',
                                                //'weight_unit',
                                                //'sports_interest',

                                                ['class' => 'yii\grid\ActionColumn'],
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