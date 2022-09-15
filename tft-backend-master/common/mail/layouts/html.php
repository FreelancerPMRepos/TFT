<?php
use yii\helpers\Html;
use yii\helpers\Url;
/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\MessageInterface the message being composed */
/* @var $content string main view render result */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>" />
    <title><?= Html::encode($this->title) ?></title>
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet"> 
    <?php $this->head() ?>
</head>    
<body style="margin: 0; padding: 0;font-family:'Roboto" >
  <div class="welCome-main" style="background-color: #eee; padding: 10px 0;">
      <div style="min-width: 300px; height: auto; margin: 20px auto;  background-color: #fff; max-width: 800px; display: table;">
          <div class="header" style=" text-align: center; display: table;  width: 100%; min-width: 300px;">              
            <div class="logo" style="padding: 10px 10px 10px 10px; display: table-row; background: #f7c83d; ">
                <a href="<?=Url::base(true);?>" style="display: table-cell;" >
                    <img src="<?=Url::base(true);?>/img_assets/logo.png" alt="logo" style="width: 140px; height: auto;"> 
                    <!-- <p><?= Yii::$app->name;?></p> -->
                </a> 
               
            </div>
            <div style=" height: 4px; width: 100%; 
                        background: #000; /* Old browsers */
                        background: -moz-linear-gradient(top, #000 0%, #000 100%); /* FF3.6-15 */
                        background: -webkit-linear-gradient(top, #000 0%,#000 100%); /* Chrome10-25,Safari5.1-6 */
                        background: linear-gradient(to bottom, #000 0%,#000 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
                        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#000', endColorstr='#000',GradientType=0 ); /* IE6-9 */">

              </div>
          </div>
          <div width="100%">
             <?= $content;?>
          </div>
          <div class="stor-btn" style="display: table;text-align: center;width: 50%;margin: 0 auto;min-width: 300px;">
                <div style="width: 50%;display: table-cell;">
                    <a href="<?=\Yii::$app->setting->val('ios_app_link');?>"><img src="<?=Url::base(true);?>/img_assets/mai_assets/isostor.png" alt="iso"></a>
                </div>
                <div style="width: 50%;display: table-cell;">
                    <a href="<?=\Yii::$app->setting->val('android_app_link');?>"><img src="<?=Url::base(true);?>/img_assets/mai_assets/googleplay.png" alt="iso"></a> 
                </div>
          </div>
          
          <div style="padding: 10px 0 10px 0; text-align: center;">
            <p style="font-family:'Roboto', sans-serif; font-size: 16px;  letter-spacing: 0.5px; color: #484848;" > 
            <img src="<?=Url::base(true);?>/img_assets/mai_assets/like.png" alt="like-icon" style="width: 22px;height: 22px; vertical-align: middle;">
            <?= Yii::$app->name;?></p>
          </div>      
          
          <div class="footer" style="background-color: #000 ;padding: 10px 0 24px 0; text-align: left;  display: table;width: 100%;min-width: 300px; text-align: center;">
              <div style="display: table;  clear: both; width: 100%;">
                    <div class="social-top" style="vertical-align: top; display: table; padding: 10px 10px 20px 10px; width: 100%">
                            <ul  style="margin: 0; padding: 0;">
                                <li style="display: inline-block; margin: 0 3px;"><a href="<?=\Yii::$app->setting->val('facebook');?>" style="display: inline-block;"><img src="<?=Url::base(true);?>/img_assets/mai_assets/facebook.png" alt="facebook" style="width: 40px; height: 40px;"></a></li>
                                <li style="display: inline-block; margin: 0 3px;"><a href="<?=\Yii::$app->setting->val('instagram');?>" style="display: inline-block;"><img src="<?=Url::base(true);?>/img_assets/mai_assets/instagram.png" alt="tweet" style="width: 40px; height: 40px;"></a></li>
                                <li style="display: inline-block; margin: 0 3px;"><a href="<?=\Yii::$app->setting->val('youtube');?>" style="display: inline-block;"><img src="<?=Url::base(true);?>/img_assets/mai_assets/youtube.png" alt="google-plas" style="width: 40px; height: 40px;"></a></li>
                            </ul>
                        </div>
                    <div class="menu-footer" style=" display: table; padding: 14px 0 20px 0; width: 100%;">
                            <ul style="margin: 0;  padding: 0;">
                            <li style="display: inline-block; margin: 0 5px;"><a href="<?=Url::toRoute(['site/about'], $schema = true);;?>" style="display: inline-block; font-family:'Roboto', sans-serif; font-size: 14px; text-decoration: none; color: #fff; text-transform: uppercase;">About us </a> </li> <span style="display:inline-block; width: 8px; height: 8px; background-color: #ddd; border-radius: 2px;"></span>
                              <li style="display: inline-block; margin: 0 5px;"><a href="<?=Url::toRoute(['site/contact'], $schema = true);;?>" style="display: inline-block; font-family:'Roboto', sans-serif; font-size: 14px; text-decoration: none; color: #fff; text-transform: uppercase;">Contact us </a> </li> <span style="display:inline-block; width: 8px; height: 8px; background-color: #ddd; border-radius: 2px;"></span>

                              <li style="display: inline-block; margin: 0 5px;"><a href="#" style="display: inline-block; font-family:'Roboto', sans-serif; font-size: 14px; text-decoration: none; color: #fff; text-transform: uppercase;">Terms </a> </li> <span style="display:inline-block; width: 8px; height: 8px; background-color: #ddd; border-radius: 2px;"></span>
                              <li style="display: inline-block; margin: 0 5px;"><a href="#" style="display: inline-block; font-family:'Roboto', sans-serif; font-size: 14px;text-decoration: none; color: #fff; text-transform: uppercase;">Support </a> </li> <span style="display:inline-block; width: 8px; height: 8px; background-color: #ddd; border-radius: 2px;"></span>
                              <li style="display: inline-block; margin: 0 5px;"><a href="#" style="display: inline-block; font-family:'Roboto', sans-serif; font-size: 14px; text-decoration: none; color: #fff; text-transform: uppercase;">Privacy policy</a> </li>
                            </ul>
                          </div>  
              </div>             
              <div style="display: table; clear: both; padding: 0 0 0 0; width: 100%; text-align: center;">
                <p style="font-family:'Roboto', sans-serif; font-size: 14px;  letter-spacing: 0.5px; color: #fff; padding: 10px 0 0 0; margin: 0 0 5px 0; "><?=\Yii::$app->setting->val('company_name');?> <a href="#" style="display: inline-block; text-decoration: none; color: #fff;"> 
                <?=\Yii::$app->setting->val('company_name');?></a></p>
                <p style="font-family:'Roboto', sans-serif; font-size: 14px;  letter-spacing: 0.5px; color: #fff; padding:0; margin: 0 0 5px 0; ">© <?= date('Y');?> <?=\Yii::$app->setting->val('company_name');?> All rights reserved</p>
              </div>
          </div>
      </div>
  </div> 
</body>
</html>
