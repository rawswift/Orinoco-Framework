<?php
/**
 * Orinoco Framework - a lightweight MVC framework.
 * http://code.google.com/p/orinoco-framework/
 *  
 * Copyright (c) 2008-2011 Ryan Yonzon, http://ryan.rawswift.com/
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 */

/**
 * autoload classes; magic method
 *
 * @param string $_class_name name of class to instantiate
 * @return true if class is found else throw a 404 error
 */
function __autoload($_class_name) {
	
	// try model classes path
	$_app_model_file = APPLICATION_DIR . MODEL_DIR;
	$_model_path = $_app_model_file . strtolower($_class_name) . MODEL_FILE_SUFFIX . MODEL_FILE_EXTENSION;
		if(file_exists($_model_path)){
			require($_model_path);
			return true;
		}

	// try adapter classes path
	$_adapter_path = ADAPTER_DIR . strtolower($_class_name) . ADAPTER_FILE_SUFFIX . ADAPTER_FILE_EXTENSION;
		if(file_exists($_adapter_path)){
			require($_adapter_path);
			return true;
		}

	// try extension classes path
	$_extension_path = EXTENSION_DIR . strtolower($_class_name) . EXTENSION_FILE_SUFFIX . EXTENSION_FILE_EXTENSION;
		if(file_exists($_extension_path)){
			require($_extension_path);
			return true;
		}
		
	// @todo find a better error message especially on debug mode
	header("HTTP/1.1 404 Not Found");
	echo 'Model class "' . $_class_name . '" does not exists.';
	exit();
		
} // end __autoload()

require('helper.class.php');

class Controller extends Helper {

	// variables that will be available on the developer's controllers
	public $controller;
	public $action;
	public $id;	

	public function __construct() {
		$this->controller = Router::getController(); // get current controller
		$this->action = Router::getAction(); // get current action/method
		
		// check if there is an ID on current route (URL)
		if(Router::hasID()) {
			$this->id = Router::getID(); // get ID
		}
	}
	
	public function redirect($_mixed_url, $_timeout = 0, $_soft_redirect = false) {
		// initialize
		$_controller = $this->controller; // default: current controller
		$_action = NULL;
		$_url = '';
		
		// URL
		if(is_string($_mixed_url)) {
			$_url = $_mixed_url;
		} else if(is_array($_mixed_url)) {
			
			// check for controller (on parameter)
			if(isset($_mixed_url['controller'])) {
				$_controller = $_mixed_url['controller'];
			}
			
			// check for action (on parameter)
			if(isset($_mixed_url['action'])) {
				$_action = $_mixed_url['action'];
			}
			
			// construct main URL, controller and action/method
			$_url = '/' . $_controller;
			if(isset($_action)) {
				$_url = $_url . '/' . $_action;
			}

			// check for additional parameters
			if(isset($_mixed_url['parameter'])) {
				$_param = '?';
				foreach($_mixed_url['parameter'] as $_k => $_v) {
					$_param = $_param . $_k . '=' . urlencode($_v) . '&';
				}		
				// remove trailing "&"
				$_param[strlen($_param) - 1] = '';
				$_param = trim($_param);
				
				// add the additional parameter(s) on URL
				$_url = $_url . $_param;
			}
		}
		
		if ($_soft_redirect) {
			echo '<meta http-equiv="Refresh" content="' . $_timeout . '; URL=' . $_url . '">';
		} else {
			header('refresh:' . $_timeout . ';url=' . $_url);			
		}
		
		exit(0);
	}
	
} // end class

// -EOF-