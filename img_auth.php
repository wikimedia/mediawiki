<?php
/**
 * The web entry point for serving non-public images to logged-in users.
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
 * Your server needs to support REQUEST_URI or PATH_INFO; CGI-based
 * configurations sometimes don't.
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
 * @ingroup entrypoint
 */

define( 'MW_NO_OUTPUT_COMPRESSION', 1 );
define( 'MW_ENTRY_POINT', 'img_auth' );
require __DIR__ . '/includes/WebStart.php';

wfImageAuthMain();

$mediawiki = new MediaWiki();
$mediawiki->doPostOutputShutdown();

function wfImageAuthMain() {
	global $wgImgAuthUrlPathMap, $wgScriptPath, $wgImgAuthPath;

	$services = \MediaWiki\MediaWikiServices::getInstance();
	$permissionManager = $services->getPermissionManager();

	$request = RequestContext::getMain()->getRequest();
	$publicWiki = in_array( 'read', $permissionManager->getGroupPermissions( [ '*' ] ), true );

	// Find the path assuming the request URL is relative to the local public zone URL
	$baseUrl = $services->getRepoGroup()->getLocalRepo()->getZoneUrl( 'public' );
	if ( $baseUrl[0] === '/' ) {
		$basePath = $baseUrl;
	} else {
		$basePath = parse_url( $baseUrl, PHP_URL_PATH );
	}
	$path = WebRequest::getRequestPathSuffix( $basePath );

	if ( $path === false ) {
		// Try instead assuming img_auth.php is the base path
		$basePath = $wgImgAuthPath ?: "$wgScriptPath/img_auth.php";
		$path = WebRequest::getRequestPathSuffix( $basePath );
	}

	if ( $path === false ) {
		wfForbidden( 'img-auth-accessdenied', 'img-auth-notindir' );
		return;
	}

	if ( $path === '' || $path[0] !== '/' ) {
		// Make sure $path has a leading /
		$path = "/" . $path;
	}

	$user = RequestContext::getMain()->getUser();

	// Various extensions may have their own backends that need access.
	// Check if there is a special backend and storage base path for this file.
	foreach ( $wgImgAuthUrlPathMap as $prefix => $storageDir ) {
		$prefix = rtrim( $prefix, '/' ) . '/'; // implicit trailing slash
		if ( strpos( $path, $prefix ) === 0 ) {
			$be = $services->getFileBackendGroup()->backendFromPath( $storageDir );
			$filename = $storageDir . substr( $path, strlen( $prefix ) ); // strip prefix
			// Check basic user authorization
			$isAllowedUser = $permissionManager->userHasRight( $user, 'read' );
			if ( !$isAllowedUser ) {
				wfForbidden( 'img-auth-accessdenied', 'img-auth-noread', $path );
				return;
			}
			if ( $be->fileExists( [ 'src' => $filename ] ) ) {
				wfDebugLog( 'img_auth', "Streaming `" . $filename . "`." );
				$be->streamFile( [
					'src' => $filename,
					'headers' => [ 'Cache-Control: private', 'Vary: Cookie' ]
				] );
			} else {
				wfForbidden( 'img-auth-accessdenied', 'img-auth-nofile', $path );
			}
			return;
		}
	}

	// Get the local file repository
	$repo = $services->getRepoGroup()->getRepo( 'local' );
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

	$title = Title::makeTitleSafe( NS_FILE, $name );

	if ( !$publicWiki ) {
		// For private wikis, run extra auth checks and set cache control headers
		$headers['Cache-Control'] = 'private';
		$headers['Vary'] = 'Cookie';

		if ( !$title instanceof Title ) { // files have valid titles
			wfForbidden( 'img-auth-accessdenied', 'img-auth-badtitle', $name );
			return;
		}

		// Run hook for extension authorization plugins
		/** @var array $result */
		$result = null;
		if ( !Hooks::runner()->onImgAuthBeforeStream( $title, $path, $name, $result ) ) {
			wfForbidden( $result[0], $result[1], array_slice( $result, 2 ) );
			return;
		}

		// Check user authorization for this title
		// Checks Whitelist too

		if ( !$permissionManager->userCan( 'read', $user, $title ) ) {
			wfForbidden( 'img-auth-accessdenied', 'img-auth-noread', $name );
			return;
		}
	}

	if ( isset( $_SERVER['HTTP_RANGE'] ) ) {
		$headers['Range'] = $_SERVER['HTTP_RANGE'];
	}
	if ( isset( $_SERVER['HTTP_IF_MODIFIED_SINCE'] ) ) {
		$headers['If-Modified-Since'] = $_SERVER['HTTP_IF_MODIFIED_SINCE'];
	}

	if ( $request->getCheck( 'download' ) ) {
		$headers['Content-Disposition'] = 'attachment';
	}

	// Allow modification of headers before streaming a file
	Hooks::runner()->onImgAuthModifyHeaders( $title->getTitleValue(), $headers );

	// Stream the requested file
	list( $headers, $options ) = HTTPFileStreamer::preprocessHeaders( $headers );
	wfDebugLog( 'img_auth', "Streaming `" . $filename . "`." );
	$repo->streamFileWithStatus( $filename, $headers, $options );
}

/**
 * Issue a standard HTTP 403 Forbidden header ($msg1-a message index, not a message) and an
 * error message ($msg2, also a message index), (both required) then end the script
 * subsequent arguments to $msg2 will be passed as parameters only for replacing in $msg2
 * @param string $msg1
 * @param string $msg2
 * @param mixed ...$args To pass as params to wfMessage() with $msg2. Either variadic, or a single
 *   array argument.
 */
function wfForbidden( $msg1, $msg2, ...$args ) {
	global $wgImgAuthDetails;

	$args = ( isset( $args[0] ) && is_array( $args[0] ) ) ? $args[0] : $args;

	$msgHdr = wfMessage( $msg1 )->text();
	$detailMsgKey = $wgImgAuthDetails ? $msg2 : 'badaccess-group0';
	$detailMsg = wfMessage( $detailMsgKey, $args )->text();

	wfDebugLog( 'img_auth',
		"wfForbidden Hdr: " . wfMessage( $msg1 )->inLanguage( 'en' )->text() . " Msg: " .
			wfMessage( $msg2, $args )->inLanguage( 'en' )->text()
	);

	HttpStatus::header( 403 );
	header( 'Cache-Control: no-cache' );
	header( 'Content-Type: text/html; charset=utf-8' );
	$templateParser = new TemplateParser();
	echo $templateParser->processTemplate( 'ImageAuthForbidden', [
		'msgHdr' => $msgHdr,
		'detailMsg' => $detailMsg,
	] );
}
