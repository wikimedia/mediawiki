<?php
/**
 * This is the main web entry point for MediaWiki.
 *
 * If you are reading this in your web browser, your server is probably
 * not configured correctly to run PHP applications!
 *
 * See the README, INSTALL, and UPGRADE files for basic setup instructions
 * and pointers to the online documentation.
 *
 * http://www.mediawiki.org/
 *
 * ----------
 *
 * Copyright (C) 2001-2011 Magnus Manske, Brion Vibber, Lee Daniel Crocker,
 * Tim Starling, Erik Möller, Gabriel Wicke, Ævar Arnfjörð Bjarmason,
 * Niklas Laxström, Domas Mituzas, Rob Church, Yuri Astrakhan, Aryeh Gregor,
 * Aaron Schulz, Andrew Garrett, Raimond Spekking, Alexandre Emsenhuber
 * Siebrand Mazeland, Chad Horohoe, Roan Kattouw and others.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

// Bail on old versions of PHP.  Pretty much every other file in the codebase
// has structures (try/catch, foo()->bar(), etc etc) which throw parse errors in PHP 4.
// Setup.php and ObjectCache.php have structures invalid in PHP 5.0 and 5.1, respectively.
if ( !function_exists( 'version_compare' ) || version_compare( phpversion(), '5.2.3' ) < 0 ) {
	$phpversion = htmlspecialchars( phpversion() );
	$errorMsg = <<<ENDL
		<p>
			MediaWiki requires PHP 5.2.3 or higher. You are running PHP $phpversion.
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
ENDL;
	wfDie( $errorMsg );
}

# Initialise common code.  This gives us access to GlobalFunctions, the AutoLoader, and
# the globals $wgRequest, $wgOut, $wgUser, $wgLang and $wgContLang, amongst others; it
# does *not* load $wgTitle
if ( isset( $_SERVER['MW_COMPILED'] ) ) {
	require ( 'phase3/includes/WebStart.php' );
} else {
	require ( dirname( __FILE__ ) . '/includes/WebStart.php' );
}

$mediaWiki = new MediaWiki();
$mediaWiki->run();

/**
 * Display something vaguely comprehensible in the event of a totally unrecoverable error.
 * Does not assume access to *anything*; no globals, no autloader, no database, no localisation.
 * Safe for PHP4 (and putting this here means that WebStart.php and GlobalSettings.php
 * no longer need to be).
 *
 * Calling this function kills execution immediately.
 *
 * @param $errorMsg String fully-escaped HTML
 */
function wfDie( $errorMsg ){
	// Use the version set in DefaultSettings if possible, but don't rely on it
	global $wgVersion, $wgLogo;
	$version = isset( $wgVersion ) && $wgVersion
		? htmlspecialchars( $wgVersion )
		: '';

	$script = $_SERVER['SCRIPT_NAME'];
	$path = pathinfo( $script, PATHINFO_DIRNAME ) . '/';
	$path = str_replace( '//', '/', $path );

	$logo = isset( $wgLogo ) && $wgLogo
		? $wgLogo
		: $path . 'skins/common/images/mediawiki.png';
	$encLogo = htmlspecialchars( $logo );

	header( $_SERVER['SERVER_PROTOCOL'] . ' 500 MediaWiki configuration Error', true, 500 );
	header( 'Content-type: text/html; charset=UTF-8' );
	// Don't cache error pages!  They cause no end of trouble...
	header( 'Cache-control: none' );
	header( 'Pragma: nocache' );

	?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns='http://www.w3.org/1999/xhtml' lang='en'>
	<head>
		<title>MediaWiki <?php echo $version; ?></title>
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
		<img src="<?php echo $encLogo; ?>" alt='The MediaWiki logo' />
		<h1>MediaWiki <?php echo $version; ?> internal error</h1>
		<div class='error'> <?php echo $errorMsg; ?> </div>
	</body>
</html>
	<?php
	die( 1 );
}
