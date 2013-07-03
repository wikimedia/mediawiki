<?php
/**
 * Scan the deletion log and purges affected files within a timeframe.
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
 * Maintenance script that scans the deletion log and purges affected files
 * within a timeframe.
 *
 * @ingroup Maintenance
 */
class PurgeDeletedFiles extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Scan the logging table and purge files that where deleted.";
		$this->addOption( 'starttime', 'Starting timestamp', false, true );
		$this->addOption( 'endtime', 'Ending timestamp', false, true );
	}

	public function execute() {
		$this->output( "Purging cache and thumbnails for deleted files...\n" );
		$this->purgeFromLogType( 'delete' );
		$this->output( "...deleted files purged.\n\n" );

		$this->output( "Purging cache and thumbnails for suppressed files...\n" );
		$this->purgeFromLogType( 'suppress' );
		$this->output( "...suppressed files purged.\n" );
	}

	protected function purgeFromLogType( $logType ) {
		$repo = RepoGroup::singleton()->getLocalRepo();
		$db = $repo->getSlaveDB();

		$conds = array(
			'log_namespace' => NS_FILE,
			'log_type' => $logType,
			'log_action' => array( 'delete', 'revision' )
		);
		$start = $this->getOption( 'starttime' );
		if ( $start ) {
			$conds[] = 'log_timestamp >= ' . $db->addQuotes( $db->timestamp( $start ) );
		}
		$end = $this->getOption( 'endtime' );
		if ( $end ) {
			$conds[] = 'log_timestamp <= ' . $db->addQuotes( $db->timestamp( $end ) );
		}

		$res = $db->select( 'logging', array( 'log_title', 'log_timestamp' ), $conds, __METHOD__ );
		foreach ( $res as $row ) {
			$file = $repo->newFile( Title::makeTitle( NS_FILE, $row->log_title ) );
			// If there is an orphaned storage file still there...delete it
			if ( !$file->exists() && $repo->fileExists( $file->getPath() ) ) {
				$dpath = $this->getDeletedPath( $repo, $file );
				if ( $repo->fileExists( $dpath ) ) { // sanity check to avoid data loss
					$repo->getBackend()->delete( array( 'src' => $file->getPath() ) );
					$this->output( "Deleted orphan file: {$file->getPath()}.\n" );
				} else {
					$this->error( "File was not deleted: {$file->getPath()}.\n" );
				}
			}
			// Purge current version and any versions in oldimage table
			$file->purgeCache();
			$file->purgeHistory();
			// Purge items from fileachive table (rows are likely here)
			$this->purgeFromArchiveTable( $repo, $file );

			$this->output( "Purged file {$row->log_title}; deleted on {$row->log_timestamp}.\n" );
		}
	}

	protected function purgeFromArchiveTable( LocalRepo $repo, LocalFile $file ) {
		$db = $repo->getSlaveDB();
		$res = $db->select( 'filearchive',
			array( 'fa_archive_name' ),
			array( 'fa_name' => $file->getName() ),
			__METHOD__
		);
		foreach ( $res as $row ) {
			if ( $row->fa_archive_name === null ) {
				continue; // was not an old version (current version names checked already)
			}
			$ofile = $repo->newFromArchiveName( $file->getTitle(), $row->fa_archive_name );
			// If there is an orphaned storage file still there...delete it
			if ( !$file->exists() && $repo->fileExists( $ofile->getPath() ) ) {
				$dpath = $this->getDeletedPath( $repo, $ofile );
				if ( $repo->fileExists( $dpath ) ) { // sanity check to avoid data loss
					$repo->getBackend()->delete( array( 'src' => $ofile->getPath() ) );
					$this->output( "Deleted orphan file: {$ofile->getPath()}.\n" );
				} else {
					$this->error( "File was not deleted: {$ofile->getPath()}.\n" );
				}
			}
			$file->purgeOldThumbnails( $row->fa_archive_name );
		}
	}

	protected function getDeletedPath( LocalRepo $repo, LocalFile $file ) {
		$hash = $repo->getFileSha1( $file->getPath() );
		$key = "{$hash}.{$file->getExtension()}";
		return $repo->getDeletedHashPath( $key ) . $key;
	}
}

$maintClass = "PurgeDeletedFiles";
require_once RUN_MAINTENANCE_IF_MAIN;
