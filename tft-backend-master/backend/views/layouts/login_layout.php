<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\LoginAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

LoginAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title>TFT - Admin Panel</title>    
    <link rel="shortcut icon" href="<?php echo Yii::$app->request->baseUrl; ?>/../img_assets/favicons/favicon.ico" type="image/x-icon">
     <link rel="icon" href="<?php echo Yii::$app->request->baseUrl; ?>/../img_assets/favicons/favicon.ico" type="image/x-icon">
    <meta name="theme-color" content="#ffffff">
    
    <?php $this->head() ?>
</head>
<body class="tab login-background dialogState">
<?php $this->beginBody() ?>
    <div id="contextMenu" class="contextMenu dropdownMenu">
        <ul></ul>
    </div>
    <?=$content;?>   
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>



