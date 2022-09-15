<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
use \yii\helpers\Url;

$this->title = $name;
?>
<div class="row">
    <div class="content-wrapper-before gradient-45deg-indigo-purple"></div>
    <div class="breadcrumbs-dark pb-0 pt-4"></div>
    <div class="col s12">
        <div class="container">
            <div class="section">               
                <div class="row">
                    <div class="col s12 m12 l12">
                        <div id="icon-sizes" class="card card-default">
                            <div class="card-content">
                                <div class="row">
                                    <div class="col s12 m12 l12">
                                        <div class="site-error">

                                            <h1><?= Html::encode($this->title) ?></h1>

                                            <div class="alert alert-danger">
                                                <h5 class="red-text text-darken-2"><?= nl2br(Html::encode($message)) ?></h5>
                                            </div>

                                            <p class="mt-2">
                                                The above error occurred while the Web server was processing your request.
                                            </p>
                                            <p>
                                                Please contact us if you think this is a server error. Thank you.
                                            </p>
                                            <a class="mt-2 btn waves-effect waves-light" href="<?= Url::toRoute(['/dashboard/index']);?>">
                                                Back to Home
                                            </a>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
