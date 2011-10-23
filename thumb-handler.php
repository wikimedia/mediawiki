<?php

# Valid web server entry point
define( 'THUMB_HANDLER', true );

# Load thumb-handler configuration. We don't want to use
# WebStart.php or the like as it would kill performance.
$configPath = dirname( __FILE__ ) . "/thumb.config.php";
if ( !file_exists( $configPath ) ) {
	die( "Thumb-handler.php is not enabled for this wiki.\n" );
}
require( $configPath );

function wfHandleThumb404() {
	global $thgThumb404File;

	# lighttpd puts the original request in REQUEST_URI, while
	# sjs sets that to the 404 handler, and puts the original
	# request in REDIRECT_URL.
	if ( isset( $_SERVER['REDIRECT_URL'] ) ) {
		# The URL is un-encoded, so put it back how it was.
		$uri = str_replace( "%2F", "/", urlencode( $_SERVER['REDIRECT_URL'] ) );
	} else {
		$uri = $_SERVER['REQUEST_URI'];
	}

	# Extract thumb.php params from the URI.
	if ( function_exists( 'wfCustomExtractThumbParams' ) ) {
		$params = wfCustomExtractThumbParams( $uri ); // overridden by configuration
	} else {
		$params = wfExtractThumbParams( $uri ); // basic wiki URL param extracting
	}
	if ( $params === null ) { // not a valid thumb request
		header( 'X-Debug: no regex match' ); // useful for debugging
		require_once( $thgThumb404File ); // standard 404 message
		return;
	}

	# Do some basic checks on the filename...
	if ( preg_match( '/[\x80-\xff]/', $uri ) ) {
		header( 'HTTP/1.0 400 Bad request' );
		header( 'Content-Type: text/html' );
		echo "<html><head><title>Bad request</title></head><body>" . 
			"The URI contained bytes with the high bit set, this is not allowed." . 
			"</body></html>";
		return;
	} elseif ( strpos( $params['f'], '%20' ) !== false ) {
		header( 'HTTP/1.0 404 Not found' );
		header( 'Content-Type: text/html' );
		header( 'X-Debug: filename contains a space' ); // useful for debugging
		echo "<html><head><title>Not found</title></head><body>" . 
			"The URL contained spaces, we don't have any thumbnail files with spaces." . 
			"</body></html>";
		return;
	}

	wfStreamThumbViaCurl( $params, $uri );
}

/**
 * Extract the required params for thumb.php from the thumbnail request URI.
 * At least 'width' and 'f' should be set if the result is an array.
 *
 * @param $uri String Thumbnail request URI
 * @return Array|null associative params array or null
 */
function wfExtractThumbParams( $uri ) {
	global $thgThumbServer, $thgThumbFragment, $thgThumbHashFragment;

	$thumbRegex = '!^(?:' . preg_quote( $thgThumbServer ) . ')?/' .
		preg_quote( $thgThumbFragment ) . '(/archive|/temp|)/' .
		$thgThumbHashFragment . '([^/]*)/' . '(page(\d*)-)*(\d*)px-([^/]*)$!';

	# Is this a thumbnail?
	if ( preg_match( $thumbRegex, $uri, $matches ) ) {
		list( $all, $archOrTemp, $filename, $pagefull, $pagenum, $size, $fn2 ) = $matches;
		$params = array( 'f' => $filename, 'width' => $size );
		if ( $pagenum ) {
			$params['page'] = $pagenum;
		}
		if ( $archOrTemp == '/archive' ) {
			$params['archived'] = 1;
		} elseif ( $archOrTemp == '/temp' ) {
			$params['temp'] = 1;
		}
	} else {
		$params = null;
	}

	return $params;
}

/**
 * cURL to thumb.php and stream back the resulting file or give an error message.
 *
 * @param $params Array Parameters to thumb.php
 * @param $uri String Thumbnail request URI
 * @return void
 */
function wfStreamThumbViaCurl( array $params, $uri ) {
	global $thgThumbScriptPath, $thgThumbCurlProxy, $thgThumbCurlTimeout;

	if ( !function_exists( 'curl_init' ) ) {
		header( 'HTTP/1.0 404 Not found' );
		header( 'Content-Type: text/html' );
		header( 'X-Debug: cURL is not enabled' ); // useful for debugging
		echo "<html><head><title>Not found</title></head><body>" . 
			"cURL is not enabled for PHP on this wiki. Unable to send request thumb.php." . 
			"</body></html>";
		return;
	}

	# Build up the request URL to use with CURL...
	$reqURL = "{$thgThumbScriptPath}?";
	$first = true;
	foreach ( $params as $name => $value ) {
		if ( $first ) {
			$first = false;
		} else {
			$reqURL .= '&';
		}
		// Note: value is already urlencoded
		$reqURL .= "$name=$value";
	}

	$ch = curl_init( $reqURL );
	if ( $thgThumbCurlProxy ) {
		curl_setopt( $ch, CURLOPT_PROXY, $thgThumbCurlProxy );
	}

	$headers = array(); // HTTP headers
	# Set certain headers...
	$headers[] = "X-Original-URI: " . str_replace( "\n", '', $uri );
	if ( function_exists( 'wfCustomThumbRequestHeaders' ) ) {
		wfCustomThumbRequestHeaders( $headers ); // add on any custom headers (like XFF)
	}
	# Pass through some other headers...
	$passthrough = array( 'If-Modified-Since', 'Referer', 'User-Agent' );
	foreach ( $passthrough as $headerName ) {
		$serverVarName = 'HTTP_' . str_replace( '-', '_', strtoupper( $headerName ) );
		if ( !empty( $_SERVER[$serverVarName] ) ) {
			$headers[] = $headerName . ': ' . 
				str_replace( "\n", '', $_SERVER[$serverVarName] );
		}
	}

	curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch, CURLOPT_TIMEOUT, $thgThumbCurlTimeout );

	# Actually make the request
	$text = curl_exec( $ch );

	# Send it on to the client
	$errno = curl_errno( $ch );
	$contentType = curl_getinfo( $ch, CURLINFO_CONTENT_TYPE );
	$httpCode = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
	if ( $errno ) {
		header( 'HTTP/1.1 500 Internal server error' );
		header( 'Cache-Control: no-cache' );
		list( $text, $contentType ) = wfCurlErrorText( $ch );
	} elseif ( $httpCode == 304 ) {
		header( 'HTTP/1.1 304 Not modified' );
		$contentType = '';
		$text = '';
	} elseif ( strval( $text ) == '' ) {
		header( 'HTTP/1.1 500 Internal server error' );
		header( 'Cache-Control: no-cache' );
		list( $text, $contentType ) = wfCurlEmptyText( $ch );
	} elseif ( $httpCode == 404 ) {
		header( 'HTTP/1.1 404 Not found' );
		header( 'Cache-Control: s-maxage=300, must-revalidate, max-age=0' );
	} elseif ( $httpCode != 200
		|| substr( $contentType, 0, 9 ) == 'text/html'
		|| substr( $text, 0, 5 ) == '<html' )
	{
		# Error message, suppress cache
		header( 'HTTP/1.1 500 Internal server error' );
		header( 'Cache-Control: no-cache' );
	}

	if ( !$contentType ) {
		header( 'Content-Type:' );
	} else {
		header( "Content-Type: $contentType" );
	}

	print $text; // thumb data or error text

	curl_close( $ch );
}

/**
 * Get error message and content type for when the cURL response is empty.
 *
 * @param $ch cURL handle
 * @return Array (error html, content type)
 */
function wfCurlErrorText( $ch ) {
	$contentType = 'text/html';
	$error = htmlspecialchars( curl_error( $ch ) );
	$text = <<<EOT
<html>
<head><title>Thumbnail error</title></head>
<body>Error retrieving thumbnail from scaling server: $error</body>
</html>
EOT;
	return array( $text, $contentType );
}

/**
 * Get error message and content type for when the cURL response is an error.
 *
 * @param $ch cURL handle
 * @return Array (error html, content type)
 */
function wfCurlEmptyText( $ch ) {
	$contentType = 'text/html';
	$error = htmlspecialchars( curl_error( $ch ) );
	$text = <<<EOT
<html>
<head><title>Thumbnail error</title></head>
<body>Error retrieving thumbnail from scaling server: empty response</body>
</html>
EOT;
	return array( $text, $contentType );
}

# Entry point
wfHandleThumb404();
