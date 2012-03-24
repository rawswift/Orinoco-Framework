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
 * database adapter interface
 */
interface databaseAdapter {
	// establish database connection; return boolean
	public function __construct($_host, $_username, $_password, $_database_name);
	// direct SQL query; return query result
	public function rawQuery($_sql_statement);
	// get field's type; return field type or FALSE
	public function getFieldType($_model, $_variable_name);	
	// check if field is auto increment
	public function isAutoIncrement($_model, $_variable_name);	
} // end class

// -EOF-