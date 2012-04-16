<?php
/**
 * Scans the deletion log and purges affected files within a timeframe.
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
 * @ingroup Maintenance
 */

require_once( dirname( __FILE__ ) . '/Maintenance.php' );

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
			'log_type'      => $logType,
			'log_action'    => array( 'delete', 'revision' )
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

			// Purge current version and any versions in oldimage table
			$file->purgeCache();
			$file->purgeHistory();
			// Purge items from fileachive table (rows are likely here)
			$this->purgeFromArchiveTable( $file );

			$this->output( "Purged file {$row->log_title}; deleted on {$row->log_timestamp}.\n" );
		}
	}

	protected function purgeFromArchiveTable( LocalFile $file ) {
		$db = $file->getRepo()->getSlaveDB();
		$res = $db->select( 'filearchive', 
			array( 'fa_archive_name' ),
			array( 'fa_name' => $file->getName() ),
			__METHOD__
		);
		foreach ( $res as $row ) {
			$file->purgeOldThumbnails( $row->fa_archive_name );
		}
	}
}

$maintClass = "PurgeDeletedFiles";
require_once( RUN_MAINTENANCE_IF_MAIN );
