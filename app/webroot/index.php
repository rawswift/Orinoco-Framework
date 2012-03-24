<?php
/**
 * Orinoco Framework - a lightweight MVC framework.
 * http://code.google.com/p/orinoco-framework/
 *  
 * Copyright (c) 2008-2012 Ryan Yonzon, http://ryan.rawswift.com/
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 */

/* Debug: measure execution time: start time */
$time_start = microtime(true);

require('../../core/config/environment.config.php');
require(CORE_CONFIG_DIR . 'framework.config.php');

require(CORE_CONFIG_DIR . 'router.config.php'); // load default route rules
require(CLASS_FRAMEWORK_DIR . 'router.core.php');

require(CLASS_FRAMEWORK_DIR . 'constructor.core.php');
require(CONFIG_DIR . 'application.config.php');

	$Router = new Router(); // instantiate router object
	Router::setDefaultRoutes($_default_routes); // set default routes
	require(CONFIG_DIR . 'routes.config.php'); // get developer's route configuration
	
	$_constructor = new coreConstructor($Router);
	$_constructor->dispatch(); // dispatch object method
	
/* Debug: measure execution time: end time then print */
$time_end = microtime(true);
$time = $time_end - $time_start;
echo  "<br /><pre>Execution time: " . round($time,3) . " s</pre>";
	
// -EOF-
