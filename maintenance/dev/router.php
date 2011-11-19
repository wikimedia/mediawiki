<?php

if ( isset( $_SERVER["SCRIPT_FILENAME"] ) ) {
	$file = $_SERVER["SCRIPT_FILENAME"];
	if ( !is_readable( $file ) ) {
		// Let the server throw the error
		return false;
	}
	$ext = pathinfo( $file, PATHINFO_EXTENSION );
	if ( $ext == 'php' ) {
		# Let it execute php files
		return false;
	}
	$mime = false;
	$lines = explode( "\n", file_get_contents( "includes/mime.types" ) );
	foreach ( $lines as $line ) {
		$exts = explode( " ", $line );
		$mime = array_shift( $exts );
		if ( in_array( $ext, $exts ) ) {
			break; # this is the right value for $mime
		}
		$mime = false;
	}
	if ( !$mime ) {
		$basename = basename( $file );
		if ( $basename == strtoupper( $basename ) ) {
			# IF it's something like README serve it as text
			$mime = "text/plain";
		}
	}
	if ( $mime ) {
		# Use custom handling to serve files with a known mime type
		# This way we can serve things like .svg files that the built-in
		# PHP webserver doesn't understand.
		# ;) Nicely enough we just happen to bundle a mime.types file
		$f = fopen($file, 'rb');
		if ( preg_match( '^text/', $mime ) ) {
			# Text should have a charset=UTF-8 (php's webserver does this too)
			header("Content-Type: $mime; charset=UTF-8");
		} else {
			header("Content-Type: $mime");
		}
		header("Content-Length: " . filesize($file));
		// Stream that out to the browser
		fpassthru($f);
		return true;
	}
}

# Let the php server handle things on it's own otherwise
return false;

