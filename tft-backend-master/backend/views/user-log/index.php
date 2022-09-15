<?php
$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : "";
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\Breadcrumbs;
use yii\helpers\Url;
use kartik\date\DatePicker;
/* @var $this yii\web\View */
/* @var $searchModel common\models\UserLogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$userName = isset($_GET['user_id']) ? common\models\Admin::findOne($_GET['user_id'])->username : "";
$this->title = 'User Logs: '.$userName;
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
                                    <?php
                                    echo DatePicker::widget([
                                        'name' => 'check_issue_date',
                                        'type' => DatePicker::TYPE_INLINE,
                                        'options' => [
                                            'placeholder' => 'Select issue date ...', 
                                            'id' => 'date-picker',
                                            'style' => 'display:none',
                                        ],
                                        'pluginOptions' => [
                                            'format' => 'dd-mm-yyyy',
                                            'todayHighlight' => true,
                                            'todayBtn' => true,
                                        ],
                                        'pluginEvents' =>[
                                            "changeDate" => "function() {  
                                                window.location = 'workouts?user_id=$user_id'
                                            }",
                                        ] 
                                    ]);
                                    ?>
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