<?php
/**
 * Functions related to the output of file content
 *
 * @file
 */
class StreamFile {
	const READY_STREAM = 1;
	const NOT_MODIFIED = 2;

	/**
	 * Stream a file to the browser, adding all the headings and fun stuff.
	 * Headers sent include: Content-type, Content-Length, Last-Modified,
	 * and Content-Disposition.
	 * 
	 * @param $fname string Full name and path of the file to stream
	 * @param $headers array Any additional headers to send
	 * @param $sendErrors bool Send error messages if errors occur (like 404)
	 * @return bool Success
	 */
	public static function stream( $fname, $headers = array(), $sendErrors = true ) {
		wfSuppressWarnings();
		$stat = stat( $fname );
		wfRestoreWarnings();

		$res = self::prepareForStream( $fname, $stat, $headers, $sendErrors );
		if ( $res == self::NOT_MODIFIED ) {
			return true; // use client cache
		} elseif ( $res == self::READY_STREAM ) {
			return readfile( $fname );
		} else {
			return false; // failed
		}
	}

	/**
	 * Call this function used in preparation before streaming a file.
	 * This function does the following:
	 * (a) sends Last-Modified, Content-type, and Content-Disposition headers
	 * (b) cancels any PHP output buffering and automatic gzipping of output
	 * (c) sends Content-Length header based on HTTP_IF_MODIFIED_SINCE check
	 *
	 * @param $path string Storage path or file system path
	 * @param $info Array|false File stat info with 'mtime' and 'size' fields
	 * @param $headers Array Additional headers to send
	 * @param $sendErrors bool Send error messages if errors occur (like 404)
	 * @return int|false READY_STREAM, NOT_MODIFIED, or false on failure
	 */
	public static function prepareForStream(
		$path, $info, $headers = array(), $sendErrors = true
	) {
		global $wgLanguageCode;

		if ( !is_array( $info ) ) {
			if ( $sendErrors ) {
				header( 'HTTP/1.0 404 Not Found' );
				header( 'Cache-Control: no-cache' );
				header( 'Content-Type: text/html; charset=utf-8' );
				$encFile = htmlspecialchars( $path );
				$encScript = htmlspecialchars( $_SERVER['SCRIPT_NAME'] );
				echo "<html><body>
					<h1>File not found</h1>
					<p>Although this PHP script ($encScript) exists, the file requested for output
					($encFile) does not.</p>
					</body></html>
					";
			}
			return false;
		}

		// Sent Last-Modified HTTP header for client-side caching
		header( 'Last-Modified: ' . wfTimestamp( TS_RFC2822, $info['mtime'] ) );

		// Cancel output buffering and gzipping if set
		wfResetOutputBuffers();

		$type = self::contentTypeFromPath( $path );
		if ( $type && $type != 'unknown/unknown' ) {
			header( "Content-type: $type" );
		} else {
			// Send a content type which is not known to Internet Explorer, to
			// avoid triggering IE's content type detection. Sending a standard
			// unknown content type here essentially gives IE license to apply
			// whatever content type it likes.
			header( 'Content-type: application/x-wiki' );
		}

		// Don't stream it out as text/html if there was a PHP error
		if ( headers_sent() ) {
			echo "Headers already sent, terminating.\n";
			return false;
		}

		header( "Content-Disposition: inline;filename*=utf-8'$wgLanguageCode'" .
			urlencode( basename( $path ) ) );

		// Send additional headers
		foreach ( $headers as $header ) {
			header( $header );
		}

		// Don't send if client has up to date cache
		if ( !empty( $_SERVER['HTTP_IF_MODIFIED_SINCE'] ) ) {
			$modsince = preg_replace( '/;.*$/', '', $_SERVER['HTTP_IF_MODIFIED_SINCE'] );
			if ( wfTimestamp( TS_UNIX, $info['mtime'] ) <= strtotime( $modsince ) ) {
				ini_set( 'zlib.output_compression', 0 );
				header( "HTTP/1.0 304 Not Modified" );
				return self::NOT_MODIFIED; // ok
			}
		}

		header( 'Content-Length: ' . $info['size'] );

		return self::READY_STREAM; // ok
	}

	/**
	 * Determine the file type of a file based on the path
	 * 
	 * @param $filename string Storage path or file system path
	 * @param $safe bool Whether to do retroactive upload blacklist checks
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
				case 'gif': return 'image/gif';
				case 'png': return 'image/png';
				case 'jpg': return 'image/jpeg';
				case 'jpeg': return 'image/jpeg';
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
				&& !UploadBase::checkFileExtensionList( $extList, $wgFileExtensions ) )
			{
				return 'unknown/unknown';
			}
			if ( $wgVerifyMimeType && in_array( strtolower( $type ), $wgMimeTypeBlacklist ) ) {
				return 'unknown/unknown';
			}
		}
		return $type;
	}
}
