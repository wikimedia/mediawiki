<?php
/**
 * Dump a the list of files uploaded, for feeding to tar or similar.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Maintenance
 */

use MediaWiki\Deferred\LinksUpdate\ImageLinksTable;
use MediaWiki\FileRepo\File\File;
use MediaWiki\MainConfigNames;
use MediaWiki\Maintenance\Maintenance;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

/**
 * Maintenance script to dump a the list of files uploaded,
 * for feeding to tar or similar.
 *
 * @ingroup Maintenance
 */
class DumpUploads extends Maintenance {
	/** @var string */
	private $mBasePath;

	private int $fileMigrationStage;
	private int $imageLinksMigrationStage;

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Generates list of uploaded files which can be fed to tar or similar.
By default, outputs relative paths against the parent directory of $wgUploadDirectory.' );
		$this->addOption( 'base', 'Set base relative path instead of wiki include root', false, true );
		$this->addOption( 'local', 'List all local files, used or not. No shared files included' );
		$this->addOption( 'used', 'Skip local images that are not used' );
		$this->addOption( 'shared', 'Include images used from shared repository' );

		$this->fileMigrationStage = $this->getConfig()->get( MainConfigNames::FileSchemaMigrationStage );
		$this->imageLinksMigrationStage = $this->getConfig()->get( MainConfigNames::ImageLinksSchemaMigrationStage );
	}

	public function execute() {
		global $IP;
		$this->mBasePath = $this->getOption( 'base', $IP );
		$shared = false;
		$sharedSupplement = false;

		if ( $this->hasOption( 'shared' ) ) {
			if ( $this->hasOption( 'used' ) ) {
				// Include shared-repo files in the used check
				$shared = true;
			} else {
				// Grab all local *plus* used shared
				$sharedSupplement = true;
			}
		}

		if ( $this->hasOption( 'local' ) ) {
			$this->fetchLocal( $shared );
		} elseif ( $this->hasOption( 'used' ) ) {
			$this->fetchUsed( $shared );
		} else {
			$this->fetchLocal( $shared );
		}

		if ( $sharedSupplement ) {
			$this->fetchUsed( true );
		}
	}

	/**
	 * Fetch a list of used images from a particular image source.
	 *
	 * @param bool $shared True to pass shared-dir settings to hash func
	 */
	private function fetchUsed( $shared ) {
		if ( $this->imageLinksMigrationStage & SCHEMA_COMPAT_READ_OLD ) {
			$ilToValues = $this->getReplicaDB( ImageLinksTable::VIRTUAL_DOMAIN )
				->newSelectQueryBuilder()
				->select( 'il_to' )
				->distinct()
				->from( 'imagelinks' )
				->caller( __METHOD__ )
				->fetchFieldValues();
		} else {
			$ilToValues = $this->getReplicaDB( ImageLinksTable::VIRTUAL_DOMAIN )
				->newSelectQueryBuilder()
				->select( 'lt_title' )
				->distinct()
				->from( 'imagelinks' )
				->join( 'linktarget', null, 'il_target_id = lt_id' )
				->caller( __METHOD__ )
				->fetchFieldValues();
		}

		$dbr = $this->getReplicaDB();

		if ( $this->fileMigrationStage & SCHEMA_COMPAT_READ_OLD ) {
			$result = $dbr->newSelectQueryBuilder()
				->select( [ 'name' => 'img_name' ] )
				->from( 'image' )
				->where( [ 'img_name' => $ilToValues ] )
				->caller( __METHOD__ )
				->fetchResultSet();
		} else {
			$result = $dbr->newSelectQueryBuilder()
				->select( [ 'name' => 'file_name' ] )
				->from( 'file' )
				->where( [
					'file_name' => $ilToValues,
					'file_deleted' => 0
				] )
				->caller( __METHOD__ )
				->fetchResultSet();
		}

		foreach ( $result as $row ) {
			$this->outputItem( $row->name, $shared );
		}
	}

	/**
	 * Fetch a list of all images from a particular image source.
	 *
	 * @param bool $shared True to pass shared-dir settings to hash func
	 */
	private function fetchLocal( $shared ) {
		$dbr = $this->getReplicaDB();

		if ( $this->fileMigrationStage & SCHEMA_COMPAT_READ_OLD ) {
			$result = $dbr->newSelectQueryBuilder()
				->select( 'img_name' )
				->from( 'image' )
				->caller( __METHOD__ )
				->fetchResultSet();
		} else {
			$result = $dbr->newSelectQueryBuilder()
				->select( 'file_name' )
				->from( 'file' )
				->where( [ 'file_deleted' => 0 ] )
				->caller( __METHOD__ )
				->fetchResultSet();
		}

		foreach ( $result as $row ) {
			$this->outputItem( $row->img_name ?? $row->file_name, $shared );
		}
	}

	private function outputItem( string $name, bool $shared ) {
		$file = $this->getServiceContainer()->getRepoGroup()->findFile( $name );
		if ( $file && $this->filterItem( $file, $shared ) ) {
			$filename = $file->getLocalRefPath();
			$rel = wfRelativePath( $filename, $this->mBasePath );
			$this->output( "$rel\n" );
		} else {
			wfDebug( __METHOD__ . ": base file? $name" );
		}
	}

	private function filterItem( File $file, bool $shared ): bool {
		return $shared || $file->isLocal();
	}
}

// @codeCoverageIgnoreStart
$maintClass = DumpUploads::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
