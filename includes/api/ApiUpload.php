<?php
/**
 *
 *
 * Created on Aug 21, 2008
 *
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
 * @ingroup API
 */
class ApiUpload extends ApiBase {
	/** @var UploadBase|UploadFromChunks */
	protected $mUpload = null;

	protected $mParams;

	public function execute() {
		// Check whether upload is enabled
		if ( !UploadBase::isEnabled() ) {
			$this->dieUsageMsg( 'uploaddisabled' );
		}

		$user = $this->getUser();

		// Parameter handling
		$this->mParams = $this->extractRequestParams();
		$request = $this->getMain()->getRequest();
		// Check if async mode is actually supported (jobs done in cli mode)
		$this->mParams['async'] = ( $this->mParams['async'] && $this->getConfig()->get( 'EnableAsyncUploads' ) );
		// Add the uploaded file to the params array
		$this->mParams['file'] = $request->getFileName( 'file' );
		$this->mParams['chunk'] = $request->getFileName( 'chunk' );

		// Copy the session key to the file key, for backward compatibility.
		if ( !$this->mParams['filekey'] && $this->mParams['sessionkey'] ) {
			$this->logFeatureUsage( 'action=upload&sessionkey' );
			$this->mParams['filekey'] = $this->mParams['sessionkey'];
		}

		// Select an upload module
		try {
			if ( !$this->selectUploadModule() ) {
				return; // not a true upload, but a status request or similar
			} elseif ( !isset( $this->mUpload ) ) {
				$this->dieUsage( 'No upload module set', 'nomodule' );
			}
		} catch ( UploadStashException $e ) { // XXX: don't spam exception log
			$this->handleStashException( $e );
		}

		// First check permission to upload
		$this->checkPermissions( $user );

		// Fetch the file (usually a no-op)
		/** @var $status Status */
		$status = $this->mUpload->fetchFile();
		if ( !$status->isGood() ) {
			$errors = $status->getErrorsArray();
			$error = array_shift( $errors[0] );
			$this->dieUsage( 'Error fetching file from remote source', $error, 0, $errors[0] );
		}

		// Check if the uploaded file is sane
		if ( $this->mParams['chunk'] ) {
			$maxSize = UploadBase::getMaxUploadSize();
			if ( $this->mParams['filesize'] > $maxSize ) {
				$this->dieUsage( 'The file you submitted was too large', 'file-too-large' );
			}
			if ( !$this->mUpload->getTitle() ) {
				$this->dieUsage( 'Invalid file title supplied', 'internal-error' );
			}
		} elseif ( $this->mParams['async'] && $this->mParams['filekey'] ) {
			// defer verification to background process
		} else {
			wfDebug( __METHOD__ . " about to verify\n" );
			$this->verifyUpload();
		}

		// Check if the user has the rights to modify or overwrite the requested title
		// (This check is irrelevant if stashing is already requested, since the errors
		//  can always be fixed by changing the title)
		if ( !$this->mParams['stash'] ) {
			$permErrors = $this->mUpload->verifyTitlePermissions( $user );
			if ( $permErrors !== true ) {
				$this->dieRecoverableError( $permErrors[0], 'filename' );
			}
		}

		// Get the result based on the current upload context:
		try {
			$result = $this->getContextResult();
			if ( $result['result'] === 'Success' ) {
				$result['imageinfo'] = $this->mUpload->getImageInfo( $this->getResult() );
			}
		} catch ( UploadStashException $e ) { // XXX: don't spam exception log
			$this->handleStashException( $e );
		}

		$this->getResult()->addValue( null, $this->getModuleName(), $result );

		// Cleanup any temporary mess
		$this->mUpload->cleanupTempFile();
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

		// Check throttle after we've handled warnings
		if ( UploadBase::isThrottled( $this->getUser() )
		) {
			$this->dieUsageMsg( 'actionthrottledtext' );
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
		$result = array();
		// Some uploads can request they be stashed, so as not to publish them immediately.
		// In this case, a failure to stash ought to be fatal
		try {
			$result['result'] = 'Success';
			$result['filekey'] = $this->performStash();
			$result['sessionkey'] = $result['filekey']; // backwards compatibility
			if ( $warnings && count( $warnings ) > 0 ) {
				$result['warnings'] = $warnings;
			}
		} catch ( UploadStashException $e ) {
			$this->handleStashException( $e );
		} catch ( Exception $e ) {
			$this->dieUsage( $e->getMessage(), 'stashfailed' );
		}

		return $result;
	}

	/**
	 * Get Warnings Result
	 * @param array $warnings Array of Api upload warnings
	 * @return array
	 */
	private function getWarningsResult( $warnings ) {
		$result = array();
		$result['result'] = 'Warning';
		$result['warnings'] = $warnings;
		// in case the warnings can be fixed with some further user action, let's stash this upload
		// and return a key they can use to restart it
		try {
			$result['filekey'] = $this->performStash();
			$result['sessionkey'] = $result['filekey']; // backwards compatibility
		} catch ( Exception $e ) {
			$result['warnings']['stashfailed'] = $e->getMessage();
		}

		return $result;
	}

	/**
	 * Get the result of a chunk upload.
	 * @param array $warnings Array of Api upload warnings
	 * @return array
	 */
	private function getChunkResult( $warnings ) {
		$result = array();

		if ( $warnings && count( $warnings ) > 0 ) {
			$result['warnings'] = $warnings;
		}

		$request = $this->getMain()->getRequest();
		$chunkPath = $request->getFileTempname( 'chunk' );
		$chunkSize = $request->getUpload( 'chunk' )->getSize();
		$totalSoFar = $this->mParams['offset'] + $chunkSize;
		$minChunkSize = $this->getConfig()->get( 'MinUploadChunkSize' );

		// Sanity check sizing
		if ( $totalSoFar > $this->mParams['filesize'] ) {
			$this->dieUsage(
				'Offset plus current chunk is greater than claimed file size', 'invalid-chunk'
			);
		}

		// Enforce minimum chunk size
		if ( $totalSoFar != $this->mParams['filesize'] && $chunkSize < $minChunkSize ) {
			$this->dieUsage(
				"Minimum chunk size is $minChunkSize bytes for non-final chunks", 'chunk-too-small'
			);
		}

		if ( $this->mParams['offset'] == 0 ) {
			try {
				$filekey = $this->performStash();
			} catch ( UploadStashException $e ) {
				$this->handleStashException( $e );
			} catch ( Exception $e ) {
				// FIXME: Error handling here is wrong/different from rest of this
				$this->dieUsage( $e->getMessage(), 'stashfailed' );
			}
		} else {
			$filekey = $this->mParams['filekey'];

			// Don't allow further uploads to an already-completed session
			$progress = UploadBase::getSessionStatus( $this->getUser(), $filekey );
			if ( !$progress ) {
				// Probably can't get here, but check anyway just in case
				$this->dieUsage( 'No chunked upload session with this key', 'stashfailed' );
			} elseif ( $progress['result'] !== 'Continue' || $progress['stage'] !== 'uploading' ) {
				$this->dieUsage(
					'Chunked upload is already completed, check status for details', 'stashfailed'
				);
			}

			$status = $this->mUpload->addChunk(
				$chunkPath, $chunkSize, $this->mParams['offset'] );
			if ( !$status->isGood() ) {
				$extradata = array(
					'offset' => $this->mUpload->getOffset(),
				);

				$this->dieUsage( $status->getWikiText(), 'stashfailed', 0, $extradata );
			}
		}

		// Check we added the last chunk:
		if ( $totalSoFar == $this->mParams['filesize'] ) {
			if ( $this->mParams['async'] ) {
				UploadBase::setSessionStatus(
					$this->getUser(),
					$filekey,
					array( 'result' => 'Poll',
						'stage' => 'queued', 'status' => Status::newGood() )
				);
				JobQueueGroup::singleton()->push( new AssembleUploadChunksJob(
					Title::makeTitle( NS_FILE, $filekey ),
					array(
						'filename' => $this->mParams['filename'],
						'filekey' => $filekey,
						'session' => $this->getContext()->exportSession()
					)
				) );
				$result['result'] = 'Poll';
				$result['stage'] = 'queued';
			} else {
				$status = $this->mUpload->concatenateChunks();
				if ( !$status->isGood() ) {
					UploadBase::setSessionStatus(
						$this->getUser(),
						$filekey,
						array( 'result' => 'Failure', 'stage' => 'assembling', 'status' => $status )
					);
					$this->dieUsage( $status->getWikiText(), 'stashfailed' );
				}

				// The fully concatenated file has a new filekey. So remove
				// the old filekey and fetch the new one.
				UploadBase::setSessionStatus( $this->getUser(), $filekey, false );
				$this->mUpload->stash->removeFile( $filekey );
				$filekey = $this->mUpload->getLocalFile()->getFileKey();

				$result['result'] = 'Success';
			}
		} else {
			UploadBase::setSessionStatus(
				$this->getUser(),
				$filekey,
				array(
					'result' => 'Continue',
					'stage' => 'uploading',
					'offset' => $totalSoFar,
					'status' => Status::newGood(),
				)
			);
			$result['result'] = 'Continue';
			$result['offset'] = $totalSoFar;
		}
		$result['filekey'] = $filekey;

		return $result;
	}

	/**
	 * Stash the file and return the file key
	 * Also re-raises exceptions with slightly more informative message strings (useful for API)
	 * @throws MWException
	 * @return string File key
	 */
	private function performStash() {
		try {
			$stashFile = $this->mUpload->stashFile( $this->getUser() );

			if ( !$stashFile ) {
				throw new MWException( 'Invalid stashed file' );
			}
			$fileKey = $stashFile->getFileKey();
		} catch ( Exception $e ) {
			$message = 'Stashing temporary file failed: ' . get_class( $e ) . ' ' . $e->getMessage();
			wfDebug( __METHOD__ . ' ' . $message . "\n" );
			$className = get_class( $e );
			throw new $className( $message );
		}

		return $fileKey;
	}

	/**
	 * Throw an error that the user can recover from by providing a better
	 * value for $parameter
	 *
	 * @param array $error Error array suitable for passing to dieUsageMsg()
	 * @param string $parameter Parameter that needs revising
	 * @param array $data Optional extra data to pass to the user
	 * @throws UsageException
	 */
	private function dieRecoverableError( $error, $parameter, $data = array() ) {
		try {
			$data['filekey'] = $this->performStash();
			$data['sessionkey'] = $data['filekey'];
		} catch ( Exception $e ) {
			$data['stashfailed'] = $e->getMessage();
		}
		$data['invalidparameter'] = $parameter;

		$parsed = $this->parseMsg( $error );
		$this->dieUsage( $parsed['info'], $parsed['code'], 0, $data );
	}

	/**
	 * Select an upload module and set it to mUpload. Dies on failure. If the
	 * request was a status request and not a true upload, returns false;
	 * otherwise true
	 *
	 * @return bool
	 */
	protected function selectUploadModule() {
		$request = $this->getMain()->getRequest();

		// chunk or one and only one of the following parameters is needed
		if ( !$this->mParams['chunk'] ) {
			$this->requireOnlyOneParameter( $this->mParams,
				'filekey', 'file', 'url', 'statuskey' );
		}

		// Status report for "upload to stash"/"upload from stash"
		if ( $this->mParams['filekey'] && $this->mParams['checkstatus'] ) {
			$progress = UploadBase::getSessionStatus( $this->getUser(), $this->mParams['filekey'] );
			if ( !$progress ) {
				$this->dieUsage( 'No result in status data', 'missingresult' );
			} elseif ( !$progress['status']->isGood() ) {
				$this->dieUsage( $progress['status']->getWikiText(), 'stashfailed' );
			}
			if ( isset( $progress['status']->value['verification'] ) ) {
				$this->checkVerification( $progress['status']->value['verification'] );
			}
			unset( $progress['status'] ); // remove Status object
			$this->getResult()->addValue( null, $this->getModuleName(), $progress );

			return false;
		}

		if ( $this->mParams['statuskey'] ) {
			$this->checkAsyncDownloadEnabled();

			// Status request for an async upload
			$sessionData = UploadFromUrlJob::getSessionData( $this->mParams['statuskey'] );
			if ( !isset( $sessionData['result'] ) ) {
				$this->dieUsage( 'No result in session data', 'missingresult' );
			}
			if ( $sessionData['result'] == 'Warning' ) {
				$sessionData['warnings'] = $this->transformWarnings( $sessionData['warnings'] );
				$sessionData['sessionkey'] = $this->mParams['statuskey'];
			}
			$this->getResult()->addValue( null, $this->getModuleName(), $sessionData );

			return false;
		}

		// The following modules all require the filename parameter to be set
		if ( is_null( $this->mParams['filename'] ) ) {
			$this->dieUsageMsg( array( 'missingparam', 'filename' ) );
		}

		if ( $this->mParams['chunk'] ) {
			// Chunk upload
			$this->mUpload = new UploadFromChunks();
			if ( isset( $this->mParams['filekey'] ) ) {
				if ( $this->mParams['offset'] === 0 ) {
					$this->dieUsage( 'Cannot supply a filekey when offset is 0', 'badparams' );
				}

				// handle new chunk
				$this->mUpload->continueChunks(
					$this->mParams['filename'],
					$this->mParams['filekey'],
					$request->getUpload( 'chunk' )
				);
			} else {
				if ( $this->mParams['offset'] !== 0 ) {
					$this->dieUsage( 'Must supply a filekey when offset is non-zero', 'badparams' );
				}

				// handle first chunk
				$this->mUpload->initialize(
					$this->mParams['filename'],
					$request->getUpload( 'chunk' )
				);
			}
		} elseif ( isset( $this->mParams['filekey'] ) ) {
			// Upload stashed in a previous request
			if ( !UploadFromStash::isValidKey( $this->mParams['filekey'] ) ) {
				$this->dieUsageMsg( 'invalid-file-key' );
			}

			$this->mUpload = new UploadFromStash( $this->getUser() );
			// This will not download the temp file in initialize() in async mode.
			// We still have enough information to call checkWarnings() and such.
			$this->mUpload->initialize(
				$this->mParams['filekey'], $this->mParams['filename'], !$this->mParams['async']
			);
		} elseif ( isset( $this->mParams['file'] ) ) {
			$this->mUpload = new UploadFromFile();
			$this->mUpload->initialize(
				$this->mParams['filename'],
				$request->getUpload( 'file' )
			);
		} elseif ( isset( $this->mParams['url'] ) ) {
			// Make sure upload by URL is enabled:
			if ( !UploadFromUrl::isEnabled() ) {
				$this->dieUsageMsg( 'copyuploaddisabled' );
			}

			if ( !UploadFromUrl::isAllowedHost( $this->mParams['url'] ) ) {
				$this->dieUsageMsg( 'copyuploadbaddomain' );
			}

			if ( !UploadFromUrl::isAllowedUrl( $this->mParams['url'] ) ) {
				$this->dieUsageMsg( 'copyuploadbadurl' );
			}

			$async = false;
			if ( $this->mParams['asyncdownload'] ) {
				$this->checkAsyncDownloadEnabled();

				if ( $this->mParams['leavemessage'] && !$this->mParams['ignorewarnings'] ) {
					$this->dieUsage( 'Using leavemessage without ignorewarnings is not supported',
						'missing-ignorewarnings' );
				}

				if ( $this->mParams['leavemessage'] ) {
					$async = 'async-leavemessage';
				} else {
					$async = 'async';
				}
			}
			$this->mUpload = new UploadFromUrl;
			$this->mUpload->initialize( $this->mParams['filename'],
				$this->mParams['url'], $async );
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
			if ( !$user->isLoggedIn() ) {
				$this->dieUsageMsg( array( 'mustbeloggedin', 'upload' ) );
			}

			$this->dieUsageMsg( 'badaccess-groups' );
		}
	}

	/**
	 * Performs file verification, dies on error.
	 */
	protected function verifyUpload() {
		$verification = $this->mUpload->verifyUpload();
		if ( $verification['status'] === UploadBase::OK ) {
			return;
		}

		$this->checkVerification( $verification );
	}

	/**
	 * Performs file verification, dies on error.
	 * @param array $verification
	 */
	protected function checkVerification( array $verification ) {
		// @todo Move them to ApiBase's message map
		switch ( $verification['status'] ) {
			// Recoverable errors
			case UploadBase::MIN_LENGTH_PARTNAME:
				$this->dieRecoverableError( 'filename-tooshort', 'filename' );
				break;
			case UploadBase::ILLEGAL_FILENAME:
				$this->dieRecoverableError( 'illegal-filename', 'filename',
					array( 'filename' => $verification['filtered'] ) );
				break;
			case UploadBase::FILENAME_TOO_LONG:
				$this->dieRecoverableError( 'filename-toolong', 'filename' );
				break;
			case UploadBase::FILETYPE_MISSING:
				$this->dieRecoverableError( 'filetype-missing', 'filename' );
				break;
			case UploadBase::WINDOWS_NONASCII_FILENAME:
				$this->dieRecoverableError( 'windows-nonascii-filename', 'filename' );
				break;

			// Unrecoverable errors
			case UploadBase::EMPTY_FILE:
				$this->dieUsage( 'The file you submitted was empty', 'empty-file' );
				break;
			case UploadBase::FILE_TOO_LARGE:
				$this->dieUsage( 'The file you submitted was too large', 'file-too-large' );
				break;

			case UploadBase::FILETYPE_BADTYPE:
				$extradata = array(
					'filetype' => $verification['finalExt'],
					'allowed' => array_values( array_unique( $this->getConfig()->get( 'FileExtensions' ) ) )
				);
				ApiResult::setIndexedTagName( $extradata['allowed'], 'ext' );

				$msg = "Filetype not permitted: ";
				if ( isset( $verification['blacklistedExt'] ) ) {
					$msg .= join( ', ', $verification['blacklistedExt'] );
					$extradata['blacklisted'] = array_values( $verification['blacklistedExt'] );
					ApiResult::setIndexedTagName( $extradata['blacklisted'], 'ext' );
				} else {
					$msg .= $verification['finalExt'];
				}
				$this->dieUsage( $msg, 'filetype-banned', 0, $extradata );
				break;
			case UploadBase::VERIFICATION_ERROR:
				ApiResult::setIndexedTagName( $verification['details'], 'detail' );
				$this->dieUsage( 'This file did not pass file verification', 'verification-error',
					0, array( 'details' => $verification['details'] ) );
				break;
			case UploadBase::HOOK_ABORTED:
				$this->dieUsage( "The modification you tried to make was aborted by an extension hook",
					'hookaborted', 0, array( 'error' => $verification['error'] ) );
				break;
			default:
				$this->dieUsage( 'An unknown error occurred', 'unknown-error',
					0, array( 'code' => $verification['status'] ) );
				break;
		}
	}

	/**
	 * Check warnings.
	 * Returns a suitable array for inclusion into API results if there were warnings
	 * Returns the empty array if there were no warnings
	 *
	 * @return array
	 */
	protected function getApiWarnings() {
		$warnings = $this->mUpload->checkWarnings();

		return $this->transformWarnings( $warnings );
	}

	protected function transformWarnings( $warnings ) {
		if ( $warnings ) {
			// Add indices
			$result = $this->getResult();
			ApiResult::setIndexedTagName( $warnings, 'warning' );

			if ( isset( $warnings['duplicate'] ) ) {
				$dupes = array();
				/** @var File $dupe */
				foreach ( $warnings['duplicate'] as $dupe ) {
					$dupes[] = $dupe->getName();
				}
				ApiResult::setIndexedTagName( $dupes, 'duplicate' );
				$warnings['duplicate'] = $dupes;
			}

			if ( isset( $warnings['exists'] ) ) {
				$warning = $warnings['exists'];
				unset( $warnings['exists'] );
				/** @var LocalFile $localFile */
				$localFile = isset( $warning['normalizedFile'] )
					? $warning['normalizedFile']
					: $warning['file'];
				$warnings[$warning['warning']] = $localFile->getName();
			}
		}

		return $warnings;
	}

	/**
	 * Handles a stash exception, giving a useful error to the user.
	 * @param Exception $e The exception we encountered.
	 */
	protected function handleStashException( $e ) {
		$exceptionType = get_class( $e );

		switch ( $exceptionType ) {
			case 'UploadStashFileNotFoundException':
				$this->dieUsage( 'Could not find the file in the stash: ' . $e->getMessage(), 'stashedfilenotfound' );
				break;
			case 'UploadStashBadPathException':
				$this->dieUsage( 'File key of improper format or otherwise invalid: ' . $e->getMessage(), 'stashpathinvalid' );
				break;
			case 'UploadStashFileException':
				$this->dieUsage( 'Could not store upload in the stash: ' . $e->getMessage(), 'stashfilestorage' );
				break;
			case 'UploadStashZeroLengthFileException':
				$this->dieUsage( 'File is of zero length, and could not be stored in the stash: ' . $e->getMessage(), 'stashzerolength' );
				break;
			case 'UploadStashNotLoggedInException':
				$this->dieUsage( 'Not logged in: ' . $e->getMessage(), 'stashnotloggedin' );
				break;
			case 'UploadStashWrongOwnerException':
				$this->dieUsage( 'Wrong owner: ' . $e->getMessage(), 'stashwrongowner' );
				break;
			case 'UploadStashNoSuchKeyException':
				$this->dieUsage( 'No such filekey: ' . $e->getMessage(), 'stashnosuchfilekey' );
				break;
			default:
				$this->dieUsage( $exceptionType . ": " . $e->getMessage(), 'stasherror' );
				break;
		}
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
		if ( is_null( $this->mParams['text'] ) ) {
			$this->mParams['text'] = $this->mParams['comment'];
		}

		/** @var $file File */
		$file = $this->mUpload->getLocalFile();

		// For preferences mode, we want to watch if 'watchdefault' is set or
		// if the *file* doesn't exist and 'watchcreations' is set. But
		// getWatchlistValue()'s automatic handling checks if the *title*
		// exists or not, so we need to check both prefs manually.
		$watch = $this->getWatchlistValue(
			$this->mParams['watchlist'], $file->getTitle(), 'watchdefault'
		);
		if ( !$watch && $this->mParams['watchlist'] == 'preferences' && !$file->exists() ) {
			$watch = $this->getWatchlistValue(
				$this->mParams['watchlist'], $file->getTitle(), 'watchcreations'
			);
		}

		// Deprecated parameters
		if ( $this->mParams['watch'] ) {
			$this->logFeatureUsage( 'action=upload&watch' );
			$watch = true;
		}

		// No errors, no warnings: do the upload
		if ( $this->mParams['async'] ) {
			$progress = UploadBase::getSessionStatus( $this->getUser(), $this->mParams['filekey'] );
			if ( $progress && $progress['result'] === 'Poll' ) {
				$this->dieUsage( "Upload from stash already in progress.", 'publishfailed' );
			}
			UploadBase::setSessionStatus(
				$this->getUser(),
				$this->mParams['filekey'],
				array( 'result' => 'Poll', 'stage' => 'queued', 'status' => Status::newGood() )
			);
			JobQueueGroup::singleton()->push( new PublishStashedFileJob(
				Title::makeTitle( NS_FILE, $this->mParams['filename'] ),
				array(
					'filename' => $this->mParams['filename'],
					'filekey' => $this->mParams['filekey'],
					'comment' => $this->mParams['comment'],
					'text' => $this->mParams['text'],
					'watch' => $watch,
					'session' => $this->getContext()->exportSession()
				)
			) );
			$result['result'] = 'Poll';
			$result['stage'] = 'queued';
		} else {
			/** @var $status Status */
			$status = $this->mUpload->performUpload( $this->mParams['comment'],
				$this->mParams['text'], $watch, $this->getUser() );

			if ( !$status->isGood() ) {
				$error = $status->getErrorsArray();

				if ( count( $error ) == 1 && $error[0][0] == 'async' ) {
					// The upload can not be performed right now, because the user
					// requested so
					return array(
						'result' => 'Queued',
						'statuskey' => $error[0][1],
					);
				}

				ApiResult::setIndexedTagName( $error, 'error' );
				$this->dieUsage( 'An internal error occurred', 'internal-error', 0, $error );
			}
			$result['result'] = 'Success';
		}

		$result['filename'] = $file->getName();
		if ( $warnings && count( $warnings ) > 0 ) {
			$result['warnings'] = $warnings;
		}

		return $result;
	}

	/**
	 * Checks if asynchronous copy uploads are enabled and throws an error if they are not.
	 */
	protected function checkAsyncDownloadEnabled() {
		if ( !$this->getConfig()->get( 'AllowAsyncCopyUploads' ) ) {
			$this->dieUsage( 'Asynchronous copy uploads disabled', 'asynccopyuploaddisabled' );
		}
	}

	public function mustBePosted() {
		return true;
	}

	public function isWriteMode() {
		return true;
	}

	public function getAllowedParams() {
		$params = array(
			'filename' => array(
				ApiBase::PARAM_TYPE => 'string',
			),
			'comment' => array(
				ApiBase::PARAM_DFLT => ''
			),
			'text' => null,
			'watch' => array(
				ApiBase::PARAM_DFLT => false,
				ApiBase::PARAM_DEPRECATED => true,
			),
			'watchlist' => array(
				ApiBase::PARAM_DFLT => 'preferences',
				ApiBase::PARAM_TYPE => array(
					'watch',
					'preferences',
					'nochange'
				),
			),
			'ignorewarnings' => false,
			'file' => array(
				ApiBase::PARAM_TYPE => 'upload',
			),
			'url' => null,
			'filekey' => null,
			'sessionkey' => array(
				ApiBase::PARAM_DFLT => null,
				ApiBase::PARAM_DEPRECATED => true,
			),
			'stash' => false,

			'filesize' => array(
				ApiBase::PARAM_TYPE => 'integer',
				ApiBase::PARAM_MIN => 0,
				ApiBase::PARAM_MAX => UploadBase::getMaxUploadSize(),
			),
			'offset' => array(
				ApiBase::PARAM_TYPE => 'integer',
				ApiBase::PARAM_MIN => 0,
			),
			'chunk' => array(
				ApiBase::PARAM_TYPE => 'upload',
			),

			'async' => false,
			'asyncdownload' => false,
			'leavemessage' => false,
			'statuskey' => null,
			'checkstatus' => false,
		);

		return $params;
	}

	public function needsToken() {
		return 'csrf';
	}

	protected function getExamplesMessages() {
		return array(
			'action=upload&filename=Wiki.png' .
				'&url=http%3A//upload.wikimedia.org/wikipedia/en/b/bc/Wiki.png&token=123ABC'
				=> 'apihelp-upload-example-url',
			'action=upload&filename=Wiki.png&filekey=filekey&ignorewarnings=1&token=123ABC'
				=> 'apihelp-upload-example-filekey',
		);
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Upload';
	}
}
