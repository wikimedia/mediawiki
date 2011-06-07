<?php

/**
 * API for MediaWiki 1.8+
 *
 * Copyright (C) 2006 Yuri Astrakhan <Firstname><Lastname>@gmail.com
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

/**
 * This file is the entry point for all API queries. It begins by checking
 * whether the API is enabled on this wiki; if not, it informs the user that
 * s/he should set $wgEnableAPI to true and exits. Otherwise, it constructs
 * a new ApiMain using the parameter passed to it as an argument in the URL
 * ('?action=') and with write-enabled set to the value of $wgEnableWriteAPI
 * as specified in LocalSettings.php. It then invokes "execute()" on the
 * ApiMain object instance, which produces output in the format sepecified
 * in the URL.
 */

// So extensions (and other code) can check whether they're running in API mode
define( 'MW_API', true );

// Initialise common code
require ( dirname( __FILE__ ) . '/includes/WebStart.php' );

wfProfileIn( 'api.php' );
$starttime = microtime( true );

// URL safety checks
if ( !$wgRequest->checkUrlExtension() ) {
	return;
}

// Verify that the API has not been disabled
if ( !$wgEnableAPI ) {
	echo 'MediaWiki API is not enabled for this site. Add the following line to your LocalSettings.php';
	echo '<pre><b>$wgEnableAPI=true;</b></pre>';
	die( 1 );
}

// Selectively allow cross-site AJAX

/*
 * Helper function to convert wildcard string into a regex
 * '*' => '.*?'
 * '?' => '.'
 * @ return string
 */
function convertWildcard( $search ) {
	$search = preg_quote( $search, '/' );
	$search = str_replace(
		array( '\*', '\?' ),
		array( '.*?', '.' ),
		$search
	);
	return "/$search/";
}

if ( $wgCrossSiteAJAXdomains && isset( $_SERVER['HTTP_ORIGIN'] ) ) {
	$exceptions = array_map( 'convertWildcard', $wgCrossSiteAJAXdomainExceptions );
	$regexes = array_map( 'convertWildcard', $wgCrossSiteAJAXdomains );
	foreach ( $regexes as $regex ) {
		if ( preg_match( $regex, $_SERVER['HTTP_ORIGIN'] ) ) {
			foreach ( $exceptions as $exc ) { // Check against exceptions
				if ( preg_match( $exc, $_SERVER['HTTP_ORIGIN'] ) ) {
					break 2;
				}
			}
			header( "Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}" );
			header( 'Access-Control-Allow-Credentials: true' );
			break;
		}
	}
}

// Set a dummy $wgTitle, because $wgTitle == null breaks various things
// In a perfect world this wouldn't be necessary
$wgTitle = Title::makeTitle( NS_MAIN, 'API' );

/* Construct an ApiMain with the arguments passed via the URL. What we get back
 * is some form of an ApiMain, possibly even one that produces an error message,
 * but we don't care here, as that is handled by the ctor.
 */
$processor = new ApiMain( $wgRequest, $wgEnableWriteAPI );

// Process data & print results
$processor->execute();

// Execute any deferred updates
wfDoUpdates();

// Log what the user did, for book-keeping purposes.
$endtime = microtime( true );
wfProfileOut( 'api.php' );
wfLogProfilingData();

// Log the request
if ( $wgAPIRequestLog ) {
	$items = array(
			wfTimestamp( TS_MW ),
			$endtime - $starttime,
			wfGetIP(),
			$_SERVER['HTTP_USER_AGENT']
	);
	$items[] = $wgRequest->wasPosted() ? 'POST' : 'GET';
	if ( $processor->getModule()->mustBePosted() ) {
		$items[] = "action=" . $wgRequest->getVal( 'action' );
	} else {
		$items[] = wfArrayToCGI( $wgRequest->getValues() );
	}
	wfErrorLog( implode( ',', $items ) . "\n", $wgAPIRequestLog );
	wfDebug( "Logged API request to $wgAPIRequestLog\n" );
}

// Shut down the database
wfGetLBFactory()->shutdown();

