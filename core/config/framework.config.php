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

define('DOMAIN', 'http://' . $_SERVER['HTTP_HOST']);

// file suffixes
define('CONTROLLER_FILE_SUFFIX', '.controller'); // controller filename suffix
define('MODEL_FILE_SUFFIX', '.model'); // model filename suffix
define('VIEW_FILE_SUFFIX', '.view'); // view filename suffix
define('CLASS_FILE_SUFFIX', '.class'); // class file suffix
define('LAYOUT_FILE_SUFFIX', '.layout'); // layout file suffix
define('PARTIAL_FILE_SUFFIX', '.partial'); // partial file suffix

// database/ORM related
define('INTERFACE_FILE_SUFFIX', '.interface'); // class interface file suffix
define('ADAPTER_FILE_SUFFIX', '.adapter'); // adapter file suffix
define('EXTENSION_FILE_SUFFIX', '.class'); // extension file suffix

// file extensions
define('CONTROLLER_FILE_EXTENSION', '.php');
define('MODEL_FILE_EXTENSION', '.php');
define('VIEW_FILE_EXTENSION', '.php');
define('PARTIAL_FILE_EXTENSION', '.php');
define('CLASS_FILE_EXTENSION', '.php');
define('LAYOUT_FILE_EXTENSION', '.php');
define('EXTENSION_FILE_EXTENSION', '.php');

// database/ORM related
define('INTERFACE_FILE_EXTENSION', '.php');
define('ADAPTER_FILE_EXTENSION', '.php');

// -EOF-