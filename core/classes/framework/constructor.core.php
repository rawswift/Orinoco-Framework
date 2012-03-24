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

require('controller.core.php'); // require framework layer controller

// classes that will be available for application layer (developer's classes)  
require(CLASS_APPLICATION_DIR . 'controller.class.php');
require(DATABASE_DIR . 'record.class.php');

class coreConstructor extends coreController {
	
	protected $_controller;	// controller
	protected $_action;	// action/method
	protected $_class = null;	// controller class (explicit)
	protected $_id = NULL; // ID (from URI)
	
	public function __construct($_route) {
		$this->_controller = $_route->getController();
		$this->_action = $_route->getAction();
		
		if ($_route->hasID()) { // get ID if we have one
			$this->_id = $_route->getID();
		}

		if ($_route->explicitClass()) { // class are explicitly specified
			$this->_class = $_route->explicitClass();
		}
	}
	
	public function dispatch() {
		if ($this->isControllerFileExists()) {
			
			require($this->getControllerFilePath());
			
			// create an object of the developer's controller class
			// using the class name as object name
			$_objname = $this->_controller . CONTROLLER_SUFFIX;
			$$_objname = new $_objname();
			
			if ($this->isActionExists()) {
				// execute action/method
				$_method_name = $this->_action;
				$$_objname->$_method_name();
				
				// create inherited variables on-fly
				// variables that will be available for view templates
				foreach($$_objname as $_k => $_v) {
					$this->$_k = $_v;
				}		

					// construct view template
					if(isset($this->layout)) { // check if layout variable is set
						$_layout_file = APPLICATION_DIR . LAYOUT_DIR . $this->layout . LAYOUT_FILE_SUFFIX . LAYOUT_FILE_EXTENSION;	
							if (!file_exists($_layout_file)) {
								// @todo find a better error message especially on debug mode
								header("HTTP/1.1 404 Not Found");
								echo 'Constructor Error: Layout/template "' . $this->layout . '" does not exists.';
								exit();
							} else { // load alternate layout
								require($_layout_file);						
							}
					} else { // render default layout wrapper
						require($this->getDefaultLayout());
					}
				
			} else {
				// @todo find a better error message especially on debug mode
				header("HTTP/1.1 404 Not Found");
				echo 'Constructor Error: Action "' . $this->_action . '" under "' . $this->_controller . CONTROLLER_SUFFIX . '" controller/class does not exists.';
				exit();
			}
		} else {
			// @todo find a better error message especially on debug mode
			header("HTTP/1.1 404 Not Found");
			echo 'Constructor Error: Controller file "' . $this->_controller . CONTROLLER_FILE_SUFFIX . CONTROLLER_FILE_EXTENSION . '" Not Found.';
			exit();
		}
	}
	
} // end class

// -EOF-