<?php

/**
 * Returns the image directory of an image
 * If the directory does not exist, it is created.
 * The result is an absolute path.
 *
 * This function is called from thumb.php before Setup.php is included
 *
 * @param $fname String: file name of the image file.
 * @public
 */
function wfImageDir( $fname ) {
	global $wgUploadDirectory, $wgHashedUploadDirectory;

	if (!$wgHashedUploadDirectory) { return $wgUploadDirectory; }

	$hash = md5( $fname );
	$oldumask = umask(0);
	$dest = $wgUploadDirectory . '/' . $hash{0};
	if ( ! is_dir( $dest ) ) { mkdir( $dest, 0777 ); }
	$dest .= '/' . substr( $hash, 0, 2 );
	if ( ! is_dir( $dest ) ) { mkdir( $dest, 0777 ); }

	umask( $oldumask );
	return $dest;
}

/**
 * Returns the image directory of an image's thubnail
 * If the directory does not exist, it is created.
 * The result is an absolute path.
 *
 * This function is called from thumb.php before Setup.php is included
 *
 * @param $fname String: file name of the original image file
 * @param $shared Boolean: (optional) use the shared upload directory (default: 'false').
 * @public
 */
function wfImageThumbDir( $fname, $shared = false ) {
	$base = wfImageArchiveDir( $fname, 'thumb', $shared );
	if ( Image::isHashed( $shared ) ) {
		$dir =  "$base/$fname";

		if ( !is_dir( $base ) ) {
			$oldumask = umask(0);
			@mkdir( $base, 0777 );
			umask( $oldumask );
		}

		if ( ! is_dir( $dir ) ) {
			if ( is_file( $dir ) ) {
				// Old thumbnail in the way of directory creation, kill it
				unlink( $dir );
			}
			$oldumask = umask(0);
			@mkdir( $dir, 0777 );
			umask( $oldumask );
		}
	} else {
		$dir = $base;
	}

	return $dir;
}

/**
 * Old thumbnail directory, kept for conversion
 */
function wfDeprecatedThumbDir( $thumbName , $subdir='thumb', $shared=false) {
	return wfImageArchiveDir( $thumbName, $subdir, $shared );
}

/**
 * Returns the image directory of an image's old version
 * If the directory does not exist, it is created.
 * The result is an absolute path.
 *
 * This function is called from thumb.php before Setup.php is included
 *
 * @param $fname String: file name of the thumbnail file, including file size prefix.
 * @param $subdir String: subdirectory of the image upload directory that should be used for storing the old version. Default is 'archive'.
 * @param $shared Boolean use the shared upload directory (only relevant for other functions which call this one). Default is 'false'.
 * @public
 */
function wfImageArchiveDir( $fname , $subdir='archive', $shared=false ) {
	global $wgUploadDirectory, $wgHashedUploadDirectory;
	global $wgSharedUploadDirectory, $wgHashedSharedUploadDirectory;
	$dir = $shared ? $wgSharedUploadDirectory : $wgUploadDirectory;
	$hashdir = $shared ? $wgHashedSharedUploadDirectory : $wgHashedUploadDirectory;
	if (!$hashdir) { return $dir.'/'.$subdir; }
	$hash = md5( $fname );
	$oldumask = umask(0);

	# Suppress warning messages here; if the file itself can't
	# be written we'll worry about it then.
	wfSuppressWarnings();

	$archive = $dir.'/'.$subdir;
	if ( ! is_dir( $archive ) ) { mkdir( $archive, 0777 ); }
	$archive .= '/' . $hash{0};
	if ( ! is_dir( $archive ) ) { mkdir( $archive, 0777 ); }
	$archive .= '/' . substr( $hash, 0, 2 );
	if ( ! is_dir( $archive ) ) { mkdir( $archive, 0777 ); }

	wfRestoreWarnings();
	umask( $oldumask );
	return $archive;
}


/*
 * Return the hash path component of an image path (URL or filesystem),
 * e.g. "/3/3c/", or just "/" if hashing is not used.
 *
 * @param $dbkey The filesystem / database name of the file
 * @param $fromSharedDirectory Use the shared file repository? It may
 *   use different hash settings from the local one.
 */
function wfGetHashPath ( $dbkey, $fromSharedDirectory = false ) {
	if( Image::isHashed( $fromSharedDirectory ) ) {
		$hash = md5($dbkey);
		return '/' . $hash{0} . '/' . substr( $hash, 0, 2 ) . '/';
	} else {
		return '/';
	}
}

/**
 * Returns the image URL of an image's old version
 *
 * @param $name String: file name of the image file
 * @param $subdir String: (optional) subdirectory of the image upload directory that is used by the old version. Default is 'archive'
 * @public
 */
function wfImageArchiveUrl( $name, $subdir='archive' ) {
	global $wgUploadPath, $wgHashedUploadDirectory;

	if ($wgHashedUploadDirectory) {
		$hash = md5( substr( $name, 15) );
		$url = $wgUploadPath.'/'.$subdir.'/' . $hash{0} . '/' .
		  substr( $hash, 0, 2 ) . '/'.$name;
	} else {
		$url = $wgUploadPath.'/'.$subdir.'/'.$name;
	}
	return wfUrlencode($url);
}

/**
 * Return a rounded pixel equivalent for a labeled CSS/SVG length.
 * http://www.w3.org/TR/SVG11/coords.html#UnitIdentifiers
 *
 * @param $length String: CSS/SVG length.
 * @return Integer: length in pixels
 */
function wfScaleSVGUnit( $length ) {
	static $unitLength = array(
		'px' => 1.0,
		'pt' => 1.25,
		'pc' => 15.0,
		'mm' => 3.543307,
		'cm' => 35.43307,
		'in' => 90.0,
		''   => 1.0, // "User units" pixels by default
		'%'  => 2.0, // Fake it!
		);
	if( preg_match( '/^(\d+(?:\.\d+)?)(em|ex|px|pt|pc|cm|mm|in|%|)$/', $length, $matches ) ) {
		$length = floatval( $matches[1] );
		$unit = $matches[2];
		return round( $length * $unitLength[$unit] );
	} else {
		// Assume pixels
		return round( floatval( $length ) );
	}
}

/**
 * Compatible with PHP getimagesize()
 * @todo support gzipped SVGZ
 * @todo check XML more carefully
 * @todo sensible defaults
 *
 * @param $filename String: full name of the file (passed to php fopen()).
 * @return array
 */
function wfGetSVGsize( $filename ) {
	$width = 256;
	$height = 256;

	// Read a chunk of the file
	$f = fopen( $filename, "rt" );
	if( !$f ) return false;
	$chunk = fread( $f, 4096 );
	fclose( $f );

	// Uber-crappy hack! Run through a real XML parser.
	if( !preg_match( '/<svg\s*([^>]*)\s*>/s', $chunk, $matches ) ) {
		return false;
	}
	$tag = $matches[1];
	if( preg_match( '/\bwidth\s*=\s*("[^"]+"|\'[^\']+\')/s', $tag, $matches ) ) {
		$width = wfScaleSVGUnit( trim( substr( $matches[1], 1, -1 ) ) );
	}
	if( preg_match( '/\bheight\s*=\s*("[^"]+"|\'[^\']+\')/s', $tag, $matches ) ) {
		$height = wfScaleSVGUnit( trim( substr( $matches[1], 1, -1 ) ) );
	}

	return array( $width, $height, 'SVG',
		"width=\"$width\" height=\"$height\"" );
}

/**
 * Determine if an image exists on the 'bad image list'.
 *
 * @param $name String: the image name to check
 * @return bool
 */
function wfIsBadImage( $name ) {
	static $titleList = false;
	wfProfileIn( __METHOD__ );
	$bad = false;
	if( wfRunHooks( 'BadImage', array( $name, &$bad ) ) {
		if( !$titleList ) {
			# Build the list now
			$titleList = array();
			$lines = explode( "\n", wfMsgForContent( 'bad_image_list' ) );
			foreach( $lines as $line ) {
				if( preg_match( '/^\*\s*\[\[:?(.*?)\]\]/i', $line, $matches ) ) {
					$title = Title::newFromText( $matches[1] );
					if( is_object( $title ) && $title->getNamespace() == NS_IMAGE )
						$titleList[ $title->getDBkey() ] = true;
				}
			}
		}
		wfProfileOut( __METHOD__ );
		return array_key_exists( $name, $titleList );
	} else {
		wfProfileOut( __METHOD__ );
		return $bad;
	}
}

/**
 * Calculate the largest thumbnail width for a given original file size
 * such that the thumbnail's height is at most $maxHeight.
 * @param $boxWidth Integer Width of the thumbnail box.
 * @param $boxHeight Integer Height of the thumbnail box.
 * @param $maxHeight Integer Maximum height expected for the thumbnail.
 * @return Integer.
 */
function wfFitBoxWidth( $boxWidth, $boxHeight, $maxHeight ) {
	$idealWidth = $boxWidth * $maxHeight / $boxHeight;
	$roundedUp = ceil( $idealWidth );
	if( round( $roundedUp * $boxHeight / $boxWidth ) > $maxHeight )
		return floor( $idealWidth );
	else
		return $roundedUp;
}


?>
