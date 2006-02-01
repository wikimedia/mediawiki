<?php
/**
 * Various HTTP related functions
 */

/**
 * Get the contents of a file by HTTP
 *
 * if $timeout is 'default', $wgHTTPTimeout is used
 */
function wfGetHTTP( $url, $timeout = 'default' ) {
	global $wgHTTPTimeout, $wgHTTPProxy, $wgVersion, $wgTitle, $wgCommandLineMode;

	# Use curl if available
	if ( function_exists( 'curl_init' ) ) {
		$c = curl_init( $url );
		if ( wfIsLocalURL( $url ) ) {
			curl_setopt( $c, CURLOPT_PROXY, 'localhost:80' );
		} else if ($wgHTTPProxy) {
			curl_setopt($c, CURLOPT_PROXY, $wgHTTPProxy);
		}

		if ( $timeout == 'default' ) {
			$timeout = $wgHTTPTimeout;
		}
		curl_setopt( $c, CURLOPT_TIMEOUT, $timeout );
		curl_setopt( $c, CURLOPT_USERAGENT, "MediaWiki/$wgVersion" );

		# Set the referer to $wgTitle, even in command-line mode
		# This is useful for interwiki transclusion, where the foreign
		# server wants to know what the referring page is.
		# $_SERVER['REQUEST_URI'] gives a less reliable indication of the 
		# referring page.
		if ( is_object( $wgTitle ) ) {
			curl_setopt( $c, CURLOPT_REFERER, $wgTitle->getFullURL() );
		}

		ob_start();
		curl_exec( $c );
		$text = ob_get_contents();
		ob_end_clean();

		# Don't return the text of error messages, return false on error
		if ( curl_getinfo( $c, CURLINFO_HTTP_CODE ) != 200 ) {
			$text = false;
		}
		curl_close( $c );
	} else {
		# Otherwise use file_get_contents, or its compatibility function from GlobalFunctions.php
		# This may take 3 minutes to time out, and doesn't have local fetch capabilities
		$url_fopen = ini_set( 'allow_url_fopen', 1 );
		$text = file_get_contents( $url );
		ini_set( 'allow_url_fopen', $url_fopen );
	}
	return $text;
}

/**
 * Check if the URL can be served by localhost
 */
function wfIsLocalURL( $url ) {
	global $wgCommandLineMode, $wgConf;
	if ( $wgCommandLineMode ) {
		return false;
	}

	// Extract host part
	$matches = array();
	if ( preg_match( '!^http://([\w.-]+)[/:].*$!', $url, $matches ) ) {
		$host = $matches[1];
		// Split up dotwise
		$domainParts = explode( '.', $host );
		// Check if this domain or any superdomain is listed in $wgConf as a local virtual host
		$domainParts = array_reverse( $domainParts );
		for ( $i = 0; $i < count( $domainParts ); $i++ ) {
			$domainPart = $domainParts[$i];
			if ( $i == 0 ) {
				$domain = $domainPart;
			} else {
				$domain = $domainPart . '.' . $domain;
			}
			if ( $wgConf->isLocalVHost( $domain ) ) {
				return true;
			}
		}
	}
	return false;
}

?>
