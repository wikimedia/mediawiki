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
 */

use MediaWiki\MediaWikiServices;

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
		$this->addDescription( 'Erases traces of deleted files.' );
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

		if ( $filekey === '*' ) {
			// all versions by name
			if ( !strlen( $filename ) ) {
				$this->fatalError( "Missing --filename parameter." );
			}
			$afile = false;
		} else {
			// specified version
			$dbw = $this->getDB( DB_MASTER );
			$fileQuery = ArchivedFile::getQueryInfo();
			$row = $dbw->selectRow( $fileQuery['tables'], $fileQuery['fields'],
				[ 'fa_storage_group' => 'deleted', 'fa_storage_key' => $filekey ],
				__METHOD__, [], $fileQuery['joins'] );
			if ( !$row ) {
				$this->fatalError( "No deleted file exists with key '$filekey'." );
			}
			$filename = $row->fa_name;
			$afile = ArchivedFile::newFromRow( $row );
		}

		$file = MediaWikiServices::getInstance()->getRepoGroup()->getLocalRepo()->newFile( $filename );
		if ( $file->exists() ) {
			$this->fatalError( "File '$filename' is still a public file, use the delete form.\n" );
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
		$dbw = $this->getDB( DB_MASTER );
		$fileQuery = ArchivedFile::getQueryInfo();
		$res = $dbw->select( $fileQuery['tables'], $fileQuery['fields'],
			[ 'fa_name' => $name, 'fa_storage_group' => 'deleted' ],
			__METHOD__, [], $fileQuery['joins'] );
		foreach ( $res as $row ) {
			$this->scrubVersion( ArchivedFile::newFromRow( $row ) );
		}
	}

	protected function scrubVersion( ArchivedFile $archivedFile ) {
		$key = $archivedFile->getStorageKey();
		$name = $archivedFile->getName();
		$ts = $archivedFile->getTimestamp();
		$repo = MediaWikiServices::getInstance()->getRepoGroup()->getLocalRepo();
		$path = $repo->getZonePath( 'deleted' ) . '/' . $repo->getDeletedHashPath( $key ) . $key;
		if ( $this->hasOption( 'delete' ) ) {
			$status = $repo->getBackend()->delete( [ 'src' => $path ] );
			if ( $status->isOK() ) {
				$this->output( "Deleted version '$key' ($ts) of file '$name'\n" );
			} else {
				$this->output( "Failed to delete version '$key' ($ts) of file '$name'\n" );
				$this->output( print_r( Status::wrap( $status )->getErrorsArray(), true ) );
			}
		} else {
			$this->output( "Would delete version '{$key}' ({$ts}) of file '$name'\n" );
		}
	}
}

$maintClass = EraseArchivedFile::class;
require_once RUN_MAINTENANCE_IF_MAIN;
