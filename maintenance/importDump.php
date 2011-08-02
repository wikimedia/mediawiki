<?php
/**
 * Copyright (C) 2005 Brion Vibber <brion@pobox.com>
 * http://www.mediawiki.org/
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

require_once( dirname( __FILE__ ) . '/Maintenance.php' );

/**
 * @ingroup Maintenance
 */
class BackupReader extends Maintenance {
	var $reportingInterval = 100;
	var $pageCount = 0;
	var $revCount  = 0;
	var $dryRun    = false;
	var $uploads   = false;
	var $imageBasePath = false;
	var $nsFilter  = false;

	function __construct() {
		parent::__construct();
		$gz = in_array('compress.zlib', stream_get_wrappers()) ? 'ok' : '(disabled; requires PHP zlib module)';
		$bz2 = in_array('compress.bzip2', stream_get_wrappers()) ? 'ok' : '(disabled; requires PHP bzip2 module)';

		$this->mDescription = <<<TEXT
This script reads pages from an XML file as produced from Special:Export or
dumpBackup.php, and saves them into the current wiki.

Compressed XML files may be read directly:
  .gz $gz
  .bz2 $bz2
  .7z (if 7za executable is in PATH)

Note that for very large data sets, importDump.php may be slow; there are
alternate methods which can be much faster for full site restoration:
<http://www.mediawiki.org/wiki/Manual:Importing_XML_dumps>
TEXT;
		$this->stderr = fopen( "php://stderr", "wt" );
		$this->addOption( 'report',
			'Report position and speed after every n pages processed', false, true );
		$this->addOption( 'namespaces', 
			'Import only the pages from namespaces belonging to the list of ' .
			'pipe-separated namespace names or namespace indexes', false, true );
		$this->addOption( 'dry-run', 'Parse dump without actually importing pages' );
		$this->addOption( 'debug', 'Output extra verbose debug information' );
		$this->addOption( 'uploads', 'Process file upload data if included (experimental)' );
		$this->addOption( 'no-updates', 'Disable link table updates. Is faster but leaves the wiki in an inconsistent state' );
		$this->addOption( 'image-base-path', 'Import files from a specified path', false, true );
		$this->addArg( 'file', 'Dump file to import [else use stdin]', false );
	}

	public function execute() {
		if( wfReadOnly() ) {
			$this->error( "Wiki is in read-only mode; you'll need to disable it for import to work.", true );
		}

		$this->reportingInterval = intval( $this->getOption( 'report', 100 ) );
		$this->dryRun = $this->hasOption( 'dry-run' );
		$this->uploads = $this->hasOption( 'uploads' ); // experimental!
		if ( $this->hasOption( 'image-base-path' ) ) {
			$this->imageBasePath = $this->getOption( 'image-base-path' );
		}
		if ( $this->hasOption( 'namespaces' ) ) {
			$this->setNsfilter( explode( '|', $this->getOption( 'namespaces' ) ) );
		}

		if( $this->hasArg() ) {
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
		$this->nsFilter = array_unique( array_map( array( $this, 'getNsIndex' ), $namespaces ) );
	}

	private function getNsIndex( $namespace ) {
		global $wgContLang;
		if ( ( $result = $wgContLang->getNsIndex( $namespace ) ) !== false ) {
			return $result;
		}
		$ns = intval( $namespace );
		if ( strval( $ns ) === $namespace && $wgContLang->getNsText( $ns ) !== false ) {
			return $ns;
		}
		$this->error( "Unknown namespace text / index specified: $namespace", true );
	}

	private function skippedNamespace( $obj ) {
		if ( $obj instanceof Title ) {
			$ns = $obj->getNamespace();
		} elseif ( $obj instanceof Revision ) {
			$ns = $obj->getTitle()->getNamespace();
		} elseif ( $obj instanceof WikiRevision ) {
			$ns = $obj->title->getNamespace();
		} else {
			echo wfBacktrace();
			$this->error( "Cannot get namespace of object in " . __METHOD__, true );
		}
		return is_array( $this->nsFilter ) && !in_array( $ns, $this->nsFilter );
	}

	function reportPage( $page ) {
		$this->pageCount++;
	}

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

	function handleUpload( $revision ) {
		if ( $this->uploads ) {
			if ( $this->skippedNamespace( $revision ) ) {
				return;
			}
			$this->uploadCount++;
			// $this->report();
			$this->progress( "upload: " . $revision->getFilename() );

			if ( !$this->dryRun ) {
				// bluuuh hack
				// call_user_func( $this->uploadCallback, $revision );
				$dbw = wfGetDB( DB_MASTER );
				return $dbw->deadlockLoop( array( $revision, 'importUpload' ) );
			}
		}
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
		if ( $this->mQuiet ) {
			$delta = wfTime() - $this->startTime;
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
		// XXX: Don't let deferred jobs array get absurdly large (bug 24375)
		wfDoUpdates( 'commit' );
	}

	function progress( $string ) {
		fwrite( $this->stderr, $string . "\n" );
	}

	function importFromFile( $filename ) {
		if ( preg_match( '/\.gz$/', $filename ) ) {
			$filename = 'compress.zlib://' . $filename;
		}
		elseif ( preg_match( '/\.bz2$/', $filename ) ) {
			$filename = 'compress.bzip2://' . $filename;
		}
		elseif ( preg_match( '/\.7z$/', $filename ) ) {
			$filename = 'mediawiki.compress.7z://' . $filename;
		}

		$file = fopen( $filename, 'rt' );
		return $this->importFromHandle( $file );
	}

	function importFromStdin() {
		$file = fopen( 'php://stdin', 'rt' );
		if( self::posix_isatty( $file ) ) {
			$this->maybeHelp( true );
		}
		return $this->importFromHandle( $file );
	}

	function importFromHandle( $handle ) {
		$this->startTime = wfTime();

		$source = new ImportStreamSource( $handle );
		$importer = new WikiImporter( $source );

		if( $this->hasOption( 'debug' ) ) {
			$importer->setDebug( true );
		}
		if ( $this->hasOption( 'no-updates' ) ) {
			$importer->setNoUpdates( true );
		}
		$importer->setPageCallback( array( &$this, 'reportPage' ) );
		$this->importCallback =  $importer->setRevisionCallback(
			array( &$this, 'handleRevision' ) );
		$this->uploadCallback = $importer->setUploadCallback(
			array( &$this, 'handleUpload' ) );
		$this->logItemCallback = $importer->setLogItemCallback(
			array( &$this, 'handleLogItem' ) );
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
require_once( RUN_MAINTENANCE_IF_MAIN );
