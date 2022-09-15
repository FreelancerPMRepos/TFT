<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\MailManagementSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Mail Managements';
$this->params['breadcrumbs'][] = $this->title;
$currentUserData = Yii::$app->user->identity;
if(!empty($currentUserData)){
    $getUserOtherDetails = Yii::$app->general->getUserDetails(Yii::$app->user->identity->id);
}
?>
<div class="row">
    <div class="content-wrapper-before gradient-45deg-indigo-purple"></div>
    <div class="col s12">
        <div class="container">
            <!-- Sidebar Area Starts -->
            <div class="sidebar-left sidebar-fixed">
                <div class="sidebar">
                    <div class="sidebar-content">
                        <div class="sidebar-header">
                            <div class="sidebar-details">
                                <h5 class="m-0 sidebar-title"><i class="material-icons app-header-icon text-top">mail_outline</i> Mailbox</h5>
                                <div class="row valign-wrapper mt-10 pt-2 animate fadeLeft">
                                    <div class="col s2 media-image">
                                        <img src="<?php echo !empty($getUserOtherDetails) ? $getUserOtherDetails['photo'] : ''; ?>" alt="" class="circle z-depth-2 responsive-img">
                                        <!-- notice the "circle" class -->
                                    </div>
                                    <div class="col s10">
                                        <p class="m-0 subtitle font-weight-700"><?= !empty($currentUserData['username']) ? $currentUserData['username'] : ''; ?></p>
                                        <p class="m-0 text-muted"><?= !empty($currentUserData['email']) ? $currentUserData['email'] : ''; ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="sidebar-list" class="sidebar-menu list-group position-relative animate fadeLeft ps ps--active-y">
                            <div class="sidebar-list-padding app-sidebar" id="email-sidenav">
                                <ul class="email-list display-grid">
                                    <li class="sidebar-title">Folders</li>
                                  
                                    <li class="<?php echo !empty($searchModel->email_type) && $searchModel->email_type == 'inbox' && $searchModel->reply_of == 0 ? 'active' : ''; ?>">
                                        <a href="<?php echo Url::to(['/mailmanagement/index', 'MailManagementSearch[email_type]' => 'inbox','MailManagementSearch[reply_of]' => '0']); ?>" class="text-sub">
                                        <i class="material-icons mr-2"> mail_outline </i> Un-read 
                                        </a>
                                    </li>
                                      <li class="<?php echo !empty($searchModel->email_type) && $searchModel->email_type == 'inbox' && $searchModel->reply_of == 1? 'active' : ''; ?>">
                                        <a href="<?php echo Url::to(['/mailmanagement/index', 'MailManagementSearch[email_type]' => 'inbox','MailManagementSearch[reply_of]' => '1']); ?>" class="text-sub">
                                        <i class="material-icons mr-2"> check </i> Done
                                        </a>
                                    </li>
                                    <li class="<?php echo !empty($searchModel->email_type) && $searchModel->email_type == 'sent' ? 'active' : ''; ?>">
                                        <a href="<?php echo Url::to(['/mailmanagement/index', 'MailManagementSearch[email_type]' => 'sent']); ?>" class="text-sub">
                                            <i class="material-icons mr-2"> send </i> Sent
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <a href="#" data-target="email-sidenav" class="sidenav-trigger hide-on-large-only">
                            <i class="material-icons">menu</i>
                        </a>
                    </div>
                </div>
            </div>
            <!-- Sidebar Area Ends -->
            <!-- Content Area Starts -->
            <div class="app-email">
                <div class="content-area content-right">
                    <div class="app-wrapper">
                        <div class="app-search">
                            <?php echo $this->render('_search', ['model' => $searchModel]); ?>
                            <!-- <i class="material-icons mr-2 search-icon">search</i>
                            <input type="text" placeholder="Search Mail" class="app-filter" id="email_filter"> -->
                        </div>
                        <div class="card card card-default scrollspy border-radius-6 fixed-width">
                        
                            <div class="card-content mail-management animate fadeUp delay-1">
                            <button class="btn btn-small delete-multipe"><i class="material-icons left">delete</i> Delete All</button>  
                                                             
                                    <?php if($searchModel->email_type == "inbox" && $searchModel->reply_of == 0  ){?>
                                        <button class="btn btn-small mark-read"> <i class="material-icons left"> check </i>Mark as Done</button>
                                    <?php } ?>
                                <?= GridView::widget([
                                    'dataProvider' => $dataProvider,
                                    'filterModel' => $searchModel,
                                    'rowOptions' => function ($model) {
                                        return ['class' => 'mail-row'];
                                    },
                                    'columns' => [
                                        [
                                            'class' => 'yii\grid\CheckboxColumn',
                                            'headerOptions' => ['style' => 'width: 50px;'],
                                            'content'=>function ($model, $key, $index, $column){
                                                return '<label>
                                                <input type="checkbox" name="selection[]" value = "'.$model->id.'">
                                                <span></span>
                                              </label>';
                                            }
                                           
                                        ],
                                        [
                                            'attribute' => 'name',
                                            'format' => 'raw',
                                            'value' => function($data){
                                                if($data->email_type == "inbox"){
                                                    return'<div class="app-todo">
                                                        <div class="collection-item">              
                                                                <div class="list-content">
                                                                <div class="list-title-area">
                                                                    <div class="list-title" style="text-decoration: none;">'.$data->subject.'</div>                                                    
                                                                </div>
                                                                <div class="list-desc" style="text-decoration: none;">'.$data->body.'</div>
                                                                </div>
                                                                <div class="list-right">
                                                                
                                                                <div class="delete-task"><p>'.$data->email.'</p>'.date('d-M-Y H:i:s', $data->created_at).'</div>
                                                                </div>
                                                            </div>
                                                        </div>';
                                                }else{
                                                    return '<ul class="collapsible collapsible-accordion" data-collapsible="accordion">
                                                    <li class="">
                                                      
                                                       <div class="collapsible-header" tabindex="0"><div class="list-title" style="text-decoration: none;">'.$data->subject.'</div> </div>
                                                       <div class="collapsible-body" style="">
                                                          <p>
                                                             '.$data->body.'
                                                          </p>
                                                          <div class="list-date">'.$data->email.'</div>
                                                          <div class="list-date">'.date('d-M-Y H:i:s', $data->created_at).'</div>
                                                       </div>
                                                    </li>                                                
                                                 </ul>';
                                                }
                                               
                                            },
                                            'header' => '',
                                            'filter' => false
                                        ]
                                    ],
                                ]); ?>                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Content Area Ends -->
            <!-- Add new email popup -->
            <div class="fixed-action-btn direction-top active">
                <a class="btn-floating btn-large primary-text gradient-shadow modal-trigger" href="#composemail">
                    <i class="material-icons">add</i>
                </a>
            </div>
            <!-- Add new email popup Ends-->
            
            <!-- Modal Structure -->
            <div id="composemail" class="modal border-radius-6" tabindex="-1">
                <div class="modal-content">
                <?= $this->render('_form', [
                    'model' => $model,
                ]) ?>
                </div>
            </div>
            <!-- Modal Structure Ends -->
        </div>
    </div>
</div>
