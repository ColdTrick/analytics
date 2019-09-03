<?php

namespace ColdTrick\Analytics;

use Elgg\Services\AjaxResponse;

/**
 * Ajax response handler
 */
class AjaxResponseHandler {
	
	/**
	 * Alter ajax response to send back analytics stats
	 *
	 * @param \Elgg\Hook $hook AjaxResponse::RESPONSE_HOOK, 'all'
	 *
	 * @return void|\Elgg\Services\AjaxResponse
	 */
	public function __invoke(\Elgg\Hook $hook) {
		
		$response = $hook->getValue();
		if (!$response instanceof AjaxResponse) {
			return;
		}
		
		$tracker = TrackingService::instance();
		if (!$tracker->actionTrackingEnabled() && !$tracker->eventTrackingEnabled()) {
			return;
		}
		
		$response->getData()->analytics = [
			'actions' => $tracker->getActions(),
			'events' => $tracker->getEvents(),
		];
		
		return $response;
	}
}
