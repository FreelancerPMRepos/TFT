<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\MailManagement */

$this->title = 'Update Mail Management: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Mail Managements', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="mail-management-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
