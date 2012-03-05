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
 * Copyright (C) 2001-2010 Magnus Manske, Brion Vibber, Lee Daniel Crocker,
 * Tim Starling, Erik Möller, Gabriel Wicke, Ævar Arnfjörð Bjarmason,
 * Niklas Laxström, Domas Mituzas, Rob Church, Yuri Astrakhan, Aryeh Gregor,
 * Aaron Schulz and others.
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
OutputPage::setEncodings(); # Not really used yet

$maxLag = $wgRequest->getVal( 'maxlag' );
if( !is_null( $maxLag ) && !$mediaWiki->checkMaxLag( $maxLag ) ) {
	exit;
}

# Query string fields
$action = $wgRequest->getVal( 'action', 'view' );
$title = $wgRequest->getVal( 'title' );

# Set title from request parameters
$wgTitle = $mediaWiki->checkInitialQueries( $title, $action );
if( $wgTitle === null ) {
	unset( $wgTitle );
}

wfProfileOut( 'main-misc-setup' );

#
# Send Ajax requests to the Ajax dispatcher.
#
if( $wgUseAjax && $action == 'ajax' ) {
	require_once( $IP . '/includes/AjaxDispatcher.php' );
	$dispatcher = new AjaxDispatcher();
	$dispatcher->performAction();
	$mediaWiki->restInPeace();
	exit;
}

if( $wgUseFileCache && isset( $wgTitle ) ) {
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
			wfProfileOut( 'main-try-filecache' );
			$mediaWiki->restInPeace();
			exit;
		}
	}
	wfProfileOut( 'main-try-filecache' );
}

# Setting global variables in mediaWiki
$mediaWiki->setVal( 'action', $action );
$mediaWiki->setVal( 'CommandLineMode', $wgCommandLineMode );
$mediaWiki->setVal( 'DisabledActions', $wgDisabledActions );
$mediaWiki->setVal( 'DisableHardRedirects', $wgDisableHardRedirects );
$mediaWiki->setVal( 'DisableInternalSearch', $wgDisableInternalSearch );
$mediaWiki->setVal( 'EnableCreativeCommonsRdf', $wgEnableCreativeCommonsRdf );
$mediaWiki->setVal( 'EnableDublinCoreRdf', $wgEnableDublinCoreRdf );
$mediaWiki->setVal( 'JobRunRate', $wgJobRunRate );
$mediaWiki->setVal( 'Server', $wgServer );
$mediaWiki->setVal( 'SquidMaxage', $wgSquidMaxage );
$mediaWiki->setVal( 'UseExternalEditor', $wgUseExternalEditor );
$mediaWiki->setVal( 'UsePathInfo', $wgUsePathInfo );

$mediaWiki->performRequestForTitle( $wgTitle, $wgArticle, $wgOut, $wgUser, $wgRequest );
$mediaWiki->finalCleanup( $wgDeferredUpdateList, $wgOut );

# Not sure when $wgPostCommitUpdateList gets set, so I keep this separate from finalCleanup
$mediaWiki->doUpdates( $wgPostCommitUpdateList );

$mediaWiki->restInPeace();

