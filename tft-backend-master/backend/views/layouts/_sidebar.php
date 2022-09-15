<?php
use \yii\helpers\Url;
$controller = Yii::$app->controller->id;
//sports
$action     = Yii::$app->controller->action->id;
?>
<aside class="sidenav-main nav-expanded nav-lock nav-collapsible sidenav-light sidenav-active-square">
      <div class="brand-sidebar">
        <h1 class="logo-wrapper"><a class="brand-logo darken-1" href="<?= Url::to(['/site/index'], $schema = true)?>">
        <img src="<?php echo Yii::$app->request->baseUrl; ?>/../img_assets/logo.png" alt="materialize logo"/>
        <span class="logo-text hide-on-med-and-down">Admin Panel</span></a>
            <a class="navbar-toggler" href="#">
                <i class="material-icons">radio_button_checked</i>
            </a>
        </h1>
      </div>
      <ul class="sidenav sidenav-collapsible leftside-navigation collapsible sidenav-fixed menu-shadow" id="slide-out" data-menu="menu-navigation" data-collapsible="menu-accordion">
        <!-- <li>
            <a class="waves-effect waves-cyan <?= $controller == "site" && $action =="index"?"active":"";?>" href="<?= Url::toRoute(['/site/index']);?>">
              <i class="material-icons">settings_input_svideo</i>
              <span class="menu-title" data-i18n="">Dashboard</span>
            </a>
        </li>    -->
        <li>
          <a class="waves-effect waves-cyan <?= $controller == "dashboard"&& $action == "index"?"active":"";?>" href="<?= Url::toRoute(['/dashboard/index']);?>">
              <i class="material-icons">dashboard</i>
              <span class="menu-title" data-i18n="">Dashboard</span>
            </a>
        </li>
        <li class="navigation-header"><a class="navigation-header-text">USERS</a><i class="navigation-header-icon material-icons">more_horiz</i>
        </li>
        <!-- <li>
          <a class="waves-effect waves-cyan <?= $controller == "user" && $action == "admin" ? "active" : ""; ?>" href="<?= Url::toRoute(['/user/admin']);?>">
            <i class="material-icons">group</i>
            <span class="menu-title" data-i18n="">Admins</span>
          </a>
        </li> -->
        <!-- <li>
          <a class="waves-effect waves-cyan <?= $controller == "user" && $action == "trainer" ? "active" : ""; ?>" href="<?= Url::toRoute(['/user/trainer']);?>">
            <i class="material-icons">group</i>
            <span class="menu-title" data-i18n="">Trainer</span>
          </a>
        </li> -->
        <li>
          <a class="waves-effect waves-cyan <?= $controller == "user"  ? "active" : ""; ?>" href="<?= Url::toRoute(['/user/index']);?>">
            <i class="material-icons">group</i>
            <span class="menu-title" data-i18n="">App Users</span>
          </a>
        </li>
        <li class="navigation-header"><a class="navigation-header-text">CMS</a><i class="navigation-header-icon material-icons">more_horiz</i>
        </li> 
        <li>
          <a class="waves-effect waves-cyan <?= $controller == "cms"?"active":"";?>" href="<?= Url::toRoute(['/cms/index']);?>">
            <i class="material-icons">content_paste</i>
            <span class="menu-title" data-i18n="">Pages</span>
          </a>
        </li> 

        <?php if(\Yii::$app->user->identity->role == 99) { ?>

        <li class="navigation-header"><a class="navigation-header-text">Exercise</a><i class="navigation-header-icon material-icons">more_horiz</i>
        </li> 

        <li>
          <a class="waves-effect waves-cyan <?= $controller == "exercise"?"active":"";?>" href="<?= Url::toRoute(['/exercise/index']);?>">
            <i class="material-icons">directions_run</i>
            <span class="menu-title" data-i18n="">Exercise</span>
          </a>
          <a class="waves-effect waves-cyan <?= $controller == "exercisecategory"?"active":"";?>" href="<?= Url::toRoute(['/exercisecategory/index']);?>">
            <i class="material-icons">subject</i>
            <span class="menu-title" data-i18n="">Exercise Category</span>
          </a> 
          <!-- <a class="waves-effect waves-cyan <?= $controller == "routines"?"active":"";?>" href="<?= Url::toRoute(['/routines/index']);?>">
            <i class="material-icons">fitness_center</i>
            <span class="menu-title" data-i18n="">Workout Routine</span>
          </a> -->
        </li>

        <li class="<?= $controller == "strength-routines"? "active" : "";?>"><a class="collapsible-header waves-effect waves-cyan " href="JavaScript:void(0)" tabindex="0"><i class="material-icons">directions_bike</i><span class="menu-title" data-i18n="Chart">Strength Routine</span></a>
          <div class="collapsible-body" style="">
            <ul class="collapsible collapsible-sub" data-collapsible="accordion">
                <li class = "<?= $controller == "strength-routines" && $action == "ssstr"?"active":"";?>">
                    <a class = "<?= $controller == "strength-routines" && $action == "ssstr"?"active":"";?>" href="<?= Url::toRoute(['strength-routines/ssstr']);?>">
                        <i class="material-icons"><?= $controller == "strength-routines" && $action == "ssstr" ? "radio_button_checked" : "radio_button_unchecked"; ?></i>
                            <span data-i18n="">SSSTR</span>
                    </a>
                </li>
                <li class = "<?= $controller == "strength-routines" && $action == "ssgst"?"active":"";?>">
                    <a class = "<?= $controller == "strength-routines" && $action == "ssgst"?"active":"";?>" href="<?= Url::toRoute(['strength-routines/ssgst']);?>">
                        <i class="material-icons"><?= $controller == "strength-routines" && $action == "ssgst" ? "radio_button_checked" : "radio_button_unchecked"; ?></i>
                            <span data-i18n="">SSGST</span>
                    </a>
                </li>
                <li class = "<?= $controller == "strength-routines" && $action == "post"?"active":"";?>">
                    <a class = "<?= $controller == "strength-routines" && $action == "post"?"active":"";?>" href="<?= Url::toRoute(['strength-routines/post']);?>">
                        <i class="material-icons"><?= $controller == "strength-routines" && $action == "post"? "radio_button_checked" : "radio_button_unchecked"; ?></i>
                            <span data-i18n="">PoST</span>
                    </a>
                </li>
                <li class = "<?= $controller == "strength-routines" && $action == "prst"?"active":"";?>">
                    <a class = "<?= $controller == "strength-routines" && $action == "prst"?"active":"";?>" href="<?= Url::toRoute(['strength-routines/prst']);?>">
                        <i class="material-icons"><?= $controller == "strength-routines" && $action == "prst"? "radio_button_checked" : "radio_button_unchecked"; ?></i>
                            <span data-i18n="">PrST</span>
                    </a>
                </li>
                <li class = "<?= $controller == "strength-routines" && $action == "sst"?"active":"";?>">
                    <a class = "<?= $controller == "strength-routines" && $action == "sst"?"active":"";?>" href="<?= Url::toRoute(['strength-routines/sst']);?>">
                        <i class="material-icons"><?= $controller == "strength-routines" && $action == "sst"? "radio_button_checked" : "radio_button_unchecked"; ?></i>
                            <span data-i18n="">SST</span>
                    </a>
                </li>
            </ul>
          </div>
        </li>

        <li class="<?= $controller == "sports"?"active":"";?>"><a class="collapsible-header waves-effect waves-cyan " href="JavaScript:void(0)" tabindex="0"><i class="material-icons">gamepad</i><span class="menu-title" data-i18n="Chart">Sports</span></a>
          <div class="collapsible-body" style="">
            <ul class="collapsible collapsible-sub" data-collapsible="accordion">
              <li class = "<?= $controller == "sports" && $action == "ssstr"?"active":"";?>">
                <a class = "<?= $controller == "sports" && $action == "ssstr"?"active":"";?>" href="<?= Url::toRoute(['/sports/ssstr']);?>">
                    <i class="material-icons"><?= $controller == "sports" && $action == "ssstr"? "radio_button_checked" : "radio_button_unchecked"; ?></i>
                        <span data-i18n="">SSSTR Sports</span>
                </a>
              </li>
              <li class = "<?= $controller == "sports" && $action == "ssgst"?"active":"";?>">
                <a class = "<?= $controller == "sports" && $action == "ssgst"?"active":"";?>" href="<?= Url::toRoute(['/sports/ssgst']);?>">
                    <i class="material-icons"><?= $controller == "sports" && $action == "ssgst"? "radio_button_checked" : "radio_button_unchecked"; ?></i>
                        <span data-i18n="">SSGST Sports</span>
                </a>
              </li>
            </ul>
          </div>
        </li>


        <li class="navigation-header"><a class="navigation-header-text">Setting</a><i class="navigation-header-icon material-icons">more_horiz</i>
        </li> 
        <li>
          <a class="waves-effect waves-cyan <?= $controller == "setting"?"active":"";?>" href="<?= Url::toRoute(['/setting/index']);?>">
            <i class="material-icons">settings</i>
            <span class="menu-title" data-i18n="">General Settings</span>
          </a> 
                 
        </li> 
          <li>
              <a class="waves-effect waves-cyan <?= $controller == "email"?"active":"";?>" href="<?= Url::toRoute(['/email/index']);?>">
                <i class="material-icons">email</i>
                <span class="menu-title" data-i18n="">Email Templates</span>
              </a>
          </li>
        <?php } ?>	
       
        <?php if(\Yii::$app->user->identity->role == 99) { ?> 
        <!-- <li class="navigation-header"><a class="navigation-header-text">Translator </a><i class="navigation-header-icon material-icons">more_horiz</i>
        </li>
        <li>
          <a class="waves-effect waves-cyan <?= $controller == "language" && ($action == "list"||$action == "translate"||$action == "create"||$action == "update"||$action == "view")?"active":"";?>" href="<?= Url::toRoute(['/translatemanager/language/list']);?>">
            <i class="material-icons">language</i>
            <span class="menu-title" data-i18n="">Languages</span>
          </a>
        </li>     
        <li>
          <a class="waves-effect waves-cyan <?= $controller == "language" && $action == "scan"?"active":"";?>" href="<?= Url::toRoute(['/translatemanager/language/scan']);?>">
            <i class="material-icons">repeat</i>
            <span class="menu-title" data-i18n="">Scan</span>
          </a>
        </li>  
        <li>
          <a class="waves-effect waves-cyan <?= $controller == "app-trans" && $action == "index"?"active":"";?>" href="<?= Url::toRoute(['/app-trans/index']);?>">
            <i class="material-icons">repeat</i>
            <span class="menu-title" data-i18n="">App Text Scan</span>
          </a>
        </li> 
        <li>
          <a class="waves-effect waves-cyan <?= $controller == "language" && $action == "optimizer"?"active":"";?>" href="<?= Url::toRoute(['/translatemanager/language/optimizer']);?>">
            <i class="material-icons">import_export</i>
            <span class="menu-title" data-i18n="">Optimizer</span>
          </a>
        </li>  
        <li>
          <a class="waves-effect waves-cyan <?= $controller == "language" && $action == "import"?"active":"";?>" href="<?= Url::toRoute(['/translatemanager/language/import']);?>">
            <i class="material-icons">cloud_upload</i>
            <span class="menu-title" data-i18n="">Import</span>
          </a>
        </li>  
        <li>
          <a class="waves-effect waves-cyan <?= $controller == "language" && $action == "export"?"active":"";?>" href="<?= Url::toRoute(['/translatemanager/language/export']);?>">
            <i class="material-icons">cloud_download</i>
            <span class="menu-title" data-i18n="">Export</span>
          </a>
        </li> -->       
        <li class="navigation-header"><a class="navigation-header-text">For Developer</a><i class="navigation-header-icon material-icons">more_horiz</i>
        </li> 
        <li>
          <a class="waves-effect waves-cyan <?= $controller == "postman"?"active":"";?>" href="<?= Url::toRoute(['/postman/index']);?>">
            <i class="material-icons">error</i>
            <span class="menu-title" data-i18n="">Postman</span>
          </a>
          <a class="waves-effect waves-cyan <?= $controller == "log"&& $action == "api"?"active":"";?>" href="<?= Url::toRoute(['/log/api','LogSearch[category]'=>'api']);?>">
            <i class="material-icons">error</i>
            <span class="menu-title" data-i18n="">Api Call</span>
          </a>
          <a class="waves-effect waves-cyan <?= $controller == "log" && $action == "index"?"active":"";?>" href="<?= Url::toRoute(['/log/index','LogSearch[level]'=>1]);?>">
            <i class="material-icons">error</i>
            <span class="menu-title" data-i18n="">Log</span>
          </a>
          <a class="waves-effect waves-cyan <?= $controller == "log" && $action =="cron"?"active":"";?>" href="<?= Url::toRoute(['/log/cron']);?>">
              <i class="material-icons">error</i>
              <span class="menu-title" data-i18n="">Cron Log</span>
          </a>
        </li>    
        <?php } ?>  
      </ul>
    </aside>
    <!-- END: SideNav-->