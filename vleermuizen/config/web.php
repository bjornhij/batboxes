<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'vleermuiskasten',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'kint', 'drupal'],
    'components' => [
        'request' => [
            'cookieValidationKey' => 'Vq14bLnTBkcuEk72ke4_LQtN2SHQXhj2',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\Users',
            'enableAutoLogin' => false,
        	'loginUrl' => 'http://vleermuizen.beta.swigledev.nl/user',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => true,
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '<controller:\w+>/<action:(\w|-)+>/<id:\d+>'=>'<controller>/<action>',
                '<controller:\w+>/<action:(\w|-)+>/<code:\S+>'=>'<controller>/<action>',
                '<controller:\w+>/<action:(\w|-)+>'=>'<controller>/<action>',
                '' => 'index/index',
                'login' => 'auth/login',
                'logout' => 'auth/logout',
                '<controller:\w+>'=>'<controller>',
            ],
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
    	'authManager' => [
    		'class' => 'yii\rbac\DbManager',
    	],
    	'kint' => [
    		'class' => 'app\components\Kint\Kint',
    	],
    	'view' => [
    		'class' => 'app\components\View',
    	],
    	'drupal' => [
    		'class' => 'app\components\Drupal',
    	],
    	'WGS84' => [
    		'class' => 'app\components\WGS84'
    	],
    	'session' => [
        	'class' => 'yii\web\DbSession',
    	],
    	'db' => require(__DIR__ . '/db.php'),
    ],
    'params' => $params,
	'modules' => [
		'redactor' => 'yii\redactor\RedactorModule',
	],
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
