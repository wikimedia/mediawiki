<?php
/**
 *
 * Created on January 3rd, 2013
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

class ApiRotate extends ApiBase {

	public function __construct( $main, $action ) {
		parent::__construct( $main, $action );
	}

	public function execute() {
		$params = $this->extractRequestParams();
		$rotation = $params[ 'rotation' ];
		$filenames = $params[ 'filename' ];
		$user = $this->getUser();

		if( is_null( $rotation ) || $rotation % 90 ) {
			$this->dieUsage( "Rotation: {$rotation}", 'rotation must be multiple of 90 degrees' );
		}

		foreach( $filenames as $filename ) {
			$filename = Title::makeTitleSafe( NS_FILE, $filename );
			$file = wfFindFile( $filename );

			if ( !$file ) {
				$this->dieUsage( "File: {$filename}", 'not found' );
			}
			$handler = $file->getHandler();
			if ( !$handler || !$handler->canRotate() ) {
				$this->dieUsage( "File: {$filename}", 'does not support rotation' );
			}

			// Check whether we're allowed to rotate this file
			$this->checkPermissions( $this->getUser(), $file->getTitle() );

			$srcPath = $file->getLocalRefPath();
			$ext = strtolower( pathinfo( "$srcPath", PATHINFO_EXTENSION ) );
			$tmpFile = TempFSFile::factory( 'rotate_', $ext);
			$dstPath = $tmpFile->getPath();
			$err = $handler->rotate( $file, array(
				"srcPath" => $srcPath,
				"dstPath" => $dstPath,
				"rotation"=> $rotation
			) );
			if ( !$err ) {
				$comment = wfMessage( 'rotate-comment' )->numParams( $rotation )->text();
				$status = $file->upload( $dstPath,
					$comment, $comment, 0, false, false, $this->getUser() );
				if ( $status->isGood() ) {
					$result = array( 'result' => 'Success' );
				} else {
					$result = array(
						'result' => 'Failure',
						'errors' => $this->getResult()->convertStatusToArray( $status ),
					);
				}
			} else {
				$result = array(
					'result' => 'Failure',
					'errors' => $err->toText(),
				);
			}
			$this->getResult()->addValue( null, $this->getModuleName(), $result );
		}
	}

	/**
	 * Checks that the user has permissions to perform rotations.
	 * Dies with usage message on inadequate permissions.
	 * @param $user User The user to check.
	 */
	protected function checkPermissions( $user, $title ) {
		$permissionErrors = array_merge(
			$title->getUserPermissionsErrors( 'edit' , $user ),
			$title->getUserPermissionsErrors( 'upload' , $user )
		);

		if ( $permissionErrors ) {
			$this->dieUsageMsg( $permissionErrors[0] );
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
				ApiBase::PARAM_REQUIRED => true,
				ApiBase::PARAM_ISMULTI => true,
			),
			'rotation' => array(
				ApiBase::PARAM_DFLT => 0,
			),
			'token' => array(
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => true
			),
		);
	}

	public function getParamDescription() {
		return array(
			'filename' => 'The filename of the image(s) to be rotated',
			'rotation'	=> 'Degrees to rotate image, values can be 0, 90, 180 or 270',
			'token' => 'Edit token. You can get one of these through prop=info',
		);
	}

	public function getDescription() {
		return 'Rotate one or more images';
	}

	public function needsToken() {
		return true;
	}

	public function getTokenSalt() {
		return '';
	}

	public function getExamples() {
		return array(
			'api.php?action=rotate&filename=Example.jpg&rotation=90&token=+\\',
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
