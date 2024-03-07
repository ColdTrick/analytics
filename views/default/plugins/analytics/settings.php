<?php
/**
 * Analytics settings configuration.
 */

/* @var $plugin \ElggPlugin */
$plugin = elgg_extract('entity', $vars);

// Google Analytics
$google = elgg_view('output/longtext', [
	'value' => elgg_echo('analytics:settings:google:description'),
]);

$google .= elgg_view_field([
	'#type' => 'text',
	'#label' => elgg_echo('analytics:settings:trackid'),
	'name' => 'params[analyticsSiteID]',
	'value' => $plugin->analyticsSiteID,
	'maxlength' => '15',
]);

$google .= elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('analytics:settings:debug_mode'),
	'#help' => elgg_echo('analytics:settings:debug_mode:help'),
	'name' => 'params[analyticsDebug]',
	'default' => 0,
	'value' => 1,
	'checked' => (bool) $plugin->analyticsDebug,
	'switch' => true,
]);

echo elgg_view_module('info', elgg_echo('analytics:settings:google'), $google);
