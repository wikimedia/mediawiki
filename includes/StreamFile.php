<?php
/**
 * Functions related to the output of file content.
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
 * Functions related to the output of file content
 */
class StreamFile {
	// Do not send any HTTP headers unless requested by caller (e.g. body only)
	const STREAM_HEADLESS = 1;
	// Do not try to tear down any PHP output buffers
	const STREAM_ALLOW_OB = 2;

	/**
	 * Stream a file to the browser, adding all the headings and fun stuff.
	 * Headers sent include: Content-type, Content-Length, Last-Modified,
	 * and Content-Disposition.
	 *
	 * @param string $fname Full name and path of the file to stream
	 * @param array $headers Any additional headers to send if the file exists
	 * @param bool $sendErrors Send error messages if errors occur (like 404)
	 * @param array $optHeaders HTTP request header map (e.g. "range") (use lowercase keys)
	 * @param integer $flags Bitfield of STREAM_* constants
	 * @throws MWException
	 * @return bool Success
	 */
	public static function stream(
		$fname, $headers = array(), $sendErrors = true, $optHeaders = array(), $flags = 0
	) {
		$section = new ProfileSection( __METHOD__ );

		if ( FileBackend::isStoragePath( $fname ) ) { // sanity
			throw new MWException( __FUNCTION__ . " given storage path '$fname'." );
		}

		// Don't stream it out as text/html if there was a PHP error
		if ( ( ( $flags & self::STREAM_HEADLESS ) == 0 || $headers ) && headers_sent() ) {
			echo "Headers already sent, terminating.\n";
			return false;
		}

		$headerFunc = ( $flags & self::STREAM_HEADLESS )
			? function( $header ) {}
			: function( $header ) { header( $header ); };

		wfSuppressWarnings();
		$info = stat( $fname );
		wfRestoreWarnings();

		if ( !is_array( $info ) ) {
			if ( $sendErrors ) {
				self::send404Message( $fname, $flags );
			}
			return false;
		}

		// Send Last-Modified HTTP header for client-side caching
		$headerFunc( 'Last-Modified: ' . wfTimestamp( TS_RFC2822, $info['mtime'] ) );

		if ( ( $flags & self::STREAM_ALLOW_OB ) == 0 ) {
			// Cancel output buffering and gzipping if set
			wfResetOutputBuffers();
		}

		$type = self::contentTypeFromPath( $fname );
		if ( $type && $type != 'unknown/unknown' ) {
			$headerFunc( "Content-type: $type" );
		} else {
			// Send a content type which is not known to Internet Explorer, to
			// avoid triggering IE's content type detection. Sending a standard
			// unknown content type here essentially gives IE license to apply
			// whatever content type it likes.
			$headerFunc( 'Content-type: application/x-wiki' );
		}

		// Don't send if client has up to date cache
		if ( isset( $optHeaders['if-modified-since'] ) ) {
			$modsince = preg_replace( '/;.*$/', '', $optHeaders['if-modified-since'] );
			if ( wfTimestamp( TS_UNIX, $info['mtime'] ) <= strtotime( $modsince ) ) {
				ini_set( 'zlib.output_compression', 0 );
				$headerFunc( "HTTP/1.0 304 Not Modified" );
				return true; // ok
			}
		}

		// Send additional headers
		foreach ( $headers as $header ) {
			header( $header ); // always use header(); specifically requested
		}

		if ( isset( $optHeaders['range'] ) ) {
			$range = self::parseRange( $optHeaders['range'], $info['size'] );
			if ( is_array( $range ) ) {
				$headerFunc( 'HTTP/1.1 206 Partial Content' );
				$headerFunc( 'Content-Length: ' . $range[2] );
				$headerFunc( "Content-Range: bytes {$range[0]}-{$range[1]}/{$info['size']}" );
			} elseif ( $range === 'invalid' ) {
				if ( $sendErrors ) {
					$headerFunc( 'HTTP/1.0 416 Range Not Satisfiable' );
					$headerFunc( 'Cache-Control: no-cache' );
					$headerFunc( 'Content-Type: text/html; charset=utf-8' );
					$headerFunc( 'Content-Range: bytes */' . $info['size'] );
				}
				return false;
			} else { // unsupported Range request (e.g. multiple ranges)
				$range = null;
				$headerFunc( 'Content-Length: ' . $info['size'] );
			}
		} else {
			$range = null;
			$headerFunc( 'Content-Length: ' . $info['size'] );
		}

		if ( is_array( $range ) ) {
			$handle = fopen( $fname, 'rb' );
			if ( $handle ) {
				$ok = true;
				fseek( $handle, $range[0] );
				$remaining = ( $info['size'] > 0 ) ? $range[1] - $range[0] + 1 : 0;
				while ( $remaining > 0 && $ok ) {
					$bytes = min( $remaining, 8 * 1024 );
					$data = fread( $handle, $bytes );
					$remaining -= $bytes;
					$ok = ( $data !== false );
					print $data;
				}
			} else {
				return false;
			}
		} else {
			return readfile( $fname ) !== false; // faster
		}

		return true;
	}

	/**
	 * Send out a standard 404 message for a file
	 *
	 * @param string $fname Full name and path of the file to stream
	 * @param integer $flags Bitfield of STREAM_* constants
	 * @since 1.24
	 */
	public static function send404Message( $fname, $flags = 0 ) {
		if ( ( $flags & self::STREAM_HEADLESS ) == 0 ) {
			header( 'HTTP/1.0 404 Not Found' );
			header( 'Cache-Control: no-cache' );
			header( 'Content-Type: text/html; charset=utf-8' );
		}
		$encFile = htmlspecialchars( $fname );
		$encScript = htmlspecialchars( $_SERVER['SCRIPT_NAME'] );
		echo "<html><body>
			<h1>File not found</h1>
			<p>Although this PHP script ($encScript) exists, the file requested for output
			($encFile) does not.</p>
			</body></html>
			";
	}

	/**
	 * Convert a Range header value to an absolute (start, end) range tuple
	 *
	 * @param string $range Range header value
	 * @param integer $size File size
	 * @return array|string Returns error string on failure (start, end, length)
	 * @since 1.24
	 */
	public static function parseRange( $range, $size ) {
		$m = array();
		if ( preg_match( '#^bytes=(\d*)-(\d*)$#', $range, $m ) ) {
			list( , $start, $end ) = $m;
			if ( $start === '' && $end === '' ) {
				$absRange = array( 0, $size - 1 );
			} elseif ( $start === '' ) {
				$absRange = array( $size - $end, $size - 1 );
			} elseif ( $end === '' ) {
				$absRange = array( $start, $size - 1 );
			} else {
				$absRange = array( $start, $end );
			}
			if ( $absRange[0] >= 0 && $absRange[1] >= $absRange[0] ) {
				if ( $absRange[0] < $size ) {
					$absRange[1] = min( $absRange[1], $size - 1 ); // stop at EOF
					$absRange[2] = $absRange[1] - $absRange[0] + 1;
					return $absRange;
				} elseif ( $absRange[0] == 0 && $size == 0 ) {
					return 'unrecognized'; // the whole file should just be sent
				}
			}
			return 'invalid';
		}
		return 'unrecognized';
	}

	/**
	 * Determine the file type of a file based on the path
	 *
	 * @param string $filename Storage path or file system path
	 * @param bool $safe Whether to do retroactive upload blacklist checks
	 * @return null|string
	 */
	public static function contentTypeFromPath( $filename, $safe = true ) {
		global $wgTrivialMimeDetection;

		$ext = strrchr( $filename, '.' );
		$ext = $ext === false ? '' : strtolower( substr( $ext, 1 ) );

		# trivial detection by file extension,
		# used for thumbnails (thumb.php)
		if ( $wgTrivialMimeDetection ) {
			switch ( $ext ) {
				case 'gif':
					return 'image/gif';
				case 'png':
					return 'image/png';
				case 'jpg':
					return 'image/jpeg';
				case 'jpeg':
					return 'image/jpeg';
			}

			return 'unknown/unknown';
		}

		$magic = MimeMagic::singleton();
		// Use the extension only, rather than magic numbers, to avoid opening
		// up vulnerabilities due to uploads of files with allowed extensions
		// but disallowed types.
		$type = $magic->guessTypesForExtension( $ext );

		/**
		 * Double-check some security settings that were done on upload but might
		 * have changed since.
		 */
		if ( $safe ) {
			global $wgFileBlacklist, $wgCheckFileExtensions, $wgStrictFileExtensions,
				$wgFileExtensions, $wgVerifyMimeType, $wgMimeTypeBlacklist;
			list( , $extList ) = UploadBase::splitExtensions( $filename );
			if ( UploadBase::checkFileExtensionList( $extList, $wgFileBlacklist ) ) {
				return 'unknown/unknown';
			}
			if ( $wgCheckFileExtensions && $wgStrictFileExtensions
				&& !UploadBase::checkFileExtensionList( $extList, $wgFileExtensions )
			) {
				return 'unknown/unknown';
			}
			if ( $wgVerifyMimeType && in_array( strtolower( $type ), $wgMimeTypeBlacklist ) ) {
				return 'unknown/unknown';
			}
		}
		return $type;
	}
}
