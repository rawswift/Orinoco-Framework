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

require('database.class.php');
require('object.class.php');

/**
 * object-relational mapping (ORM)  
 */
class Record extends Database {

	/**
	 * constructor
	 */
	public function __construct() {
		$this->databaseConnect();
		$this->initModel();
	}

	/**
	 * initialize model, this will create a blank record object
	 */
	private function initModel() {
		$_object_var = get_object_vars($this);
		foreach($_object_var as $_key => $_value) {
			$this->$_key = new recordObject();
			$this->$_key->_model_name = strtolower(get_class($this));
				if(is_array($_value) && isset($_value['model'])) {
					$this->$_key->_associated_model = $_value;
				} else if(is_array($_value) && $_value['primary']) {
					$this->$_key->_is_primary = $_value['primary'];
				}
			
			$this->$_key->_property_name = $_key; // field name
			$this->$_key->_type = $this->getFieldType(get_class($this), $_key); // field type
			
			// check for enum fields
			if(preg_match('(enum)', $this->getFieldType(get_class($this), $_key))) {
				$_enum_data = $this->getEnumData(get_class($this), $_key);
					if(is_array($_enum_data)) {
						$this->$_key->_enum_data = $_enum_data;
					}
			}
		} // end foreach

		// freezes this model's properties
		// so we wouldn't have any problem getting its true properties
		$this->_this_model = clone($this);
	}

	/**
	 * get model's primary key
	 * 
	 * @return model's primary key, otherwise false
	 * @todo must not be dependent on model file, e.g. var $id = array('primary'=>true);
	 */
	private function getPrimaryKey() {
		$_object_var = get_object_vars($this->_this_model);
		foreach($_object_var as $_key => $_value) {
			if($this->$_key->_is_primary) {
				return $_key;
			}
		}
		return false;
	}
	
	/**
	 * save model record
	 *
	 * @param object|array $_param array or post object 
	 * @param bool $_debug set true if you want to see the generated SQL statement
	 * @return bool true if query is successful, otherwise false
	 */
	public function save($_param = NULL, $_debug = false) {

		// current model
		$_model_name = get_class($this);
		
		$_fields = '';
		$_values = '';		
		
		if(isset($_param)) {
			/**
			 * @todo should check if $_param is an array else return false
			 */
			$_properties = $_param; // assumes $_param is an array
		} else {
			/**
			 * @todo this might cause an error some time in the future? so it's safer to pass array as a parameter instead of relying on current model/field value
			 */
			$_properties = get_object_vars($this->_this_model);
		}
		
		$_c = count($_properties) - 1;

			foreach($_properties as $_k => $_v) {
				
				if ($this->isExists($_model_name, $_k)) {
					
					if(isset($_v)) {
						if(!$this->isAutoIncrement($_model_name, $_k)) {
							$_field_type = $this->_this_model->$_k->_type;
							if($_field_type) {
								$_fields = $_fields . $_k;
								$_val = $this->escapeString($_v); // escape string
						
								switch($_field_type) {
									case 'decimal':
									case 'numeric': 
									case 'date':
									case 'datetime':
									case 'timestamp': 
									case 'time':
									case 'year': 
									case 'char': 
									case 'varchar': 
									case 'tinyblob':
									case 'tinytext':
									case 'blob':
									case 'text':
									case 'mediumblob':
									case 'mediumtext':
									case 'longblob':
									case 'longtext':
									case 'enum': 
									//case 'set':								
										$_values = $_values . '"' . $_val . '"';
										break;
										
									case 'tinyint':
									case 'smallint':
									case 'mediumint':
									case 'int':
									case 'integer':
									case 'bigint':
									case 'float':
									case 'double':
									case 'double precision':
									case 'real':
										$_values = $_values . $_val;
										break;
										
									default:
										break;
								} // end switch
								
								if ($_c > 0) {
									$_fields = $_fields . ',';
									$_values = $_values . ',';
								}
								
							} // end check field_type
							
							$_c = $_c - 1;
							
						} // end isAutoIncrement
					} // end isset					
					
				} // end isExists

			} // end foreach

		// construct SQL statement
		$_sql = 'INSERT INTO ' . strtolower($_model_name);
		$_sql = $_sql . ' (' . trim($_fields, ',') . ') ';
		$_sql = $_sql . 'VALUES';		
		$_sql = $_sql . ' (' . trim($_values, ',') . ');';

		// if debug is on then show SQL statement and terminate
		if($_debug) {
			print_r($_sql);
			exit(0);
		}

		// do query
		if(!$this->rawQuery($_sql)) {
			return false;
		}
		
		return $this->getLastQueryID(); // return ID generated in the last query
	}	

	/**
	 * update model record
	 *
	 * @param int|array $_param accepts id or array {condition, order, limit} clause
	 * @param bool $_debug set true if you want to see the generated SQL statement
	 * @return bool true if query is successful, otherwise false
	 */
	public function update($_param = NULL, $_debug = false) {
	
		$_model_name = get_class($this); // get model name
		$_fields = '';

		if(isset($_param)) {
			/**
			 * @todo should check if $_param is an array else return false
			 */
			$_properties = $_param; // assumes $_param is an array
		} else {
			/**
			 * @todo this might cause an error some time in the future? so it's safer to pass array as a parameter instead of relying on current model/field value
			 */
			$_properties = get_object_vars($this->_this_model);		
		}
		
		$_c = count($_properties) - 1;
			foreach($_properties as $_k => $_v) {

				if ($this->isExists($_model_name, $_k)) {
					
					if(!($_k == 'id')) {
						$_field_type = $this->_this_model->$_k->_type;
						if($_field_type) {
							$_val = $this->escapeString($_v); // escape string
							switch($_field_type) {
								case 'decimal':
								case 'numeric': 
								case 'date':
								case 'datetime':
								case 'timestamp': 
								case 'time':
								case 'year': 
								case 'char': 
								case 'varchar': 
								case 'tinyblob':
								case 'tinytext':
								case 'blob':
								case 'text':
								case 'mediumblob':
								case 'mediumtext':
								case 'longblob':
								case 'longtext':
								case 'enum': 
								//case 'set':								
									if(isset($_v)) {
										$_fields = $_fields . $_k . ' = "' . $_val . '"';
									}
									break;
									
								case 'tinyint':
								case 'smallint':
								case 'mediumint':
								case 'int':
								case 'integer':
								case 'bigint':
								case 'float':
								case 'double':
								case 'double precision':
								case 'real':
									if(isset($_v)) {					
										$_fields = $_fields . $_k . ' = ' . $_val;
									}
									break;
									
								default:
									if(isset($_v)) {					
										$_fields = $_fields . $_k . ' = ' . $_val;
									}
									break;
									
							} // end switch
							
							if ($_c > 0) {
								$_fields = $_fields . ',';
							}
							
						} // end field_type
					} // end check $_k == 'id'
					
					$_c = $_c - 1;					
					
				} // end isExists				
				
			} // end foreach
		
		// construct SQL
		$_sql = 'UPDATE ' . strtolower($_model_name);
		$_sql = $_sql . ' SET ';
		$_sql = $_sql . trim($_fields, ',');
		
		/**
		 * @todo must not be dependent on ID; $this->id->_value
		 */
		$_sql = $_sql . ' WHERE ' . $this->getPrimaryKey() . ' = ' . $this->id->_value . ';';
		
		// if debug is on then show SQL statement and terminate
		if($_debug) {
			print_r($_sql);
			exit(0);
		}
		
		// do query
		if(!$this->rawQuery($_sql)) {
			return false;
		}
		return true;
	}
	
	/**
	 * find records w/ multiple table join support
	 *
	 * @param int|array $_param accepts id or array {condition, order, limit} clause
	 * @param bool $_debug set true if you want to see the generated SQL statement
	 * @return bool true if query is successful, otherwise false
	 */
	public function find($_param = NULL, $_debug = false) {

		// initialize get model map
		$_model_map = $this->mapModel($this->_this_model);

		// #1 - construct fields
		$_fields = NULL;
		foreach($_model_map['fields'] as $_k => $_v) {
			if(isset($_fields)) {
				$_fields = $_fields . ', '; 
			}
			$_fields = $_fields . $_v;
		}
		
		$_sql = $_fields . ' FROM ' . $_model_map['tables'];
		
		// #2 - construct table join
		if(isset($_model_map['join'])) {
			$_join = NULL;
			$_arr_count = count($_model_map['join']) - 1;
			
			for($i = 0; $i <= $_arr_count; $i++) {
				$_sql = $_sql . $_model_map['join'][$i];
			}
		}

		// #3 - check for additional parameters
		if(isset($_param)) {
			if(is_numeric($_param)) {
				$_current_model_name = get_class($this); // get current model name
				$_sql = $_sql . ' WHERE ' . $_current_model_name . '.' . $this->getPrimaryKey() . ' = ' . $_param;
			} else if(is_array($_param)) {
				// check for clause
				if(isset($_param['condition'])) {
					$_sql = $_sql . ' WHERE ' . $_param['condition'];
				}
				// check order
				if(isset($_param['order'])) {
					$_sql = $_sql . ' ORDER BY ' . $_param['order'];
				}
				// check limit
				if(isset($_param['limit'])) {
					$_sql = $_sql . ' LIMIT ' . $_param['limit'];
				}
			}
		}

		// if debug is on then show SQL statement and terminate
		if($_debug) {
			print_r($_sql);
			exit(0);
		}
		
		// #4 - do query
		$this->_result = $this->rawQuery('SELECT ' . $_sql . ';');

		$_res = $this->fetchObject($this->_result);
		// check if we get any record
		if(!is_object($_res)) {
			return false;
		}
		
		$this->dataSeek($this->_result, 0); // set data pointer to zero
		
		// #5 - create field objects based on query result
		$this->createRecordObject($_res);
		
		return true;
	}
	
	/**
	 * select record(s) for edit, update or delete
	 *
	 * @param int|array $_param accepts id or array {condition, order, limit} clause
	 * @param bool $_debug set true if you want to see the generated SQL statement
	 * @return bool true if query is successful, otherwise false
	 */
	public function select($_param = NULL, $_debug = false) {

		$_model_name = get_class($this->_this_model); // current model
		$_fields = '';

		$_properties = get_object_vars($this->_this_model); // get object variables and save it in field variable
		$_c = count($_properties) - 1;
			foreach($_properties as $_k => $_v) {
				$_fields = $_fields . $_k;
					if ($_c > 0) {
						$_fields = $_fields . ',';
					}
				$_c = $_c - 1;
			}
		
		$_sql = $_fields . ' FROM ' . strtolower($_model_name);
		
		if(isset($_param)) {
			if(is_numeric($_param)) {
				$_sql = $_sql . ' WHERE ' . $this->getPrimaryKey() . ' = ' . $_param;
			} else if(is_array($_param)) {
				// check for clause
				if(isset($_param['condition'])) {
					$_sql = $_sql . ' WHERE ' . $_param['condition'];
				}
				// check order
				if(isset($_param['order'])) {
					$_sql = $_sql . ' ORDER BY ' . $_param['order'];
				}
				// check limit
				if(isset($_param['limit'])) {
					$_sql = $_sql . ' LIMIT ' . $_param['limit'];
				}
			}
		}

		// if debug is on then show SQL statement and terminate
		if($_debug) {
			print_r($_sql);
			exit(0);
		}
				
		$this->_result = $this->rawQuery('SELECT ' . $_sql . ';'); // do query
		$_res = $this->fetchObject($this->_result);
			if(!is_object($_res)) {
				return false;
			}
		
		$this->dataSeek($this->_result, 0); // set data pointer to zero

		$_object_var = get_object_vars($_res); // create field objects based on query result

		// set recod value and properties
		foreach($_object_var as $_key => $_value) {
			$this->$_key->_value = $_res->$_key;
			$this->$_key->_property_name = $_key;
			$this->$_key->_model_name = strtolower($_model_name);
			//$this->$_key->_current_controller = $_route->_controller; // Nov 12, 2010 @todo do we need to set a value?
			$this->$_key->_record_id = $_res->id;
		}
		return true;
	}
	
	/**
	 * delete model record
	 *
	 * @param int|array $_param accepts id or array {condition, order, limit} clause
	 * @param bool $_debug set true if you want to see the generated SQL statement
	 * @return bool true if query is successful, otherwise false
	 */
	public function delete($_param = NULL, $_debug = false) {
		$_sql = 'DELETE FROM ' . strtolower(get_class($this));

		if(isset($_param)) {
			if(is_numeric($_param)) {
				$_current_model_name = get_class($this); // get current model name
				$_sql = $_sql . ' WHERE ' . $_current_model_name . '.' . $this->getPrimaryKey() . ' = ' . $_param;
			} else if(is_array($_param)) {
				// check for clause
				if(isset($_param['condition'])) {
					$_sql = $_sql . ' WHERE ' . $_param['condition'];
				}
			}
		} else {
			if(!isset($this->id)) {
				return false;
			}
			/**
			 * @todo must not be dependent on ID; $this->id->_value
			 */
			$_sql = $_sql . ' WHERE ' . $this->getPrimaryKey() . ' = ' . $this->id->_value . ';';
		}
		
		// if debug is on then show SQL statement and terminate
		if($_debug) {
			print_r($_sql);
			exit(0);
		}

		// do query
		if(!$this->rawQuery($_sql)) {
			return false;
		}
		return true;
	}	
	
	/**
	 * iterate through model's records
	 *
	 * @return bool true if record found, otherwise false
	 */
	public function iterate() {
		$_row = $this->fetchObject($this->_result);
		if(!empty($_row)) {
			$_field_objects = get_object_vars($_row);
				foreach($_field_objects as $_key => $_value) {
					$this->$_key->_value = $_row->$_key;
					//$this->$_key->_current_controller = $_route->_controller; // Nov 12, 2010 @todo do we need to set a value?
					if(isset($_row->id)) {
						$this->$_key->_record_id = $_row->id;
					}					
				}
			return true;
		}
		return false;
	}
	
	/**
	 * go to next record (wrapper to iterate method)
	 *
	 * @return bool true if record found, otherwise false
	 */
	public function next() {
		return $this->iterate();
	}
	
	/**
	 * go to the first record
	 *
	 * @return bool true if record found, otherwise false
	 */
	public function first($_cursor = 0) {
			if(!isset($this->_result)) {
				return false;
			}

		// fix cursor offset
		if ($_cursor != 0) {
			$_cursor = $_cursor - 1;
		}
			
		$this->dataSeek($this->_result, $_cursor); // set data pointer to first record: one(1)
		
		$_row = $this->fetchObject($this->_result);
		if(!empty($_row)) {
			$_field_objects = get_object_vars($_row);
				foreach($_field_objects as $_key => $_value) {
					$this->$_key->_value = $_row->$_key;
					//$this->$_key->_current_controller = $_route->_controller; // Nov 12, 2010 @todo do we need to set a value?
					if(isset($_row->id)) {
						$this->$_key->_record_id = $_row->id;
					}					
				}
			$this->createRecordObject($_row);
			return true;
		}
		return false;
		
	}
	
	/**
	 * go to the last record
	 *
	 * @return bool true if record found, otherwise false
	 */
	public function last($_offset = 1) {
			if(!isset($this->_result)) {
				return false;
			}
		
		$this->dataSeek($this->_result, $this->getNumRows($this->_result) - $_offset - 1); // set data pointer to zero

		$_row = $this->fetchObject($this->_result);
		if(!empty($_row)) {
			$_field_objects = get_object_vars($_row);
				foreach($_field_objects as $_key => $_value) {
					$this->$_key->_value = $_row->$_key;
					//$this->$_key->_current_controller = $_route->_controller; // Nov 12, 2010 @todo do we need to set a value?
					if(isset($_row->id)) {
						$this->$_key->_record_id = $_row->id;
					}					
				}
			$this->createRecordObject($_row);
			return true;
		}
		return false;
		
	}
	
	/**
	 * returns model's row count
	 *
	 * @return bool true if record found, otherwise false
	 */
	public function record_count() {
		if(!isset($this->_result)) {
			return false;
		}
		return $this->getNumRows($this->_result);		
	}

	/**
	 * direct/raw SQL query
	 *
	 * @param string $_sql_statement SQL statement
	 * @return bool true if query is successful, otherwise false
	 */
	public function query($_sql_statement) {
		$this->_result = $this->rawQuery($_sql_statement); // do direct SQL query
		$_res = $this->fetchObject($this->_result);
			if(!is_object($_res)) {
				return false;
			}
		$this->dataSeek($this->_result, 0); // set data pointer to zero
		$this->createRecordObject($_res); // create field objects based on query result
		return true;		
	}

	/**
	 * map models, recursive method
	 *
	 * @param object $_model_object accepts model object, usually '$this' object
	 * @param array $_map array of mapped models
	 * @param string $_join join clause
	 * @param array $_prop_lists property lists, to keep track of properties
	 * @param array $_field_specific include only specific model field
	 * @param bool $_id_mapped set true if field ID is already mapped
	 * @return bool true if query is successful, otherwise false
	 */
	private function mapModel($_model_object, $_map = NULL, $_join = NULL, $_prop_lists = NULL, $_field_specific = NULL, $_id_mapped = false) {
		   
			// unset variable $_this_model
			// so that this will not reflect on the SQL statement later on
			unset($_model_object->_this_model);
		
			$_current_model_name = get_class($_model_object); // get model name
			
			$_field_specific_arr = $_field_specific;
			$_field_specific = NULL;
			
			$_check_has_associate = true;
			
			if(isset($_map['tables'])) {
					$_map['tables'] = $_current_model_name . ' ' . $_join . ' JOIN (' . $_map['tables'];
			} else {
					$_map['tables'] = $_current_model_name;
			}
		   
			$_model_vars = get_object_vars($_model_object); // get model's variables
		   
					// recurse through associated models
					foreach($_model_vars as $_key => $_value) {
						   
						$_relationship = array();
                        if(isset($_model_object->$_key->_associated_model)) {
							$_relationship = $_model_object->$_key->_associated_model;
                        }
														   
							if((!$_id_mapped) || ($_key != 'id')) {
								if(isset($_field_specific_arr)) {
									if(in_array($_key, $_field_specific_arr)) {
										if(isset($_prop_lists)) {
											if(!in_array($_key, $_prop_lists)) {
												$_prop_lists[] = $_key;
												$_map['fields'][] = $_current_model_name . '.' . $_key;
												$_check_has_associate = true;
											} else {
												$_check_has_associate = false;
											}
										} else {
											$_prop_lists[] = $_key;
											$_map['fields'][] = $_current_model_name . '.' . $_key;
											$_check_has_associate = true;
										}
									} else {
										$_check_has_associate = false;
									}
								} else {
									if(isset($_prop_lists)) {
											if(!in_array($_key, $_prop_lists)) {
												$_prop_lists[] = $_key;
												$_map['fields'][] = $_current_model_name . '.' . $_key;
												$_check_has_associate = true;
											} else {
												$_check_has_associate = false;
											}
									} else {
										$_prop_lists[] = $_key;
										$_map['fields'][] = $_current_model_name . '.' . $_key;
										$_check_has_associate = true;
									}
								}
								$_id_mapped = true; // set found ID flag									
							} else {
								$_check_has_associate = false;
							}
							
						if($_check_has_associate) {
							
								// check if there is an associated model for this variable
								if(isset($_relationship['model'])) {
										// set join type
										if(!isset($_relationship['join'])) {
											$_join_type = 'INNER'; // default
										} else {
											$_join_type = strtoupper($_relationship['join']);
										}
									   
										// set join comparison
										if(!isset($_relationship['on'])) {
											$_join_on = 'id';
										} else {
											$_join_on  = $_relationship['on'];
										}
									   
										if(!isset($_map['join'])) {
											$_map['join'] = NULL; // init join entry
										}
										$_map['join'][] = ') on ' . $_relationship['model'] . '.' . $_join_on . ' = ' . $_current_model_name . '.' . $_key;
									   
										if(isset($_relationship['field'])) {
											$_field_specific = $_relationship['field']; // specific field is set
										}
										
										$_associated_model = $_relationship['model'];
									   
										// recurse
										$_map = $this->mapModel(new $_associated_model(), $_map, $_join_type, $_prop_lists, $_field_specific, $_id_mapped);
								}

						} // end check if has associate
						
					}
				   
			return $_map;
	}
	
	/**
	 * create record object 
	 *
	 * @param object $_object resource object e.g. returned from a recent SQL query
	 */
	private function createRecordObject($_object) {
		
		$_object_var = get_object_vars($_object);
		
		foreach($_object_var as $_key => $_value) {
			/**
			 * @todo set values and properties just like select() method
			 * Update: (1) Investigate further on this, result are similar to select method (2) Investigate on else clause, it seems properties are not set there
			 */
			if(isset($this->$_key)) {
				if(!($this->$_key instanceof recordObject)) {
					$this->$_key = new recordObject();
					$this->$_key->_value = $_value;
				}
			} else {
					$this->$_key = new recordObject();
					$this->$_key->_value = $_value;
			}
		}
	}
	
	/**
	 * destructor
	 */
	public function __destruct() {
		unset($this);
	}
	
} // end class

// -EOF-