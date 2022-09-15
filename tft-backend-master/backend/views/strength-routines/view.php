<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use yii\grid\GridView;
use yii\widgets\Pjax;
use common\models\ExerciseCategory;
use common\models\Pathways;

$action     = Yii::$app->controller->action->id;
if($action == 'post'){  $SETS = json_decode(Pathways::findOne(['name'=>'PoST'])->set_data, true); }
if($action == 'sst'){  $SETS = json_decode(Pathways::findOne(['name'=>'SST'])->set_data, true); }
if($action == 'prst'){  $SETS = json_decode(Pathways::findOne(['name'=>'PrST'])->set_data, true); }

$this->title = $provider['routine_name'];
$this->params['breadcrumbs'][] = ['label' => $PathwayTitle, 'url' => [$action]];
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
                                        <?php foreach ($provider['workout_list'] as $key => $value): ?>
                                            
                                            <?php foreach ($provider['workout_list'][$key] as $k => $v): ?>
                                                <table style="color: black">
                                                    <tr>
                                                        <th style="background-color: #f7786b"><?= $provider['workout_list'][$key]['title'] ?></th>
                                                        <th style="background-color: #f7786b"><?= $provider['workout_list'][$key]['workout_title'] ?></th>
                                                    </tr>
                                                </table>
                                                <br>
                                                <?php foreach ($provider['workout_list'][$key]['exe_list'] as $a => $b): ?>

                                                    <?php foreach ($provider['workout_list'][$key]['exe_list'][$a] as $c => $d): ?>
                                                        <table style="color: black">
                                                            <thead>
                                                                <tr>
                                                                    <th colspan="5" style="background-color: #f7786b"><b><?= ExerciseCategory::findOne(['id'=>$provider['workout_list'][$key]['exe_list'][$a]['exe_category_id']])->name ?> - <?= $provider['workout_list'][$key]['exe_list'][$a]['exe_name'] ?></b></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody style="background-color: #f7cac9">
                                                                <tr>
                                                                    <td style="border: 1px solid black; border-collapse: collapse;"><?= "Sets: ".$provider['workout_list'][$key]['exe_list'][$a]['sets'] ?></td>
                                                                    <td style="border: 1px solid black; border-collapse: collapse;"><?= "Reps: ".$provider['workout_list'][$key]['exe_list'][$a]['reps'] ?></td>
                                                                    <td style="border: 1px solid black; border-collapse: collapse;"><?= "Lifting Time: ".$provider['workout_list'][$key]['exe_list'][$a]['lifting_time']."s" ?></td>
                                                                    <td colspan="2" style="border: 1px solid black; border-collapse: collapse;"><?= "Time Between Set: ".$provider['workout_list'][$key]['exe_list'][$a]['time_between_set']."s" ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="2" style="border: 1px solid black; border-collapse: collapse;"><?= "Counter Timer: ".$provider['workout_list'][$key]['exe_list'][$a]['time_unit_countdown']."s" ?></td>
                                                                    <td colspan="3" style="border: 1px solid black; border-collapse: collapse;"><?= "Time Interval Between Body Parts: ".$provider['workout_list'][$key]['exe_list'][$a]['time_between_body_part']."s" ?></td>
                                                                </tr>
                                                                <?php if(!($action == 'ssgst' || $action == 'ssstr')): ?>
                                                                    <?php foreach ($SETS as $key => $value): ?>
                                                                <tr style="background-color: #92a8d1">
                                                                    <td style="border: 1px solid black; border-collapse: collapse;"><?= "REPS: ".$SETS[$key]['reps'] ?></td>
                                                                    <td style="border: 1px solid black; border-collapse: collapse;"><?= "Lifting Time: ".$SETS[$key]['lifting_time']."s" ?></td>
                                                                    <td style="border: 1px solid black; border-collapse: collapse;"><?= "Time Between Set: ".$SETS[$key]['time_between_set']."s" ?></td>
                                                                    <td style="border: 1px solid black; border-collapse: collapse;"><?= "Counter Timer: ".$SETS[$key]['time_unit_countdown']."s" ?></td>
                                                                    <td style="border: 1px solid black; border-collapse: collapse;"><?= "---- Timer: ".$SETS[$key]['countdown_timer']."s" ?></td>
                                                                </tr>
                                                                    <?php endforeach; ?>
                                                                <?php endif; ?>
                                                            </tbody>
                                                        </table>
                                                    <br>
                                                    <?php break; ?>
                                                    <?php endforeach; ?>
                                                <?php endforeach; ?>     
                                            <?php break; ?>            
                                            <?php endforeach; ?>           
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>