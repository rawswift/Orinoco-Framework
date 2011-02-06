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

class coreView {

	/**
	 * render controllers/methods view template
	 * this method must be called inside the layout template/wrapper
	 * to pull/render the method view
	 * 
	 * @return bool true is successful, otherwise false
	 */  
	public function render_content() {
		// use associated view template 
		$_content_view = APPLICATION_DIR . CONTENT_DIR . $this->_controller . '/' . $this->_action . VIEW_FILE_SUFFIX . VIEW_FILE_EXTENSION;
		
			// check if action/method view file exists
			if(!file_exists($_content_view)) {
				return false; // NO verbose
			}
			
			// include/use view template
			require($_content_view);
			return true;				
	}
	
	/**
	 * render partial template
	 *
	 * @param string $_partial_name partial template name
	 * @return bool true is successful, otherwise false
	 */
	public function render_partial($_partial_name) {
		$_partial_path = APPLICATION_DIR . PARTIAL_DIR . $_partial_name . PARTIAL_FILE_SUFFIX . PARTIAL_FILE_EXTENSION;
			
			// check if partial template exists
			if (!file_exists($_partial_path)) {
				return false; // NO verbose
			}
			
				require($_partial_path);
				return true;
	}	
	
	/**
	 * get default layout/wrapper template
	 *
	 * @return bool|string default layout's path
	 */
	protected function getDefaultLayout() {
		$_layout_wrapper = APPLICATION_DIR . LAYOUT_DIR . DEFAULT_LAYOUT . LAYOUT_FILE_SUFFIX . LAYOUT_FILE_EXTENSION;
		if(file_exists($_layout_wrapper)) { 
			return $_layout_wrapper;
		}
		return false;
	}
	
} // end class

// -EOF-