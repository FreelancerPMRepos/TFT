<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\Breadcrumbs;
use yii\helpers\ArrayHelper;

use common\models\Admin;
if(isset($_GET['user_id'])){
    $username = Admin::findOne($_GET['user_id']) ? $username = "of ".Admin::findOne($_GET['user_id'])->username : $username = "";
}else{
    $username = "";
}

use yii\helpers\Url;
use common\models\Pathways;
$pathways = Pathways::find()->select(['id','name'])->orderBy("name ASC")->asArray()->all();
$pathways = ArrayHelper::map($pathways,'id','name');
// print_r($pathways);die;
/* @var $this yii\web\View */
/* @var $searchModel common\models\RoutinesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Routines '.$username;
$this->params['breadcrumbs'][] = 'Routines';
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
                    <?php if(isset($_GET['user_id'])): ?>
                        <a href = "<?= Url::to(['create','user_id'=>$_GET['user_id']], $schema = true)?>" class="btn dropdown-settings waves-effect waves-light breadcrumbs-btn right" >
                            Add Routines
                        </a>
                    <?php else: ?>
                        <a href = "<?= Url::to(['create'], $schema = true)?>" class="btn dropdown-settings waves-effect waves-light breadcrumbs-btn right" >
                            Add Routines
                        </a>
                    <?php endif; ?>
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
                                                            'attribute' => 'pathway_id',
                                                            'filter' => $pathways,
                                                            'value' => function($model){
                                                                return Pathways::findOne($model->pathway_id)->name;
                                                            }
                                                        ],
                                                        [
                                                            'attribute' => 'day',
                                                            'filter' => ['1' => '1','2' => '2','3' => '3','3' => '4','5' => '5','6' => '6','7' => '7'],
                                                        ],
                                                        // 'time_between_last_sets:datetime',
                                                        //custome
                                                        [
                                                            'attribute'=>'time_between_last_sets',
                                                            // 'value'=> 'time_between_last_sets',
                                                            // 'format' => ['date', 'php:d/m/Y'],
                                                            'filter'=>false,
                                                            'enableSorting'=>true
                                                        ],
                                                        [
                                                            'class' => 'yii\grid\ActionColumn',
                                                            'template' => '{create}&nbsp{update}&nbsp{delete}',
                                                            'header'=>'Action',
                                                            'buttons' => [   
                                                                'create'=>function ($url,$model) {
                                                                    // return Html::a('Exercise Mapping', "mapping?id=".$model->id."&name=".$model->name, ['class'=>'btn btn-lg btn-blue','data' => ['method' => 'post','data-pjax' => false]]);
                                                                    return Html::a('Exercise Mapping', "mapping?id=".$model->id, ['class'=>'btn btn-lg btn-blue','data' => ['method' => 'post','data-pjax' => false]]);
                                                                },  
                                                                'update'=>function ($url) {
                                                                    return Html::a('<i class="material-icons">edit</i>', $url, ['class'=>'btn btn-lg btn-blue','data' => ['method' => 'post']]);
                                                                },
                                                                'delete'=>function ($url) {
                                                                    return  Html::a('<i class="material-icons">delete</i>', $url, [
                                                                        'class' => 'btn btn-lg btn-blue',
                                                                        'data' => [
                                                                            'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                                                            'method' => 'post',
                                                                        ],
                                                                    ]);
                                                                    // return Html::a('<i class="material-icons">delete</i>', $url, ['class' => 'btn btn-lg btn-blue', 'data' => ['method' => 'post']]);
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
