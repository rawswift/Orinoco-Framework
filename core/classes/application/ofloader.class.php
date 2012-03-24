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

/**
 * OFLoader - A replacement for previous __autoload magic function which will allow other libraries to add their own autoload method
 */
class OFLoader {

	public static function autoloader($_class_name) {

		// try libs classes path
		$_lib_path = LIBS_DIR . strtolower($_class_name) . LIBS_FILE_SUFFIX . LIBS_FILE_EXTENSION;
			if(file_exists($_lib_path)){
				require($_lib_path);
				return true;
			}
		
		// try extension classes path
		$_extension_path = EXTENSION_DIR . strtolower($_class_name) . EXTENSION_FILE_SUFFIX . EXTENSION_FILE_EXTENSION;
			if(file_exists($_extension_path)){
				require($_extension_path);
				return true;
			}

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

		// @todo find a better error message especially on debug mode
		/*header("HTTP/1.1 404 Not Found");
		echo 'Model class "' . $_class_name . '" does not exists.';
		exit();*/
				
	}
	
} // end class

spl_autoload_register(array('OFLoader', 'autoloader')); // register class OFLoader::autoloader

// -EOF-