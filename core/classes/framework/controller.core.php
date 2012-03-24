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

require('view.core.php'); // require framework layer view

class coreController extends coreView {
	
	private $_controller_file;
	
	protected function isControllerFileExists() {
		if (isset($this->_class)) {
			$this->_controller_file =  APPLICATION_DIR . CONTROLLER_DIR . $this->_class;
		} else {
			$this->_controller_file =  APPLICATION_DIR . CONTROLLER_DIR . $this->_controller . CONTROLLER_FILE_SUFFIX . CONTROLLER_FILE_EXTENSION;
		}		

		if (!file_exists($this->_controller_file)) {
			return false;
		}
		return true;		
	}
	
	protected function isActionExists() {
		if(!is_callable(array($this->_controller . CONTROLLER_SUFFIX, $this->_action))) {
			return false;
		}
		return true;
	}
	
	protected function getControllerFilePath() {
		return $this->_controller_file;
	}
	
} // end class

// -EOF-