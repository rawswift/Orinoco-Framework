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

// define framework directories
define('ROOT_DIR', '../../'); // set framework root dir

// libraries and classes
define('LIBRARY_DIR', ROOT_DIR . 'core/'); // core libraries directory
define('CLASSES', LIBRARY_DIR . 'classes/'); // classes directory

define('CLASS_FRAMEWORK_DIR', CLASSES . 'framework/'); // core classes
define('CLASS_APPLICATION_DIR', CLASSES . 'application/'); // app classes

// define application directories
define('APPLICATION_DIR', ROOT_DIR . 'app/'); // application directory
define('CONTROLLER_DIR', 'controllers/'); // controllers
define('MODEL_DIR', 'models/'); // models
define('VIEW_DIR', 'views/'); // views

// configuration directories
define('CONFIG_DIR', APPLICATION_DIR . 'config/'); // developer's configuration
define('CORE_CONFIG_DIR', LIBRARY_DIR . 'config/'); // core configuration	

// template directories
define('CONTENT_DIR', VIEW_DIR . 'contents/'); // contents templates
define('LAYOUT_DIR', VIEW_DIR . 'layouts/'); // layouts templates
define('PARTIAL_DIR', VIEW_DIR . 'partials/'); // partial templates

define('DEFAULT_LAYOUT', 'default'); // default layout	

// object-relational mapping
define('DATABASE_DIR', CLASSES . 'database/'); // database/ORM directory
define('INTERFACE_DIR', DATABASE_DIR . 'adapter/'); // class interface directory
define('ADAPTER_DIR', DATABASE_DIR . 'adapter/'); // database adapter directory

// extensions and vendors directories
define('LIBS_DIR', APPLICATION_DIR . 'libs/'); // libs classes
define('EXTENSION_DIR', APPLICATION_DIR . 'extensions/'); // extension classes
define('VENDOR_DIR', APPLICATION_DIR . 'vendors/'); // 3rd party classes/scripts

// -EOF-
