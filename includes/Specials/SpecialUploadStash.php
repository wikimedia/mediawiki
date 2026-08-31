<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Specials;

use MediaWiki\Exception\HttpError;
use MediaWiki\FileRepo\File\File;
use MediaWiki\FileRepo\LocalRepo;
use MediaWiki\FileRepo\RepoGroup;
use MediaWiki\Html\Html;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\Http\HttpRequestFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Request\ContentSecurityPolicy;
use MediaWiki\Rest\HeaderParser\HttpDate;
use MediaWiki\SpecialPage\UnlistedSpecialPage;
use MediaWiki\Specials\Exception\SpecialUploadStashTooLargeException;
use MediaWiki\Specials\Pager\UploadStashPager;
use MediaWiki\Status\Status;
use MediaWiki\Upload\Exception\UploadStashBadPathException;
use MediaWiki\Upload\Exception\UploadStashException;
use MediaWiki\Upload\Exception\UploadStashFileNotFoundException;
use MediaWiki\Upload\UploadStash;
use MediaWiki\Utils\UrlUtils;
use StatusValue;
use Wikimedia\FileBackend\FileBackend;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * Web access for files temporarily stored by UploadStash, for example files
 * uploaded with UploadWizard and not yet published. Such files can only be
 * viewed by the user who created them.
 *
 * @ingroup SpecialPage
 * @ingroup Upload
 */
class SpecialUploadStash extends UnlistedSpecialPage {
	private ?UploadStash $stash = null;
	private LocalRepo $localRepo;

	/**
	 * Since we are directly writing the file to STDOUT, we should not be
	 * reading in really big files and serving them out.
	 *
	 * We also don't want people using this as a file drop, even if they
	 * share credentials.
	 *
	 * This service is really for thumbnails and other such previews while
	 * uploading.
	 */
	private int $maxServeBytes = 1_048_576; // 1 MiB

	public function __construct(
		RepoGroup $repoGroup,
		private readonly HttpRequestFactory $httpRequestFactory,
		private readonly UrlUtils $urlUtils,
		private readonly IConnectionProvider $dbProvider,
	) {
		parent::__construct( 'UploadStash' );
		$this->localRepo = $repoGroup->getLocalRepo();
	}

	/** @inheritDoc */
	public function getRestriction(): string {
		return 'upload';
	}

	/** @inheritDoc */
	public function doesWrites() {
		return true;
	}

	/**
	 * Execute page -- can output a file directly or show a listing of them.
	 *
	 * @param string|null $subPage
	 */
	public function execute( $subPage ) {
		$this->useTransactionalTimeLimit();
		$this->checkPermissions();

		if ( $subPage === null || $subPage === '' ) {
			$this->showUploads();
		} else {
			$this->showUpload( $subPage );
		}
	}

	/**
	 * If the file is available in the stash, stream it out to the client.
	 *
	 * @param string $subPage May be one of:
	 *  - /file/<key>: An original stashed file
	 *  - /thumb/<key>/120px-<key>: A thumbnail of a stashed file
	 * @throws HttpError
	 */
	private function showUpload( string $subPage ) {
		// Disable standard HTML output -- we'll take it from here
		$this->getOutput()->disable();

		try {
			$this->showUploadOrThrow( $subPage );
			return;
		} catch ( UploadStashFileNotFoundException | UploadStashBadPathException $e ) {
			$code = 404;
			$message = $e->getMessage();
		} catch ( UploadStashException $e ) {
			$code = 500;
			$message = $e->getMessage();
		}

		throw new HttpError( $code, $message );
	}

	/**
	 * Stream out the file and throw an exception if anything goes wrong
	 *
	 * @param string $subPage
	 * @throws UploadStashBadPathException
	 */
	private function showUploadOrThrow( $subPage ) {
		$type = strtok( $subPage, '/' );

		if ( $type !== 'file' && $type !== 'thumb' ) {
			throw new UploadStashBadPathException(
				$this->msg( 'uploadstash-bad-path-unknown-type', $type )
			);
		}
		$fileName = strtok( '/' );
		$thumbPart = strtok( '/' );
		$file = $this->getStash()->getFile( $fileName );
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
					// The params are invalid
					throw new UploadStashBadPathException(
						$this->msg( 'uploadstash-bad-path-unrecognized-thumb-name' )
					);
				}

				$this->outputThumbFromStash( $file, $params );
			} else {
				throw new UploadStashBadPathException(
					$this->msg( 'uploadstash-bad-path-no-handler', $file->getMimeType(), $file->getPath() )
				);
			}
		} else {
			$this->outputOriginal( $file );
		}
	}

	/**
	 * Get a thumbnail for a file and stream it out
	 *
	 * @param File $file
	 * @param array $params
	 */
	private function outputThumbFromStash( $file, $params ) {
		if ( $file->getRepo()->getThumbProxyUrl()
			|| $this->getConfig()->get( MainConfigNames::UploadStashScalerBaseUrl )
		) {
			$this->outputRemoteScaledThumb( $file, $params );
		} else {
			$this->outputLocallyScaledThumb( $file, $params );
		}
	}

	/**
	 * Scale a file, using ImageMagick or similar, and stream it out.
	 *
	 * @param File $file
	 * @param array $params Scaling parameters ( e.g. [ width => '50' ] );
	 */
	private function outputLocallyScaledThumb( $file, $params ) {
		// Transform the file immediately and cache the result in the FileRepo.
		// If the destination file already exists, it will be used.
		$thumbnailImage = $file->transform( $params, File::RENDER_NOW );
		if ( !$thumbnailImage ) {
			throw new UploadStashFileNotFoundException(
				$this->msg( 'uploadstash-file-not-found-no-thumb' )
			);
		}

		$path = $thumbnailImage->getStoragePath();
		if ( $path === false ) {
			// This can happen if the thumbnail width was larger than the original width
			throw new UploadStashFileNotFoundException(
				$this->msg( 'uploadstash-file-not-found-no-local-path' )
			);
		}

		if ( $this->localRepo->getFileSize( $thumbnailImage->getStoragePath() )
			> $this->maxServeBytes
		) {
			throw new SpecialUploadStashTooLargeException(
				$this->msg( 'uploadstash-file-too-large', $this->maxServeBytes )
			);
		}

		$fileName = FileBackend::fileNameFromPath( $path );
		$status = $thumbnailImage->streamFileWithStatus( $this->getStreamHeaders( $fileName ) );
		$this->assertStatusOK( $status );
	}

	/**
	 * Scale a file with a remote "scaler", as exists on the Wikimedia Foundation
	 * cluster, and stream it out.
	 *
	 * We rely on FileBackend to have propagated the file contents to the scaler.
	 * But the thumbnail comes back to us in the response, we don't need
	 * FileBackend for that.
	 *
	 * Note: No caching is being done here, although we are instructing the
	 * client to cache it.
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

			if ( str_starts_with( $scalerBaseUrl, '//' ) ) {
				// Expand protocol-relative URL
				$scalerBaseUrl = $this->urlUtils->expand( $scalerBaseUrl, PROTO_CANONICAL );
			}

			$scalerThumbUrl = $scalerBaseUrl . '/' . $file->getUrlRel() .
				'/' . rawurlencode( $scalerThumbName );
			$secret = null;
		}

		$httpOptions = [
			'method' => 'GET',
			'timeout' => 5 // T90599 attempt to time out cleanly
		];
		$req = $this->httpRequestFactory->create( $scalerThumbUrl, $httpOptions, __METHOD__ );

		// Pass a secret key shared with the proxied service if any
		if ( $secret !== null ) {
			$req->setHeader( 'X-Swift-Secret', $secret );
		}

		// Abort the request if the data returned exceeds the maximum size
		$content = '';
		$limitExceeded = false;
		$req->setCallback( function ( $stream, $chunk ) use ( &$content, &$limitExceeded ) {
			if ( strlen( $chunk ) + strlen( $content ) > $this->maxServeBytes ) {
				$limitExceeded = true;
				return 0;
			}
			$content .= $chunk;
			return strlen( $chunk );
		} );

		$status = $req->execute();

		if ( $limitExceeded ) {
			throw new SpecialUploadStashTooLargeException(
				$this->msg( 'uploadstash-file-too-large', $this->maxServeBytes )
			);
		}

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

		$headers = $this->getStreamHeaders( $scalerThumbName );
		$headers[] = "Content-Type: $contentType";

		wfResetOutputBuffers();

		// Send headers to WebResponse for testability
		$webResponse = $this->getRequest()->response();
		foreach ( $headers as $line ) {
			$webResponse->header( $line );
		}

		print $content;
	}

	/**
	 * Stream the unscaled file to the output, with headers
	 *
	 * @param File $file
	 * @throws SpecialUploadStashTooLargeException
	 */
	private function outputOriginal( File $file ) {
		if ( $file->getSize() > $this->maxServeBytes ) {
			throw new SpecialUploadStashTooLargeException(
				$this->msg( 'uploadstash-file-too-large', $this->maxServeBytes )
			);
		}

		$status = $file->getRepo()->streamFileWithStatus( $file->getPath(),
			$this->getStreamHeaders( $file->getName() )
		);
		$this->assertStatusOK( $status );
	}

	/**
	 * Throw an exception if the status is not OK
	 *
	 * @param StatusValue $status
	 * @throws UploadStashException
	 */
	private function assertStatusOK( StatusValue $status ) {
		if ( !$status->isOK() ) {
			throw new UploadStashException(
				isset( $status->getMessages()[0] )
					? $this->msg( $status->getMessages()[0] )
					: 'unknown error from streamFileWithStatus'
			);
		}
	}

	/**
	 * Get header lines for streaming a file out, as in thumb.php
	 *
	 * @param string $fileName
	 * @return string[]
	 */
	private function getStreamHeaders( $fileName ) {
		$maxAge = $this->getConfig()->get( MainConfigNames::UploadStashMaxAge );
		$headers = [
			'Cache-Control: private',
			'Expires: ' . HttpDate::format( time() + $maxAge ),
		];
		$cspHeader = ContentSecurityPolicy::getMediaHeader( $fileName );
		if ( $cspHeader ) {
			$headers[] = 'Content-Security-Policy: ' . $cspHeader;
		}
		return $headers;
	}

	/**
	 * Default action when we don't have a subpage -- just show links to the uploads we have.
	 * Also show a button to clear stashed files.
	 */
	private function showUploads() {
		$this->setHeaders();
		$this->outputHeader();
		$this->getOutput()->addModuleStyles( 'mediawiki.special' );

		// Create the form, which will also be used to execute a callback to
		// process incoming form data.

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
				if ( !$this->getStash()->clear() ) {
					return Status::newFatal( 'uploadstash-errclear' );
				}
			}
			return Status::newGood();
		} );

		$form->setSubmitTextMsg( 'uploadstash-clear' );

		$form->prepareForm();
		$formResult = $form->tryAuthorizedSubmit();

		// Show the files + form, if there are any, or just say there are none
		$linkRenderer = $this->getLinkRenderer();
		$refreshHtml = $linkRenderer->makeKnownLink(
			$this->getPageTitle(),
			$this->msg( 'uploadstash-refresh' )->text()
		);
		$pager = new UploadStashPager(
			$this->getContext(),
			$linkRenderer,
			$this->dbProvider,
			$this->getStash(),
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

	private function getStash(): UploadStash {
		if ( !$this->stash ) {
			$this->stash = $this->localRepo->getUploadStash( $this->getUser() );
		}
		return $this->stash;
	}

	/**
	 * @internal For testing
	 */
	public function setMaxServeBytes( int $size ) {
		$this->maxServeBytes = $size;
	}
}

// @codeCoverageIgnoreStart
/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( SpecialUploadStash::class, 'SpecialUploadStash' );
// @codeCoverageIgnoreEnd
