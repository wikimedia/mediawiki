<?php
/**
 * PHP script to stream out an image thumbnail.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup Media
 */

define( 'MW_NO_OUTPUT_COMPRESSION', 1 );
if ( isset( $_SERVER['MW_COMPILED'] ) ) {
	require( 'core/includes/WebStart.php' );
} else {
	require( __DIR__ . '/includes/WebStart.php' );
}

// Don't use fancy mime detection, just check the file extension for jpg/gif/png
$wgTrivialMimeDetection = true;

if ( defined( 'THUMB_HANDLER' ) ) {
	// Called from thumb_handler.php via 404; extract params from the URI...
	wfThumbHandle404();
} else {
	// Called directly, use $_REQUEST params
	wfThumbHandleRequest();
}
wfLogProfilingData();

//--------------------------------------------------------------------------

/**
 * Handle a thumbnail request via query parameters
 *
 * @return void
 */
function wfThumbHandleRequest() {
	$params = get_magic_quotes_gpc()
		? array_map( 'stripslashes', $_REQUEST )
		: $_REQUEST;

	wfStreamThumb( $params ); // stream the thumbnail
}

/**
 * Handle a thumbnail request via thumbnail file URL
 *
 * @return void
 */
function wfThumbHandle404() {
	# lighttpd puts the original request in REQUEST_URI, while sjs sets
	# that to the 404 handler, and puts the original request in REDIRECT_URL.
	if ( isset( $_SERVER['REDIRECT_URL'] ) ) {
		# The URL is un-encoded, so put it back how it was
		$uriPath = str_replace( "%2F", "/", urlencode( $_SERVER['REDIRECT_URL'] ) );
	} else {
		$uriPath = $_SERVER['REQUEST_URI'];
	}
	# Just get the URI path (REDIRECT_URL/REQUEST_URI is either a full URL or a path)
	if ( substr( $uriPath, 0, 1 ) !== '/' ) {
		$bits = wfParseUrl( $uriPath );
		if ( $bits && isset( $bits['path'] ) ) {
			$uriPath = $bits['path'];
		} else {
			wfThumbError( 404, 'The source file for the specified thumbnail does not exist.' );
			return;
		}
	}

	$params = wfExtractThumbParams( $uriPath ); // basic wiki URL param extracting
	if ( $params == null ) {
		wfThumbError( 404, 'The source file for the specified thumbnail does not exist.' );
		return;
	}

	wfStreamThumb( $params ); // stream the thumbnail
}

/**
 * Stream a thumbnail specified by parameters
 *
 * @param $params Array
 * @return void
 */
function wfStreamThumb( array $params ) {
	global $wgVaryOnXFP;
	wfProfileIn( __METHOD__ );

	$headers = array(); // HTTP headers to send

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
	unset( $params['r'] ); // ignore 'r' because we unconditionally pass File::RENDER

	// Is this a thumb of an archived file?
	$isOld = ( isset( $params['archived'] ) && $params['archived'] );
	unset( $params['archived'] ); // handlers don't care

	// Is this a thumb of a temp file?
	$isTemp = ( isset( $params['temp'] ) && $params['temp'] );
	unset( $params['temp'] ); // handlers don't care

	// Some basic input validation
	$fileName = strtr( $fileName, '\\/', '__' );

	// Actually fetch the image. Method depends on whether it is archived or not.
	if ( $isTemp ) {
		$repo = RepoGroup::singleton()->getLocalRepo()->getTempRepo();
		$img = new UnregisteredLocalFile( null, $repo,
			# Temp files are hashed based on the name without the timestamp.
			# The thumbnails will be hashed based on the entire name however.
			# @TODO: fix this convention to actually be reasonable.
			$repo->getZonePath( 'public' ) . '/' . $repo->getTempHashPath( $fileName ) . $fileName
		);
	} elseif ( $isOld ) {
		// Format is <timestamp>!<name>
		$bits = explode( '!', $fileName, 2 );
		if ( count( $bits ) != 2 ) {
			wfThumbError( 404, wfMessage( 'badtitletext' )->text() );
			wfProfileOut( __METHOD__ );
			return;
		}
		$title = Title::makeTitleSafe( NS_FILE, $bits[1] );
		if ( !$title ) {
			wfThumbError( 404, wfMessage( 'badtitletext' )->text() );
			wfProfileOut( __METHOD__ );
			return;
		}
		$img = RepoGroup::singleton()->getLocalRepo()->newFromArchiveName( $title, $fileName );
	} else {
		$img = wfLocalFile( $fileName );
	}

	// Check permissions if there are read restrictions
	$varyHeader = array();
	if ( !in_array( 'read', User::getGroupPermissions( array( '*' ) ), true ) ) {
		if ( !$img->getTitle() || !$img->getTitle()->userCan( 'read' ) ) {
			wfThumbError( 403, 'Access denied. You do not have permission to access ' .
				'the source file.' );
			wfProfileOut( __METHOD__ );
			return;
		}
		$headers[] = 'Cache-Control: private';
		$varyHeader[] = 'Cookie';
	}

	// Check the source file storage path
	if ( !$img ) {
		wfThumbError( 404, wfMessage( 'badtitletext' )->text() );
		wfProfileOut( __METHOD__ );
		return;
	}
	if ( !$img->exists() ) {
		wfThumbError( 404, 'The source file for the specified thumbnail does not exist.' );
		wfProfileOut( __METHOD__ );
		return;
	}
	$sourcePath = $img->getPath();
	if ( $sourcePath === false ) {
		wfThumbError( 500, 'The source file is not locally accessible.' );
		wfProfileOut( __METHOD__ );
		return;
	}

	// Check IMS against the source file
	// This means that clients can keep a cached copy even after it has been deleted on the server
	if ( !empty( $_SERVER['HTTP_IF_MODIFIED_SINCE'] ) ) {
		// Fix IE brokenness
		$imsString = preg_replace( '/;.*$/', '', $_SERVER["HTTP_IF_MODIFIED_SINCE"] );
		// Calculate time
		wfSuppressWarnings();
		$imsUnix = strtotime( $imsString );
		wfRestoreWarnings();
		$sourceTsUnix = wfTimestamp( TS_UNIX, $img->getTimestamp() );
		if ( $sourceTsUnix <= $imsUnix ) {
			header( 'HTTP/1.1 304 Not Modified' );
			wfProfileOut( __METHOD__ );
			return;
		}
	}

	$thumbName = $img->thumbName( $params );
	if ( !strlen( $thumbName ) ) { // invalid params?
		wfThumbError( 400, 'The specified thumbnail parameters are not valid.' );
		wfProfileOut( __METHOD__ );
		return;
	}

	$disposition = $img->getThumbDisposition( $thumbName );
	$headers[] = "Content-Disposition: $disposition";

	// Stream the file if it exists already...
	try {
		$thumbName2 = $img->thumbName( $params, File::THUMB_FULL_NAME ); // b/c; "long" style
		// For 404 handled thumbnails, we only use the the base name of the URI
		// for the thumb params and the parent directory for the source file name.
		// Check that the zone relative path matches up so squid caches won't pick
		// up thumbs that would not be purged on source file deletion (bug 34231).
		if ( isset( $params['rel404'] ) ) { // thumbnail was handled via 404
			if ( urldecode( $params['rel404'] ) === $img->getThumbRel( $thumbName ) ) {
				// Request for the canonical thumbnail name
			} elseif ( urldecode( $params['rel404'] ) === $img->getThumbRel( $thumbName2 ) ) {
				// Request for the "long" thumbnail name; redirect to canonical name
				$response = RequestContext::getMain()->getRequest()->response();
				$response->header( "HTTP/1.1 301 " . HttpStatus::getMessage( 301 ) );
				$response->header( 'Location: ' . wfExpandUrl( $img->getThumbUrl( $thumbName ), PROTO_CURRENT ) );
				$response->header( 'Expires: ' .
					gmdate( 'D, d M Y H:i:s', time() + 7*86400 ) . ' GMT' );
				if ( $wgVaryOnXFP ) {
					$varyHeader[] = 'X-Forwarded-Proto';
				}
				$response->header( 'Vary: ' . implode( ', ', $varyHeader ) );
				wfProfileOut( __METHOD__ );
				return;
			} else {
				wfThumbError( 404, 'The given path of the specified thumbnail is incorrect.' );
				wfProfileOut( __METHOD__ );
				return;
			}
		}
		$thumbPath = $img->getThumbPath( $thumbName );
		if ( $img->getRepo()->fileExists( $thumbPath ) ) {
			$headers[] = 'Vary: ' . implode( ', ', $varyHeader );
			$img->getRepo()->streamFile( $thumbPath, $headers );
			wfProfileOut( __METHOD__ );
			return;
		}
	} catch ( MWException $e ) {
		wfThumbError( 500, $e->getHTML() );
		wfProfileOut( __METHOD__ );
		return;
	}
	$headers[] = 'Vary: ' . implode( ', ', $varyHeader );

	// Thumbnail isn't already there, so create the new thumbnail...
	try {
		$thumb = $img->transform( $params, File::RENDER_NOW );
	} catch ( Exception $ex ) {
		// Tried to select a page on a non-paged file?
		$thumb = false;
	}

	// Check for thumbnail generation errors...
	$errorMsg = false;
	$msg = wfMessage( 'thumbnail_error' );
	if ( !$thumb ) {
		$errorMsg = $msg->rawParams( 'File::transform() returned false' )->escaped();
	} elseif ( $thumb->isError() ) {
		$errorMsg = $thumb->getHtmlMsg();
	} elseif ( !$thumb->hasFile() ) {
		$errorMsg = $msg->rawParams( 'No path supplied in thumbnail object' )->escaped();
	} elseif ( $thumb->fileIsSource() ) {
		$errorMsg = $msg->
			rawParams( 'Image was not scaled, is the requested width bigger than the source?' )->escaped();
	}

	if ( $errorMsg !== false ) {
		wfThumbError( 500, $errorMsg );
	} else {
		// Stream the file if there were no errors
		$thumb->streamFile( $headers );
	}

	wfProfileOut( __METHOD__ );
}

/**
 * Extract the required params for thumb.php from the thumbnail request URI.
 * At least 'width' and 'f' should be set if the result is an array.
 *
 * @param $uriPath String Thumbnail request URI path
 * @return Array|null associative params array or null
 */
function wfExtractThumbParams( $uriPath ) {
	$repo = RepoGroup::singleton()->getLocalRepo();

	// Zone URL might be relative ("/images") or protocol-relative ("//lang.site/image")
	$zoneUriPath = $repo->getZoneHandlerUrl( 'thumb' )
		? $repo->getZoneHandlerUrl( 'thumb' ) // custom URL
		: $repo->getZoneUrl( 'thumb' ); // default to main URL
	$bits = wfParseUrl( wfExpandUrl( $zoneUriPath, PROTO_INTERNAL ) );
	if ( $bits && isset( $bits['path'] ) ) {
		$zoneUriPath = $bits['path'];
	} else {
		return null; // not a valid thumbnail URL
	}

	$hashDirReg = $subdirReg = '';
	for ( $i = 0; $i < $repo->getHashLevels(); $i++ ) {
		$subdirReg .= '[0-9a-f]';
		$hashDirReg .= "$subdirReg/";
	}
	$zoneReg = preg_quote( $zoneUriPath ); // regex for thumb zone URI

	// Check if this is a thumbnail of an original in the local file repo
	if ( preg_match( "!^$zoneReg/((archive/)?$hashDirReg([^/]*)/([^/]*))$!", $uriPath, $m ) ) {
		list( /*all*/, $rel, $archOrTemp, $filename, $thumbname ) = $m;
	// Check if this is a thumbnail of an temp file in the local file repo
	} elseif ( preg_match( "!^$zoneReg/(temp/)($hashDirReg([^/]*)/([^/]*))$!", $uriPath, $m ) ) {
		list( /*all*/, $archOrTemp, $rel, $filename, $thumbname ) = $m;
	} else {
		return null; // not a valid looking thumbnail request
	}

	$filename = urldecode( $filename );
	$thumbname = urldecode( $thumbname );

	$params = array( 'f' => $filename, 'rel404' => $rel );
	if ( $archOrTemp === 'archive/' ) {
		$params['archived'] = 1;
	} elseif ( $archOrTemp === 'temp/' ) {
		$params['temp'] = 1;
	}

	// Check if the parameters can be extracted from the thumbnail name...
	if ( preg_match( '!^(page(\d*)-)*(\d*)px-[^/]*$!', $thumbname, $matches ) ) {
		list( /* all */, $pagefull, $pagenum, $size ) = $matches;
		$params['width'] = $size;
		if ( $pagenum ) {
			$params['page'] = $pagenum;
		}
		return $params; // valid thumbnail URL
	// Hooks return false if they manage to *resolve* the parameters
	} elseif ( !wfRunHooks( 'ExtractThumbParameters', array( $thumbname, &$params ) ) ) {
		return $params; // valid thumbnail URL (via extension or config)
	}

	return null; // not a valid thumbnail URL
}

/**
 * Output a thumbnail generation error message
 *
 * @param $status integer
 * @param $msg string
 * @return void
 */
function wfThumbError( $status, $msg ) {
	global $wgShowHostnames;

	header( 'Cache-Control: no-cache' );
	header( 'Content-Type: text/html; charset=utf-8' );
	if ( $status == 404 ) {
		header( 'HTTP/1.1 404 Not found' );
	} elseif ( $status == 403 ) {
		header( 'HTTP/1.1 403 Forbidden' );
		header( 'Vary: Cookie' );
	} else {
		header( 'HTTP/1.1 500 Internal server error' );
	}
	if ( $wgShowHostnames ) {
		$url = htmlspecialchars( isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : '' );
		$hostname = htmlspecialchars( wfHostname() );
		$debug = "<!-- $url -->\n<!-- $hostname -->\n";
	} else {
		$debug = "";
	}
	echo <<<EOT
<html><head><title>Error generating thumbnail</title></head>
<body>
<h1>Error generating thumbnail</h1>
<p>
$msg
</p>
$debug
</body>
</html>

EOT;
}
