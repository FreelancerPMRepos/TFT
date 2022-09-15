<?php
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\grid\GridView; 
use yii\widgets\Breadcrumbs;
use yii\helpers\Url;
use common\models\UserLog;
use common\models\Exercise;
use jino5577\daterangepicker\DateRangePicker;
use yii\widgets\ActiveForm; 

$action = Yii::$app->controller->action->id;
$userName = isset($_GET['user_id']) ? common\models\Admin::findOne($_GET['user_id'])->username : "";
/* @var $this yii\web\View */
/* @var $model common\models\UserLog */

$this->title = 'View';
$this->params['breadcrumbs'][] =  isset($_GET['user_id'])? ['label' => 'User Logs: '.$userName, 'url' => ['index','user_id'=>$_GET['user_id']]] : ['label' => 'User Logs', 'url' => ['index']];
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

                                        <?php if(isset($_GET['user_id'])): ?>

                                            <ul class="tabs">
                                                <li class="tab col m3"><a class="<?= $action == 'workouts'?  "active" : "" ?>" onclick="window.location='workouts?user_id=<?= $_GET['user_id'] ?>';">     Workouts     </a></li>
                                                <li class="tab col m3"><a class="<?= $action == 'notes'?  "active" : "" ?>" onclick="window.location='notes?user_id=<?= $_GET['user_id'] ?>';">           Notes        </a></li>
                                                <li class="tab col m3"><a class="<?= $action == 'photos'?  "active" : "" ?>" onclick="window.location='photos?user_id=<?= $_GET['user_id'] ?>';">         Photos       </a></li>
                                                <li class="tab col m3"><a class="<?= $action == 'body-stats'?  "active" : "" ?>" onclick="window.location='body-stats?user_id=<?= $_GET['user_id'] ?>';"> Body Stats   </a></li>
                                            </ul>

                                            <?php if($action == 'workouts'): $models = $provider['data']['items']  ?>
                                                <div class="col s12 mt-2">
                                                    <?php if($models):  ?>
                                                        <?php $form = ActiveForm::begin(); ?>
                                                        <div class="row mb-1">
                                                            <div class="col s6">
                                                                <?= $form->field($model, 'created_at_range')->widget(
                                                                        DateRangePicker::className(),[
                                                                            'model' => $model,
                                                                            'attribute' => 'created_at_range',
                                                                            'pluginOptions' => [
                                                                                'format' => 'd-m-Y',
                                                                                'autoUpdateInput' => false
                                                                            ]
                                                                        ]
                                                                    );
                                                                ?>
                                                            </div>
                                                            <div class="col s6 mt-2">
                                                                <?= Html::submitButton('Fliter', ['class' => 'btn btn-success']) ?>
                                                            </div>
                                                        </div>
                                                        <?php ActiveForm::end(); ?>
                                                        <?php foreach ($models as $key => $value): ?>
                                                            <table class="col s12 mb-1">
                                                                <thead style="background-color: #034f84; color: #fff;">
                                                                    <tr>
                                                                        <th colspan="3">
                                                                            <b>
                                                                                <?= $models[$key]['exe'] ?><br>
                                                                                <?= $models[$key]['exe_category'] ?> | <?= $models[$key]['week_no'] ?> - <?= $models[$key]['day'] ?> - <?= $models[$key]['workout_title'] ?>
                                                                            </b>        
                                                                        </th>
                                                                        <th style="text-align: center; vertical-align: middle;">
                                                                            <a class="waves-effect waves-light btn modal-trigger" href="#modal<?= $key ?>"><i class="material-icons">visibility</i></a>
                                                                            <!-- Modal Structure -->
                                                                            <div id="modal<?= $key ?>" class="modal">
                                                                                <div class="modal-content">
                                                                                    <h5 style="text-align: left;"><?= $models[$key]['week_no'] ?> - Routine: <?= explode(" ",$models[$key]['workout_title'])[1] ?> - <?= $models[$key]['day'] ?><br>
                                                                                    sets(<?= $models[$key]['total_sets'] ?>):
                                                                                    </h5>
                                                                                    <table>
                                                                                        <thead style="background-color: #f7786b; color: #fff;">
                                                                                            <tr>
                                                                                                <th>Sets</th>
                                                                                                <th>Weight</th>
                                                                                                <th>Reps</th>
                                                                                                <th>Lift Time</th>
                                                                                            </tr>
                                                                                        </thead>
                                                                                    <?php foreach ($models[$key]['completed_sets'] as $k => $v): ?>
                                                                                            <tbody style="background-color: #f7cac9; color: black;">
                                                                                                <tr>
                                                                                                    <td><?= $models[$key]['completed_sets'][$k]['set_no'] ?></td>
                                                                                                    <td><?= $models[$key]['completed_sets'][$k]['weight'] ?></td>
                                                                                                    <td><?= $models[$key]['completed_sets'][$k]['reps'] ?></td>
                                                                                                    <td><?= $models[$key]['completed_sets'][$k]['lifting_time'] ?></td>
                                                                                                </tr>
                                                                                            </tbody>
                                                                                    <?php endforeach; ?>
                                                                                    </table>
                                                                                </div>
                                                                            </div>
                                                                        </th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody style="background-color: #92a8d1; color: black;">
                                                                    <tr>
                                                                        <td style="border: 1px solid black; border-collapse: collapse;">No of Sets</td>
                                                                        <td style="border: 1px solid black; border-collapse: collapse;">Total Weight</td>
                                                                        <td style="border: 1px solid black; border-collapse: collapse;">Total Reps</td>
                                                                        <td style="border: 1px solid black; border-collapse: collapse;">Total Lift Time</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="border: 1px solid black; border-collapse: collapse;"><?= $models[$key]['total_sets'] ?></td>
                                                                        <td style="border: 1px solid black; border-collapse: collapse;"><?= $models[$key]['total_weight'] ?></td>
                                                                        <td style="border: 1px solid black; border-collapse: collapse;"><?= $models[$key]['total_reps'] ?></td>
                                                                        <td style="border: 1px solid black; border-collapse: collapse;"><?= $models[$key]['total_lifting_time'] ?></td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <b>DATA NOT FOUND</b>     
                                                    <?php endif; ?>     
                                                </div>
                                            <?php endif; ?>

                                            <?php if($action == 'notes'):  ?>
                                                <?php Pjax::begin(); ?>
                                                    <?= GridView::widget([
                                                        // 'summary'=> false,
                                                        'dataProvider' => $dataProvider,
                                                        'filterModel' => $searchModel,
                                                        'columns' => [
                                                            ['class' => 'yii\grid\SerialColumn'],
                                                            [   
                                                                'attribute' => 'userLog.log_date',
                                                                'label'     => 'Log Date', 
                                                                'value'     => function($model){
                                                                    return date('d-m-Y', $model['userLog']['log_date']);
                                                                },
                                                                'filter' => DateRangePicker::widget([
                                                                    'model' => $searchModel,
                                                                    'attribute' => 'created_at_range',
                                                                    'pluginOptions' => [
                                                                        'format' => 'd-m-Y',
                                                                        'autoUpdateInput' => false
                                                                    ]
                                                                ])
                                                            ],
                                                            [
                                                                'attribute' => 'userLog.exe_id',
                                                                'label'     => 'Exercise', 
                                                                'value'     => function($model){
                                                                    return Exercise::findOne(['id'=>$model->exe_id])->name;
                                                                }
                                                            ],
                                                            'notes',
                                                        ],
                                                    ]); ?>
                                                <?php Pjax::end(); ?>
                                            <?php endif; ?> 

                                            <?php if($action == 'photos'):  ?>
                                                <?php Pjax::begin(); ?>
                                                    <?= GridView::widget([
                                                        // 'summary'=> false,
                                                        'dataProvider' => $dataProvider,
                                                        'filterModel' => $searchModel,
                                                        'columns' => [
                                                            ['class' => 'yii\grid\SerialColumn'],
                                                            [   
                                                                'attribute' => 'userLog.log_date',
                                                                'label'     => 'Log Date', 
                                                                'value'     => function($model){
                                                                    return date('d-m-Y', $model['userLog']['log_date']);
                                                                },
                                                                'filter' => DateRangePicker::widget([
                                                                    'model' => $searchModel,
                                                                    'attribute' => 'created_at_range',
                                                                    'pluginOptions' => [
                                                                    'format' => 'd-m-Y',
                                                                    'autoUpdateInput' => false
                                                                ]
                                                                ])
                                                            ],
                                                            [
                                                                'attribute' => 'photo',
                                                                'label'     => 'Photo',
                                                                'filter'    =>false,
                                                                'format'    => ['image', ['width' => '200']],
                                                                'value'     => function ($model) {
                                                                    $default_Image  = Yii::$app->request->baseUrl."/../img_assets/upload/nophotoavailable.jpg";
                                                                    if(!empty($model->photo)){
                                                                        $basePath   =  Yii::getAlias('@webroot/../../img_assets/user_log/'.$model->photo);
                                                                        if(file_exists($basePath)){
                                                                            return Yii::$app->request->baseUrl."/../img_assets/user_log/".$model->photo. "?r=".rand();
                                                                        }else{
                                                                            return $default_Image;
                                                                        }
                                                                    }else{
                                                                        return $default_Image;
                                                                    }
                                                                }
                                                            ],
                                                            'tag',
                                                        ],
                                                    ]); ?>
                                                <?php Pjax::end(); ?>
                                            <?php endif; ?> 

                                            <?php if($action == 'body-stats'):  ?>
                                                <?php Pjax::begin(); ?>
                                                    <?= GridView::widget([
                                                        // 'summary'=> false,
                                                        'dataProvider' => $dataProvider,
                                                        'filterModel' => $searchModel,
                                                        'columns' => [
                                                            ['class' => 'yii\grid\SerialColumn'],
                                                            [   
                                                                'attribute' => 'userLog.log_date',
                                                                'label'     => 'Log Date', 
                                                                'value'     => function($model){
                                                                    return date('d-m-Y', $model['userLog']['log_date']);
                                                                },
                                                                'filter' => DateRangePicker::widget([
                                                                    'model' => $searchModel,
                                                                    'attribute' => 'created_at_range',
                                                                    'pluginOptions' => [
                                                                    'format' => 'd-m-Y',
                                                                    'autoUpdateInput' => false
                                                                ]
                                                                ])
                                                            ],
                                                            'body_part',
                                                            [
                                                                'attribute' => 'value',
                                                                'label'     => 'Value',
                                                                'value'     => function($model){
                                                                    return $model->value." ".$model->value_unit;
                                                                } 
                                                            ],
                                                        ],
                                                    ]); ?>
                                                <?php Pjax::end(); ?>
                                            <?php endif; ?>      

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