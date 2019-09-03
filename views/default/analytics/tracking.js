define(function(require) {
	var $ = require('jquery');
	var elgg = require('elgg');
	var Ajax = require('elgg/Ajax');
	
	// Any Ajax operation can return analytics data
	elgg.register_hook_handler(Ajax.RESPONSE_DATA_HOOK, 'all', function (hook, type, params, value) {
		if (value.analytics) {
			var analytics = value.analytics;
			console.log(analytics);
			
			if (analytics.actions) {
				$.each(analytics.actions, function(action, success) {
					if (success) {
						ga('send', 'pageview', '/action/' + action + '/success');
					} else {
						ga('send', 'pageview', '/action/' + action + '/error');
					}
				});
			}
			
			if (analytics.events) {
				$.each(analytics.events, function (index, event) {
					var data = {
						eventCategory: event.category,
						eventAction: event.action
					};
					
					if (event.label) {
						data.eventLabel = event.label;
					}
					
					ga('send', 'event', data);
				});
			}
		}
	});
});
