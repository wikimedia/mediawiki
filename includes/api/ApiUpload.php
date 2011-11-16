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

	/**
	 * @var UploadBase
	 */
	protected $mUpload = null;

	protected $mParams;

	public function __construct( $main, $action ) {
		parent::__construct( $main, $action );
	}

	public function execute() {
		// Check whether upload is enabled
		if ( !UploadBase::isEnabled() ) {
			$this->dieUsageMsg( 'uploaddisabled' );
		}

		$user = $this->getUser();

		// Parameter handling
		$this->mParams = $this->extractRequestParams();
		$request = $this->getMain()->getRequest();
		// Add the uploaded file to the params array
		$this->mParams['file'] = $request->getFileName( 'file' );
		$this->mParams['chunk'] = $request->getFileName( 'chunk' );

		// Copy the session key to the file key, for backward compatibility.
		if( !$this->mParams['filekey'] && $this->mParams['sessionkey'] ) {
			$this->mParams['filekey'] = $this->mParams['sessionkey'];
		}

		// Select an upload module
		if ( !$this->selectUploadModule() ) {
			// This is not a true upload, but a status request or similar
			return;
		}
		if ( !isset( $this->mUpload ) ) {
			$this->dieUsage( 'No upload module set', 'nomodule' );
		}

		// First check permission to upload
		$this->checkPermissions( $user );

		// Fetch the file
		$status = $this->mUpload->fetchFile();
		if ( !$status->isGood() ) {
			$errors = $status->getErrorsArray();
			$error = array_shift( $errors[0] );
			$this->dieUsage( 'Error fetching file from remote source', $error, 0, $errors[0] );
		}

		// Check if the uploaded file is sane
		if ( $this->mParams['chunk'] ) {
			$maxSize = $this->mUpload->getMaxUploadSize( );
			if( $this->mParams['filesize'] > $maxSize ) {
				$this->dieUsage( 'The file you submitted was too large', 'file-too-large' );
			}
		} else {
			$this->verifyUpload();
		}


		// Check if the user has the rights to modify or overwrite the requested title
		// (This check is irrelevant if stashing is already requested, since the errors
		//  can always be fixed by changing the title)
		if ( ! $this->mParams['stash'] ) {
			$permErrors = $this->mUpload->verifyTitlePermissions( $user );
			if ( $permErrors !== true ) {
				$this->dieRecoverableError( $permErrors[0], 'filename' );
			}
		}

		// Prepare the API result
		$result = array();

		$warnings = $this->getApiWarnings();
		if ( $warnings ) {
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
		} elseif ( $this->mParams['chunk'] ) {
			$result['result'] = 'Continue';
			$chunk = $request->getFileTempName( 'chunk' );
			$chunkSize = $request->getUpload( 'chunk' )->getSize();
			if ($this->mParams['offset'] == 0) {
				$result['filekey'] = $this->performStash();
			} else {
				$status = $this->mUpload->appendChunk($chunk, $chunkSize,
													  $this->mParams['offset']);
				if ( !$status->isGood() ) {
					$this->dieUsage( $status->getWikiText(), 'stashfailed' );
				} else {
					$result['filekey'] = $this->mParams['filekey'];
					if($this->mParams['offset'] + $chunkSize == $this->mParams['filesize']) {
						$this->mUpload->finalizeFile();
						$result['result'] = 'Success';
					}
				}
			}
			$result['offset'] = $this->mParams['offset'] + $chunkSize;
		} elseif ( $this->mParams['stash'] ) {
			// Some uploads can request they be stashed, so as not to publish them immediately.
			// In this case, a failure to stash ought to be fatal
			try {
				$result['result'] = 'Success';
				$result['filekey'] = $this->performStash();
				$result['sessionkey'] = $result['filekey']; // backwards compatibility
			} catch ( MWException $e ) {
				$this->dieUsage( $e->getMessage(), 'stashfailed' );
			}
		} else {
			// This is the most common case -- a normal upload with no warnings
			// $result will be formatted properly for the API already, with a status
			$result = $this->performUpload();
		}

		if ( $result['result'] === 'Success' ) {
			$result['imageinfo'] = $this->mUpload->getImageInfo( $this->getResult() );
		}

		$this->getResult()->addValue( null, $this->getModuleName(), $result );

		// Cleanup any temporary mess
		$this->mUpload->cleanupTempFile();
	}

	/**
	 * Stash the file and return the file key
	 * Also re-raises exceptions with slightly more informative message strings (useful for API)
	 * @throws MWException
	 * @return String file key
	 */
	function performStash() {
		try {
			$stashFile = $this->mUpload->stashFile();

			if ( !$stashFile ) {
				throw new MWException( 'Invalid stashed file' );
			}
			$fileKey = $stashFile->getFileKey();
		} catch ( MWException $e ) {
			$message = 'Stashing temporary file failed: ' . get_class( $e ) . ' ' . $e->getMessage();
			wfDebug( __METHOD__ . ' ' . $message . "\n");
			throw new MWException( $message );
		}
		return $fileKey;
	}

	/**
	 * Throw an error that the user can recover from by providing a better
	 * value for $parameter
	 *
	 * @param $error array Error array suitable for passing to dieUsageMsg()
	 * @param $parameter string Parameter that needs revising
	 * @param $data array Optional extra data to pass to the user
	 * @throws UsageException
	 */
	function dieRecoverableError( $error, $parameter, $data = array() ) {
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
		if( !$this->mParams['chunk'] ) {
			$this->requireOnlyOneParameter( $this->mParams,
				'filekey', 'file', 'url', 'statuskey' );
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

		if ( $this->mParams['filekey'] ) {
			// Upload stashed in a previous request
			if ( !UploadFromStash::isValidKey( $this->mParams['filekey'] ) ) {
				$this->dieUsageMsg( 'invalid-file-key' );
			}

			$this->mUpload = new UploadFromStash( $this->getUser() );

			$this->mUpload->initialize( $this->mParams['filekey'], $this->mParams['filename'] );

		} elseif ( isset( $this->mParams['chunk'] ) ) {
			// Start new Chunk upload
			$this->mUpload = new UploadFromFile();
			$this->mUpload->initialize(
				$this->mParams['filename'],
				$request->getUpload( 'chunk' )
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
			} else {
				$this->dieUsageMsg( 'badaccess-groups' );
			}
		}
	}

	/**
	 * Performs file verification, dies on error.
	 */
	protected function verifyUpload( ) {
		global $wgFileExtensions;

		$verification = $this->mUpload->verifyUpload( );
		if ( $verification['status'] === UploadBase::OK ) {
			return;
		}

		// TODO: Move them to ApiBase's message map
		switch( $verification['status'] ) {
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
				$this->dieUsage( 'This type of file is banned', 'filetype-banned',
						0, array(
							'filetype' => $verification['finalExt'],
							'allowed' => $wgFileExtensions
						) );
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
						0, array( 'code' =>  $verification['status'] ) );
				break;
		}
	}


	/**
	 * Check warnings if ignorewarnings is not set.
	 * Returns a suitable array for inclusion into API results if there were warnings
	 * Returns the empty array if there were no warnings
	 *
	 * @return array
	 */
	protected function getApiWarnings() {
		$warnings = array();

		if ( !$this->mParams['ignorewarnings'] ) {
			$warnings = $this->mUpload->checkWarnings();
		}
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
				$warnings[$warning['warning']] = $warning['file']->getName();
			}
		}
		return $warnings;
	}


	/**
	 * Perform the actual upload. Returns a suitable result array on success;
	 * dies on failure.
	 *
	 * @return array
	 */
	protected function performUpload() {
		// Use comment as initial page text by default
		if ( is_null( $this->mParams['text'] ) ) {
			$this->mParams['text'] = $this->mParams['comment'];
		}

		$file = $this->mUpload->getLocalFile();
		$watch = $this->getWatchlistValue( $this->mParams['watchlist'], $file->getTitle() );

		// Deprecated parameters
		if ( $this->mParams['watch'] ) {
			$watch = true;
		}

		// No errors, no warnings: do the upload
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
			} else {
				$this->getResult()->setIndexedTagName( $error, 'error' );

				$this->dieUsage( 'An internal error occurred', 'internal-error', 0, $error );
			}
		}

		$file = $this->mUpload->getLocalFile();

		$result['result'] = 'Success';
		$result['filename'] = $file->getName();

		return $result;
	}

	/**
	 * Checks if asynchronous copy uploads are enabled and throws an error if they are not.
	 */
	protected function checkAsyncDownloadEnabled() {
		global $wgAllowAsyncCopyUploads;
		if ( !$wgAllowAsyncCopyUploads ) {
			$this->dieUsage( 'Asynchronous copy uploads disabled', 'asynccopyuploaddisabled');
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
			'token' => null,
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
			'file' => null,
			'url' => null,
			'filekey' => null,
			'sessionkey' => array(
				ApiBase::PARAM_DFLT => null,
				ApiBase::PARAM_DEPRECATED => true,
			),
			'stash' => false,

			'filesize' => null,
			'offset' => null,
			'chunk' => null,

			'asyncdownload' => false,
			'leavemessage' => false,
			'statuskey' => null,
		);

		return $params;
	}

	public function getParamDescription() {
		$params = array(
			'filename' => 'Target filename',
			'token' => 'Edit token. You can get one of these through prop=info',
			'comment' => 'Upload comment. Also used as the initial page text for new files if "text" is not specified',
			'text' => 'Initial page text for new files',
			'watch' => 'Watch the page',
			'watchlist' => 'Unconditionally add or remove the page from your watchlist, use preferences or do not change watch',
			'ignorewarnings' => 'Ignore any warnings',
			'file' => 'File contents',
			'url' => 'Url to fetch the file from',
			'filekey' => 'Key that identifies a previous upload that was stashed temporarily.',
			'sessionkey' => 'Same as filekey, maintained for backward compatibility.',
			'stash' => 'If set, the server will not add the file to the repository and stash it temporarily.',

			'chunk' => 'Chunk contents',
			'offset' => 'Offset of chunk in bytes',
			'filesize' => 'Filesize of entire upload',

			'asyncdownload' => 'Make fetching a URL asynchronous',
			'leavemessage' => 'If asyncdownload is used, leave a message on the user talk page if finished',
			'statuskey' => 'Fetch the upload status for this file key',
		);

		return $params;

	}

	public function getDescription() {
		return array(
			'Upload a file, or get the status of pending uploads. Several methods are available:',
			' * Upload file contents directly, using the "file" parameter',
			' * Have the MediaWiki server fetch a file from a URL, using the "url" parameter',
			' * Complete an earlier upload that failed due to warnings, using the "filekey" parameter',
			'Note that the HTTP POST must be done as a file upload (i.e. using multipart/form-data) when',
			'sending the "file".  Also you must get and send an edit token before doing any upload stuff'
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
				array( 'code' => 'internal-error', 'info' => 'An internal error occurred' ),
				array( 'code' => 'asynccopyuploaddisabled', 'info' => 'Asynchronous copy uploads disabled' ),
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
			'Upload from a URL:',
			'    api.php?action=upload&filename=Wiki.png&url=http%3A//upload.wikimedia.org/wikipedia/en/b/bc/Wiki.png',
			'Complete an upload that failed due to warnings:',
			'    api.php?action=upload&filename=Wiki.png&filekey=filekey&ignorewarnings=1',
		);
	}

	public function getHelpUrls() {
		return 'http://www.mediawiki.org/wiki/API:Upload';
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
