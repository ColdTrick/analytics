<?php

use ColdTrick\Analytics\TrackingService;

return [
	TrackingService::name() => Di\object(TrackingService::class)
		->constructor(Di\get('session')),
];
