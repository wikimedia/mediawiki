<?php

/**
 * PHP script to stream out an image thumbnail.
 * If the file exists, we make do with abridged MediaWiki initialisation.
 */
define( 'MW_NO_SETUP', 1 );
require_once( './includes/WebStart.php' );
wfProfileIn( 'thumb.php' );
wfProfileIn( 'thumb.php-start' );
require_once( 'GlobalFunctions.php' );
require_once( 'ImageFunctions.php' );

$wgTrivialMimeDetection = true; //don't use fancy mime detection, just check the file extension for jpg/gif/png.

require_once( 'Image.php' );
require_once( 'StreamFile.php' );

// Get input parameters
$p=null;

if ( get_magic_quotes_gpc() ) {
	$fileName = stripslashes( $_REQUEST['f'] );
	$width = stripslashes( $_REQUEST['w'] );
	if ( isset( $_REQUEST['p'] ) ) { // optional page number
		$page = stripslashes( $_REQUEST['p'] );
	}
} else {
	$fileName = $_REQUEST['f'];
	$width = $_REQUEST['w'];
	if ( isset( $_REQUEST['p'] ) ) { // optional page number
		$page =  $_REQUEST['p'] ;
	}
}

$pre_render= isset($_REQUEST['r']) && $_REQUEST['r']!="0";

// Some basic input validation

$width = intval( $width );
if ( ! is_null( $page ) ) {
	$page = intval( $page );
}
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
require_once( 'Setup.php' );
wfProfileIn( 'thumb.php-render' );

$img = Image::newFromName( $fileName );
if ( $img ) {
	if ( ! is_null( $page ) ) {
		$img->selectPage( $page );
	}
	$thumb = $img->renderThumb( $width, false );
} else {
	$thumb = false;
}

if ( $img->lastError && $img->lastError !== true ) {
	header( 'HTTP/1.0 500 Internal Server Error' );
	echo "<html><body><h1>Thumbnail generation error</h1><p>" . 
		htmlspecialchars( $img->lastError ) . "<br>" . wfHostname() .
		"</p></body></html>";
} elseif ( $thumb && $thumb->path ) {
	wfStreamFile( $thumb->path );
} else {
	$badtitle = wfMsg( 'badtitle' );
	$badtitletext = wfMsg( 'badtitletext' );
	echo "<html><head>
	<title>$badtitle</title>
	<body>
<h1>$badtitle</h1>
<p>$badtitletext</p>
</body></html>";
}

wfProfileOut( 'thumb.php-render' );
wfProfileOut( 'thumb.php' );
wfLogProfilingData();

?>
