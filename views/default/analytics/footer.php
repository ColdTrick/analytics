<?php

// do we have the plugin configured correctly
if (elgg_get_plugin_setting("analyticsSiteID", "analytics")) {

	$trackActions = elgg_get_plugin_setting("trackActions", "analytics");
	$trackEvents = elgg_get_plugin_setting("trackEvents", "analytics");

	// do we track actions/events
	if ($trackActions == "yes" || $trackEvents == "yes") {
?>
<script type="text/javascript" id="analytics_ajax_result">

	$(document).ajaxSuccess(function(event, XMLHttpRequest, ajaxOptions) {
		
		elgg.get("analytics/ajax_success", {
			global: false,
			success: function(data) {
				if (data) {
					var temp = document.createElement("script");
					temp.setAttribute("type", "text/javascript");
					temp.innerHTML = data;
					
					$('#analytics_ajax_result').after(temp);
				}
			}
		});
	});
</script>
<?php
	}
}
