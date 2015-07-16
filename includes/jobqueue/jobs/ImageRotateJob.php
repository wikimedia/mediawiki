<?php
/**
 * Job for asynchronous rotation and resaving of images.
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
 * @ingroup JobQueue
 */

/**
 * Job for asynchronous rotation and resaving of images.
 *
 * @ingroup JobQueue
 */
class ImageRotateJob extends Job {
	public function __construct( Title $title, array $params ) {
		parent::__construct( 'ImageRotate', $title, $params );
	}

	public function run() {
		$userId = intval( $this->params['user_id'] );
		$rotation = intval( $this->params['rotation'] );

		$file = wfFindFile( $this->title, array( 'latest' => true ) );
		if ( !$file ) {
			$this->setLastError( __METHOD__ . ': file doesn\'t exist' );
			return false;
		}

		$handler = $file->getHandler();
		if ( !$handler || !$handler->canRotate() ) {
			$this->setLastError( __METHOD__ . ': File type cannot be rotated' );
			return false;
		}

		$user = User::newFromId( $userId );
		if ( !$user || !$user->exists() ) {
			$this->setLastError( __METHOD__ . ': bad user_id' );
			return false;
		}

		// Check whether we're allowed to rotate this file
		$permError = $this->checkPermissions( $user, $file->getTitle() );
		if ( $permError !== null ) {
			$this->setLastError( __METHOD__ . ': ' . $permError );
			return false;
		}

		$srcPath = $file->getLocalRefPath();
		if ( $srcPath === false ) {
			$this->setLastError( __METHOD__ . ': Cannot get local file path' );
			return false;
		}
		$ext = strtolower( pathinfo( "$srcPath", PATHINFO_EXTENSION ) );
		$tmpFile = TempFSFile::factory( 'rotate_', $ext );
		$dstPath = $tmpFile->getPath();
		$err = $handler->rotate( $file, array(
			"srcPath" => $srcPath,
			"dstPath" => $dstPath,
			"rotation" => $rotation
		) );
		if ( !$err ) {
			$comment = wfMessage(
				'rotate-comment'
			)->numParams( $rotation )->inContentLanguage()->text();
			$status = $file->upload( $dstPath,
				$comment, $comment, 0, false, false, $user );
			if ( $status->isGood() ) {
				return true;
			} else {
				$this->setLastError( __METHOD__ . ': Failure: ' . $status->getMessage()->text() );
				return false;
			}
		} else {
			$this->setLastError( __METHOD__ . ': Failure: ' . $err->toText() );
			return false;
		}
	}

	/**
	 * Checks that the user has permissions to perform rotations.
	 * @param User $user The user to check
	 * @param Title $title
	 * @return string|null Permission error message, or null if there is no error
	 */
	protected function checkPermissions( User $user, Title $title ) {
		$permissionErrors = array_merge(
			$title->getUserPermissionsErrors( 'edit', $user ),
			$title->getUserPermissionsErrors( 'upload', $user )
		);

		if ( $permissionErrors ) {
			// Just return the first error
			return $permissionErrors[0]; // @fixme make this prettier
		}

		return null;
	}

}
