<?php

# Valid web server entry point
define( 'THUMB_HANDLER', true );

# Load thumb-handler configuration. Avoids WebStart.php for performance.
if ( !file_exists( dirname( __FILE__ ) . "/thumb.config.php" ) ) {
	die( "thumb_handler.php is not enabled for this wiki.\n" );
}
require( dirname( __FILE__ ) . "/thumb.config.php" );

# Execute thumb.php if not handled via cURL
if ( wfHandleThumb404Main() === 'wfThumbMain' ) {
	require( dirname( __FILE__ ) . '/thumb.php' );
}

function wfHandleThumb404Main() {
	global $thgThumbCallbacks, $thgThumbCurlConfig;

	# lighttpd puts the original request in REQUEST_URI, while
	# sjs sets that to the 404 handler, and puts the original
	# request in REDIRECT_URL.
	if ( isset( $_SERVER['REDIRECT_URL'] ) ) {
		# The URL is un-encoded, so put it back how it was.
		$uri = str_replace( "%2F", "/", urlencode( $_SERVER['REDIRECT_URL'] ) );
	} else {
		$uri = $_SERVER['REQUEST_URI'];
	}

	# Extract thumb.php params from the URI...
	if ( isset( $thgThumbCallbacks['extractParams'] )
		&& is_callable( $thgThumbCallbacks['extractParams'] ) ) // overridden by configuration?
	{
		$params = call_user_func_array( $thgThumbCallbacks['extractParams'], array( $uri ) );
	} else {
		$params = wfExtractThumbParams( $uri ); // basic wiki URL param extracting
	}

	# Show 404 error if this is not a valid thumb request...
	if ( !is_array( $params ) ) {
		header( 'X-Debug: no regex match' ); // useful for debugging
		if ( isset( $thgThumbCallbacks['error404'] )
			&& is_callable( $thgThumbCallbacks['error404'] ) ) // overridden by configuration?
		{
			call_user_func( $thgThumbCallbacks['error404'] );
		} else {
			wfDisplay404Error(); // standard 404 message
		}
		return;
	}

	# Check any backend caches for the thumbnail...
	if ( isset( $thgThumbCallbacks['checkCache'] )
		&& is_callable( $thgThumbCallbacks['checkCache'] ) )
	{
		if ( call_user_func_array( $thgThumbCallbacks['checkCache'], array( $uri, $params ) ) ) {
			return; // file streamed from backend thumb cache
		}
	}

	# Obtain and stream the thumbnail or setup for wfThumbMain() call...
	if ( $thgThumbCurlConfig['enabled'] ) {
		wfStreamThumbViaCurl( $params, $uri );
		return true; // done
	} else {
		$_REQUEST = $params; // pass params to thumb.php
		return 'wfThumbMain';
	}
}

/**
 * Extract the required params for thumb.php from the thumbnail request URI.
 * At least 'width' and 'f' should be set if the result is an array.
 *
 * @param $uri String Thumbnail request URI
 * @return Array|null associative params array or null
 */
function wfExtractThumbParams( $uri ) {
	global $thgThumbUrlMatch;

	$thumbRegex = '!^(?:' . preg_quote( $thgThumbUrlMatch['server'] ) . ')?/' .
		preg_quote( $thgThumbUrlMatch['dirFragment'] ) . '(/archive|/temp|)/' .
		$thgThumbUrlMatch['hashFragment'] . '([^/]*)/(page(\d*)-)*(\d*)px-[^/]*$!';

	if ( preg_match( $thumbRegex, $uri, $matches ) ) {
		list( $all, $archOrTemp, $filename, $pagefull, $pagenum, $size ) = $matches;
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
		$params = null; // not a valid thumbnail URL
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
	global $thgThumbCallbacks, $thgThumbCurlConfig;

	if ( !extension_loaded( 'curl' ) ) {
		die( "cURL is not enabled for PHP on this wiki.\n" ); // sanity
	}

	# Build up the request URL to use with CURL...
	$reqURL = $thgThumbCurlConfig['url'] . '?';
	$first = true;
	foreach ( $params as $name => $value ) {
		if ( $first ) {
			$first = false;
		} else {
			$reqURL .= '&';
		}
		$reqURL .= "$name=$value"; // Note: value is already urlencoded
	}

	# Set relevant HTTP headers...
	$headers = array();
	$headers[] = "X-Original-URI: " . str_replace( "\n", '', $uri );
	if ( isset( $thgThumbCallbacks['curlHeaders'] )
		&& is_callable( $thgThumbCallbacks['curlHeaders'] ) )
	{
		# Add on any custom headers (like XFF)
		call_user_func_array( $thgThumbCallbacks['curlHeaders'], array( &$headers ) );
	}

	# Pass through some other headers...
	$passThrough = array( 'If-Modified-Since', 'Referer', 'User-Agent' );
	foreach ( $passThrough as $headerName ) {
		$serverVarName = 'HTTP_' . str_replace( '-', '_', strtoupper( $headerName ) );
		if ( !empty( $_SERVER[$serverVarName] ) ) {
			$headers[] = $headerName . ': ' . 
				str_replace( "\n", '', $_SERVER[$serverVarName] );
		}
	}

	$ch = curl_init( $reqURL );
	if ( $thgThumbCurlConfig['proxy'] ) {
		curl_setopt( $ch, CURLOPT_PROXY, $thgThumbCurlConfig['proxy'] );
	}

	curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch, CURLOPT_TIMEOUT, $thgThumbCurlConfig['timeout'] );

	# Actually make the request
	$text = curl_exec( $ch );

	# Send it on to the client...
	$errno = curl_errno( $ch );
	$contentType = curl_getinfo( $ch, CURLINFO_CONTENT_TYPE );
	$httpCode = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
	if ( $errno ) {
		header( 'HTTP/1.1 500 Internal server error' );
		header( 'Cache-Control: no-cache' );
		$contentType = 'text/html';
		$text = wfCurlErrorText( $ch );
	} elseif ( $httpCode == 304 ) { // OK
		header( 'HTTP/1.1 304 Not modified' );
		$contentType = '';
		$text = '';
	} elseif ( strval( $text ) == '' ) {
		header( 'HTTP/1.1 500 Internal server error' );
		header( 'Cache-Control: no-cache' );
		$contentType = 'text/html';
		$text = wfCurlEmptyText( $ch );
	} elseif ( $httpCode == 404 ) {
		header( 'HTTP/1.1 404 Not found' );
		header( 'Cache-Control: s-maxage=300, must-revalidate, max-age=0' );
	} elseif ( $httpCode != 200 || substr( $contentType, 0, 9 ) == 'text/html' ) {
		# Error message, suppress cache
		header( 'HTTP/1.1 500 Internal server error' );
		header( 'Cache-Control: no-cache' );
	} else {
		# OK thumbnail; save to any backend caches...
		if ( isset( $thgThumbCallbacks['fillCache'] )
			&& is_callable( $thgThumbCallbacks['fillCache'] ) )
		{
			call_user_func_array( $thgThumbCallbacks['fillCache'], array( $uri, $text ) );
		}
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
 * Get error message HTML for when the cURL response is an error.
 *
 * @param $ch cURL handle
 * @return string
 */
function wfCurlErrorText( $ch ) {
	$error = htmlspecialchars( curl_error( $ch ) );
	return <<<EOT
<html>
<head><title>Thumbnail error</title></head>
<body>Error retrieving thumbnail from scaling server: $error</body>
</html>
EOT;
}

/**
 * Get error message HTML for when the cURL response is empty.
 *
 * @param $ch cURL handle
 * @return string
 */
function wfCurlEmptyText( $ch ) {
	return <<<EOT
<html>
<head><title>Thumbnail error</title></head>
<body>Error retrieving thumbnail from scaling server: empty response</body>
</html>
EOT;
}

/**
 * Print out a generic 404 error message.
 *
 * @return void
 */
function wfDisplay404Error() {
	header( 'HTTP/1.1 404 Not Found' );
	header( 'Content-Type: text/html;charset=utf-8' );

	$prot = isset( $_SERVER['HTTPS'] ) ? "https://" : "http://";
	$serv = strlen( $_SERVER['HTTP_HOST'] ) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'];
	$loc = $_SERVER["REQUEST_URI"];

	$encUrl = htmlspecialchars( $prot . $serv . $loc );

	// Looks like a typical apache2 error
	$standard_404 = <<<ENDTEXT
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<html><head>
<title>404 Not Found</title>
</head><body>
<h1>Not Found</h1>
<p>The requested URL $encUrl was not found on this server.</p>
</body></html>
ENDTEXT;

	print $standard_404;
}
