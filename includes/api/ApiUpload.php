<?php
/*
 * Created on Aug 21, 2008
 * API for MediaWiki 1.8+
 *
 * Copyright (C) 2008 - 2010 Bryan Tong Minh <Bryan.TongMinh@Gmail.com>
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
	require_once( "ApiBase.php" );
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
		global $wgUser, $wgAllowCopyUploads;

		// Check whether upload is enabled
		if ( !UploadBase::isEnabled() )
			$this->dieUsageMsg( array( 'uploaddisabled' ) );

		$this->mParams = $this->extractRequestParams();
		$request = $this->getMain()->getRequest();

		// Add the uploaded file to the params array
		$this->mParams['file'] = $request->getFileName( 'file' );

		// One and only one of the following parameters is needed
		$this->requireOnlyOneParameter( $this->mParams,
			'sessionkey', 'file', 'url' );

		if ( $this->mParams['sessionkey'] ) {
			/**
			 * Upload stashed in a previous request
			 */
			// Check the session key
			if ( !isset( $_SESSION['wsUploadData'][$this->mParams['sessionkey']] ) )
				$this->dieUsageMsg( array( 'invalid-session-key' ) );

			$this->mUpload = new UploadFromStash();
			$this->mUpload->initialize( $this->mParams['filename'],
				$this->mParams['sessionkey'],
				$_SESSION['wsUploadData'][$this->mParams['sessionkey']] );
		} elseif ( isset( $this->mParams['filename'] ) ) {
			/**
			 * Upload from url, etc
			 * Parameter filename is required
			 */

			if ( isset( $this->mParams['file'] ) ) {
				$this->mUpload = new UploadFromFile();
				$this->mUpload->initialize(
					$this->mParams['filename'],
					$request->getFileTempName( 'file' ),
					$request->getFileSize( 'file' )
				);
			} elseif ( isset( $this->mParams['url'] ) ) {
				// make sure upload by url is enabled:
				if ( !$wgAllowCopyUploads )
					$this->dieUsageMsg( array( 'uploaddisabled' ) );

				// make sure the current user can upload
				if ( ! $wgUser->isAllowed( 'upload_by_url' ) )
					$this->dieUsageMsg( array( 'badaccess-groups' ) );

				$this->mUpload = new UploadFromUrl();
				$this->mUpload->initialize( $this->mParams['filename'],
						$this->mParams['url'] );

				$status = $this->mUpload->fetchFile();
				if ( !$status->isOK() ) {
					$this->dieUsage( $status->getWikiText(),  'fetchfileerror' );
				}
			}
		} else $this->dieUsageMsg( array( 'missingparam', 'filename' ) );

		if ( !isset( $this->mUpload ) )
			$this->dieUsage( 'No upload module set', 'nomodule' );

		// Check whether the user has the appropriate permissions to upload anyway
		$permission = $this->mUpload->isAllowed( $wgUser );

		if ( $permission !== true ) {
			if ( !$wgUser->isLoggedIn() )
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
		if ( $permErrors !== true ) {
			$this->dieUsageMsg( array( 'badaccess-groups' ) );
		}

		// TODO: Move them to ApiBase's message map
		$verification = $this->mUpload->verifyUpload();
		if ( $verification['status'] !== UploadBase::OK ) {
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
				case UploadBase::MIN_LENGTH_PARTNAME:
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
		if ( !$this->mParams['ignorewarnings'] ) {
			$warnings = $this->mUpload->checkWarnings();
			if ( $warnings ) {
				// Add indices
				$this->getResult()->setIndexedTagName( $warnings, 'warning' );

				if ( isset( $warnings['duplicate'] ) ) {
					$dupes = array();
					foreach ( $warnings['duplicate'] as $key => $dupe )
						$dupes[] = $dupe->getName();
					$this->getResult()->setIndexedTagName( $dupes, 'duplicate' );
					$warnings['duplicate'] = $dupes;
				}


				if ( isset( $warnings['exists'] ) ) {
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

		// Use comment as initial page text by default
		if ( is_null( $this->mParams['text'] ) )
			$this->mParams['text'] = $this->mParams['comment'];

		// No errors, no warnings: do the upload
		$status = $this->mUpload->performUpload( $this->mParams['comment'],
			$this->mParams['text'], $this->mParams['watch'], $wgUser );

		if ( !$status->isGood() ) {
			$error = $status->getErrorsArray();
			$this->getResult()->setIndexedTagName( $result['details'], 'error' );

			$this->dieUsage( 'An internal error occurred', 'internal-error', 0, $error );
		}

		$file = $this->mUpload->getLocalFile();
		$result['result'] = 'Success';
		$result['filename'] = $file->getName();
		$result['imageinfo'] = $this->mUpload->getImageInfo( $this->getResult() );

		return $result;
	}

	public function mustBePosted() {
		return true;
	}

	public function isWriteMode() {
		return true;
	}

	public function getAllowedParams() {
		$params = array(
			'filename' => null,
			'comment' => array(
				ApiBase::PARAM_DFLT => ''
			),
			'text' => null,
			'token' => null,
			'watch' => false,
			'ignorewarnings' => false,
			'file' => null,
			'url' => null,
			'sessionkey' => null,
		);
		return $params;

	}

	public function getParamDescription() {
		return array(
			'filename' => 'Target filename',
			'token' => 'Edit token. You can get one of these through prop=info',
			'comment' => 'Upload comment. Also used as the initial page text for new files if "text" is not specified',
			'text' => 'Initial page text for new files',
			'watch' => 'Watch the page',
			'ignorewarnings' => 'Ignore any warnings',
			'file' => 'File contents',
			'url' => 'Url to fetch the file from',
			'sessionkey' => array(
				'Session key returned by a previous upload that failed due to warnings',
			),
		);
	}

	public function getDescription() {
		return array(
			'Upload a file, or get the status of pending uploads. Several methods are available:',
			' * Upload file contents directly, using the "file" parameter',
			' * Have the MediaWiki server fetch a file from a URL, using the "url" parameter',
			' * Complete an earlier upload that failed due to warnings, using the "sessionkey" parameter',
			'Note that the HTTP POST must be done as a file upload (i.e. using multipart/form-data) when',
			'sending the "file". Note also that queries using session keys must be',
			'done in the same login session as the query that originally returned the key (i.e. do not',
			'log out and then log back in). Also you must get and send an edit token before doing any upload stuff.'
		);
	}
	
    public function getPossibleErrors() {
		return array_merge( parent::getPossibleErrors(), array(
			array( 'uploaddisabled' ),
			array( 'invalid-session-key' ),
			array( 'uploaddisabled' ),
			array( 'badaccess-groups' ),
			array( 'missingparam', 'filename' ),
			array( 'mustbeloggedin', 'upload' ),
			array( 'badaccess-groups' ),
			array( 'badaccess-groups' ),
			array( 'code' => 'fetchfileerror', 'info' => '' ),
			array( 'code' => 'nomodule', 'info' => 'No upload module set' ),
			array( 'code' => 'empty-file', 'info' => 'The file you submitted was empty' ),
			array( 'code' => 'filetype-missing', 'info' => 'The file is missing an extension' ),
			array( 'code' => 'filename-tooshort', 'info' => 'The filename is too short' ),
			array( 'code' => 'overwrite', 'info' => 'Overwriting an existing file is not allowed' ),
			array( 'code' => 'stashfailed', 'info' => 'Stashing temporary file failed' ),
			array( 'code' => 'internal-error', 'info' => 'An internal error occurred' ),
        ) );
	}
	
	public function getTokenSalt() {
		return '';
	}

	protected function getExamples() {
		return array(
			'Upload from a URL:',
			'    api.php?action=upload&filename=Wiki.png&url=http%3A//upload.wikimedia.org/wikipedia/en/b/bc/Wiki.png',
			'Complete an upload that failed due to warnings:',
			'    api.php?action=upload&filename=Wiki.png&sessionkey=sessionkey&ignorewarnings=1',
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id: ApiUpload.php 51812 2009-06-12 23:45:20Z dale $';
	}
}
