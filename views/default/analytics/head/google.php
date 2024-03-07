<?php

// Google Analytics tracking
$trackID = elgg_get_plugin_setting('analyticsSiteID', 'analytics');
if (empty($trackID)) {
	return;
}

$config = [];

if ((bool) elgg_get_plugin_setting('analyticsDebug', 'analytics')) {
	$config['debug_mode'] = true;
}

?>
<!-- Google Analytics -->

<!-- Google tag (gtag.js) -->
<script async="async" src="https://www.googletagmanager.com/gtag/js?id=<?= $trackID; ?>"></script>
<script>
	window.dataLayer = window.dataLayer || [];
	function gtag(){dataLayer.push(arguments);}
	gtag('js', new Date());
	
	gtag('config', '<?= $trackID; ?>', <?= json_encode($config); ?>);
</script>
<!-- End Google Analytics -->
