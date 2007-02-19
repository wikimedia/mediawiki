<?php

/**
 * Standard output handler for use with ob_start
 */
function wfOutputHandler( $s ) {
	global $wgDisableOutputCompression;
	$s = wfMangleFlashPolicy( $s );
	if ( !ini_get( 'zlib.output_compression' ) ) {
		if ( $wgDisableOutputCompression || !defined( 'MW_NO_OUTPUT_COMPRESSION' ) ) {
			$s = wfGzipHandler( $s );
		}
		if ( !ini_get( 'output_handler' ) ) {
			wfDoContentLength( strlen( $s ) );
		}
	}
	return $s;
}

/**
 * Handler that compresses data with gzip if allowed by the Accept header.
 * Unlike ob_gzhandler, it works for HEAD requests too.
 */
function wfGzipHandler( $s ) {
	if ( $s !== '' && function_exists( 'gzencode' ) && !headers_sent() ) {
		$tokens = preg_split( '/[,; ]/', $_SERVER['HTTP_ACCEPT_ENCODING'] );
		if ( in_array( 'gzip', $tokens ) ) {
			header( 'Content-Encoding: gzip' );
			$s = gzencode( $s, 3 );

			# Set vary header if it hasn't been set already
			$headers = headers_list();
			$foundVary = false;
			foreach ( $headers as $header ) {
				if ( substr( $header, 0, 5 ) == 'Vary:' ) {
					$foundVary == true;
					break;
				}
			}
			if ( !$foundVary ) {
				header( 'Vary: Accept-Encoding' );
			}
		}
	}
	return $s;
}

/**
 * Mangle flash policy tags which open up the site to XSS attacks.
 */
function wfMangleFlashPolicy( $s ) {
	return preg_replace( '/\<\s*cross-domain-policy\s*\>/i', '<NOT-cross-domain-policy>', $s );
}

/**
 * Add a Content-Length header if possible. This makes it cooperate with squid better.
 */
function wfDoContentLength( $length ) {
	if ( !headers_sent() && $_SERVER['SERVER_PROTOCOL'] == 'HTTP/1.0' ) {
		header( "Content-Length: $length" );
	}
}

?>
