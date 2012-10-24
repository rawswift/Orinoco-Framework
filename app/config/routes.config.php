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

/*
 * Sample routes
 */

// index/root domain route 
//Router::add("/", array("controller" => "index", "action" => "index"));

// controller and action/methods routes
//Router::add("/foo", array("controller" => "foo", "action" => "index"));
//Router::add("/foo/bar", array("controller" => "foo", "action" => "bar"));

// test ORM controller
//Router::add("/orm", array("controller" => "orm", "action" => "index"));

// regular expression and controller path
// Router::add("(^\/+[a-zA-Z0-9-\-]+\/test+$)", array("controller" => "test", "action" => "index", "class" => "/path/to/test.controller.php"));