<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
       // 'css/site.css',
     	  'vendor/bootstrap/css/bootstrap.min.css',
	  'vendor/font-awesome/css/font-awesome.min.css',
	  'css/fontastic.css',
          'css/style.default.css',
	  'css/custom.css',


    ];
    public $js = [
    'vendor/jquery/jquery.min.js',
    'vendor/popper.js/umd/popper.min.js',
    'vendor/bootstrap/js/bootstrap.min.js',
    'vendor/jquery.cookie/jquery.cookie.js',
   'vendor/chart.js/Chart.min.js',
    'vendor/jquery-validation/jquery.validate.min.js',
    'js/charts-home.js',
   
    'js/front.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',

    ];
}
