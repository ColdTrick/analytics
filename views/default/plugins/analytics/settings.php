<?php
/**
* Analytics settings configuration.
*/

/* @var $plugin \ElggPlugin */
$plugin = elgg_extract('entity', $vars);

$domain = $plugin->analyticsDomain;

$site = elgg_get_site_entity();

$host = $site->getDomain();
$hostArray = explode('.', $host);
$host_count = count($hostArray);
$host = '';
for ($i = 1; $i < $host_count; $i++) {
	$host .= '.' . $hostArray[$i];
}

$sample = false;
if ($domain != $host) {
	$sample = true;
}

$noyes_options = [
	'no' => elgg_echo('option:no'),
	'yes' => elgg_echo('option:yes'),
];

// Google Analytics
$google = elgg_format_element('div', ['class' => 'mbs'], elgg_echo('analytics:settings:google:description'));

$google .= '<div class="mbs">';
$google .= elgg_echo('analytics:settings:trackid');
$google .= elgg_view('input/text', [
	'name' => 'params[analyticsSiteID]',
	'value' => $plugin->analyticsSiteID,
	'maxlength' => '15',
]);
$google .= '</div>';

$google .= '<div class="mbs">';
$google .= elgg_echo('analytics:settings:domain');
$google .= elgg_view('input/text', [
	'name' => 'params[analyticsDomain]',
	'value' => $domain,
	'id' => 'analyticsDomain',
]);

if ($sample) {
	$google .= elgg_format_element('div', ['class' => 'elgg-subtext'], elgg_echo('analytics:settings:domain:sample', [$host]));
}
$google .= '</div>';

$google .= '<div class="mbs">';
$google .= elgg_echo('analytics:settings:track_actions');
$google .= elgg_view('input/select', [
	'name' => 'params[trackActions]',
	'options_values' => $noyes_options,
	'value' => $plugin->trackActions,
	'class' => 'mls',
]);
$google .= '<br />';

$google .= elgg_echo('analytics:settings:track_events');
$google .= elgg_view('input/select', [
	'name' => 'params[trackEvents]',
	'options_values' => $noyes_options,
	'value' => $plugin->trackEvents,
	'class' => 'mls',
]);
$google .= elgg_format_element('div', [], elgg_echo('analytics:settings:track_events:warning'));
$google .= '</div>';

$google .= '<div class="mbs">';
$google .= elgg_echo('analytics:settings:flag_administrators');
$google .= elgg_view('input/select', [
	'name' => 'params[flagAdmins]',
	'options_values' => $noyes_options,
	'value' => $plugin->flagAdmins,
	'class' => 'mls',
]);
$google .= '</div>';

$google .= '<div class="mbs">';
$google .= elgg_echo('analytics:settings:anonymize_ip');
$google .= elgg_view('input/select', [
	'name' => 'params[anonymizeIp]',
	'options_values' => $noyes_options,
	'value' => $plugin->anonymizeIp,
	'class' => 'mls',
]);
$google .= '</div>';

echo elgg_view_module('inline', elgg_echo('analytics:settings:google'), $google);
