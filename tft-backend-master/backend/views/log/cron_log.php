<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Breadcrumbs;
use yii\widgets\Pjax;
use yii2mod\cron\models\enumerables\CronScheduleStatus;

/* @var $this yii\web\View */
/* @var $searchModel common\models\LogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cron Log';
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
                 <a href = "<?= Url::to(['log/clear-cron'], $schema = true)?>" class="btn dropdown-settings waves-effect waves-light breadcrumbs-btn right" >
                 Clear log</a>
                  
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
                                                    'jobCode',
                                                    [
                                                        'attribute' => 'status',
                                                        'value' => function ($model) {
                                                            return CronScheduleStatus::getLabel($model->status);
                                                        },
                                                        'filter' => CronScheduleStatus::listData(),
                                                        'filterInputOptions' => ['prompt' => Yii::t('yii2mod-cron-log', 'Select Status'), 'class' => 'form-control'],
                                                    ],
                                                    'messages:ntext',
                                                    'dateCreated',
                                                    'dateFinished',
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