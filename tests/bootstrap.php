<?php
/**
 * Leaps Framework [ WE CAN DO IT JUST THINK IT ]
 *
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2015 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
// ensure we get report on all possible php errors
error_reporting(-1);

define('YII_ENABLE_ERROR_HANDLER', false);
define('YII_DEBUG', true);
$_SERVER['SCRIPT_NAME'] = '/' . __DIR__;
$_SERVER['SCRIPT_FILENAME'] = __FILE__;

require_once(__DIR__ . '/../vendor/autoload.php');
require_once(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

Leaps::setAlias('@xutongle/extensions/apistore', __DIR__);
Leaps::setAlias('@xutongle/apistore', dirname(__DIR__));
