<?php
/**
 * Functions related to the output of file content
 *
 * @file
 */
class StreamFile {
	/**
	 * Stream a file to the browser, adding all the headings and fun stuff
	 * @param $fname string Full name and path of the file to stream
	 * @param $headers array Any additional headers to send
	 * @param $sendErrors bool Send error messages if errors occur (like 404)
	 * @return bool Success
	 */
	public static function stream( $fname, $headers = array(), $sendErrors = true ) {
		global $wgLanguageCode;

		wfSuppressWarnings();
		$stat = stat( $fname );
		wfRestoreWarnings();
		if ( !$stat ) {
			if ( $sendErrors ) {
				header( 'HTTP/1.0 404 Not Found' );
				header( 'Cache-Control: no-cache' );
				header( 'Content-Type: text/html; charset=utf-8' );
				$encFile = htmlspecialchars( $fname );
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

		header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s', $stat['mtime'] ) . ' GMT' );

		// Cancel output buffering and gzipping if set
		wfResetOutputBuffers();

		$type = self::getType( $fname );
		if ( $type && $type != 'unknown/unknown' ) {
			header( "Content-type: $type" );
		} else {
			header( 'Content-type: application/x-wiki' );
		}

		// Don't stream it out as text/html if there was a PHP error
		if ( headers_sent() ) {
			echo "Headers already sent, terminating.\n";
			return false;
		}

		header( "Content-Disposition: inline;filename*=utf-8'$wgLanguageCode'" .
			urlencode( basename( $fname ) ) );

		// Send additional headers
		foreach ( $headers as $header ) {
			header( $header );
		}

		// Don't send if client has up to date cache
		if ( !empty( $_SERVER['HTTP_IF_MODIFIED_SINCE'] ) ) {
			$modsince = preg_replace( '/;.*$/', '', $_SERVER['HTTP_IF_MODIFIED_SINCE'] );
			$sinceTime = strtotime( $modsince );
			if ( $stat['mtime'] <= $sinceTime ) {
				ini_set( 'zlib.output_compression', 0 );
				header( "HTTP/1.0 304 Not Modified" );
				return true; // ok
			}
		}

		header( 'Content-Length: ' . $stat['size'] );

		return readfile( $fname );
	}

	/**
	 * Determine the filetype we're dealing with
	 * @param $filename string
	 * @param $safe bool
	 * @return null|string
	 */
	private static function getType( $filename, $safe = true ) {
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
