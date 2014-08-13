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
 * @param string $s
 *
 * @return string
 */
function wfOutputHandler( $s ) {
	// Mangle flash policy tags which open up the site to XSS attacks
	$s = preg_replace( '/\<\s*cross-domain-policy\s*\>/i', '<NOT-cross-domain-policy>', $s );

	global $wgValidateAllHtml;
	if ( $wgValidateAllHtml ) {
		foreach ( headers_list() as $header ) {
			$parts = explode( ':', $header, 2 );
			if ( count( $parts ) !== 2 ) {
				continue;
			}
			$name = strtolower( trim( $parts[0] ) );
			$value = trim( $parts[1] );
			if ( $name === 'content-type' && ( strpos( $value, 'text/html' ) === 0
				|| strpos( $value, 'application/xhtml+xml' ) === 0 )
			) {
				$errors = '';
				if ( !MWTidy::checkErrors( $s, $errors ) ) {
					// Replace the output with an error if the HTML is not valid
					header( 'Cache-Control: no-cache' );
					$body = Html::element( 'h1', null, 'HTML validation error' ) .
						Html::openElement( 'ul' );
					$error = strtok( $errors, "\n" );
					$badLines = array();
					while ( $error !== false ) {
						if ( preg_match( '/^line (\d+)/', $error, $m ) ) {
							$lineNum = intval( $m[1] );
							$badLines[$lineNum] = true;
							$body .= Html::rawElement( 'li', null,
								Html::element( 'a', array( 'href' => "#line-{$lineNum}" ), $error )
							) . "\n";
						}
						$error = strtok( "\n" );
					}
					$body .= Html::closeElement( 'ul' ) .
						Html::element( 'pre', null, $errors ) .
					Html::openElement( 'ol' ) . "\n";
					$line = strtok( $s, "\n" );
					for ( $i = 1; $line !== false; $i++ ) {
						$attrs = array();
						if ( isset( $badLines[$i] ) ) {
							$attrs['class'] = 'highlight';
							$attrs['id'] = "line-{$i}";
						}
						$body .= Html::element( 'li', $attrs, $line ) . "\n";
						$line = strtok( "\n" );
					}
					$body .= Html::closeElement( 'ol' );
					$s = Html::htmlHeader( array( 'lang' => 'en', 'dir' => 'ltr' ) ) .
						Html::rawElement( 'head', null,
							Html::element( 'title', null, 'HTML validation error' ) .
							Html::inlineStyle( ".highlight { background-color: #ffc }\nli { white-space: pre }" )
						) .
						Html::rawElement( 'body', null, $body ) .
					Html::closeElement( 'html' );
				}
				break;
			}
		}
	}

	global $wgDisableOutputCompression;
	if ( !$wgDisableOutputCompression && !ini_get( 'zlib.output_compression' ) ) {
		if ( !defined( 'MW_NO_OUTPUT_COMPRESSION' ) ) {
			// If allowed by the Accept header, handle data compression with gzip
			// Unlike ob_gzhandler, also works with HEAD requests
			$path = explode( '?', WebRequest::getRequestURL(), 2 );
			$period = strrpos( $path[0], '.' );
			$ext = $period !== false ? strtolower( substr( $path[0], $period ) ) : null;
			// Don't do gzip compression if the URL path ends in .gz or .tgz
			// This confuses Safari and triggers a download of the page,
			// even though it's pretty clearly labeled as viewable HTML
			if ( !headers_sent() && function_exists( 'gzencode' ) &&
				( $ext !== '.gz' || $ext !== '.tgz' )
			) {
				if ( wfClientAcceptsGzip() ) {
					header( 'Content-Encoding: gzip' );
					$s = gzencode( $s, 6 );
				}

				// Set vary header if it hasn't been set already
				$foundVary = false;
				foreach ( headers_list() as $header ) {
					if ( strpos( $header, 'Vary:' ) === 0 ) {
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
			}
		}
		if ( !ini_get( 'output_handler' ) ) {
			// Add a Content-Length header if possible. This makes it cooperate with squid better
			if ( !headers_sent() &&
				isset( $_SERVER['SERVER_PROTOCOL'] ) && $_SERVER['SERVER_PROTOCOL'] === 'HTTP/1.0'
			) {
				$length = strlen( $s );
				header( "Content-Length: {$length}" );
			}
		}
	}

	return $s;
}
