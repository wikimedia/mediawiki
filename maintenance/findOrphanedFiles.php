<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\FileRepo\File\FileSelectQueryBuilder;
use MediaWiki\FileRepo\LocalRepo;
use MediaWiki\Maintenance\Maintenance;
use MediaWiki\Title\Title;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

class FindOrphanedFiles extends Maintenance {

	public function __construct() {
		parent::__construct();

		$this->addDescription( "Find unregistered files in the 'public' repo zone." );
		$this->addOption( 'subdir',
			'Only scan files in this subdirectory (e.g. "a/a0")', false, true );
		$this->addOption( 'verbose', "Mention file paths checked" );
		$this->setBatchSize( 500 );
	}

	public function execute() {
		$subdir = $this->getOption( 'subdir', '' );
		$verbose = $this->hasOption( 'verbose' );

		$repo = $this->getServiceContainer()->getRepoGroup()->getLocalRepo();
		if ( $repo->hasSha1Storage() ) {
			$this->fatalError( "Local repo uses SHA-1 file storage names; aborting." );
		}

		$directory = $repo->getZonePath( 'public' );
		if ( $subdir != '' ) {
			$directory .= "/$subdir/";
		}

		if ( $verbose ) {
			$this->output( "Scanning files under $directory:\n" );
		}

		$list = $repo->getBackend()->getFileList( [ 'dir' => $directory ] );
		if ( $list === null ) {
			$this->fatalError( "Could not get file listing." );
		}

		$pathBatch = [];
		foreach ( $list as $path ) {
			if ( preg_match( '#^(thumb|deleted)/#', $path ) ) {
				continue; // handle ugly nested containers on stock installs
			}

			$pathBatch[] = $path;
			if ( count( $pathBatch ) >= $this->getBatchSize() ) {
				$this->checkFiles( $repo, $pathBatch, $verbose );
				$pathBatch = [];
			}
		}
		$this->checkFiles( $repo, $pathBatch, $verbose );
	}

	protected function checkFiles( LocalRepo $repo, array $paths, bool $verbose ) {
		if ( !count( $paths ) ) {
			return;
		}

		$dbr = $repo->getReplicaDB();

		$curNames = [];
		$oldNames = [];
		$imgIN = [];
		$oiWheres = [];
		foreach ( $paths as $path ) {
			$name = basename( $path );
			if ( preg_match( '#^archive/#', $path ) ) {
				if ( $verbose ) {
					$this->output( "Checking old file $name\n" );
				}

				$oldNames[] = $name;
				[ , $base ] = explode( '!', $name, 2 ); // <TS::MW>!<img_name>
				$oiWheres[]  = $dbr->expr( 'oi_name', '=', $base )->and( 'oi_archive_name', '=', $name );
			} else {
				if ( $verbose ) {
					$this->output( "Checking current file $name\n" );
				}

				$curNames[] = $name;
				$imgIN[] = $name;
			}
		}

		$res1 = FileSelectQueryBuilder::newForFile( $dbr )
			->where( $imgIN ? [ 'img_name' => $imgIN ] : '1=0' )
			->caller( __METHOD__ )
			->fetchResultSet();
		$res2 = FileSelectQueryBuilder::newForOldFile( $dbr )
			->where( $oiWheres ? $dbr->orExpr( $oiWheres ) : '1=0' )
			->caller( __METHOD__ )
			->fetchResultSet();

		$curNamesFound = [];
		$oldNamesFound = [];

		foreach ( $res1 as $row ) {
			$curNamesFound[] = $row->img_name;
		}
		foreach ( $res2 as $row ) {
			$oldNamesFound[] = $row->oi_name;
		}

		foreach ( array_diff( $curNames, $curNamesFound ) as $name ) {
			$file = $repo->newFile( $name );
			// Print name and public URL to ease recovery
			if ( $file ) {
				$this->output( $name . "\n" . $file->getCanonicalUrl() . "\n\n" );
			} else {
				$this->error( "Cannot get URL for bad file title '$name'" );
			}
		}

		foreach ( array_diff( $oldNames, $oldNamesFound ) as $name ) {
			[ , $base ] = explode( '!', $name, 2 ); // <TS::MW>!<img_name>
			$file = $repo->newFromArchiveName( Title::makeTitle( NS_FILE, $base ), $name );
			// Print name and public URL to ease recovery
			$this->output( $name . "\n" . $file->getCanonicalUrl() . "\n\n" );
		}
	}
}

// @codeCoverageIgnoreStart
$maintClass = FindOrphanedFiles::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
