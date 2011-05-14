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
	
	/**
	 * returns current value to permalink or URI format
	 *
	 * @example foo bar --> foo-bar 
	 * @return string permalink format
	 */
	public function permalink($_string) {
		$_patterns = array('(-)', '(\')', '( )', '(\.)');
		$_replacements = array(';', '+', '-', '_');
		return preg_replace($_patterns, $_replacements, strtolower($_string));
	}	
	
	/**
	 * return a human string from permalink type string
	 *
	 * @example foo-bar --> foo bar 
	 * @return permalink format to human string
	 */
	public function unpermalink($_string) {
		$_patterns = array('(\+)', '(-)', '(_)', '(;)');
		$_replacements = array('\'', ' ', '.', '-');
		return preg_replace($_patterns, $_replacements, $_string);
	}	
	
	/**
     * print an array
     *
     * @param array $_arr array to print
     */
	public function debug($_arr) {
    	echo '<pre>';
        print_r($_arr);
        echo '</pre>';          
	}	
	
} // end class

// -EOF-