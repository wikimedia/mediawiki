<?php

/**
 * PHP script to stream out an image thumbnail.
 * If the file exists, we make do with abridged MediaWiki initialisation.
 */

define( 'MEDIAWIKI', true );
unset( $IP );
if ( isset( $_REQUEST['GLOBALS'] ) ) {
	echo '<a href="http://www.hardened-php.net/index.76.html">$GLOBALS overwrite vulnerability</a>';
	die( -1 );
}

define( 'MW_NO_OUTPUT_BUFFER', true );

require_once( './includes/Defines.php' );
require_once( './LocalSettings.php' );
require_once( 'GlobalFunctions.php' );
require_once( 'ImageFunctions.php' );

$wgTrivialMimeDetection = true; //don't use fancy mime detection, just check the file extension for jpg/gif/png.

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

$pre_render= isset($_REQUEST['r']) && $_REQUEST['r']!="0";

// Some basic input validation

$width = intval( $width );
$fileName = strtr( $fileName, '\\/', '__' );

// Work out paths, carefully avoiding constructing an Image object because that won't work yet

$imagePath = wfImageDir( $fileName ) . '/' . $fileName;
$thumbName = "{$width}px-$fileName";
if ( $pre_render ) {
	$thumbName .= '.png';
}
$thumbPath = wfImageThumbDir( $fileName ) . '/' . $thumbName;

if ( is_file( $thumbPath ) && filemtime( $thumbPath ) >= filemtime( $imagePath ) ) {
	wfStreamFile( $thumbPath );
	exit;
}

// OK, no valid thumbnail, time to get out the heavy machinery
require_once( 'Setup.php' );
wfProfileIn( 'thumb.php' );

$img = Image::newFromName( $fileName );
if ( $img ) {
	$thumb = $img->renderThumb( $width, false );
} else {
	$thumb = false;
}

if ( $thumb && $thumb->path ) {
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

wfProfileOut( 'thumb.php' );


?>
