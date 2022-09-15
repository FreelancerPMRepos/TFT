<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

/* @var $this yii\web\View */
/* @var $model common\models\Sports */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sports'), 'url' => ['index']];
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
                                            <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                                            <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                                                'class' => 'btn btn-danger',
                                                'data' => [
                                                    'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                                    'method' => 'post',
                                                ],
                                            ]) ?>
                                        </p>

                                        <?= DetailView::widget([
                                            'model' => $model,
                                            'attributes' => [
                                                // 'id',
                                                'name',
                                                [
                                                    'label' => 'Image',
                                                    'format' => ['image', ['width' => '200']],
                                                    'value' => function ($model) {
                                                        $default_Image = Yii::$app->request->baseUrl."/../img_assets/upload/nophotoavailable.jpg";
                                                        if($model->images){
                                                            $basePath =  Yii::getAlias('@webroot/../../img_assets/sports/'.$model->images);
                                                            if(file_exists($basePath)){
                                                                return Yii::$app->request->baseUrl."/../img_assets/sports/".$model->images."?r=".rand();
                                                            }else{
                                                                return $default_Image;
                                                            }
                                                        }else{
                                                            return $default_Image;
                                                        }
                                                    },
                                                ],
                                                [
                                                    'attribute'=>'active',
                                                    'value'=>function($model){
                                                        return $model->active == 1 ? 'Yes' : 'No';
                                                    }
                                                ],
                                                // 'created_at:datetime',
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
</div>
