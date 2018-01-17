<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);
use kartik\mpdf\Pdf;

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'components' => [
        'assetManager' => [
            'bundles' => [
                'yii\web\JqueryAsset' => false,
            ],
        ],
        'pdf' => [
            'class' => Pdf::classname(),
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            // refer settings section for all configuration options
        ],
//        'htmlToPdf' => [
//            'class' => 'boundstate\htmlconverter\HtmlToPdfConverter',
//            'bin' => '/usr/bin/wkhtmltopdf',
//            // global wkhtmltopdf command line options
//            // (see http://wkhtmltopdf.org/usage/wkhtmltopdf.txt)
//            'options' => [
//                'print-media-type',
//                'disable-smart-shrinking',
//                'no-outline',
//                'page-size' => 'letter',
//                'load-error-handling' => 'ignore',
//                'load-media-error-handling' => 'ignore'
//            ],
//        ],
//        'htmlToImage' => [
//            'class' => 'boundstate\htmlconverter\HtmlToImageConverter',
//            'bin' => '/usr/bin/wkhtmltoimage',
//        ],
//        'response' => [
//            'formatters' => [
//                'pdf' => [
//                    'class' => 'boundstate\htmlconverter\PdfResponseFormatter',
//                    // Set a filename to download the response as an attachments (instead of displaying in browser)
//                    'filename' => 'attachment.pdf'
//                ],
//                'image' => [
//                    'class' => 'boundstate\htmlconverter\ImageResponseFormatter',
//                ],
//            ]
//        ],
        'request' => [
            'baseUrl' => '/forms/admin',
            'csrfParam' => '_csrf-backend',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'App',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '' => 'site/index',
                '<controller:\w+>/<action:\w+>/' => '<controller>/<action>',
            ],
        ],
    ],
    'params' => $params,
];
