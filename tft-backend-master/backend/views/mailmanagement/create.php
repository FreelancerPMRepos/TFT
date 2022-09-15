<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\MailManagement */

$this->title = 'Create Mail Management';
$this->params['breadcrumbs'][] = ['label' => 'Mail Managements', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mail-management-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
