<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\Breadcrumbs;
use yii\helpers\Url;
use common\models\Pathways;

/* @var $this yii\web\View */
/* @var $model common\models\Routines */

$this->title = Pathways::findOne(['id'=>$model->pathway_id])->name ." ".$model->day;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Routines'), 'url' => ['index']];
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
                                                'id',
                                                [
                                                    'attribute' => 'pathway_id',
                                                    'value' => $exe_Category = Pathways::findOne(['id'=>$model->pathway_id])->name
                                                ],
                                                'day',
                                                'time_between_last_sets',
                                                'created_at:datetime',
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
