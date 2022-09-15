<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\RoutinesWeeksSetsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Routines Weeks Sets';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="routines-weeks-sets-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Routines Weeks Sets', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'routine_week_id',
            'set_no',
            'reps',
            'weight',
            //'lifting_time:datetime',
            //'time_unit_countdown:datetime',
            //'coutdown_timer:datetime',
            //'time_between_set:datetime',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
