<?php
/**
 * All helper functions are bundled here
 */

/**
 * Track Elgg actions if the setting allows this
 *
 * @param string $action the name of the action
 *
 * @return void
 */
function analytics_track_action($action) {
	$action_result = true;
	
	if (!analytics_google_track_actions_enabled()) {
		// tracking is not enabled
		return;
	}
	
	$params = [
		'action' => $action,
	];
	if (!elgg_trigger_plugin_hook('track_action', 'analytics', $params, true)) {
		// don't track this action
		return;
	}
	
	// if an error occured log the action as failed
	if (count_messages('error') > 0) {
		$action_result = false;
	}
	
	if (!isset($_SESSION['analytics'])) {
		$_SESSION['analytics'] = [];
	}
	
	if (!isset($_SESSION['analytics']['actions'])) {
		$_SESSION['analytics']['actions'] = [];
	}
	
	$_SESSION['analytics']['actions'][$action] = $action_result;
}

/**
 * Check is tracking Actions is enabled for Google Analytics
 *
 * @return bool
 */
function analytics_google_track_actions_enabled() {
	static $cache;
	
	if (!isset($cache)) {
		$cache = false;
		
		$setting = elgg_get_plugin_setting('trackActions', 'analytics');
		if ($setting === 'yes') {
			$cache = true;
		}
	}
	
	return $cache;
}

/**
 * Get all the tracked Actions in a Google Analytics format
 *
 * @return string
 */
function analytics_google_get_tracked_actions() {
	$output = '';
	
	if (!analytics_google_track_actions_enabled()) {
		return $output;
	}
	
	if (empty($_SESSION['analytics']['actions'])) {
		return $output;
	}
	
	foreach ($_SESSION['analytics']['actions'] as $action => $result) {
		if ($result) {
			$output .= "ga('send', 'pageview', '/action/{$action}/succes');";
		} else {
			$output .= "ga('send', 'pageview', '/action/{$action}/error');";
		}
	}
	
	$_SESSION['analytics']['actions'] = [];
	
	return $output;
}
