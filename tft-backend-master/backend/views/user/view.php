<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\widgets\ListView;
use yii\widgets\Breadcrumbs;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = 'Details of '.$type.': '.$model['user']['username'];//.$model['id'];
$this->params['breadcrumbs'][] = isset($_GET['id']) ? $type == "Trainee"? ['label' => $type, 'url' => ['index','id'=>$_GET['t_id']]] : ['label' => $type, 'url' => ['index']] : ['label' => $type, 'url' => ['index']] ;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
	<div class="content-wrapper-before gradient-45deg-indigo-purple"></div>
	<div class="breadcrumbs-dark pb-0 pt-4" id="breadcrumbs-wrapper">
		<!-- Search for small screen-->
		<div class="container">
			<div class="row">
				<div class="col s10 m6 l12">
					<h5 class="breadcrumbs-title mt-0 mb-0"><?= $this->title?></h5>
					<?php 
					echo Breadcrumbs::widget([
						'itemTemplate' => '<li class="breadcrumb-item">{link}</li>',
						'tag' => 'ol',
						'options' => [
							'class' => 'breadcrumbs mb-0'
						],
						'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
					]);
					?>
				</div>
			</div>
		</div>
    </div>
    <div class="col s12">
        <div class="container">
            <div class="section" id="user-profile">               
                <div class="row">
					<!-- User Profile Section -->
					<div class="card-panel">
						<div class="row">
							<div class="col s12 m7">
								<div class="display-flex media">
									<a class="avatar">
										<?php
											$default_Image = Yii::$app->request->baseUrl."/../img_assets/upload/nophotoavailable.jpg";
											if(!empty($model['thum_photo'])){
												$basePath =  Yii::getAlias('@webroot/../../img_assets/users/'.$model['thum_photo']);
												if(file_exists($basePath)){
													echo '<img src="' .Yii::$app->request->baseUrl."/../img_assets/users/".$model['thum_photo']. "?r=".rand() . '" width="200" alt="">';
												}else{
													echo '<img src="' . $default_Image . '" width="200" alt="">';
												}
											}else{
												echo '<img src="' . $default_Image . '" width="200" alt="">';
											}
										?>
									</a>
									<div class="media-body ml-4">
										<h6 class="media-heading">
											<span class="users-view-name"><?= $model['user']['username']; ?> </span>
											<span class="grey-text">@</span>
											<span class="users-view-username grey-text"><?= $model['user']['email']; ?></span>
										</h6>
										<span>ID:</span>
										<span class="users-view-id"><?= $model['user_id']; ?></span>
									</div>
								</div>
							</div>
							<div class="col s12 m5 quick-action-btns display-flex justify-content-end align-items-center pt-2">
								<a href="<?= Url::toRoute(['/user/update?id='. $model['user_id']], $schema = true)?>" class="btn-small indigo">Edit</a>
							</div>
						</div>
					</div>
					<div class="card">
						<div class="card-content mt-2 mb-2">
							<div class="row">
								<div class="col s12 m4">
									<table class="striped">
										<tbody>
											<tr>
												<td>Registered:</td>
												<td><?= date('F d, Y', $model['user']['created_at']); ?></td>
											</tr>
											<tr>
												<td>Status:</td>
												<td>
													<div class="switch">
														<label>
															Block														
															<input type="checkbox" class="user-status-checkbox" data-user="<?php echo $model['user_id']; ?>" value="1" 
															<?php echo empty($model['user']['blocked_at']) ? 'checked="checked"' : ''; ?>>
															<span class="lever"></span>
															Active
														</label>
													</div>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					<div class="card">
						<div class="card-content mt-2 mb-2">
							<div class="row">
								<div class="col s12">
									<h6 class="mb-2"><i class="material-icons">error_outline</i> Personal Info</h6>
									<table class="striped">
										<tbody>
										<tr>
											<td>Birthday:</td>
											<td><?= date("F d, Y", strtotime($model['date_of_birth'])); ?></td>
										</tr>
                                        <tr>
                                            <td>Gender:</td>
                                            <td class="users-view-role"><?= ucfirst($model['gender']) ?></td>
                                        </tr>
										</tbody>
									</table>
								</div>
								<div class="col s12">
									<h6 class="mb-2 mt-2"><i class="material-icons">error_outline</i> Body Info</h6>
									<table class="striped">
										<tbody>
											<tr>
												<td>Height:</td>
												<td> <?php 
													if($model['units_of_measurement'] == 'lbs/in'){
														echo $model['height'] . ' inches';
													}else{
														echo $model['height']. ' cm';
													} ?>
												</td>
											</tr>
											<tr>
												<td>Weight:</td>
												<td><?php 
													if($model['units_of_measurement'] == 'lbs/in'){
														echo $model['weight'] . ' Lb';
													}else{
														echo $model['weight']. ' kg';
													} ?>
												</td>
											</tr>
											<tr>
												<td>BMI:</td>
												<td><?= \Yii::$app->general->bmi($model['weight'],$model['weight_unit'],$model['height'],$model['height_unit'],$model['gender'],'bmi'); ?> % </td>
											</tr>
											<tr>
												<td>LBW:</td>
												<td><?= \Yii::$app->general->bmi($model['weight'],$model['weight_unit'],$model['height'],$model['height_unit'],$model['gender'],'lbw'); ?> kg / cm</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>