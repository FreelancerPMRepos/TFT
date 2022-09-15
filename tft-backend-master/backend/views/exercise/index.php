<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\Breadcrumbs;
use yii\helpers\Url;
use common\models\ExerciseCategory;
/* @var $this yii\web\View */
/* @var $searchModel common\models\ExerciseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Exercises';
$this->params['breadcrumbs'][] = $this->title;
$ExerciseCategory = ArrayHelper::map(\common\models\ExerciseCategory::find()->where(1)->orderBy('name ASC')->asArray()->all(),'id','name');;
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
                <!-- <a href = "<?= Url::to(['exercise/create'], $schema = true)?>" class="btn dropdown-settings waves-effect waves-light breadcrumbs-btn right" >
                    Add
                </a> -->
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
                                                    'label' => 'Exercise Image',
                                                    'format' => ['image', ['width' => '200']],
                                                    'value' => function ($model) {
                                                        $default = Yii::$app->request->baseUrl."/../img_assets/upload/nophotoavailable.jpg";
                                                        if($model->img){
                                                            $basePath =  Yii::getAlias('@webroot/../../img_assets/exercise/'.$model->img);
                                                            if(file_exists($basePath)){
                                                                return Yii::$app->request->baseUrl."/../img_assets/exercise/".$model->img."?r=".rand();
                                                            }else{
                                                                return $default;
                                                            }
                                                        }else{
                                                            return $default;
                                                        }
                                                    },
                                                ],
                                                // 'id',
                                                [
                                                    'attribute' => 'exe_category_id',
                                                    'filter' => $ExerciseCategory,
                                                    'value' => function($model){
                                                        return ExerciseCategory::findOne($model->exe_category_id)->name;
                                                    }
                                                ],
                                                'name',
                                                // 'description:ntext',
                                                // 'body_parts:ntext',
                                                //'steps:ntext',
                                                //'instructions:ntext',
                                                //'type',
                                                //'record_type',
                                                //'source',
                                                //'img',
                                                //'gif',
                                                //'is_active',
                                                //'created_at',
                                                // ['class' => 'yii\grid\ActionColumn'],
                                                [
                                                    'class' => 'yii\grid\ActionColumn',
                                                    'template' => '{update}&nbsp',
                                                    'header'=>'Action',
                                                    'buttons' => [    
                                                        'update'=>function ($url) {
                                                            return Html::a('<i class="material-icons">edit</i>', $url, ['class'=>'btn btn-lg btn-blue']);
                                                        },
                                                        'delete'=>function ($url) {
                                                            return  Html::a('<i class="material-icons">delete</i>', $url, [
                                                                'class' => 'btn btn-danger',
                                                                'data' => [
                                                                    'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                                                    'method' => 'post',
                                                                ],
                                                            ]);
                                                            // return Html::a('<i class="material-icons">delete</i>', $url, ['class'=>'btn btn-lg btn-blue','data'=>['method'=>'post']]);
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
