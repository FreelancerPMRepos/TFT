<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\AppsCountriesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Apps Countries');
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
                    echo \yii\widgets\Breadcrumbs::widget([
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
                                        <?= GridView::widget([
                                            'dataProvider' => $dataProvider,
                                            'filterModel' => $searchModel,
                                            'columns' => [
                                                'country_code',
                                                'country_name',
                                                [
                                                    'label' => 'Status',
                                                    'attribute'=>'status',
                                                    'format'=>'raw',
                                                    'filter'=>['1'=>'Enable','0'=>'Disable'],
                                                    'value' => function ($model) {
                                                       if($model->status){
                                                            return '<a href= "'.Url::to(['country/update','id'=>$model->id,'status'=>0], $schema = true).'" class="waves-effect waves-light btn-success btn">Disable</a>';
                                                       }else{
                                                            return '<a href= "'.Url::to(['country/update','id'=>$model->id,'status'=>1], $schema = true).'" class="btn gradient-45deg-indigo-purple gradient-shadow white-text">Enable</a>';
                                                       }
                                                    }
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
