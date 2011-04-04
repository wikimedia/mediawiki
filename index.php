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

# Initialise common code.  This gives us access to GlobalFunctions, the AutoLoader, and
# the globals $wgRequest, $wgOut, $wgUser, $wgLang and $wgContLang, amongst others; it
# does *not* load $wgTitle or $wgArticle
require ( dirname( __FILE__ ) . '/includes/WebStart.php' );

wfProfileIn( 'index.php' );
wfProfileIn( 'index.php-setup' );

$maxLag = $wgRequest->getVal( 'maxlag' );
if ( !is_null( $maxLag ) ) {
	list( $host, $lag ) = wfGetLB()->getMaxLag();
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
			$wgArticle = MediaWiki::articleFromTitle( $wgTitle );
			$wgArticle->viewUpdates();
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

# Setting global variables in mediaWiki
$mediaWiki->setVal( 'DisableHardRedirects', $wgDisableHardRedirects );
$mediaWiki->setVal( 'EnableCreativeCommonsRdf', $wgEnableCreativeCommonsRdf );
$mediaWiki->setVal( 'EnableDublinCoreRdf', $wgEnableDublinCoreRdf );
$mediaWiki->setVal( 'Server', $wgServer );
$mediaWiki->setVal( 'SquidMaxage', $wgSquidMaxage );
$mediaWiki->setVal( 'UseExternalEditor', $wgUseExternalEditor );
$mediaWiki->setVal( 'UsePathInfo', $wgUsePathInfo );

$mediaWiki->performRequestForTitle( $wgArticle );
$mediaWiki->finalCleanup();

wfProfileOut( 'index.php' );

$mediaWiki->restInPeace();
