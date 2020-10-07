<?php
/**
 * Delete archived (non-current) files from the database
 *
 * Based on deleteOldRevisions.php by Rob Church.
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

use MediaWiki\MediaWikiServices;

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script to delete archived (non-current) files from the database.
 *
 * @ingroup Maintenance
 */
class DeleteArchivedFiles extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Deletes all archived images.' );
		$this->addOption( 'delete', 'Perform the deletion' );
		$this->addOption( 'force', 'Force deletion of rows from filearchive' );
	}

	public function execute() {
		if ( !$this->hasOption( 'delete' ) ) {
			$this->output( "Use --delete to actually confirm this script\n" );
			return;
		}

		# Data should come off the master, wrapped in a transaction
		$dbw = $this->getDB( DB_MASTER );
		$this->beginTransaction( $dbw, __METHOD__ );
		$repo = MediaWikiServices::getInstance()->getRepoGroup()->getLocalRepo();

		# Get "active" revisions from the filearchive table
		$this->output( "Searching for and deleting archived files...\n" );
		$res = $dbw->select(
			'filearchive',
			[ 'fa_id', 'fa_storage_group', 'fa_storage_key', 'fa_sha1', 'fa_name' ],
			'',
			__METHOD__
		);

		$count = 0;
		foreach ( $res as $row ) {
			$key = $row->fa_storage_key;
			if ( !strlen( $key ) ) {
				$this->output( "Entry with ID {$row->fa_id} has empty key, skipping\n" );
				continue;
			}

			$file = $repo->newFile( $row->fa_name );
			try {
				$file->lock();
			} catch ( LocalFileLockError $e ) {
				$this->error( "Could not acquire lock on '{$row->fa_name}', skipping\n" );
				continue;
			}

			$group = $row->fa_storage_group;
			$id = $row->fa_id;
			$path = $repo->getZonePath( 'deleted' ) .
				'/' . $repo->getDeletedHashPath( $key ) . $key;
			if ( isset( $row->fa_sha1 ) ) {
				$sha1 = $row->fa_sha1;
			} else {
				// old row, populate from key
				$sha1 = LocalRepo::getHashFromKey( $key );
			}

			// Check if the file is used anywhere...
			$inuse = $dbw->selectField(
				'oldimage',
				'1',
				[
					'oi_sha1' => $sha1,
					$dbw->bitAnd( 'oi_deleted', File::DELETED_FILE ) => File::DELETED_FILE
				],
				__METHOD__,
				[ 'FOR UPDATE' ]
			);

			$needForce = true;
			if ( !$repo->fileExists( $path ) ) {
				$this->output( "Notice - file '$key' not found in group '$group'\n" );
			} elseif ( $inuse ) {
				$this->output( "Notice - file '$key' is still in use\n" );
			} elseif ( !$repo->quickPurge( $path ) ) {
				$this->output( "Unable to remove file $path, skipping\n" );
				$file->unlock();

				// don't delete even with --force
				continue;
			} else {
				$needForce = false;
			}

			if ( $needForce ) {
				if ( $this->hasOption( 'force' ) ) {
					$this->output( "Got --force, deleting DB entry\n" );
				} else {
					$file->unlock();
					continue;
				}
			}

			$count++;
			$dbw->delete( 'filearchive', [ 'fa_id' => $id ], __METHOD__ );
			$file->unlock();
		}

		$this->commitTransaction( $dbw, __METHOD__ );
		$this->output( "Done! [$count file(s)]\n" );
	}
}

$maintClass = DeleteArchivedFiles::class;
require_once RUN_MAINTENANCE_IF_MAIN;
