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

class coreHelper {

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