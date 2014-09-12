<?php
/**
 * This file is the entry point for all API queries.
 *
 * It begins by checking whether the API is enabled on this wiki; if not,
 * it informs the user that s/he should set $wgEnableAPI to true and exits.
 * Otherwise, it constructs a new ApiMain using the parameter passed to it
 * as an argument in the URL ('?action=') and with write-enabled set to the
 * value of $wgEnableWriteAPI as specified in LocalSettings.php.
 * It then invokes "execute()" on the ApiMain object instance, which
 * produces output in the format specified in the URL.
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

// So extensions (and other code) can check whether they're running in API mode
define( 'MW_API', true );

// Bail if PHP is too low
if ( !function_exists( 'version_compare' ) || version_compare( phpversion(), '5.3.2' ) < 0 ) {
	// We need to use dirname( __FILE__ ) here cause __DIR__ is PHP5.3+
	require dirname( __FILE__ ) . '/includes/PHPVersionError.php';
	wfPHPVersionError( 'api.php' );
}

require __DIR__ . '/includes/WebStart.php';

wfProfileIn( 'api.php' );
$starttime = microtime( true );

// URL safety checks
if ( !$wgRequest->checkUrlExtension() ) {
	return;
}

// Verify that the API has not been disabled
if ( !$wgEnableAPI ) {
	header( $_SERVER['SERVER_PROTOCOL'] . ' 500 MediaWiki configuration Error', true, 500 );
	echo 'MediaWiki API is not enabled for this site. Add the following line to your LocalSettings.php'
		. '<pre><b>$wgEnableAPI=true;</b></pre>';
	die( 1 );
}

// Set a dummy $wgTitle, because $wgTitle == null breaks various things
// In a perfect world this wouldn't be necessary
$wgTitle = Title::makeTitle( NS_MAIN, 'API' );

/* Construct an ApiMain with the arguments passed via the URL. What we get back
 * is some form of an ApiMain, possibly even one that produces an error message,
 * but we don't care here, as that is handled by the ctor.
 */
$processor = new ApiMain( RequestContext::getMain(), $wgEnableWriteAPI );

// Last chance hook before executing the API
try {
	wfRunHooks( 'ApiBeforeMain', array( &$processor ) );
	if ( !$processor instanceof ApiMain ) {
		throw new MWException( 'ApiBeforMain hook set $processor to a non-ApiMain class' );
	}
} catch ( Exception $e ) {
	// Crap. Try to report the exception in API format to be friendly to clients.
	ApiMain::handleApiBeforeMainException( $e );
	$processor = false;
}

// Process data & print results
if ( $processor ) {
	$processor->execute();
}

if ( function_exists( 'fastcgi_finish_request' ) ) {
	fastcgi_finish_request();
}

// Execute any deferred updates
DeferredUpdates::doUpdates();

// Log what the user did, for book-keeping purposes.
$endtime = microtime( true );
wfProfileOut( 'api.php' );

wfLogProfilingData();

// Log the request
if ( $wgAPIRequestLog ) {
	$items = array(
		wfTimestamp( TS_MW ),
		$endtime - $starttime,
		$wgRequest->getIP(),
		$_SERVER['HTTP_USER_AGENT']
	);
	$items[] = $wgRequest->wasPosted() ? 'POST' : 'GET';
	if ( $processor ) {
		$module = $processor->getModule();
		if ( $module->mustBePosted() ) {
			$items[] = "action=" . $wgRequest->getVal( 'action' );
		} else {
			$items[] = wfArrayToCgi( $wgRequest->getValues() );
		}
	} else {
		$items[] = "failed in ApiBeforeMain";
	}
	wfErrorLog( implode( ',', $items ) . "\n", $wgAPIRequestLog );
	wfDebug( "Logged API request to $wgAPIRequestLog\n" );
}

// Shut down the database.  foo()->bar() syntax is not supported in PHP4: we won't ever actually
// get here to worry about whether this should be = or =&, but the file has to parse properly.
$lb = wfGetLBFactory();
$lb->shutdown();
