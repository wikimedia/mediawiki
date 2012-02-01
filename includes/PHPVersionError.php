<?php
/**
 * Display something vaguely comprehensible in the event of a totally unrecoverable error.
 * Does not assume access to *anything*; no globals, no autloader, no database, no localisation.
 * Safe for PHP4 (and putting this here means that WebStart.php and GlobalSettings.php
 * no longer need to be).
 *
 * Calling this function kills execution immediately.
 *
 * @param $type String Which entry point we are protecting. One of:
 *   - index.php
 *   - load.php
 *   - api.php
 *   - cli
 *
 * @note Since we can't rely on anything, the minimum PHP versions and MW current
 * version are hardcoded here
 */
function wfPHPVersionError( $type ){
	$mwVersion = '1.19';
	$phpVersion = PHP_VERSION;
	$message = "MediaWiki $mwVersion requires at least PHP version 5.2.3, you are using PHP $phpVersion.";
	if( $type == 'index.php' ) {
		$encLogo = htmlspecialchars(
			str_replace( '//', '/', pathinfo( $_SERVER['SCRIPT_NAME'], PATHINFO_DIRNAME ) . '/'
			) . 'skins/common/images/mediawiki.png'
		);

		header( $_SERVER['SERVER_PROTOCOL'] . ' 500 MediaWiki configuration Error', true, 500 );
		header( 'Content-type: text/html; charset=UTF-8' );
		// Don't cache error pages!  They cause no end of trouble...
		header( 'Cache-control: none' );
		header( 'Pragma: nocache' );

		$finalOutput = <<<HTML
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns='http://www.w3.org/1999/xhtml' lang='en'>
	<head>
		<title>MediaWiki {$mwVersion}</title>
		<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
		<style type='text/css' media='screen'>
			body {
				color: #000;
				background-color: #fff;
				font-family: sans-serif;
				padding: 2em;
				text-align: center;
			}
			p, img, h1 {
				text-align: left;
				margin: 0.5em 0;
			}
			h1 {
				font-size: 120%;
			}
		</style>
	</head>
	<body>
		<img src="{$encLogo}" alt='The MediaWiki logo' />
		<h1>MediaWiki {$mwVersion} internal error</h1>
		<div class='error'>
		<p>
			{$message}
		</p>
		<p>
			Please consider <a href="http://www.php.net/downloads.php">upgrading your copy of PHP</a>.
			PHP versions less than 5.3.0 are no longer supported by the PHP Group and will not receive
			security or bugfix updates.
		</p>
		<p>
			If for some reason you are unable to upgrade your PHP version, you will need to
			<a href="http://www.mediawiki.org/wiki/Download">download</a> an older version
			of MediaWiki from our website.  See our
			<a href="http://www.mediawiki.org/wiki/Compatibility#PHP">compatibility page</a>
			for details of which versions are compatible with prior versions of PHP.
		</p>
		</div>
	</body>
</html>
HTML;
	// Handle everything that's not index.php
	} else {
		// So nothing thinks this is JS or CSS
		$finalOutput = ( $type == 'load.php' ) ? "/* $message */" : $message;
		if( $type != 'cli' ) {
			header( $_SERVER['SERVER_PROTOCOL'] . ' 500 MediaWiki configuration Error', true, 500 );
		}
	}
	echo( "$finalOutput\n" );
	die( 1 );
}
