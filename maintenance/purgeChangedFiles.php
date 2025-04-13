<?php
/**
 * Scan the logging table and purge affected files within a timeframe.
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

use MediaWiki\FileRepo\File\LocalFile;
use MediaWiki\FileRepo\LocalRepo;
use MediaWiki\Maintenance\Maintenance;
use MediaWiki\Title\Title;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

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
	private static $typeMappings = [
		'created' => [
			'upload' => [ 'upload' ],
			'import' => [ 'upload', 'interwiki' ],
		],
		'deleted' => [
			'delete' => [ 'delete', 'revision' ],
			'suppress' => [ 'delete', 'revision' ],
		],
		'modified' => [
			'upload' => [ 'overwrite', 'revert' ],
			'move' => [ 'move', 'move_redir' ],
		],
	];

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
		$this->addDescription( 'Scan the logging table and purge files and thumbnails.' );
		$this->addOption( 'starttime', 'Starting timestamp', true, true );
		$this->addOption( 'endtime', 'Ending timestamp', true, true );
		$this->addOption( 'type', 'Comma-separated list of types of changes to send purges for (' .
			implode( ',', array_keys( self::$typeMappings ) ) . ',all)', false, true );
		$this->addOption( 'htcp-dest', 'HTCP announcement destination (IP:port)', false, true );
		$this->addOption( 'dry-run', 'Do not send purge requests' );
		$this->addOption( 'sleep-per-batch', 'Milliseconds to sleep between batches', false, true );
		$this->addOption( 'verbose', 'Show more output', false, false, 'v' );
		$this->setBatchSize( 100 );
	}

	public function execute() {
		global $wgHTCPRouting;

		if ( $this->hasOption( 'htcp-dest' ) ) {
			$parts = explode( ':', $this->getOption( 'htcp-dest' ), 2 );
			if ( count( $parts ) < 2 ) {
				// Add default htcp port
				$parts[] = '4827';
			}

			// Route all HTCP messages to provided host:port
			$wgHTCPRouting = [
				'' => [ 'host' => $parts[0], 'port' => $parts[1] ],
			];
			$this->verbose( "HTCP broadcasts to {$parts[0]}:{$parts[1]}\n" );
		}

		// Find out which actions we should be concerned with
		$typeOpt = $this->getOption( 'type', 'all' );
		if ( $typeOpt === 'all' ) {
			// Convert 'all' to all registered types
			$typeOpt = implode( ',', array_keys( self::$typeMappings ) );
		}
		$typeList = explode( ',', $typeOpt );
		foreach ( $typeList as $type ) {
			if ( !isset( self::$typeMappings[$type] ) ) {
				$this->error( "\nERROR: Unknown type: {$type}\n" );
				$this->maybeHelp( true );
			}
		}

		// Validate the timestamps
		$dbr = $this->getReplicaDB();
		$this->startTimestamp = $dbr->timestamp( $this->getOption( 'starttime' ) );
		$this->endTimestamp = $dbr->timestamp( $this->getOption( 'endtime' ) );

		if ( $this->startTimestamp > $this->endTimestamp ) {
			$this->error( "\nERROR: starttime after endtime\n" );
			$this->maybeHelp( true );
		}

		// Turn on verbose when dry-run is enabled
		if ( $this->hasOption( 'dry-run' ) ) {
			$this->setOption( 'verbose', 1 );
		}

		$this->verbose( 'Purging files that were: ' . implode( ', ', $typeList ) . "\n" );
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
		$repo = $this->getServiceContainer()->getRepoGroup()->getLocalRepo();
		$dbr = $this->getReplicaDB();

		foreach ( self::$typeMappings[$type] as $logType => $logActions ) {
			$this->verbose( "Scanning for {$logType}/" . implode( ',', $logActions ) . "\n" );

			$res = $dbr->newSelectQueryBuilder()
				->select( [ 'log_title', 'log_timestamp', 'log_params' ] )
				->from( 'logging' )
				->where( [
					'log_namespace' => NS_FILE,
					'log_type' => $logType,
					'log_action' => $logActions,
					$dbr->expr( 'log_timestamp', '>=', $this->startTimestamp ),
					$dbr->expr( 'log_timestamp', '<=', $this->endTimestamp ),
				] )
				->caller( __METHOD__ )->fetchResultSet();

			$bSize = 0;
			foreach ( $res as $row ) {
				$file = $repo->newFile( Title::makeTitle( NS_FILE, $row->log_title ) );

				if ( $this->hasOption( 'dry-run' ) ) {
					$this->verbose( "{$type}[{$row->log_timestamp}]: {$row->log_title}\n" );
					continue;
				}

				// Purge current version and its thumbnails
				$file->purgeCache();
				// Purge the old versions and their thumbnails
				foreach ( $file->getHistory() as $oldFile ) {
					$oldFile->purgeCache();
				}

				if ( $logType === 'delete' ) {
					// If there is an orphaned storage file... delete it
					if ( !$file->exists() && $repo->fileExists( $file->getPath() ) ) {
						$dpath = $this->getDeletedPath( $repo, $file );
						if ( $repo->fileExists( $dpath ) ) {
							// Check to avoid data loss
							$repo->getBackend()->delete( [ 'src' => $file->getPath() ] );
							$this->verbose( "Deleted orphan file: {$file->getPath()}.\n" );
						} else {
							$this->error( "File was not deleted: {$file->getPath()}.\n" );
						}
					}

					// Purge items from fileachive table (rows are likely here)
					$this->purgeFromArchiveTable( $repo, $file );
				} elseif ( $logType === 'move' ) {
					// Purge the target file as well

					$params = unserialize( $row->log_params );
					if ( isset( $params['4::target'] ) ) {
						$target = $params['4::target'];
						$targetFile = $repo->newFile( Title::makeTitle( NS_FILE, $target ) );
						$targetFile->purgeCache();
						$this->verbose( "Purged file {$target}; move target @{$row->log_timestamp}.\n" );
					}
				}

				$this->verbose( "Purged file {$row->log_title}; {$type} @{$row->log_timestamp}.\n" );

				if ( $this->hasOption( 'sleep-per-batch' ) && ++$bSize > $this->getBatchSize() ) {
					$bSize = 0;
					// sleep-per-batch is milliseconds, usleep wants micro seconds.
					usleep( 1000 * (int)$this->getOption( 'sleep-per-batch' ) );
				}
			}
		}
	}

	protected function purgeFromArchiveTable( LocalRepo $repo, LocalFile $file ) {
		$dbr = $repo->getReplicaDB();
		$res = $dbr->newSelectQueryBuilder()
			->select( [ 'fa_archive_name' ] )
			->from( 'filearchive' )
			->where( [ 'fa_name' => $file->getName() ] )
			->caller( __METHOD__ )->fetchResultSet();

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
					// Check to avoid data loss
					$repo->getBackend()->delete( [ 'src' => $ofile->getPath() ] );
					$this->output( "Deleted orphan file: {$ofile->getPath()}.\n" );
				} else {
					$this->error( "File was not deleted: {$ofile->getPath()}.\n" );
				}
			}
			$file->purgeOldThumbnails( $row->fa_archive_name );
		}
	}

	protected function getDeletedPath( LocalRepo $repo, LocalFile $file ): string {
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

// @codeCoverageIgnoreStart
$maintClass = PurgeChangedFiles::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
