<?php

use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\helpers\Url;
use yii\widgets\Pjax;

$action     = Yii::$app->controller->action->id;
$this->title = $PathwayTitle;
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

                                        <?php if($action == 'ssstr'): ?>
                                        <?= $this->render('_formSSSTR', [
                                            'model' => $model,
                                        ]) ?>
                                        <?php endif; ?>

                                        <?php if($action == 'ssgst'): ?>
                                        <?= $this->render('_formSSGST', [
                                            'model' => $model,
                                        ]) ?>
                                        <?php endif; ?>

                                        <?php if($action == 'post'): ?>
                                        <?= $this->render('_form', [
                                            'model' => $model,
                                        ]) ?>
                                        <?php endif; ?>

                                        <?php if($action == 'prst'): ?>
                                        <?= $this->render('_form', [
                                            'model' => $model,
                                        ]) ?>
                                        <?php endif; ?>

                                        <?php if($action == 'sst'): ?>
                                        <?= $this->render('_form', [
                                            'model' => $model,
                                        ]) ?>
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
</div> 
