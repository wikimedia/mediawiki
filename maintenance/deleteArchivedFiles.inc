<?php
/**
 * Core functions for deleteArchivedFiles.php
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
 * @ingroup Maintenance
 */

/**
 * Core functions for deleteArchivedFiles.php
 *
 * @ingroup Maintenance
 */
class DeleteArchivedFilesImplementation {
	public static function doDelete( $output, $force ) {
		# Data should come off the master, wrapped in a transaction
		$dbw = wfGetDB( DB_MASTER );
		$dbw->begin( __METHOD__ );
		$tbl_arch = $dbw->tableName( 'filearchive' );
		$repo = RepoGroup::singleton()->getLocalRepo();
		# Get "active" revisions from the filearchive table
		$output->handleOutput( "Searching for and deleting archived files...\n" );
		$res = $dbw->query( "SELECT fa_id,fa_storage_group,fa_storage_key,fa_sha1 FROM $tbl_arch" );
		$count = 0;
		foreach ( $res as $row ) {
			$key = $row->fa_storage_key;
			$group = $row->fa_storage_group;
			$id = $row->fa_id;
			$path = $repo->getZonePath( 'deleted' ) . '/' . $repo->getDeletedHashPath( $key ) . $key;
			if ( isset( $row->fa_sha1 ) ) {
				$sha1 = $row->fa_sha1;
			} else {
				// old row, populate from key
				$sha1 = LocalRepo::getHashFromKey( $key );
			}
			// Check if the file is used anywhere...
			$inuse = $dbw->selectField( 'oldimage', '1',
				array( 'oi_sha1' => $sha1,
				'oi_deleted & ' . File::DELETED_FILE => File::DELETED_FILE ),
				__METHOD__,
				array( 'FOR UPDATE' )
			);
			if ( $path && $repo->fileExists( $path ) && !$inuse ) {
				if ( $repo->quickPurge( $path ) ) {
					$count++;
					$dbw->query( "DELETE FROM $tbl_arch WHERE fa_id = $id" );
				} else {
					$output->handleOutput( "Unable to remove file $path, skipping\n" );
				}
			} else {
				$output->handleOutput( "Notice - file '$key' not found in group '$group'\n" );
				if ( $force ) {
					$output->handleOutput( "Got --force, deleting DB entry\n" );
					$dbw->query( "DELETE FROM $tbl_arch WHERE fa_id = $id" );
				}
			}
		}
		$dbw->commit( __METHOD__ );
		$output->handleOutput( "Done! [$count file(s)]\n" );
	}
}
