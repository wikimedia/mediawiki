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

namespace MediaWiki;

/**
 * @since 1.31
 */
class OutputHandler {
	/**
	 * Standard output handler for use with ob_start.
	 *
	 * @param string $s Web response output
	 * @return string
	 */
	public static function handle( $s ) {
		global $wgDisableOutputCompression, $wgMangleFlashPolicy;
		if ( $wgMangleFlashPolicy ) {
			$s = self::mangleFlashPolicy( $s );
		}
		if ( !$wgDisableOutputCompression && !ini_get( 'zlib.output_compression' ) ) {
			if ( !defined( 'MW_NO_OUTPUT_COMPRESSION' ) ) {
				$s = self::handleGzip( $s );
			}
			if ( !ini_get( 'output_handler' ) ) {
				self::emitContentLength( strlen( $s ) );
			}
		}
		return $s;
	}

	/**
	 * Get the "file extension" that some client apps will estimate from
	 * the currently-requested URL.
	 *
	 * This isn't a WebRequest method, because we need it before the class loads.
	 * @todo As of 2018, this actually runs after autoloader in Setup.php, so
	 * WebRequest seems like a good place for this.
	 *
	 * @return string
	 */
	private static function findUriExtension() {
		/// @todo FIXME: this sort of dupes some code in WebRequest::getRequestUrl()
		if ( isset( $_SERVER['REQUEST_URI'] ) ) {
			// Strip the query string...
			$path = explode( '?', $_SERVER['REQUEST_URI'], 2 )[0];
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
	 *
	 * Unlike ob_gzhandler, it works for HEAD requests too.
	 *
	 * @param string $s Web response output
	 * @return string
	 */
	private static function handleGzip( $s ) {
		if ( !function_exists( 'gzencode' ) ) {
			wfDebug( __METHOD__ . "() skipping compression (gzencode unavailable)" );
			return $s;
		}
		if ( headers_sent() ) {
			wfDebug( __METHOD__ . "() skipping compression (headers already sent)" );
			return $s;
		}

		$ext = self::findUriExtension();
		if ( $ext == '.gz' || $ext == '.tgz' ) {
			// Don't do gzip compression if the URL path ends in .gz or .tgz
			// This confuses Safari and triggers a download of the page,
			// even though it's pretty clearly labeled as viewable HTML.
			// Bad Safari! Bad!
			return $s;
		}

		if ( wfClientAcceptsGzip() ) {
			wfDebug( __METHOD__ . "() is compressing output" );
			header( 'Content-Encoding: gzip' );
			$s = gzencode( $s, 6 );
		}

		// Set vary header if it hasn't been set already
		$headers = headers_list();
		$foundVary = false;
		foreach ( $headers as $header ) {
			$headerName = strtolower( substr( $header, 0, 5 ) );
			if ( $headerName == 'vary:' ) {
				$foundVary = true;
				break;
			}
		}
		if ( !$foundVary ) {
			header( 'Vary: Accept-Encoding' );
		}
		return $s;
	}

	/**
	 * Mangle flash policy tags which open up the site to XSS attacks.
	 *
	 * @param string $s Web response output
	 * @return string
	 */
	private static function mangleFlashPolicy( $s ) {
		# Avoid weird excessive memory usage in PCRE on big articles
		if ( preg_match( '/\<\s*cross-domain-policy(?=\s|\>)/i', $s ) ) {
			return preg_replace( '/\<(\s*)(cross-domain-policy(?=\s|\>))/i', '<$1NOT-$2', $s );
		} else {
			return $s;
		}
	}

	/**
	 * Add a Content-Length header if possible. This makes it cooperate with CDN better.
	 *
	 * @param int $length
	 */
	private static function emitContentLength( $length ) {
		if ( !headers_sent()
			&& isset( $_SERVER['SERVER_PROTOCOL'] )
			&& $_SERVER['SERVER_PROTOCOL'] == 'HTTP/1.0'
		) {
			header( "Content-Length: $length" );
		}
	}
}
