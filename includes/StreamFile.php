<?php
/**
 * Functions related to the output of file content
 *
 * @file
 */

/** */
function wfStreamFile( $fname, $headers = array() ) {
	$stat = @stat( $fname );
	if ( !$stat ) {
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
		return;
	}

	header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s', $stat['mtime'] ) . ' GMT' );

	// Cancel output buffering and gzipping if set
	wfResetOutputBuffers();

	$type = wfGetType( $fname );
	if ( $type and $type!="unknown/unknown") {
		header("Content-type: $type");
	} else {
		header('Content-type: application/x-wiki');
	}

	// Don't stream it out as text/html if there was a PHP error
	if ( headers_sent() ) {
		echo "Headers already sent, terminating.\n";
		return;
	}

	global $wgLanguageCode;
	header( "Content-Disposition: inline;filename*=utf-8'$wgLanguageCode'" . urlencode( basename( $fname ) ) );

	foreach ( $headers as $header ) {
		header( $header );
	}

	if ( !empty( $_SERVER['HTTP_IF_MODIFIED_SINCE'] ) ) {
		$modsince = preg_replace( '/;.*$/', '', $_SERVER['HTTP_IF_MODIFIED_SINCE'] );
		$sinceTime = strtotime( $modsince );
		if ( $stat['mtime'] <= $sinceTime ) {
			ini_set('zlib.output_compression', 0);
			header( "HTTP/1.0 304 Not Modified" );
			return;
		}
	}

	header( 'Content-Length: ' . $stat['size'] );

	readfile( $fname );
}

/** */
function wfGetType( $filename, $safe = true ) {
	global $wgTrivialMimeDetection;

	$ext = strrchr($filename, '.');
	$ext = $ext === false ? '' : strtolower( substr( $ext, 1 ) );

	# trivial detection by file extension,
	# used for thumbnails (thumb.php)
	if ($wgTrivialMimeDetection) {
		switch ($ext) {
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
