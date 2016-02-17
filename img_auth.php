<?php
/**
 * Image authorisation script
 *
 * To use this, see https://www.mediawiki.org/wiki/Manual:Image_Authorization
 *
 * - Set $wgUploadDirectory to a non-public directory (not web accessible)
 * - Set $wgUploadPath to point to this file
 *
 * Optional Parameters
 *
 * - Set $wgImgAuthDetails = true if you want the reason the access was denied messages to
 *       be displayed instead of just the 403 error (doesn't work on IE anyway),
 *       otherwise it will only appear in error logs
 *
 *  For security reasons, you usually don't want your user to know *why* access was denied,
 *  just that it was. If you want to change this, you can set $wgImgAuthDetails to 'true'
 *  in localsettings.php and it will give the user the reason why access was denied.
 *
 * Your server needs to support PATH_INFO; CGI-based configurations usually don't.
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
 */

define( 'MW_NO_OUTPUT_COMPRESSION', 1 );
require __DIR__ . '/includes/WebStart.php';

# Set action base paths so that WebRequest::getPathInfo()
# recognizes the "X" as the 'title' in ../img_auth.php/X urls.
$wgArticlePath = false; # Don't let a "/*" article path clober our action path
$wgActionPaths = [ "$wgUploadPath/" ];

wfImageAuthMain();

$mediawiki = new MediaWiki();
$mediawiki->doPostOutputShutdown( 'fast' );

function wfImageAuthMain() {
	global $wgImgAuthUrlPathMap;

	$request = RequestContext::getMain()->getRequest();
	$publicWiki = in_array( 'read', User::getGroupPermissions( [ '*' ] ), true );

	// Get the requested file path (source file or thumbnail)
	$matches = WebRequest::getPathInfo();
	if ( !isset( $matches['title'] ) ) {
		wfForbidden( 'img-auth-accessdenied', 'img-auth-nopathinfo' );
		return;
	}
	$path = $matches['title'];
	if ( $path && $path[0] !== '/' ) {
		// Make sure $path has a leading /
		$path = "/" . $path;
	}

	// Check for bug 28235: QUERY_STRING overriding the correct extension
	$whitelist = [];
	$extension = FileBackend::extensionFromPath( $path, 'rawcase' );
	if ( $extension != '' ) {
		$whitelist[] = $extension;
	}
	if ( !$request->checkUrlExtension( $whitelist ) ) {
		return;
	}

	// Various extensions may have their own backends that need access.
	// Check if there is a special backend and storage base path for this file.
	foreach ( $wgImgAuthUrlPathMap as $prefix => $storageDir ) {
		$prefix = rtrim( $prefix, '/' ) . '/'; // implicit trailing slash
		if ( strpos( $path, $prefix ) === 0 ) {
			$be = FileBackendGroup::singleton()->backendFromPath( $storageDir );
			$filename = $storageDir . substr( $path, strlen( $prefix ) ); // strip prefix
			// Check basic user authorization
			if ( !RequestContext::getMain()->getUser()->isAllowed( 'read' ) ) {
				wfForbidden( 'img-auth-accessdenied', 'img-auth-noread', $path );
				return;
			}
			if ( $be->fileExists( [ 'src' => $filename ] ) ) {
				wfDebugLog( 'img_auth', "Streaming `" . $filename . "`." );
				$be->streamFile( [ 'src' => $filename ],
					[ 'Cache-Control: private', 'Vary: Cookie' ] );
			} else {
				wfForbidden( 'img-auth-accessdenied', 'img-auth-nofile', $path );
			}
			return;
		}
	}

	// Get the local file repository
	$repo = RepoGroup::singleton()->getRepo( 'local' );
	$zone = strstr( ltrim( $path, '/' ), '/', true );

	// Get the full file storage path and extract the source file name.
	// (e.g. 120px-Foo.png => Foo.png or page2-120px-Foo.png => Foo.png).
	// This only applies to thumbnails/transcoded, and each of them should
	// be under a folder that has the source file name.
	if ( $zone === 'thumb' || $zone === 'transcoded' ) {
		$name = wfBaseName( dirname( $path ) );
		$filename = $repo->getZonePath( $zone ) . substr( $path, strlen( "/" . $zone ) );
		// Check to see if the file exists
		if ( !$repo->fileExists( $filename ) ) {
			wfForbidden( 'img-auth-accessdenied', 'img-auth-nofile', $filename );
			return;
		}
	} else {
		$name = wfBaseName( $path ); // file is a source file
		$filename = $repo->getZonePath( 'public' ) . $path;
		// Check to see if the file exists and is not deleted
		$bits = explode( '!', $name, 2 );
		if ( substr( $path, 0, 9 ) === '/archive/' && count( $bits ) == 2 ) {
			$file = $repo->newFromArchiveName( $bits[1], $name );
		} else {
			$file = $repo->newFile( $name );
		}
		if ( !$file->exists() || $file->isDeleted( File::DELETED_FILE ) ) {
			wfForbidden( 'img-auth-accessdenied', 'img-auth-nofile', $filename );
			return;
		}
	}

	$headers = []; // extra HTTP headers to send

	if ( !$publicWiki ) {
		// For private wikis, run extra auth checks and set cache control headers
		$headers[] = 'Cache-Control: private';
		$headers[] = 'Vary: Cookie';

		$title = Title::makeTitleSafe( NS_FILE, $name );
		if ( !$title instanceof Title ) { // files have valid titles
			wfForbidden( 'img-auth-accessdenied', 'img-auth-badtitle', $name );
			return;
		}

		// Run hook for extension authorization plugins
		/** @var $result array */
		$result = null;
		if ( !Hooks::run( 'ImgAuthBeforeStream', [ &$title, &$path, &$name, &$result ] ) ) {
			wfForbidden( $result[0], $result[1], array_slice( $result, 2 ) );
			return;
		}

		// Check user authorization for this title
		// Checks Whitelist too
		if ( !$title->userCan( 'read' ) ) {
			wfForbidden( 'img-auth-accessdenied', 'img-auth-noread', $name );
			return;
		}
	}

	if ( $request->getCheck( 'download' ) ) {
		$headers[] = 'Content-Disposition: attachment';
	}

	// Stream the requested file
	wfDebugLog( 'img_auth', "Streaming `" . $filename . "`." );
	$repo->streamFile( $filename, $headers );
}

/**
 * Issue a standard HTTP 403 Forbidden header ($msg1-a message index, not a message) and an
 * error message ($msg2, also a message index), (both required) then end the script
 * subsequent arguments to $msg2 will be passed as parameters only for replacing in $msg2
 * @param string $msg1
 * @param string $msg2
 */
function wfForbidden( $msg1, $msg2 ) {
	global $wgImgAuthDetails;

	$args = func_get_args();
	array_shift( $args );
	array_shift( $args );
	$args = ( isset( $args[0] ) && is_array( $args[0] ) ) ? $args[0] : $args;

	$msgHdr = wfMessage( $msg1 )->escaped();
	$detailMsgKey = $wgImgAuthDetails ? $msg2 : 'badaccess-group0';
	$detailMsg = wfMessage( $detailMsgKey, $args )->escaped();

	wfDebugLog( 'img_auth',
		"wfForbidden Hdr: " . wfMessage( $msg1 )->inLanguage( 'en' )->text() . " Msg: " .
			wfMessage( $msg2, $args )->inLanguage( 'en' )->text()
	);

	HttpStatus::header( 403 );
	header( 'Cache-Control: no-cache' );
	header( 'Content-Type: text/html; charset=utf-8' );
	echo <<<ENDS
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8" />
<title>$msgHdr</title>
</head>
<body>
<h1>$msgHdr</h1>
<p>$detailMsg</p>
</body>
</html>
ENDS;
}
