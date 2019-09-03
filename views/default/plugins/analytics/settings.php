<?php
/**
* Analytics settings configuration.
*/

/* @var $plugin \ElggPlugin */
$plugin = elgg_extract('entity', $vars);

$site = elgg_get_site_entity();

$host = $site->getDomain();
$hostArray = explode('.', $host);
$host_count = count($hostArray);
$host = '';
for ($i = 1; $i < $host_count; $i++) {
	$host .= '.' . $hostArray[$i];
}

$sample = false;
if ($plugin->analyticsDomain !== $host) {
	$sample = true;
}

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
	'#type' => 'text',
	'#label' => elgg_echo('analytics:settings:domain'),
	'#help' => $sample ? elgg_echo('analytics:settings:domain:sample', [$host]) : null,
	'name' => 'params[analyticsDomain]',
	'value' => $plugin->analyticsDomain,
]);

$google .= elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('analytics:settings:track_actions'),
	'name' => 'params[trackActions]',
	'default' => 'no',
	'value' => 'yes',
	'checked' => $plugin->trackActions === 'yes',
	'switch' => true,
]);

$google .= elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('analytics:settings:track_events'),
	'#help' => elgg_echo('analytics:settings:track_events:warning'),
	'name' => 'params[trackEvents]',
	'default' => 'no',
	'value' => 'yes',
	'checked' => $plugin->trackEvents === 'yes',
	'switch' => true,
]);

$google .= elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('analytics:settings:flag_administrators'),
	'name' => 'params[flagAdmins]',
	'default' => 'no',
	'value' => 'yes',
	'checked' => $plugin->flagAdmins === 'yes',
	'switch' => true,
]);

$google .= elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('analytics:settings:anonymize_ip'),
	'name' => 'params[anonymizeIp]',
	'default' => 'no',
	'value' => 'yes',
	'checked' => $plugin->anonymizeIp === 'yes',
	'switch' => true,
]);

echo elgg_view_module('info', elgg_echo('analytics:settings:google'), $google);
