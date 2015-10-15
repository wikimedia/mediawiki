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
	/** @var UploadBase */
	protected $mUpload = null;

	protected $mParams;

	public function execute() {
		global $wgEnableAsyncUploads;

		// Check whether upload is enabled
		if ( !UploadBase::isEnabled() ) {
			$this->dieUsageMsg( 'uploaddisabled' );
		}

		$user = $this->getUser();

		// Parameter handling
		$this->mParams = $this->extractRequestParams();
		$request = $this->getMain()->getRequest();
		// Check if async mode is actually supported (jobs done in cli mode)
		$this->mParams['async'] = ( $this->mParams['async'] && $wgEnableAsyncUploads );
		// Add the uploaded file to the params array
		$this->mParams['file'] = $request->getFileName( 'file' );
		$this->mParams['chunk'] = $request->getFileName( 'chunk' );

		// Copy the session key to the file key, for backward compatibility.
		if ( !$this->mParams['filekey'] && $this->mParams['sessionkey'] ) {
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
			$this->dieUsage( get_class( $e ) . ": " . $e->getMessage(), 'stasherror' );
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
			$this->dieUsage( get_class( $e ) . ": " . $e->getMessage(), 'stasherror' );
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
		} catch ( MWException $e ) {
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
		} catch ( MWException $e ) {
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
			} catch ( MWException $e ) {
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

			/** @var $status Status */
			$status = $this->mUpload->addChunk(
				$chunkPath, $chunkSize, $this->mParams['offset'] );
			if ( !$status->isGood() ) {
				$this->dieUsage( $status->getWikiText(), 'stashfailed' );
			}
		}

		// Check we added the last chunk:
		if ( $totalSoFar == $this->mParams['filesize'] ) {
			if ( $this->mParams['async'] ) {
				UploadBase::setSessionStatus(
					$filekey,
					array( 'result' => 'Poll',
						'stage' => 'queued', 'status' => Status::newGood() )
				);
				$ok = JobQueueGroup::singleton()->push( new AssembleUploadChunksJob(
					Title::makeTitle( NS_FILE, $filekey ),
					array(
						'filename' => $this->mParams['filename'],
						'filekey' => $filekey,
						'session' => $this->getContext()->exportSession()
					)
				) );
				if ( $ok ) {
					$result['result'] = 'Poll';
				} else {
					UploadBase::setSessionStatus( $filekey, false );
					$this->dieUsage(
						"Failed to start AssembleUploadChunks.php", 'stashfailed' );
				}
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
	 * @return String file key
	 */
	private function performStash() {
		try {
			$stashFile = $this->mUpload->stashFile();

			if ( !$stashFile ) {
				throw new MWException( 'Invalid stashed file' );
			}
			$fileKey = $stashFile->getFileKey();
		} catch ( MWException $e ) {
			$message = 'Stashing temporary file failed: ' . get_class( $e ) . ' ' . $e->getMessage();
			wfDebug( __METHOD__ . ' ' . $message . "\n" );
			throw new MWException( $message );
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
		} catch ( MWException $e ) {
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
			$progress = UploadBase::getSessionStatus( $this->mParams['filekey'] );
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
	 * @param $user User The user to check.
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
	 */
	protected function checkVerification( array $verification ) {
		global $wgFileExtensions;

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
					'allowed' => array_values( array_unique( $wgFileExtensions ) )
				);
				$this->getResult()->setIndexedTagName( $extradata['allowed'], 'ext' );

				$msg = "Filetype not permitted: ";
				if ( isset( $verification['blacklistedExt'] ) ) {
					$msg .= join( ', ', $verification['blacklistedExt'] );
					$extradata['blacklisted'] = array_values( $verification['blacklistedExt'] );
					$this->getResult()->setIndexedTagName( $extradata['blacklisted'], 'ext' );
				} else {
					$msg .= $verification['finalExt'];
				}
				$this->dieUsage( $msg, 'filetype-banned', 0, $extradata );
				break;
			case UploadBase::VERIFICATION_ERROR:
				$this->getResult()->setIndexedTagName( $verification['details'], 'detail' );
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
			$result->setIndexedTagName( $warnings, 'warning' );

			if ( isset( $warnings['duplicate'] ) ) {
				$dupes = array();
				foreach ( $warnings['duplicate'] as $dupe ) {
					$dupes[] = $dupe->getName();
				}
				$result->setIndexedTagName( $dupes, 'duplicate' );
				$warnings['duplicate'] = $dupes;
			}

			if ( isset( $warnings['exists'] ) ) {
				$warning = $warnings['exists'];
				unset( $warnings['exists'] );
				$localFile = isset( $warning['normalizedFile'] )
					? $warning['normalizedFile']
					: $warning['file'];
				$warnings[$warning['warning']] = $localFile->getName();
			}
		}

		return $warnings;
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
			$watch = true;
		}

		// No errors, no warnings: do the upload
		if ( $this->mParams['async'] ) {
			$progress = UploadBase::getSessionStatus( $this->mParams['filekey'] );
			if ( $progress && $progress['result'] === 'Poll' ) {
				$this->dieUsage( "Upload from stash already in progress.", 'publishfailed' );
			}
			UploadBase::setSessionStatus(
				$this->mParams['filekey'],
				array( 'result' => 'Poll', 'stage' => 'queued', 'status' => Status::newGood() )
			);
			$ok = JobQueueGroup::singleton()->push( new PublishStashedFileJob(
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
			if ( $ok ) {
				$result['result'] = 'Poll';
			} else {
				UploadBase::setSessionStatus( $this->mParams['filekey'], false );
				$this->dieUsage(
					"Failed to start PublishStashedFile.php", 'publishfailed' );
			}
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

				$this->getResult()->setIndexedTagName( $error, 'error' );
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
		global $wgAllowAsyncCopyUploads;
		if ( !$wgAllowAsyncCopyUploads ) {
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
			'token' => array(
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => true
			),
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

	public function getParamDescription() {
		$params = array(
			'filename' => 'Target filename',
			'token' => 'Edit token. You can get one of these through prop=info',
			'comment' => 'Upload comment. Also used as the initial page text for new ' .
				'files if "text" is not specified',
			'text' => 'Initial page text for new files',
			'watch' => 'Watch the page',
			'watchlist' => 'Unconditionally add or remove the page from your watchlist, ' .
				'use preferences or do not change watch',
			'ignorewarnings' => 'Ignore any warnings',
			'file' => 'File contents',
			'url' => 'URL to fetch the file from',
			'filekey' => 'Key that identifies a previous upload that was stashed temporarily.',
			'sessionkey' => 'Same as filekey, maintained for backward compatibility.',
			'stash' => 'If set, the server will not add the file to the repository ' .
				'and stash it temporarily.',

			'chunk' => 'Chunk contents',
			'offset' => 'Offset of chunk in bytes',
			'filesize' => 'Filesize of entire upload',

			'async' => 'Make potentially large file operations asynchronous when possible',
			'asyncdownload' => 'Make fetching a URL asynchronous',
			'leavemessage' => 'If asyncdownload is used, leave a message on the user talk page if finished',
			'statuskey' => 'Fetch the upload status for this file key (upload by URL)',
			'checkstatus' => 'Only fetch the upload status for the given file key',
		);

		return $params;
	}

	public function getResultProperties() {
		return array(
			'' => array(
				'result' => array(
					ApiBase::PROP_TYPE => array(
						'Success',
						'Warning',
						'Continue',
						'Queued'
					),
				),
				'filekey' => array(
					ApiBase::PROP_TYPE => 'string',
					ApiBase::PROP_NULLABLE => true
				),
				'sessionkey' => array(
					ApiBase::PROP_TYPE => 'string',
					ApiBase::PROP_NULLABLE => true
				),
				'offset' => array(
					ApiBase::PROP_TYPE => 'integer',
					ApiBase::PROP_NULLABLE => true
				),
				'statuskey' => array(
					ApiBase::PROP_TYPE => 'string',
					ApiBase::PROP_NULLABLE => true
				),
				'filename' => array(
					ApiBase::PROP_TYPE => 'string',
					ApiBase::PROP_NULLABLE => true
				)
			)
		);
	}

	public function getDescription() {
		return array(
			'Upload a file, or get the status of pending uploads. Several methods are available:',
			' * Upload file contents directly, using the "file" parameter',
			' * Have the MediaWiki server fetch a file from a URL, using the "url" parameter',
			' * Complete an earlier upload that failed due to warnings, using the "filekey" parameter',
			'Note that the HTTP POST must be done as a file upload (i.e. using multipart/form-data) when',
			'sending the "file". Also you must get and send an edit token before doing any upload stuff.'
		);
	}

	public function getPossibleErrors() {
		return array_merge( parent::getPossibleErrors(),
			$this->getRequireOnlyOneParameterErrorMessages( array( 'filekey', 'file', 'url', 'statuskey' ) ),
			array(
				array( 'uploaddisabled' ),
				array( 'invalid-file-key' ),
				array( 'uploaddisabled' ),
				array( 'mustbeloggedin', 'upload' ),
				array( 'badaccess-groups' ),
				array( 'code' => 'fetchfileerror', 'info' => '' ),
				array( 'code' => 'nomodule', 'info' => 'No upload module set' ),
				array( 'code' => 'empty-file', 'info' => 'The file you submitted was empty' ),
				array( 'code' => 'filetype-missing', 'info' => 'The file is missing an extension' ),
				array( 'code' => 'filename-tooshort', 'info' => 'The filename is too short' ),
				array( 'code' => 'overwrite', 'info' => 'Overwriting an existing file is not allowed' ),
				array( 'code' => 'stashfailed', 'info' => 'Stashing temporary file failed' ),
				array( 'code' => 'publishfailed', 'info' => 'Publishing of stashed file failed' ),
				array( 'code' => 'internal-error', 'info' => 'An internal error occurred' ),
				array( 'code' => 'asynccopyuploaddisabled', 'info' => 'Asynchronous copy uploads disabled' ),
				array( 'code' => 'stasherror', 'info' => 'An upload stash error occurred' ),
				array( 'fileexists-forbidden' ),
				array( 'fileexists-shared-forbidden' ),
			)
		);
	}

	public function needsToken() {
		return true;
	}

	public function getTokenSalt() {
		return '';
	}

	public function getExamples() {
		return array(
			'api.php?action=upload&filename=Wiki.png' .
			'&url=http%3A//upload.wikimedia.org/wikipedia/en/b/bc/Wiki.png'
				=> 'Upload from a URL',
			'api.php?action=upload&filename=Wiki.png&filekey=filekey&ignorewarnings=1'
				=> 'Complete an upload that failed due to warnings',
		);
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Upload';
	}
}
