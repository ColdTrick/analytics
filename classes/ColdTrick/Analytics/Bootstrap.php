<?php

namespace ColdTrick\Analytics;

use Elgg\DefaultPluginBootstrap;

class Bootstrap extends DefaultPluginBootstrap {
	
	/**
	 * {@inheritDoc}
	 * @see \Elgg\DefaultPluginBootstrap::init()
	 */
	public function init() {
		
		// load Google Analytics JS
		elgg_extend_view('page/elements/head', 'analytics/head/google', 999);
		
		// extend the page footer
		elgg_extend_view('page/elements/foot', 'analytics/footer', 999);
		
		// register page handler
		elgg_register_page_handler('analytics', __NAMESPACE__ . '\PageHandler::analytics');
		
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
		
		$hooks->registerHandler('public_pages', 'walled_garden', __NAMESPACE__ . '\Site::publicPages');
		$hooks->registerHandler('response', 'all', [$tracker, 'trackAction']);
	}
}
