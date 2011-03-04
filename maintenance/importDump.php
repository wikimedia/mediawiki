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

$optionsWithArgs = array( 'report', 'namespaces' );

require_once( dirname( __FILE__ ) . '/commandLine.inc' );

/**
 * @ingroup Maintenance
 * @todo port to Maintenance class
 */
class BackupReader {
	var $reportingInterval = 100;
	var $reporting = true;
	var $pageCount = 0;
	var $revCount  = 0;
	var $dryRun    = false;
	var $debug     = false;
	var $uploads   = false;
	var $nsFilter  = false;

	function __construct() {
		$this->stderr = fopen( "php://stderr", "wt" );
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
		wfDie( "Unknown namespace text / index specified: $namespace\n" );
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
			wfDie( "Cannot get namespace of object in " . __METHOD__ . "\n" );
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
		if ( $this->reporting ) {
			$delta = wfTime() - $this->startTime;
			if ( $delta ) {
				$rate = sprintf( "%.2f", $this->pageCount / $delta );
				$revrate = sprintf( "%.2f", $this->revCount / $delta );
			} else {
				$rate = '-';
				$revrate = '-';
			}
			# Logs dumps don't have page tallies
			if ( $this->pageCount )
				$this->progress( "$this->pageCount ($rate pages/sec $revrate revs/sec)" );
			else
				$this->progress( "$this->revCount ($revrate revs/sec)" );
		}
		wfWaitForSlaves( 5 );
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
		if( posix_isatty( $file ) ) {
			$this->showHelp();
			exit();
		}
		return $this->importFromHandle( $file );
	}

	function importFromHandle( $handle ) {
		$this->startTime = wfTime();

		$source = new ImportStreamSource( $handle );
		$importer = new WikiImporter( $source );

		$importer->setDebug( $this->debug );
		$importer->setPageCallback( array( &$this, 'reportPage' ) );
		$this->importCallback =  $importer->setRevisionCallback(
			array( &$this, 'handleRevision' ) );
		$this->uploadCallback = $importer->setUploadCallback(
			array( &$this, 'handleUpload' ) );
		$this->logItemCallback = $importer->setLogItemCallback(
			array( &$this, 'handleLogItem' ) );

		if ( $this->dryRun ) {
			$importer->setPageOutCallback( null );
		}

		return $importer->doImport();
	}

	function showHelp() {
		$gz = in_array('compress.zlib', stream_get_wrappers()) ? 'ok' : '(disabled; requires PHP zlib module)';
		$bz2 = in_array('compress.bzip2', stream_get_wrappers()) ? 'ok' : '(disabled; requires PHP bzip2 module)';
		echo "This script reads pages from an XML file as produced from Special:Export\n";
		echo "or dumpBackup.php, and saves them into the current wiki.\n";
		echo "\n";
		echo "Note that for very large data sets, importDump.php may be slow; there are\n";
		echo "alternate methods which can be much faster for full site restoration:\n";
		echo "http://www.mediawiki.org/wiki/Manual:Importing_XML_dumps\n";
		echo "\n";
		echo "Usage: php importDump.php [<options>] [<file>]\n";
		echo "If no file is listed, input may be piped from stdin.\n";
		echo "\n";
		echo "Options:\n";
		echo "  --quiet    Don't dump status reports to stderr.\n";
		echo "  --report=n Report position and speed after every n pages processed.\n";
		echo "  --namespaces=a|b|..|z Import only the pages from namespaces belonging to\n";
		echo "    the list of pipe-separated namespace names or namespace indexes\n";
		echo "  --dry-run  Parse dump without actually importing pages.\n";
		echo "  --debug    Output extra verbose debug information\n";
		echo "  --uploads  Process file upload data if included (experimental)\n";
		echo "\n";
		echo "Compressed XML files may be read directly:\n";
		echo "  .gz $gz\n";
		echo "  .bz2 $bz2\n";
		echo "  .7z (if 7za executable is in PATH)\n";
		echo "\n";
	}
}

if ( wfReadOnly() ) {
	wfDie( "Wiki is in read-only mode; you'll need to disable it for import to work.\n" );
}

$reader = new BackupReader();
if ( isset( $options['quiet'] ) ) {
	$reader->reporting = false;
}
if ( isset( $options['report'] ) ) {
	$reader->reportingInterval = intval( $options['report'] );
}
if ( isset( $options['dry-run'] ) ) {
	$reader->dryRun = true;
}
if ( isset( $options['debug'] ) ) {
	$reader->debug = true;
}
if ( isset( $options['uploads'] ) ) {
	$reader->uploads = true; // experimental!
}
if ( isset( $options['namespaces'] ) ) {
	$reader->setNsfilter( explode( '|', $options['namespaces'] ) );
}

if ( isset( $options['help'] ) ) {
	$reader->showHelp();
	exit();
} elseif ( isset( $args[0] ) ) {
	$result = $reader->importFromFile( $args[0] );
} else {
	$result = $reader->importFromStdin();
}

echo "Done!\n";
echo "You might want to run rebuildrecentchanges.php to regenerate\n";
echo "the recentchanges page.\n";
