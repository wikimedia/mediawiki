<?php
/**
 * Remove old or broken uploads from temporary uploaded file storage,
 * clean up associated database records
 *
 * Copyright © 2011, Wikimedia Foundation
 *
 * @license GPL-2.0-or-later
 * @file
 * @author Ian Baker <ibaker@wikimedia.org>
 * @ingroup Maintenance
 */

use MediaWiki\FileRepo\FileRepo;
use MediaWiki\MainConfigNames;
use MediaWiki\Maintenance\Maintenance;
use MediaWiki\Upload\Exception\UploadStashException;
use MediaWiki\Upload\UploadStash;
use Wikimedia\Timestamp\TimestampFormat as TS;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

/**
 * Maintenance script to remove old or broken uploads from temporary uploaded
 * file storage and clean up associated database records.
 *
 * @ingroup Maintenance
 */
class CleanupUploadStash extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Clean up abandoned files in temporary uploaded file stash' );
		$this->setBatchSize( 50 );
	}

	public function execute() {
		$repo = $this->getServiceContainer()->getRepoGroup()->getLocalRepo();
		$tempRepo = $repo->getTempRepo();

		$dbr = $repo->getReplicaDB();

		// how far back should this look for files to delete?
		$cutoff = time() - (int)$this->getConfig()->get( MainConfigNames::UploadStashMaxAge );

		$this->output( "Getting list of files to clean up...\n" );
		$keys = $dbr->newSelectQueryBuilder()
			->select( 'us_key' )
			->from( 'uploadstash' )
			->where( $dbr->expr( 'us_timestamp', '<', $dbr->timestamp( $cutoff ) ) )
			->caller( __METHOD__ )
			->fetchFieldValues();

		// Delete all registered stash files...
		if ( !$keys ) {
			$this->output( "No stashed files to cleanup according to the DB.\n" );
		} else {
			$this->output( 'Removing ' . count( $keys ) . " file(s)...\n" );
			// this could be done some other, more direct/efficient way, but using
			// UploadStash's own methods means it's less likely to fall accidentally
			// out-of-date someday
			$stash = new UploadStash( $repo );

			$i = 0;
			foreach ( $keys as $key ) {
				$i++;
				try {
					$stash->getFile( $key, true );
					$stash->removeFileNoAuth( $key );
				} catch ( UploadStashException $ex ) {
					$type = get_class( $ex );
					$this->output( "Failed removing stashed upload with key: $key ($type)\n" );
				}
				if ( $i % 100 == 0 ) {
					$this->waitForReplication();
					$this->output( "$i\n" );
				}
			}
			$this->output( "$i done\n" );
		}

		// Delete all the corresponding thumbnails...
		$dir = $tempRepo->getZonePath( 'thumb' );
		$iterator = $tempRepo->getBackend()->getFileList( [ 'dir' => $dir, 'adviseStat' => 1 ] );
		if ( $iterator === null ) {
			$this->fatalError( "Could not get file listing." );
		}
		$this->output( "Deleting old thumbnails...\n" );
		$i = 0;
		$batch = [];
		foreach ( $iterator as $file ) {
			if ( wfTimestamp( TS::UNIX, $tempRepo->getFileTimestamp( "$dir/$file" ) ) < $cutoff ) {
				$batch[] = [ 'op' => 'delete', 'src' => "$dir/$file" ];
				if ( count( $batch ) >= $this->getBatchSize() ) {
					$this->doOperations( $tempRepo, $batch );
					$i += count( $batch );
					$batch = [];
					$this->output( "$i\n" );
				}
			}
		}
		if ( count( $batch ) ) {
			$this->doOperations( $tempRepo, $batch );
			$i += count( $batch );
		}
		$this->output( "$i done\n" );

		// Apparently lots of stash files are not registered in the DB...
		$dir = $tempRepo->getZonePath( 'public' );
		$iterator = $tempRepo->getBackend()->getFileList( [ 'dir' => $dir, 'adviseStat' => 1 ] );
		if ( $iterator === null ) {
			$this->fatalError( "Could not get file listing." );
		}
		$this->output( "Deleting orphaned temp files...\n" );
		if ( !str_contains( $dir, '/local-temp' ) ) {
			$this->output( "Temp repo might be misconfigured. It points to directory: '$dir' \n" );
		}

		$i = 0;
		$batch = [];
		foreach ( $iterator as $file ) {
			if ( wfTimestamp( TS::UNIX, $tempRepo->getFileTimestamp( "$dir/$file" ) ) < $cutoff ) {
				$batch[] = [ 'op' => 'delete', 'src' => "$dir/$file" ];
				if ( count( $batch ) >= $this->getBatchSize() ) {
					$this->doOperations( $tempRepo, $batch );
					$i += count( $batch );
					$batch = [];
					$this->output( "$i\n" );
				}
			}
		}
		if ( count( $batch ) ) {
			$this->doOperations( $tempRepo, $batch );
			$i += count( $batch );
		}
		$this->output( "$i done\n" );
	}

	protected function doOperations( FileRepo $tempRepo, array $ops ) {
		$status = $tempRepo->getBackend()->doQuickOperations( $ops );
		if ( !$status->isOK() ) {
			$this->error( $status );
		}
	}
}

// @codeCoverageIgnoreStart
$maintClass = CleanupUploadStash::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
