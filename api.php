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

use MediaWiki\Logger\LegacyLogger;

// So extensions (and other code) can check whether they're running in API mode
define( 'MW_API', true );

// Bail on old versions of PHP, or if composer has not been run yet to install
// dependencies. Using dirname( __FILE__ ) here because __DIR__ is PHP5.3+.
require_once dirname( __FILE__ ) . '/includes/PHPVersionCheck.php';
wfEntryPointCheck( 'api.php' );

require __DIR__ . '/includes/WebStart.php';

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
$wgTitle = Title::makeTitle( NS_SPECIAL, 'Badtitle/dummy title for API calls set in api.php' );

// RequestContext will read from $wgTitle, but it will also whine about it.
// In a perfect world this wouldn't be necessary either.
RequestContext::getMain()->setTitle( $wgTitle );

try {
	/* Construct an ApiMain with the arguments passed via the URL. What we get back
	 * is some form of an ApiMain, possibly even one that produces an error message,
	 * but we don't care here, as that is handled by the ctor.
	 */
	$processor = new ApiMain( RequestContext::getMain(), $wgEnableWriteAPI );

	// Last chance hook before executing the API
	wfRunHooks( 'ApiBeforeMain', array( &$processor ) );
	if ( !$processor instanceof ApiMain ) {
		throw new MWException( 'ApiBeforeMain hook set $processor to a non-ApiMain class' );
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

wfLogProfilingData();

// Log the request
if ( $wgAPIRequestLog ) {
	$items = array(
		wfTimestamp( TS_MW ),
		$endtime - $starttime,
		$wgRequest->getIP(),
		$wgRequest->getHeader( 'User-agent' )
	);
	$items[] = $wgRequest->wasPosted() ? 'POST' : 'GET';
	if ( $processor ) {
		try {
			$manager = $processor->getModuleManager();
			$module = $manager->getModule( $wgRequest->getVal( 'action' ), 'action' );
		} catch ( Exception $ex ) {
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

// Shut down the database.  foo()->bar() syntax is not supported in PHP4: we won't ever actually
// get here to worry about whether this should be = or =&, but the file has to parse properly.
$lb = wfGetLBFactory();
$lb->shutdown();
