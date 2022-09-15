<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use common\models\Admin;
if(isset($_GET['user_id'])){
    $username = "of ".Admin::findOne($_GET['user_id'])->username;
    $this->params['breadcrumbs'][] = ['label' => 'Routines', 'url' => ['index','user_id'=>$_GET['user_id']]];
}else{
    $username = "";
    $this->params['breadcrumbs'][] = ['label' => 'Routines', 'url' => ['index']];
}

/* @var $this yii\web\View */
/* @var $model common\models\Routines */

$this->title = 'Create Routines '.$username;
$this->params['breadcrumbs'][] = 'Create Routines';
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
                    <a class="btn dropdown-settings waves-effect waves-light breadcrumbs-btn right" href="<?= Url::toRoute(['index','user_id'=>$_GET['user_id']], $schema = true)?>">
                        <i class="material-icons hide-on-med-and-up">settings</i><span class="hide-on-small-onl"><< Back</span>
                    </a> 
                <?php else: ?>
                    <a class="btn dropdown-settings waves-effect waves-light breadcrumbs-btn right" href="<?= Url::toRoute(['index'], $schema = true)?>">
                        <i class="material-icons hide-on-med-and-up">settings</i><span class="hide-on-small-onl"><< Back</span>
                    </a>  
                <?php endif; ?>                          
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
                                        <?= $this->render('_form', [
                                            'model' => $model,
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