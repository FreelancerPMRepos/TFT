<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\widgets\Breadcrumbs;
use common\models\ExerciseCategory;
// $exe_Category = ExerciseCategory::find()->select(['name'])->where(['id'=>1])->asArray()->one();

/* @var $this yii\web\View */
/* @var $model common\models\Log */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Exercise', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
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
                <a class="btn dropdown-settings waves-effect waves-light breadcrumbs-btn right" href="<?= Url::toRoute(['index'], $schema = true)?>">
                <i class="material-icons hide-on-med-and-up">settings</i><span class="hide-on-small-onl"><< Back</span>
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
                                        <p>
                                            <?= Html::a('Update', ['update', 'id' => $model->id], [
                                                'class' => 'btn btn-danger',
                                            ]) ?>
                                            <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                                                'class' => 'btn btn-danger',
                                                'data' => [
                                                    'confirm' => 'Are you sure you want to delete this item?',
                                                    'method' => 'post',
                                                ],
                                            ]) ?>
                                        </p>

                                        <?= DetailView::widget([
                                            'model' => $model,
                                            'attributes' => [
                                                // 'id',
                                                [
                                                    'attribute' => 'exe_category_id',
                                                    'value' => $exe_Category = ExerciseCategory::findOne(['id'=>$model->exe_category_id])->name
                                                ],
                                                'name',
                                                'description:ntext',
                                                'body_parts:ntext',
                                                'steps:ntext',
                                                'instructions:ntext',
                                                'type',
                                                'record_type',
                                                'source',
                                                [
                                                    'label' => 'Image',
                                                    'format' => ['image', ['width' => '200']],
                                                    'value' => function ($model) {
                                                        $default_Image = Yii::$app->request->baseUrl."/../img_assets/upload/nophotoavailable.jpg";
                                                        if($model->img){
                                                            $basePath =  Yii::getAlias('@webroot/../../img_assets/exercise/'.$model->img);
                                                            if(file_exists($basePath)){
                                                                return Yii::$app->request->baseUrl."/../img_assets/exercise/".$model->img."?r=".rand();
                                                            }else{
                                                                return $default_Image;
                                                            }
                                                        }else{
                                                            return $default_Image;
                                                        }
                                                    },
                                                ],
                                                [
                                                    'label' => 'Gif',
                                                    'format' => ['image', ['width' => '200']],
                                                    'value' => function ($model) {
                                                        $default_Image = Yii::$app->request->baseUrl."/../img_assets/upload/nophotoavailable.jpg";
                                                        if($model->gif){
                                                            $basePath =  Yii::getAlias('@webroot/../../img_assets/exercise/'.$model->gif);
                                                            if(file_exists($basePath)){
                                                                return Yii::$app->request->baseUrl."/../img_assets/exercise/".$model->gif."?r=".rand();
                                                            }else{
                                                                return $default_Image;
                                                            }
                                                        }else{
                                                            return $default_Image;
                                                        }
                                                    },
                                                ],
                                                [
                                                    'attribute'=>'is_active',
                                                    'value'=>function($model){
                                                        return $model->is_active == 1 ? 'Yes' : 'No';
                                                    }
                                                ],
                                                // 'created_at',
                                            ],
                                        ]) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
