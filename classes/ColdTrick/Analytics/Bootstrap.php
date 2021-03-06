<?php

namespace ColdTrick\Analytics;

use Elgg\DefaultPluginBootstrap;
use Elgg\Services\AjaxResponse;

class Bootstrap extends DefaultPluginBootstrap {
	
	/**
	 * {@inheritDoc}
	 * @see \Elgg\DefaultPluginBootstrap::init()
	 */
	public function init() {
		// load Google Analytics JS
		elgg_extend_view('page/elements/head', 'analytics/head/google', 999);
		
		$tracker = TrackingService::instance();
		if (!empty($this->plugin()->getSetting('analyticsSiteID')) && $tracker->actionTrackingEnabled() || $tracker->eventTrackingEnabled()) {
			elgg_require_js('analytics/tracking');
		}
		
		$this->registerEvents();
		$this->registerHooks();
	}
	
	/**
	 * Register event handlers
	 *
	 * @return void
	 */
	protected function registerEvents() {
		$events = $this->elgg()->events;
		$tracker = TrackingService::instance();
		
		$events->registerHandler('all', 'group', [$tracker, 'trackEvent']);
		$events->registerHandler('all', 'object', [$tracker, 'trackEvent']);
		$events->registerHandler('all', 'user', [$tracker, 'trackEvent']);
	}
	
	/**
	 * Register plugin hook handlers
	 *
	 * @return void
	 */
	protected function registerHooks() {
		$hooks = $this->elgg()->hooks;
		$tracker = TrackingService::instance();
		
		$hooks->registerHandler(AjaxResponse::RESPONSE_HOOK, 'all', AjaxResponseHandler::class);
		$hooks->registerHandler('response', 'all', [$tracker, 'trackAction']);
	}
}
