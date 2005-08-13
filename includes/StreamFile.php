<?php
/** */

/** */
function wfStreamFile( $fname ) {
	global $wgSquidMaxage;
	$stat = @stat( $fname );
	if ( !$stat ) {
		header( 'HTTP/1.0 404 Not Found' );
		echo "<html><body>
<h1>File not found</h1>
<p>Although this PHP script ({$_SERVER['SCRIPT_NAME']}) exists, the file requested for output 
does not.</p>
</body></html>";
		return;
	}

	header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s', $stat['mtime'] ) . ' GMT' );

	if ( !empty( $_SERVER['HTTP_IF_MODIFIED_SINCE'] ) ) {
		$modsince = preg_replace( '/;.*$/', '', $_SERVER['HTTP_IF_MODIFIED_SINCE'] );
		$sinceTime = strtotime( $modsince );
		if ( $stat['mtime'] <= $sinceTime ) {
			header( "HTTP/1.0 304 Not Modified" );
			return;
		}
	}
	
	header( 'Content-Length: ' . $stat['size'] );
	
	$type = wfGetType( $fname );
	if ( $type and $type!="unknown/unknown") {
		header("Content-type: $type");
	} else {
		header('Content-type: application/x-wiki');
	}
	
	readfile( $fname );
}

/** */
function wfGetType( $filename ) {
	global $wgTrivialMimeDetection;

	# trivial detection by file extension,
	# used for thumbnails (thumb.php)
	if ($wgTrivialMimeDetection) {
		$ext= strtolower(strrchr($filename, '.'));
		
		switch ($ext) {
			case '.gif': return 'image/gif';
			case '.png': return 'image/png';
			case '.jpg': return 'image/jpeg';
			case '.jpeg': return 'image/jpeg';
		}
		
		return 'unknown/unknown';
	}
	else {
		$magic=& wfGetMimeMagic();
		return $magic->guessMimeType($filename); //full fancy mime detection
	}
}

?>
