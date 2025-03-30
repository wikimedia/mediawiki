<?php
/**
 * Import XML dump files into the current wiki.
 *
 * Copyright © 2005 Brooke Vibber <bvibber@wikimedia.org>
 * https://www.mediawiki.org/
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

use MediaWiki\Linker\LinkTarget;
use MediaWiki\Maintenance\Maintenance;
use MediaWiki\Permissions\UltimateAuthority;
use MediaWiki\User\User;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

/**
 * Maintenance script that imports XML dump files into the current wiki.
 *
 * @ingroup Maintenance
 */
class BackupReader extends Maintenance {
	/** @var int */
	public $reportingInterval = 100;
	/** @var int */
	public $pageCount = 0;
	/** @var int */
	public $revCount = 0;
	/** @var bool */
	public $dryRun = false;
	/** @var bool */
	public $uploads = false;
	/** @var int */
	protected $uploadCount = 0;
	/** @var string|false */
	public $imageBasePath = false;
	/** @var array|false */
	public $nsFilter = false;
	/** @var resource|false */
	public $stderr;
	/** @var callable|null */
	protected $importCallback;
	/** @var callable|null */
	protected $logItemCallback;
	/** @var callable|null */
	protected $uploadCallback;
	/** @var float */
	protected $startTime;

	public function __construct() {
		parent::__construct();
		$gz = in_array( 'compress.zlib', stream_get_wrappers() )
			? 'ok'
			: '(disabled; requires PHP zlib module)';
		$bz2 = in_array( 'compress.bzip2', stream_get_wrappers() )
			? 'ok'
			: '(disabled; requires PHP bzip2 module)';

		$this->addDescription(
			<<<TEXT
This script reads pages from an XML file as produced from Special:Export or
dumpBackup.php, and saves them into the current wiki.

Compressed XML files may be read directly:
  .gz $gz
  .bz2 $bz2
  .7z (if 7za executable is in PATH)

Note that for very large data sets, importDump.php may be slow; there are
alternate methods which can be much faster for full site restoration:
<https://www.mediawiki.org/wiki/Manual:Importing_XML_dumps>
TEXT
		);
		$this->stderr = fopen( "php://stderr", "wt" );
		$this->addOption( 'report',
			'Report position and speed after every n pages processed', false, true );
		$this->addOption( 'namespaces',
			'Import only the pages from namespaces belonging to the list of ' .
			'pipe-separated namespace names or namespace indexes', false, true );
		$this->addOption( 'rootpage', 'Pages will be imported as subpages of the specified page',
			false, true );
		$this->addOption( 'dry-run', 'Parse dump without actually importing pages' );
		$this->addOption( 'debug', 'Output extra verbose debug information' );
		$this->addOption( 'uploads', 'Process file upload data if included (experimental)' );
		$this->addOption(
			'no-updates',
			'Disable link table updates. Is faster but leaves the wiki in an inconsistent state'
		);
		$this->addOption( 'image-base-path', 'Import files from a specified path', false, true );
		$this->addOption( 'skip-to', 'Start from nth page by skipping first n-1 pages', false, true );
		$this->addOption( 'username-prefix',
			'Prefix for interwiki usernames; a trailing ">" will be added. Default: "imported>"',
			false, true );
		$this->addOption( 'no-local-users',
			'Treat all usernames as interwiki. ' .
			'The default is to assign edits to local users where they exist.',
			false, false
		);
		$this->addArg( 'file', 'Dump file to import [else use stdin]', false );
	}

	public function execute() {
		if ( $this->getServiceContainer()->getReadOnlyMode()->isReadOnly() ) {
			$this->fatalError( "Wiki is in read-only mode; you'll need to disable it for import to work." );
		}

		$this->reportingInterval = intval( $this->getOption( 'report', 100 ) );
		if ( !$this->reportingInterval ) {
			// avoid division by zero
			$this->reportingInterval = 100;
		}

		$this->dryRun = $this->hasOption( 'dry-run' );
		$this->uploads = $this->hasOption( 'uploads' );

		if ( $this->hasOption( 'image-base-path' ) ) {
			$this->imageBasePath = $this->getOption( 'image-base-path' );
		}
		if ( $this->hasOption( 'namespaces' ) ) {
			$this->setNsfilter( explode( '|', $this->getOption( 'namespaces' ) ) );
		}

		if ( $this->hasArg( 0 ) ) {
			$this->importFromFile( $this->getArg( 0 ) );
		} else {
			$this->importFromStdin();
		}

		$this->output( "Done!\n" );
		$this->output( "You might want to run rebuildrecentchanges.php to regenerate RecentChanges,\n" );
		$this->output( "and initSiteStats.php to update page and revision counts\n" );
	}

	private function setNsfilter( array $namespaces ) {
		if ( count( $namespaces ) == 0 ) {
			$this->nsFilter = false;

			return;
		}
		$this->nsFilter = array_unique( array_map( [ $this, 'getNsIndex' ], $namespaces ) );
	}

	private function getNsIndex( string $namespace ): int {
		$contLang = $this->getServiceContainer()->getContentLanguage();
		$result = $contLang->getNsIndex( $namespace );
		if ( $result !== false ) {
			return $result;
		}
		$ns = intval( $namespace );
		if ( strval( $ns ) === $namespace && $contLang->getNsText( $ns ) !== false ) {
			return $ns;
		}
		$this->fatalError( "Unknown namespace text / index specified: $namespace" );
	}

	/**
	 * @param LinkTarget|null $title
	 * @return bool
	 */
	private function skippedNamespace( $title ) {
		if ( $title === null ) {
			// Probably a log entry
			return false;
		}

		$ns = $title->getNamespace();

		return is_array( $this->nsFilter ) && !in_array( $ns, $this->nsFilter );
	}

	public function reportPage( $page ) {
		$this->pageCount++;
	}

	public function handleRevision( WikiRevision $rev ) {
		$title = $rev->getTitle();
		if ( !$title ) {
			$this->progress( "Got bogus revision with null title!" );

			return;
		}

		if ( $this->skippedNamespace( $title ) ) {
			return;
		}

		$this->revCount++;
		$this->report();

		if ( !$this->dryRun ) {
			( $this->importCallback )( $rev );
		}
	}

	/**
	 * @param WikiRevision $revision
	 * @return bool
	 */
	public function handleUpload( WikiRevision $revision ) {
		if ( $this->uploads ) {
			if ( $this->skippedNamespace( $revision->getTitle() ) ) {
				return false;
			}
			$this->uploadCount++;
			// $this->report();
			$this->progress( "upload: " . $revision->getFilename() );

			if ( !$this->dryRun ) {
				// bluuuh hack
				// ( $this->uploadCallback )( $revision );
				$importer = $this->getServiceContainer()->getWikiRevisionUploadImporter();
				$statusValue = $importer->import( $revision );

				return $statusValue->isGood();
			}
		}

		return false;
	}

	public function handleLogItem( WikiRevision $rev ) {
		if ( $this->skippedNamespace( $rev->getTitle() ) ) {
			return;
		}
		$this->revCount++;
		$this->report();

		if ( !$this->dryRun ) {
			( $this->logItemCallback )( $rev );
		}
	}

	private function report( bool $final = false ) {
		if ( $final xor ( $this->pageCount % $this->reportingInterval == 0 ) ) {
			$this->showReport();
		}
	}

	private function showReport() {
		if ( !$this->mQuiet ) {
			$delta = microtime( true ) - $this->startTime;
			if ( $delta ) {
				$rate = sprintf( "%.2f", $this->pageCount / $delta );
				$revrate = sprintf( "%.2f", $this->revCount / $delta );
			} else {
				$rate = '-';
				$revrate = '-';
			}
			# Logs dumps don't have page tallies
			if ( $this->pageCount ) {
				$this->progress( "$this->pageCount ($rate pages/sec $revrate revs/sec)" );
			} else {
				$this->progress( "$this->revCount ($revrate revs/sec)" );
			}
		}
		$this->waitForReplication();
	}

	private function progress( string $string ) {
		fwrite( $this->stderr, $string . "\n" );
	}

	private function importFromFile( string $filename ): bool {
		if ( preg_match( '/\.gz$/', $filename ) ) {
			$filename = 'compress.zlib://' . $filename;
		} elseif ( preg_match( '/\.bz2$/', $filename ) ) {
			$filename = 'compress.bzip2://' . $filename;
		} elseif ( preg_match( '/\.7z$/', $filename ) ) {
			$filename = 'mediawiki.compress.7z://' . $filename;
		}

		$file = fopen( $filename, 'rt' );
		if ( $file === false ) {
			$this->fatalError( error_get_last()['message'] ?? 'Could not open file' );
		}

		return $this->importFromHandle( $file );
	}

	private function importFromStdin(): bool {
		$file = fopen( 'php://stdin', 'rt' );
		if ( self::posix_isatty( $file ) ) {
			$this->maybeHelp( true );
		}

		return $this->importFromHandle( $file );
	}

	/**
	 * @param resource $handle
	 */
	private function importFromHandle( $handle ): bool {
		$this->startTime = microtime( true );

		$user = User::newSystemUser( User::MAINTENANCE_SCRIPT_USER, [ 'steal' => true ] );

		$source = new ImportStreamSource( $handle );
		$importer = $this->getServiceContainer()
			->getWikiImporterFactory()
			->getWikiImporter( $source, new UltimateAuthority( $user ) );

		// Updating statistics require a lot of time so disable it
		$importer->disableStatisticsUpdate();

		if ( $this->hasOption( 'debug' ) ) {
			$importer->setDebug( true );
		}
		if ( $this->hasOption( 'no-updates' ) ) {
			$importer->setNoUpdates( true );
		}
		$importer->setUsernamePrefix(
			$this->getOption( 'username-prefix', 'imported' ),
			!$this->hasOption( 'no-local-users' )
		);
		if ( $this->hasOption( 'rootpage' ) ) {
			$statusRootPage = $importer->setTargetRootPage( $this->getOption( 'rootpage' ) );
			if ( !$statusRootPage->isGood() ) {
				// Die here so that it doesn't print "Done!"
				$this->fatalError( $statusRootPage );
			}
		}
		if ( $this->hasOption( 'skip-to' ) ) {
			$nthPage = (int)$this->getOption( 'skip-to' );
			$importer->setPageOffset( $nthPage );
			$this->pageCount = $nthPage - 1;
		}
		$importer->setPageCallback( [ $this, 'reportPage' ] );
		$importer->setNoticeCallback( static function ( $msg, $params ) {
			echo wfMessage( $msg, $params )->text() . "\n";
		} );
		$this->importCallback = $importer->setRevisionCallback(
			[ $this, 'handleRevision' ] );
		$this->uploadCallback = $importer->setUploadCallback(
			[ $this, 'handleUpload' ] );
		$this->logItemCallback = $importer->setLogItemCallback(
			[ $this, 'handleLogItem' ] );
		if ( $this->uploads ) {
			$importer->setImportUploads( true );
		}
		if ( $this->imageBasePath ) {
			$importer->setImageBasePath( $this->imageBasePath );
		}

		if ( $this->dryRun ) {
			$importer->setPageOutCallback( null );
		}

		return $importer->doImport();
	}
}

// @codeCoverageIgnoreStart
$maintClass = BackupReader::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
