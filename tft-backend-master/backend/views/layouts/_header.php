<!-- BEGIN: Header-->
<?php
use \yii\helpers\Url;
?>
<header class="page-topbar" id="header">
      <div class="navbar navbar-fixed"> 
        <nav class="navbar-main navbar-color nav-collapsible sideNav-lock navbar-dark gradient-45deg-indigo-purple no-shadow">
          <div class="nav-wrapper">
            <div class="header-search-wrapper hide-on-med-and-down">
              <h4> <a><?=Yii::$app->name;?></a></h4> 
            </div>
            <ul class="navbar-list right">
              <li><a class="waves-effect waves-block waves-light profile-button" href="javascript:void(0);" data-target="profile-dropdown"><span class="avatar-status avatar-online">
                <img src="/administration/../img_assets/logo.png" alt="avatar"><i></i></span>
              </a>
              </li>
            </ul>
            <!-- profile-dropdown-->
            <ul class="dropdown-content" id="profile-dropdown">
              <li><a class="grey-text text-darken-1" href="<?=Url::to(['user/view-admin','id'=>\Yii::$app->user->id], $schema = true)?>">
              <i class="material-icons">person_outline</i> Profile</a></li>
             
              <li><a class="grey-text text-darken-1" href="<?=Url::to(['/site/logout'], $schema = true)?>"><i class="material-icons">keyboard_tab</i> Logout</a></li>
            </ul>
          </div>
          <nav class="display-none search-sm">
            <div class="nav-wrapper">
              <form>
                <div class="input-field">
                  <input class="search-box-sm" type="search" required="">
                  <label class="label-icon" for="search"><i class="material-icons search-sm-icon">search</i></label><i class="material-icons search-sm-close">close</i>
                </div>
              </form>
            </div>
          </nav>
        </nav>
      </div>
    </header>
    <!-- END: Header-->