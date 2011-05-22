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
require ( dirname( __FILE__ ) . '/includes/WebStart.php' );

wfIndexMain();

function wfIndexMain() {
	global $wgRequest, $wgShowHostnames, $mediaWiki, $wgTitle, $wgUseAjax, $wgUseFileCache;

	wfProfileIn( 'index.php' );
	wfProfileIn( 'index.php-setup' );

	$maxLag = $wgRequest->getVal( 'maxlag' );
	if ( !is_null( $maxLag ) ) {
		$lb = wfGetLB(); // foo()->bar() is not supported in PHP4
		list( $host, $lag ) = $lb->getMaxLag();
		if ( $lag > $maxLag ) {
			header( 'HTTP/1.1 503 Service Unavailable' );
			header( 'Retry-After: ' . max( intval( $maxLag ), 5 ) );
			header( 'X-Database-Lag: ' . intval( $lag ) );
			header( 'Content-Type: text/plain' );
			if( $wgShowHostnames ) {
				echo "Waiting for $host: $lag seconds lagged\n";
			} else {
				echo "Waiting for a database server: $lag seconds lagged\n";
			}
			exit;
		}
	}

	# Initialize MediaWiki base class
	$context = RequestContext::getMain();
	$mediaWiki = new MediaWiki( $context );

	# Set title from request parameters
	$wgTitle = $mediaWiki->getTitle();
	$action = $wgRequest->getVal( 'action', 'view' );

	wfProfileOut( 'index.php-setup' );

	# Send Ajax requests to the Ajax dispatcher.
	if ( $wgUseAjax && $action == 'ajax' ) {
		$dispatcher = new AjaxDispatcher();
		$dispatcher->performAction();
		wfProfileOut( 'index.php' );
		$mediaWiki->restInPeace();
		exit;
	}

	if ( $wgUseFileCache && $wgTitle !== null ) {
		wfProfileIn( 'index.php-filecache' );
		// Raw pages should handle cache control on their own,
		// even when using file cache. This reduces hits from clients.
		if ( $action != 'raw' && HTMLFileCache::useFileCache() ) {
			/* Try low-level file cache hit */
			$cache = new HTMLFileCache( $wgTitle, $action );
			if ( $cache->isFileCacheGood( /* Assume up to date */ ) ) {
				/* Check incoming headers to see if client has this cached */
				if ( !$context->output->checkLastModified( $cache->fileCacheTime() ) ) {
					$cache->loadFromFileCache();
				}
				# Do any stats increment/watchlist stuff
				$article = MediaWiki::articleFromTitle( $wgTitle, $context );
				$article->viewUpdates();
				# Tell OutputPage that output is taken care of
				$context->output->disable();
				wfProfileOut( 'index.php-filecache' );
				$mediaWiki->finalCleanup();
				wfProfileOut( 'index.php' );
				$mediaWiki->restInPeace();
				exit;
			}
		}
		wfProfileOut( 'index.php-filecache' );
	}

	$mediaWiki->performRequestForTitle( $article );

	/**
	 * $wgArticle is deprecated, do not use it. This will possibly be removed
	 * entirely in 1.20 or 1.21
	 * @deprecated since 1.19
	 */
	global $wgArticle;
	$wgArticle = $article;

	$mediaWiki->finalCleanup();

	wfProfileOut( 'index.php' );

	$mediaWiki->restInPeace();
}

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
	$logo = isset( $wgLogo ) && $wgLogo
		? $wgLogo
		: 'http://upload.wikimedia.org/wikipedia/commons/1/1c/MediaWiki_logo.png';

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
		<img src="<?php echo $logo; ?>" alt='The MediaWiki logo' />
		<h1>MediaWiki <?php echo $version; ?> internal error</h1>
		<div class='error'> <?php echo $errorMsg; ?> </div>
	</body>
</html>
	<?php
	die( 1 );
}
