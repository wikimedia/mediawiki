<?php
/**
 * Job for asynchronous cleanup of least accessed thumbnails.
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
 * Job for asynchronous cleanup of least accessed thumbnails.
 *
 * @ingroup JobQueue
 */
class LeastAccessedThumbnailsCleanupJob extends Job {
	public function __construct( $title, array $params ) {
		if ( !isset( $params['unaccessedDays'] ) ) {
			$params['unaccessedDays'] = 30;
		}

		parent::__construct( 'LeastAccessedThumbnailsCleanupJob', $title, $params );

		$this->removeDuplicates = true;
	}

	public function run() {
		global $wgUploadDirectory, $wgFindCommand;

		$cmd = wfEscapeShellArg( $wgFindCommand,
			"{$wgUploadDirectory}/thumb",
			'-atime',
			'+' . $this->params['unaccessedDays'],
			'-delete'
		);

		$retval = '';

		$return = wfShellExec( $cmd, $retval );

		return $retval === 0;
	}
}
