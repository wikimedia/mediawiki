<?php
/**
 * Base classes for database dumpers
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
 * @ingroup Dump Maintenance
 */

require_once __DIR__ . '/Maintenance.php';
require_once __DIR__ . '/../includes/export/DumpFilter.php';

/**
 * @ingroup Dump Maintenance
 */
class BackupDumper extends Maintenance {
	public $reporting = true;
	public $pages = null; // all pages
	public $skipHeader = false; // don't output <mediawiki> and <siteinfo>
	public $skipFooter = false; // don't output </mediawiki>
	public $startId = 0;
	public $endId = 0;
	public $revStartId = 0;
	public $revEndId = 0;
	public $dumpUploads = false;
	public $dumpUploadFileContents = false;
	public $orderRevs = false;

	protected $reportingInterval = 100;
	protected $pageCount = 0;
	protected $revCount = 0;
	protected $server = null; // use default
	protected $sink = null; // Output filters
	protected $lastTime = 0;
	protected $pageCountLast = 0;
	protected $revCountLast = 0;

	protected $outputTypes = [];
	protected $filterTypes = [];

	protected $ID = 0;

	/**
	 * The dependency-injected database to use.
	 *
	 * @var IDatabase|null
	 *
	 * @see self::setDB
	 */
	protected $forcedDb = null;

	/** @var LoadBalancer */
	protected $lb;

	// @todo Unused?
	private $stubText = false; // include rev_text_id instead of text; for 2-pass dump

	/**
	 * @param array $args For backward compatibility
	 */
	function __construct( $args = null ) {
		parent::__construct();
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

		// These three can be specified multiple times
		$this->addOption( 'plugin', 'Load a dump plugin class. Specify as <class>[:<file>].',
			false, true, false, true );
		$this->addOption( 'output', 'Begin a filtered output stream; Specify as <type>:<file>. ' .
			'<type>s: file, gzip, bzip2, 7zip, dbzip2', false, true, false, true );
		$this->addOption( 'filter', 'Add a filter on an output branch. Specify as ' .
			'<type>[:<options>]. <types>s: latest, notalk, namespace', false, true, false, true );
		$this->addOption( 'report', 'Report position and speed after every n pages processed. ' .
			'Default: 100.', false, true );
		$this->addOption( 'server', 'Force reading from MySQL server', false, true );
		$this->addOption( '7ziplevel', '7zip compression level for all 7zip outputs. Used for ' .
			'-mx option to 7za command.', false, true );

		if ( $args ) {
			// Args should be loaded and processed so that dump() can be called directly
			// instead of execute()
			$this->loadWithArgv( $args );
			$this->processOptions();
		}
	}

	/**
	 * @param string $name
	 * @param string $class Name of output filter plugin class
	 */
	function registerOutput( $name, $class ) {
		$this->outputTypes[$name] = $class;
	}

	/**
	 * @param string $name
	 * @param string $class Name of filter plugin class
	 */
	function registerFilter( $name, $class ) {
		$this->filterTypes[$name] = $class;
	}

	/**
	 * Load a plugin and register it
	 *
	 * @param string $class Name of plugin class; must have a static 'register'
	 *   method that takes a BackupDumper as a parameter.
	 * @param string $file Full or relative path to the PHP file to load, or empty
	 */
	function loadPlugin( $class, $file ) {
		if ( $file != '' ) {
			require_once $file;
		}
		$register = [ $class, 'register' ];
		call_user_func_array( $register, [ $this ] );
	}

	function execute() {
		throw new MWException( 'execute() must be overridden in subclasses' );
	}

	/**
	 * Processes arguments and sets $this->$sink accordingly
	 */
	function processOptions() {
		$sink = null;
		$sinks = [];

		$options = $this->orderedOptions;
		foreach ( $options as $arg ) {
			$opt = $arg[0];
			$param = $arg[1];

			switch ( $opt ) {
				case 'plugin':
					$val = explode( ':', $param );

					if ( count( $val ) === 1 ) {
						$this->loadPlugin( $val[0] );
					} elseif ( count( $val ) === 2 ) {
						$this->loadPlugin( $val[0], $val[1] );
					} else {
						$this->fatalError( 'Invalid plugin parameter' );
						return;
					}

					break;
				case 'output':
					$split = explode( ':', $param, 2 );
					if ( count( $split ) !== 2 ) {
						$this->fatalError( 'Invalid output parameter' );
					}
					list( $type, $file ) = $split;
					if ( !is_null( $sink ) ) {
						$sinks[] = $sink;
					}
					if ( !isset( $this->outputTypes[$type] ) ) {
						$this->fatalError( "Unrecognized output sink type '$type'" );
					}
					$class = $this->outputTypes[$type];
					if ( $type === "7zip" ) {
						$sink = new $class( $file, intval( $this->getOption( '7ziplevel' ) ) );
					} else {
						$sink = new $class( $file );
					}

					break;
				case 'filter':
					if ( is_null( $sink ) ) {
						$sink = new DumpOutput();
					}

					$split = explode( ':', $param );
					$key = $split[0];

					if ( !isset( $this->filterTypes[$key] ) ) {
						$this->fatalError( "Unrecognized filter type '$key'" );
					}

					$type = $this->filterTypes[$key];

					if ( count( $split ) === 1 ) {
						$filter = new $type( $sink );
					} elseif ( count( $split ) === 2 ) {
						$filter = new $type( $sink, $split[1] );
					} else {
						$this->fatalError( 'Invalid filter parameter' );
					}

					// references are lame in php...
					unset( $sink );
					$sink = $filter;

					break;
			}
		}

		if ( $this->hasOption( 'report' ) ) {
			$this->reportingInterval = intval( $this->getOption( 'report' ) );
		}

		if ( $this->hasOption( 'server' ) ) {
			$this->server = $this->getOption( 'server' );
		}

		if ( is_null( $sink ) ) {
			$sink = new DumpOutput();
		}
		$sinks[] = $sink;

		if ( count( $sinks ) > 1 ) {
			$this->sink = new DumpMultiWriter( $sinks );
		} else {
			$this->sink = $sink;
		}
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
		} elseif ( is_null( $this->pages ) ) {
			# Page dumps: all or by page ID range
			if ( $this->startId || $this->endId ) {
				$exporter->pagesByRange( $this->startId, $this->endId, $this->orderRevs );
			} elseif ( $this->revStartId || $this->revEndId ) {
				$exporter->revsByRange( $this->revStartId, $this->revEndId );
			} else {
				$exporter->allPages();
			}
		} else {
			# Dump of specific pages
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
	 * @param int $history WikiExporter::CURRENT or WikiExporter::FULL
	 */
	function initProgress( $history = WikiExporter::FULL ) {
		$table = ( $history == WikiExporter::CURRENT ) ? 'page' : 'revision';
		$field = ( $history == WikiExporter::CURRENT ) ? 'page_id' : 'rev_id';

		$dbr = $this->forcedDb;
		if ( $this->forcedDb === null ) {
			$dbr = wfGetDB( DB_REPLICA );
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
	 * @return IDatabase
	 */
	function backupDb() {
		if ( $this->forcedDb !== null ) {
			return $this->forcedDb;
		}

		$this->lb = wfGetLBFactory()->newMainLB();
		$db = $this->lb->getConnection( DB_REPLICA, 'dump' );

		// Discourage the server from disconnecting us if it takes a long time
		// to read out the big ol' batch query.
		$db->setSessionOptions( [ 'connTimeout' => 3600 * 24 ] );

		return $db;
	}

	/**
	 * Force the dump to use the provided database connection for database
	 * operations, wherever possible.
	 *
	 * @param IDatabase|null $db (Optional) the database connection to use. If null, resort to
	 *   use the globally provided ways to get database connections.
	 */
	function setDB( IDatabase $db = null ) {
		parent::setDB( $db );
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
			$this->progress( sprintf(
				"%s: %s (ID %d) %d pages (%0.1f|%0.1f/sec all|curr), "
					. "%d revs (%0.1f|%0.1f/sec all|curr), ETA %s [max %d]",
				$now, wfWikiID(), $this->ID, $this->pageCount, $pageRate,
				$pageRatePart, $this->revCount, $revRate, $revRatePart, $etats,
				$this->maxCount
			) );
			$this->lastTime = $nowts;
			$this->revCountLast = $this->revCount;
		}
	}

	function progress( $string ) {
		if ( $this->reporting ) {
			fwrite( $this->stderr, $string . "\n" );
		}
	}

	function fatalError( $msg ) {
		$this->error( "$msg\n", 1 );
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
