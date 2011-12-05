<?php

/**
 * Image authorisation script
 *
 * To use this, see http://www.mediawiki.org/wiki/Manual:Image_Authorization
 *
 * - Set $wgUploadDirectory to a non-public directory (not web accessible)
 * - Set $wgUploadPath to point to this file
 *
 * Optional Parameters
 *
 * - Set $wgImgAuthDetails = true if you want the reason the access was denied messages to
 *       be displayed instead of just the 403 error (doesn't work on IE anyway),
 *       otherwise it will only appear in error logs
 * - Set $wgImgAuthPublicTest false if you don't want to just check and see if all are public
 *       must be set to false if using specific restrictions such as LockDown or NSFileRepo
 *
 *  For security reasons, you usually don't want your user to know *why* access was denied,
 *  just that it was. If you want to change this, you can set $wgImgAuthDetails to 'true'
 *  in localsettings.php and it will give the user the reason why access was denied.
 *
 * Your server needs to support PATH_INFO; CGI-based configurations usually don't.
 *
 * @file
 *
 **/

define( 'MW_NO_OUTPUT_COMPRESSION', 1 );
if ( isset( $_SERVER['MW_COMPILED'] ) ) {
	require ( 'phase3/includes/WebStart.php' );
} else {
	require ( dirname( __FILE__ ) . '/includes/WebStart.php' );
}
wfProfileIn( 'img_auth.php' );

# Set action base paths so that WebRequest::getPathInfo()
# recognizes the "X" as the 'title' in ../image_auth/X urls.
$wgArticlePath = false; # Don't let a "/*" article path clober our action path
$wgActionPaths = array( "$wgUploadPath/" );

wfImageAuthMain();
wfLogProfilingData();

function wfImageAuthMain() {
	global $wgImgAuthPublicTest, $wgRequest, $wgUploadDirectory;

	// See if this is a public Wiki (no protections).
	if ( $wgImgAuthPublicTest
		&& in_array( 'read', User::getGroupPermissions( array( '*' ) ), true ) )
	{
		// This is a public wiki, so disable this script (for private wikis only)
		wfForbidden( 'img-auth-accessdenied', 'img-auth-public' );
		return;
	}

	// Get the requested file path (source file or thumbnail)
	$matches = WebRequest::getPathInfo();
	$path = $matches['title'];
	if ( $path && $path[0] !== '/' ) {
		// Make sure $path has a leading /
		$path = "/" . $path;
	}

	// Check for bug 28235: QUERY_STRING overriding the correct extension
	$whitelist = array();
	$dotPos = strrpos( $path, '.' );
	if ( $dotPos !== false ) {
		$whitelist[] = substr( $path, $dotPos + 1 );
	}
	if ( !$wgRequest->checkUrlExtension( $whitelist ) ) {
		return;
	}

	// Get the full file path
	$filename = realpath( $wgUploadDirectory . $path );
	$realUpload = realpath( $wgUploadDirectory );

	// Basic directory traversal check
	if ( substr( $filename, 0, strlen( $realUpload ) ) != $realUpload ) {
		wfForbidden( 'img-auth-accessdenied', 'img-auth-notindir' );
		return;
	}

	// Check to see if the file exists
	if ( !file_exists( $filename ) ) {
		wfForbidden( 'img-auth-accessdenied','img-auth-nofile', $filename );
		return;
	}
	
	// Check to see if tried to access a directory
	if ( is_dir( $filename ) ) {
		wfForbidden( 'img-auth-accessdenied','img-auth-isdir', $filename );
		return;
	}

	// Extract the file name and chop off the size specifier
	// (e.g. 120px-Foo.png => Foo.png or page2-120px-Foo.png => Foo.png).
	// This only applies to thumbnails, and all thumbnails should have
	// a width indicator and be under a folder that has the source file name.
	$name = wfBaseName( $path );
	if ( preg_match( '!(?:[^-]*-)*?\d+px-(.*)!i', $name ) ) {
		$name = wfBaseName( dirname( $path ) ); // this file is a thumbnail
	}

	$title = Title::makeTitleSafe( NS_FILE, $name );
	if ( !$title instanceof Title ) { // files have valid titles
		wfForbidden( 'img-auth-accessdenied', 'img-auth-badtitle', $name );
		return;
	}

	// Run hook for extension authorization plugins
	if ( !wfRunHooks( 'ImgAuthBeforeStream', array( &$title, &$path, &$name, &$result ) ) ) {
		wfForbidden( $result[0], $result[1], array_slice( $result, 2 ) );
		return;
	}
	
	// Check user authorization for this title
	// UserCanRead Checks Whitelist too
	if ( !$title->userCanRead() ) {
		wfForbidden( 'img-auth-accessdenied', 'img-auth-noread', $name );
		return;
	}

	// Stream the requested file
	wfDebugLog( 'img_auth', "Streaming `".$filename."`." );
	StreamFile::stream( $filename, array( 'Cache-Control: private', 'Vary: Cookie' ) );
}

/**
 * Issue a standard HTTP 403 Forbidden header ($msg1-a message index, not a message) and an
 * error message ($msg2, also a message index), (both required) then end the script
 * subsequent arguments to $msg2 will be passed as parameters only for replacing in $msg2
 * @param $msg1
 * @param $msg2
 */
function wfForbidden( $msg1, $msg2 ) {
	global $wgImgAuthDetails;

	$args = func_get_args();
	array_shift( $args );
	array_shift( $args );

	$msgHdr = htmlspecialchars( wfMsg( $msg1 ) );
	$detailMsgKey = $wgImgAuthDetails ? $msg2 : 'badaccess-group0';
	$detailMsg = htmlspecialchars( wfMsg( $detailMsgKey, $args ) );

	wfDebugLog( 'img_auth',
		"wfForbidden Hdr:" . wfMsgExt( $msg1, array( 'language' => 'en' ) ). " Msg: ".
		wfMsgExt( $msg2, array( 'language' => 'en' ), $args )
	);

	header( 'HTTP/1.0 403 Forbidden' );
	header( 'Cache-Control: no-cache' );
	header( 'Content-Type: text/html; charset=utf-8' );
	echo <<<ENDS
<html>
<body>
<h1>$msgHdr</h1>
<p>$detailMsg</p>
</body>
</html>
ENDS;
}
