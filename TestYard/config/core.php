<?php

/**
 * Debug Level:
 *
 * Production Mode:
 * 	0: No error messages, errors, or warnings shown.
 *
 * Development Mode:
 * 	1: Errors and warnings shown, model caches refreshed.
 * 	2: As in 1, but also with full debug messages and SQL output.
 */
Configure::write('debug', 2);

/**
 * Correct your server timezone to fix the date & time related errors.
 */
date_default_timezone_set('UTC');

/**
* Log Level:
*
* In case of Production Mode Railyard gives you the possibility to continue logging errors.
*
* The following parameters can be used:
*  Boolean: Set true/false to activate/deactivate logging
*    Configure::write('log', true);
*
*  Integer: Use built-in PHP constants to set the error level (see error_reporting)
*    Configure::write('log', E_ERROR | E_WARNING);
*    Configure::write('log', E_ALL ^ E_NOTICE);
*/
Configure::write('log', true);

/**
 * Application wide charset encoding
 */
Configure::write('App.encoding', 'UTF-8');

?>