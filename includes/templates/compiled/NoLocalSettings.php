<?php return function ($in, $debugopt = 1) {
    $cx = array(
        'flags' => array(
            'jstrue' => false,
            'jsobj' => false,
            'spvar' => false,
            'prop' => false,
            'method' => false,
            'mustlok' => false,
            'mustsec' => false,
            'echo' => true,
            'debug' => $debugopt,
        ),
        'constants' => array(),
        'helpers' => array(),
        'blockhelpers' => array(),
        'hbhelpers' => array(),
        'partials' => array(),
        'scopes' => array($in),
        'sp_vars' => array('root' => $in),

    );
    
    ob_start();echo '<!DOCTYPE html>
<html lang="en" dir="ltr">
	<head>
		<meta charset="UTF-8" />
		<title>MediaWiki ',htmlentities((string)((isset($in['wgVersion']) && is_array($in)) ? $in['wgVersion'] : null), ENT_QUOTES, 'UTF-8'),'</title>
		<style media="screen">
			html, body {
				color: #000;
				background-color: #fff;
				font-family: sans-serif;
				text-align: center;
			}

			h1 {
				font-size: 150%;
			}
		</style>
	</head>
	<body>
		<img src="',htmlentities((string)((isset($in['path']) && is_array($in)) ? $in['path'] : null), ENT_QUOTES, 'UTF-8'),'resources/assets/mediawiki.png" alt="The MediaWiki logo" />

		<h1>MediaWiki ',htmlentities((string)((isset($in['wgVersion']) && is_array($in)) ? $in['wgVersion'] : null), ENT_QUOTES, 'UTF-8'),'</h1>
		<div class="error">
		',LCRun3::sec($cx, ((isset($in['localSettingsExists']) && is_array($in)) ? $in['localSettingsExists'] : null), $in, false, function($cx, $in) {echo '
			<p>LocalSettings.php not readable.</p>
			<p>Please correct file permissions and try again.</p>
		';}),'
		';if (LCRun3::isec($cx, ((isset($in['localSettingsExists']) && is_array($in)) ? $in['localSettingsExists'] : null))){echo '
			<p>LocalSettings.php not found.</p>
			',LCRun3::sec($cx, ((isset($in['installerStarted']) && is_array($in)) ? $in['installerStarted'] : null), $in, false, function($cx, $in) {echo '
				<p>Please <a href="',htmlentities((string)((isset($in['path']) && is_array($in)) ? $in['path'] : null), ENT_QUOTES, 'UTF-8'),'mw-config/index',htmlentities((string)((isset($in['ext']) && is_array($in)) ? $in['ext'] : null), ENT_QUOTES, 'UTF-8'),'">complete the installation</a> and download LocalSettings.php.</p>
			';}),'
			';if (LCRun3::isec($cx, ((isset($in['installerStarted']) && is_array($in)) ? $in['installerStarted'] : null))){echo '
				<p>Please <a href="',htmlentities((string)((isset($in['path']) && is_array($in)) ? $in['path'] : null), ENT_QUOTES, 'UTF-8'),'mw-config/index',htmlentities((string)((isset($in['ext']) && is_array($in)) ? $in['ext'] : null), ENT_QUOTES, 'UTF-8'),'">set up the wiki</a> first.</p>
			';}else{echo '';}echo '
		';}else{echo '';}echo '
		</div>
	</body>
</html>';return ob_get_clean();
}
?>