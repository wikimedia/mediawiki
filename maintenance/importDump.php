<?php
/**
 * Import XML dump files into the current wiki.
 *
 * Copyright © 2005 Brion Vibber <brion@pobox.com>
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

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script that imports XML dump files into the current wiki.
 *
 * @ingroup Maintenance
 */
class BackupReader extends Maintenance {
	public $reportingInterval = 100;
	public $pageCount = 0;
	public $revCount = 0;
	public $dryRun = false;
	public $uploads = false;
	public $imageBasePath = false;
	public $nsFilter = false;

	function __construct() {
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
		$this->addArg( 'file', 'Dump file to import [else use stdin]', false );
	}

	public function execute() {
		if ( wfReadOnly() ) {
			$this->error( "Wiki is in read-only mode; you'll need to disable it for import to work.", true );
		}

		$this->reportingInterval = intval( $this->getOption( 'report', 100 ) );
		if ( !$this->reportingInterval ) {
			$this->reportingInterval = 100; // avoid division by zero
		}

		$this->dryRun = $this->hasOption( 'dry-run' );
		$this->uploads = $this->hasOption( 'uploads' ); // experimental!
		if ( $this->hasOption( 'image-base-path' ) ) {
			$this->imageBasePath = $this->getOption( 'image-base-path' );
		}
		if ( $this->hasOption( 'namespaces' ) ) {
			$this->setNsfilter( explode( '|', $this->getOption( 'namespaces' ) ) );
		}

		if ( $this->hasArg() ) {
			$this->importFromFile( $this->getArg() );
		} else {
			$this->importFromStdin();
		}

		$this->output( "Done!\n" );
		$this->output( "You might want to run rebuildrecentchanges.php to regenerate RecentChanges\n" );
	}

	function setNsfilter( array $namespaces ) {
		if ( count( $namespaces ) == 0 ) {
			$this->nsFilter = false;

			return;
		}
		$this->nsFilter = array_unique( array_map( [ $this, 'getNsIndex' ], $namespaces ) );
	}

	private function getNsIndex( $namespace ) {
		global $wgContLang;
		$result = $wgContLang->getNsIndex( $namespace );
		if ( $result !== false ) {
			return $result;
		}
		$ns = intval( $namespace );
		if ( strval( $ns ) === $namespace && $wgContLang->getNsText( $ns ) !== false ) {
			return $ns;
		}
		$this->error( "Unknown namespace text / index specified: $namespace", true );
	}

	/**
	 * @param Title|Revision $obj
	 * @return bool
	 */
	private function skippedNamespace( $obj ) {
		$title = null;
		if ( $obj instanceof Title ) {
			$title = $obj;
		} elseif ( $obj instanceof Revision ) {
			$title = $obj->getTitle();
		} elseif ( $obj instanceof WikiRevision ) {
			$title = $obj->title;
		} else {
			throw new MWException( "Cannot get namespace of object in " . __METHOD__ );
		}

		if ( is_null( $title ) ) {
			// Probably a log entry
			return false;
		}

		$ns = $title->getNamespace();

		return is_array( $this->nsFilter ) && !in_array( $ns, $this->nsFilter );
	}

	function reportPage( $page ) {
		$this->pageCount++;
	}

	/**
	 * @param Revision $rev
	 */
	function handleRevision( $rev ) {
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
			call_user_func( $this->importCallback, $rev );
		}
	}

	/**
	 * @param Revision $revision
	 * @return bool
	 */
	function handleUpload( $revision ) {
		if ( $this->uploads ) {
			if ( $this->skippedNamespace( $revision ) ) {
				return false;
			}
			$this->uploadCount++;
			// $this->report();
			$this->progress( "upload: " . $revision->getFilename() );

			if ( !$this->dryRun ) {
				// bluuuh hack
				// call_user_func( $this->uploadCallback, $revision );
				$dbw = $this->getDB( DB_MASTER );

				return $dbw->deadlockLoop( [ $revision, 'importUpload' ] );
			}
		}

		return false;
	}

	function handleLogItem( $rev ) {
		if ( $this->skippedNamespace( $rev ) ) {
			return;
		}
		$this->revCount++;
		$this->report();

		if ( !$this->dryRun ) {
			call_user_func( $this->logItemCallback, $rev );
		}
	}

	function report( $final = false ) {
		if ( $final xor ( $this->pageCount % $this->reportingInterval == 0 ) ) {
			$this->showReport();
		}
	}

	function showReport() {
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
		wfWaitForSlaves();
	}

	function progress( $string ) {
		fwrite( $this->stderr, $string . "\n" );
	}

	function importFromFile( $filename ) {
		if ( preg_match( '/\.gz$/', $filename ) ) {
			$filename = 'compress.zlib://' . $filename;
		} elseif ( preg_match( '/\.bz2$/', $filename ) ) {
			$filename = 'compress.bzip2://' . $filename;
		} elseif ( preg_match( '/\.7z$/', $filename ) ) {
			$filename = 'mediawiki.compress.7z://' . $filename;
		}

		$file = fopen( $filename, 'rt' );

		return $this->importFromHandle( $file );
	}

	function importFromStdin() {
		$file = fopen( 'php://stdin', 'rt' );
		if ( self::posix_isatty( $file ) ) {
			$this->maybeHelp( true );
		}

		return $this->importFromHandle( $file );
	}

	function importFromHandle( $handle ) {
		$this->startTime = microtime( true );

		$source = new ImportStreamSource( $handle );
		$importer = new WikiImporter( $source, $this->getConfig() );

		if ( $this->hasOption( 'debug' ) ) {
			$importer->setDebug( true );
		}
		if ( $this->hasOption( 'no-updates' ) ) {
			$importer->setNoUpdates( true );
		}
		if ( $this->hasOption( 'rootpage' ) ) {
			$statusRootPage = $importer->setTargetRootPage( $this->getOption( 'rootpage' ) );
			if ( !$statusRootPage->isGood() ) {
				// Die here so that it doesn't print "Done!"
				$this->error( $statusRootPage->getMessage()->text(), 1 );
				return false;
			}
		}
		$importer->setPageCallback( [ $this, 'reportPage' ] );
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

$maintClass = 'BackupReader';
require_once RUN_MAINTENANCE_IF_MAIN;
