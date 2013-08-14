<?php
/**
 * Scan the logging table and purge affected files within a timeframe.
 *
 * @section LICENSE
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
class PurgeChangedFiles extends Maintenance {
	/**
	 * Mapping from type option to log type and actions.
	 * @var array
	 */
	private static $typeMappings = array(
		'created' => array(
			'upload' => array( 'upload' ),
			'import' => array( 'upload', 'interwiki' ),
		),
		'deleted' => array(
			'delete' => array( 'delete', 'revision' ),
			'suppress' => array( 'delete', 'revision' ),
		),
		'modified' => array(
			'upload' => array( 'overwrite', 'revert' ),
			'move' => array( 'move', 'move_redir' ),
		),
	);

	/**
	 * @var string
	 */
	private $startTimestamp;

	/**
	 * @var string
	 */
	private $endTimestamp;

	public function __construct() {
		parent::__construct();
		$this->mDescription = "Scan the logging table and purge files and thumbnails.";
		$this->addOption( 'starttime', 'Starting timestamp', true, true );
		$this->addOption( 'endtime', 'Ending timestamp', true, true );
		$this->addOption( 'type', 'Comma-separated list of types of changes to send purges for (' .
			implode( ',', array_keys( self::$typeMappings ) ) . ',all)', false, true );
		$this->addOption( 'htcp-dest', 'HTCP announcement destination (IP:port)', false, true );
		$this->addOption( 'dry-run', 'Do not send purge requests' );
		$this->addOption( 'verbose', 'Show more output', false, false, 'v' );
	}

	public function execute() {
		global $wgHTCPRouting;

		if ( $this->hasOption( 'htcp-dest' ) ) {
			$parts = explode( ':', $this->getOption( 'htcp-dest' ) );
			if ( count( $parts ) < 2 ) {
				// Add default htcp port
				$parts[] = '4827';
			}

			// Route all HTCP messages to provided host:port
			$wgHTCPRouting = array(
				'' => array( 'host' => $parts[0], 'port' => $parts[1] ),
			);
			$this->verbose( "HTCP broadcasts to {$parts[0]}:{$parts[1]}\n" );
		}

		// Find out which actions we should be concerned with
		$typeOpt = $this->getOption( 'type', 'all' );
		$validTypes = array_keys( self::$typeMappings );
		if ( $typeOpt === 'all' ) {
			// Convert 'all' to all registered types
			$typeOpt = implode( ',', $validTypes );
		}
		$typeList = explode( ',', $typeOpt );
		foreach ( $typeList as $type ) {
			if ( !in_array( $type, $validTypes ) ) {
				$this->error( "\nERROR: Unknown type: {$type}\n" );
				$this->maybeHelp( true );
			}
		}

		// Validate the timestamps
		$dbr = $this->getDB( DB_SLAVE );
		$this->startTimestamp = $dbr->timestamp( $this->getOption( 'starttime' ) );
		$this->endTimestamp = $dbr->timestamp( $this->getOption( 'endtime' ) );

		if ( $this->startTimestamp > $this->endTimestamp ) {
			$this->error( "\nERROR: starttime after endtime\n" );
			$this->maybeHelp( true );
		}

		// Turn on verbose when dry-run is enabled
		if ( $this->hasOption( 'dry-run' ) ) {
			$this->mOptions['verbose'] = 1;
		}

		$this->verbose( 'Purging files that were: ' . implode( ', ', $typeList ) . "\n");
		foreach ( $typeList as $type ) {
			$this->verbose( "Checking for {$type} files...\n" );
			$this->purgeFromLogType( $type );
			if ( !$this->hasOption( 'dry-run' ) ) {
				$this->verbose( "...{$type} files purged.\n\n" );
			}
		}
	}

	/**
	 * Purge cache and thumbnails for changes of the given type.
	 *
	 * @param string $type Type of change to find
	 */
	protected function purgeFromLogType( $type ) {
		$repo = RepoGroup::singleton()->getLocalRepo();
		$dbr = $this->getDB( DB_SLAVE );

		foreach ( self::$typeMappings[$type] as $logType => $logActions ) {
			$this->verbose( "Scanning for {$logType}/" . implode( ',', $logActions ) . "\n" );

			$res = $dbr->select(
				'logging',
				array( 'log_title', 'log_timestamp', 'log_params' ),
				array(
					'log_namespace' => NS_FILE,
					'log_type' => $logType,
					'log_action' => $logActions,
					'log_timestamp >= ' . $dbr->addQuotes( $this->startTimestamp ),
					'log_timestamp <= ' . $dbr->addQuotes( $this->endTimestamp ),
				),
				__METHOD__
			);

			foreach ( $res as $row ) {
				$file = $repo->newFile( Title::makeTitle( NS_FILE, $row->log_title ) );

				if ( $this->hasOption( 'dry-run' ) ) {
					$this->verbose( "{$type}[{$row->log_timestamp}]: {$row->log_title}\n" );
					continue;
				}

				// Purge current version and any versions in oldimage table
				$file->purgeCache();
				$file->purgeHistory();

				if ( $logType === 'delete' ) {
					// If there is an orphaned storage file... delete it
					if ( !$file->exists() && $repo->fileExists( $file->getPath() ) ) {
						$dpath = $this->getDeletedPath( $repo, $file );
						if ( $repo->fileExists( $dpath ) ) {
							// Sanity check to avoid data loss
							$repo->getBackend()->delete( array( 'src' => $file->getPath() ) );
							$this->verbose( "Deleted orphan file: {$file->getPath()}.\n" );

						} else {
							$this->error( "File was not deleted: {$file->getPath()}.\n" );
						}
					}

					// Purge items from fileachive table (rows are likely here)
					$this->purgeFromArchiveTable( $repo, $file );

				} else if ( $logType === 'move' ) {
					// Purge the target file as well

					$params = unserialize( $row->log_params );
					if ( isset( $params['4::target'] ) ) {
						$target = $params['4::target'];
						$targetFile = $repo->newFile( Title::makeTitle( NS_FILE, $target ) );
						$targetFile->purgeCache();
						$targetFile->purgeHistory();
						$this->verbose( "Purged file {$target}; move target @{$row->log_timestamp}.\n" );
					}
				}

				$this->verbose( "Purged file {$row->log_title}; {$type} @{$row->log_timestamp}.\n" );
			}
		}
	}

	protected function purgeFromArchiveTable( LocalRepo $repo, LocalFile $file ) {
		$dbr = $repo->getSlaveDB();
		$res = $dbr->select(
			'filearchive',
			array( 'fa_archive_name' ),
			array( 'fa_name' => $file->getName() ),
			__METHOD__
		);

		foreach ( $res as $row ) {
			if ( $row->fa_archive_name === null ) {
				// Was not an old version (current version names checked already)
				continue;
			}
			$ofile = $repo->newFromArchiveName( $file->getTitle(), $row->fa_archive_name );
			// If there is an orphaned storage file still there...delete it
			if ( !$file->exists() && $repo->fileExists( $ofile->getPath() ) ) {
				$dpath = $this->getDeletedPath( $repo, $ofile );
				if ( $repo->fileExists( $dpath ) ) {
					// Sanity check to avoid data loss
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

	/**
	 * Send an output message iff the 'verbose' option has been provided.
	 *
	 * @param string $msg Message to output
	 */
	protected function verbose( $msg ) {
		if ( $this->hasOption( 'verbose' ) ) {
			$this->output( $msg );
		}
	}

}

$maintClass = "PurgeChangedFiles";
require_once RUN_MAINTENANCE_IF_MAIN;
