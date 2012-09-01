<?php
/**
 * PHP versions 5
 *
 * RailYard(tm) : The tracks of rapid development (http://myrailyard.com)
 * Copyright 2010, RGBKights
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright	Copyright 2010, RGBKights. (http://rgbknights.venatiostudios.com)
 * @link		http://myrailyard.com RailYard(tm) Project
 * @since		RailYard(tm) v 0.1.0
 * @license		MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

// Test for correct PHP version
if (!defined('PHP_VERSION_ID') || PHP_VERSION_ID < 50300)
	die('RailYard requires PHP 5.3 or higher');

// Define Constants
define('DS', DIRECTORY_SEPARATOR);
define('APP_PATH', dirname(dirname(__FILE__)));
define('FRAMEWORK_PATH', dirname(APP_PATH) . DS . 'RailYard' . DS);

// Load framework core
//include FRAMEWORK_PATH . DS . 'libs' . DS . "framework.class.php";

// Starts RailYard
//Framework::process_request();


include FRAMEWORK_PATH . DS . 'libs' . DS . "queryable.class.php";

$func = function($name)
{
	printf("Hello %s\r\n", $name);
};

$info = new ReflectionFunction($func);

var_dump(
	$info->getParameters(),
	$info->getName(),
	$info->isInternal(),
	$info->isDisabled(),
	$info->getFileName(),
	$info->getStartLine(),
	$info->getEndline(),
	$info->returnsReference()
);
