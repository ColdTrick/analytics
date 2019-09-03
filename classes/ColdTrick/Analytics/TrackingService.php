<?php

namespace ColdTrick\Analytics;

use Elgg\Di\ServiceFacade;

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
			return;
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
