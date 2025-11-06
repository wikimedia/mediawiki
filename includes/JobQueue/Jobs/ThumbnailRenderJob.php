<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\JobQueue\Jobs;

use MediaTransformError;
use MediaWiki\FileRepo\File\File;
use MediaWiki\FileRepo\File\LocalFile;
use MediaWiki\JobQueue\Job;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Status\Status;
use MediaWiki\Title\Title;
use Wikimedia\Rdbms\IDBAccessObject;

/**
 * Job for asynchronous rendering of thumbnails, e.g. after new uploads.
 *
 * @ingroup JobQueue
 */
class ThumbnailRenderJob extends Job {
	public function __construct( Title $title, array $params ) {
		parent::__construct( 'ThumbnailRender', $title, $params );
	}

	/** @inheritDoc */
	public function run() {
		$uploadThumbnailRenderMethod = MediaWikiServices::getInstance()
			->getMainConfig()->get( MainConfigNames::UploadThumbnailRenderMethod );

		$transformParams = $this->params['transformParams'];

		$file = MediaWikiServices::getInstance()->getRepoGroup()->getLocalRepo()
			->newFile( $this->title );
		$file->load( IDBAccessObject::READ_LATEST );

		if ( $file && $file->exists() ) {
			if ( $uploadThumbnailRenderMethod === 'jobqueue' ) {
				$thumb = $file->transform( $transformParams, File::RENDER_NOW );

				if ( !$thumb || $thumb->isError() ) {
					if ( $thumb instanceof MediaTransformError ) {
						$this->setLastError( __METHOD__ . ': thumbnail couldn\'t be generated:' .
							$thumb->toText() );
					} else {
						$this->setLastError( __METHOD__ . ': thumbnail couldn\'t be generated' );
					}
					return false;
				}
				$this->maybeEnqueueNextPage( $transformParams );
				return true;
			} elseif ( $uploadThumbnailRenderMethod === 'http' ) {
				$res = $this->hitThumbUrl( $file, $transformParams );
				$this->maybeEnqueueNextPage( $transformParams );
				return $res;
			} else {
				$this->setLastError( __METHOD__ . ': unknown thumbnail render method ' .
					$uploadThumbnailRenderMethod );
				return false;
			}
		} else {
			$this->setLastError( __METHOD__ . ': file doesn\'t exist' );
			return false;
		}
	}

	/**
	 * @param LocalFile $file
	 * @param array $transformParams
	 * @return bool Success status (error will be set via setLastError() when false)
	 */
	protected function hitThumbUrl( LocalFile $file, $transformParams ) {
		$config = MediaWikiServices::getInstance()->getMainConfig();
		$uploadThumbnailRenderHttpCustomHost =
			$config->get( MainConfigNames::UploadThumbnailRenderHttpCustomHost );
		$uploadThumbnailRenderHttpCustomDomain =
			$config->get( MainConfigNames::UploadThumbnailRenderHttpCustomDomain );
		$handler = $file->getHandler();
		if ( !$handler ) {
			$this->setLastError( __METHOD__ . ': could not get handler' );
			return false;
		} elseif ( !$handler->normaliseParams( $file, $transformParams ) ) {
			$this->setLastError( __METHOD__ . ': failed to normalize' );
			return false;
		}
		$thumbName = $file->thumbName( $transformParams );
		$thumbUrl = $file->getThumbUrl( $thumbName );

		if ( $thumbUrl === null ) {
			$this->setLastError( __METHOD__ . ': could not get thumb URL' );
			return false;
		}

		if ( $uploadThumbnailRenderHttpCustomDomain ) {
			$parsedUrl = wfGetUrlUtils()->parse( $thumbUrl );

			if ( !isset( $parsedUrl['path'] ) || $parsedUrl['path'] === '' ) {
				$this->setLastError( __METHOD__ . ": invalid thumb URL: $thumbUrl" );
				return false;
			}

			$thumbUrl = '//' . $uploadThumbnailRenderHttpCustomDomain . $parsedUrl['path'];
		}

		wfDebug( __METHOD__ . ": hitting url {$thumbUrl}" );

		// T203135 We don't wait for the request to complete, as this is mostly fire & forget.
		// Looking at the HTTP status of requests that take less than 1s is a double check.
		$request = MediaWikiServices::getInstance()->getHttpRequestFactory()->create(
			$thumbUrl,
			[ 'method' => 'HEAD', 'followRedirects' => true, 'timeout' => 1 ],
			__METHOD__
		);

		if ( $uploadThumbnailRenderHttpCustomHost ) {
			$request->setHeader( 'Host', $uploadThumbnailRenderHttpCustomHost );
		}

		$status = $request->execute();
		$statusCode = $request->getStatus();
		wfDebug( __METHOD__ . ": received status {$statusCode}" );

		// 400 happens when requesting a size greater or equal than the original
		// TODO use proper error signaling. 400 could mean a number of other things.
		if ( $statusCode === 200 || $statusCode === 301 || $statusCode === 302 || $statusCode === 400 ) {
			return true;
		} elseif ( $statusCode ) {
			$this->setLastError( __METHOD__ . ": incorrect HTTP status $statusCode when hitting $thumbUrl" );
		} elseif ( $status->hasMessage( 'http-timed-out' ) ) {
			// T203135 we ignore timeouts, as it would be inefficient for this job to wait for
			// minutes for the slower thumbnails to complete.
			return true;
		} else {
			$this->setLastError( __METHOD__ . ': HTTP request failure: '
				. Status::wrap( $status )->getWikiText( false, false, 'en' ) );
		}
		return false;
	}

	private function maybeEnqueueNextPage( array $transformParams ) {
		if (
			( $this->params['enqueueNextPage'] ?? false ) &&
			( $transformParams['page'] ?? 0 ) < ( $this->params['pageLimit'] ?? 0 )
		) {
			$transformParams['page']++;
			$job = new ThumbnailRenderJob(
				$this->getTitle(),
				[
					'transformParams' => $transformParams,
					'enqueueNextPage' => true,
					'pageLimit' => $this->params['pageLimit']
				]
			);

			MediaWikiServices::getInstance()->getJobQueueGroup()->lazyPush( [ $job ] );
		}
	}

	/**
	 * Whether to retry the job.
	 * @return bool
	 */
	public function allowRetries() {
		// ThumbnailRenderJob is a warmup for the thumbnails cache,
		// so loosing it is not a problem. Most times the job fails
		// for non-renderable or missing images which will not be fixed
		// by a retry, but will create additional load on the renderer.
		return false;
	}
}

/** @deprecated class alias since 1.44 */
class_alias( ThumbnailRenderJob::class, 'ThumbnailRenderJob' );
