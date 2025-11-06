<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @defgroup JobQueue JobQueue
 */

namespace MediaWiki\JobQueue\Jobs;

use Exception;
use MediaWiki\Api\ApiUpload;
use MediaWiki\Context\RequestContext;
use MediaWiki\Exception\MWExceptionHandler;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\Status\Status;
use MediaWiki\User\User;
use UploadBase;
use UploadStashException;
use Wikimedia\ScopedCallback;

/**
 * Common functionality for async uploads
 *
 * @ingroup Upload
 * @ingroup JobQueue
 */
trait UploadJobTrait {
	/** @var User|null */
	private $user;

	/** @var string */
	private $cacheKey;

	/** @var UploadBase */
	private $upload;

	/** @var array The job parameters */
	public $params;

	/**
	 * Set up the job
	 *
	 * @param string $cacheKey
	 * @return void
	 */
	protected function initialiseUploadJob( $cacheKey ): void {
		$this->cacheKey = $cacheKey;
		$this->user = null;
	}

	/**
	 * Do not allow retries on jobs by default.
	 */
	public function allowRetries(): bool {
		return false;
	}

	/**
	 * Run the job
	 */
	public function run(): bool {
		$this->user = $this->getUserFromSession();
		if ( $this->user === null ) {
			return false;
		}

		try {
			// Check the initial status of the upload
			$startingStatus = UploadBase::getSessionStatus( $this->user, $this->cacheKey );
			// Warn if in wrong stage, but still continue. User may be able to trigger
			// this by retrying after failure.
			if (
				!$startingStatus ||
				( $startingStatus['result'] ?? '' ) !== 'Poll' ||
				( $startingStatus['stage'] ?? '' ) !== 'queued'
			) {
				$logger = LoggerFactory::getInstance( 'upload' );
				$logger->warning( "Tried to publish upload that is in stage {stage}/{result}",
					$this->logJobParams( $startingStatus )
				);
			}

			// Fetch the file if needed
			if ( !$this->fetchFile() ) {
				return false;
			}

			// Verify the upload is valid
			if ( !$this->verifyUpload() ) {
				return false;
			}

			// Actually upload the file
			if ( !$this->performUpload() ) {
				return false;
			}

			// All done
			$this->setStatusDone();

			// Cleanup any temporary local file
			$this->getUpload()->cleanupTempFile();
		} catch ( UploadStashException $e ) {
			$this->setStatus( 'publish', 'Failure', Status::newFatal( $e->getMessageObject() ) );
			$this->setLastError( get_class( $e ) . ": " . $e->getMessage() );
			return false;
		} catch ( Exception $e ) {
			$this->setStatus( 'publish', 'Failure', Status::newFatal( 'api-error-publishfailed' ) );
			$this->setLastError( get_class( $e ) . ": " . $e->getMessage() );
			// To prevent potential database referential integrity issues.
			// See T34551.
			MWExceptionHandler::rollbackPrimaryChangesAndLog( $e );
			return false;
		}

		return true;
	}

	/**
	 * Get the cache key used to store status
	 *
	 * @return string
	 */
	public function getCacheKey() {
		return $this->cacheKey;
	}

	/**
	 * Get user data from the session key
	 *
	 * @return User|null
	 */
	private function getUserFromSession() {
		$scope = RequestContext::importScopedSession( $this->params['session'] );
		$this->addTeardownCallback( static function () use ( &$scope ) {
			ScopedCallback::consume( $scope ); // T126450
		} );

		$context = RequestContext::getMain();
		$user = $context->getUser();
		if ( !$user->isRegistered() ) {
			$this->setLastError( "Could not load the author user from session." );

			return null;
		}
		return $user;
	}

	/**
	 * Set the upload status
	 *
	 * @param string $stage
	 * @param string $result
	 * @param Status|null $status
	 * @param array $additionalInfo
	 */
	private function setStatus( $stage, $result = 'Poll', $status = null, $additionalInfo = [] ) {
		// We're most probably not running in a job.
		// @todo maybe throw an exception?
		if ( $this->user === null ) {
			return;
		}
		$status ??= Status::newGood();
		$info = [ 'result' => $result, 'stage' => $stage, 'status' => $status ];
		$info += $additionalInfo;
		UploadBase::setSessionStatus(
			$this->user,
			$this->cacheKey,
			$info
		);
	}

	/**
	 * Ensure we have the file available. A noop here.
	 */
	protected function fetchFile(): bool {
		$this->setStatus( 'fetching' );
		// make sure the upload file is here. This is a noop in most cases.
		$status = $this->getUpload()->fetchFile();
		if ( !$status->isGood() ) {
			$this->setStatus( 'fetching', 'Failure', $status );
			$this->setLastError( "Error while fetching the image." );
			return false;
		}
		$this->setStatus( 'publish' );
		// We really don't care as this is, as mentioned, generally a noop.
		// When that's not the case, classes will need to override this method anyways.
		return true;
	}

	/**
	 * Verify the upload is ok
	 */
	private function verifyUpload(): bool {
		// Check if the local file checks out (this is generally a no-op)
		$verification = $this->getUpload()->verifyUpload();
		if ( $verification['status'] !== UploadBase::OK ) {
			$status = Status::newFatal( 'verification-error' );
			$status->value = [ 'verification' => $verification ];
			$this->setStatus( 'publish', 'Failure', $status );
			$this->setLastError( "Could not verify upload." );
			return false;
		}
		// Verify title permissions for this user
		$status = $this->getUpload()->authorizeUpload( $this->user );
		if ( !$status->isGood() ) {
			$this->setStatus( 'publish', 'Failure', Status::wrap( $status ) );
			$this->setLastError( "Could not verify title permissions." );
			return false;
		}

		// Verify if any upload warnings are present
		$ignoreWarnings = $this->params['ignorewarnings'] ?? false;
		$isReupload = $this->params['reupload'] ?? false;
		if ( $ignoreWarnings ) {
			// If we're ignoring warnings, we don't need to check them
			return true;
		}
		$warnings = $this->getUpload()->checkWarnings( $this->user );
		if ( $warnings ) {
			// If the file exists and we're reuploading, ignore the warning
			// and continue with the upload
			if ( count( $warnings ) === 1 && isset( $warnings['exists'] ) && $isReupload ) {
				return true;
			}
			// Make the array serializable
			$serializableWarnings = UploadBase::makeWarningsSerializable( $warnings );
			$this->setStatus( 'publish', 'Warning', null, [ 'warnings' => $serializableWarnings ] );
			$this->setLastError( "Upload warnings present." );
			return false;
		}

		return true;
	}

	/**
	 * Upload the stashed file to a permanent location
	 */
	private function performUpload(): bool {
		if ( $this->user === null ) {
			return false;
		}
		$status = $this->getUpload()->performUpload(
			$this->params['comment'],
			$this->params['text'],
			$this->params['watch'],
			$this->user,
			$this->params['tags'] ?? [],
			$this->params['watchlistexpiry'] ?? null
		);
		if ( !$status->isGood() ) {
			$this->setStatus( 'publish', 'Failure', $status );
			$this->setLastError( $status->getWikiText( false, false, 'en' ) );
			return false;
		}
		return true;
	}

	/**
	 * Set the status at the end or processing
	 */
	private function setStatusDone() {
		// Build the image info array while we have the local reference handy
		$imageInfo = ApiUpload::getDummyInstance()->getUploadImageInfo( $this->getUpload() );

		// Cache the info so the user doesn't have to wait forever to get the final info
		$this->setStatus(
			'publish',
			'Success',
			Status::newGood(),
			[ 'filename' => $this->getUpload()->getLocalFile()->getName(), 'imageinfo' => $imageInfo ]
		);
	}

	/**
	 * Getter for the upload. Needs to be implemented by the job class
	 *
	 * @return UploadBase
	 */
	abstract protected function getUpload(): UploadBase;

	/**
	 * Get the job parameters for logging. Needs to be implemented by the job class.
	 *
	 * @param Status[] $status
	 * @return array
	 */
	abstract protected function logJobParams( $status ): array;

	/**
	 * This is actually implemented in the Job class
	 *
	 * @param mixed $error
	 * @return void
	 */
	abstract protected function setLastError( $error );

	/**
	 * This is actually implemented in the Job class
	 *
	 * @param callable $callback
	 * @return void
	 */
	abstract protected function addTeardownCallback( $callback );

}

/** @deprecated class alias since 1.44 */
class_alias( UploadJobTrait::class, 'UploadJobTrait' );
