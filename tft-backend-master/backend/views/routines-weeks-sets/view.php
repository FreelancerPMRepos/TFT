<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\RoutinesWeeksSets */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Routines Weeks Sets', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="routines-weeks-sets-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
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
            'id',
            'routine_week_id',
            'set_no',
            'reps',
            'weight',
            'lifting_time:datetime',
            'time_unit_countdown:datetime',
            'coutdown_timer:datetime',
            'time_between_set:datetime',
        ],
    ]) ?>

</div>
