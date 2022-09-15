<?php

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php'
);
$l = 'en-US';
if(!empty($_GET['lang_id'])){
   $l = $_GET['lang_id'];
}else if(!empty($_COOKIE['lang_id'])){
    $l = $_COOKIE['lang_id'];
}
$config = [
    'id' => 'api',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log','assetsAutoCompress'],
    'controllerNamespace' => 'app\controllers',
    // 'language' => !empty($_REQUEST['lang_id'])?$_REQUEST['lang_id']:'en-US',
    'language' => $l,
    'modules' => [
        'translatemanager' => [
            'class' => 'lajax\translatemanager\Module',
            'root' => '@api',               // The root directory of the project scan.
            'scanRootParentDirectory' => true, // Whether scan the defined `root` parent directory, or the folder itself.
                                               // IMPORTANT: for detailed instructions read the chapter about root configuration.
            'layout' => null,         // Name of the used layout. If using own layout use 'null'.
            'allowedIPs' => ['*'],  // IP addresses from which the translation interface is accessible.
            //'roles' => ['@'],               // For setting access levels to the translating interface.
            'tmpDir' => '@runtime',         // Writable directory for the client-side temporary language files.
                                            // IMPORTANT: must be identical for all applications (the AssetsManager serves the JavaScript files containing language elements from this directory).
            'phpTranslators' => ['::t'],    // list of the php function for translating messages.
            'jsTranslators' => ['lajax.t'], // list of the js function for translating messages.
            'patterns' => ['*.js', '*.php'],// list of file extensions that contain language elements.
            'ignoredCategories' => ['yii'], // these categories won't be included in the language database.
            'ignoredItems' => ['config'],   // these files will not be processed.
            'scanTimeLimit' => null,        // increase to prevent "Maximum execution time" errors, if null the default max_execution_time will be used
            'searchEmptyCommand' => '!',    // the search string to enter in the 'Translation' search field to find not yet translated items, set to null to disable this feature
            'defaultExportStatus' => 1,     // the default selection of languages to export, set to 0 to select all languages by default
            'defaultExportFormat' => 'json',// the default format for export, can be 'json' or 'xml'
            'tables' => [                   // Properties of individual tables
                [
                    'connection' => 'db',   // connection identifier
                    'table' => '{{%language}}',         // table name
                    'columns' => ['name', 'name_ascii'],// names of multilingual fields
                    'category' => 'database-table-name',// the category is the database table name
                ]
            ],
            'scanners' => [ // define this if you need to override default scanners (below)
                '\lajax\translatemanager\services\scanners\ScannerPhpFunction',
                '\lajax\translatemanager\services\scanners\ScannerPhpArray',
                '\lajax\translatemanager\services\scanners\ScannerJavaScriptFunction',
                '\lajax\translatemanager\services\scanners\ScannerDatabase',
            ],
        ],
        'v1' => [
            'class' => 'app\modules\v1\Module',
        ],
    ],
    'components' => [   
       
        'assetsAutoCompress' => [
            'class'                         => '\skeeks\yii2\assetsAuto\AssetsAutoCompressComponent',
            'enabled'                       => !YII_ENV_DEV,
            
            'readFileTimeout'               => 3,           //Time in seconds for reading each asset file
            
            'jsCompress'                    => true,        //Enable minification js in html code
            'jsCompressFlaggedComments'     => true,        //Cut comments during processing js
            
            'cssCompress'                   => true,        //Enable minification css in html code
            
            'cssFileCompile'                => true,        //Turning association css files
            'cssFileRemouteCompile'         => false,       //Trying to get css files to which the specified path as the remote file, skchat him to her.
            'cssFileCompress'               => false,        //Enable compression and processing before being stored in the css file
            'cssFileBottom'                 => false,       //Moving down the page css files
            'cssFileBottomLoadOnJs'         => false,       //Transfer css file down the page and uploading them using js
            
            'jsFileCompile'                 => true,        //Turning association js files
            'jsFileRemouteCompile'          => true,       //Trying to get a js files to which the specified path as the remote file, skchat him to her.
            'jsFileCompress'                => true,        //Enable compression and processing js before saving a file
            'jsFileCompressFlaggedComments' => true,        //Cut comments during processing js
            
            
            'noIncludeJsFilesOnPjax'        => true,        //Do not connect the js files when all pjax requests            
            
            
        ],            
        'assetManager' => [
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'js'=>[]
                ],
                'yii\bootstrap\BootstrapPluginAsset' => [
                    'js'=>[]
                ],
                'yii\bootstrap\BootstrapAsset' => [
                    'css' => [],
                ],
        
            ],
            'appendTimestamp' => true,
        ],
        'request' => [
            'cookieValidationKey' =>'GROOVYTHEWEBFIRM',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
            'baseUrl' => '/',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-api', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'translatemanager' => [
            'class' => 'lajax\translatemanager\Component'
        ],
        'urlManagerBackend' => [
            'class' => 'yii\web\urlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'baseUrl' => '/administration',
        ],
        'urlManager' => [
            'baseUrl' => '/',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '/' => 'site/index',
                'success/<id>'=>'site/success',
                'client/subscribe/<trainee_id>'=>'client/subscribe',
                'pay'=>'site/pay',
            ],
        ],
        'response' => [
            'class' => 'yii\web\Response',
            'on beforeSend' => function ($event) {
                $response = $event->sender;   
                if(Yii::$app->setting->val('record_api') == 1){
                    $data['Authorization']  =  !empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])?$_SERVER['REDIRECT_HTTP_AUTHORIZATION']:"";
                    $data['Url']            =  !empty($_SERVER['REQUEST_URI'])?\yii\helpers\Url::toRoute($_SERVER['REQUEST_URI'], $schema = true):"";
                    $data['POST']           = $_POST;
                    $data['_GET']           = $_GET;
                    Yii::info($data,'api');
                }
                if (isset($response->data['type']) && $response->data['type'] == "apple") {
                    $response->data = $response->data['data'];
                    return $response;
                }
                if ($response->format == 'html' || $response->format == 'raw') {
                    return $response;
                }
                $responseData = $response->data;

                if (is_string($responseData) && json_decode($responseData)) {
                    $responseData = json_decode($responseData, true);
                }

                if ($response->statusCode >= 200 && $response->statusCode <= 299) {                  
                    $response->data = [
                        'success' => $responseData['status'],
                        'data'    => !empty($responseData['data'])?$responseData['data']:"",
                        'message' => !empty($responseData['message'])?$responseData['message']:"",
                        'status'  => $response->statusCode,
                    ];
                } else {
                    $response->data = [
                        'success' => false,
                        'data'    => $responseData,
                        'message' => !empty($responseData['message'])?$responseData['message']:"",
                        'status'  => $response->statusCode,
                    ];
                }
                return $response;
            },
        ],        
    ],
    'params'=>$params
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => ['*']
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['*']
    ];
}
return $config;
