<?php
/**
 * Base classes for database-dumping maintenance scripts.
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
 * @ingroup Dump
 * @ingroup Maintenance
 */

namespace MediaWiki\Maintenance;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/../Maintenance.php';
require_once __DIR__ . '/../../includes/export/WikiExporter.php';
// @codeCoverageIgnoreEnd

use DumpMultiWriter;
use DumpOutput;
use ExportProgressFilter;
use MediaWiki\MainConfigNames;
use MediaWiki\Settings\SettingsBuilder;
use MediaWiki\WikiMap\WikiMap;
use WikiExporter;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\IMaintainableDatabase;
use XmlDumpWriter;

/**
 * @ingroup Dump
 * @ingroup Maintenance
 */
abstract class BackupDumper extends Maintenance {
	/** @var bool */
	public $reporting = true;
	/** @var string[]|null null means all pages */
	public $pages = null;
	/** @var bool don't output <mediawiki> and <siteinfo> */
	public $skipHeader = false;
	/** @var bool don't output </mediawiki> */
	public $skipFooter = false;
	/** @var int */
	public $startId = 0;
	/** @var int */
	public $endId = 0;
	/** @var int */
	public $revStartId = 0;
	/** @var int */
	public $revEndId = 0;
	/** @var bool */
	public $dumpUploads = false;
	/** @var bool */
	public $dumpUploadFileContents = false;
	/** @var bool */
	public $orderRevs = false;
	/** @var array|null */
	public $limitNamespaces = [];
	/** @var resource|false */
	public $stderr;

	/** @var int */
	protected $reportingInterval = 100;
	/** @var int */
	protected $pageCount = 0;
	/** @var int */
	protected $revCount = 0;
	/** @var string|null null means use default */
	protected $schemaVersion = null;
	/** @var DumpMultiWriter|DumpOutput|null Output filters */
	protected $sink = null;
	/** @var float */
	protected $lastTime = 0;
	/** @var int */
	protected $pageCountLast = 0;
	/** @var int */
	protected $revCountLast = 0;

	/** @var string[] */
	protected $outputTypes = [];
	/** @var string[] */
	protected $filterTypes = [];

	/** @var int */
	protected $ID = 0;

	/** @var float */
	protected $startTime;
	/** @var int */
	protected $pageCountPart;
	/** @var int */
	protected $revCountPart;
	/** @var int */
	protected $maxCount;
	/** @var float */
	protected $timeOfCheckpoint;
	/** @var ExportProgressFilter */
	protected $egress;
	/** @var string */
	protected $buffer;
	/** @var array|false */
	protected $openElement;
	/** @var bool */
	protected $atStart;
	/** @var string|null */
	protected $thisRevModel;
	/** @var string|null */
	protected $thisRevFormat;
	/** @var string */
	protected $lastName;
	/** @var string */
	protected $state;

	/**
	 * The dependency-injected database to use.
	 *
	 * @var IMaintainableDatabase|null
	 *
	 * @see self::setDB
	 */
	protected $forcedDb = null;

	/**
	 * @param array|null $args For backward compatibility
	 */
	public function __construct( $args = null ) {
		parent::__construct();
		$this->stderr = fopen( "php://stderr", "wt" );

		// Built-in output and filter plugins
		$this->registerOutput( 'file', \DumpFileOutput::class );
		$this->registerOutput( 'gzip', \DumpGZipOutput::class );
		$this->registerOutput( 'bzip2', \DumpBZip2Output::class );
		$this->registerOutput( 'dbzip2', \DumpDBZip2Output::class );
		$this->registerOutput( 'lbzip2', \DumpLBZip2Output::class );
		$this->registerOutput( '7zip', \Dump7ZipOutput::class );

		$this->registerFilter( 'latest', \DumpLatestFilter::class );
		$this->registerFilter( 'notalk', \DumpNotalkFilter::class );
		$this->registerFilter( 'namespace', \DumpNamespaceFilter::class );

		// These three can be specified multiple times
		$this->addOption( 'plugin', 'Load a dump plugin class. Specify as <class>[:<file>].',
			false, true, false, true );
		$this->addOption( 'output', 'Begin a filtered output stream; Specify as <type>:<file>. ' .
			'<type>s: file, gzip, bzip2, 7zip, dbzip2, lbzip2', false, true, 'o', true );
		$this->addOption( 'filter', 'Add a filter on an output branch. Specify as ' .
			'<type>[:<options>]. <types>s: latest, notalk, namespace', false, true, false, true );
		$this->addOption( 'report', 'Report position and speed after every n pages processed. ' .
			'Default: 100.', false, true );
		$this->addOption( '7ziplevel', '7zip compression level for all 7zip outputs. Used for ' .
			'-mx option to 7za command.', false, true );
		// NOTE: we can't know the default schema version yet, since configuration has not been
		//       loaded when this constructor is called. To work around this, we re-declare
		//       this option in validateParamsAndArgs().
		$this->addOption( 'schema-version', 'Schema version to use for output.', false, true );

		if ( $args ) {
			// Args should be loaded and processed so that dump() can be called directly
			// instead of execute()
			$this->loadWithArgv( $args );
			$this->processOptions();
		}
	}

	public function finalSetup( SettingsBuilder $settingsBuilder ) {
		parent::finalSetup( $settingsBuilder );
		// re-declare the --schema-version option to include the default schema version
		// in the description.
		$schemaVersion = $settingsBuilder->getConfig()->get( MainConfigNames::XmlDumpSchemaVersion );
		$this->addOption( 'schema-version', 'Schema version to use for output. ' .
			'Default: ' . $schemaVersion, false, true );
	}

	/**
	 * @param string $name
	 * @param string $class Name of output filter plugin class
	 */
	public function registerOutput( $name, $class ) {
		$this->outputTypes[$name] = $class;
	}

	/**
	 * @param string $name
	 * @param string $class Name of filter plugin class
	 */
	public function registerFilter( $name, $class ) {
		$this->filterTypes[$name] = $class;
	}

	/**
	 * Load a plugin and register it
	 *
	 * @param string $class Name of plugin class; must have a static 'register'
	 *   method that takes a BackupDumper as a parameter.
	 * @param string $file Full or relative path to the PHP file to load, or empty
	 */
	public function loadPlugin( $class, $file ) {
		if ( $file != '' ) {
			require_once $file;
		}
		$register = [ $class, 'register' ];
		$register( $this );
	}

	/**
	 * Processes arguments and sets $this->$sink accordingly
	 */
	protected function processOptions() {
		$sink = null;
		$sinks = [];

		$this->schemaVersion = WikiExporter::schemaVersion();

		$options = $this->orderedOptions;
		foreach ( $options as [ $opt, $param ] ) {
			switch ( $opt ) {
				case 'plugin':
					$val = explode( ':', $param, 2 );

					if ( count( $val ) === 1 ) {
						$this->loadPlugin( $val[0], '' );
					} elseif ( count( $val ) === 2 ) {
						$this->loadPlugin( $val[0], $val[1] );
					}

					break;
				case 'output':
					$split = explode( ':', $param, 2 );
					if ( count( $split ) !== 2 ) {
						$this->fatalError( 'Invalid output parameter' );
					}
					[ $type, $file ] = $split;
					if ( $sink !== null ) {
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
					$sink ??= new DumpOutput();

					$split = explode( ':', $param, 2 );
					$key = $split[0];

					if ( !isset( $this->filterTypes[$key] ) ) {
						$this->fatalError( "Unrecognized filter type '$key'" );
					}

					$type = $this->filterTypes[$key];

					if ( count( $split ) === 2 ) {
						$filter = new $type( $sink, $split[1] );
					} else {
						$filter = new $type( $sink );
					}

					// references are lame in php...
					unset( $sink );
					$sink = $filter;

					break;
				case 'schema-version':
					if ( !in_array( $param, XmlDumpWriter::$supportedSchemas ) ) {
						$this->fatalError(
							"Unsupported schema version $param. Supported versions: " .
							implode( ', ', XmlDumpWriter::$supportedSchemas )
						);
					}
					$this->schemaVersion = $param;
					break;
			}
		}

		if ( $this->hasOption( 'report' ) ) {
			$this->reportingInterval = intval( $this->getOption( 'report' ) );
		}

		$sink ??= new DumpOutput();
		$sinks[] = $sink;

		if ( count( $sinks ) > 1 ) {
			$this->sink = new DumpMultiWriter( $sinks );
		} else {
			$this->sink = $sink;
		}
	}

	public function dump( $history, $text = WikiExporter::TEXT ) {
		# Notice messages will foul up your XML output even if they're
		# relatively harmless.
		if ( ini_get( 'display_errors' ) ) {
			ini_set( 'display_errors', 'stderr' );
		}

		$this->initProgress( $history );

		$services = $this->getServiceContainer();
		$exporter = $services->getWikiExporterFactory()->getWikiExporter(
			$this->getBackupDatabase(),
			$history,
			$text,
			$this->limitNamespaces
		);
		$exporter->setSchemaVersion( $this->schemaVersion );
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
		} elseif ( $this->pages === null ) {
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
	 * We'll make ETA calculations based on progress, assuming relatively
	 * constant per-revision rate.
	 * @param int $history WikiExporter::CURRENT or WikiExporter::FULL
	 */
	public function initProgress( $history = WikiExporter::FULL ) {
		$table = ( $history == WikiExporter::CURRENT ) ? 'page' : 'revision';
		$field = ( $history == WikiExporter::CURRENT ) ? 'page_id' : 'rev_id';

		$dbr = $this->forcedDb;
		if ( $this->forcedDb === null ) {
			$dbr = $this->getDB( DB_REPLICA, [ 'dump' ] );
		}
		$this->maxCount = $dbr->newSelectQueryBuilder()
			->select( "MAX($field)" )
			->from( $table )
			->caller( __METHOD__ )->fetchField();
		$this->startTime = microtime( true );
		$this->lastTime = $this->startTime;
		$this->ID = getmypid();
	}

	/**
	 * @return IDatabase
	 */
	protected function getBackupDatabase() {
		if ( $this->forcedDb !== null ) {
			return $this->forcedDb;
		}

		$db = $this->getServiceContainer()
			->getDBLoadBalancerFactory()
			->getMainLB()
			->getConnection( DB_REPLICA, 'dump' );

		// Discourage the server from disconnecting us if it takes a long time
		// to read out the big ol' batch query.
		$db->setSessionOptions( [ 'connTimeout' => 3600 * 24 ] );

		return $db;
	}

	/**
	 * Force the dump to use the provided database connection for database
	 * operations, wherever possible.
	 *
	 * @param IMaintainableDatabase $db The database connection to use
	 */
	public function setDB( IMaintainableDatabase $db ) {
		parent::setDB( $db );
		$this->forcedDb = $db;
	}

	public function reportPage() {
		$this->pageCount++;
	}

	public function revCount() {
		$this->revCount++;
		$this->report();
	}

	public function report( bool $final = false ) {
		if ( $final xor ( $this->revCount % $this->reportingInterval == 0 ) ) {
			$this->showReport();
		}
	}

	public function showReport() {
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

			$dbDomain = WikiMap::getCurrentWikiDbDomain()->getId();
			$this->progress( sprintf(
				"%s: %s (ID %d) %d pages (%0.1f|%0.1f/sec all|curr), "
					. "%d revs (%0.1f|%0.1f/sec all|curr), ETA %s [max %d]",
				$now, $dbDomain, $this->ID, $this->pageCount, $pageRate,
				$pageRatePart, $this->revCount, $revRate, $revRatePart, $etats,
				$this->maxCount
			) );
			$this->lastTime = $nowts;
			$this->revCountLast = $this->revCount;
		}
	}

	protected function progress( string $string ) {
		if ( $this->reporting ) {
			fwrite( $this->stderr, $string . "\n" );
		}
	}
}

/** @deprecated class alias since 1.43 */
class_alias( BackupDumper::class, 'BackupDumper' );
