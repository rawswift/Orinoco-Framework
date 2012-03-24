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

/*
 * --------------------------------------------------------------
 * this configuration file contains the default route rule
 * -------------------------------------------------------------- 
 */

/**
 * route rules
 * 
 * how to construct a route rule:
 *
 *		'(^\/[REG_EXPRESSION][+$])'
 *							=>		array(
 *											'controller' => '[CONTROLLER_NAME] or [DEFAULT]',
 *											'action' => '[ACTION_NAME] or [DEFAULT]',
 *											'id' => [INT [offset to where ID is]],
 *										),
 */

$_default_routes = array(

	/**
	 * [/year/month/day/blog-style-url/] or [/year/month/day/blog-style-url]
	 * 
	 * NOTE: change the 'your_blog_controller_here' to your actual controller that will handle this URI request
	 */
		'(^\/+[0-9]+\/+[0-9]+\/+[0-9]+\/+[a-zA-Z0-9-\--\;-\+]+\/|\/+[0-9]+\/+[0-9]+\/+[0-9]+\/+[a-zA-Z0-9-\--\;-\+]+$)' 
				=>		array(
								'controller' => 'your_blog_controller_here', // blog controller here
								'action' => 'DEFAULT',
							),

	/**
	 * [/controller/action/id/] or [/controller/action/id] 
	 */
		'(^\/+[a-zA-Z-\-]+\/+[a-zA-Z-\-]+\/+[0-9]+\/|\/+[a-zA-Z-\-]+\/+[a-zA-Z-\-]+\/+[0-9]+$)'
				=>		array(
								'controller' => 'CURRENT',
								'action' => 'CURRENT',
								'id' => 2,
							),
						
	/**
	 * [/controller/action/] or [/controller/action]
	 */
		'(^\/+[a-zA-Z-\-]+\/+[a-zA-Z-\-]+\/|\/+[a-zA-Z-\-]+\/+[a-zA-Z-\-]+$)' 
				=>		array(
								'controller' => 'CURRENT',
								'action' => 'CURRENT',
							),
	
	/**
	 * [/controller/id/] or [/controller/id]
	 */
		'(^\/+[a-zA-Z-\-]+\/+[0-9]+\/|\/+[a-zA-Z-\-]+\/+[0-9]+$)' 
				=>		array(
								'controller' => 'CURRENT',
								'action' => 'DEFAULT',
								'id' => 1,
							),
	
	/**
	 * [/controller/] or [/controller]
	 */					
		'(^\/+[a-zA-Z-\-]+\/|\/+[a-zA-Z-\-]+$)' 
				=>		array(
								'controller' => 'CURRENT',
								'action' => 'DEFAULT',
							),
	
	/**
	 * index/root (e.g. http://www.domain.tld/)
	 */
		'(^\/$)' 
				=>		array(
								'controller' => 'DEFAULT',
								'action' => 'DEFAULT',
							)
	
	); // end array config

// -EOF-