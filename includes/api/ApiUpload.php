<?php
/**
 * Copyright © 2008 - 2010 Bryan Tong Minh <Bryan.TongMinh@Gmail.com>
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

/**
 * @todo: create a UploadCommandFactory and UploadComand classes to share logic with Special:Upload
 * @todo: split the different cases of upload in subclasses or submethods.
 */

namespace MediaWiki\Api;

use Exception;
use MediaWiki\ChangeTags\ChangeTags;
use MediaWiki\Config\Config;
use MediaWiki\FileRepo\File\LocalFile;
use MediaWiki\JobQueue\JobQueueGroup;
use MediaWiki\JobQueue\Jobs\AssembleUploadChunksJob;
use MediaWiki\JobQueue\Jobs\PublishStashedFileJob;
use MediaWiki\JobQueue\Jobs\UploadFromUrlJob;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Message\Message;
use MediaWiki\Status\Status;
use MediaWiki\User\Options\UserOptionsLookup;
use MediaWiki\User\User;
use MediaWiki\Watchlist\WatchedItemStoreInterface;
use MediaWiki\Watchlist\WatchlistManager;
use Psr\Log\LoggerInterface;
use StatusValue;
use UploadBase;
use UploadFromChunks;
use UploadFromFile;
use UploadFromStash;
use UploadFromUrl;
use UploadStashBadPathException;
use UploadStashException;
use UploadStashFileException;
use UploadStashFileNotFoundException;
use UploadStashNoSuchKeyException;
use UploadStashNotLoggedInException;
use UploadStashWrongOwnerException;
use UploadStashZeroLengthFileException;
use Wikimedia\Message\MessageSpecifier;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef\IntegerDef;

/**
 * @ingroup API
 */
class ApiUpload extends ApiBase {

	use ApiWatchlistTrait;

	/** @var UploadBase|UploadFromChunks|null */
	protected $mUpload = null;

	/** @var array */
	protected $mParams;

	private JobQueueGroup $jobQueueGroup;

	private LoggerInterface $log;

	public function __construct(
		ApiMain $mainModule,
		string $moduleName,
		JobQueueGroup $jobQueueGroup,
		WatchlistManager $watchlistManager,
		WatchedItemStoreInterface $watchedItemStore,
		UserOptionsLookup $userOptionsLookup
	) {
		parent::__construct( $mainModule, $moduleName );
		$this->jobQueueGroup = $jobQueueGroup;

		// Variables needed in ApiWatchlistTrait trait
		$this->watchlistExpiryEnabled = $this->getConfig()->get( MainConfigNames::WatchlistExpiry );
		$this->watchlistMaxDuration =
			$this->getConfig()->get( MainConfigNames::WatchlistExpiryMaxDuration );
		$this->watchlistManager = $watchlistManager;
		$this->watchedItemStore = $watchedItemStore;
		$this->userOptionsLookup = $userOptionsLookup;
		$this->log = LoggerFactory::getInstance( 'upload' );
	}

	public function execute() {
		// Check whether upload is enabled
		if ( !UploadBase::isEnabled() ) {
			$this->dieWithError( 'uploaddisabled' );
		}

		$user = $this->getUser();

		// Parameter handling
		$this->mParams = $this->extractRequestParams();
		// Check if async mode is actually supported (jobs done in cli mode)
		$this->mParams['async'] = ( $this->mParams['async'] &&
			$this->getConfig()->get( MainConfigNames::EnableAsyncUploads ) );

		// Copy the session key to the file key, for backward compatibility.
		if ( !$this->mParams['filekey'] && $this->mParams['sessionkey'] ) {
			$this->mParams['filekey'] = $this->mParams['sessionkey'];
		}

		if ( !$this->mParams['checkstatus'] ) {
			$this->useTransactionalTimeLimit();
		}

		// Select an upload module
		try {
			if ( !$this->selectUploadModule() ) {
				return; // not a true upload, but a status request or similar
			} elseif ( !$this->mUpload ) {
				$this->dieDebug( __METHOD__, 'No upload module set' );
			}
		} catch ( UploadStashException $e ) { // XXX: don't spam exception log
			$this->dieStatus( $this->handleStashException( $e ) );
		}

		// First check permission to upload
		$this->checkPermissions( $user );

		// Fetch the file (usually a no-op)
		// Skip for async upload from URL, where we just want to run checks.
		/** @var Status $status */
		if ( $this->mParams['async'] && $this->mParams['url'] ) {
			$status = $this->mUpload->canFetchFile();
		} else {
			$status = $this->mUpload->fetchFile();
		}

		if ( !$status->isGood() ) {
			$this->log->info( "Unable to fetch file {filename} for {user} because {status}",
				[
					'user' => $this->getUser()->getName(),
					'status' => (string)$status,
					'filename' => $this->mParams['filename'] ?? '-',
				]
			);
			$this->dieStatus( $status );
		}

		// Check the uploaded file
		$this->verifyUpload();

		// Check if the user has the rights to modify or overwrite the requested title
		// (This check is irrelevant if stashing is already requested, since the errors
		//  can always be fixed by changing the title)
		if ( !$this->mParams['stash'] ) {
			$status = $this->mUpload->authorizeUpload( $user );
			if ( !$status->isGood() ) {
				$this->dieRecoverableError( $status->getMessages(), 'filename' );
			}
		}

		// Get the result based on the current upload context:
		try {
			$result = $this->getContextResult();
		} catch ( UploadStashException $e ) { // XXX: don't spam exception log
			$this->dieStatus( $this->handleStashException( $e ) );
		}
		$this->getResult()->addValue( null, $this->getModuleName(), $result );

		// Add 'imageinfo' in a separate addValue() call. File metadata can be unreasonably large,
		// so otherwise when it exceeded $wgAPIMaxResultSize, no result would be returned (T143993).
		// @phan-suppress-next-line PhanTypeArraySuspiciousNullable False positive
		if ( $result['result'] === 'Success' ) {
			$imageinfo = $this->getUploadImageInfo( $this->mUpload );
			$this->getResult()->addValue( $this->getModuleName(), 'imageinfo', $imageinfo );
		}

		// Cleanup any temporary mess
		$this->mUpload->cleanupTempFile();
	}

	public static function getDummyInstance(): self {
		$services = MediaWikiServices::getInstance();
		$apiMain = new ApiMain(); // dummy object (XXX)
		$apiUpload = new ApiUpload(
			$apiMain,
			'upload',
			$services->getJobQueueGroup(),
			$services->getWatchlistManager(),
			$services->getWatchedItemStore(),
			$services->getUserOptionsLookup()
		);

		return $apiUpload;
	}

	/**
	 * Gets image info about the file just uploaded.
	 *
	 * Also has the effect of setting metadata to be an 'indexed tag name' in
	 * returned API result if 'metadata' was requested. Oddly, we have to pass
	 * the "result" object down just so it can do that with the appropriate
	 * format, presumably.
	 *
	 * @internal For use in upload jobs and a deprecated method on UploadBase.
	 * @todo Extract the logic actually needed by the jobs, and separate it
	 *       from the structure used in API responses.
	 *
	 * @return array Image info
	 */
	public function getUploadImageInfo( UploadBase $upload ): array {
		$result = $this->getResult();
		$stashFile = $upload->getStashFile();

		// Calling a different API module depending on whether the file was stashed is less than optimal.
		// In fact, calling API modules here at all is less than optimal. Maybe it should be refactored.
		if ( $stashFile ) {
			$imParam = ApiQueryStashImageInfo::getPropertyNames();
			$info = ApiQueryStashImageInfo::getInfo(
				$stashFile,
				array_fill_keys( $imParam, true ),
				$result
			);
		} else {
			$localFile = $upload->getLocalFile();
			$imParam = ApiQueryImageInfo::getPropertyNames();
			$info = ApiQueryImageInfo::getInfo(
				$localFile,
				array_fill_keys( $imParam, true ),
				$result
			);
		}

		return $info;
	}

	/**
	 * Get an upload result based on upload context
	 * @return array
	 */
	private function getContextResult() {
		$warnings = $this->getApiWarnings();
		if ( $warnings && !$this->mParams['ignorewarnings'] ) {
			// Get warnings formatted in result array format
			return $this->getWarningsResult( $warnings );
		} elseif ( $this->mParams['chunk'] ) {
			// Add chunk, and get result
			return $this->getChunkResult( $warnings );
		} elseif ( $this->mParams['stash'] ) {
			// Stash the file and get stash result
			return $this->getStashResult( $warnings );
		}

		// This is the most common case -- a normal upload with no warnings
		// performUpload will return a formatted properly for the API with status
		return $this->performUpload( $warnings );
	}

	/**
	 * Get Stash Result, throws an exception if the file could not be stashed.
	 * @param array $warnings Array of Api upload warnings
	 * @return array
	 */
	private function getStashResult( $warnings ) {
		$result = [];
		$result['result'] = 'Success';
		if ( $warnings && count( $warnings ) > 0 ) {
			$result['warnings'] = $warnings;
		}
		// Some uploads can request they be stashed, so as not to publish them immediately.
		// In this case, a failure to stash ought to be fatal
		$this->performStash( 'critical', $result );

		return $result;
	}

	/**
	 * Get Warnings Result
	 * @param array $warnings Array of Api upload warnings
	 * @return array
	 */
	private function getWarningsResult( $warnings ) {
		$result = [];
		$result['result'] = 'Warning';
		$result['warnings'] = $warnings;
		// in case the warnings can be fixed with some further user action, let's stash this upload
		// and return a key they can use to restart it
		$this->performStash( 'optional', $result );

		return $result;
	}

	/**
	 * @since 1.35
	 * @see $wgMinUploadChunkSize
	 * @param Config $config Site configuration for MinUploadChunkSize
	 * @return int
	 */
	public static function getMinUploadChunkSize( Config $config ) {
		$configured = $config->get( MainConfigNames::MinUploadChunkSize );

		// Leave some room for other POST parameters
		$postMax = (
			wfShorthandToInteger(
				ini_get( 'post_max_size' ),
				PHP_INT_MAX
			) ?: PHP_INT_MAX
		) - 1024;

		// Ensure the minimum chunk size is less than PHP upload limits
		// or the maximum upload size.
		return min(
			$configured,
			UploadBase::getMaxUploadSize( 'file' ),
			UploadBase::getMaxPhpUploadSize(),
			$postMax
		);
	}

	/**
	 * Get the result of a chunk upload.
	 * @param array $warnings Array of Api upload warnings
	 * @return array
	 */
	private function getChunkResult( $warnings ) {
		$result = [];

		if ( $warnings && count( $warnings ) > 0 ) {
			$result['warnings'] = $warnings;
		}

		$chunkUpload = $this->getMain()->getUpload( 'chunk' );
		$chunkPath = $chunkUpload->getTempName();
		$chunkSize = $chunkUpload->getSize();
		$totalSoFar = $this->mParams['offset'] + $chunkSize;
		$minChunkSize = self::getMinUploadChunkSize( $this->getConfig() );

		// Double check sizing
		if ( $totalSoFar > $this->mParams['filesize'] ) {
			$this->dieWithError( 'apierror-invalid-chunk' );
		}

		// Enforce minimum chunk size
		if ( $totalSoFar != $this->mParams['filesize'] && $chunkSize < $minChunkSize ) {
			$this->dieWithError( [ 'apierror-chunk-too-small', Message::numParam( $minChunkSize ) ] );
		}

		if ( $this->mParams['offset'] == 0 ) {
			$this->log->debug( "Started first chunk of chunked upload of {filename} for {user}",
				[
					'user' => $this->getUser()->getName(),
					'filename' => $this->mParams['filename'] ?? '-',
					'filesize' => $this->mParams['filesize'],
					'chunkSize' => $chunkSize
				]
			);
			$filekey = $this->performStash( 'critical' );
		} else {
			$filekey = $this->mParams['filekey'];

			// Don't allow further uploads to an already-completed session
			$progress = UploadBase::getSessionStatus( $this->getUser(), $filekey );
			if ( !$progress ) {
				// Probably can't get here, but check anyway just in case
				$this->log->info( "Stash failed due to no session for {user}",
					[
						'user' => $this->getUser()->getName(),
						'filename' => $this->mParams['filename'] ?? '-',
						'filekey' => $this->mParams['filekey'] ?? '-',
						'filesize' => $this->mParams['filesize'],
						'chunkSize' => $chunkSize
					]
				);
				$this->dieWithError( 'apierror-stashfailed-nosession', 'stashfailed' );
			} elseif ( $progress['result'] !== 'Continue' || $progress['stage'] !== 'uploading' ) {
				$this->dieWithError( 'apierror-stashfailed-complete', 'stashfailed' );
			}

			$status = $this->mUpload->addChunk(
				$chunkPath, $chunkSize, $this->mParams['offset'] );
			if ( !$status->isGood() ) {
				$extradata = [
					'offset' => $this->mUpload->getOffset(),
				];
				$this->log->info( "Chunked upload stash failure {status} for {user}",
					[
						'status' => (string)$status,
						'user' => $this->getUser()->getName(),
						'filename' => $this->mParams['filename'] ?? '-',
						'filekey' => $this->mParams['filekey'] ?? '-',
						'filesize' => $this->mParams['filesize'],
						'chunkSize' => $chunkSize,
						'offset' => $this->mUpload->getOffset()
					]
				);
				$this->dieStatusWithCode( $status, 'stashfailed', $extradata );
			} else {
				$this->log->debug( "Got chunk for {filename} with offset {offset} for {user}",
					[
						'user' => $this->getUser()->getName(),
						'filename' => $this->mParams['filename'] ?? '-',
						'filekey' => $this->mParams['filekey'] ?? '-',
						'filesize' => $this->mParams['filesize'],
						'chunkSize' => $chunkSize,
						'offset' => $this->mUpload->getOffset()
					]
				);
			}
		}

		// Check we added the last chunk:
		if ( $totalSoFar == $this->mParams['filesize'] ) {
			if ( $this->mParams['async'] ) {
				UploadBase::setSessionStatus(
					$this->getUser(),
					$filekey,
					[ 'result' => 'Poll',
						'stage' => 'queued', 'status' => Status::newGood() ]
				);
				// It is important that this be lazyPush, as we do not want to insert
				// into job queue until after the current transaction has completed since
				// this depends on values in uploadstash table that were updated during
				// the current transaction. (T350917)
				$this->jobQueueGroup->lazyPush( new AssembleUploadChunksJob( [
					'filename' => $this->mParams['filename'],
					'filekey' => $filekey,
					'filesize' => $this->mParams['filesize'],
					'session' => $this->getContext()->exportSession()
				] ) );
				$this->log->info( "Received final chunk of {filename} for {user}, queuing assemble job",
					[
						'user' => $this->getUser()->getName(),
						'filename' => $this->mParams['filename'] ?? '-',
						'filekey' => $this->mParams['filekey'] ?? '-',
						'filesize' => $this->mParams['filesize'],
						'chunkSize' => $chunkSize,
					]
				);
				$result['result'] = 'Poll';
				$result['stage'] = 'queued';
			} else {
				$this->log->info( "Received final chunk of {filename} for {user}, assembling immediately",
					[
						'user' => $this->getUser()->getName(),
						'filename' => $this->mParams['filename'] ?? '-',
						'filekey' => $this->mParams['filekey'] ?? '-',
						'filesize' => $this->mParams['filesize'],
						'chunkSize' => $chunkSize,
					]
				);

				$status = $this->mUpload->concatenateChunks();
				if ( !$status->isGood() ) {
					UploadBase::setSessionStatus(
						$this->getUser(),
						$filekey,
						[ 'result' => 'Failure', 'stage' => 'assembling', 'status' => $status ]
					);
					$this->log->info( "Non jobqueue assembly of {filename} failed because {status}",
						[
							'user' => $this->getUser()->getName(),
							'filename' => $this->mParams['filename'] ?? '-',
							'filekey' => $this->mParams['filekey'] ?? '-',
							'filesize' => $this->mParams['filesize'],
							'chunkSize' => $chunkSize,
							'status' => (string)$status
						]
					);
					$this->dieStatusWithCode( $status, 'stashfailed' );
				}

				// We can only get warnings like 'duplicate' after concatenating the chunks
				$warnings = $this->getApiWarnings();
				if ( $warnings ) {
					$result['warnings'] = $warnings;
				}

				// The fully concatenated file has a new filekey. So remove
				// the old filekey and fetch the new one.
				UploadBase::setSessionStatus( $this->getUser(), $filekey, false );
				$this->mUpload->stash->removeFile( $filekey );
				$filekey = $this->mUpload->getStashFile()->getFileKey();

				$result['result'] = 'Success';
			}
		} else {
			UploadBase::setSessionStatus(
				$this->getUser(),
				$filekey,
				[
					'result' => 'Continue',
					'stage' => 'uploading',
					'offset' => $totalSoFar,
					'status' => Status::newGood(),
				]
			);
			$result['result'] = 'Continue';
			$result['offset'] = $totalSoFar;
		}

		$result['filekey'] = $filekey;

		return $result;
	}

	/**
	 * Stash the file and add the file key, or error information if it fails, to the data.
	 *
	 * @param string $failureMode What to do on failure to stash:
	 *   - When 'critical', use dieStatus() to produce an error response and throw an exception.
	 *     Use this when stashing the file was the primary purpose of the API request.
	 *   - When 'optional', only add a 'stashfailed' key to the data and return null.
	 *     Use this when some error happened for a non-stash upload and we're stashing the file
	 *     only to save the client the trouble of re-uploading it.
	 * @param array|null &$data API result to which to add the information
	 * @return string|null File key
	 */
	private function performStash( $failureMode, &$data = null ) {
		$isPartial = (bool)$this->mParams['chunk'];
		try {
			$status = $this->mUpload->tryStashFile( $this->getUser(), $isPartial );

			if ( $status->isGood() && !$status->getValue() ) {
				// Not actually a 'good' status...
				$status->fatal( new ApiMessage( 'apierror-stashinvalidfile', 'stashfailed' ) );
			}
		} catch ( Exception $e ) {
			$debugMessage = 'Stashing temporary file failed: ' . get_class( $e ) . ' ' . $e->getMessage();
			$this->log->info( $debugMessage,
				[
					'user' => $this->getUser()->getName(),
					'filename' => $this->mParams['filename'] ?? '-',
					'filekey' => $this->mParams['filekey'] ?? '-'
				]
			);

			$status = Status::newFatal( $this->getErrorFormatter()->getMessageFromException(
				$e, [ 'wrap' => new ApiMessage( 'apierror-stashexception', 'stashfailed' ) ]
			) );
		}

		if ( $status->isGood() ) {
			$stashFile = $status->getValue();
			$data['filekey'] = $stashFile->getFileKey();
			// Backwards compatibility
			$data['sessionkey'] = $data['filekey'];
			return $data['filekey'];
		}

		if ( $status->getMessage()->getKey() === 'uploadstash-exception' ) {
			// The exceptions thrown by upload stash code and pretty silly and UploadBase returns poor
			// Statuses for it. Just extract the exception details and parse them ourselves.
			[ $exceptionType, $message ] = $status->getMessage()->getParams();
			$debugMessage = 'Stashing temporary file failed: ' . $exceptionType . ' ' . $message;
			$this->log->info( $debugMessage,
				[
					'user' => $this->getUser()->getName(),
					'filename' => $this->mParams['filename'] ?? '-',
					'filekey' => $this->mParams['filekey'] ?? '-'
				]
			);
		}

		$this->log->info( "Stash upload failure {status}",
			[
				'status' => (string)$status,
				'user' => $this->getUser()->getName(),
				'filename' => $this->mParams['filename'] ?? '-',
				'filekey' => $this->mParams['filekey'] ?? '-'
			]
		);
		// Bad status
		if ( $failureMode !== 'optional' ) {
			$this->dieStatus( $status );
		} else {
			$data['stasherrors'] = $this->getErrorFormatter()->arrayFromStatus( $status );
			return null;
		}
	}

	/**
	 * Throw an error that the user can recover from by providing a better
	 * value for $parameter
	 *
	 * @param MessageSpecifier[] $errors
	 * @param string|null $parameter Parameter that needs revising
	 * @throws ApiUsageException
	 * @return never
	 */
	private function dieRecoverableError( $errors, $parameter = null ) {
		$this->performStash( 'optional', $data );

		if ( $parameter ) {
			$data['invalidparameter'] = $parameter;
		}

		$sv = StatusValue::newGood();
		foreach ( $errors as $error ) {
			$msg = ApiMessage::create( $error );
			$msg->setApiData( $msg->getApiData() + $data );
			$sv->fatal( $msg );
		}
		$this->dieStatus( $sv );
	}

	/**
	 * Like dieStatus(), but always uses $overrideCode for the error code, unless the code comes from
	 * IApiMessage.
	 *
	 * @param Status $status
	 * @param string $overrideCode Error code to use if there isn't one from IApiMessage
	 * @param array|null $moreExtraData
	 * @throws ApiUsageException
	 * @return never
	 */
	public function dieStatusWithCode( $status, $overrideCode, $moreExtraData = null ) {
		$sv = StatusValue::newGood();
		foreach ( $status->getMessages() as $error ) {
			$msg = ApiMessage::create( $error, $overrideCode );
			if ( $moreExtraData ) {
				$msg->setApiData( $msg->getApiData() + $moreExtraData );
			}
			$sv->fatal( $msg );
		}
		$this->dieStatus( $sv );
	}

	/**
	 * Select an upload module and set it to mUpload. Dies on failure. If the
	 * request was a status request and not a true upload, returns false;
	 * otherwise true
	 *
	 * @return bool
	 */
	protected function selectUploadModule() {
		// chunk or one and only one of the following parameters is needed
		if ( !$this->mParams['chunk'] ) {
			$this->requireOnlyOneParameter( $this->mParams,
				'filekey', 'file', 'url' );
		}

		// Status report for "upload to stash"/"upload from stash"/"upload by url"
		if ( $this->mParams['checkstatus'] && ( $this->mParams['filekey'] || $this->mParams['url'] ) ) {
			$statusKey = $this->mParams['filekey'] ?: UploadFromUrl::getCacheKey( $this->mParams );
			$progress = UploadBase::getSessionStatus( $this->getUser(), $statusKey );
			if ( !$progress ) {
				$this->log->info( "Cannot check upload status due to missing upload session for {user}",
					[
						'user' => $this->getUser()->getName(),
						'filename' => $this->mParams['filename'] ?? '-',
						'filekey' => $this->mParams['filekey'] ?? '-'
					]
				);
				$this->dieWithError( 'apierror-upload-missingresult', 'missingresult' );
			} elseif ( !$progress['status']->isGood() ) {
				$this->dieStatusWithCode( $progress['status'], 'stashfailed' );
			}
			if ( isset( $progress['status']->value['verification'] ) ) {
				$this->checkVerification( $progress['status']->value['verification'] );
			}
			if ( isset( $progress['status']->value['warnings'] ) ) {
				$warnings = $this->transformWarnings( $progress['status']->value['warnings'] );
				if ( $warnings ) {
					$progress['warnings'] = $warnings;
				}
			}
			unset( $progress['status'] ); // remove Status object
			$imageinfo = null;
			if ( isset( $progress['imageinfo'] ) ) {
				$imageinfo = $progress['imageinfo'];
				unset( $progress['imageinfo'] );
			}

			$this->getResult()->addValue( null, $this->getModuleName(), $progress );
			// Add 'imageinfo' in a separate addValue() call. File metadata can be unreasonably large,
			// so otherwise when it exceeded $wgAPIMaxResultSize, no result would be returned (T143993).
			if ( $imageinfo ) {
				$this->getResult()->addValue( $this->getModuleName(), 'imageinfo', $imageinfo );
			}

			return false;
		}

		// The following modules all require the filename parameter to be set
		if ( $this->mParams['filename'] === null ) {
			$this->dieWithError( [ 'apierror-missingparam', 'filename' ] );
		}

		if ( $this->mParams['chunk'] ) {
			// Chunk upload
			$this->mUpload = new UploadFromChunks( $this->getUser() );
			if ( isset( $this->mParams['filekey'] ) ) {
				if ( $this->mParams['offset'] === 0 ) {
					$this->dieWithError( 'apierror-upload-filekeynotallowed', 'filekeynotallowed' );
				}

				// handle new chunk
				$this->mUpload->continueChunks(
					$this->mParams['filename'],
					$this->mParams['filekey'],
					$this->getMain()->getUpload( 'chunk' )
				);
			} else {
				if ( $this->mParams['offset'] !== 0 ) {
					$this->dieWithError( 'apierror-upload-filekeyneeded', 'filekeyneeded' );
				}

				// handle first chunk
				$this->mUpload->initialize(
					$this->mParams['filename'],
					$this->getMain()->getUpload( 'chunk' )
				);
			}
		} elseif ( isset( $this->mParams['filekey'] ) ) {
			// Upload stashed in a previous request
			if ( !UploadFromStash::isValidKey( $this->mParams['filekey'] ) ) {
				$this->dieWithError( 'apierror-invalid-file-key' );
			}

			$this->mUpload = new UploadFromStash( $this->getUser() );
			// This will not download the temp file in initialize() in async mode.
			// We still have enough information to call checkWarnings() and such.
			$this->mUpload->initialize(
				$this->mParams['filekey'], $this->mParams['filename'], !$this->mParams['async']
			);
		} elseif ( isset( $this->mParams['file'] ) ) {
			// Can't async upload directly from a POSTed file, we'd have to
			// stash the file and then queue the publish job. The user should
			// just submit the two API queries to perform those two steps.
			if ( $this->mParams['async'] ) {
				$this->dieWithError( 'apierror-cannot-async-upload-file' );
			}

			$this->mUpload = new UploadFromFile();
			$this->mUpload->initialize(
				$this->mParams['filename'],
				$this->getMain()->getUpload( 'file' )
			);
		} elseif ( isset( $this->mParams['url'] ) ) {
			// Make sure upload by URL is enabled:
			if ( !UploadFromUrl::isEnabled() ) {
				$this->dieWithError( 'copyuploaddisabled' );
			}

			if ( !UploadFromUrl::isAllowedHost( $this->mParams['url'] ) ) {
				$this->dieWithError( 'apierror-copyuploadbaddomain' );
			}

			if ( !UploadFromUrl::isAllowedUrl( $this->mParams['url'] ) ) {
				$this->dieWithError( 'apierror-copyuploadbadurl' );
			}

			$this->mUpload = new UploadFromUrl;
			$this->mUpload->initialize( $this->mParams['filename'],
				$this->mParams['url'] );
		}

		return true;
	}

	/**
	 * Checks that the user has permissions to perform this upload.
	 * Dies with usage message on inadequate permissions.
	 * @param User $user The user to check.
	 */
	protected function checkPermissions( $user ) {
		// Check whether the user has the appropriate permissions to upload anyway
		$permission = $this->mUpload->isAllowed( $user );

		if ( $permission !== true ) {
			if ( !$user->isNamed() ) {
				$this->dieWithError( [ 'apierror-mustbeloggedin', $this->msg( 'action-upload' ) ] );
			}

			$this->dieStatus( User::newFatalPermissionDeniedStatus( $permission ) );
		}

		// Check blocks
		if ( $user->isBlockedFromUpload() ) {
			// @phan-suppress-next-line PhanTypeMismatchArgumentNullable Block is checked and not null
			$this->dieBlocked( $user->getBlock() );
		}
	}

	/**
	 * Performs file verification, dies on error.
	 */
	protected function verifyUpload() {
		if ( $this->mParams['chunk'] ) {
			$maxSize = UploadBase::getMaxUploadSize();
			if ( $this->mParams['filesize'] > $maxSize ) {
				$this->dieWithError( 'file-too-large' );
			}
			if ( !$this->mUpload->getTitle() ) {
				$this->dieWithError( 'illegal-filename' );
			}
			// file will be assembled after having uploaded the last chunk,
			// so we can only validate the name at this point
			$verification = $this->mUpload->validateName();
			if ( $verification === true ) {
				return;
			}
		} elseif ( $this->mParams['async'] && ( $this->mParams['filekey'] || $this->mParams['url'] ) ) {
			// file will be assembled/downloaded in a background process, so we
			// can only validate the name at this point
			// file verification will happen in background process
			$verification = $this->mUpload->validateName();
			if ( $verification === true ) {
				return;
			}
		} else {
			wfDebug( __METHOD__ . " about to verify" );

			$verification = $this->mUpload->verifyUpload();

			if ( $verification['status'] === UploadBase::OK ) {
				return;
			} else {
				$this->log->info( "File verification of {filename} failed for {user} because {result}",
					[
						'user' => $this->getUser()->getName(),
						'resultCode' => $verification['status'],
						'result' => $this->mUpload->getVerificationErrorCode( $verification['status'] ),
						'filename' => $this->mParams['filename'] ?? '-',
						'details' => $verification['details'] ?? ''
					]
				);
			}
		}

		$this->checkVerification( $verification );
	}

	/**
	 * Performs file verification, dies on error.
	 * @param array $verification
	 * @return never
	 */
	protected function checkVerification( array $verification ) {
		$status = $this->mUpload->convertVerifyErrorToStatus( $verification );
		if ( $status->isRecoverableError() ) {
			$this->dieRecoverableError( [ $status->asApiMessage() ], $status->getInvalidParameter() );
			// dieRecoverableError prevents continuation
		}
		$this->dieWithError( $status->asApiMessage() );
		// dieWithError prevents continuation
	}

	/**
	 * Check warnings.
	 * Returns a suitable array for inclusion into API results if there were warnings
	 * Returns the empty array if there were no warnings
	 *
	 * @return array
	 */
	protected function getApiWarnings() {
		$warnings = UploadBase::makeWarningsSerializable(
			$this->mUpload->checkWarnings( $this->getUser() )
		);

		return $this->transformWarnings( $warnings );
	}

	protected function transformWarnings( array $warnings ): array {
		if ( $warnings ) {
			// Add indices
			ApiResult::setIndexedTagName( $warnings, 'warning' );

			if ( isset( $warnings['duplicate'] ) ) {
				$dupes = array_column( $warnings['duplicate'], 'fileName' );
				ApiResult::setIndexedTagName( $dupes, 'duplicate' );
				$warnings['duplicate'] = $dupes;
			}

			if ( isset( $warnings['exists'] ) ) {
				$warning = $warnings['exists'];
				unset( $warnings['exists'] );
				$localFile = $warning['normalizedFile'] ?? $warning['file'];
				$warnings[$warning['warning']] = $localFile['fileName'];
			}

			if ( isset( $warnings['no-change'] ) ) {
				$file = $warnings['no-change'];
				unset( $warnings['no-change'] );

				$warnings['nochange'] = [
					'timestamp' => wfTimestamp( TS_ISO_8601, $file['timestamp'] )
				];
			}

			if ( isset( $warnings['duplicate-version'] ) ) {
				$dupes = [];
				foreach ( $warnings['duplicate-version'] as $dupe ) {
					$dupes[] = [
						'timestamp' => wfTimestamp( TS_ISO_8601, $dupe['timestamp'] )
					];
				}
				unset( $warnings['duplicate-version'] );

				ApiResult::setIndexedTagName( $dupes, 'ver' );
				$warnings['duplicateversions'] = $dupes;
			}
			// We haven't downloaded the file, so this will result in an empty file warning
			if ( $this->mParams['async'] && $this->mParams['url'] ) {
				unset( $warnings['empty-file'] );
			}
		}

		return $warnings;
	}

	/**
	 * Handles a stash exception, giving a useful error to the user.
	 * @todo Internationalize the exceptions then get rid of this
	 * @param Exception $e
	 * @return StatusValue
	 */
	protected function handleStashException( $e ) {
		$this->log->info( "Upload stashing of {filename} failed for {user} because {error}",
			[
				'user' => $this->getUser()->getName(),
				'error' => get_class( $e ),
				'filename' => $this->mParams['filename'] ?? '-',
				'filekey' => $this->mParams['filekey'] ?? '-'
			]
		);

		switch ( get_class( $e ) ) {
			case UploadStashFileNotFoundException::class:
				$wrap = 'apierror-stashedfilenotfound';
				break;
			case UploadStashBadPathException::class:
				$wrap = 'apierror-stashpathinvalid';
				break;
			case UploadStashFileException::class:
				$wrap = 'apierror-stashfilestorage';
				break;
			case UploadStashZeroLengthFileException::class:
				$wrap = 'apierror-stashzerolength';
				break;
			case UploadStashNotLoggedInException::class:
				return StatusValue::newFatal( ApiMessage::create(
					[ 'apierror-mustbeloggedin', $this->msg( 'action-upload' ) ], 'stashnotloggedin'
				) );
			case UploadStashWrongOwnerException::class:
				$wrap = 'apierror-stashwrongowner';
				break;
			case UploadStashNoSuchKeyException::class:
				$wrap = 'apierror-stashnosuchfilekey';
				break;
			default:
				$wrap = [ 'uploadstash-exception', get_class( $e ) ];
				break;
		}
		return StatusValue::newFatal(
			$this->getErrorFormatter()->getMessageFromException( $e, [ 'wrap' => $wrap ] )
		);
	}

	/**
	 * Perform the actual upload. Returns a suitable result array on success;
	 * dies on failure.
	 *
	 * @param array $warnings Array of Api upload warnings
	 * @return array
	 */
	protected function performUpload( $warnings ) {
		// Use comment as initial page text by default
		$this->mParams['text'] ??= $this->mParams['comment'];

		/** @var LocalFile $file */
		$file = $this->mUpload->getLocalFile();
		$user = $this->getUser();
		$title = $file->getTitle();

		// for preferences mode, we want to watch if 'watchdefault' is set,
		// or if the *file* doesn't exist, and either 'watchuploads' or
		// 'watchcreations' is set. But getWatchlistValue()'s automatic
		// handling checks if the *title* exists or not, so we need to check
		// all three preferences manually.
		$watch = $this->getWatchlistValue(
			$this->mParams['watchlist'], $title, $user, 'watchdefault'
		);

		if ( !$watch && $this->mParams['watchlist'] == 'preferences' && !$file->exists() ) {
			$watch = (
				$this->getWatchlistValue( 'preferences', $title, $user, 'watchuploads' ) ||
				$this->getWatchlistValue( 'preferences', $title, $user, 'watchcreations' )
			);
		}
		$watchlistExpiry = $this->getExpiryFromParams( $this->mParams, $title, $user );

		// Deprecated parameters
		if ( $this->mParams['watch'] ) {
			$watch = true;
		}

		if ( $this->mParams['tags'] ) {
			$status = ChangeTags::canAddTagsAccompanyingChange( $this->mParams['tags'], $this->getAuthority() );
			if ( !$status->isOK() ) {
				$this->dieStatus( $status );
			}
		}

		// No errors, no warnings: do the upload
		$result = [];
		if ( $this->mParams['async'] ) {
			// Only stash uploads and copy uploads support async
			if ( $this->mParams['filekey'] ) {
				$job = new PublishStashedFileJob(
					[
						'filename' => $this->mParams['filename'],
						'filekey' => $this->mParams['filekey'],
						'comment' => $this->mParams['comment'],
						'tags' => $this->mParams['tags'] ?? [],
						'text' => $this->mParams['text'],
						'watch' => $watch,
						'watchlistexpiry' => $watchlistExpiry,
						'session' => $this->getContext()->exportSession(),
						'ignorewarnings' => $this->mParams['ignorewarnings']
					]
					);
			} elseif ( $this->mParams['url'] ) {
				$job = new UploadFromUrlJob(
					[
						'filename' => $this->mParams['filename'],
						'url' => $this->mParams['url'],
						'comment' => $this->mParams['comment'],
						'tags' => $this->mParams['tags'] ?? [],
						'text' => $this->mParams['text'],
						'watch' => $watch,
						'watchlistexpiry' => $watchlistExpiry,
						'session' => $this->getContext()->exportSession(),
						'ignorewarnings' => $this->mParams['ignorewarnings']
					]
					);
			} else {
				$this->dieWithError( 'apierror-no-async-support', 'publishfailed' );
				// We will never reach this, but it's here to help phan figure out
				// $job is never null
				// @phan-suppress-next-line PhanPluginUnreachableCode On purpose
				return [];
			}
			$cacheKey = $job->getCacheKey();
			// Check if an upload is already in progress.
			// the result can be Poll / Failure / Success
			$progress = UploadBase::getSessionStatus( $this->getUser(), $cacheKey );
			if ( $progress && $progress['result'] === 'Poll' ) {
				$this->dieWithError( 'apierror-upload-inprogress', 'publishfailed' );
			}
			UploadBase::setSessionStatus(
				$this->getUser(),
				$cacheKey,
				[ 'result' => 'Poll', 'stage' => 'queued', 'status' => Status::newGood() ]
			);

			$this->jobQueueGroup->push( $job );
			$this->log->info( "Sending publish job of {filename} for {user}",
				[
					'user' => $this->getUser()->getName(),
					'filename' => $this->mParams['filename'] ?? '-'
				]
			);
			$result['result'] = 'Poll';
			$result['stage'] = 'queued';
		} else {
			/** @var Status $status */
			$status = $this->mUpload->performUpload(
				$this->mParams['comment'],
				$this->mParams['text'],
				$watch,
				$this->getUser(),
				$this->mParams['tags'] ?? [],
				$watchlistExpiry
			);

			if ( !$status->isGood() ) {
				$this->log->info( "Non-async API upload publish failed for {user} because {status}",
					[
						'user' => $this->getUser()->getName(),
						'filename' => $this->mParams['filename'] ?? '-',
						'filekey' => $this->mParams['filekey'] ?? '-',
						'status' => (string)$status
					]
				);
				$this->dieRecoverableError( $status->getMessages() );
			}
			$result['result'] = 'Success';
		}

		$result['filename'] = $file->getName();
		if ( $warnings && count( $warnings ) > 0 ) {
			$result['warnings'] = $warnings;
		}

		return $result;
	}

	public function mustBePosted() {
		return true;
	}

	public function isWriteMode() {
		return true;
	}

	public function getAllowedParams() {
		$params = [
			'filename' => [
				ParamValidator::PARAM_TYPE => 'string',
			],
			'comment' => [
				ParamValidator::PARAM_DEFAULT => ''
			],
			'tags' => [
				ParamValidator::PARAM_TYPE => 'tags',
				ParamValidator::PARAM_ISMULTI => true,
			],
			'text' => [
				ParamValidator::PARAM_TYPE => 'text',
			],
			'watch' => [
				ParamValidator::PARAM_DEFAULT => false,
				ParamValidator::PARAM_DEPRECATED => true,
			],
		];

		// Params appear in the docs in the order they are defined,
		// which is why this is here and not at the bottom.
		$params += $this->getWatchlistParams( [
			'watch',
			'preferences',
			'nochange',
		] );

		$params += [
			'ignorewarnings' => false,
			'file' => [
				ParamValidator::PARAM_TYPE => 'upload',
			],
			'url' => null,
			'filekey' => null,
			'sessionkey' => [
				ParamValidator::PARAM_DEPRECATED => true,
			],
			'stash' => false,

			'filesize' => [
				ParamValidator::PARAM_TYPE => 'integer',
				IntegerDef::PARAM_MIN => 0,
				IntegerDef::PARAM_MAX => UploadBase::getMaxUploadSize(),
			],
			'offset' => [
				ParamValidator::PARAM_TYPE => 'integer',
				IntegerDef::PARAM_MIN => 0,
			],
			'chunk' => [
				ParamValidator::PARAM_TYPE => 'upload',
			],

			'async' => false,
			'checkstatus' => false,
		];

		return $params;
	}

	public function needsToken() {
		return 'csrf';
	}

	protected function getExamplesMessages() {
		return [
			'action=upload&filename=Wiki.png' .
				'&url=http%3A//upload.wikimedia.org/wikipedia/en/b/bc/Wiki.png&token=123ABC'
				=> 'apihelp-upload-example-url',
			'action=upload&filename=Wiki.png&filekey=filekey&ignorewarnings=1&token=123ABC'
				=> 'apihelp-upload-example-filekey',
		];
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Upload';
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiUpload::class, 'ApiUpload' );
