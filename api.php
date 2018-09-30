<?php
/**
 * This file is the entry point for all API queries.
 *
 * It begins by constructing a new ApiMain using the parameter passed to it
 * as an argument in the URL ('?action='). It then invokes "execute()" on the
 * ApiMain object instance, which produces output in the format specified in
 * the URL.
 *
 * Copyright © 2006 Yuri Astrakhan <Firstname><Lastname>@gmail.com
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

use MediaWiki\Logger\LegacyLogger;

// So extensions (and other code) can check whether they're running in API mode
define( 'MW_API', true );

require __DIR__ . '/includes/WebStart.php';

$starttime = microtime( true );

// URL safety checks
if ( !$wgRequest->checkUrlExtension() ) {
	return;
}

// PATH_INFO can be used for stupid things. We don't support it for api.php at
// all, so error out if it's present.
if ( isset( $_SERVER['PATH_INFO'] ) && $_SERVER['PATH_INFO'] != '' ) {
	$correctUrl = wfAppendQuery( wfScript( 'api' ), $wgRequest->getQueryValues() );
	$correctUrl = wfExpandUrl( $correctUrl, PROTO_CANONICAL );
	header( "Location: $correctUrl", true, 301 );
	echo 'This endpoint does not support "path info", i.e. extra text between "api.php"'
		. 'and the "?". Remove any such text and try again.';
	die( 1 );
}

// Set a dummy $wgTitle, because $wgTitle == null breaks various things
// In a perfect world this wouldn't be necessary
$wgTitle = Title::makeTitle( NS_SPECIAL, 'Badtitle/dummy title for API calls set in api.php' );

// RequestContext will read from $wgTitle, but it will also whine about it.
// In a perfect world this wouldn't be necessary either.
RequestContext::getMain()->setTitle( $wgTitle );

try {
	/* Construct an ApiMain with the arguments passed via the URL. What we get back
	 * is some form of an ApiMain, possibly even one that produces an error message,
	 * but we don't care here, as that is handled by the constructor.
	 */
	$processor = new ApiMain( RequestContext::getMain(), true );

	// Last chance hook before executing the API
	Hooks::run( 'ApiBeforeMain', [ &$processor ] );
	if ( !$processor instanceof ApiMain ) {
		throw new MWException( 'ApiBeforeMain hook set $processor to a non-ApiMain class' );
	}
} catch ( Exception $e ) { // @todo Remove this block when HHVM is no longer supported
	// Crap. Try to report the exception in API format to be friendly to clients.
	ApiMain::handleApiBeforeMainException( $e );
	$processor = false;
} catch ( Throwable $e ) {
	// Crap. Try to report the exception in API format to be friendly to clients.
	ApiMain::handleApiBeforeMainException( $e );
	$processor = false;
}

// Process data & print results
if ( $processor ) {
	$processor->execute();
}

// Log what the user did, for book-keeping purposes.
$endtime = microtime( true );

// Log the request
if ( $wgAPIRequestLog ) {
	$items = [
		wfTimestamp( TS_MW ),
		$endtime - $starttime,
		$wgRequest->getIP(),
		$wgRequest->getHeader( 'User-agent' )
	];
	$items[] = $wgRequest->wasPosted() ? 'POST' : 'GET';
	if ( $processor ) {
		try {
			$manager = $processor->getModuleManager();
			$module = $manager->getModule( $wgRequest->getVal( 'action' ), 'action' );
		} catch ( Exception $ex ) { // @todo Remove this block when HHVM is no longer supported
			$module = null;
		} catch ( Throwable $ex ) {
			$module = null;
		}
		if ( !$module || $module->mustBePosted() ) {
			$items[] = "action=" . $wgRequest->getVal( 'action' );
		} else {
			$items[] = wfArrayToCgi( $wgRequest->getValues() );
		}
	} else {
		$items[] = "failed in ApiBeforeMain";
	}
	LegacyLogger::emit( implode( ',', $items ) . "\n", $wgAPIRequestLog );
	wfDebug( "Logged API request to $wgAPIRequestLog\n" );
}

$mediawiki = new MediaWiki();
$mediawiki->doPostOutputShutdown( 'fast' );
