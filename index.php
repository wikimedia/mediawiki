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

# Initialise common code
$preIP = dirname( __FILE__ );
require_once( "$preIP/includes/WebStart.php" );

# Initialize MediaWiki base class
$mediaWiki = new MediaWiki();

wfProfileIn( 'main-misc-setup' );

$maxLag = $wgRequest->getVal( 'maxlag' );
if( !is_null( $maxLag ) && !$mediaWiki->checkMaxLag( $maxLag ) ) {
	exit;
}

# Set title from request parameters
$wgTitle = $mediaWiki->checkInitialQueries( $wgRequest );

wfProfileOut( 'main-misc-setup' );

$action = $wgRequest->getVal( 'action', 'view' );

# Send Ajax requests to the Ajax dispatcher.
if( $wgUseAjax && $action == 'ajax' ) {
	$dispatcher = new AjaxDispatcher();
	$dispatcher->performAction();
	$mediaWiki->restInPeace();
	exit;
}

if( $wgUseFileCache && $wgTitle !== null ) {
	wfProfileIn( 'main-try-filecache' );
	// Raw pages should handle cache control on their own,
	// even when using file cache. This reduces hits from clients.
	if( $action != 'raw' && HTMLFileCache::useFileCache() ) {
		/* Try low-level file cache hit */
		$cache = new HTMLFileCache( $wgTitle, $action );
		if( $cache->isFileCacheGood( /* Assume up to date */ ) ) {
			/* Check incoming headers to see if client has this cached */
			if( !$wgOut->checkLastModified( $cache->fileCacheTime() ) ) {
				$cache->loadFromFileCache();
			}
			# Do any stats increment/watchlist stuff
			$wgArticle = MediaWiki::articleFromTitle( $wgTitle );
			$wgArticle->viewUpdates();
			# Tell $wgOut that output is taken care of
			$wgOut->disable();
			wfProfileOut( 'main-try-filecache' );
			$mediaWiki->finalCleanup( $wgOut );
			$mediaWiki->restInPeace();
			exit;
		}
	}
	wfProfileOut( 'main-try-filecache' );
}

# Setting global variables in mediaWiki
$mediaWiki->setVal( 'DisableHardRedirects', $wgDisableHardRedirects );
$mediaWiki->setVal( 'EnableCreativeCommonsRdf', $wgEnableCreativeCommonsRdf );
$mediaWiki->setVal( 'EnableDublinCoreRdf', $wgEnableDublinCoreRdf );
$mediaWiki->setVal( 'Server', $wgServer );
$mediaWiki->setVal( 'SquidMaxage', $wgSquidMaxage );
$mediaWiki->setVal( 'UseExternalEditor', $wgUseExternalEditor );
$mediaWiki->setVal( 'UsePathInfo', $wgUsePathInfo );

$mediaWiki->performRequestForTitle( $wgTitle, $wgArticle, $wgOut, $wgUser, $wgRequest );
$mediaWiki->finalCleanup( $wgOut );
$mediaWiki->restInPeace();
