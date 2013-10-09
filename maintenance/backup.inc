<?php
/**
 * Base classes for database dumpers
 *
 * Copyright © 2005 Brion Vibber <brion@pobox.com>
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
 * @ingroup Dump Maintenance
 */

/**
 * @ingroup Dump Maintenance
 */
class DumpDBZip2Output extends DumpPipeOutput {
	function __construct( $file ) {
		parent::__construct( "dbzip2", $file );
	}
}

/**
 * @ingroup Dump Maintenance
 */
class BackupDumper {
	var $reportingInterval = 100;
	var $reporting = true;
	var $pageCount = 0;
	var $revCount = 0;
	var $server = null; // use default
	var $pages = null; // all pages
	var $skipHeader = false; // don't output <mediawiki> and <siteinfo>
	var $skipFooter = false; // don't output </mediawiki>
	var $startId = 0;
	var $endId = 0;
	var $revStartId = 0;
	var $revEndId = 0;
	var $sink = null; // Output filters
	var $stubText = false; // include rev_text_id instead of text; for 2-pass dump
	var $dumpUploads = false;
	var $dumpUploadFileContents = false;
	var $lastTime = 0;
	var $pageCountLast = 0;
	var $revCountLast = 0;
	var $ID = 0;

	var $outputTypes = array(), $filterTypes = array();

	/**
	 * The dependency-injected database to use.
	 *
	 * @var DatabaseBase|null
	 *
	 * @see self::setDb
	 */
	protected $forcedDb = null;

	/**
	 * @var LoadBalancer
	 */
	protected $lb;

	function __construct( $args ) {
		$this->stderr = fopen( "php://stderr", "wt" );

		// Built-in output and filter plugins
		$this->registerOutput( 'file', 'DumpFileOutput' );
		$this->registerOutput( 'gzip', 'DumpGZipOutput' );
		$this->registerOutput( 'bzip2', 'DumpBZip2Output' );
		$this->registerOutput( 'dbzip2', 'DumpDBZip2Output' );
		$this->registerOutput( '7zip', 'Dump7ZipOutput' );

		$this->registerFilter( 'latest', 'DumpLatestFilter' );
		$this->registerFilter( 'notalk', 'DumpNotalkFilter' );
		$this->registerFilter( 'namespace', 'DumpNamespaceFilter' );

		$this->sink = $this->processArgs( $args );
	}

	/**
	 * @param $name String
	 * @param $class String: name of output filter plugin class
	 */
	function registerOutput( $name, $class ) {
		$this->outputTypes[$name] = $class;
	}

	/**
	 * @param $name String
	 * @param $class String: name of filter plugin class
	 */
	function registerFilter( $name, $class ) {
		$this->filterTypes[$name] = $class;
	}

	/**
	 * Load a plugin and register it
	 *
	 * @param $class String: name of plugin class; must have a static 'register'
	 *               method that takes a BackupDumper as a parameter.
	 * @param $file String: full or relative path to the PHP file to load, or empty
	 */
	function loadPlugin( $class, $file ) {
		if ( $file != '' ) {
			require_once $file;
		}
		$register = array( $class, 'register' );
		call_user_func_array( $register, array( &$this ) );
	}

	/**
	 * @param $args Array
	 * @return Array
	 */
	function processArgs( $args ) {
		$sink = null;
		$sinks = array();
		foreach ( $args as $arg ) {
			$matches = array();
			if ( preg_match( '/^--(.+?)(?:=(.+?)(?::(.+?))?)?$/', $arg, $matches ) ) {
				@list( /* $full */ , $opt, $val, $param ) = $matches;
				switch ( $opt ) {
				case "plugin":
					$this->loadPlugin( $val, $param );
					break;
				case "output":
					if ( !is_null( $sink ) ) {
						$sinks[] = $sink;
					}
					if ( !isset( $this->outputTypes[$val] ) ) {
						$this->fatalError( "Unrecognized output sink type '$val'" );
					}
					$type = $this->outputTypes[$val];
					$sink = new $type( $param );
					break;
				case "filter":
					if ( is_null( $sink ) ) {
						$sink = new DumpOutput();
					}
					if ( !isset( $this->filterTypes[$val] ) ) {
						$this->fatalError( "Unrecognized filter type '$val'" );
					}
					$type = $this->filterTypes[$val];
					$filter = new $type( $sink, $param );

					// references are lame in php...
					unset( $sink );
					$sink = $filter;

					break;
				case "report":
					$this->reportingInterval = intval( $val );
					break;
				case "server":
					$this->server = $val;
					break;
				case "force-normal":
					if ( !function_exists( 'utf8_normalize' ) ) {
						$this->fatalError( "UTF-8 normalization extension not loaded. " .
							"Install or remove --force-normal parameter to use slower code." );
					}
					break;
				default:
					$this->processOption( $opt, $val, $param );
				}
			}
		}

		if ( is_null( $sink ) ) {
			$sink = new DumpOutput();
		}
		$sinks[] = $sink;

		if ( count( $sinks ) > 1 ) {
			return new DumpMultiWriter( $sinks );
		} else {
			return $sink;
		}
	}

	function processOption( $opt, $val, $param ) {
		// extension point for subclasses to add options
	}

	function dump( $history, $text = WikiExporter::TEXT ) {
		# Notice messages will foul up your XML output even if they're
		# relatively harmless.
		if ( ini_get( 'display_errors' ) ) {
			ini_set( 'display_errors', 'stderr' );
		}

		$this->initProgress( $history );

		$db = $this->backupDb();
		$exporter = new WikiExporter( $db, $history, WikiExporter::STREAM, $text );
		$exporter->dumpUploads = $this->dumpUploads;
		$exporter->dumpUploadFileContents = $this->dumpUploadFileContents;

		$wrapper = new ExportProgressFilter( $this->sink, $this );
		$exporter->setOutputSink( $wrapper );

		if ( !$this->skipHeader ) {
			$exporter->openStream();
		}
		# Log item dumps: all or by range
		if ( $history & WikiExporter::LOGS ) {
			if ( $this->startId || $this->endId ) {
				$exporter->logsByRange( $this->startId, $this->endId );
			} else {
				$exporter->allLogs();
			}
		# Page dumps: all or by page ID range
		} elseif ( is_null( $this->pages ) ) {
			if ( $this->startId || $this->endId ) {
				$exporter->pagesByRange( $this->startId, $this->endId );
			} elseif ( $this->revStartId || $this->revEndId ) {
				$exporter->revsByRange( $this->revStartId, $this->revEndId );
			} else {
				$exporter->allPages();
			}
		# Dump of specific pages
		} else {
			$exporter->pagesByName( $this->pages );
		}

		if ( !$this->skipFooter ) {
			$exporter->closeStream();
		}

		$this->report( true );
	}

	/**
	 * Initialise starting time and maximum revision count.
	 * We'll make ETA calculations based an progress, assuming relatively
	 * constant per-revision rate.
	 * @param $history Integer: WikiExporter::CURRENT or WikiExporter::FULL
	 */
	function initProgress( $history = WikiExporter::FULL ) {
		$table = ( $history == WikiExporter::CURRENT ) ? 'page' : 'revision';
		$field = ( $history == WikiExporter::CURRENT ) ? 'page_id' : 'rev_id';

		$dbr = $this->forcedDb;
		if ( $this->forcedDb === null ) {
			$dbr = wfGetDB( DB_SLAVE );
		}
		$this->maxCount = $dbr->selectField( $table, "MAX($field)", '', __METHOD__ );
		$this->startTime = microtime( true );
		$this->lastTime = $this->startTime;
		$this->ID = getmypid();
	}

	/**
	 * @todo Fixme: the --server parameter is currently not respected, as it
	 * doesn't seem terribly easy to ask the load balancer for a particular
	 * connection by name.
	 * @return DatabaseBase
	 */
	function backupDb() {
		if ( $this->forcedDb !== null ) {
			return $this->forcedDb;
		}

		$this->lb = wfGetLBFactory()->newMainLB();
		$db = $this->lb->getConnection( DB_SLAVE, 'backup' );

		// Discourage the server from disconnecting us if it takes a long time
		// to read out the big ol' batch query.
		$db->setSessionOptions( array( 'connTimeout' => 3600 * 24 ) );

		return $db;
	}

	/**
	 * Force the dump to use the provided database connection for database
	 * operations, wherever possible.
	 *
	 * @param $db DatabaseBase|null: (Optional) the database connection to
	 *            use. If null, resort to use the globally provided ways to
	 *            get database connections.
	 */
	function setDb( DatabaseBase $db = null ) {
		$this->forcedDb = $db;
	}

	function __destruct() {
		if ( isset( $this->lb ) ) {
			$this->lb->closeAll();
		}
	}

	function backupServer() {
		global $wgDBserver;
		return $this->server
			? $this->server
			: $wgDBserver;
	}

	function reportPage() {
		$this->pageCount++;
	}

	function revCount() {
		$this->revCount++;
		$this->report();
	}

	function report( $final = false ) {
		if ( $final xor ( $this->revCount % $this->reportingInterval == 0 ) ) {
			$this->showReport();
		}
	}

	function showReport() {
		if ( $this->reporting ) {
			$now = wfTimestamp( TS_DB );
			$nowts = microtime( true );
			$deltaAll = $nowts - $this->startTime;
			$deltaPart = $nowts - $this->lastTime;
			$this->pageCountPart = $this->pageCount - $this->pageCountLast;
			$this->revCountPart = $this->revCount - $this->revCountLast;

			if ( $deltaAll ) {
				$portion = $this->revCount / $this->maxCount;
				$eta = $this->startTime + $deltaAll / $portion;
				$etats = wfTimestamp( TS_DB, intval( $eta ) );
				$pageRate = $this->pageCount / $deltaAll;
				$revRate = $this->revCount / $deltaAll;
			} else {
				$pageRate = '-';
				$revRate = '-';
				$etats = '-';
			}
			if ( $deltaPart ) {
				$pageRatePart = $this->pageCountPart / $deltaPart;
				$revRatePart = $this->revCountPart / $deltaPart;
			} else {
				$pageRatePart = '-';
				$revRatePart = '-';
			}
			$this->progress( sprintf( "%s: %s (ID %d) %d pages (%0.1f|%0.1f/sec all|curr), %d revs (%0.1f|%0.1f/sec all|curr), ETA %s [max %d]",
					$now, wfWikiID(), $this->ID, $this->pageCount, $pageRate, $pageRatePart, $this->revCount, $revRate, $revRatePart, $etats, $this->maxCount ) );
			$this->lastTime = $nowts;
			$this->revCountLast = $this->revCount;
		}
	}

	function progress( $string ) {
		fwrite( $this->stderr, $string . "\n" );
	}

	function fatalError( $msg ) {
		$this->progress( "$msg\n" );
		die( 1 );
	}
}

class ExportProgressFilter extends DumpFilter {
	function __construct( &$sink, &$progress ) {
		parent::__construct( $sink );
		$this->progress = $progress;
	}

	function writeClosePage( $string ) {
		parent::writeClosePage( $string );
		$this->progress->reportPage();
	}

	function writeRevision( $rev, $string ) {
		parent::writeRevision( $rev, $string );
		$this->progress->revCount();
	}
}
