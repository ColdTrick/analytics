<?php

return [
	'plugin' => [
		'version' => '4.0',
	],
	'settings' => [
		'analyticsDebug' => 0,
	],
	'view_extensions' => [
		'page/elements/head' => [
			'analytics/head/google' => ['priority' => 1],
		],
	],
];
