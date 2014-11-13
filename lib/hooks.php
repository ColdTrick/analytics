<?php
/**
 * All plugin hook handlers are bundled here
 */

/**
 * Track Elgg actions
 *
 * @param string $hook        the name of the hook
 * @param string $type        the type of the hook
 * @param bool   $returnvalue current return value
 * @param array  $params      supplied params
 *
 * @return void
 */
function analytics_action_plugin_hook($hook, $type, $returnvalue, $params) {
	
	if (!analytics_google_track_actions_enabled()) {
		return $returnvalue;
	}
	
	register_shutdown_function("analytics_track_action", $type);
	
	return $returnvalue;
}
