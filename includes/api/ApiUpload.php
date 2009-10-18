<?php
/*
 * Created on Aug 21, 2008
 * API for MediaWiki 1.8+
 *
 * Copyright (C) 2008 - 2009 Bryan Tong Minh <Bryan.TongMinh@Gmail.com>
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
 * 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
 * http://www.gnu.org/copyleft/gpl.html
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	// Eclipse helper - will be ignored in production
	require_once("ApiBase.php");
}

/**
 * @ingroup API
 */
class ApiUpload extends ApiBase {
	protected $mUpload = null;
	protected $mParams;

	public function __construct( $main, $action ) {
		parent::__construct( $main, $action );
	}

	public function execute() {
		global $wgUser;

		$this->getMain()->isWriteMode();
		$this->mParams = $this->extractRequestParams();
		$request = $this->getMain()->getRequest();

		// Do token checks:
		if ( is_null( $this->mParams['token'] ) )
			$this->dieUsageMsg( array( 'missingparam', 'token' ) );
		if ( !$wgUser->matchEditToken( $this->mParams['token'] ) )
			$this->dieUsageMsg( array( 'sessionfailure' ) );


		// Add the uploaded file to the params array
		$this->mParams['file'] = $request->getFileName( 'file' );

		// Check whether upload is enabled
		if ( !UploadBase::isEnabled() )
			$this->dieUsageMsg( array( 'uploaddisabled' ) );

		// One and only one of the following parameters is needed
		$this->requireOnlyOneParameter( $this->mParams,
			'sessionkey', 'file', 'url', 'enablechunks' );

		if ( $this->mParams['enablechunks'] ) {
			/**
			 * Chunked upload mode
			 */

			$this->mUpload = new UploadFromChunks();
			$status = $this->mUpload->initializeFromParams( $this->mParams, $request );

			if( isset( $status['error'] ) )
				$this->dieUsageMsg( $status['error'] );

		} elseif ( isset( $this->mParams['internalhttpsession'] ) && $this->mParams['internalhttpsession'] ) {
			$sd = & $_SESSION['wsDownload'][ $this->mParams['internalhttpsession'] ];

			//wfDebug("InternalHTTP:: " . print_r($this->mParams, true));
			// get the params from the init session:
			$this->mUpload = new UploadFromFile();

			$this->mUpload->initialize( $this->mParams['filename'],
				$sd['target_file_path'],
				filesize( $sd['target_file_path'] )
			);
		} elseif ( $this->mParams['httpstatus'] && $this->mParams['sessionkey'] ) {
			/**
			 * Return the status of the given background upload session_key:
			 */
			// Check the session key
			if( !isset( $_SESSION['wsDownload'][$this->mParams['sessionkey']] ) )
					return $this->dieUsageMsg( array( 'invalid-session-key' ) );

			$sd =& $_SESSION['wsDownload'][$this->mParams['sessionkey']];
			// Keep passing down the upload sessionkey
			$statusResult = array(
				'upload_session_key' => $this->mParams['sessionkey']
			);

			// put values into the final apiResult if available
			if( isset( $sd['apiUploadResult'] ) )
				$statusResult['apiUploadResult'] = $sd['apiUploadResult'];
			if( isset( $sd['loaded'] ) )
				$statusResult['loaded'] = $sd['loaded'];
			if( isset( $sd['content_length'] ) )
				$statusResult['content_length'] = $sd['content_length'];

			return $this->getResult()->addValue( null,
					$this->getModuleName(), $statusResult );

		} elseif( $this->mParams['sessionkey'] ) {
			/**
			 * Upload stashed in a previous request
			 */
			$this->mUpload = new UploadFromStash();
			$this->mUpload->initialize( $this->mParams['filename'],
					$_SESSION['wsUploadData'][$this->mParams['sessionkey']] );
		} else {
			/**
			 * Upload from url or file
			 * Parameter filename is required
			 */
			if ( !isset( $this->mParams['filename'] ) )
				$this->dieUsageMsg( array( 'missingparam', 'filename' ) );

			// Initialize $this->mUpload
			if ( isset( $this->mParams['file'] ) ) {
				$this->mUpload = new UploadFromFile();
				$this->mUpload->initialize(
					$this->mParams['filename'],
					$request->getFileTempName( 'file' ),
					$request->getFileSize( 'file' )
				);
			} elseif ( isset( $this->mParams['url'] ) ) {
				$this->mUpload = new UploadFromUrl();
				$this->mUpload->initialize( $this->mParams['filename'],
						$this->mParams['url'], $this->mParams['asyncdownload'] );

				$status = $this->mUpload->fetchFile();
				if( !$status->isOK() ) {
					return $this->dieUsage( 'fetchfileerror', $status->getWikiText() );
				}

				// check if we doing a async request set session info and return the upload_session_key)
				if( $this->mUpload->isAsync() ){
					$upload_session_key = $status->value;
					// update the session with anything with the params we will need to finish up the upload later on:
					if( !isset( $_SESSION['wsDownload'][$upload_session_key] ) )
						$_SESSION['wsDownload'][$upload_session_key] = array();

					$sd =& $_SESSION['wsDownload'][$upload_session_key];

					// copy mParams for finishing up after:
					$sd['mParams'] = $this->mParams;

					return $this->getResult()->addValue( null, $this->getModuleName(),
									array( 'upload_session_key' => $upload_session_key
							));
				}
			}
		}

		if( !isset( $this->mUpload ) )
			$this->dieUsage( 'No upload module set', 'nomodule' );


		// Finish up the exec command:
		$this->doExecUpload();

	}

	protected function doExecUpload(){
		global $wgUser;
		// Check whether the user has the appropriate permissions to upload anyway
		$permission = $this->mUpload->isAllowed( $wgUser );

		if( $permission !== true ) {
			if( !$wgUser->isLoggedIn() )
				$this->dieUsageMsg( array( 'mustbeloggedin', 'upload' ) );
			else
				$this->dieUsageMsg( array( 'badaccess-groups' ) );
		}
		// Perform the upload
		$result = $this->performUpload();
		// Cleanup any temporary mess
		$this->mUpload->cleanupTempFile();
		$this->getResult()->addValue( null, $this->getModuleName(), $result );
	}

	protected function performUpload() {
		global $wgUser;
		$result = array();
		$permErrors = $this->mUpload->verifyPermissions( $wgUser );
		if( $permErrors !== true ) {
			$this->dieUsageMsg( array( 'baddaccess-groups' ) );
		}

		// TODO: Move them to ApiBase's message map
		$verification = $this->mUpload->verifyUpload();
		if( $verification['status'] !== UploadBase::OK ) {
			$result['result'] = 'Failure';
			switch( $verification['status'] ) {
				case UploadBase::EMPTY_FILE:
					$this->dieUsage( 'The file you submitted was empty', 'empty-file' );
					break;
				case UploadBase::FILETYPE_MISSING:
					$this->dieUsage( 'The file is missing an extension', 'filetype-missing' );
					break;
				case UploadBase::FILETYPE_BADTYPE:
					global $wgFileExtensions;
					$this->dieUsage( 'This type of file is banned', 'filetype-banned',
							0, array(
								'filetype' => $verification['finalExt'],
								'allowed' => $wgFileExtensions
							) );
					break;
				case UploadBase::MIN_LENGHT_PARTNAME:
					$this->dieUsage( 'The filename is too short', 'filename-tooshort' );
					break;
				case UploadBase::ILLEGAL_FILENAME:
					$this->dieUsage( 'The filename is not allowed', 'illegal-filename',
							0, array( 'filename' => $verification['filtered'] ) );
					break;
				case UploadBase::OVERWRITE_EXISTING_FILE:
					$this->dieUsage( 'Overwriting an existing file is not allowed', 'overwrite' );
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
			return $result;
		}
		if( !$this->mParams['ignorewarnings'] ) {
			$warnings = $this->mUpload->checkWarnings();
			if( $warnings ) {
				// Add indices
				$this->getResult()->setIndexedTagName( $warnings, 'warning' );

				if( isset( $warnings['duplicate'] ) ) {
					$dupes = array();
					foreach( $warnings['duplicate'] as $key => $dupe )
						$dupes[] = $dupe->getName();
					$this->getResult()->setIndexedTagName( $dupes, 'duplicate');
					$warnings['duplicate'] = $dupes;
				}


				if( isset( $warnings['exists'] ) ) {
					$warning = $warnings['exists'];
					unset( $warnings['exists'] );
					$warnings[$warning['warning']] = $warning['file']->getName();
				}

				$result['result'] = 'Warning';
				$result['warnings'] = $warnings;

				$sessionKey = $this->mUpload->stashSession();
				if ( !$sessionKey )
					$this->dieUsage( 'Stashing temporary file failed', 'stashfailed' );

				$result['sessionkey'] = $sessionKey;

				return $result;
			}
		}

		// No errors, no warnings: do the upload
		$status = $this->mUpload->performUpload( $this->mParams['comment'],
			$this->mParams['comment'], $this->mParams['watch'], $wgUser );

		if( !$status->isGood() ) {
			$error = $status->getErrorsArray();
			$this->getResult()->setIndexedTagName( $result['details'], 'error' );

			$this->dieUsage( 'An internal error occurred', 'internal-error', 0, $error );
		}

		$file = $this->mUpload->getLocalFile();
		$result['result'] = 'Success';
		$result['filename'] = $file->getName();

		// Append imageinfo to the result
		$imParam = ApiQueryImageInfo::getPropertyNames();
        $result['imageinfo'] = ApiQueryImageInfo::getInfo( $file,
				array_flip( $imParam ), $this->getResult() );

		return $result;
	}

	public function mustBePosted() {
		return true;
	}

	public function isWriteMode() {
		return true;
	}

	public function getAllowedParams() {
		global $wgEnableAsyncDownload;
		$params = array(
			'filename' => null,
			'comment' => array(
				ApiBase::PARAM_DFLT => ''
			),
			'token' => null,
			'watch' => false,
			'ignorewarnings' => false,
			'file' => null,
			'enablechunks' => null,
			'chunksessionkey' => null,
			'chunk' => null,
			'done' => false,
			'url' => null,
			'httpstatus' => false,
			'sessionkey' => null,
		);

		if ( $this->getMain()->isInternalMode() )
			$params['internalhttpsession'] = null;
		if($wgEnableAsyncDownload){
			$params['asyncdownload'] = false;
		}
		return $params;

	}

	public function getParamDescription() {
		return array(
			'filename' => 'Target filename',
			'token' => 'Edit token. You can get one of these through prop=info',
			'comment' => 'Upload comment. Also used as the initial page text for new files',
			'watch' => 'Watch the page',
			'ignorewarnings' => 'Ignore any warnings',
			'file' => 'File contents',
			'enablechunks' => 'Set to use chunk mode; see http://firefogg.org/dev/chunk_post.html for protocol',
			'chunksessionkey' => 'Used to sync uploading of chunks',
			'chunk' => 'Chunk contents',
			'done' => 'Set when the last chunk is being uploaded',
			'url' => 'Url to fetch the file from',
			'asyncdownload' => 'Set to download the url asynchronously. Useful for large files that will take more than php max_execution_time to download',
			'httpstatus' => 'Set to return the status of an asynchronous upload (specify the key in sessionkey)',
			'sessionkey' => array(
				'Session key returned by a previous upload that failed due to warnings, or',
				'(with httpstatus) The upload_session_key of an asynchronous upload',
			),
			'internalhttpsession' => 'Used internally',
		);
	}

	public function getDescription() {
		return array(
			'Upload a file, or get the status of pending uploads. Several methods are available:',
			' * Upload file contents directly, using the "file" parameter',
			' * Upload a file in chunks, using the "enablechunks", "chunk", and "chunksessionkey", and "done" parameters',
			' * Have the MediaWiki server fetch a file from a URL, using the "url" and "asyncdownload" parameters',
			' * Retrieve the status of an asynchronous upload, using the "httpstatus" and "sessionkey" parameters',
			' * Complete an earlier upload that failed due to warnings, using the "sessionkey" parameter',
			'Note that the HTTP POST must be done as a file upload (i.e. using multipart/form-data) when',
			'sending the "file" or "chunk" parameters. Note also that queries using session keys must be',
			'done in the same login session as the query that originally returned the key (i.e. do not',
			'log out and then log back in). Also you must get and send an edit token before doing any upload stuff.'
		);
	}

	protected function getExamples() {
		return array(
			'Upload from a URL:',
			'    api.php?action=upload&filename=Wiki.png&url=http%3A//upload.wikimedia.org/wikipedia/en/b/bc/Wiki.png',
			'Get the status of an asynchronous upload:',
			'    api.php?action=upload&filename=Wiki.png&httpstatus=1&sessionkey=upload_session_key',
			'Complete an upload that failed due to warnings:',
			'    api.php?action=upload&filename=Wiki.png&sessionkey=sessionkey&ignorewarnings=1',
			'Begin a chunked upload:',
			'    api.php?action=upload&filename=Wiki.png&enablechunks=1'
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id: ApiUpload.php 51812 2009-06-12 23:45:20Z dale $';
	}
}


