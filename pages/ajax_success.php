<?php
/**
 * jQuery call to echo tracked events and actions
 *
 */

use ColdTrick\Analytics\TrackingService;

$tracker = TrackingService::instance();

echo analytics_google_get_tracked_actions();
echo $tracker->getEvents();
