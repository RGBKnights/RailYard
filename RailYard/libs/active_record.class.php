<?php
define('PHP_ACTIVERECORD_VERSION_ID','1.0');

require 'activerecord/Singleton.php';
require 'activerecord/Config.php';
require 'activerecord/Utils.php';
require 'activerecord/DateTime.php';
require 'activerecord/Model.php';
require 'activerecord/Table.php';
require 'activerecord/ConnectionManager.php';
require 'activerecord/Connection.php';
require 'activerecord/SQLBuilder.php';
require 'activerecord/Reflections.php';
require 'activerecord/Inflector.php';
require 'activerecord/CallBack.php';
require 'activerecord/Exceptions.php';

spl_autoload_register('activerecord_autoload');

function activerecord_autoload($class_name)
{
	$path = ActiveRecord\Config::instance()->get_model_directory();
	$root = realpath(isset($path) ? $path : '.');

	if (($namespaces = ActiveRecord\get_namespaces($class_name)))
	{
		$class_name = array_pop($namespaces);
		$directories = array();

		foreach ($namespaces as $directory)
			$directories[] = $directory;

		$root .= DIRECTORY_SEPARATOR . implode($directories, DIRECTORY_SEPARATOR);
	}

	$file = "$root/$class_name.php";

	if (file_exists($file))
		require $file;
}
?>
