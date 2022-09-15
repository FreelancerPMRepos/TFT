
                <!-- END: Top Bar -->
                <h2 class="intro-y text-lg font-medium mt-10">
                    My Client List 
                </h2>
                <div class="grid grid-cols-12 gap-6 mt-5">
                    
                    <!-- BEGIN: Data List -->
                    <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
                        <table class="table table-report -mt-2" id="myTable">
                            <thead>
                                <tr>
                                    <th class="whitespace-no-wrap">PHOTO</th>
                                    <th class="whitespace-no-wrap">CLIENT NAME</th>
                                    <th class="whitespace-no-wrap">CONTACT DETAIL</th>
                                    <th class="whitespace-no-wrap">SUBSCRIPTION</th>
                                    <th class="text-center whitespace-no-wrap">ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($data as $item){ 
                                    ?>
                                    <tr class="intro-x">
                                        <td class="w-5">
                                            <div class="flex">
                                                <div class="w-10 h-10 image-fit zoom-in">
                                                    <img alt="Client" class="tooltip rounded-full" 
                                                    src="<?=$item['photo'];?>">
                                                </div>
                                            </div>
                                        </td>
                                        <td class="w-15">
                                            <a href="" class="font-medium whitespace-no-wrap"><?=$item['username'];?></a> 
                                            <div class="text-gray-600 text-xs whitespace-no-wrap"><?=$item['email'];?></div>
                                        </td>
                                        <td class="w-40">
                                            <?=$item['traineeAdditionalDetail']['contact_data'];?>
                                            <div class="text-gray-600 text-xs whitespace-no-wrap">
                                                <?=$item['traineeAdditionalDetail']['address'];?>
                                            </div>
                                        </div>
                                        <td>
                                            
                                            <div class="text-gray-600 text-xs whitespace-no-wrap">
                                                <?php
                                                    if(!empty($item['traineeAdditionalDetail']['subscription_on'])){
                                                        echo date('d M Y',$item['traineeAdditionalDetail']['subscription_start']);
                                                        echo ' to ';
                                                        echo date('d M Y',$item['traineeAdditionalDetail']['subscription_end']);
                                                        echo '<br><br> 
                                                        <a href="'.\yii\helpers\Url::toRoute(['client/cancel-subscription',
                                                        'trainee_id'=>$item['traineeAdditionalDetail']['user_id']]).'" class="button button--sm w-30 inline-block bg-theme-6 text-white">Cancel Subscription</a>';

                                                    }else{
                                                       echo' <form action="'.\yii\helpers\Url::toRoute(['client/subscribe/'.$item['traineeAdditionalDetail']['user_id']]).'" method="GET">
                                                                                                    <script
                                                                                                        src="https://checkout.stripe.com/checkout.js" class="stripe-button text-center"
                                                                                                        data-key="'.Yii::$app->setting->val('stripe_public_key').'"
                                                                                                        data-label= "Subscribe"
                                                                                                        data-email="'.Yii::$app->user->identity->email.'"
                                                                                                        data-name=""
                                                                                                        data-description=""
                                                                                                        data-image="https://targetedfitnesstraining.com/img_assets/logo.png"
                                                                                                        data-locale="auto"
                                                                                                        data-currency="USD">
                                                                                                    </script>
                                                                                                </form>
                                                        ';
                                                      
                                                    }
                                                ?>
                                            </div>
                                        </td>
                                        <td class="table-report__action w-70">
                                            <div class="flex items-center justify-center text-theme-9"> 
                                                <?php 
                                                   if(!empty($item['traineeAdditionalDetail']['subscription_on'])){
                                                    echo '<br><br> 
                                                    <a href="#" class="button button--sm w-30 inline-block bg-theme-6 text-white" onclick="myFunction()">Discontinue</a>';
                                                   }else{
                                                        echo '<br><br> 
                                                        <a href="'.\yii\helpers\Url::toRoute(['client/discontinue',
                                                        'trainee_id'=>$item['traineeAdditionalDetail']['user_id']]).'" class="button button--sm w-30 inline-block bg-theme-6 text-white">Discontinue</a>';

                                                   }
                                                ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <script>
function myFunction(e) {
  alert("You must have to stop subscription before to discontinue with client.");
}
</script>
                