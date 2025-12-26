<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\FileRepo\File\FileSelectQueryBuilder;
use MediaWiki\MainConfigNames;
use MediaWiki\Maintenance\Maintenance;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

class FindMissingFiles extends Maintenance {
	public function __construct() {
		parent::__construct();

		$this->addDescription( 'Find registered files with no corresponding file.' );
		$this->addOption( 'start', 'Start after this file name', false, true );
		$this->addOption( 'mtimeafter', 'Only include files changed since this time', false, true );
		$this->addOption( 'mtimebefore', 'Only includes files changed before this time', false, true );
		$this->setBatchSize( 300 );
	}

	public function execute() {
		$lastName = $this->getOption( 'start', '' );

		$repo = $this->getServiceContainer()->getRepoGroup()->getLocalRepo();
		$dbr = $repo->getReplicaDB();
		$be = $repo->getBackend();
		$batchSize = $this->getBatchSize();

		$mtime1 = $dbr->timestampOrNull( $this->getOption( 'mtimeafter', null ) );
		$mtime2 = $dbr->timestampOrNull( $this->getOption( 'mtimebefore', null ) );

		$migrationStage = $this->getServiceContainer()->getMainConfig()->get(
			MainConfigNames::FileSchemaMigrationStage
		);
		$nameField = ( $migrationStage & SCHEMA_COMPAT_READ_NEW ) ? 'file_name' : 'img_name';

		$queryBuilder = FileSelectQueryBuilder::newForFile( $dbr )
			->groupBy( $nameField )
			->orderBy( $nameField )
			->limit( $batchSize );

		if ( $mtime1 || $mtime2 ) {
			$queryBuilder->join( 'page', null, 'page_title = img_name' );
			$queryBuilder->andWhere( [ 'page_namespace' => NS_FILE ] );

			$queryBuilder->join( 'logging', null, 'log_page = page_id' );
			$queryBuilder->andWhere( [ 'log_type' => [ 'upload', 'move', 'delete' ] ] );
			if ( $mtime1 ) {
				$queryBuilder->andWhere( $dbr->expr( 'log_timestamp', '>', $mtime1 ) );
			}
			if ( $mtime2 ) {
				$queryBuilder->andWhere( $dbr->expr( 'log_timestamp', '<', $mtime2 ) );
			}
		}

		do {
			$res = ( clone $queryBuilder )
				->where( $dbr->expr( $nameField, '>', $lastName ) )
				->caller( __METHOD__ )->fetchResultSet();

			// Check if any of these files are missing...
			$pathsByName = [];
			foreach ( $res as $row ) {
				$file = $repo->newFile( $row->img_name );
				$pathsByName[$row->img_name] = $file->getPath();
				$lastName = $row->img_name;
			}
			$be->preloadFileStat( [ 'srcs' => $pathsByName ] );
			foreach ( $pathsByName as $path ) {
				if ( $be->fileExists( [ 'src' => $path ] ) === false ) {
					$this->output( "$path\n" );
				}
			}

			// Find all missing old versions of any of the files in this batch...
			if ( count( $pathsByName ) ) {
				$ores = FileSelectQueryBuilder::newForOldFile( $dbr )
					->where( [ 'oi_name' => array_map( 'strval', array_keys( $pathsByName ) ) ] )
					->caller( __METHOD__ )->fetchResultSet();

				$checkPaths = [];
				foreach ( $ores as $row ) {
					if ( $row->oi_archive_name === '' ) {
						// broken row
						continue;
					}
					$file = $repo->newFromArchiveName( $row->oi_name, $row->oi_archive_name );
					$checkPaths[] = $file->getPath();
				}

				foreach ( array_chunk( $checkPaths, $batchSize ) as $paths ) {
					$be->preloadFileStat( [ 'srcs' => $paths ] );
					foreach ( $paths as $path ) {
						if ( $be->fileExists( [ 'src' => $path ] ) === false ) {
							$this->output( "$path\n" );
						}
					}
				}
			}
		} while ( $res->numRows() >= $batchSize );
	}
}

// @codeCoverageIgnoreStart
$maintClass = FindMissingFiles::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
