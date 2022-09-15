<?php
ini_set('max_execution_time', 300);
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'bootstrap' => ['log'],   
    'name'=> 'TFT',
    'timezone'=>'UTC',
    'components' => [  
        'emailtemplate' => [
            'class' => 'common\components\EmailsTemplate',
        ],
        'general' => [
            'class' => 'common\components\General',
        ],
        'ffmpeg' => [
            'class' => '\rbtphp\ffmpeg\Ffmpeg', 'path' => '/usr/bin/ffmpeg' 
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],  
        'setting' => [         
            'class' => 'common\components\Setting',
        ],
        'stripePayment' => [         
            'class' => 'common\components\StripePayment',
        ],
        'push' => [         
            'class' => 'common\components\Push',
        ],
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\DbMessageSource',
                    'db' => 'db',
                    'sourceLanguage' => 'en-US', // Developer language
                    'sourceMessageTable' => '{{%language_source}}',
                    'messageTable' => '{{%language_translate}}',
                    'cachingDuration' => 86400,
                    'enableCaching' => true,
                ],
            ],
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            // uncomment if you want to cache RBAC items hierarchy
            // 'cache' => 'cache',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                    [
                        'class' => 'yii\log\DbTarget',
                        'levels' => ['error'],
                        'except' => ['application','yii\web\HttpException:404'],
                    ],                    
                    [
                        'class' => 'yii\log\DbTarget',
                      //  'enable'=> YII_ENV_DEV,
                        'levels' => ['info'],
                        'categories'=>['api'],
                        'prefix' => function ($message) {
                            $user = Yii::$app->has('user', true) ? Yii::$app->get('user') :
                            'undefined user';
                            $userID = $user ? $user->getId(false) : 'GUEST';
                            return Yii::$app->controller->id.'/'.Yii::$app->controller->action->id;
                        },
                        'except' => ['application','yii\web\HttpException:404'],
                        'logVars' => [],
                        'exportInterval' => 50,
                        // 'logFile' => '@runtime/logs/api.log'
                    ],
                    [
                        'class' => 'yii\log\FileTarget',
                        'levels' => ['info'],                       
                        'categories'=>['login'],
                        'logVars' => [],
                        'exportInterval' => 50,
                        'logFile' => '@runtime/logs/login.log'
                    ]
                
            ]
                    ],       
    ],    
];