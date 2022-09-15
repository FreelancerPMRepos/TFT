<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use common\models\Routines;

$user_id = Routines::findOne(['id'=>$_GET['id']])->user_id;

/* @var $this yii\web\View */
/* @var $model common\models\RoutinesWeeks */

$this->title = 'Weekly Exercise Mapping';
$this->params['breadcrumbs'][] = ['label' => 'Routine', 'url' => ['index','user_id'=>$user_id]];
// $this->params['breadcrumbs'][] = $this->title;
$this->params['breadcrumbs'][] = 'Weekly Exercise Mapping';
?>
<div class="routines-weeks-create">
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
                <a class="btn dropdown-settings waves-effect waves-light breadcrumbs-btn right" href="<?= Url::toRoute('index?user_id='.$user_id, $schema = true)?>">
                    <i class="material-icons hide-on-med-and-up">settings</i><span class="hide-on-small-onl">Back</span>
                </a>                            
                </div>
            </div>
        </div>
    </div>  
    <?= $this->render('_form', [
        'model' => $model,
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
    ]) ?>
</div>
