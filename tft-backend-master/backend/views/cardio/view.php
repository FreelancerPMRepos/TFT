<?php
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\widgets\Breadcrumbs;
use yii\widgets\DetailView;
use yii\helpers\Url;
use common\models\Exercise;

$this->title = 'Details';
$this->params['breadcrumbs'][] = ['label' => 'User', 'url' => ['user/index']];
$this->params['breadcrumbs'][] = ['label' => 'Cardio Routine', 'url' => ['routines/cardio','name'=>$_GET['name'],'user_id'=>$_GET['user_id']]];

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
                <a class="btn dropdown-settings waves-effect waves-light breadcrumbs-btn right" href="<?= Url::toRoute(['routines/cardio','name'=>$_GET['name'],'user_id'=>$_GET['user_id']], $schema = true)?>">
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
                <?php if(count($model)): ?>
                    <?php $i = 0; ?>
                    <?php foreach ($model as $key => $value): ?>
                        <div class="card">
                            <div class="card-content mt-2 mb-2">
                                <div class="row">
                                    <div class="col s12 m4">
                                        <!-- <h6 class="">Week: <?= ++$i > 4? $i = 1 : $i ?></h6> -->
                                        <table class="striped">
                                            <tbody>
                                                <tr>
                                                    <td>Day:</td>
                                                    <td><?= $value['day_no'] ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Time:</td>
                                                    <td><?= $value['day_time'] ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Exercise:</td>
                                                    <td><?= Exercise::findOne(['id'=>$value['exe_id']])->name ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?> 
                <?php else: ?>
                    <div class="col s12 m12 l12">
                        <div id="icon-sizes" class="card card-default">
                            <div class="card-content">
                                <b>DATA NOT FOUND</b>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<div>