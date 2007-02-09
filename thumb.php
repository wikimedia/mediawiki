<?php

/**
 * PHP script to stream out an image thumbnail.
 * If the file exists, we make do with abridged MediaWiki initialisation.
 */
define( 'MW_NO_SETUP', 1 );
require_once( './includes/WebStart.php' );
wfProfileIn( 'thumb.php' );
wfProfileIn( 'thumb.php-start' );
require_once( './includes/GlobalFunctions.php' );
require_once( './includes/ImageFunctions.php' );

$wgTrivialMimeDetection = true; //don't use fancy mime detection, just check the file extension for jpg/gif/png.

require_once( './includes/Image.php' );
require_once( './includes/StreamFile.php' );

// Get input parameters
$fileName = isset( $_REQUEST['f'] ) ? $_REQUEST['f'] : '';
$width = isset( $_REQUEST['w'] ) ? intval( $_REQUEST['w'] ) : 0;
$page = isset( $_REQUEST['p'] ) ? intval( $_REQUEST['p'] ) : null;

if ( get_magic_quotes_gpc() ) {
	$fileName = stripslashes( $fileName );
}

$pre_render= isset($_REQUEST['r']) && $_REQUEST['r']!="0";

// Some basic input validation
$fileName = strtr( $fileName, '\\/', '__' );

// Work out paths, carefully avoiding constructing an Image object because that won't work yet

$imagePath = wfImageDir( $fileName ) . '/' . $fileName;
$thumbName = "{$width}px-$fileName";
if ( ! is_null( $page ) ) {
	$thumbName = 'page' . $page . '-' . $thumbName;
}
if ( $pre_render ) {
	$thumbName .= '.png';
}
$thumbPath = wfImageThumbDir( $fileName ) . '/' . $thumbName;

if ( is_file( $thumbPath ) && filemtime( $thumbPath ) >= filemtime( $imagePath ) ) {
	wfStreamFile( $thumbPath );
	// Can't log profiling data with no Setup.php
	exit;
}

// OK, no valid thumbnail, time to get out the heavy machinery
wfProfileOut( 'thumb.php-start' );
require_once( './includes/Setup.php' );
wfProfileIn( 'thumb.php-render' );

$img = Image::newFromName( $fileName );
try {
	if ( $img ) {
		if ( ! is_null( $page ) ) {
			$img->selectPage( $page );
		}
		$thumb = $img->renderThumb( $width, false );
	} else {
		$thumb = false;
	}
} catch( Exception $ex ) {
	// Tried to select a page on a non-paged file?
	$thumb = false;
}

if ( $thumb && $thumb->path ) {
	wfStreamFile( $thumb->path );
} else {
	$badtitle = wfMsg( 'badtitle' );
	$badtitletext = wfMsg( 'badtitletext' );
	header( 'Cache-Control: no-cache' );
	header( 'Content-Type: text/html' );
	echo "<html><head>
	<title>$badtitle</title>
	<body>
<h1>$badtitle</h1>
<p>$badtitletext</p>
</body></html>
";
}

wfProfileOut( 'thumb.php-render' );
wfProfileOut( 'thumb.php' );
wfLogProfilingData();

?>
