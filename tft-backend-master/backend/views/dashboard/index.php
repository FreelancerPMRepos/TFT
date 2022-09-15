
<div id="card-stats" class="pt-0">
    <div class="row">
        <div class="col s12 m6 l6 xl3">
            <div class="card gradient-45deg-light-blue-cyan gradient-shadow min-height-100 white-text animate fadeLeft">
            <div class="padding-4">
                <div class="row">
                    <div class="col s7 m7">
                    <i class="material-icons background-round mt-5">perm_identity</i>
                        <p>Exercise</p>
                    </div>
                    <div class="col s5 m5 right-align">
                    <h5 class="mb-0 white-text"><?php
                            print_r($exercise);
                            ?></h5>
                    </div>
                </div>
            </div>
            </div>
        </div>
        <div class="col s12 m6 l6 xl3">
            <div class="card gradient-45deg-red-pink gradient-shadow min-height-100 white-text animate fadeLeft">
            <div class="padding-4">
                <div class="row">
                    <div class="col s7 m7">
                        <i class="material-icons background-round mt-5">perm_identity</i>
                        <p>Exercise Category</p>
                    </div>
                    <div class="col s5 m5 right-align">
                    <h5 class="mb-0 white-text"><?php
                            print_r($exeCategory);
                            ?></h5>
                        <p></p>
                    </div>
                </div>
            </div>
            </div>
        </div>
        <div class="col s12 m6 l6 xl3">
            <div class="card gradient-45deg-amber-amber gradient-shadow min-height-100 white-text animate fadeRight">
            <div class="padding-4">
                <div class="row">
                    <div class="col s7 m7">
                    <i class="material-icons background-round mt-5">perm_identity</i>
                        <p>Trainer</p>
                    </div>
                    <div class="col s5 m5 right-align">
                    <h5 class="mb-0 white-text"><?php
                            print_r($trainer);
                            ?></h5>
                        <p></p>
                    </div>
                </div>
            </div>
            </div>
        </div>
        <div class="col s12 m6 l6 xl3">
            <div class="card gradient-45deg-green-teal gradient-shadow min-height-100 white-text animate fadeRight">
            <div class="padding-4">
                <div class="row">
                    <div class="col s7 m7">
                    <i class="material-icons background-round mt-5">perm_identity</i>
                        <p>Users</p>
                    </div>
                    <div class="col s5 m5 right-align">
                    <h5 class="mb-0 white-text"><?php
                            print_r($users);
                            ?></h5>
                        <p></p>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>

<div id="ecommerce-offer">
    <div class="row">
    <div class="col s12 m3">
        <div class="card gradient-shadow gradient-45deg-light-blue-cyan border-radius-3 animate fadeUp">
            <div class="card-content center">
    
                <h5 class="white-text lighten-4"><?php print_r($androiduser);?></h5>
                <p class="white-text lighten-4">Android Users</p>
            </div>
        </div>
    </div>
    <div class="col s12 m3">
        <div class="card gradient-shadow gradient-45deg-red-pink border-radius-3 animate fadeUp">
            <div class="card-content center">
            
                <h5 class="white-text lighten-4"><?php print_r($iosuser);?></h5>
                <p class="white-text lighten-4">IOS Users</p>
            </div>
        </div>
    </div>
    <div class="col s12 m3">
        <div class="card gradient-shadow gradient-45deg-amber-amber border-radius-3 animate fadeUp">
            <div class="card-content center">
                
                <h5 class="white-text lighten-4"><?php print_r($googleuser);?></h5>
                <p class="white-text lighten-4">Google Users</p>
            </div>
        </div>
    </div>
    <div class="col s12 m3">
        <div class="card gradient-shadow gradient-45deg-green-teal border-radius-3 animate fadeUp">
            <div class="card-content center">
                <h5 class="white-text lighten-4"><?php print_r($facebookuser);?></h5>
                <p class="white-text lighten-4">Facebook Users</p>
            </div>
        </div>
    </div>
    </div>
</div>