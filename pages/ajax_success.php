<?php
/**
 * jQuery call to echo tracked events and actions
 *
 */

use ColdTrick\Analytics\TrackingService;

$tracker = TrackingService::instance();

echo $tracker->getActions();
echo $tracker->getEvents();
