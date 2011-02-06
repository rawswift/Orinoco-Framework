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

class Helper {

	public function __construct() {
		// do something useful here?
	}
	
	/**
	 * return JSON encoded data
	 * @param $_data array
	 * @return none
	 */
	public function JSONResponse($_data) {
		$JSON_encoded = json_encode($_data);
		header("Content-Length: " . strlen($JSON_encoded));
		header("Content-type: application/json;");
		echo $JSON_encoded;
		exit(0);
	}
	
} // end class

// -EOF-