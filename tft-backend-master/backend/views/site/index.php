<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Dashboard';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
        <div class="content-wrapper-before gradient-45deg-indigo-purple"></div>
        <div class="col s12">
          <div class="container">
            <!-- Current balance & total transactions cards-->
            <div class="row mt-4">
               <div class="col s12 m4 l4">
                  <!-- Current Balance -->
                  <div class="card animate fadeLeft">
                     <div class="card-content">
                        <h4 class="card-title mb-0">Revenue Of <?= date('M Y')?> <i class="material-icons float-right">more_vert</i></h4>
                        <p class="medium-small">This billing cycle</p>
                        <div class="current-balance-container">
                           <div id="current-balance-donut-chart" class="current-balance-shadow" style="position: relative;"><svg xmlns:ct="http://gionkunz.github.com/chartist-js/ct" width="100%" height="100%" class="ct-chart-donut" style="width: 100%; height: 100%;"><g class="ct-series ct-series-b ct-fill-donut"><path d="M231.414,9A76,76,0,0,0,159.052,61.767" class="ct-slice-donut" ct:value="20" ct:meta="Remaining" style="stroke-width: 8px;"></path></g><g class="ct-series ct-series-a ct-fill-donut"><path d="M159.134,61.515A76,76,0,1,0,231.414,9" class="ct-slice-donut" ct:value="80" ct:meta="Completed" style="stroke-width: 8px;"></path></g><g class="ct-series ct-series-a"><path d="M159.134,61.515A76,76,0,1,0,231.414,9" class="ct-slice-donut" ct:value="80" ct:meta="Completed" style="stroke-width: 8px;"></path></g><g class="ct-series ct-series-b"><path d="M231.414,9A76,76,0,0,0,159.052,61.767" class="ct-slice-donut" ct:value="20" ct:meta="Remaining" style="stroke-width: 8px;"></path></g></svg><div class="ct-fill-donut-label" data-fill-index="fdid-0" style="position: absolute; top: 60px; left: 200px;"><p class="small">Balance</p><h5 class="mt-0 mb-0">$ 10k</h5></div></div>
                        </div>
                        <h5 class="center-align">$ 50,150.00</h5>
                        <p class="medium-small center-align">Used balance this billing cycle</p>
                     </div>
                  </div>
               </div>
               <div class="col s12 m8 l8 animate fadeRight">
                  <!-- Total Transaction -->
                  <div class="card">
                     <div class="card-content">
                        <h4 class="card-title mb-0">Total Transaction <i class="material-icons float-right">more_vert</i></h4>
                        <p class="medium-small">This month transaction</p>
                        <div class="total-transaction-container">
                           <div id="total-transaction-line-chart" class="total-transaction-shadow"><svg xmlns:ct="http://gionkunz.github.com/chartist-js/ct" width="100%" height="100%" class="ct-chart-line" style="width: 100%; height: 100%;"><g class="ct-grids"><line y1="210" y2="210" x1="40" x2="1003.65625" class="ct-grid ct-vertical"></line><line y1="168" y2="168" x1="40" x2="1003.65625" class="ct-grid ct-vertical"></line><line y1="126" y2="126" x1="40" x2="1003.65625" class="ct-grid ct-vertical"></line><line y1="84" y2="84" x1="40" x2="1003.65625" class="ct-grid ct-vertical"></line><line y1="42" y2="42" x1="40" x2="1003.65625" class="ct-grid ct-vertical"></line><line y1="0" y2="0" x1="40" x2="1003.65625" class="ct-grid ct-vertical"></line></g><g><g class="ct-series ct-series-a"><path d="M40,197.4C83.803,197.4,83.803,168,127.605,168C171.408,168,171.408,193.2,215.21,193.2C259.013,193.2,259.013,126,302.815,126C346.618,126,346.618,180.6,390.42,180.6C434.223,180.6,434.223,21,478.026,21C521.828,21,521.828,189,565.631,189C609.433,189,609.433,63,653.236,63C697.038,63,697.038,126,740.841,126C784.643,126,784.643,8.4,828.446,8.4C872.249,8.4,872.249,84,916.051,84C959.854,84,959.854,0,1003.656,0" class="ct-line"></path><circle cx="40" cy="197.4" ct:value="197.4" r="5" class="ct-point ct-point-circle-transperent"></circle><circle cx="127.60511363636364" cy="168" ct:value="168" r="5" class="ct-point ct-point-circle-transperent"></circle><circle cx="215.21022727272728" cy="193.2" ct:value="193.2" r="5" class="ct-point ct-point-circle-transperent"></circle><circle cx="302.81534090909093" cy="126" ct:value="126" r="5" class="ct-point ct-point-circle-transperent"></circle><circle cx="390.42045454545456" cy="180.6" ct:value="180.6" r="5" class="ct-point ct-point-circle-transperent"></circle><circle cx="478.0255681818182" cy="21" ct:value="21" r="5" class="ct-point ct-point-circle-transperent"></circle><circle cx="565.6306818181819" cy="189" ct:value="189" r="5" class="ct-point ct-point-circle-transperent"></circle><circle cx="653.2357954545455" cy="63" ct:value="63" r="5" class="ct-point ct-point-circle"></circle><circle cx="740.8409090909091" cy="126" ct:value="126" r="5" class="ct-point ct-point-circle-transperent"></circle><circle cx="828.4460227272727" cy="8.400000000000006" ct:value="8.400000000000006" r="5" class="ct-point ct-point-circle-transperent"></circle><circle cx="916.0511363636364" cy="84" ct:value="84" r="5" class="ct-point ct-point-circle-transperent"></circle><circle cx="1003.65625" cy="0" ct:value="0" r="5" class="ct-point ct-point-circle-transperent"></circle></g></g><g class="ct-labels"><foreignObject style="overflow: visible;" x="40" y="215" width="87.60511363636364" height="20"><span class="ct-label ct-horizontal ct-end" xmlns="http://www.w3.org/2000/xmlns/" style="width: 88px; height: 20px;"></span></foreignObject><foreignObject style="overflow: visible;" x="127.60511363636364" y="215" width="87.60511363636364" height="20"><span class="ct-label ct-horizontal ct-end" xmlns="http://www.w3.org/2000/xmlns/" style="width: 88px; height: 20px;"></span></foreignObject><foreignObject style="overflow: visible;" x="215.21022727272728" y="215" width="87.60511363636365" height="20"><span class="ct-label ct-horizontal ct-end" xmlns="http://www.w3.org/2000/xmlns/" style="width: 88px; height: 20px;"></span></foreignObject><foreignObject style="overflow: visible;" x="302.81534090909093" y="215" width="87.60511363636363" height="20"><span class="ct-label ct-horizontal ct-end" xmlns="http://www.w3.org/2000/xmlns/" style="width: 88px; height: 20px;"></span></foreignObject><foreignObject style="overflow: visible;" x="390.42045454545456" y="215" width="87.60511363636363" height="20"><span class="ct-label ct-horizontal ct-end" xmlns="http://www.w3.org/2000/xmlns/" style="width: 88px; height: 20px;"></span></foreignObject><foreignObject style="overflow: visible;" x="478.0255681818182" y="215" width="87.60511363636368" height="20"><span class="ct-label ct-horizontal ct-end" xmlns="http://www.w3.org/2000/xmlns/" style="width: 88px; height: 20px;"></span></foreignObject><foreignObject style="overflow: visible;" x="565.6306818181819" y="215" width="87.60511363636363" height="20"><span class="ct-label ct-horizontal ct-end" xmlns="http://www.w3.org/2000/xmlns/" style="width: 88px; height: 20px;"></span></foreignObject><foreignObject style="overflow: visible;" x="653.2357954545455" y="215" width="87.60511363636363" height="20"><span class="ct-label ct-horizontal ct-end" xmlns="http://www.w3.org/2000/xmlns/" style="width: 88px; height: 20px;"></span></foreignObject><foreignObject style="overflow: visible;" x="740.8409090909091" y="215" width="87.60511363636363" height="20"><span class="ct-label ct-horizontal ct-end" xmlns="http://www.w3.org/2000/xmlns/" style="width: 88px; height: 20px;"></span></foreignObject><foreignObject style="overflow: visible;" x="828.4460227272727" y="215" width="87.60511363636363" height="20"><span class="ct-label ct-horizontal ct-end" xmlns="http://www.w3.org/2000/xmlns/" style="width: 88px; height: 20px;"></span></foreignObject><foreignObject style="overflow: visible;" x="916.0511363636364" y="215" width="87.60511363636363" height="20"><span class="ct-label ct-horizontal ct-end" xmlns="http://www.w3.org/2000/xmlns/" style="width: 88px; height: 20px;"></span></foreignObject><foreignObject style="overflow: visible;" x="1003.65625" y="215" width="30" height="20"><span class="ct-label ct-horizontal ct-end" xmlns="http://www.w3.org/2000/xmlns/" style="width: 30px; height: 20px;"></span></foreignObject><foreignObject style="overflow: visible;" y="168" x="0" height="42" width="30"><span class="ct-label ct-vertical ct-start" xmlns="http://www.w3.org/2000/xmlns/" style="height: 42px; width: 30px;">0</span></foreignObject><foreignObject style="overflow: visible;" y="126" x="0" height="42" width="30"><span class="ct-label ct-vertical ct-start" xmlns="http://www.w3.org/2000/xmlns/" style="height: 42px; width: 30px;">10</span></foreignObject><foreignObject style="overflow: visible;" y="84" x="0" height="42" width="30"><span class="ct-label ct-vertical ct-start" xmlns="http://www.w3.org/2000/xmlns/" style="height: 42px; width: 30px;">20</span></foreignObject><foreignObject style="overflow: visible;" y="42" x="0" height="42" width="30"><span class="ct-label ct-vertical ct-start" xmlns="http://www.w3.org/2000/xmlns/" style="height: 42px; width: 30px;">30</span></foreignObject><foreignObject style="overflow: visible;" y="0" x="0" height="42" width="30"><span class="ct-label ct-vertical ct-start" xmlns="http://www.w3.org/2000/xmlns/" style="height: 42px; width: 30px;">40</span></foreignObject><foreignObject style="overflow: visible;" y="-30" x="0" height="30" width="30"><span class="ct-label ct-vertical ct-start" xmlns="http://www.w3.org/2000/xmlns/" style="height: 30px; width: 30px;">50</span></foreignObject></g><defs><linearGradient id="lineLinearStats" x1="0" y1="0" x2="1" y2="0"><stop offset="0%" stop-color="rgba(255, 82, 249, 0.1)"></stop><stop offset="10%" stop-color="rgba(255, 82, 249, 1)"></stop><stop offset="30%" stop-color="rgba(255, 82, 249, 1)"></stop><stop offset="95%" stop-color="rgba(133, 3, 168, 1)"></stop><stop offset="100%" stop-color="rgba(133, 3, 168, 0.1)"></stop></linearGradient></defs></svg></div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
<!--/ Current balance & total transactions cards-->

<!-- User statistics & appointment cards-->
<div class="row">
   <div class="col s12 l5">
      <!-- User Statistics -->
      <div class="card user-statistics-card animate fadeLeft">
         <div class="card-content">
            <h4 class="card-title mb-0">User Statistics <i class="material-icons float-right">more_vert</i></h4>
            <div class="row">
               <div class="col s12 m6">
                  <ul class="collection border-none mb-0">
                     <li class="collection-item avatar">
                        <i class="material-icons circle pink accent-2">trending_up</i>
                        <p class="medium-small">This year</p>
                        <h5 class="mt-0 mb-0">60%</h5>
                     </li>
                  </ul>
               </div>
               <div class="col s12 m6">
                  <ul class="collection border-none mb-0">
                     <li class="collection-item avatar">
                        <i class="material-icons circle purple accent-4">trending_down</i>
                        <p class="medium-small">Last year</p>
                        <h5 class="mt-0 mb-0">40%</h5>
                     </li>
                  </ul>
               </div>
            </div>
            <div class="user-statistics-container">
               <div id="user-statistics-bar-chart" class="user-statistics-shadow">
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="col s12 l4">
      <!-- Recent Buyers -->
      <div class="card recent-buyers-card animate fadeUp">
         <div class="card-content">
            <h4 class="card-title mb-0">Recent Buyers <i class="material-icons float-right">more_vert</i></h4>
            <p class="medium-small pt-2">Today</p>
            <ul class="collection mb-0">
               <li class="collection-item avatar">
                  <img src="../../../app-assets/images/avatar/avatar-7.png" alt="" class="circle">
                  <p class="font-weight-600">John Doe</p>
                  <p class="medium-small">18, January 2019</p>
                  <a href="#!" class="secondary-content"><i class="material-icons">star_border</i></a>
               </li>
               <li class="collection-item avatar">
                  <img src="../../../app-assets/images/avatar/avatar-3.png" alt="" class="circle">
                  <p class="font-weight-600">Adam Garza</p>
                  <p class="medium-small">20, January 2019</p>
                  <a href="#!" class="secondary-content"><i class="material-icons">star_border</i></a>
               </li>
               <li class="collection-item avatar">
                  <img src="../../../app-assets/images/avatar/avatar-5.png" alt="" class="circle">
                  <p class="font-weight-600">Jennifer Rice</p>
                  <p class="medium-small">25, January 2019</p>
                  <a href="#!" class="secondary-content"><i class="material-icons">star_border</i></a>
               </li>
            </ul>
         </div>
      </div>
   </div>
   <div class="col s12 l3">
      <div class="card animate fadeRight">
         <div class="card-content">
            <h4 class="card-title mb-0">Conversion Ratio</h4>
            <div class="conversion-ration-container mt-8">
               <div id="conversion-ration-bar-chart" class="conversion-ration-shadow"><svg xmlns:ct="http://gionkunz.github.com/chartist-js/ct" width="100%" height="100%" class="ct-chart-bar" style="width: 100%; height: 100%;"><g class="ct-grids"></g><g><g class="ct-series ct-series-a"><line x1="158.8135" x2="158.8125" y1="120" y2="54" class="ct-bar" ct:value="55000" style="stroke-width: 40px"></line><circle cx="158.8125" cy="54"></circle></g><g class="ct-series ct-series-b"><line x1="158.8135" x2="158.8125" y1="54" y2="12" class="ct-bar" ct:value="35000" style="stroke-width: 40px"></line><circle cx="158.8125" cy="12"></circle></g><g class="ct-series ct-series-c"><line x1="158.8135" x2="158.8125" y1="12" y2="0" class="ct-bar" ct:value="10000" style="stroke-width: 40px"></line><circle cx="158.8125" cy="0"></circle></g></g><g class="ct-labels"><foreignObject style="overflow: visible;" y="90" x="0" height="30" width="30"><span class="ct-label ct-vertical ct-start" xmlns="http://www.w3.org/2000/xmlns/" style="height: 30px; width: 30px;">0k</span></foreignObject><foreignObject style="overflow: visible;" y="60" x="0" height="30" width="30"><span class="ct-label ct-vertical ct-start" xmlns="http://www.w3.org/2000/xmlns/" style="height: 30px; width: 30px;">25k</span></foreignObject><foreignObject style="overflow: visible;" y="30" x="0" height="30" width="30"><span class="ct-label ct-vertical ct-start" xmlns="http://www.w3.org/2000/xmlns/" style="height: 30px; width: 30px;">50k</span></foreignObject><foreignObject style="overflow: visible;" y="0" x="0" height="30" width="30"><span class="ct-label ct-vertical ct-start" xmlns="http://www.w3.org/2000/xmlns/" style="height: 30px; width: 30px;">75k</span></foreignObject><foreignObject style="overflow: visible;" y="-30" x="0" height="30" width="30"><span class="ct-label ct-vertical ct-start" xmlns="http://www.w3.org/2000/xmlns/" style="height: 30px; width: 30px;">100k</span></foreignObject></g><defs><linearGradient id="barGradient1" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="rgba(129,51,255,1)"></stop><stop offset="1" stop-color="rgba(129,51,255, 0.6)"></stop></linearGradient><linearGradient id="barGradient2" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="rgba(255,75,172,1)"></stop><stop offset="1" stop-color="rgba(255,75,172, 0.6)"></stop></linearGradient></defs></svg></div>
            </div>
            <p class="medium-small center-align">This month conversion ratio</p>
            <h5 class="center-align mb-0 mt-0">62%</h5>
         </div>
      </div>
   </div>
</div>
<!--/ Current balance & appointment cards-->

<div class="row">
   <div class="col s12 m6 l4">
      <div class="card padding-4 animate fadeLeft">
         <div class="col s5 m5">
            <h5 class="mb-0">1885</h5>
            <p class="no-margin">New</p>
            <p class="mb-0 pt-8">1,12,900</p>
         </div>
         <div class="col s7 m7 right-align">
            <i class="material-icons background-round mt-5 mb-5 gradient-45deg-purple-amber gradient-shadow white-text">perm_identity</i>
            <p class="mb-0">Total Clients</p>
         </div>
      </div>
      <div id="chartjs" class="card pt-0 pb-0 animate fadeLeft">
         <div class="padding-2 ml-2">
            <span class="new badge gradient-45deg-indigo-purple gradient-shadow mt-2 mr-2">+ $900</span>
            <p class="mt-2 mb-0 font-weight-600">Today's revenue</p>
            <p class="no-margin grey-text lighten-3">$40,512 avg</p>
            <h5>$ 22,300</h5>
         </div>
         <div class="row">
            <div class="sample-chart-wrapper card-gradient-chart"><div class="chartjs-size-monitor" style="position: absolute; left: 0px; top: 0px; right: 0px; bottom: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;"><div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div></div><div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:200%;height:200%;left:0; top:0"></div></div></div>
               <canvas id="custom-line-chart-sample-three" class="center chartjs-render-monitor" width="533" height="266" style="display: block; width: 533px; height: 266px;"></canvas>
            </div>
         </div>
      </div>
   </div>
   <div class="col s12 m6 l8">
      <div class="card subscriber-list-card animate fadeRight">
         <div class="card-content pb-1">
            <h4 class="card-title mb-0">Subscriber List <i class="material-icons float-right">more_vert</i></h4>
         </div>
         <table class="subscription-table responsive-table highlight">
            <thead>
               <tr>
                  <th>Name</th>
                  <th>Company</th>
                  <th>Start Date</th>
                  <th>Status</th>
                  <th>Amount</th>
                  <th>Action</th>
               </tr>
            </thead>
            <tbody>
               <tr>
                  <td>Michael Austin</td>
                  <td>ABC Fintech LTD.</td>
                  <td>Jan 1,2019</td>
                  <td><span class="badge pink lighten-5 pink-text text-accent-2">Close</span></td>
                  <td>$ 1000.00</td>
                  <td class="center-align"><a href="#"><i class="material-icons pink-text">clear</i></a></td>
               </tr>
               <tr>
                  <td>Aldin Rakić</td>
                  <td>ACME Pvt LTD.</td>
                  <td>Jan 10,2019</td>
                  <td><span class="badge green lighten-5 green-text text-accent-4">Open</span></td>
                  <td>$ 3000.00</td>
                  <td class="center-align"><a href="#"><i class="material-icons pink-text">clear</i></a></td>
               </tr>
               <tr>
                  <td>İris Yılmaz</td>
                  <td>Collboy Tech LTD.</td>
                  <td>Jan 12,2019</td>
                  <td><span class="badge green lighten-5 green-text text-accent-4">Open</span></td>
                  <td>$ 2000.00</td>
                  <td class="center-align"><a href="#"><i class="material-icons pink-text">clear</i></a></td>
               </tr>
               <tr>
                  <td>Lidia Livescu</td>
                  <td>My Fintech LTD.</td>
                  <td>Jan 14,2019</td>
                  <td><span class="badge pink lighten-5 pink-text text-accent-2">Close</span></td>
                  <td>$ 1100.00</td>
                  <td class="center-align"><a href="#"><i class="material-icons pink-text">clear</i></a></td>
               </tr>
            </tbody>
         </table>
      </div>
   </div>
</div><!-- START RIGHT SIDEBAR NAV -->
<aside id="right-sidebar-nav">
   <div id="slide-out-right" class="slide-out-right-sidenav sidenav rightside-navigation right-aligned">
      <div class="row">
         <div class="slide-out-right-title">
            <div class="col s12 border-bottom-1 pb-0 pt-1">
               <div class="row">
                  <div class="col s2 pr-0 center">
                     <i class="material-icons vertical-text-middle"><a href="#" class="sidenav-close">clear</a></i>
                  </div>
                  <div class="col s10 pl-0">
                     <ul class="tabs">
                        <li class="tab col s4 p-0">
                           <a href="#messages" class="active">
                              <span>Messages</span>
                           </a>
                        </li>
                        <li class="tab col s4 p-0">
                           <a href="#settings">
                              <span>Settings</span>
                           </a>
                        </li>
                        <li class="tab col s4 p-0">
                           <a href="#activity">
                              <span>Activity</span>
                           </a>
                        </li>
                     <li class="indicator" style="left: 0px; right: 188px;"></li></ul>
                  </div>
               </div>
            </div>
         </div>
         <div class="slide-out-right-body ps ps--active-y">
            <div id="messages" class="col s12 active">
               <div class="collection border-none">
                  <input class="header-search-input mt-4 mb-2" type="text" name="Search" placeholder="Search Messages">
                  <ul class="collection p-0">
                     <li class="collection-item sidenav-trigger display-flex avatar pl-5 pb-0" data-target="slide-out-chat">
                        <span class="avatar-status avatar-online avatar-50"><img src="../../../app-assets/images/avatar/avatar-7.png" alt="avatar">
                           <i></i>
                        </span>
                        <div class="user-content">
                           <h6 class="line-height-0">Elizabeth Elliott</h6>
                           <p class="medium-small blue-grey-text text-lighten-3 pt-3">Thank you</p>
                        </div>
                        <span class="secondary-content medium-small">5.00 AM</span>
                     </li>
                     <li class="collection-item sidenav-trigger display-flex avatar pl-5 pb-0" data-target="slide-out-chat">
                        <span class="avatar-status avatar-online avatar-50"><img src="../../../app-assets/images/avatar/avatar-1.png" alt="avatar">
                           <i></i>
                        </span>
                        <div class="user-content">
                           <h6 class="line-height-0">Mary Adams</h6>
                           <p class="medium-small blue-grey-text text-lighten-3 pt-3">Hello Boo</p>
                        </div>
                        <span class="secondary-content medium-small">4.14 AM</span>
                     </li>
                     <li class="collection-item sidenav-trigger display-flex avatar pl-5 pb-0" data-target="slide-out-chat">
                        <span class="avatar-status avatar-off avatar-50"><img src="../../../app-assets/images/avatar/avatar-2.png" alt="avatar">
                           <i></i>
                        </span>
                        <div class="user-content">
                           <h6 class="line-height-0">Caleb Richards</h6>
                           <p class="medium-small blue-grey-text text-lighten-3 pt-3">Hello Boo</p>
                        </div>
                        <span class="secondary-content medium-small">4.14 AM</span>
                     </li>
                     <li class="collection-item sidenav-trigger display-flex avatar pl-5 pb-0" data-target="slide-out-chat">
                        <span class="avatar-status avatar-online avatar-50"><img src="../../../app-assets/images/avatar/avatar-3.png" alt="avatar">
                           <i></i>
                        </span>
                        <div class="user-content">
                           <h6 class="line-height-0">Caleb Richards</h6>
                           <p class="medium-small blue-grey-text text-lighten-3 pt-3">Keny !</p>
                        </div>
                        <span class="secondary-content medium-small">9.00 PM</span>
                     </li>
                     <li class="collection-item sidenav-trigger display-flex avatar pl-5 pb-0" data-target="slide-out-chat">
                        <span class="avatar-status avatar-online avatar-50"><img src="../../../app-assets/images/avatar/avatar-4.png" alt="avatar">
                           <i></i>
                        </span>
                        <div class="user-content">
                           <h6 class="line-height-0">June Lane</h6>
                           <p class="medium-small blue-grey-text text-lighten-3 pt-3">Ohh God</p>
                        </div>
                        <span class="secondary-content medium-small">4.14 AM</span>
                     </li>
                     <li class="collection-item sidenav-trigger display-flex avatar pl-5 pb-0" data-target="slide-out-chat">
                        <span class="avatar-status avatar-off avatar-50"><img src="../../../app-assets/images/avatar/avatar-5.png" alt="avatar">
                           <i></i>
                        </span>
                        <div class="user-content">
                           <h6 class="line-height-0">Edward Fletcher</h6>
                           <p class="medium-small blue-grey-text text-lighten-3 pt-3">Love you</p>
                        </div>
                        <span class="secondary-content medium-small">5.15 PM</span>
                     </li>
                     <li class="collection-item sidenav-trigger display-flex avatar pl-5 pb-0" data-target="slide-out-chat">
                        <span class="avatar-status avatar-online avatar-50"><img src="../../../app-assets/images/avatar/avatar-6.png" alt="avatar">
                           <i></i>
                        </span>
                        <div class="user-content">
                           <h6 class="line-height-0">Crystal Bates</h6>
                           <p class="medium-small blue-grey-text text-lighten-3 pt-3">Can we</p>
                        </div>
                        <span class="secondary-content medium-small">8.00 AM</span>
                     </li>
                     <li class="collection-item sidenav-trigger display-flex avatar pl-5 pb-0" data-target="slide-out-chat">
                        <span class="avatar-status avatar-off avatar-50"><img src="../../../app-assets/images/avatar/avatar-7.png" alt="avatar">
                           <i></i>
                        </span>
                        <div class="user-content">
                           <h6 class="line-height-0">Nathan Watts</h6>
                           <p class="medium-small blue-grey-text text-lighten-3 pt-3">Great!</p>
                        </div>
                        <span class="secondary-content medium-small">9.53 PM</span>
                     </li>
                     <li class="collection-item sidenav-trigger display-flex avatar pl-5 pb-0" data-target="slide-out-chat">
                        <span class="avatar-status avatar-off avatar-50"><img src="../../../app-assets/images/avatar/avatar-8.png" alt="avatar">
                           <i></i>
                        </span>
                        <div class="user-content">
                           <h6 class="line-height-0">Willard Wood</h6>
                           <p class="medium-small blue-grey-text text-lighten-3 pt-3">Do it</p>
                        </div>
                        <span class="secondary-content medium-small">4.20 AM</span>
                     </li>
                     <li class="collection-item sidenav-trigger display-flex avatar pl-5 pb-0" data-target="slide-out-chat">
                        <span class="avatar-status avatar-online avatar-50"><img src="../../../app-assets/images/avatar/avatar-1.png" alt="avatar">
                           <i></i>
                        </span>
                        <div class="user-content">
                           <h6 class="line-height-0">Ronnie Ellis</h6>
                           <p class="medium-small blue-grey-text text-lighten-3 pt-3">Got that</p>
                        </div>
                        <span class="secondary-content medium-small">5.20 AM</span>
                     </li>
                     <li class="collection-item sidenav-trigger display-flex avatar pl-5 pb-0" data-target="slide-out-chat">
                        <span class="avatar-status avatar-online avatar-50"><img src="../../../app-assets/images/avatar/avatar-9.png" alt="avatar">
                           <i></i>
                        </span>
                        <div class="user-content">
                           <h6 class="line-height-0">Daniel Russell</h6>
                           <p class="medium-small blue-grey-text text-lighten-3 pt-3">Thank you</p>
                        </div>
                        <span class="secondary-content medium-small">12.00 AM</span>
                     </li>
                     <li class="collection-item sidenav-trigger display-flex avatar pl-5 pb-0" data-target="slide-out-chat">
                        <span class="avatar-status avatar-off avatar-50"><img src="../../../app-assets/images/avatar/avatar-10.png" alt="avatar">
                           <i></i>
                        </span>
                        <div class="user-content">
                           <h6 class="line-height-0">Sarah Graves</h6>
                           <p class="medium-small blue-grey-text text-lighten-3 pt-3">Okay you</p>
                        </div>
                        <span class="secondary-content medium-small">11.14 PM</span>
                     </li>
                     <li class="collection-item sidenav-trigger display-flex avatar pl-5 pb-0" data-target="slide-out-chat">
                        <span class="avatar-status avatar-off avatar-50"><img src="../../../app-assets/images/avatar/avatar-11.png" alt="avatar">
                           <i></i>
                        </span>
                        <div class="user-content">
                           <h6 class="line-height-0">Andrew Hoffman</h6>
                           <p class="medium-small blue-grey-text text-lighten-3 pt-3">Can do</p>
                        </div>
                        <span class="secondary-content medium-small">7.30 PM</span>
                     </li>
                     <li class="collection-item sidenav-trigger display-flex avatar pl-5 pb-0" data-target="slide-out-chat">
                        <span class="avatar-status avatar-online avatar-50"><img src="../../../app-assets/images/avatar/avatar-12.png" alt="avatar">
                           <i></i>
                        </span>
                        <div class="user-content">
                           <h6 class="line-height-0">Camila Lynch</h6>
                           <p class="medium-small blue-grey-text text-lighten-3 pt-3">Leave it</p>
                        </div>
                        <span class="secondary-content medium-small">2.00 PM</span>
                     </li>
                  </ul>
               </div>
            </div>
            <div id="settings" class="col s12" style="display: none;">
               <p class="mt-8 mb-0 ml-5 font-weight-900">GENERAL SETTINGS</p>
               <ul class="collection border-none">
                  <li class="collection-item border-none mt-3">
                     <div class="m-0">
                        <span>Notifications</span>
                        <div class="switch right">
                           <label>
                              <input checked="" type="checkbox">
                              <span class="lever"></span>
                           </label>
                        </div>
                     </div>
                  </li>
                  <li class="collection-item border-none mt-3">
                     <div class="m-0">
                        <span>Show recent activity</span>
                        <div class="switch right">
                           <label>
                              <input type="checkbox">
                              <span class="lever"></span>
                           </label>
                        </div>
                     </div>
                  </li>
                  <li class="collection-item border-none mt-3">
                     <div class="m-0">
                        <span>Show recent activity</span>
                        <div class="switch right">
                           <label>
                              <input type="checkbox">
                              <span class="lever"></span>
                           </label>
                        </div>
                     </div>
                  </li>
                  <li class="collection-item border-none mt-3">
                     <div class="m-0">
                        <span>Show Task statistics</span>
                        <div class="switch right">
                           <label>
                              <input type="checkbox">
                              <span class="lever"></span>
                           </label>
                        </div>
                     </div>
                  </li>
                  <li class="collection-item border-none mt-3">
                     <div class="m-0">
                        <span>Show your emails</span>
                        <div class="switch right">
                           <label>
                              <input type="checkbox">
                              <span class="lever"></span>
                           </label>
                        </div>
                     </div>
                  </li>
                  <li class="collection-item border-none mt-3">
                     <div class="m-0">
                        <span>Email Notifications</span>
                        <div class="switch right">
                           <label>
                              <input checked="" type="checkbox">
                              <span class="lever"></span>
                           </label>
                        </div>
                     </div>
                  </li>
               </ul>
               <p class="mt-8 mb-0 ml-5 font-weight-900">SYSTEM SETTINGS</p>
               <ul class="collection border-none">
                  <li class="collection-item border-none mt-3">
                     <div class="m-0">
                        <span>System Logs</span>
                        <div class="switch right">
                           <label>
                              <input type="checkbox">
                              <span class="lever"></span>
                           </label>
                        </div>
                     </div>
                  </li>
                  <li class="collection-item border-none mt-3">
                     <div class="m-0">
                        <span>Error Reporting</span>
                        <div class="switch right">
                           <label>
                              <input type="checkbox">
                              <span class="lever"></span>
                           </label>
                        </div>
                     </div>
                  </li>
                  <li class="collection-item border-none mt-3">
                     <div class="m-0">
                        <span>Applications Logs</span>
                        <div class="switch right">
                           <label>
                              <input checked="" type="checkbox">
                              <span class="lever"></span>
                           </label>
                        </div>
                     </div>
                  </li>
                  <li class="collection-item border-none mt-3">
                     <div class="m-0">
                        <span>Backup Servers</span>
                        <div class="switch right">
                           <label>
                              <input type="checkbox">
                              <span class="lever"></span>
                           </label>
                        </div>
                     </div>
                  </li>
                  <li class="collection-item border-none mt-3">
                     <div class="m-0">
                        <span>Audit Logs</span>
                        <div class="switch right">
                           <label>
                              <input type="checkbox">
                              <span class="lever"></span>
                           </label>
                        </div>
                     </div>
                  </li>
               </ul>
            </div>
            <div id="activity" class="col s12" style="display: none;">
               <div class="activity">
                  <p class="mt-5 mb-0 ml-5 font-weight-900">SYSTEM LOGS</p>
                  <ul class="collection with-header">
                     <li class="collection-item">
                        <div class="font-weight-900">
                           Homepage mockup design <span class="secondary-content">Just now</span>
                        </div>
                        <p class="mt-0 mb-2">Melissa liked your activity.</p>
                        <span class="new badge amber" data-badge-caption="Important"> </span>
                     </li>
                     <li class="collection-item">
                        <div class="font-weight-900">
                           Melissa liked your activity Drinks. <span class="secondary-content">10 mins</span>
                        </div>
                        <p class="mt-0 mb-2">Here are some news feed interactions concepts.</p>
                        <span class="new badge light-green" data-badge-caption="Resolved"></span>
                     </li>
                     <li class="collection-item">
                        <div class="font-weight-900">
                           12 new users registered <span class="secondary-content">30 mins</span>
                        </div>
                        <p class="mt-0 mb-2">Here are some news feed interactions concepts.</p>
                     </li>
                     <li class="collection-item">
                        <div class="font-weight-900">
                           Tina is attending your activity <span class="secondary-content">2 hrs</span>
                        </div>
                        <p class="mt-0 mb-2">Here are some news feed interactions concepts.</p>
                     </li>
                     <li class="collection-item">
                        <div class="font-weight-900">
                           Josh is now following you <span class="secondary-content">5 hrs</span>
                        </div>
                        <p class="mt-0 mb-2">Here are some news feed interactions concepts.</p>
                        <span class="new badge red" data-badge-caption="Pending"></span>
                     </li>
                  </ul>
                  <p class="mt-5 mb-0 ml-5 font-weight-900">APPLICATIONS LOGS</p>
                  <ul class="collection with-header">
                     <li class="collection-item">
                        <div class="font-weight-900">
                           New order received urgent <span class="secondary-content">Just now</span>
                        </div>
                        <p class="mt-0 mb-2">Melissa liked your activity.</p>
                     </li>
                     <li class="collection-item">
                        <div class="font-weight-900">System shutdown. <span class="secondary-content">5 min</span></div>
                        <p class="mt-0 mb-2">Here are some news feed interactions concepts.</p>
                        <span class="new badge blue" data-badge-caption="Urgent"> </span>
                     </li>
                     <li class="collection-item">
                        <div class="font-weight-900">
                           Database overloaded 89% <span class="secondary-content">20 min</span>
                        </div>
                        <p class="mt-0 mb-2">Here are some news feed interactions concepts.</p>
                     </li>
                  </ul>
                  <p class="mt-5 mb-0 ml-5 font-weight-900">SERVER LOGS</p>
                  <ul class="collection with-header">
                     <li class="collection-item">
                        <div class="font-weight-900">System error <span class="secondary-content">10 min</span></div>
                        <p class="mt-0 mb-2">Melissa liked your activity.</p>
                     </li>
                     <li class="collection-item">
                        <div class="font-weight-900">
                           Production server down. <span class="secondary-content">1 hrs</span>
                        </div>
                        <p class="mt-0 mb-2">Here are some news feed interactions concepts.</p>
                        <span class="new badge blue" data-badge-caption="Urgent"></span>
                     </li>
                  </ul>
               </div>
            </div>
         <div class="ps__rail-x" style="left: 0px; bottom: 0px;"><div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps__rail-y" style="top: 0px; height: 825px; right: 0px;"><div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 521px;"></div></div></div>
      </div>
   </div>

   <!-- Slide Out Chat -->
   <ul id="slide-out-chat" class="sidenav slide-out-right-sidenav-chat right-aligned">
      <li class="center-align pt-2 pb-2 sidenav-close chat-head">
         <a href="#!"><i class="material-icons mr-0">chevron_left</i>Elizabeth Elliott</a>
      </li>
      <li class="chat-body">
         <ul class="collection ps ps--active-y">
            <li class="collection-item display-flex avatar pl-5 pb-0" data-target="slide-out-chat">
               <span class="avatar-status avatar-online avatar-50"><img src="../../../app-assets/images/avatar/avatar-7.png" alt="avatar">
               </span>
               <div class="user-content speech-bubble">
                  <p class="medium-small">hello!</p>
               </div>
            </li>
            <li class="collection-item display-flex avatar justify-content-end pl-5 pb-0" data-target="slide-out-chat">
               <div class="user-content speech-bubble-right">
                  <p class="medium-small">How can we help? We're here for you!</p>
               </div>
            </li>
            <li class="collection-item display-flex avatar pl-5 pb-0" data-target="slide-out-chat">
               <span class="avatar-status avatar-online avatar-50"><img src="../../../app-assets/images/avatar/avatar-7.png" alt="avatar">
               </span>
               <div class="user-content speech-bubble">
                  <p class="medium-small">I am looking for the best admin template.?</p>
               </div>
            </li>
            <li class="collection-item display-flex avatar justify-content-end pl-5 pb-0" data-target="slide-out-chat">
               <div class="user-content speech-bubble-right">
                  <p class="medium-small">Materialize admin is the responsive materializecss admin template.</p>
               </div>
            </li>

            <li class="collection-item display-grid width-100 center-align">
               <p>8:20 a.m.</p>
            </li>

            <li class="collection-item display-flex avatar pl-5 pb-0" data-target="slide-out-chat">
               <span class="avatar-status avatar-online avatar-50"><img src="../../../app-assets/images/avatar/avatar-7.png" alt="avatar">
               </span>
               <div class="user-content speech-bubble">
                  <p class="medium-small">Ohh! very nice</p>
               </div>
            </li>
            <li class="collection-item display-flex avatar justify-content-end pl-5 pb-0" data-target="slide-out-chat">
               <div class="user-content speech-bubble-right">
                  <p class="medium-small">Thank you.</p>
               </div>
            </li>
            <li class="collection-item display-flex avatar pl-5 pb-0" data-target="slide-out-chat">
               <span class="avatar-status avatar-online avatar-50"><img src="../../../app-assets/images/avatar/avatar-7.png" alt="avatar">
               </span>
               <div class="user-content speech-bubble">
                  <p class="medium-small">How can I purchase it?</p>
               </div>
            </li>

            <li class="collection-item display-grid width-100 center-align">
               <p>9:00 a.m.</p>
            </li>

            <li class="collection-item display-flex avatar justify-content-end pl-5 pb-0" data-target="slide-out-chat">
               <div class="user-content speech-bubble-right">
                  <p class="medium-small">From ThemeForest.</p>
               </div>
            </li>
            <li class="collection-item display-flex avatar justify-content-end pl-5 pb-0" data-target="slide-out-chat">
               <div class="user-content speech-bubble-right">
                  <p class="medium-small">Only $24</p>
               </div>
            </li>
            <li class="collection-item display-flex avatar pl-5 pb-0" data-target="slide-out-chat">
               <span class="avatar-status avatar-online avatar-50"><img src="../../../app-assets/images/avatar/avatar-7.png" alt="avatar">
               </span>
               <div class="user-content speech-bubble">
                  <p class="medium-small">Ohh! Thank you.</p>
               </div>
            </li>
            <li class="collection-item display-flex avatar pl-5 pb-0" data-target="slide-out-chat">
               <span class="avatar-status avatar-online avatar-50"><img src="../../../app-assets/images/avatar/avatar-7.png" alt="avatar">
               </span>
               <div class="user-content speech-bubble">
                  <p class="medium-small">I will purchase it for sure.</p>
               </div>
            </li>
            <li class="collection-item display-flex avatar justify-content-end pl-5 pb-0" data-target="slide-out-chat">
               <div class="user-content speech-bubble-right">
                  <p class="medium-small">Great, Feel free to get in touch on</p>
               </div>
            </li>
            <li class="collection-item display-flex avatar justify-content-end pl-5 pb-0" data-target="slide-out-chat">
               <div class="user-content speech-bubble-right">
                  <p class="medium-small">https://pixinvent.ticksy.com/</p>
               </div>
            </li>
         <div class="ps__rail-x" style="left: 0px; bottom: -465px;"><div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps__rail-y" style="top: 465px; height: 754px; right: 0px;"><div class="ps__thumb-y" tabindex="0" style="top: 288px; height: 466px;"></div></div></ul>
      </li>
      <li class="center-align chat-footer">
         <form class="col s12" onsubmit="slide_out_chat()" action="javascript:void(0);">
            <div class="input-field">
               <input id="icon_prefix" type="text" class="search">
               <label for="icon_prefix">Type here..</label>
               <a onclick="slide_out_chat()"><i class="material-icons prefix">send</i></a>
            </div>
         </form>
      </li>
   </ul>
</aside>
<!-- END RIGHT SIDEBAR NAV -->
            <!-- Intro -->

<div id="intro">
    <div class="row">
        <div class="col s12">

            <div id="img-modal" class="modal white" tabindex="0" style="z-index: 1003; display: none; opacity: 0; top: 4%; transform: scaleX(0.8) scaleY(0.8);">
                <div class="modal-content">
                    <div class="bg-img-div"></div>
                    <p class="modal-header right modal-close">
                        Skip Intro <span class="right"><i class="material-icons right-align">clear</i></span>
                    </p>
                    <div class="carousel carousel-slider center intro-carousel" style="height: 0px;">
                        <div class="carousel-fixed-item center middle-indicator with-indicators">
                            <div class="left">
                                <button class="movePrevCarousel middle-indicator-text btn btn-flat purple-text waves-effect waves-light btn-prev disabled">
                                    <i class="material-icons">navigate_before</i> <span class="hide-on-small-only">Prev</span>
                                </button>
                            </div>

                            <div class="right">
                                <button class=" moveNextCarousel middle-indicator-text btn btn-flat purple-text waves-effect waves-light btn-next">
                                    <span class="hide-on-small-only">Next</span> <i class="material-icons">navigate_next</i>
                                </button>
                            </div>
                        </div>
                        <div class="carousel-item slide-1 active" style="z-index: 0; opacity: 1; visibility: visible; transform: translateX(0px) translateX(0px) translateX(0px) translateZ(0px);">
                            <img src="../../../app-assets/images/gallery/intro-slide-1.png" alt="" class="responsive-img animated fadeInUp slide-1-img">
                            <h5 class="intro-step-title mt-7 center animated fadeInUp">Welcome to Materialize</h5>
                            <p class="intro-step-text mt-5 animated fadeInUp">Materialize is a Material Design Admin
                                Template is the excellent responsive google material design inspired multipurpose admin
                                template. Materialize has a huge collection of material design animation &amp; widgets, UI
                                Elements.</p>
                        </div>
                        <div class="carousel-item slide-2" style="transform: translateX(0px) translateX(624px) translateZ(0px); z-index: -1; opacity: 1; visibility: visible;">
                            <img src="../../../app-assets/images/gallery/intro-features.png" alt="" class="responsive-img slide-2-img">
                            <h5 class="intro-step-title mt-7 center">Example Request Information</h5>
                            <p class="intro-step-text mt-5">Lorem ipsum dolor sit amet consectetur,
                                adipisicing elit.
                                Aperiam deserunt nulla
                                repudiandae odit quisquam incidunt, maxime explicabo.</p>
                            <div class="row">
                                <div class="col s6">
                                    <div class="input-field">
                                        <label for="first_name" class="active">Name</label>
                                        <input placeholder="Name" id="first_name" type="text" class="validate">
                                    </div>
                                </div>
                                <div class="col s6">
                                    <div class="input-field">
                                        <div class="select-wrapper"><input class="select-dropdown dropdown-trigger" type="text" readonly="true" data-target="select-options-41a4aa77-3430-7dda-3e30-a4b50ac20008"><ul id="select-options-41a4aa77-3430-7dda-3e30-a4b50ac20008" class="dropdown-content select-dropdown" tabindex="0"><li class="disabled selected" id="select-options-41a4aa77-3430-7dda-3e30-a4b50ac200080" tabindex="0"><span>Choose your option</span></li><li id="select-options-41a4aa77-3430-7dda-3e30-a4b50ac200081" tabindex="0"><span>Option 1</span></li><li id="select-options-41a4aa77-3430-7dda-3e30-a4b50ac200082" tabindex="0"><span>Option 2</span></li><li id="select-options-41a4aa77-3430-7dda-3e30-a4b50ac200083" tabindex="0"><span>Option 3</span></li></ul><svg class="caret" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M7 10l5 5 5-5z"></path><path d="M0 0h24v24H0z" fill="none"></path></svg><select tabindex="-1">
                                            <option value="" disabled="" selected="">Choose your option</option>
                                            <option value="1">Option 1</option>
                                            <option value="2">Option 2</option>
                                            <option value="3">Option 3</option>
                                        </select></div>
                                        <label>Materialize Select</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item slide-3" style="transform: translateX(0px) translateX(-624px) translateZ(0px); z-index: -1; opacity: 1; visibility: visible;">
                            <img src="../../../app-assets/images/gallery/intro-app.png" alt="" class="responsive-img slide-1-img">
                            <h5 class="intro-step-title mt-7 center">Showcase App Features</h5>
                            <div class="row">
                                <div class="col m5 offset-m1 s12">
                                    <ul class="feature-list left-align">
                                        <li><i class="material-icons">check</i> Email Application</li>
                                        <li><i class="material-icons">check</i> Chat Application</li>
                                        <li><i class="material-icons">check</i> Todo Application</li>
                                    </ul>
                                </div>
                                <div class="col m6 s12">
                                    <ul class="feature-list left-align">
                                        <li><i class="material-icons">check</i>Contacts Application</li>
                                        <li><i class="material-icons">check</i>Full Calendar</li>
                                        <li><i class="material-icons">check</i> Ecommerce Application</li>
                                    </ul>
                                </div>
                                <div class="row">
                                    <div class="col s12 center">
                                        <button class="get-started btn waves-effect waves-light gradient-45deg-purple-deep-orange mt-3 modal-close">Get
                                            Started</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <ul class="indicators"><li class="indicator-item active"></li><li class="indicator-item"></li><li class="indicator-item"></li></ul></div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- / Intro -->
          </div>
        </div>
      </div>