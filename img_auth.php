<?php
/**
 * Image download authorisation script
 *
 * To use, in LocalSettings.php set $wgUploadDirectory to point to a non-public
 * directory, and $wgUploadPath to point to this file. Also set $wgWhitelistRead
 * to an array of pages you want everyone to be able to access. Your server must
 * support PATH_INFO, CGI-based configurations generally don't.
 */
define( 'MW_NO_OUTPUT_COMPRESSION', 1 );
require_once( './includes/WebStart.php' );
wfProfileIn( 'img_auth.php' );
require_once( './includes/StreamFile.php' );

if( !isset( $_SERVER['PATH_INFO'] ) ) {
	wfDebugLog( 'img_auth', "missing PATH_INFO" );
	wfForbidden();
}

# Get filenames/directories
wfDebugLog( 'img_auth', "PATH_INFO is: " . $_SERVER['PATH_INFO'] );
$filename = realpath( $wgUploadDirectory . $_SERVER['PATH_INFO'] );
$realUploadDirectory = realpath( $wgUploadDirectory );
$imageName = $wgContLang->getNsText( NS_IMAGE ) . ":" . wfBaseName( $_SERVER['PATH_INFO'] );

# Check if the filename is in the correct directory
if ( substr( $filename, 0, strlen( $realUploadDirectory ) ) != $realUploadDirectory ) {
	wfDebugLog( 'img_auth', "requested path not in upload dir: $filename" );
	wfForbidden();
}

if ( is_array( $wgWhitelistRead ) && !in_array( $imageName, $wgWhitelistRead ) && !$wgUser->getID() ) {
	wfDebugLog( 'img_auth', "not logged in and requested file not in whitelist: $imageName" );
	wfForbidden();
}

if( !file_exists( $filename ) ) {
	wfDebugLog( 'img_auth', "requested file does not exist: $filename" );
	wfForbidden();
}
if( is_dir( $filename ) ) {
	wfDebugLog( 'img_auth', "requested file is a directory: $filename" );
	wfForbidden();
}

# Write file
wfDebugLog( 'img_auth', "streaming file: $filename" );
wfStreamFile( $filename );
wfLogProfilingData();

function wfForbidden() {
	header( 'HTTP/1.0 403 Forbidden' );
	print
"<html><body>
<h1>Access denied</h1>
<p>You need to log in to access files on this server</p>
</body></html>";
	wfLogProfilingData();
	exit;
}

?>
