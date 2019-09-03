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
		
		$events->registerHandler('all', 'group', __NAMESPACE__ . '\Tracker::events');
		$events->registerHandler('all', 'object', __NAMESPACE__ . '\Tracker::events');
		$events->registerHandler('all', 'user', __NAMESPACE__ . '\Tracker::events');
	}
	
	/**
	 * Register plugin hook handlers
	 *
	 * @return void
	 */
	protected function registerHooks() {
		$hooks = $this->elgg()->hooks;
		
		$hooks->registerHandler('action', 'all', __NAMESPACE__ . '\Tracker::actions');
		$hooks->registerHandler('public_pages', 'walled_garden', __NAMESPACE__ . '\Site::publicPages');
	}
}
