<?php
/**
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

namespace MediaWiki\Specials;

use Exception;
use MediaWiki\Exception\HttpError;
use MediaWiki\FileRepo\File\File;
use MediaWiki\FileRepo\File\UnregisteredLocalFile;
use MediaWiki\FileRepo\LocalRepo;
use MediaWiki\FileRepo\RepoGroup;
use MediaWiki\Html\Html;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\Http\HttpRequestFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\Pager\UploadStashPager;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\SpecialPage\UnlistedSpecialPage;
use MediaWiki\Status\Status;
use MediaWiki\Utils\UrlUtils;
use SpecialUploadStashTooLargeException;
use UploadStash;
use UploadStashBadPathException;
use UploadStashFileNotFoundException;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * Web access for files temporarily stored by UploadStash.
 *
 * For example -- files that were uploaded with the UploadWizard extension are stored temporarily
 * before committing them to the db. But we want to see their thumbnails and get other information
 * about them.
 *
 * Since this is based on the user's session, in effect this creates a private temporary file area.
 * However, the URLs for the files cannot be shared.
 *
 * @ingroup SpecialPage
 * @ingroup Upload
 */
class SpecialUploadStash extends UnlistedSpecialPage {
	/** @var UploadStash|null */
	private $stash;

	private LocalRepo $localRepo;
	private HttpRequestFactory $httpRequestFactory;
	private UrlUtils $urlUtils;
	private IConnectionProvider $dbProvider;

	/**
	 * Since we are directly writing the file to STDOUT,
	 * we should not be reading in really big files and serving them out.
	 *
	 * We also don't want people using this as a file drop, even if they
	 * share credentials.
	 *
	 * This service is really for thumbnails and other such previews while
	 * uploading.
	 */
	private const MAX_SERVE_BYTES = 1_048_576; // 1 MiB

	public function __construct(
		RepoGroup $repoGroup,
		HttpRequestFactory $httpRequestFactory,
		UrlUtils $urlUtils,
		IConnectionProvider $dbProvider
	) {
		parent::__construct( 'UploadStash', 'upload' );
		$this->localRepo = $repoGroup->getLocalRepo();
		$this->httpRequestFactory = $httpRequestFactory;
		$this->urlUtils = $urlUtils;
		$this->dbProvider = $dbProvider;
	}

	/** @inheritDoc */
	public function doesWrites() {
		return true;
	}

	/**
	 * Execute page -- can output a file directly or show a listing of them.
	 *
	 * @param string|null $subPage Subpage, e.g. in
	 *   https://example.com/wiki/Special:UploadStash/foo.jpg, the "foo.jpg" part
	 */
	public function execute( $subPage ) {
		$this->useTransactionalTimeLimit();

		// This is not set in constructor, because the context with the user is not safe to be set
		$this->stash = $this->localRepo->getUploadStash( $this->getUser() );
		$this->checkPermissions();

		if ( $subPage === null || $subPage === '' ) {
			$this->showUploads();
		} else {
			$this->showUpload( $subPage );
		}
	}

	/**
	 * If file available in stash, cats it out to the client as a simple HTTP response.
	 * n.b. Most checking done in UploadStashLocalFile, so this is straightforward.
	 *
	 * @param string $key The key of a particular requested file
	 * @throws HttpError
	 */
	public function showUpload( $key ) {
		// prevent callers from doing standard HTML output -- we'll take it from here
		$this->getOutput()->disable();

		try {
			$params = $this->parseKey( $key );
			if ( $params['type'] === 'thumb' ) {
				$this->outputThumbFromStash( $params['file'], $params['params'] );
			} else {
				$this->outputLocalFile( $params['file'] );
			}
			return;
		} catch ( UploadStashFileNotFoundException $e ) {
			$code = 404;
			$message = $e->getMessage();
		} catch ( Exception $e ) {
			$code = 500;
			$message = $e->getMessage();
		}

		throw new HttpError( $code, $message );
	}

	/**
	 * Parse the key passed to the SpecialPage. Returns an array containing
	 * the associated file object, the type ('file' or 'thumb') and if
	 * application the transform parameters
	 *
	 * @param string $key
	 * @throws UploadStashBadPathException
	 * @return array
	 */
	private function parseKey( $key ) {
		$type = strtok( $key, '/' );

		if ( $type !== 'file' && $type !== 'thumb' ) {
			throw new UploadStashBadPathException(
				$this->msg( 'uploadstash-bad-path-unknown-type', $type )
			);
		}
		$fileName = strtok( '/' );
		$thumbPart = strtok( '/' );
		$file = $this->stash->getFile( $fileName );
		if ( $type === 'thumb' ) {
			$srcNamePos = strrpos( $thumbPart, $fileName );
			if ( $srcNamePos === false || $srcNamePos < 1 ) {
				throw new UploadStashBadPathException(
					$this->msg( 'uploadstash-bad-path-unrecognized-thumb-name' )
				);
			}
			$paramString = substr( $thumbPart, 0, $srcNamePos - 1 );

			$handler = $file->getHandler();
			if ( $handler ) {
				$params = $handler->parseParamString( $paramString );
				if ( $params === false ) {
					// The params are invalid, but still try to show a thumb
					$params = [];
				}

				return [ 'file' => $file, 'type' => $type, 'params' => $params ];
			} else {
				throw new UploadStashBadPathException(
					$this->msg( 'uploadstash-bad-path-no-handler', $file->getMimeType(), $file->getPath() )
				);
			}
		}

		return [ 'file' => $file, 'type' => $type ];
	}

	/**
	 * Get a thumbnail for file, either generated locally or remotely, and stream it out
	 *
	 * @param File $file
	 * @param array $params
	 */
	private function outputThumbFromStash( $file, $params ) {
		// this config option, if it exists, points to a "scaler", as you might find in
		// the Wikimedia Foundation cluster. See outputRemoteScaledThumb(). This
		// is part of our horrible NFS-based system, we create a file on a mount
		// point here, but fetch the scaled file from somewhere else that
		// happens to share it over NFS.
		if ( $file->getRepo()->getThumbProxyUrl()
			|| $this->getConfig()->get( MainConfigNames::UploadStashScalerBaseUrl )
		) {
			$this->outputRemoteScaledThumb( $file, $params );
		} else {
			$this->outputLocallyScaledThumb( $file, $params );
		}
	}

	/**
	 * Scale a file (probably with a locally installed imagemagick, or similar)
	 * and output it to STDOUT.
	 * @param File $file
	 * @param array $params Scaling parameters ( e.g. [ width => '50' ] );
	 */
	private function outputLocallyScaledThumb( $file, $params ) {
		// n.b. this is stupid, we insist on re-transforming the file every time we are invoked. We rely
		// on HTTP caching to ensure this doesn't happen.

		$thumbnailImage = $file->transform( $params, File::RENDER_NOW );
		if ( !$thumbnailImage ) {
			throw new UploadStashFileNotFoundException(
				$this->msg( 'uploadstash-file-not-found-no-thumb' )
			);
		}

		// we should have just generated it locally
		if ( !$thumbnailImage->getStoragePath() ) {
			throw new UploadStashFileNotFoundException(
				$this->msg( 'uploadstash-file-not-found-no-local-path' )
			);
		}

		// now we should construct a File, so we can get MIME and other such info in a standard way
		// n.b. MIME type may be different from original (ogx original -> jpeg thumb)
		$thumbFile = new UnregisteredLocalFile( false,
			$this->stash->repo, $thumbnailImage->getStoragePath(), false );

		$this->outputLocalFile( $thumbFile );
	}

	/**
	 * Scale a file with a remote "scaler", as exists on the Wikimedia Foundation
	 * cluster, and output it to STDOUT.
	 * Note: Unlike the usual thumbnail process, the web client never sees the
	 * cluster URL; we do the whole HTTP transaction to the scaler ourselves
	 * and cat the results out.
	 * Note: We rely on NFS to have propagated the file contents to the scaler.
	 * However, we do not rely on the thumbnail being created in NFS and then
	 * propagated back to our filesystem. Instead we take the results of the
	 * HTTP request instead.
	 * Note: No caching is being done here, although we are instructing the
	 * client to cache it forever.
	 *
	 * @param File $file
	 * @param array $params Scaling parameters ( e.g. [ width => '50' ] );
	 */
	private function outputRemoteScaledThumb( $file, $params ) {
		// We need to use generateThumbName() instead of thumbName(), because
		// the suffix needs to match the file name for the remote thumbnailer
		// to work
		$scalerThumbName = $file->generateThumbName( $file->getName(), $params );

		// If a thumb proxy is set up for the repo, we favor that, as that will
		// keep the request internal
		$thumbProxyUrl = $file->getRepo()->getThumbProxyUrl();
		if ( $thumbProxyUrl !== null ) {
			$scalerThumbUrl = $thumbProxyUrl . 'temp/' . $file->getUrlRel() .
				'/' . rawurlencode( $scalerThumbName );
			$secret = $file->getRepo()->getThumbProxySecret();
		} else {
			// This option probably looks something like
			// '//upload.wikimedia.org/wikipedia/test/thumb/temp'. Do not use
			// trailing slash.
			$scalerBaseUrl = $this->getConfig()->get( MainConfigNames::UploadStashScalerBaseUrl );

			if ( preg_match( '/^\/\//', $scalerBaseUrl ) ) {
				// this is apparently a protocol-relative URL, which makes no sense in this context,
				// since this is used for communication that's internal to the application.
				// default to http.
				$scalerBaseUrl = $this->urlUtils->expand( $scalerBaseUrl, PROTO_CANONICAL );
			}

			$scalerThumbUrl = $scalerBaseUrl . '/' . $file->getUrlRel() .
				'/' . rawurlencode( $scalerThumbName );
			$secret = null;
		}

		// make an http request based on wgUploadStashScalerBaseUrl to lazy-create
		// a thumbnail
		$httpOptions = [
			'method' => 'GET',
			'timeout' => 5 // T90599 attempt to time out cleanly
		];
		$req = $this->httpRequestFactory->create( $scalerThumbUrl, $httpOptions, __METHOD__ );

		// Pass a secret key shared with the proxied service if any
		if ( $secret !== null ) {
			$req->setHeader( 'X-Swift-Secret', $secret );
		}

		$status = $req->execute();
		if ( !$status->isOK() ) {
			throw new UploadStashFileNotFoundException(
				$this->msg(
					'uploadstash-file-not-found-no-remote-thumb',
					$status->getMessage(),
					$scalerThumbUrl
				)
			);
		}
		$contentType = $req->getResponseHeader( "content-type" );
		if ( !$contentType ) {
			throw new UploadStashFileNotFoundException(
				$this->msg( 'uploadstash-file-not-found-missing-content-type' )
			);
		}

		$this->outputContents( $req->getContent(), $contentType );
	}

	/**
	 * Output HTTP response for file
	 * Side effect: writes HTTP response to STDOUT.
	 *
	 * @param File $file File object with a local path (e.g. UnregisteredLocalFile,
	 *   LocalFile. Oddly these don't share an ancestor!)
	 * @throws SpecialUploadStashTooLargeException
	 */
	private function outputLocalFile( File $file ) {
		if ( $file->getSize() > self::MAX_SERVE_BYTES ) {
			throw new SpecialUploadStashTooLargeException(
				$this->msg( 'uploadstash-file-too-large', self::MAX_SERVE_BYTES )
			);
		}

		$file->getRepo()->streamFileWithStatus( $file->getPath(),
			[ 'Content-Transfer-Encoding: binary',
				'Expires: Sun, 17-Jan-2038 19:14:07 GMT' ]
		);
	}

	/**
	 * Output HTTP response of raw content
	 * Side effect: writes HTTP response to STDOUT.
	 * @param string $content
	 * @param string $contentType MIME type
	 * @throws SpecialUploadStashTooLargeException
	 */
	private function outputContents( $content, $contentType ) {
		$size = strlen( $content );
		if ( $size > self::MAX_SERVE_BYTES ) {
			throw new SpecialUploadStashTooLargeException(
				$this->msg( 'uploadstash-file-too-large', self::MAX_SERVE_BYTES )
			);
		}
		// Cancel output buffering and gzipping if set
		wfResetOutputBuffers();
		self::outputFileHeaders( $contentType, $size );
		print $content;
	}

	/**
	 * Output headers for streaming
	 * @todo Unsure about encoding as binary; if we received from HTTP perhaps
	 * we should use that encoding, concatenated with semicolon to `$contentType` as it
	 * usually is.
	 * Side effect: preps PHP to write headers to STDOUT.
	 * @param string $contentType String suitable for content-type header
	 * @param int $size Length in bytes
	 */
	private static function outputFileHeaders( $contentType, $size ) {
		header( "Content-Type: $contentType", true );
		header( 'Content-Transfer-Encoding: binary', true );
		header( 'Expires: Sun, 17-Jan-2038 19:14:07 GMT', true );
		// T55032 - It shouldn't be a problem here, but let's be safe and not cache
		header( 'Cache-Control: private' );
		header( "Content-Length: $size", true );
	}

	/**
	 * Default action when we don't have a subpage -- just show links to the uploads we have,
	 * Also show a button to clear stashed files
	 */
	private function showUploads() {
		// sets the title, etc.
		$this->setHeaders();
		$this->outputHeader();

		// create the form, which will also be used to execute a callback to process incoming form data
		// this design is extremely dubious, but supposedly HTMLForm is our standard now?

		$form = HTMLForm::factory( 'ooui', [
			'Clear' => [
				'type' => 'hidden',
				'default' => true,
				'name' => 'clear',
			]
		], $this->getContext(), 'clearStashedUploads' );
		$form->setTitle( $this->getPageTitle() ); // Remove subpage
		$form->setSubmitDestructive();
		$form->setSubmitCallback( function ( $formData, $form ) {
			if ( isset( $formData['Clear'] ) ) {
				wfDebug( 'stash has: ' . print_r( $this->stash->listFiles(), true ) );

				if ( !$this->stash->clear() ) {
					return Status::newFatal( 'uploadstash-errclear' );
				}
			}

			return Status::newGood();
		} );
		$form->setSubmitTextMsg( 'uploadstash-clear' );

		$form->prepareForm();
		$formResult = $form->tryAuthorizedSubmit();

		// show the files + form, if there are any, or just say there are none
		$linkRenderer = $this->getLinkRenderer();
		$refreshHtml = $linkRenderer->makeKnownLink(
			$this->getPageTitle(),
			$this->msg( 'uploadstash-refresh' )->text()
		);
		$pager = new UploadStashPager(
			$this->getContext(),
			$linkRenderer,
			$this->dbProvider,
			$this->stash,
			$this->localRepo
		);
		if ( $pager->getNumRows() ) {
			$pager->getForm();
			$this->getOutput()->addParserOutputContent(
				$pager->getFullOutput(),
				ParserOptions::newFromContext( $this->getContext() )
			);
			$form->displayForm( $formResult );
			$this->getOutput()->addHTML( Html::rawElement( 'p', [], $refreshHtml ) );
		} else {
			$this->getOutput()->addHTML( Html::rawElement( 'p', [],
				Html::element( 'span', [], $this->msg( 'uploadstash-nofiles' )->text() )
				. ' '
				. $refreshHtml
			) );
		}
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( SpecialUploadStash::class, 'SpecialUploadStash' );
