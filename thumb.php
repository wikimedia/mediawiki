<?php
/**
 * The web entry point for retrieving media thumbnails, created by a MediaHandler
 * subclass or proxy request if FileRepo::getThumbProxyUrl is configured.
 *
 * This script may also resize an image on-demand, if it isn't found in the
 * configured FileBackend storage.
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
 * @ingroup Media
 */

use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MediaWikiServices;

define( 'MW_NO_OUTPUT_COMPRESSION', 1 );
// T241340: thumb.php is included by thumb_handler.php which already defined
// MW_ENTRY_POINT to 'thumb_handler'
if ( !defined( 'MW_ENTRY_POINT' ) ) {
	define( 'MW_ENTRY_POINT', 'thumb' );
}
require __DIR__ . '/includes/WebStart.php';

wfThumbMain();

function wfThumbMain() {
	global $wgTrivialMimeDetection, $wgRequest;

	// Don't use fancy MIME detection, just check the file extension for jpg/gif/png
	$wgTrivialMimeDetection = true;

	if ( defined( 'THUMB_HANDLER' ) ) {
		// Called from thumb_handler.php via 404; extract params from the URI...
		wfThumbHandle404();
	} else {
		// Called directly, use $_GET params
		wfStreamThumb( $wgRequest->getQueryValuesOnly() );
	}

	$mediawiki = new MediaWiki();
	$mediawiki->doPostOutputShutdown();
}

/**
 * Handle a thumbnail request via thumbnail file URL
 *
 * @return void
 */
function wfThumbHandle404() {
	global $wgThumbPath;

	if ( $wgThumbPath ) {
		$relPath = WebRequest::getRequestPathSuffix( $wgThumbPath );
	} else {
		// Determine the request path relative to the thumbnail zone base
		$repo = MediaWikiServices::getInstance()->getRepoGroup()->getLocalRepo();
		$baseUrl = $repo->getZoneUrl( 'thumb' );
		if ( substr( $baseUrl, 0, 1 ) === '/' ) {
			$basePath = $baseUrl;
		} else {
			$basePath = parse_url( $baseUrl, PHP_URL_PATH );
		}
		$relPath = WebRequest::getRequestPathSuffix( $basePath );
	}

	$params = wfExtractThumbRequestInfo( $relPath ); // basic wiki URL param extracting
	if ( $params == null ) {
		wfThumbError( 400, 'The specified thumbnail parameters are not recognized.' );
		return;
	}

	wfStreamThumb( $params ); // stream the thumbnail
}

/**
 * Stream a thumbnail specified by parameters
 *
 * @param array $params List of thumbnailing parameters. In addition to parameters
 *  passed to the MediaHandler, this may also includes the keys:
 *   f (for filename), archived (if archived file), temp (if temp file),
 *   w (alias for width), p (alias for page), r (ignored; historical),
 *   rel404 (path for render on 404 to verify hash path correct),
 *   thumbName (thumbnail name to potentially extract more parameters from
 *   e.g. 'lossy-page1-120px-Foo.tiff' would add page, lossy and width
 *   to the parameters)
 * @return void
 */
function wfStreamThumb( array $params ) {
	global $wgVaryOnXFP;
	$permissionManager = MediaWikiServices::getInstance()->getPermissionManager();

	$headers = []; // HTTP headers to send

	$fileName = $params['f'] ?? '';

	// Backwards compatibility parameters
	if ( isset( $params['w'] ) ) {
		$params['width'] = $params['w'];
		unset( $params['w'] );
	}
	if ( isset( $params['width'] ) && substr( $params['width'], -2 ) == 'px' ) {
		// strip the px (pixel) suffix, if found
		$params['width'] = substr( $params['width'], 0, -2 );
	}
	if ( isset( $params['p'] ) ) {
		$params['page'] = $params['p'];
	}

	// Is this a thumb of an archived file?
	$isOld = ( isset( $params['archived'] ) && $params['archived'] );
	unset( $params['archived'] ); // handlers don't care

	// Is this a thumb of a temp file?
	$isTemp = ( isset( $params['temp'] ) && $params['temp'] );
	unset( $params['temp'] ); // handlers don't care

	// Some basic input validation
	$fileName = strtr( $fileName, '\\/', '__' );
	$localRepo = MediaWikiServices::getInstance()->getRepoGroup()->getLocalRepo();

	// Actually fetch the image. Method depends on whether it is archived or not.
	if ( $isTemp ) {
		$repo = $localRepo->getTempRepo();
		$img = new UnregisteredLocalFile( null, $repo,
			# Temp files are hashed based on the name without the timestamp.
			# The thumbnails will be hashed based on the entire name however.
			# @todo fix this convention to actually be reasonable.
			$repo->getZonePath( 'public' ) . '/' . $repo->getTempHashPath( $fileName ) . $fileName
		);
	} elseif ( $isOld ) {
		// Format is <timestamp>!<name>
		$bits = explode( '!', $fileName, 2 );
		if ( count( $bits ) != 2 ) {
			wfThumbError( 404, wfMessage( 'badtitletext' )->parse() );
			return;
		}
		$title = Title::makeTitleSafe( NS_FILE, $bits[1] );
		if ( !$title ) {
			wfThumbError( 404, wfMessage( 'badtitletext' )->parse() );
			return;
		}
		$img = $localRepo->newFromArchiveName( $title, $fileName );
	} else {
		$img = $localRepo->newFile( $fileName );
	}

	// Check the source file title
	if ( !$img ) {
		wfThumbError( 404, wfMessage( 'badtitletext' )->parse() );
		return;
	}

	// Check permissions if there are read restrictions
	$varyHeader = [];
	if ( !in_array( 'read', $permissionManager->getGroupPermissions( [ '*' ] ), true ) ) {
		$user = RequestContext::getMain()->getUser();
		$imgTitle = $img->getTitle();

		if ( !$imgTitle || !$permissionManager->userCan( 'read', $user, $imgTitle ) ) {
			wfThumbError( 403, 'Access denied. You do not have permission to access ' .
				'the source file.' );
			return;
		}
		$headers[] = 'Cache-Control: private';
		$varyHeader[] = 'Cookie';
	}

	// Check if the file is hidden
	if ( $img->isDeleted( File::DELETED_FILE ) ) {
		wfThumbErrorText( 404, "The source file '$fileName' does not exist." );
		return;
	}

	// Do rendering parameters extraction from thumbnail name.
	if ( isset( $params['thumbName'] ) ) {
		$params = wfExtractThumbParams( $img, $params );
	}
	if ( $params == null ) {
		wfThumbError( 400, 'The specified thumbnail parameters are not recognized.' );
		return;
	}

	// Check the source file storage path
	if ( !$img->exists() ) {
		$redirectedLocation = false;
		if ( !$isTemp ) {
			// Check for file redirect
			// Since redirects are associated with pages, not versions of files,
			// we look for the most current version to see if its a redirect.
			$possRedirFile = $localRepo->findFile( $img->getName() );
			if ( $possRedirFile && $possRedirFile->getRedirected() !== null ) {
				$redirTarget = $possRedirFile->getName();
				$targetFile = $localRepo->newFile( Title::makeTitleSafe( NS_FILE, $redirTarget ) );
				if ( $targetFile->exists() ) {
					$newThumbName = $targetFile->thumbName( $params );
					if ( $isOld ) {
						/** @var array $bits */
						$newThumbUrl = $targetFile->getArchiveThumbUrl(
							$bits[0] . '!' . $targetFile->getName(), $newThumbName );
					} else {
						$newThumbUrl = $targetFile->getThumbUrl( $newThumbName );
					}
					$redirectedLocation = wfExpandUrl( $newThumbUrl, PROTO_CURRENT );
				}
			}
		}

		if ( $redirectedLocation ) {
			// File has been moved. Give redirect.
			$response = RequestContext::getMain()->getRequest()->response();
			$response->statusHeader( 302 );
			$response->header( 'Location: ' . $redirectedLocation );
			$response->header( 'Expires: ' .
				gmdate( 'D, d M Y H:i:s', time() + 12 * 3600 ) . ' GMT' );
			if ( $wgVaryOnXFP ) {
				$varyHeader[] = 'X-Forwarded-Proto';
			}
			if ( count( $varyHeader ) ) {
				$response->header( 'Vary: ' . implode( ', ', $varyHeader ) );
			}
			$response->header( 'Content-Length: 0' );
			return;
		}

		// If its not a redirect that has a target as a local file, give 404.
		wfThumbErrorText( 404, "The source file '$fileName' does not exist." );
		return;
	} elseif ( $img->getPath() === false ) {
		wfThumbErrorText( 400, "The source file '$fileName' is not locally accessible." );
		return;
	}

	// Check IMS against the source file
	// This means that clients can keep a cached copy even after it has been deleted on the server
	if ( !empty( $_SERVER['HTTP_IF_MODIFIED_SINCE'] ) ) {
		// Fix IE brokenness
		$imsString = preg_replace( '/;.*$/', '', $_SERVER["HTTP_IF_MODIFIED_SINCE"] );
		// Calculate time
		Wikimedia\suppressWarnings();
		$imsUnix = strtotime( $imsString );
		Wikimedia\restoreWarnings();
		if ( wfTimestamp( TS_UNIX, $img->getTimestamp() ) <= $imsUnix ) {
			HttpStatus::header( 304 );
			return;
		}
	}

	$rel404 = $params['rel404'] ?? null;
	unset( $params['r'] ); // ignore 'r' because we unconditionally pass File::RENDER
	unset( $params['f'] ); // We're done with 'f' parameter.
	unset( $params['rel404'] ); // moved to $rel404

	// Get the normalized thumbnail name from the parameters...
	try {
		$thumbName = $img->thumbName( $params );
		if ( !strlen( $thumbName ) ) { // invalid params?
			throw new MediaTransformInvalidParametersException(
				'Empty return from File::thumbName'
			);
		}
		$thumbName2 = $img->thumbName( $params, File::THUMB_FULL_NAME ); // b/c; "long" style
	} catch ( MediaTransformInvalidParametersException $e ) {
		wfThumbError(
			400,
			'The specified thumbnail parameters are not valid: ' . $e->getMessage()
		);
		return;
	} catch ( MWException $e ) {
		wfThumbError( 500, $e->getHTML(), 'Exception caught while extracting thumb name',
			[ 'exception' => $e ] );
		return;
	}

	// For 404 handled thumbnails, we only use the base name of the URI
	// for the thumb params and the parent directory for the source file name.
	// Check that the zone relative path matches up so CDN caches won't pick
	// up thumbs that would not be purged on source file deletion (T36231).
	if ( $rel404 !== null ) { // thumbnail was handled via 404
		if ( rawurldecode( $rel404 ) === $img->getThumbRel( $thumbName ) ) {
			// Request for the canonical thumbnail name
		} elseif ( rawurldecode( $rel404 ) === $img->getThumbRel( $thumbName2 ) ) {
			// Request for the "long" thumbnail name; redirect to canonical name
			$response = RequestContext::getMain()->getRequest()->response();
			$response->statusHeader( 301 );
			$response->header( 'Location: ' .
				wfExpandUrl( $img->getThumbUrl( $thumbName ), PROTO_CURRENT ) );
			$response->header( 'Expires: ' .
				gmdate( 'D, d M Y H:i:s', time() + 7 * 86400 ) . ' GMT' );
			if ( $wgVaryOnXFP ) {
				$varyHeader[] = 'X-Forwarded-Proto';
			}
			if ( count( $varyHeader ) ) {
				$response->header( 'Vary: ' . implode( ', ', $varyHeader ) );
			}
			return;
		} else {
			wfThumbErrorText( 404, "The given path of the specified thumbnail is incorrect;
				expected '" . $img->getThumbRel( $thumbName ) . "' but got '" .
				rawurldecode( $rel404 ) . "'." );
			return;
		}
	}

	$dispositionType = isset( $params['download'] ) ? 'attachment' : 'inline';

	// Suggest a good name for users downloading this thumbnail
	$headers[] =
		"Content-Disposition: {$img->getThumbDisposition( $thumbName, $dispositionType )}";

	if ( count( $varyHeader ) ) {
		$headers[] = 'Vary: ' . implode( ', ', $varyHeader );
	}

	// Stream the file if it exists already...
	$thumbPath = $img->getThumbPath( $thumbName );
	if ( $img->getRepo()->fileExists( $thumbPath ) ) {
		$starttime = microtime( true );
		$status = $img->getRepo()->streamFileWithStatus( $thumbPath, $headers );
		$streamtime = microtime( true ) - $starttime;

		if ( $status->isOK() ) {
			MediaWikiServices::getInstance()->getStatsdDataFactory()->timing(
				'media.thumbnail.stream', $streamtime
			);
		} else {
			wfThumbError( 500, 'Could not stream the file', null, [ 'file' => $thumbName,
				'path' => $thumbPath, 'error' => $status->getWikiText( false, false, 'en' ) ] );
		}
		return;
	}

	$user = RequestContext::getMain()->getUser();
	if ( !wfThumbIsStandard( $img, $params ) && $user->pingLimiter( 'renderfile-nonstandard' ) ) {
		wfThumbError( 429, wfMessage( 'actionthrottledtext' )->parse() );
		return;
	} elseif ( $user->pingLimiter( 'renderfile' ) ) {
		wfThumbError( 429, wfMessage( 'actionthrottledtext' )->parse() );
		return;
	}

	$thumbProxyUrl = $img->getRepo()->getThumbProxyUrl();

	if ( strlen( $thumbProxyUrl ) ) {
		wfProxyThumbnailRequest( $img, $thumbName );
		// No local fallback when in proxy mode
		return;
	} else {
		// Generate the thumbnail locally
		list( $thumb, $errorMsg ) = wfGenerateThumbnail( $img, $params, $thumbName, $thumbPath );
	}

	/** @var MediaTransformOutput|MediaTransformError|bool $thumb */

	// Check for thumbnail generation errors...
	$msg = wfMessage( 'thumbnail_error' );
	$errorCode = 500;

	if ( !$thumb ) {
		$errorMsg = $errorMsg ?: $msg->rawParams( 'File::transform() returned false' )->escaped();
		if ( $errorMsg instanceof MessageSpecifier &&
			$errorMsg->getKey() === 'thumbnail_image-failure-limit'
		) {
			$errorCode = 429;
		}
	} elseif ( $thumb->isError() ) {
		$errorMsg = $thumb->getHtmlMsg();
		$errorCode = $thumb->getHttpStatusCode();
	} elseif ( !$thumb->hasFile() ) {
		$errorMsg = $msg->rawParams( 'No path supplied in thumbnail object' )->escaped();
	} elseif ( $thumb->fileIsSource() ) {
		$errorMsg = $msg
			->rawParams( 'Image was not scaled, is the requested width bigger than the source?' )
			->escaped();
		$errorCode = 400;
	}

	if ( $errorMsg !== false ) {
		wfThumbError( $errorCode, $errorMsg, null, [ 'file' => $thumbName, 'path' => $thumbPath ] );
	} else {
		// Stream the file if there were no errors
		$status = $thumb->streamFileWithStatus( $headers );
		if ( !$status->isOK() ) {
			wfThumbError( 500, 'Could not stream the file', null, [
				'file' => $thumbName, 'path' => $thumbPath,
				'error' => $status->getWikiText( false, false, 'en' ) ] );
		}
	}
}

/**
 * Proxies thumbnail request to a service that handles thumbnailing
 *
 * @param File $img
 * @param string $thumbName
 */
function wfProxyThumbnailRequest( $img, $thumbName ) {
	$thumbProxyUrl = $img->getRepo()->getThumbProxyUrl();

	// Instead of generating the thumbnail ourselves, we proxy the request to another service
	$thumbProxiedUrl = $thumbProxyUrl . $img->getThumbRel( $thumbName );

	$req = MWHttpRequest::factory( $thumbProxiedUrl );
	$secret = $img->getRepo()->getThumbProxySecret();

	// Pass a secret key shared with the proxied service if any
	if ( strlen( $secret ) ) {
		$req->setHeader( 'X-Swift-Secret', $secret );
	}

	// Send request to proxied service
	$status = $req->execute();

	MediaWiki\HeaderCallback::warnIfHeadersSent();

	// Simply serve the response from the proxied service as-is
	header( 'HTTP/1.1 ' . $req->getStatus() );

	$headers = $req->getResponseHeaders();

	foreach ( $headers as $key => $values ) {
		foreach ( $values as $value ) {
			header( $key . ': ' . $value, false );
		}
	}

	echo $req->getContent();
}

/**
 * Actually try to generate a new thumbnail
 *
 * @param File $file
 * @param array $params
 * @param string $thumbName
 * @param string $thumbPath
 * @return array (MediaTransformOutput|bool, string|bool error message HTML)
 */
function wfGenerateThumbnail( File $file, array $params, $thumbName, $thumbPath ) {
	global $wgAttemptFailureEpoch;

	$cache = ObjectCache::getLocalClusterInstance();
	$key = $cache->makeKey(
		'attempt-failures',
		$wgAttemptFailureEpoch,
		$file->getRepo()->getName(),
		$file->getSha1(),
		md5( $thumbName )
	);

	// Check if this file keeps failing to render
	if ( $cache->get( $key ) >= 4 ) {
		return [ false, wfMessage( 'thumbnail_image-failure-limit', 4 ) ];
	}

	$done = false;
	// Record failures on PHP fatals in addition to caching exceptions
	register_shutdown_function( function () use ( $cache, &$done, $key ) {
		if ( !$done ) { // transform() gave a fatal
			// Randomize TTL to reduce stampedes
			$cache->incrWithInit( $key, $cache::TTL_HOUR + mt_rand( 0, 300 ) );
		}
	} );

	$thumb = false;
	$errorHtml = false;

	// guard thumbnail rendering with PoolCounter to avoid stampedes
	// expensive files use a separate PoolCounter config so it is possible
	// to set up a global limit on them
	if ( $file->isExpensiveToThumbnail() ) {
		$poolCounterType = 'FileRenderExpensive';
	} else {
		$poolCounterType = 'FileRender';
	}

	// Thumbnail isn't already there, so create the new thumbnail...
	try {
		$work = new PoolCounterWorkViaCallback( $poolCounterType, sha1( $file->getName() ),
			[
				'doWork' => function () use ( $file, $params ) {
					return $file->transform( $params, File::RENDER_NOW );
				},
				'doCachedWork' => function () use ( $file, $params, $thumbPath ) {
					// If the worker that finished made this thumbnail then use it.
					// Otherwise, it probably made a different thumbnail for this file.
					return $file->getRepo()->fileExists( $thumbPath )
						? $file->transform( $params, File::RENDER_NOW )
						: false; // retry once more in exclusive mode
				},
				'error' => function ( Status $status ) {
					return wfMessage( 'generic-pool-error' )->parse() . '<hr>' . $status->getHTML();
				}
			]
		);
		$result = $work->execute();
		if ( $result instanceof MediaTransformOutput ) {
			$thumb = $result;
		} elseif ( is_string( $result ) ) { // error
			$errorHtml = $result;
		}
	} catch ( Exception $e ) {
		// Tried to select a page on a non-paged file?
	}

	/** @noinspection PhpUnusedLocalVariableInspection */
	$done = true; // no PHP fatal occurred

	if ( !$thumb || $thumb->isError() ) {
		// Randomize TTL to reduce stampedes
		$cache->incrWithInit( $key, $cache::TTL_HOUR + mt_rand( 0, 300 ) );
	}

	return [ $thumb, $errorHtml ];
}

/**
 * Convert pathinfo type parameter, into normal request parameters
 *
 * So for example, if the request was redirected from
 * /w/images/thumb/a/ab/Foo.png/120px-Foo.png. The $thumbRel parameter
 * of this function would be set to "a/ab/Foo.png/120px-Foo.png".
 * This method is responsible for turning that into an array
 * with the following keys:
 *  * f => the filename (Foo.png)
 *  * rel404 => the whole thing (a/ab/Foo.png/120px-Foo.png)
 *  * archived => 1 (If the request is for an archived thumb)
 *  * temp => 1 (If the file is in the "temporary" zone)
 *  * thumbName => the thumbnail name, including parameters (120px-Foo.png)
 *
 * Transform specific parameters are set later via wfExtractThumbParams().
 *
 * @param string $thumbRel Thumbnail path relative to the thumb zone
 * @return array|null Associative params array or null
 */
function wfExtractThumbRequestInfo( $thumbRel ) {
	$repo = MediaWikiServices::getInstance()->getRepoGroup()->getLocalRepo();

	$hashDirReg = $subdirReg = '';
	$hashLevels = $repo->getHashLevels();
	for ( $i = 0; $i < $hashLevels; $i++ ) {
		$subdirReg .= '[0-9a-f]';
		$hashDirReg .= "$subdirReg/";
	}

	// Check if this is a thumbnail of an original in the local file repo
	if ( preg_match( "!^((archive/)?$hashDirReg([^/]*)/([^/]*))$!", $thumbRel, $m ) ) {
		list( /*all*/, $rel, $archOrTemp, $filename, $thumbname ) = $m;
	// Check if this is a thumbnail of an temp file in the local file repo
	} elseif ( preg_match( "!^(temp/)($hashDirReg([^/]*)/([^/]*))$!", $thumbRel, $m ) ) {
		list( /*all*/, $archOrTemp, $rel, $filename, $thumbname ) = $m;
	} else {
		return null; // not a valid looking thumbnail request
	}

	$params = [ 'f' => $filename, 'rel404' => $rel ];
	if ( $archOrTemp === 'archive/' ) {
		$params['archived'] = 1;
	} elseif ( $archOrTemp === 'temp/' ) {
		$params['temp'] = 1;
	}

	$params['thumbName'] = $thumbname;
	return $params;
}

/**
 * Convert a thumbnail name (122px-foo.png) to parameters, using
 * file handler.
 *
 * @param File $file File object for file in question
 * @param array $params Array of parameters so far
 * @return array Parameters array with more parameters
 */
function wfExtractThumbParams( $file, $params ) {
	if ( !isset( $params['thumbName'] ) ) {
		throw new InvalidArgumentException( "No thumbnail name passed to wfExtractThumbParams" );
	}

	$thumbname = $params['thumbName'];
	unset( $params['thumbName'] );

	// FIXME: Files in the temp zone don't set a MIME type, which means
	// they don't have a handler. Which means we can't parse the param
	// string. However, not a big issue as what good is a param string
	// if you have no handler to make use of the param string and
	// actually generate the thumbnail.
	$handler = $file->getHandler();

	// Based on UploadStash::parseKey
	$fileNamePos = strrpos( $thumbname, $params['f'] );
	if ( $fileNamePos === false ) {
		// Maybe using a short filename? (see FileRepo::nameForThumb)
		$fileNamePos = strrpos( $thumbname, 'thumbnail' );
	}

	if ( $handler && $fileNamePos !== false ) {
		$paramString = substr( $thumbname, 0, $fileNamePos - 1 );
		$extraParams = $handler->parseParamString( $paramString );
		if ( $extraParams !== false ) {
			return $params + $extraParams;
		}
	}

	// As a last ditch fallback, use the traditional common parameters
	if ( preg_match( '!^(page(\d*)-)*(\d*)px-[^/]*$!', $thumbname, $matches ) ) {
		list( /* all */, /* pagefull */, $pagenum, $size ) = $matches;
		$params['width'] = $size;
		if ( $pagenum ) {
			$params['page'] = $pagenum;
		}
		return $params; // valid thumbnail URL
	}
	return null;
}

/**
 * Output a thumbnail generation error message
 *
 * @param int $status
 * @param string $msgText Plain text (will be html escaped)
 * @return void
 */
function wfThumbErrorText( $status, $msgText ) {
	wfThumbError( $status, htmlspecialchars( $msgText, ENT_NOQUOTES ) );
}

/**
 * Output a thumbnail generation error message
 *
 * @param int $status
 * @param string $msgHtml HTML
 * @param string|null $msgText Short error description, for internal logging. Defaults to $msgHtml.
 *   Only used for HTTP 500 errors.
 * @param array $context Error context, for internal logging. Only used for HTTP 500 errors.
 * @return void
 */
function wfThumbError( $status, $msgHtml, $msgText = null, $context = [] ) {
	global $wgShowHostnames;

	MediaWiki\HeaderCallback::warnIfHeadersSent();

	if ( headers_sent() ) {
		LoggerFactory::getInstance( 'thumbnail' )->error(
			'Error after output had been started. Output may be corrupt or truncated. ' .
			'Original error: ' . ( $msgText ?: $msgHtml ) . " (Status $status)",
			$context
		);
		return;
	}

	header( 'Cache-Control: no-cache' );
	header( 'Content-Type: text/html; charset=utf-8' );
	if ( $status == 400 || $status == 404 || $status == 429 ) {
		HttpStatus::header( $status );
	} elseif ( $status == 403 ) {
		HttpStatus::header( 403 );
		header( 'Vary: Cookie' );
	} else {
		LoggerFactory::getInstance( 'thumbnail' )->error( $msgText ?: $msgHtml, $context );
		HttpStatus::header( 500 );
	}
	if ( $wgShowHostnames ) {
		header( 'X-MW-Thumbnail-Renderer: ' . wfHostname() );
		$url = htmlspecialchars(
			$_SERVER['REQUEST_URI'] ?? '',
			ENT_NOQUOTES
		);
		$hostname = htmlspecialchars( wfHostname(), ENT_NOQUOTES );
		$debug = "<!-- $url -->\n<!-- $hostname -->\n";
	} else {
		$debug = '';
	}
	$content = <<<EOT
<!DOCTYPE html>
<html><head>
<meta charset="UTF-8" />
<title>Error generating thumbnail</title>
</head>
<body>
<h1>Error generating thumbnail</h1>
<p>
$msgHtml
</p>
$debug
</body>
</html>

EOT;
	header( 'Content-Length: ' . strlen( $content ) );
	echo $content;
}
