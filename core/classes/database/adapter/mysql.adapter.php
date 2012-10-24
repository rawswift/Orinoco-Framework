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

require('adapter.interface.php');

/**
 * MySQL implementation
 */
class mysql implements databaseAdapter {

	private $_db_resource;

	// constructor
	public function __construct($_host, $_username, $_password, $_database_name) {
		$this->_db_resource = mysql_connect($_host, $_username, $_password); // establish db connection
		if(!$this->_db_resource) {
			return false;
		}
		mysql_select_db($_database_name, $this->_db_resource); // select database
		return true;
	}
	
	// direct SQL query
	public function rawQuery($_sql_statement) {
		return mysql_query($_sql_statement, $this->_db_resource);		
	}
	
	// get field's type
    public function getFieldType($_model, $_variable_name) {
    	$_result = mysql_query('SHOW COLUMNS FROM ' . strtolower($_model) . ' WHERE Field = "' . $_variable_name . '";');
		if (mysql_num_rows($_result) != 0) {
        	$type = mysql_result($_result, 0, 'Type'); // get type from result
            $type = explode('(', $type);
            return $type[0];
		}
		return false;
	}
	
	// get field's flag
	public function getFieldFlags($_model, $_variable_name) {
		$_result = mysql_query('SHOW COLUMNS FROM ' . strtolower($_model) . ' WHERE Field = "' . $_variable_name . '";');
		if (mysql_num_rows($_result) != 0) {
			return mysql_field_flags($_result, 0);
		}
		return false;
	}
	
	// get field's enum data
	public function getEnumData($_model, $_field_name) {
		$_cols = mysql_query('SHOW COLUMNS FROM ' . strtolower($_model) . ' WHERE Field = "' . $_field_name . '";');
		$_cols_row = mysql_fetch_object($_cols);
		if(!is_object($_cols_row)) { // check if get anything
			return false;
		}
		$_raw_enum = preg_replace(array('(enum\()', '(\))', '(\')'), array('', '', ''), $_cols_row->Type); // remove data that is not needed
		return preg_split("/\,/", $_raw_enum, 0, PREG_SPLIT_NO_EMPTY); // return the enum data in array format
	}
	
	// check if field is auto increment
	public function isAutoIncrement($_model, $_field_name) {
		$_cols = mysql_query('SHOW COLUMNS FROM ' . strtolower($_model) . ' WHERE Field = "' . $_field_name . '";');
		$_cols_row = mysql_fetch_object($_cols);
		if($_cols_row->Extra == "auto_increment") {
			return true;
		}
		return false;
	}
	
	// check if field/column exists
	public function isExists($_model, $_field_name) {
		$_cols = mysql_query('SHOW COLUMNS FROM ' . strtolower($_model) . ' WHERE Field = "' . $_field_name . '";');
		$_cols_num_row = mysql_num_rows($_cols);
		if($_cols_num_row != 0) {
			return true;
		}
		return false;
	}	
	
	// get the ID generated in the last query
	public function getLastQueryID() {
		return mysql_insert_id();
	}

	// escape string wrapper
	public function escapeString($_str) {
		return mysql_real_escape_string($_str);
	}
	
	// fetch result as object
	public function fetchObject($_res) {
		return mysql_fetch_object($_res);
	}
	
	// set data pointer
	public function dataSeek($_res, $_pointer) {
		mysql_data_seek($_res, $_pointer);
	}
	
	// get number of data rows
	public function getNumRows($_res) {
		return mysql_num_rows($_res);
	}
	
	// self destruct
	public function __destruct() {
		unset($this);
	}
	
} // end class

// -EOF-