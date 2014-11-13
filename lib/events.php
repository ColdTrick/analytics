<?php
/**
 * All event handlers are bundled here
 */

/**
 * Track certan events on ElggEntity's
 *
 * @param string     $event       the event
 * @param string     $object_type the type of the ElggEntity
 * @param ElggEntity $object      the entity for the event
 *
 * @return void
 */
function analytics_event_tracker($event, $object_type, $object) {
	
	if (empty($object) && !($object instanceof ElggEntity)) {
		return;
	}
	
	if (!analytics_google_track_events_enabled()) {
		return;
	}
	
	switch ($object->type) {
		case "object":
			analytics_track_event($object->getSubtype(), $event, $object->title);
			break;
		case "group":
			analytics_track_event($object->type, $event, $object->name);
			break;
		case "user":
			analytics_track_event($object->type, $event, $object->name);
			break;
	}
}
