<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/main.css',
    	'css/holdon.css',
    	'http://vleermuizen.beta.swigledev.nl/sites/all/themes/vleermuiskasten/css/main.css'
    ];
    public $js = [
    	'js/custom.js',
    	'js/holdon.js',
    	'http://vleermuizen.beta.swigledev.nl/sites/all/themes/vleermuiskasten/js/search.js'
    	
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
