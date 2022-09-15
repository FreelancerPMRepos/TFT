<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use yii\widgets\Breadcrumbs;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $searchModel common\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $title;
$this->params['breadcrumbs'][] = $this->title;
$countryList = ArrayHelper::map(\common\models\AppsCountries::find()->where(1)->asArray()->all(),'id','country_code');

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
                    <a href = "<?= Url::to(['user/create-admin'], $schema = true)?>" class="btn dropdown-settings waves-effect waves-light breadcrumbs-btn right" >
                        Add
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
                                    <?php Pjax::begin(); ?>
                                        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

                                        <?= GridView::widget([
                                            'dataProvider' => $dataProvider,
                                            'filterModel' => $searchModel,
                                            'columns' => [
                                                ['class' => 'yii\grid\SerialColumn'],
                                                [
                                                    'class' => 'yii\grid\ActionColumn',
                                                    'template' => '{view}&nbsp{update}&nbsp{delete}',
                                                    'header'=>'Action',
                                                    'buttons' => [
                                                        'update'=>function ($url,$model) {
                                                            return Html::a('<i class="material-icons">edit</i>', "update-admin?id=".$model->id, ['class'=>'btn btn-lg btn-blue']);
                                                        },
                                                        'delete'=>function ($url,$model) {
                                                            return  Html::a('<i class="material-icons">delete</i>', $url, [
                                                                'class' => 'btn btn-danger',
                                                                'data' => [
                                                                    'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                                                    'method' => 'post',
                                                                ],
                                                            ]);
                                                            // return Html::a('<i class="material-icons">delete</i>', "delete-admin?id=".$model->id, ['class'=>'btn btn-lg btn-blue','data'=>['method'=>'post']]);
                                                        },   
                                                    ],
                                                ],
                                                // 'id',
                                                'username',
                                                // 'auth_key',
                                                // 'access_token_expired_at',
                                                // 'password_hash',
                                                //'password_reset_token',
                                                'email:email',
                                                //'unconfirmed_email:email',
                                                //'confirmed_at',
                                                'registration_ip',
                                                [
                                                    'attribute' => 'last_login_at',
                                                    'value' => function ($model) {
                                                        return date('d-m-Y H:i:s', $model->last_login_at);
                                                    },
                                                ],
                                                //'last_login_ip',
                                                //'blocked_at',
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
                                                //'role',
                                                //'user_type',
                                                //'social_provider_id',
                                                //'social_type',
                                                //'created_at',
                                                //'updated_at',

                                                ['class' => 'yii\grid\ActionColumn'],
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
    </div>
</div>