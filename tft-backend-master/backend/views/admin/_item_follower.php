<?php
use yii\helpers\Url;
$getUserDetails = Yii::$app->general->getUserDetails($model->follower_id);

?>
<div class="card card-border center-align gradient-45deg-indigo-purple">
    <div class="card-content white-text">
            <div class="col s12">
              <span class="flag-icon right flag-icon-in"></span>
            </div>
            <img class="responsive-img user-images circle z-depth-4" width="100" src="<?php echo $getUserDetails['userAdditionalInfos']['small_photo']; ?>" alt="">
            <h5 class="white-text mb-1"><?php echo $getUserDetails['userAdditionalInfos']['full_name']; ?></h5>
            <p class="m-0"><?php echo $getUserDetails['userAdditionalInfos']['position']; ?></p>
            <p class="">@<?php echo $getUserDetails['userAdditionalInfos']['city']; ?></p>
            <a class="waves-effect waves-light btn gradient-45deg-green-teal border-round mt-7 z-depth-4" 
            data-pjax="0" href="<?= Url::toRoute(['user/view','id'=>$getUserDetails['id']])?>">View</a>
      </div>
  </div>
