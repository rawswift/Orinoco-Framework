<?php
/**
 * Orinoco Framework - a lightweight MVC framework.
 * https://github.com/rawswift/Orinoco-Framework
 *  
 * Copyright (c) 2008-2012 Ryan Yonzon, http://ryan.rawswift.com/
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 */

class Router {
	
	public static $_route_table = array(); // route table map
	public static $_default_routes = array(); // default route rules
	public static $_request; // current URI request
	public static $_controller; // controller
	public static $_action; // action/method
	public static $_class = null; // controller class (explicit)
	public static $_id = NULL; // ID
	
	public function __construct() {
		self::$_request = $_SERVER['REQUEST_URI']; // get current URI request
	}
	
	public function setDefaultRoutes($_default_routes) {
		self::$_default_routes = $_default_routes;
	}

	public function add($_uri, $_method_map) {
		self::$_route_table[trim($_uri)] = $_method_map;
	}
	
	public function getController() {
		if (isset(self::$_controller)) { // check if we already have a controller
			return self::$_controller; // return current controller
		} else { // else process controller and action request
			$_split = preg_split("/\?/", self::$_request); // split request
			$_uri = $_split[0];
			$_map = preg_split("/\//", $_uri, 0, PREG_SPLIT_NO_EMPTY);
			
			// check if there's a matching custom rule
			if ($_match = self::matchCustomConfig(self::$_route_table, $_uri)) {
				self::$_controller = $_match["controller"];	
				self::$_action = $_match["action"];
				if (isset($_match["class"])) {
					self::$_class = $_match["class"];
				}
				return self::$_controller; // return controller name
			} else {
				
				if (!isset($_map[0])) { // check if this is index URI (e.g. http://sub.domain.tld/)
					if (!self::matchDefaultRoutes($_uri, $_map)) { // check if we'll have a match using our default route rules
						// @todo find a better error message especially on debug mode
						header("HTTP/1.1 404 Not Found");
						echo 'Router Error: Controller "' . DEFAULT_CONTROLLER . '" Not Found.';
						exit(0);
					}
				} else { // No, we are not index URI
					if (!isset($_map[1])) { // check that we don't have an action/method on URI (e.g. /controller/action-name)
						if (!self::matchDefaultRoutes($_uri, $_map)) { // check if we'll have a match using our default route rules
							// @todo find a better error message especially on debug mode
							header("HTTP/1.1 404 Not Found");
							echo 'Router Error: Controller "' . $_map[0] . '" Not Found.';
							exit();
						}
					} else { // we have an action/method (e.g. /controller/action-name)
						if (!self::matchDefaultRoutes($_uri, $_map)) { // check if we'll have a match using our default route rules
							// @todo find a better error message especially on debug mode
							header("HTTP/1.1 404 Not Found");
							echo 'Router Error: Action "' . $_map[1] . '" Not Found.';
							exit();
						}
					} 
				}			
				return self::$_controller; // return controller name
			} // end second if
		} // end first if
	}
	
	public function getAction() {
		if (isset(self::$_action)) { // check if we already have a action/method
			return self::$_action; // return current action/method name
		} else { // throw 404 file not found header
			// @todo find a better error message especially on debug mode
			header("HTTP/1.1 404 Not Found");
			echo 'Router Error: Action Not Found.';
			exit();
		}			
	}

	public function explicitClass() {
		if (isset(self::$_class)) { // check class file is specified in router config
			return ltrim(self::$_class, "/"); // make sure there's no slashes on front (left) 
		}
		return false;		
	}

	public function hasID() {
		if (isset(self::$_id)) {
			return true;
		}
		return false;
	}
	
	public function getID() {
		return self::$_id;
	}
	
	public function getRouteTable() {
		return self::$_route_table;
	}
	
	public function getURIRequest() {
		return self::$_request;
	}
	
	/**
	 * Return an array map of URI
	 */
	public function mapURI() {
		$_uri_split = preg_split("/\?/", self::$_request); // split
		$_uri = $_uri_split[0]; // store the value for route comparison later
		$_map = preg_split("/\//", $_uri, 0, PREG_SPLIT_NO_EMPTY); // get resources
		return $_map;
	}
	
	/**
	 * Return an array map of GET parameter (in key/pair values)
	 */
	public function mapGET() {
		$_uri_split = preg_split("/\?/", self::$_request); // split
		
		if (!isset($_uri_split[1])) {
			return false;
		}
		
		$_get = $_uri_split[1]; // we only need the GET data
		$_get_arr = preg_split("/\&/", $_get, 0, PREG_SPLIT_NO_EMPTY); // split
		
		$_map = array(); // initialize an empty array
		foreach ($_get_arr as $k => $v) {
			$_split_val = preg_split("/\=/", $v, 0, PREG_SPLIT_NO_EMPTY); // split value for key/pair value
			$_map[$_split_val[0]] = $_split_val[1];
		}
		
		return $_map;
	}

	/**
	 * Match custom route config
	 */
	public function matchCustomConfig($_table, $_uri) {
		foreach($_table as $_key => $_value) {
			$_str = '(' . $_key . '$)'; // preg_match friendly
			if (preg_match($_str, $_uri)) {
				return $_value;
			}
			
		}
		return false;
	}
	
	/**
	 * Match using default route config
	 */
	public function matchDefaultRoutes($_original_uri, $_route_map) {
		// iterate through default routes
		foreach(self::$_default_routes as $_key => $_value) {
			// compare URI with the default route rule
			if(preg_match($_key, $_original_uri)) {
				// iterate and compare the set values of the default routing rule
				foreach($_value as $_k => $_v) {
					switch($_k) {
						case 'controller':
							if($_v == 'DEFAULT') {
								self::$_controller = DEFAULT_CONTROLLER;
							} else if($_v == 'CURRENT') {
								self::$_controller = $_route_map[0];
							} else {
								self::$_controller = $_v;
							}
							break;
						case 'action':
							if($_v == 'DEFAULT') {
								self::$_action = DEFAULT_ACTION;
							} else if($_v == 'CURRENT') {
								self::$_action = $_route_map[1];
							} else {
								self::$_action = $_v;
							}
							break;
						case 'id':
							self::$_id = $_route_map[self::$_default_routes[$_key]['id']];
							break;
						default:
							break;
					}
				}
			
				return true;
				
			} // end if
			
		} // end foreach loop
		
		return false;
		
	}
	
} // end class

// -EOF-