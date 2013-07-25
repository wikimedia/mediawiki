<?php
/**
 * Functions to be used with PHP's output buffer.
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
 * Standard output handler for use with ob_start
 *
 * @param $s string
 *
 * @return string
 */
function wfOutputHandler( $s ) {
	global $wgDisableOutputCompression, $wgValidateAllHtml;
	$s = wfMangleFlashPolicy( $s );
	if ( $wgValidateAllHtml ) {
		$headers = headers_list();
		$isHTML = false;
		foreach ( $headers as $header ) {
			$parts = explode( ':', $header, 2 );
			if ( count( $parts ) !== 2 ) {
				continue;
			}
			$name = strtolower( trim( $parts[0] ) );
			$value = trim( $parts[1] );
			if ( $name == 'content-type' && ( strpos( $value, 'text/html' ) === 0
				|| strpos( $value, 'application/xhtml+xml' ) === 0 )
			) {
				$isHTML = true;
				break;
			}
		}
		if ( $isHTML ) {
			$s = wfHtmlValidationHandler( $s );
		}
	}
	if ( !$wgDisableOutputCompression && !ini_get( 'zlib.output_compression' ) ) {
		if ( !defined( 'MW_NO_OUTPUT_COMPRESSION' ) ) {
			$s = wfGzipHandler( $s );
		}
		if ( !ini_get( 'output_handler' ) ) {
			wfDoContentLength( strlen( $s ) );
		}
	}
	return $s;
}

/**
 * Get the "file extension" that some client apps will estimate from
 * the currently-requested URL.
 * This isn't on WebRequest because we need it when things aren't initialized
 * @private
 *
 * @return string
 */
function wfRequestExtension() {
	/// @todo FIXME: this sort of dupes some code in WebRequest::getRequestUrl()
	if ( isset( $_SERVER['REQUEST_URI'] ) ) {
		// Strip the query string...
		list( $path ) = explode( '?', $_SERVER['REQUEST_URI'], 2 );
	} elseif ( isset( $_SERVER['SCRIPT_NAME'] ) ) {
		// Probably IIS. QUERY_STRING appears separately.
		$path = $_SERVER['SCRIPT_NAME'];
	} else {
		// Can't get the path from the server? :(
		return '';
	}

	$period = strrpos( $path, '.' );
	if ( $period !== false ) {
		return strtolower( substr( $path, $period ) );
	}
	return '';
}

/**
 * Handler that compresses data with gzip if allowed by the Accept header.
 * Unlike ob_gzhandler, it works for HEAD requests too.
 *
 * @param $s string
 *
 * @return string
 */
function wfGzipHandler( $s ) {
	if ( !function_exists( 'gzencode' ) ) {
		wfDebug( __FUNCTION__ . "() skipping compression (gzencode unavailable)\n" );
		return $s;
	}
	if ( headers_sent() ) {
		wfDebug( __FUNCTION__ . "() skipping compression (headers already sent)\n" );
		return $s;
	}

	$ext = wfRequestExtension();
	if ( $ext == '.gz' || $ext == '.tgz' ) {
		// Don't do gzip compression if the URL path ends in .gz or .tgz
		// This confuses Safari and triggers a download of the page,
		// even though it's pretty clearly labeled as viewable HTML.
		// Bad Safari! Bad!
		return $s;
	}

	if ( wfClientAcceptsGzip() ) {
		wfDebug( __FUNCTION__ . "() is compressing output\n" );
		header( 'Content-Encoding: gzip' );
		$s = gzencode( $s, 6 );
	}

	// Set vary header if it hasn't been set already
	$headers = headers_list();
	$foundVary = false;
	foreach ( $headers as $header ) {
		if ( substr( $header, 0, 5 ) == 'Vary:' ) {
			$foundVary = true;
			break;
		}
	}
	if ( !$foundVary ) {
		header( 'Vary: Accept-Encoding' );
		global $wgUseXVO;
		if ( $wgUseXVO ) {
			header( 'X-Vary-Options: Accept-Encoding;list-contains=gzip' );
		}
	}
	return $s;
}

/**
 * Mangle flash policy tags which open up the site to XSS attacks.
 *
 * @param $s string
 *
 * @return string
 */
function wfMangleFlashPolicy( $s ) {
	# Avoid weird excessive memory usage in PCRE on big articles
	if ( preg_match( '/\<\s*cross-domain-policy\s*\>/i', $s ) ) {
		return preg_replace( '/\<\s*cross-domain-policy\s*\>/i', '<NOT-cross-domain-policy>', $s );
	} else {
		return $s;
	}
}

/**
 * Add a Content-Length header if possible. This makes it cooperate with squid better.
 *
 * @param $length int
 */
function wfDoContentLength( $length ) {
	if ( !headers_sent() && isset( $_SERVER['SERVER_PROTOCOL'] ) && $_SERVER['SERVER_PROTOCOL'] == 'HTTP/1.0' ) {
		header( "Content-Length: $length" );
	}
}

/**
 * Replace the output with an error if the HTML is not valid
 *
 * @param $s string
 *
 * @return string
 */
function wfHtmlValidationHandler( $s ) {

	$errors = '';
	if ( MWTidy::checkErrors( $s, $errors ) ) {
		return $s;
	}

	header( 'Cache-Control: no-cache' );

	$out = Html::element( 'h1', null, 'HTML validation error' );
	$out .= Html::openElement( 'ul' );

	$error = strtok( $errors, "\n" );
	$badLines = array();
	while ( $error !== false ) {
		if ( preg_match( '/^line (\d+)/', $error, $m ) ) {
			$lineNum = intval( $m[1] );
			$badLines[$lineNum] = true;
			$out .= Html::rawElement( 'li', null,
				Html::element( 'a', array( 'href' => "#line-{$lineNum}" ), $error ) ) . "\n";
		}
		$error = strtok( "\n" );
	}

	$out .= Html::closeElement( 'ul' );
	$out .= Html::element( 'pre', null, $errors );
	$out .= Html::openElement( 'ol' ) . "\n";
	$line = strtok( $s, "\n" );
	$i = 1;
	while ( $line !== false ) {
		$attrs = array();
		if ( isset( $badLines[$i] ) ) {
			$attrs['class'] = 'highlight';
			$attrs['id'] = "line-$i";
		}
		$out .= Html::element( 'li', $attrs, $line ) . "\n";
		$line = strtok( "\n" );
		$i++;
	}
	$out .= Html::closeElement( 'ol' );

	$style = <<<CSS
.highlight { background-color: #ffc }
li { white-space: pre }
CSS;

	$out = Html::htmlHeader( array( 'lang' => 'en', 'dir' => 'ltr' ) ) .
		Html::rawElement( 'head', null,
			Html::element( 'title', null, 'HTML validation error' ) .
			Html::inlineStyle( $style ) ) .
		Html::rawElement( 'body', null, $out ) .
		Html::closeElement( 'html' );

	return $out;
}
