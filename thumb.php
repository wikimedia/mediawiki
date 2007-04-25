<?php

/**
 * PHP script to stream out an image thumbnail.
 * If the file exists, we make do with abridged MediaWiki initialisation.
 */
define( 'MW_NO_SETUP', 1 );
define( 'MW_NO_OUTPUT_COMPRESSION', 1 );
require_once( './includes/WebStart.php' );
wfProfileIn( 'thumb.php' );
wfProfileIn( 'thumb.php-start' );
require_once( "$IP/includes/GlobalFunctions.php" );
require_once( "$IP/includes/ImageFunctions.php" );

$wgTrivialMimeDetection = true; //don't use fancy mime detection, just check the file extension for jpg/gif/png.

require_once( "$IP/includes/StreamFile.php" );
require_once( "$IP/includes/AutoLoader.php" );

// Get input parameters
if ( get_magic_quotes_gpc() ) {
	$params = array_map( 'stripslashes', $_REQUEST );
} else {
	$params = $_REQUEST;
}

$fileName = isset( $params['f'] ) ? $params['f'] : '';
unset( $params['f'] );

// Backwards compatibility parameters
if ( isset( $params['w'] ) ) {
	$params['width'] = $params['w'];
	unset( $params['w'] );
}
if ( isset( $params['p'] ) ) {
	$params['page'] = $params['p'];
}
unset( $params['r'] );

// Some basic input validation
$fileName = strtr( $fileName, '\\/', '__' );

// Work out paths, carefully avoiding constructing an Image object because that won't work yet
$handler = thumbGetHandler( $fileName );
if ( $handler ) {
	$imagePath = wfImageDir( $fileName ) . '/' . $fileName;
	$thumbName = $handler->makeParamString( $params ) . "-$fileName";
	$thumbPath = wfImageThumbDir( $fileName ) . '/' . $thumbName;

	if ( is_file( $thumbPath ) && filemtime( $thumbPath ) >= filemtime( $imagePath ) ) {
		wfStreamFile( $thumbPath );
		// Can't log profiling data with no Setup.php
		exit;
	}
}

// OK, no valid thumbnail, time to get out the heavy machinery
wfProfileOut( 'thumb.php-start' );
require_once( './includes/Setup.php' );
wfProfileIn( 'thumb.php-render' );

$img = Image::newFromName( $fileName );
try {
	if ( $img ) {
		$thumb = $img->transform( $params, Image::RENDER_NOW );
	} else {
		$thumb = false;
	}
} catch( Exception $ex ) {
	// Tried to select a page on a non-paged file?
	$thumb = false;
}

if ( $thumb && $thumb->getPath() && file_exists( $thumb->getPath() ) ) {
	wfStreamFile( $thumb->getPath() );
} elseif ( $img ) {
	header( 'Cache-Control: no-cache' );
	header( 'Content-Type: text/html; charset=utf-8' );
	header( 'HTTP/1.1 500 Internal server error' );
	if ( !$thumb ) {
		$msg = wfMsgHtml( 'thumbnail_error', 'Image::transform() returned false' );
	} elseif ( $thumb->isError() ) {
		$msg = $thumb->getHtmlMsg();
	} elseif ( !$thumb->getPath() ) {
		$msg = wfMsgHtml( 'thumbnail_error', 'No path supplied in thumbnail object' );
	} else {
		$msg = wfMsgHtml( 'thumbnail_error', 'Output file missing' );
	}
	echo <<<EOT
<html><head><title>Error generating thumbnail</title></head>
<body>
<h1>Error generating thumbnail</h1>
<p>
$msg
</p>
</body>
</html>

EOT;
} else {
	$badtitle = wfMsg( 'badtitle' );
	$badtitletext = wfMsg( 'badtitletext' );
	header( 'Cache-Control: no-cache' );
	header( 'Content-Type: text/html; charset=utf-8' );
	header( 'HTTP/1.1 500 Internal server error' );
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

//--------------------------------------------------------------------------

function thumbGetHandler( $fileName ) {
	// Determine type
	$magic = MimeMagic::singleton();
	$extPos = strrpos( $fileName, '.' );
	if ( $extPos === false ) {
		return false;
	}
	$mime = $magic->guessTypesForExtension( substr( $fileName, $extPos + 1 ) );
	return MediaHandler::getHandler( $mime );
}

?>
