<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\widgets\Breadcrumbs;

/* @var $this yii\web\View */
/* @var $model common\models\Log */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Admin', 'url' => ['admin']];
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
                <a class="btn dropdown-settings waves-effect waves-light breadcrumbs-btn right" href="<?= Url::toRoute(['admin'], $schema = true)?>">
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
                                            <?= Html::a('Update', ['update-admin', 'id' => $model->id], [
                                                'class' => 'btn btn-danger',
                                            ]) ?>
                                            <?= Html::a('Delete', ['delete-admin', 'id' => $model->id], [
                                                'class' => 'btn btn-danger',
                                                'data' => [
                                                    'confirm' => 'Are you sure you want to delete this item?',
                                                    'method' => 'post',
                                                ],
                                            ]) ?>
                                        </p>
										<?= DetailView::widget([
											'model' => $model,
											'attributes' => [
												// 'id',
												'username',
												// 'auth_key',
												// 'access_token_expired_at',
												// 'password_hash',
												// 'password_reset_token',
												'email:email',
												// 'unconfirmed_email:email',
												[
                                                    'attribute' => 'confirmed_at',
                                                    'value' => function ($model) {
                                                        return date('d-m-Y H:i:s', $model->confirmed_at);
                                                    },
                                                ],
												'registration_ip',
												[
                                                    'attribute' => 'last_login_at',
                                                    'value' => function ($model) {
                                                        return date('d-m-Y H:i:s', $model->last_login_at);
                                                    },
                                                ],
												'last_login_ip',
												// 'blocked_at',
												[
                                                    'attribute' => 'status',
                                                    'value' => function ($model) {
                                                        if($model->status == 10){
															return 'Active';
														}
														else if($model->status == 1){
															return 'Pending';
														}
														else{
															return 'Disabled';
														}
                                                    },
                                                ],
												// 'role',
												// 'user_type',
												// 'social_provider_id',
												// 'social_type',
												[
                                                    'attribute' => 'created_at',
                                                    'value' => function ($model) {
                                                        return date('d-m-Y H:i:s', $model->created_at);
                                                    },
                                                ],
												// 'updated_at',
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
