<?php

use ColdTrick\Analytics\Bootstrap;

require_once(dirname(__FILE__) . '/lib/functions.php');

return [
	'bootstrap' => Bootstrap::class,
	'settings' => [
		'trackActions' => 'no',
		'trackEvents' => 'no',
		'flagAdmins' => 'no',
		'anonymizeIp' => 'no',
	],
];
