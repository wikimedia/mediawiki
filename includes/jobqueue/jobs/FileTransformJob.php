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
class FileTransformJob extends Job {
	public function __construct( Title $title, array $params ) {
		parent::__construct( 'FileTransform', $title, $params );
	}

	public function run() {
		$id = intval( $this->params['ft_id'] );
		$ft = FileTransform::newFromId( $id );

		if ( !$ft ) {
			$this->setLastError( __METHOD__ . ': invalid file_transform.ft_id for job: ' . $this->params['ft_id'] );
			return false;
		}

		$status = $ft->execute();

		// clean it up
		$ft->delete();

		if ( $status->isGood() ) {
			return true;
		} else {
			// @fixme report error back to user
			$this->setLastError( __METHOD__ . ': transform failed: ' . $status->getMessage()->text() );
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
