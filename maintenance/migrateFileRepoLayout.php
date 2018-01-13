<?php
/**
 * Copy all files in FileRepo to an originals container using SHA1 paths.
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

require_once __DIR__ . '/Maintenance.php';

/**
 * Copy all files in FileRepo to an originals container using SHA1 paths.
 *
 * This script should be run while the repo is still set to the old layout.
 *
 * @ingroup Maintenance
 */
class MigrateFileRepoLayout extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Copy files in repo to a different layout.' );
		$this->addOption( 'oldlayout', "Old layout; one of 'name' or 'sha1'", true, true );
		$this->addOption( 'newlayout', "New layout; one of 'name' or 'sha1'", true, true );
		$this->addOption( 'since', "Copy only files from after this timestamp", false, true );
		$this->setBatchSize( 50 );
	}

	public function execute() {
		$oldLayout = $this->getOption( 'oldlayout' );
		if ( !in_array( $oldLayout, [ 'name', 'sha1' ] ) ) {
			$this->fatalError( "Invalid old layout." );
		}
		$newLayout = $this->getOption( 'newlayout' );
		if ( !in_array( $newLayout, [ 'name', 'sha1' ] ) ) {
			$this->fatalError( "Invalid new layout." );
		}
		$since = $this->getOption( 'since' );

		$repo = $this->getRepo();

		$be = $repo->getBackend();
		if ( $be instanceof FileBackendDBRepoWrapper ) {
			$be = $be->getInternalBackend(); // avoid path translations for this script
		}

		$dbw = $repo->getMasterDB();

		$origBase = $be->getContainerStoragePath( "{$repo->getName()}-original" );
		$startTime = wfTimestampNow();

		// Do current and archived versions...
		$conds = [];
		if ( $since ) {
			$conds[] = 'img_timestamp >= ' . $dbw->addQuotes( $dbw->timestamp( $since ) );
		}

		$batchSize = $this->getBatchSize();
		$batch = [];
		$lastName = '';
		do {
			$res = $dbw->select( 'image',
				[ 'img_name', 'img_sha1' ],
				array_merge( [ 'img_name > ' . $dbw->addQuotes( $lastName ) ], $conds ),
				__METHOD__,
				[ 'LIMIT' => $batchSize, 'ORDER BY' => 'img_name' ]
			);

			foreach ( $res as $row ) {
				$lastName = $row->img_name;
				/** @var LocalFile $file */
				$file = $repo->newFile( $row->img_name );
				// Check in case SHA1 rows are not populated for some files
				$sha1 = strlen( $row->img_sha1 ) ? $row->img_sha1 : $file->getSha1();

				if ( !strlen( $sha1 ) ) {
					$this->error( "Image SHA-1 not known for {$row->img_name}." );
				} else {
					if ( $oldLayout === 'sha1' ) {
						$spath = "{$origBase}/{$sha1[0]}/{$sha1[1]}/{$sha1[2]}/{$sha1}";
					} else {
						$spath = $file->getPath();
					}

					if ( $newLayout === 'sha1' ) {
						$dpath = "{$origBase}/{$sha1[0]}/{$sha1[1]}/{$sha1[2]}/{$sha1}";
					} else {
						$dpath = $file->getPath();
					}

					$status = $be->prepare( [
						'dir' => dirname( $dpath ), 'bypassReadOnly' => 1 ] );
					if ( !$status->isOK() ) {
						$this->error( print_r( $status->getErrors(), true ) );
					}

					$batch[] = [ 'op' => 'copy', 'overwrite' => true,
						'src' => $spath, 'dst' => $dpath, 'img' => $row->img_name ];
				}

				foreach ( $file->getHistory() as $ofile ) {
					$sha1 = $ofile->getSha1();
					if ( !strlen( $sha1 ) ) {
						$this->error( "Image SHA-1 not set for {$ofile->getArchiveName()}." );
						continue;
					}

					if ( $oldLayout === 'sha1' ) {
						$spath = "{$origBase}/{$sha1[0]}/{$sha1[1]}/{$sha1[2]}/{$sha1}";
					} elseif ( $ofile->isDeleted( File::DELETED_FILE ) ) {
						$spath = $be->getContainerStoragePath( "{$repo->getName()}-deleted" ) .
							'/' . $repo->getDeletedHashPath( $sha1 ) .
							$sha1 . '.' . $ofile->getExtension();
					} else {
						$spath = $ofile->getPath();
					}

					if ( $newLayout === 'sha1' ) {
						$dpath = "{$origBase}/{$sha1[0]}/{$sha1[1]}/{$sha1[2]}/{$sha1}";
					} else {
						$dpath = $ofile->getPath();
					}

					$status = $be->prepare( [
						'dir' => dirname( $dpath ), 'bypassReadOnly' => 1 ] );
					if ( !$status->isOK() ) {
						$this->error( print_r( $status->getErrors(), true ) );
					}
					$batch[] = [ 'op' => 'copy', 'overwrite' => true,
						'src' => $spath, 'dst' => $dpath, 'img' => $ofile->getArchiveName() ];
				}

				if ( count( $batch ) >= $batchSize ) {
					$this->runBatch( $batch, $be );
					$batch = [];
				}
			}
		} while ( $res->numRows() );

		if ( count( $batch ) ) {
			$this->runBatch( $batch, $be );
		}

		// Do deleted versions...
		$conds = [];
		if ( $since ) {
			$conds[] = 'fa_deleted_timestamp >= ' . $dbw->addQuotes( $dbw->timestamp( $since ) );
		}

		$batch = [];
		$lastId = 0;
		do {
			$res = $dbw->select( 'filearchive', [ 'fa_storage_key', 'fa_id', 'fa_name' ],
				array_merge( [ 'fa_id > ' . $dbw->addQuotes( $lastId ) ], $conds ),
				__METHOD__,
				[ 'LIMIT' => $batchSize, 'ORDER BY' => 'fa_id' ]
			);

			foreach ( $res as $row ) {
				$lastId = $row->fa_id;
				$sha1Key = $row->fa_storage_key;
				if ( !strlen( $sha1Key ) ) {
					$this->error( "Image SHA-1 not set for file #{$row->fa_id} (deleted)." );
					continue;
				}
				$sha1 = substr( $sha1Key, 0, strpos( $sha1Key, '.' ) );

				if ( $oldLayout === 'sha1' ) {
					$spath = "{$origBase}/{$sha1[0]}/{$sha1[1]}/{$sha1[2]}/{$sha1}";
				} else {
					$spath = $be->getContainerStoragePath( "{$repo->getName()}-deleted" ) .
						'/' . $repo->getDeletedHashPath( $sha1Key ) . $sha1Key;
				}

				if ( $newLayout === 'sha1' ) {
					$dpath = "{$origBase}/{$sha1[0]}/{$sha1[1]}/{$sha1[2]}/{$sha1}";
				} else {
					$dpath = $be->getContainerStoragePath( "{$repo->getName()}-deleted" ) .
						'/' . $repo->getDeletedHashPath( $sha1Key ) . $sha1Key;
				}

				$status = $be->prepare( [
					'dir' => dirname( $dpath ), 'bypassReadOnly' => 1 ] );
				if ( !$status->isOK() ) {
					$this->error( print_r( $status->getErrors(), true ) );
				}

				$batch[] = [ 'op' => 'copy', 'src' => $spath, 'dst' => $dpath,
					'overwriteSame' => true, 'img' => "(ID {$row->fa_id}) {$row->fa_name}" ];

				if ( count( $batch ) >= $batchSize ) {
					$this->runBatch( $batch, $be );
					$batch = [];
				}
			}
		} while ( $res->numRows() );

		if ( count( $batch ) ) {
			$this->runBatch( $batch, $be );
		}

		$this->output( "Done (started $startTime)\n" );
	}

	protected function getRepo() {
		return RepoGroup::singleton()->getLocalRepo();
	}

	protected function runBatch( array $ops, FileBackend $be ) {
		$this->output( "Migrating file batch:\n" );
		foreach ( $ops as $op ) {
			$this->output( "\"{$op['img']}\" (dest: {$op['dst']})\n" );
		}

		$status = $be->doOperations( $ops, [ 'bypassReadOnly' => 1 ] );
		if ( !$status->isOK() ) {
			$this->output( print_r( $status->getErrors(), true ) );
		}

		$this->output( "Batch done\n\n" );
	}
}

$maintClass = MigrateFileRepoLayout::class;
require_once RUN_MAINTENANCE_IF_MAIN;
