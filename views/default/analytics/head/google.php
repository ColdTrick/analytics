<?php

// Google Analytics tracking
$trackID = elgg_get_plugin_setting("analyticsSiteID", "analytics");
if (empty($trackID)) {
	return;
}

$domain = elgg_get_plugin_setting("analyticsDomain", "analytics");
$flagAdmins = elgg_get_plugin_setting("flagAdmins", "analytics");
$anonymizelp = elgg_get_plugin_setting("anonymizelp", "analytics");

?>
<!-- Google Analytics -->
<script type="text/javascript">
	
	var _gaq = _gaq || [];
	_gaq.push(['_setAccount', '<?php echo $trackID; ?>']);
	<?php if (!empty($domain)) { ?>
	_gaq.push(['_setDomainName', '<?php echo $domain; ?>']);
	<?php } ?>
	<?php if (elgg_is_admin_logged_in() && $flagAdmins == "yes") { ?>
	_gaq.push(['_setCustomVar', 1, 'role', 'admin', 1]);
	<?php } ?>
	<?php if ($anonymizelp == "yes") { ?>
	_gaq.push (['_gat._anonymizeIp']);
	<?php } ?>
	_gaq.push(['_trackPageview']);
	
	<?php
	echo analytics_google_get_tracked_actions();
	echo analytics_google_get_tracked_events();
	?>
	
	(function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	})();

</script>
<!-- End Google Analytics -->