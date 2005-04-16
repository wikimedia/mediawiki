<?php

/** 
 * PHP script to stream out an image thumbnail. 
 * If the file exists, we make do with abridged MediaWiki initialisation. 
 */

unset( $IP );
define( 'MEDIAWIKI', true );
require_once( './includes/Defines.php' );
require_once( './LocalSettings.php' );
require_once( 'Image.php' );
require_once( 'StreamFile.php' );

// Get input parameters

if ( get_magic_quotes_gpc() ) {
	$fileName = stripslashes( $_REQUEST['f'] );
	$width = stripslashes( $_REQUEST['w'] );
} else {
	$fileName = $_REQUEST['f'];
	$width = $_REQUEST['w'];
}

// Some basic input validation

$width = intval( $width );
$fileName = str_replace( '/', '_', $fileName );

// Work out paths, carefully avoiding constructing an Image object because that won't work yet

$imagePath = wfImageDir( $fileName ) . '/' . $fileName;
$thumbName = "{$width}px-$fileName";
if ( preg_match( '/\.svg$/', $fileName ) ) {
	$thumbName .= '.png';
}
$thumbPath = wfImageThumbDir( $thumbName ) . '/' . $thumbName;

if ( file_exists( $thumbPath ) && filemtime( $thumbPath ) >= filemtime( $imagePath ) ) {
	wfStreamFile( $thumbPath );
	exit;
}

// OK, no valid thumbnail, time to get out the heavy machinery
require_once( 'Setup.php' );

// Force renderThumb() to actually do something
$wgThumbnailScriptPath = false;
$wgSharedThumbnailScriptPath = false;

$img = Image::newFromName( $fileName );
if ( $img ) {
	$thumb = $img->renderThumb( $width );
} else {
	$thumb = false;
}

if ( $thumb ) {
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


?>
