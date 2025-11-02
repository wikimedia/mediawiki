<?php
/**
 * Entry point implementation for serving non-public images to logged-in users.
 *
 * @see /img_auth.php The web entry point.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup entrypoint
 */

namespace MediaWiki\FileRepo;

use MediaWiki\FileRepo\File\File;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Html\TemplateParser;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiEntryPoint;
use MediaWiki\Request\ContentSecurityPolicy;
use MediaWiki\Title\Title;
use Wikimedia\FileBackend\HTTPFileStreamer;
use Wikimedia\Message\MessageParam;
use Wikimedia\Message\MessageSpecifier;

class AuthenticatedFileEntryPoint extends MediaWikiEntryPoint {

	/**
	 * Main entry point
	 */
	public function execute() {
		$services = $this->getServiceContainer();
		$permissionManager = $services->getPermissionManager();

		$request = $this->getRequest();
		$publicWiki = $services->getGroupPermissionsLookup()->groupHasPermission( '*', 'read' );

		// Find the path assuming the request URL is relative to the local public zone URL
		$baseUrl = $services->getRepoGroup()->getLocalRepo()->getZoneUrl( 'public' );
		if ( $baseUrl[0] === '/' ) {
			$basePath = $baseUrl;
		} else {
			$basePath = parse_url( $baseUrl, PHP_URL_PATH );
		}
		$path = $this->getRequestPathSuffix( "$basePath" );

		if ( $path === false ) {
			// Try instead assuming img_auth.php is the base path
			$basePath = $this->getConfig( MainConfigNames::ImgAuthPath )
				?: $this->getConfig( MainConfigNames::ScriptPath ) . '/img_auth.php';
			$path = $this->getRequestPathSuffix( $basePath );
		}

		if ( $path === false ) {
			$this->forbidden( 'img-auth-accessdenied', 'img-auth-notindir' );
			return;
		}

		if ( $path === '' || $path[0] !== '/' ) {
			// Make sure $path has a leading /
			$path = "/" . $path;
		}

		$user = $this->getContext()->getUser();

		// Various extensions may have their own backends that need access.
		// Check if there is a special backend and storage base path for this file.
		$pathMap = $this->getConfig( MainConfigNames::ImgAuthUrlPathMap );
		foreach ( $pathMap as $prefix => $storageDir ) {
			$prefix = rtrim( $prefix, '/' ) . '/'; // implicit trailing slash
			if ( str_starts_with( $path, $prefix ) ) {
				$be = $services->getFileBackendGroup()->backendFromPath( $storageDir );
				$filename = $storageDir . substr( $path, strlen( $prefix ) ); // strip prefix
				// Check basic user authorization
				$isAllowedUser = $permissionManager->userHasRight( $user, 'read' );
				if ( !$isAllowedUser ) {
					$this->forbidden( 'img-auth-accessdenied', 'img-auth-noread', $path );
					return;
				}
				if ( $be && $be->fileExists( [ 'src' => $filename ] ) ) {
					wfDebugLog( 'img_auth', "Streaming `" . $filename . "`." );
					$be->streamFile( [
						'src' => $filename,
						'headers' => [ 'Cache-Control: private', 'Vary: Cookie' ]
					] );
				} else {
					$this->forbidden( 'img-auth-accessdenied', 'img-auth-nofile', $path );
				}

				return;
			}
		}

		// Get the local file repository
		$repo = $services->getRepoGroup()->getLocalRepo();
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
				$this->forbidden( 'img-auth-accessdenied', 'img-auth-nofile', $filename );
				return;
			}
		} else {
			$name = wfBaseName( $path ); // file is a source file
			$filename = $repo->getZonePath( 'public' ) . $path;
			// Check to see if the file exists and is not deleted
			$bits = explode( '!', $name, 2 );
			if ( str_starts_with( $path, '/archive/' ) && count( $bits ) == 2 ) {
				$file = $repo->newFromArchiveName( $bits[1], $name );
			} else {
				$file = $repo->newFile( $name );
			}
			if ( !$file || !$file->exists() || $file->isDeleted( File::DELETED_FILE ) ) {
				$this->forbidden( 'img-auth-accessdenied', 'img-auth-nofile', $filename );
				return;
			}
		}

		$headers = []; // extra HTTP headers to send

		$title = Title::makeTitleSafe( NS_FILE, $name );

		$hookRunner = new HookRunner( $services->getHookContainer() );
		if ( !$publicWiki ) {
			// For private wikis, run extra auth checks and set cache control headers
			$headers['Cache-Control'] = 'private';
			$headers['Vary'] = 'Cookie';

			if ( !$title instanceof Title ) { // files have valid titles
				$this->forbidden( 'img-auth-accessdenied', 'img-auth-badtitle', $name );
				return;
			}

			// Run hook for extension authorization plugins
			$authResult = [];
			if ( !$hookRunner->onImgAuthBeforeStream( $title, $path, $name, $authResult ) ) {
				$this->forbidden( $authResult[0], $authResult[1], array_slice( $authResult, 2 ) );
				return;
			}

			if ( !$permissionManager->userCan( 'read', $user, $title ) ) {
				$this->forbidden( 'img-auth-accessdenied', 'img-auth-noread', $name );
				return;
			}
		}

		$range = $this->environment->getServerInfo( 'HTTP_RANGE' );
		$ims = $this->environment->getServerInfo( 'HTTP_IF_MODIFIED_SINCE' );

		if ( $range !== null ) {
			$headers['Range'] = $range;
		}
		if ( $ims !== null ) {
			$headers['If-Modified-Since'] = $ims;
		}

		if ( $request->getCheck( 'download' ) ) {
			$headers['Content-Disposition'] = 'attachment';
		}

		$cspHeader = ContentSecurityPolicy::getMediaHeader( $filename );
		if ( $cspHeader ) {
			$headers['Content-Security-Policy'] = $cspHeader;
		}

		// Allow modification of headers before streaming a file
		$hookRunner->onImgAuthModifyHeaders( $title->getTitleValue(), $headers );

		// Stream the requested file
		$this->prepareForOutput();

		[ $headers, $options ] = HTTPFileStreamer::preprocessHeaders( $headers );
		wfDebugLog( 'img_auth', "Streaming `" . $filename . "`." );
		$repo->streamFileWithStatus( $filename, $headers, $options );

		$this->enterPostSendMode();
	}

	/**
	 * Issue a standard HTTP 403 Forbidden header ($msg1-a message index, not a message) and an
	 * error message ($msg2, also a message index), (both required) then end the script
	 * subsequent arguments to $msg2 will be passed as parameters only for replacing in $msg2
	 *
	 * @param string $msg1
	 * @param string $msg2
	 * @phpcs:ignore Generic.Files.LineLength
	 * @param MessageParam|MessageSpecifier|string|int|float|list<MessageParam|MessageSpecifier|string|int|float> ...$args
	 *   See Message::params()
	 */
	private function forbidden( $msg1, $msg2, ...$args ) {
		$args = ( isset( $args[0] ) && is_array( $args[0] ) ) ? $args[0] : $args;
		$context = $this->getContext();

		$msgHdr = $context->msg( $msg1 )->text();
		$detailMsg = $this->getConfig( MainConfigNames::ImgAuthDetails )
			? $context->msg( $msg2, $args )->text()
			: $context->msg( 'badaccess-group0' )->text();

		wfDebugLog(
			'img_auth',
			"wfForbidden Hdr: " . $context->msg( $msg1 )->inLanguage( 'en' )->text()
				. " Msg: " . $context->msg( $msg2, $args )->inLanguage( 'en' )->text()
		);

		$this->status( 403 );
		$this->header( 'Cache-Control: no-cache' );
		$this->header( 'Content-Type: text/html; charset=utf-8' );
		$language = $context->getLanguage();
		$lang = $language->getHtmlCode();
		$this->header( "Content-Language: $lang" );
		$templateParser = new TemplateParser();
		$this->print(
			$templateParser->processTemplate( 'ImageAuthForbidden', [
				'dir' => $language->getDir(),
				'lang' => $lang,
				'msgHdr' => $msgHdr,
				'detailMsg' => $detailMsg,
			] )
		);
	}
}
