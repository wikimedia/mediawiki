<?php
/**
 * Various HTTP related functions
 * @defgroup HTTP HTTP
 * @ingroup HTTP
 */
class Http {

	/**
	 * Simple wrapper for Http::request( 'GET' )
	 * @see Http::request()
	 */
	public static function get( $url, $timeout = 'default', $opts = array() ) {
		return Http::request( "GET", $url, $timeout, $opts );
	}

	/**
	 * Simple wrapper for Http::request( 'POST' )
	 * @see Http::request()
	 */
	public static function post( $url, $timeout = 'default', $opts = array() ) {
		return Http::request( "POST", $url, $timeout, $opts );
	}

	/**
	 * Get the contents of a file by HTTP
	 * @param $method string HTTP method. Usually GET/POST
	 * @param $url string Full URL to act on
	 * @param $timeout int Seconds to timeout. 'default' falls to $wgHTTPTimeout
	 * @param $curlOptions array Optional array of extra params to pass 
	 * to curl_setopt()
	 */
	public static function request( $method, $url, $timeout = 'default', $curlOptions = array() ) {
		global $wgHTTPTimeout, $wgHTTPProxy, $wgTitle;

		// Go ahead and set the timeout if not otherwise specified
		if ( $timeout == 'default' ) {
			$timeout = $wgHTTPTimeout;
		}

		wfDebug( __METHOD__ . ": $method $url\n" );
		# Use curl if available
		if ( function_exists( 'curl_init' ) ) {
			$c = curl_init( $url );
			if ( self::isLocalURL( $url ) ) {
				curl_setopt( $c, CURLOPT_PROXY, 'localhost:80' );
			} else if ($wgHTTPProxy) {
				curl_setopt($c, CURLOPT_PROXY, $wgHTTPProxy);
			}

			curl_setopt( $c, CURLOPT_TIMEOUT, $timeout );
			curl_setopt( $c, CURLOPT_USERAGENT, self :: userAgent() );
			if ( $method == 'POST' ) {
				curl_setopt( $c, CURLOPT_POST, true );
				curl_setopt( $c, CURLOPT_POSTFIELDS, '' );
			}
			else
				curl_setopt( $c, CURLOPT_CUSTOMREQUEST, $method );

			# Set the referer to $wgTitle, even in command-line mode
			# This is useful for interwiki transclusion, where the foreign
			# server wants to know what the referring page is.
			# $_SERVER['REQUEST_URI'] gives a less reliable indication of the
			# referring page.
			if ( is_object( $wgTitle ) ) {
				curl_setopt( $c, CURLOPT_REFERER, $wgTitle->getFullURL() );
			}
			
			if ( is_array( $curlOptions ) ) {
				foreach( $curlOptions as $option => $value ) {
					curl_setopt( $c, $option, $value );
				}
			}

			ob_start();
			curl_exec( $c );
			$text = ob_get_contents();
			ob_end_clean();

			# Don't return the text of error messages, return false on error
			$retcode = curl_getinfo( $c, CURLINFO_HTTP_CODE );
			if ( $retcode != 200 ) {
				wfDebug( __METHOD__ . ": HTTP return code $retcode\n" );
				$text = false;
			}
			# Don't return truncated output
			$errno = curl_errno( $c );
			if ( $errno != CURLE_OK ) {
				$errstr = curl_error( $c );
				wfDebug( __METHOD__ . ": CURL error code $errno: $errstr\n" );
				$text = false;
			}
			curl_close( $c );
		} else {
			# Otherwise use file_get_contents...
			# This doesn't have local fetch capabilities...

			$headers = array( "User-Agent: " . self :: userAgent() );
			if( strcasecmp( $method, 'post' ) == 0 ) {
				// Required for HTTP 1.0 POSTs
				$headers[] = "Content-Length: 0";
			}
			$opts = array(
				'http' => array(
					'method' => $method,
					'header' => implode( "\r\n", $headers ),
					'timeout' => $timeout ) );
			$ctx = stream_context_create($opts);

			$text = file_get_contents( $url, false, $ctx );
		}
		return $text;
	}

	/**
	 * Check if the URL can be served by localhost
	 * @param $url string Full url to check
	 * @return bool
	 */
	public static function isLocalURL( $url ) {
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
	
	/**
	 * Return a standard user-agent we can use for external requests.
	 */
	public static function userAgent() {
		global $wgVersion;
		return "MediaWiki/$wgVersion";
	}
}
