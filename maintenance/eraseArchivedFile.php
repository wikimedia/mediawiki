<?php
/**
 * Delete archived (non-current) files from storage
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
 * @author Aaron Schulz
 */

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script to delete archived (non-current) files from storage.
 *
 * @todo Maybe add some simple logging
 *
 * @ingroup Maintenance
 * @since 1.22
 */
class EraseArchivedFile extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Erases traces of deleted files.";
		$this->addOption( 'delete', 'Perform the deletion' );
		$this->addOption( 'filename', 'File name', false, true );
		$this->addOption( 'filekey', 'File storage key (with extension) or "*"', true, true );
	}

	public function execute() {
		if ( !$this->hasOption( 'delete' ) ) {
			$this->output( "Use --delete to actually confirm this script\n" );
		}

		$filekey = $this->getOption( 'filekey' );
		$filename = $this->getOption( 'filename' );

		if ( $filekey === '*' ) { // all versions by name
			if ( !strlen( $filename ) ) {
				$this->error( "Missing --filename parameter.", 1 );
			}
			$afile = false;
		} else { // specified version
			$dbw = wfGetDB( DB_MASTER );
			$row = $dbw->selectRow( 'filearchive', '*',
				array( 'fa_storage_group' => 'deleted', 'fa_storage_key' => $filekey ),
				__METHOD__ );
			if ( !$row ) {
				$this->error( "No deleted file exists with key '$filekey'.", 1 );
			}
			$filename = $row->fa_name;
			$afile = ArchivedFile::newFromRow( $row );
		}

		$file = wfLocalFile( $filename );
		if ( $file->exists() ) {
			$this->error( "File '$filename' is still a public file, use the delete form.\n", 1 );
		}

		$this->output( "Purging all thumbnails for file '$filename'..." );
		$file->purgeCache();
		$this->output( "done.\n" );

		if ( $afile instanceof ArchivedFile ) {
			$this->scrubVersion( $afile );
		} else {
			$this->output( "Finding deleted versions of file '$filename'...\n" );
			$this->scrubAllVersions( $filename );
			$this->output( "Done\n" );
		}
	}

	protected function scrubAllVersions( $name ) {
		$dbw = wfGetDB( DB_MASTER );
		$res = $dbw->select( 'filearchive', '*',
			array( 'fa_name' => $name, 'fa_storage_group' => 'deleted' ),
			__METHOD__ );
		foreach ( $res as $row ) {
			$this->scrubVersion( ArchivedFile::newFromRow( $row ) );
		}
	}

	protected function scrubVersion( ArchivedFile $archivedFile ) {
		$key = $archivedFile->getStorageKey();
		$name = $archivedFile->getName();
		$ts = $archivedFile->getTimestamp();
		$repo = RepoGroup::singleton()->getLocalRepo();
		$path = $repo->getZonePath( 'deleted' ) . '/' . $repo->getDeletedHashPath( $key ) . $key;
		if ( $this->hasOption( 'delete' ) ) {
			$status = $repo->getBackend()->delete( array( 'src' => $path ) );
			if ( $status->isOK() ) {
				$this->output( "Deleted version '$key' ($ts) of file '$name'\n" );
			} else {
				$this->output( "Failed to delete version '$key' ($ts) of file '$name'\n" );
				$this->output( print_r( $status->getErrorsArray(), true ) );
			}
		} else {
			$this->output( "Would delete version '{$key}' ({$ts}) of file '$name'\n" );
		}
	}
}

$maintClass = "EraseArchivedFile";
require_once RUN_MAINTENANCE_IF_MAIN;
