<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Breadcrumbs;

/* @var $this yii\web\View */
/* @var $searchModel common\models\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Postman';
$this->params['breadcrumbs'][] = $this->title;
$uList = \Yii::$app->general->userList();
// print_r($data);die;
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
                        <div id="icon-sizes" class="">
                            <div class="card-content">
                                <div class="row">
                                    <div class="col s2">
                                        <ul class="collapsible collapsible-accordion" data-collapsible="accordion">
                                            <?php foreach($data as $controller => $actions){?> 
                                                        <li class="">
                                                            <div class="collapsible-header" tabindex="0"><?= $controller;?></div>
                                                            <div class="collapsible-body" style="">
                                                            <ul class="" data-collapsible="accordion">
                                                            <?php foreach($actions as $k => $action){
                                                                ?>
                                                                <li class="call-action" data-url="<?=$action['url'];?>"  data-method="<?=isset($action['data']['method'])?$action['data']['method']:"";?>"
                                                                 data-attributes='<?= isset($action['data']['attributes'])?json_encode($action['data']['attributes']):"";?>'>
                                                                    <div class="collapsible-header" tabindex="0">
                                                                        <?= $action['name'];?>
                                                                    </div>
                                                                </li>
                                                            <?php } ?>
                                                            </ul>
                                                            </div>
                                                        </li> 
                                            <?php } ?>                                                                                   
                                        </ul>
                                    </div>
                                   
                                    <div class="col s5 card ">
                                        <div class="card-content">
                                            <?php $form = \yii\widgets\ActiveForm::begin([
                                            'action' => ['index'],
                                            'method' => 'post',
                                            'options'=>[
                                                'id'=>'postman-form',
                                                'enctype'=>"multipart/form-data" 
                                            ]
                                            ]); ?>
                                            <div class="row">                                               
                                                <div class="col s12">
                                                    <div class="input-field col s12">
                                                        <?= $form->field($model, 'url')->textInput(['id'=>"url"])->label('<p class="">Url</p>');?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col s6">
                                                    <div class="input-field col s12">
                                                        <?= $form->field($model, 'method')->textInput(['id'=>"method"])->label('<p class="">Method</p>');?>
                                                    </div>
                                                </div>
                                                <div class="col s6">
                                                    <div class="input-field col s12">
                                                    <?= $form->field($model, 'user_id')->dropDownList($uList,['class'=>'fstdropdown-select fstdropdown','prompt'=>'None'])
                                                    ->label('<p class="">Select User</p>'); ?>
                                                    </div>
                                                </div>                                              
                                            </div>
                                            <div class="row"> 
                                                <div class="col s12">
                                                    <div class="input-field col s12">
                                                        <div class="params-holder">
                                                            <div class="row">
                                                                <div class="col s12">
                                                                    <table>
                                                                        <thead>
                                                                            <tr>
                                                                            <th data-field="id">Key</th>
                                                                            <th data-field="name">Value</th>
                                                                            <th data-field="price">Description</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody id = "pbody">
                                                                           
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>                                                    
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row"> 
                                                <div class="col s10">
                                                    <div class="input-field col s12">
                                                            <?= Html::submitButton(Yii::t('app', 'Send'), ['class' => 'btn btn-primary']) ?>
                                                    </div>               
                                                </div>
                                            </div>
                                            <?php ActiveForm::end(); ?>               
                                        </div>  
                                    </div>  
                                    <div class="col s5 card ">
                                            <label class="control-label" for="method"><p class="">Response</p></label>
                                            <pre id="res" class="form-control"  style="
    overflow: scroll; margin: 0px; height: 598px; width: 100%;    background: #171212;
    color: #8ce623;"></pre>
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
                                         