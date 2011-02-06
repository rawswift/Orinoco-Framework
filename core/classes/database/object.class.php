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

/**
 * an object representation of model's properties or table's fields  
 */
class recordObject {

	// field properties
	public $_value = NULL; // initial value is NULL
	/*
	 * @todo give $_type variable a value
	 */
	public $_type; // currently NOT being used; give this a value; column type?
	public $_property_name;
	public $_record_id;
	//public $_current_controller; // deprecated Nov 19, 2010
	public $_model_name;
	public $_associated_model;
	public $_enum_data;
	public $_is_primary = false;
	
	/**
	 * magic method that returns value when object is used as a string
	 *
	 * @return string current value
	 */
	public function __toString() {
		if (isset($this->_value)) {
			return (string) $this->_value; // cast string value
		} else {
			//return ""; // @todo Must we return NULL instead of ""?
			return NULL;
		}
	}

	/**
	 * set object value ($_value)
	 * 
	 * @param string|int|float|date
	 */
	public function value($_value) {
		$this->_value = $_value;
	}
	
	/**
	 * returns uppercased value
	 *
	 * @return string uppercased value
	 */
	public function to_upper() {
		return strtoupper($this->_value);
	}

	/**
	 * returns lowercased value/string
	 *
	 * @return string lowercased value
	 */
	public function to_lower() {
		return strtolower($this->_value);
	}

	/**
	 * returns a value/string with the first charater in uppercase
	 *
	 * @return string uppercased value
	 */
	public function to_upper_first() {
		return ucfirst($this->_value);
	}
	
	/**
	 * returns a value/string with all first character of words in uppercase 
	 *
	 * @return string uppercased words
	 */
	public function to_upper_words() {
		return ucwords($this->_value);
	}
	
	/**
	 * returns current value to permalink or URI format
	 *
	 * @example foo bar --> foo-bar 
	 * @return string permalink format
	 */
	public function to_permalink() {
		$_patterns = array('(-)', '(\')', '( )');
		$_replacements = array(';', '+', '-');
		return preg_replace($_patterns, $_replacements, $this->_value);
	}
	
	/**
	 * returns MD5 hash with salt option
	 *
	 * @param string $_salt salt/string
	 * @return MD5 hash
	 */
	public function md5_hash($_salt = NULL) {
		return md5($this->_value . $_salt);
	}

	/**
	 * returns SHA-1 hash with salt option
	 *
	 * @param string $_salt salt/string
	 * @return SHA-1 hash
	 */
	public function sha1_hash($_salt = NULL) {
		return sha1($this->_value . $_salt);
	}
	
	/**
	 * returns formatted date
	 *
	 * @param string $_format date format
	 * @return string
	 */
	public function format_date($_format = 'F d, Y') {
		return date($_format, strtotime($this->_value));
	}
	
	/**
	 * returns formatted timestamp
	 *
	 * @param string $_format timestamp format
	 * @return string
	 */
	public function format_timestamp($_format = 'F d, Y g:ia') {
		return date($_format, strtotime($this->_value));
	}

	/**
	 * returns formatted time
	 *
	 * @param string $_format time format
	 * @return string
	 */
	public function format_time($_format = 'g:ia') {
		return date($_format, strtotime($this->_value));
	}

	/**
	 * create and print basic input tag
	 *
	 * @param array $_array_attributes additional tag attributes
	 */
	public function input_tag($_array_attributes = NULL) {
		$_attribute = NULL;
		$_input_name = $this->_model_name . '[' . $this->_property_name . ']';
		$_id_name = $_input_name; // input name is the same with id name
		$_check_for_value = true;
						
		if(isset($_array_attributes)) {
			foreach($_array_attributes as $_k => $_v) {
				if($_k == 'name') {
					$_input_name = $_v;
				} else if($_k == 'id') {
					$_id_name = $_v;
				} else if($_k == 'value') {
					$_current_value = $_v;
					$_check_for_value = false;					
				} else {
					$_attribute = $_attribute . ' ' . $_k . '="' . $_v . '"';
				}
			}		
		}
		
		if($_check_for_value) {
			$_current_value = '';
			if(isset($this->_value)) {
				$_current_value = stripslashes($this->_value); 
			} else if(isset($_POST[$this->_model_name][$this->_property_name])) {
				$_current_value = $_POST[$this->_model_name][$this->_property_name];
			}
		}
		
		$_input_tag = '<input id="' . $_id_name . '" name="' . $_input_name . '" value="' . $_current_value . '" ' . $_attribute . ' />';
		print $_input_tag;
	}
	
	/**
	 * create and print basic textarea tag
	 *
	 * @param array $_array_attributes additional tag attributes
	 * @param string $_init_text initial text that will appear in textarea
	 */
	public function textarea_tag($_array_attributes = NULL, $_init_text = NULL) {
		$_attribute = NULL;
		$_textarea_name = $this->_model_name . '[' . $this->_property_name . ']';
		$_id_name = $_textarea_name;
				
		if(isset($_array_attributes)) {
			foreach($_array_attributes as $_k => $_v) {
				if($_k == 'name') {
					$_textarea_name = $_v;
				} else if($_k == 'id') {
					$_id_name = $_v;	
				} else {
					$_attribute = $_attribute . ' ' . $_k . '="' . $_v . '"';
				}
			}		
		}
		
		if(isset($this->_value)) {
			$_init_text = stripslashes($this->_value); 
		} else if(isset($_POST[$this->_model_name][$this->_property_name])) {
			$_init_text = $_POST[$this->_model_name][$this->_property_name];
		}
		
		$_textarea_tag = '<textarea id="' . $_id_name . '" name="' . $_textarea_name . '" ' . $_attribute . '>' . $_init_text . '</textarea>';
		print $_textarea_tag;
	}
	
	/**
	 * create and print basic select tag
	 *
	 * @param string $_option option resource
	 * @param string|array $_init_choice initial text choice or array
	 * @param string $_value value attribute to use; the default is 'id'
	 */
	public function select_tag($_option = NULL, $_array_attributes = NULL, $_init_choice = NULL, $_value = 'id') {
		$_option_tags = '';
		if(!isset($_option)) {
			if(is_array($this->_enum_data)) {
				$_arr_count = count($this->_enum_data);
				$_option_tags = '';

				if(isset($_init_choice)) {
					if(is_array($_init_choice)) {
						foreach($_init_choice as $_k => $_v) {
							$_option_tags .= '<option value="' . $_v . '">' . $_k . '</option>';						
						}
					} else {
						$_option_tags .= '<option value="">' . $_init_choice . '</option>';					
					}
				}
				
				for($_c = ($_arr_count - 1); $_c >= 0; $_c--) {
					if($this->_enum_data[$_c] == $this->_value) {
						$_selected = ' selected="selected"';
					} else {
						$_selected = '';
						if((isset($_POST[$this->_model_name][$this->_property_name])) && ($_POST[$this->_model_name][$this->_property_name] == $this->_enum_data[$_c])) {
							$_selected = ' selected="selected"'; 
						}
					}
					$_option_tags = $_option_tags . '<option value="' . $this->_enum_data[$_c] . '"' . $_selected . '>' . $this->_enum_data[$_c] . '</option>';
				}
			} else {
				$_option_tags = '<option value="">No options</option>';
			}
			
		} else {
			
			if(isset($_init_choice)) {
				if(is_array($_init_choice)) {
					foreach($_init_choice as $_k => $_v) {
						$_option_tags = '<option value="' . $_v . '">' . $_k . '</option>';						
					}
				} else {
					$_option_tags = '<option value="">' . $_init_choice . '</option>';					
				}
			}
			
			if(isset($this->_associated_model['model'])) {
				$_model_name = $this->_associated_model['model'];
				$_model = new $_model_name();
				$_model->find();
				
				while($_model->iterate()) {
					if($this->_value == $_model->$_value->_value) {
						$_selected = ' selected="selected"';
					} else {
						$_selected = '';
						if((isset($_POST[$this->_model_name][$this->_property_name])) && ($_POST[$this->_model_name][$this->_property_name] == $_model->$_value->_value)) {
							$_selected = ' selected="selected"'; 
						}
					}
						$_option_tags = $_option_tags . '<option value="' . $_model->$_value->_value. '"' . $_selected . '>' . $_model->$_option->_value . '</option>';
				}
				unset($_model);
			}
		}
		
		$_attribute = NULL;
		if(isset($_array_attributes)) {
			foreach($_array_attributes as $_k => $_v) {
				if($_k == 'name') {
					$_select_tag_name = $_v;
				} else if($_k == 'id') {
					$_select_id_name = $_v;	
				} else {
					$_attribute = $_attribute . ' ' . $_k . '="' . $_v . '"';
				}
			}		
		} else {
			$_select_tag_name = $this->_model_name . '[' . $this->_property_name . ']';
			$_select_id_name = $_select_tag_name;
		}
		
		$_select_tag = '<select id="' . $_select_id_name . '" name="' . $_select_tag_name . '" ' . $_attribute . '>' . $_option_tags . '</select>';
		print $_select_tag;
	}
	
	/**
	 * construct a hyperlink which points to its record ID
	 *
	 * @param string $_action action name
	 * @param string $_title link name
	 * @param string $_controller controller name
	 * @param array $_additional_parameters additional parameters, e.g. ?id=1&name=foo&add=bar
	 */
	public function link_id_to($_action = NULL, $_title = NULL, $_controller = NULL, $_additional_parameters = NULL) {

		// controller
		if(isset($_controller) && $_controller != '') {
			$_to_controller = $_controller;
		} else {
			$_to_controller = $this->_current_controller;
		}					

			if(USE_TRAILING_SLASH) {
				$_to_controller = $_to_controller . '/';
			}
		
		// method
		$_add_param = NULL;		
		if(isset($_action) && $_action != '') {
				if(USE_TRAILING_SLASH) {
					$_to_action = $_action;					
					$_to_action = $_to_action . '/' . $this->_record_id . '/';
				} else {
					$_to_action = '/' . $_action . '/' . $this->_record_id;		
				}

				// additional parameters; _get
				if(isset($_additional_parameters)) {
					$_add_param = '?';
					foreach($_additional_parameters as $_k => $_v) {
						$_add_param = $_add_param . $_k . '=' . $_v . '&';
					}		
					// remove trailing "&"
					$_add_param[strlen($_add_param) - 1] = '';
					$_add_param = trim($_add_param);
				}
			
		} else {
			$_to_action = NULL;			
		}
		
			if(isset($_title)) {
				$_title_to_use = $_title;
			} else {
				$_title_to_use = $this->_value;
			}
		
		$_hyperlink = '<a href="' . '/' . $_to_controller . $_to_action . $_add_param . '">' . $_title_to_use . '</a>';

		print $_hyperlink; // let it boggie!
				
	}

} // end class

// -EOF-