<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\widgets\Breadcrumbs;
use yii\widgets\DetailView;
use yii\helpers\Url;

$this->title = 'Cardio Routine: '.ucfirst($name);
$this->params['breadcrumbs'][] = isset($_GET['id'])? ['label' => 'User', 'url' => ['user/index','id'=>$_GET['id']]] : ['label' => 'User', 'url' => ['user/index']];
$this->params['breadcrumbs'][] = "Cardio Routine";
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
                <a class="btn dropdown-settings waves-effect waves-light breadcrumbs-btn right" href="<?= isset($_GET['id'])? Url::toRoute(['user/index','id'=>$_GET['id']], $schema = true) : Url::toRoute(['user/index'], $schema = true); ?>">
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
                                    <?php if(isset($model)): ?>
                                        <div class="col s12 m12 l12">
                                            <p>
                                                <?php if(isset($_GET['user_id'])): ?>
                                                    <?= Html::a(Yii::t('app', 'view'), ['cardio-view', 'id'=>$model->id, 'user_id'=>$_GET['user_id'], 'name'=>$_GET['name']], ['class' => 'btn btn-primary']) ?>
                                                <?php endif; ?>
                                            </p>
                                            <?= DetailView::widget([
                                                'model' => $model,
                                                'attributes' => [
                                                    [
                                                        'attribute' => 'cardio_type',
                                                        'value' => ucfirst($model->cardio_type),
                                                    ],
                                                    'how_many_day_per_week',
                                                ],
                                            ]) ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="col s12 m12 l12">
                                            <b>DATA NOT FOUND</b>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>