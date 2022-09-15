<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use common\widgets\Alert;

AppAsset::register($this);
$controller = Yii::$app->controller->id;

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title>TFT Web Panel</title>
    <link rel="icon" href="<?php echo Yii::$app->request->baseUrl; ?>img_assets/favicons/favicon.ico" type="image/x-icon">
    <?php $this->head() ?>
    <?= Html::csrfMetaTags() ?>
</head>
<style>
.login:before {
    content: "";
    margin-left: -48%;
    background-repeat: no-repeat;
    background-size: auto 100%;
    background-position: 100%;
    width: 100%;
    height: 100%;
    position: absolute;
    top: 0;
    left: 0;
    background-color: #1d40ab !important;
}
</style>
<body>
<?php $this->beginBody() ?>
<div class="flex">
        <?php if(!empty(\Yii::$app->user->identity->id)){?>
            <nav class="side-nav">
                <a href="" class="intro-x flex items-center pl-5 pt-4">
                    <span class="hidden xl:block text-white text-lg ml-3"> TFT <span class="font-medium">-TRAINER PANEL</span> </span>
                </a>
                <div class="side-nav__devider my-6"></div>
                <ul>
                    <li>
                        <a href="<?php echo Url::toRoute(['client/index']);?>" class="side-menu <?= $controller == "client"?"side-menu--active":""?>">
                            <div class="side-menu__icon"> 
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                            </div>
                            <div class="side-menu__title"> Clients </div>
                        </a>
                    </li>
                </ul>
            </nav>
            <div class="content">
                <div class="top-bar">
                    <!-- BEGIN: Breadcrumb -->
                    <div class="-intro-x breadcrumb mr-auto hidden sm:flex"> 
                        <a href="" class="breadcrumb--active">
                            <h2> TFT TRAINER PANEL </h2>
                        </a> 
                    </div>
                    <!-- END: Breadcrumb -->
                    <?php
                    if(!empty(\Yii::$app->user->identity->userAdditionalInfos->photo)){
                        $photo = yii\helpers\Url::base(true).'/img_assets/users/'.Yii::$app->user->identity->userAdditionalInfos->photo;
                    }else{
                        $photo = yii\helpers\Url::base(true).'/img_assets/users/default.png';
                    }
                    ?>
                    <!-- BEGIN: Account Menu -->
                    <div class="intro-x dropdown w-8 h-8">
                        <div class="dropdown-toggle w-8 h-8 rounded-full overflow-hidden shadow-lg image-fit zoom-in">
                            <img alt="Midone Tailwind HTML Admin Template" src="<?=$photo;?>">
                        </div>
                        <div class="dropdown-box w-56">
                            <div class="dropdown-box__content box bg-theme-38 dark:bg-dark-6 text-white">
                                <div class="p-4 border-b border-theme-40 dark:border-dark-3">
                                    <div class="font-medium"><?=\Yii::$app->user->identity->username;?></div>
                                    <div class="text-xs text-theme-41 dark:text-gray-600"><?=\Yii::$app->user->identity->email;?></div>
                                </div>
                                
                                <div class="p-2 border-t border-theme-40 dark:border-dark-3">
                                    <a href="<?= yii\helpers\Url::toRoute(['site/logout']); ?>" class="flex items-center block p-2 transition duration-300 ease-in-out hover:bg-theme-1 dark:hover:bg-dark-3 rounded-md"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-toggle-right w-4 h-4 mr-2"><rect x="1" y="5" width="22" height="14" rx="7" ry="7"></rect><circle cx="16" cy="12" r="3"></circle></svg> Logout </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END: Account Menu -->
                </div>
                <?= $content ?>
            </div>
        <?php }else{ ?>
            <?= $content ?>
        <?php } ?>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
