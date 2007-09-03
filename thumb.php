<?php

/**
 * PHP script to stream out an image thumbnail.
 *
 * @addtogroup Media
 */
define( 'MW_NO_OUTPUT_COMPRESSION', 1 );
require_once( './includes/WebStart.php' );
wfProfileIn( 'thumb.php' );
wfProfileIn( 'thumb.php-start' );

$wgTrivialMimeDetection = true; //don't use fancy mime detection, just check the file extension for jpg/gif/png.

require_once( "$IP/includes/StreamFile.php" );

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

// Stream the file if it exists already
try {
	$img = wfLocalFile( $fileName );
	if ( $img && false != ( $thumbName = $img->thumbName( $params ) ) ) {
		$thumbPath = $img->getThumbPath( $thumbName );

		if ( is_file( $thumbPath ) ) {
			wfStreamFile( $thumbPath );
			wfLogProfilingData();
			exit;
		}
	}
} catch ( MWException $e ) {
	thumbInternalError( $e->getHTML() );
	wfLogProfilingData();
	exit;
}

wfProfileOut( 'thumb.php-start' );
wfProfileIn( 'thumb.php-render' );

try {
	if ( $img ) {
		$thumb = $img->transform( $params, File::RENDER_NOW );
	} else {
		$thumb = false;
	}
} catch( Exception $ex ) {
	// Tried to select a page on a non-paged file?
	$thumb = false;
}

$errorMsg = false;
if ( !$img ) {
	$errorMsg = wfMsg( 'badtitletext' );
} elseif ( !$thumb ) {
	$errorMsg = wfMsgHtml( 'thumbnail_error', 'File::transform() returned false' );
} elseif ( $thumb->isError() ) {
	$errorMsg = $thumb->getHtmlMsg();
} elseif ( !$thumb->getPath() ) {
	$errorMsg = wfMsgHtml( 'thumbnail_error', 'No path supplied in thumbnail object' );
} elseif ( $thumb->getPath() == $img->getPath() ) {
	$errorMsg = wfMsgHtml( 'thumbnail_error', 'Image was not scaled, ' .
		'is the requested width bigger than the source?' );
} else {
	wfStreamFile( $thumb->getPath() );
}
if ( $errorMsg !== false ) {
	thumbInternalError( $errorMsg );
}

wfProfileOut( 'thumb.php-render' );
wfProfileOut( 'thumb.php' );
wfLogProfilingData();

//--------------------------------------------------------------------------

function thumbInternalError( $msg ) {
	header( 'Cache-Control: no-cache' );
	header( 'Content-Type: text/html; charset=utf-8' );
	header( 'HTTP/1.1 500 Internal server error' );
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
}


