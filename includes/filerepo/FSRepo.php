<?php
/**
 * A repository for files accessible via the local filesystem.
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
 * @ingroup FileRepo
 */

/**
 * A repository for files accessible via the local filesystem.
 * Does not support database access or registration.
 *
 * This is a mostly a legacy class. New uses should not be added.
 *
 * @ingroup FileRepo
 * @deprecated since 1.19
 */
class FSRepo extends FileRepo {
	/**
	 * @param array $info
	 * @throws MWException
	 */
	function __construct( array $info ) {
		if ( !isset( $info['backend'] ) ) {
			// B/C settings...
			$directory = $info['directory'];
			$deletedDir = isset( $info['deletedDir'] )
				? $info['deletedDir']
				: false;
			$thumbDir = isset( $info['thumbDir'] )
				? $info['thumbDir']
				: "{$directory}/thumb";
			$transcodedDir = isset( $info['transcodedDir'] )
				? $info['transcodedDir']
				: "{$directory}/transcoded";
			$fileMode = isset( $info['fileMode'] )
				? $info['fileMode']
				: 0644;

			$repoName = $info['name'];
			// Get the FS backend configuration
			$backend = new FSFileBackend( array(
				'name' => $info['name'] . '-backend',
				'wikiId' => wfWikiID(),
				'lockManager' => LockManagerGroup::singleton( wfWikiID() )->get( 'fsLockManager' ),
				'containerPaths' => array(
					"{$repoName}-public" => "{$directory}",
					"{$repoName}-temp" => "{$directory}/temp",
					"{$repoName}-thumb" => $thumbDir,
					"{$repoName}-transcoded" => $transcodedDir,
					"{$repoName}-deleted" => $deletedDir
				),
				'fileMode' => $fileMode,
			) );
			// Update repo config to use this backend
			$info['backend'] = $backend;
		}

		parent::__construct( $info );

		if ( !( $this->backend instanceof FSFileBackend ) ) {
			throw new MWException( "FSRepo only supports FSFileBackend." );
		}
	}
}
