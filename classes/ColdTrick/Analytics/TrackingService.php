<?php

namespace ColdTrick\Analytics;

use Elgg\Di\ServiceFacade;
use Elgg\Http\ResponseBuilder;
use Elgg\Http\ErrorResponse;

class TrackingService {
	
	use ServiceFacade;
	
	/**
	 * @var \ElggSession
	 */
	protected $session;
	
	/**
	 * @var \ElggPlugin
	 */
	protected $plugin;
	
	/**
	 * Constructor
	 *
	 * @param \ElggSession $session Elgg session interface
	 */
	public function __construct(\ElggSession $session) {
		$this->session = $session;
		$this->plugin = elgg_get_plugin_from_id('analytics');
	}
	
	/**
	 * Get the name of the service
	 *
	 * @return string
	 */
	public static function name() {
		return 'analytics.tracking';
	}
	
	/**
	 * Is event tracking enabled
	 *
	 * @return bool
	 */
	protected function eventTrackingEnabled() {
		return $this->plugin->getSetting('trackEvents') === 'yes';
	}
	
	/**
	 * Is action tracking enabled
	 *
	 * @return bool
	 */
	protected function actionTrackingEnabled() {
		return $this->plugin->getSetting('trackActions') === 'yes';
	}
	
	/**
	 * Track actions
	 *
	 * @param \Elgg\Hook $hook 'response', 'all'
	 *
	 * @return void
	 */
	public function trackAction(\Elgg\Hook $hook) {
		
		if (!$this->actionTrackingEnabled()) {
			return;
		}
		
		$path = $hook->getType();
		if (strpos($path, 'action:') !== 0) {
			// not an action
			return;
		}
		
		$response = $hook->getValue();
		if (!$response instanceof ResponseBuilder) {
			return;
		}
		
		$action = substr($path, 7);
		
		$params = [
			'action' => $action,
		];
		if (!elgg_trigger_plugin_hook('track_action', 'analytics', $params, true)) {
			// don't track this action
			return;
		}
		
		$success = true;
		if ($response instanceof ErrorResponse) {
			$success = false;
		}
		
		
		$analytics = $this->session->get('analytics', []);
		$analytics['actions'] = elgg_extract('actions', $analytics, []);
		
		$analytics['actions'][$action] = $success;
		
		$this->session->set('analytics', $analytics);
	}
	
	/**
	 * Track an Elgg event
	 *
	 * @param \Elgg\Event $event the event to track
	 *
	 * @return void
	 */
	public function trackEvent(\Elgg\Event $event) {
		
		if (!$this->eventTrackingEnabled()) {
			return;
		}
		
		$entity = $event->getObject();
		if (!$entity instanceof \ElggEntity) {
			return;
		}
		
		$params = [
			'category' => $entity->getSubtype(),
			'action' => $event->getName(),
			'label' => $entity->getDisplayName(),
		];
		if (!elgg_trigger_plugin_hook('track_event', 'analytics', $params, true)) {
			// don't track this event
			return;
		}
		
		$analytics = $this->session->get('analytics', []);
		$analytics['events'] = elgg_extract('events', $analytics, []);
		
		$t_event = [
			'category' => $params['category'],
			'action' => $params['action'],
		];
		
		if (!empty($params['label'])) {
			$t_event['label'] = $params['label'];
		}
		error_log(var_export($t_event, true));
		$analytics['events'][] = $t_event;
		
		$this->session->set('analytics', $analytics);
	}
	
	/**
	 * Get the tracked actions for use in Google Analytics
	 *
	 * @return string
	 */
	public function getActions() {
		if (!$this->eventTrackingEnabled()) {
			return '';
		}
		
		$analytics = $this->session->get('analytics', []);
		if (empty($analytics)) {
			return '';
		}
		
		$actions = elgg_extract('actions', $analytics, []);
		if (empty($actions)) {
			return '';
		}
		
		$output = '';
		foreach ($actions as $action => $result) {
			if ($result) {
				$output .= "ga('send', 'pageview', '/action/{$action}/succes');" . PHP_EOL;
			} else {
				$output .= "ga('send', 'pageview', '/action/{$action}/error');" . PHP_EOL;
			}
		}
		
		$analytics['actions'] = [];
		
		$this->session->set('analytics', $analytics);
		
		return $output;
	}
	
	/**
	 * Get the tracked events for use in Google Analytics
	 *
	 * @return string
	 */
	public function getEvents() {
		
		if (!$this->eventTrackingEnabled()) {
			return '';
		}
		
		$analytics = $this->session->get('analytics', []);
		if (empty($analytics)) {
			return '';
		}
		
		$events = elgg_extract('events', $analytics, []);
		if (empty($events)) {
			return '';
		}
		
		$output = '';
		foreach ($events as $event) {
			$event_data = [
				'eventCategory' => $event['category'],
				'eventAction' => $event['action'],
			];
			if (!empty($event['label'])) {
				$event_data['eventLabel'] = $event['label'];
			}
			
			$output .= "ga('send', 'event', " . json_encode($event_data) . ");" . PHP_EOL;
		}
		
		$analytics['events'] = [];
		
		$this->session->set('analytics', $analytics);
		
		return $output;
	}
}
