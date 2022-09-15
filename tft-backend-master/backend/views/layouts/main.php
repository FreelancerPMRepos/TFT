<?php

/* @var $this \yii\web\View */
/* @var $content string */
$action = Yii::$app->controller->action->id;
$controller = Yii::$app->controller->id;

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

AppAsset::register($this);
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <?php $this->head() ?>
</head>
<body class="vertical-layout vertical-menu-collapsible page-header-dark vertical-modern-menu 2-columns  " data-open="click" data-menu="vertical-modern-menu" data-col="2-columns">

<?php $this->beginBody() ?>
<?php include('_header.php');?>
<?php include('_sidebar.php');?>
<div id="main">
<?=$content;?>
</div>
<?php 
$this->endBody() ?>
<script>
var baseUrl = "<?php echo Url::base(true);//$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].'/administration';?>";
$(document).ready(function() {
    $("#exe_category_id").on('change', function () {
        var id = $(this).val();
        $.ajax({
            url:        "exercise",
            type:       "POST",
            datatype :  "json",
            data: {
                id : $(this).val(),
            },
            success: function(data){
                if(data==0){
                    $("#routinesweeks-exe_id").empty();
                    $("#routinesweeks-exe_id").append("<option value='prompt'>Select Exercise Name</option>");
                }else{
                    $("#routinesweeks-exe_id").empty();
                    $("#routinesweeks-exe_id").append("<option value='prompt'>Select Exercise Name</option>");
                    $.each(data, function(key, value) {
                        $("#routinesweeks-exe_id").append("<option value="+key+">"+value+"</option>");
                    });
                }
            },
            error: function(xhr){
                alert("failure"+xhr.readyState+this.url)
            }
        });
    });
});
</script>
</body>
</html>
<?php $this->endPage() ?>

