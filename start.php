<?php
/**
* Google Analytics startup file.
*
* @package analytics
* @author ColdTrick IT Solutions
* @link http://www.coldtrick.com/
*/

require_once(dirname(__FILE__) . "/lib/events.php");
require_once(dirname(__FILE__) . "/lib/functions.php");
require_once(dirname(__FILE__) . "/lib/hooks.php");

// register default elgg event
elgg_register_event_handler("init", "system", "analytics_init");

/**
 * Gets called during system initialization
 *
 * @return void
 */
function analytics_init() {
	// load Google Analytics JS
	elgg_extend_view("page/elements/head", "analytics/metatags", 999);
	
	// extend the page footer
	elgg_extend_view("page/elements/foot", "analytics/footer", 999);
	
	// register page handler
	elgg_register_page_handler("analytics", "analytics_page_handler");
	
	// register tracking events
	elgg_register_event_handler("all", "object", "analytics_event_tracker");
	elgg_register_event_handler("all", "group", "analytics_event_tracker");
	elgg_register_event_handler("all", "user", "analytics_event_tracker");
	
	// register plugin hooks
	elgg_register_plugin_hook_handler("action", "all", "analytics_action_plugin_hook");
	
}

/**
 * Page handler for analytics
 *
 * @param array $page page elements
 *
 * @return bool
 */
function analytics_page_handler($page) {
	$result = false;
	
	switch ($page[0]) {
		case "ajax_success":
			$result = true;
			
			include(dirname(__FILE__) . "/pages/ajax_success.php");
			break;
	}
	
	return $result;
}
