<?php
/**
 *
 *
 * Created on March 5, 2011
 *
 * Copyright © 2011 Bryan Tong Minh <Bryan.TongMinh@Gmail.com>
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

if ( !defined( 'MEDIAWIKI' ) ) {
	// Eclipse helper - will be ignored in production
	require_once( "ApiBase.php" );
}

/**
 * @ingroup API
 */
class ApiFileRevert extends ApiBase {

	/**
	 * @var File
	 */
	protected $file;
	protected $archiveName;
	
	protected $params;

	public function __construct( $main, $action ) {
		parent::__construct( $main, $action );
	}

	public function execute() {
		global $wgUser;

		// First check permission to upload/revert
		$this->checkPermissions( $wgUser );

		$this->params = $this->extractRequestParams();
		$this->validateParameters();
		

		$sourceUrl = $this->file->getArchiveVirtualUrl( $this->archiveName );
		$status = $this->file->upload( $sourceUrl, $this->params['comment'], $this->params['comment'] );
		
		if ( $status->isGood() ) {
			$result = array( 'result' => 'Success' );
		} else {
			$result = array( 
				'result' => 'Failure', 
				'errors' => $this->getResult()->convertStatusToArray( $status ),
			);
		}
			
		$this->getResult()->addValue( null, $this->getModuleName(), $result );	

	}

	/**
	 * Checks that the user has permissions to perform this revert.
	 * Dies with usage message on inadequate permissions.
	 * @param $user User The user to check.
	 */
	protected function checkPermissions( $user ) {
		$permission = $user->isAllowed( 'edit' ) && $user->isAllowed( 'upload' );

		if ( $permission !== true ) {
			if ( !$user->isLoggedIn() ) {
				$this->dieUsageMsg( array( 'mustbeloggedin', 'upload' ) );
			} else {
				$this->dieUsageMsg( array( 'badaccess-groups' ) );
			}
		}
	}
	
	/**
	 * Validate the user parameters and set $this->archiveName and $this->file.
	 * Throws an error if validation fails
	 */
	protected function validateParameters() {
		// Validate the input title
		$title = Title::makeTitleSafe( NS_FILE, $this->params['filename'] );
		if ( is_null( $title ) ) {
			$this->dieUsageMsg( array( 'invalidtitle', $this->params['filename'] ) );
		}
		// Check if the file really exists
		$this->file = wfLocalFile( $title );
		if ( !$this->file->exists() ) {
			$this->dieUsageMsg( array( 'notanarticle' ) );
		}
		
		// Check if the archivename is valid for this file
		$this->archiveName = $this->params['archivename'];
		$oldFile = RepoGroup::singleton()->getLocalRepo()->newFromArchiveName( $title, $this->archiveName );
		if ( !$oldFile->exists() ) {
			$this->dieUsageMsg( array( 'filerevert-badversion' ) );
		}
	}
	
	

	public function mustBePosted() {
		return true;
	}

	public function isWriteMode() {
		return true;
	}

	public function getAllowedParams() {
		return array(
			'filename' => array(
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => true,
			),
			'comment' => array(
				ApiBase::PARAM_DFLT => '',
			),
			'archivename' => array(
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => true,			
			),
			'token' => null,
		);

	}

	public function getParamDescription() {
		$params = array(
			'filename' => 'Target filename',
			'token' => 'Edit token. You can get one of these through prop=info',
			'comment' => 'Upload comment',
			'archivename' => 'Archive name of the revision to revert to',
		);

		return $params;

	}

	public function getDescription() {
		return array(
			'Revert a file to an old version'
		);
	}

	public function getPossibleErrors() {
		return array_merge( parent::getPossibleErrors(),
			array(
				array( 'mustbeloggedin', 'upload' ),
				array( 'badaccess-groups' ),
				array( 'invalidtitle', 'title' ),
				array( 'notanarticle' ),
				array( 'filerevert-badversion' ),
			)
		);
	}

	public function needsToken() {
		return true;
	}

	public function getTokenSalt() {
		return '';
	}

	protected function getExamples() {
		return array(
			'Revert Wiki.png to the version of 20110305152740:',
			'    api.php?action=filerevert&filename=Wiki.png&comment=Revert&archivename=20110305152740!Wiki.png&token=+\\',
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
