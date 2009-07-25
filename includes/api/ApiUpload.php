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
	var $mUpload = null;

	public function __construct( $main, $action ) {
		parent::__construct( $main, $action );
	}

	public function execute() {
		global $wgUser;

		$this->getMain()->isWriteMode();
		$this->mParams = $this->extractRequestParams();
		$request = $this->getMain()->getRequest();

		// do token checks:
		if( is_null( $this->mParams['token'] ) )
			$this->dieUsageMsg( array( 'missingparam', 'token' ) );
		if( !$wgUser->matchEditToken( $this->mParams['token'] ) )
			$this->dieUsageMsg( array( 'sessionfailure' ) );


		// Add the uploaded file to the params array
		$this->mParams['file'] = $request->getFileName( 'file' );

		// Check whether upload is enabled
		if( !UploadBase::isEnabled() )
			$this->dieUsageMsg( array( 'uploaddisabled' ) );

		wfDebug( __METHOD__ . "running require param\n" );
		// One and only one of the following parameters is needed
		$this->requireOnlyOneParameter( $this->mParams,
			'sessionkey', 'file', 'url', 'enablechunks' );

		if( $this->mParams['enablechunks'] ){
			// chunks upload enabled
			$this->mUpload = new UploadFromChunks();
			$this->mUpload->initializeFromParams( $this->mParams, $request );

			//if getAPIresult did not exit report the status error:
			if( isset( $this->mUpload->status['error'] ) )
				$this->dieUsageMsg( $this->mUpload->status['error'] );

		} else if( $this->mParams['internalhttpsession'] ){
			$sd = & $_SESSION['wsDownload'][$this->mParams['internalhttpsession']];

			// get the params from the init session:
			$this->mUpload = new UploadFromFile();

			$this->mUpload->initialize( $this->mParams['filename'],
				$sd['target_file_path'],
				filesize( $sd['target_file_path'] )
			);

			if( !isset( $this->mUpload ) )
				$this->dieUsage( 'No upload module set', 'nomodule' );

		} else if( $this->mParams['httpstatus'] && $this->mParams['sessionkey'] ){
			// return the status of the given upload session_key:
			if( !isset( $_SESSION['wsDownload'][ $this->mParams['sessionkey'] ] ) ){
					return $this->dieUsageMsg( array( 'invalid-session-key' ) );
			}
			$sd = & $_SESSION['wsDownload'][$this->mParams['sessionkey']];
			// keep passing down the upload sessionkey
			$statusResult = array(
				'upload_session_key' => $this->mParams['sessionkey']
			);

			// put values into the final apiResult if available
			if( isset( $sd['apiUploadResult'] ) ) $statusResult['apiUploadResult'] = $sd['apiUploadResult'];
			if( isset( $sd['loaded'] ) ) $statusResult['loaded'] = $sd['loaded'];
			if( isset( $sd['content_length'] ) ) $statusResult['content_length'] = $sd['content_length'];

			return $this->getResult()->addValue( null, $this->getModuleName(),
						$statusResult
			);
		} else if( $this->mParams['sessionkey'] ) {
			// Stashed upload
			$this->mUpload = new UploadFromStash();
			$this->mUpload->initialize( $this->mParams['filename'], $_SESSION['wsUploadData'][$this->mParams['sessionkey']] );
		} else {
			// Upload from url or file
			// Parameter filename is required
			if( !isset( $this->mParams['filename'] ) )
				$this->dieUsageMsg( array( 'missingparam', 'filename' ) );

			// Initialize $this->mUpload
			if( isset( $this->mParams['file'] ) ) {
				$this->mUpload = new UploadFromFile();
				$this->mUpload->initialize(
					$request->getFileName( 'file' ),
					$request->getFileTempName( 'file' ),
					$request->getFileSize( 'file' )
				);
			} elseif( isset( $this->mParams['url'] ) ) {

				$this->mUpload = new UploadFromUrl();
				$this->mUpload->initialize( $this->mParams['filename'], $this->mParams['url'], $this->mParams['asyncdownload'] );

				$status = $this->mUpload->fetchFile();
				if( !$status->isOK() ){
					return $this->dieUsage( 'fetchfileerror', $status->getWikiText() );
				}
				//check if we doing a async request set session info and return the upload_session_key)
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
				//else the file downloaded in place continue with validation:
			}
		}

		if( !isset( $this->mUpload ) )
			$this->dieUsage( 'No upload module set', 'nomodule' );

		//finish up the exec command:
		$this->doExecUpload();
	}

	function doExecUpload(){
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

	private function performUpload() {
		global $wgUser;
		$result = array();
		$resultDetails = null;
		$permErrors = $this->mUpload->verifyPermissions( $wgUser );
		if( $permErrors !== true ) {
			$result['result'] = 'Failure';
			$result['error'] = 'permission-denied';
			return $result;
		}
		$verification = $this->mUpload->verifyUpload( $resultDetails );
		if( $verification != UploadBase::OK ) {
			$result['result'] = 'Failure';
			switch( $verification ) {
				case UploadBase::EMPTY_FILE:
					$result['error'] = 'empty-file';
					break;
				case UploadBase::FILETYPE_MISSING:
					$result['error'] = 'filetype-missing';
					break;
				case UploadBase::FILETYPE_BADTYPE:
					global $wgFileExtensions;
					$result['error'] = 'filetype-banned';
					$result['filetype'] = $resultDetails['finalExt'];
					$result['allowed-filetypes'] = $wgFileExtensions;
					break;
				case UploadBase::MIN_LENGHT_PARTNAME:
					$result['error'] = 'filename-tooshort';
					break;
				case UploadBase::ILLEGAL_FILENAME:
					$result['error'] = 'illegal-filename';
					$result['filename'] = $resultDetails['filtered'];
					break;
				case UploadBase::OVERWRITE_EXISTING_FILE:
					$result['error'] = 'overwrite';
					break;
				case UploadBase::VERIFICATION_ERROR:
					$result['error'] = 'verification-error';
					$args = $resultDetails['veri'];
					$code = array_shift( $args );
					$result['verification-error'] = $code;
					$result['args'] = $args;
					$this->getResult()->setIndexedTagName( $result['args'], 'arg' );
					break;
				case UploadBase::UPLOAD_VERIFICATION_ERROR:
					$result['error'] = 'upload-verification-error';
					$result['upload-verification-error'] = $resultDetails['error'];
					break;
				default:
					$result['error'] = 'unknown-error';
					$result['code'] = $verification;
					break;
			}
			return $result;
		}

		if( !$this->mParams['ignorewarnings'] ) {
			$warnings = $this->mUpload->checkWarnings();
			if( $warnings ) {
				$this->getResult()->setIndexedTagName( $warnings, 'warning' );

				$result['result'] = 'Warning';
				$result['warnings'] = $warnings;
				if( isset( $result['filewasdeleted'] ) )
					$result['filewasdeleted'] = $result['filewasdeleted']->getDBkey();

				$sessionKey = $this->mUpload->stashSession();
				if( $sessionKey )
					$result['sessionkey'] = $sessionKey;
				return $result;
			}
		}

		// do the upload
		$status = $this->mUpload->performUpload( $this->mParams['comment'],
			$this->mParams['comment'], $this->mParams['watch'], $wgUser );

		if( !$status->isGood() ) {
			$result['result'] = 'Failure';
			$result['error'] = 'internal-error';
			$result['details'] = $status->getErrorsArray();
			$this->getResult()->setIndexedTagName( $result['details'], 'error' );
			return $result;
		}

		$file = $this->mUpload->getLocalFile();
		$result['result'] = 'Success';
		$result['filename'] = $file->getName();

		// Append imageinfo to the result

		// might be a cleaner way to call this:
		$imParam = ApiQueryImageInfo::getAllowedParams();
		$imProp = $imParam['prop'][ApiBase::PARAM_TYPE];
		$result['imageinfo'] = ApiQueryImageInfo::getInfo( $file,
			array_flip( $imProp ),
			$this->getResult() );

		wfDebug( "\n\n return result: " . print_r( $result, true ) );

		return $result;
	}

	public function mustBePosted() {
		return true;
	}

	public function isWriteMode() {
		return true;
	}

	public function getAllowedParams() {
		return array(
			'filename' => null,
			'file' => null,
			'chunk' => null,
			'url' => null,
			'token' => null,
			'enablechunks' => null,
			'comment' => array(
				ApiBase::PARAM_DFLT => ''
			),
			'asyncdownload' => false,
			'watch' => false,
			'ignorewarnings' => false,
			'done' => false,
			'sessionkey' => null,
			'httpstatus' => false,
			'chunksessionkey' => null,
			'internalhttpsession' => null,
		);
	}

	public function getParamDescription() {
		return array(
			'filename' => 'Target filename',
			'file' => 'File contents',
			'chunk'=> 'Chunk File Contents',
			'url' => 'Url to upload from',
			'comment' => 'Upload comment or initial page text',
			'token' => 'Edit token. You can get one of these through prop=info (this helps avoid remote ajax upload requests with your credentials)',
			'enablechunks' => 'Boolean If we are in chunk mode; accepts many small file POSTs',
			'asyncdownload' => 'If we should download the url asyncrously usefull for large http downloads (returns a upload session key to get status updates in subquent calls)',
			'watch' => 'Watch the page',
			'ignorewarnings' => 'Ignore any warnings',
			'done'	=> 'When used with "chunks", Is sent to notify the api The last chunk is being uploaded.',
			'sessionkey' => 'Session key in case there were any warnings.',
			'httpstatus' => 'When set to true, will return the status of a given sessionKey (used for progress meters)',
			'chunksessionkey' => 'Used to sync uploading of chunks',
			'internalhttpsession' => 'Used internally for http session downloads',
		);
	}

	public function getDescription() {
		return array(
			'Upload a file'
		);
	}

	protected function getExamples() {
		return array(
			'api.php?action=upload&filename=Wiki.png&url=http%3A//upload.wikimedia.org/wikipedia/en/b/bc/Wiki.png&ignorewarnings'
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id: ApiUpload.php 51812 2009-06-12 23:45:20Z dale $';
	}
}

