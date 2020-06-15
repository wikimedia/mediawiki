<?php
/**
 * BackupDumper that postprocesses XML dumps from dumpBackup.php to add page text
 *
 * Copyright (C) 2005 Brion Vibber <brion@pobox.com>
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

require_once __DIR__ . '/BackupDumper.php';
require_once __DIR__ . '/SevenZipStream.php';
require_once __DIR__ . '/../../includes/export/WikiExporter.php';

use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Shell\Shell;
use MediaWiki\Storage\BlobAccessException;
use MediaWiki\Storage\SqlBlobStore;
use Wikimedia\Rdbms\IMaintainableDatabase;

/**
 * @ingroup Maintenance
 */
class TextPassDumper extends BackupDumper {
	/** @var BaseDump */
	public $prefetch = null;
	/** @var string|bool */
	private $thisPage;
	/** @var string|bool */
	private $thisRev;
	/** @var string|bool */
	private $thisRole = null;

	// when we spend more than maxTimeAllowed seconds on this run, we continue
	// processing until we write out the next complete page, then save output file(s),
	// rename it/them and open new one(s)
	public $maxTimeAllowed = 0; // 0 = no limit

	protected $input = "php://stdin";
	protected $history = WikiExporter::FULL;
	protected $fetchCount = 0;
	protected $prefetchCount = 0;
	protected $prefetchCountLast = 0;
	protected $fetchCountLast = 0;

	protected $maxFailures = 5;
	protected $maxConsecutiveFailedTextRetrievals = 200;
	protected $failureTimeout = 5; // Seconds to sleep after db failure

	protected $bufferSize = 524288; // In bytes. Maximum size to read from the stub in on go.

	/** @var array */
	protected $php = [ PHP_BINARY ];
	protected $spawn = false;

	/**
	 * @var bool|resource
	 */
	protected $spawnProc = false;

	/**
	 * @var resource
	 */
	protected $spawnWrite;

	/**
	 * @var resource
	 */
	protected $spawnRead;

	/**
	 * @var bool|resource
	 */
	protected $spawnErr = false;

	/**
	 * @var bool|XmlDumpWriter
	 */
	protected $xmlwriterobj = false;

	protected $timeExceeded = false;
	protected $firstPageWritten = false;
	protected $lastPageWritten = false;
	protected $checkpointJustWritten = false;
	/** @var string[] */
	protected $checkpointFiles = [];

	/**
	 * @var IMaintainableDatabase
	 */
	protected $db;

	/**
	 * @param array|null $args For backward compatibility
	 */
	public function __construct( $args = null ) {
		parent::__construct();

		$this->addDescription( <<<TEXT
This script postprocesses XML dumps from dumpBackup.php to add
page text which was stubbed out (using --stub).

XML input is accepted on stdin.
XML output is sent to stdout; progress reports are sent to stderr.
TEXT
		);
		$this->stderr = fopen( "php://stderr", "wt" );

		$this->addOption( 'stub', 'To load a compressed stub dump instead of stdin. ' .
			'Specify as --stub=<type>:<file>.', false, true );
		$this->addOption( 'prefetch', 'Use a prior dump file as a text source, to savepressure on the ' .
			'database. (Requires the XMLReader extension). Specify as --prefetch=<type>:<file>',
			false, true );
		$this->addOption( 'maxtime', 'Write out checkpoint file after this many minutes (writing' .
			'out complete page, closing xml file properly, and opening new one' .
			'with header).  This option requires the checkpointfile option.', false, true );
		$this->addOption( 'checkpointfile', 'Use this string for checkpoint filenames,substituting ' .
			'first pageid written for the first %s (required) and the last pageid written for the ' .
			'second %s if it exists.', false, true, false, true ); // This can be specified multiple times
		$this->addOption( 'quiet', 'Don\'t dump status reports to stderr.' );
		$this->addOption( 'full', 'Dump all revisions of every page' );
		$this->addOption( 'current', 'Base ETA on number of pages in database instead of all revisions' );
		$this->addOption( 'spawn', 'Spawn a subprocess for loading text records, optionally specify ' .
			'php[,mwscript] paths' );
		$this->addOption( 'buffersize', 'Buffer size in bytes to use for reading the stub. ' .
			'(Default: 512KB, Minimum: 4KB)', false, true );

		if ( $args ) {
			$this->loadWithArgv( $args );
			$this->processOptions();
		}
	}

	/**
	 * @return SqlBlobStore
	 */
	private function getBlobStore() {
		return MediaWikiServices::getInstance()->getBlobStore();
	}

	public function execute() {
		$this->processOptions();
		$this->dump( true );
	}

	protected function processOptions() {
		parent::processOptions();

		if ( $this->hasOption( 'buffersize' ) ) {
			$this->bufferSize = max( intval( $this->getOption( 'buffersize' ) ), 4 * 1024 );
		}

		if ( $this->hasOption( 'prefetch' ) ) {
			$url = $this->processFileOpt( $this->getOption( 'prefetch' ) );
			$this->prefetch = new BaseDump( $url );
		}

		if ( $this->hasOption( 'stub' ) ) {
			$this->input = $this->processFileOpt( $this->getOption( 'stub' ) );
		}

		if ( $this->hasOption( 'maxtime' ) ) {
			$this->maxTimeAllowed = intval( $this->getOption( 'maxtime' ) ) * 60;
		}

		if ( $this->hasOption( 'checkpointfile' ) ) {
			$this->checkpointFiles = $this->getOption( 'checkpointfile' );
		}

		if ( $this->hasOption( 'current' ) ) {
			$this->history = WikiExporter::CURRENT;
		}

		if ( $this->hasOption( 'full' ) ) {
			$this->history = WikiExporter::FULL;
		}

		if ( $this->hasOption( 'spawn' ) ) {
			$this->spawn = true;
			$val = $this->getOption( 'spawn' );
			if ( $val !== 1 ) {
				$this->php = explode( ',', $val, 2 );
			}
		}
	}

	/**
	 * Drop the database connection $this->db and try to get a new one.
	 *
	 * This function tries to get a /different/ connection if this is
	 * possible. Hence, (if this is possible) it switches to a different
	 * failover upon each call.
	 *
	 * This function resets $this->lb and closes all connections on it.
	 *
	 * @throws MWException
	 * @suppress PhanTypeObjectUnsetDeclaredProperty
	 */
	protected function rotateDb() {
		// Cleaning up old connections
		if ( isset( $this->lb ) ) {
			$this->lb->closeAll( __METHOD__ );
			unset( $this->lb );
		}

		if ( $this->forcedDb !== null ) {
			$this->db = $this->forcedDb;

			return;
		}

		if ( isset( $this->db ) && $this->db->isOpen() ) {
			throw new MWException( 'DB is set and has not been closed by the Load Balancer' );
		}

		unset( $this->db );

		// Trying to set up new connection.
		// We do /not/ retry upon failure, but delegate to encapsulating logic, to avoid
		// individually retrying at different layers of code.

		try {
			$lbFactory = MediaWikiServices::getInstance()->getDBLoadBalancerFactory();
			$this->lb = $lbFactory->newMainLB();
		} catch ( Exception $e ) {
			throw new MWException( __METHOD__
				. " rotating DB failed to obtain new load balancer (" . $e->getMessage() . ")" );
		}

		try {
			$this->db = $this->lb->getMaintenanceConnectionRef( DB_REPLICA, 'dump' );
		} catch ( Exception $e ) {
			throw new MWException( __METHOD__
				. " rotating DB failed to obtain new database (" . $e->getMessage() . ")" );
		}
	}

	public function initProgress( $history = WikiExporter::FULL ) {
		parent::initProgress();
		$this->timeOfCheckpoint = $this->startTime;
	}

	public function dump( $history, $text = WikiExporter::TEXT ) {
		// Notice messages will foul up your XML output even if they're
		// relatively harmless.
		if ( ini_get( 'display_errors' ) ) {
			ini_set( 'display_errors', 'stderr' );
		}

		$this->initProgress( $this->history );

		// We are trying to get an initial database connection to avoid that the
		// first try of this request's first call to getText fails. However, if
		// obtaining a good DB connection fails it's not a serious issue, as
		// getText does retry upon failure and can start without having a working
		// DB connection.
		try {
			$this->rotateDb();
		} catch ( Exception $e ) {
			// We do not even count this as failure. Just let eventual
			// watchdogs know.
			$this->progress( "Getting initial DB connection failed (" .
				$e->getMessage() . ")" );
		}

		$this->egress = new ExportProgressFilter( $this->sink, $this );

		// it would be nice to do it in the constructor, oh well. need egress set
		$this->finalOptionCheck();

		// we only want this so we know how to close a stream :-P
		$this->xmlwriterobj = new XmlDumpWriter( XmlDumpWriter::WRITE_CONTENT, $this->schemaVersion );

		$input = fopen( $this->input, "rt" );
		$this->readDump( $input );

		if ( $this->spawnProc ) {
			$this->closeSpawn();
		}

		$this->report( true );
	}

	protected function processFileOpt( $opt ) {
		$split = explode( ':', $opt, 2 );
		$val = $split[0];
		$param = '';
		if ( count( $split ) === 2 ) {
			$param = $split[1];
		}
		$fileURIs = explode( ';', $param );
		$newFileURIs = [];
		foreach ( $fileURIs as $URI ) {
			switch ( $val ) {
				case "file":
					$newURI = $URI;
					break;
				case "gzip":
					$newURI = "compress.zlib://$URI";
					break;
				case "bzip2":
					$newURI = "compress.bzip2://$URI";
					break;
				case "7zip":
					$newURI = "mediawiki.compress.7z://$URI";
					break;
				default:
					$newURI = $URI;
			}
			$newFileURIs[] = $newURI;
		}
		$val = implode( ';', $newFileURIs );

		return $val;
	}

	/**
	 * Overridden to include prefetch ratio if enabled.
	 */
	public function showReport() {
		if ( !$this->prefetch ) {
			parent::showReport();

			return;
		}

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
				if ( $this->fetchCount ) {
					$fetchRate = 100.0 * $this->prefetchCount / $this->fetchCount;
				} else {
					$fetchRate = '-';
				}
				$pageRate = $this->pageCount / $deltaAll;
				$revRate = $this->revCount / $deltaAll;
			} else {
				$pageRate = '-';
				$revRate = '-';
				$etats = '-';
				$fetchRate = '-';
			}
			if ( $deltaPart ) {
				if ( $this->fetchCountLast ) {
					$fetchRatePart = 100.0 * $this->prefetchCountLast / $this->fetchCountLast;
				} else {
					$fetchRatePart = '-';
				}
				$pageRatePart = $this->pageCountPart / $deltaPart;
				$revRatePart = $this->revCountPart / $deltaPart;
			} else {
				$fetchRatePart = '-';
				$pageRatePart = '-';
				$revRatePart = '-';
			}

			$dbDomain = WikiMap::getCurrentWikiDbDomain()->getId();
			$this->progress( sprintf(
				"%s: %s (ID %d) %d pages (%0.1f|%0.1f/sec all|curr), "
					. "%d revs (%0.1f|%0.1f/sec all|curr), %0.1f%%|%0.1f%% "
					. "prefetched (all|curr), ETA %s [max %d]",
				$now, $dbDomain, $this->ID, $this->pageCount, $pageRate,
				$pageRatePart, $this->revCount, $revRate, $revRatePart,
				$fetchRate, $fetchRatePart, $etats, $this->maxCount
			) );
			$this->lastTime = $nowts;
			$this->revCountLast = $this->revCount;
			$this->prefetchCountLast = $this->prefetchCount;
			$this->fetchCountLast = $this->fetchCount;
		}
	}

	private function setTimeExceeded() {
		$this->timeExceeded = true;
	}

	private function checkIfTimeExceeded() {
		if ( $this->maxTimeAllowed
			&& ( $this->lastTime - $this->timeOfCheckpoint > $this->maxTimeAllowed )
		) {
			return true;
		}

		return false;
	}

	private function finalOptionCheck() {
		if ( ( $this->checkpointFiles && !$this->maxTimeAllowed )
			|| ( $this->maxTimeAllowed && !$this->checkpointFiles )
		) {
			throw new MWException( "Options checkpointfile and maxtime must be specified together.\n" );
		}
		foreach ( $this->checkpointFiles as $checkpointFile ) {
			$count = substr_count( $checkpointFile, "%s" );
			if ( $count != 2 ) {
				throw new MWException( "Option checkpointfile must contain two '%s' "
					. "for substitution of first and last pageids, count is $count instead, "
					. "file is $checkpointFile.\n" );
			}
		}

		if ( $this->checkpointFiles ) {
			$filenameList = (array)$this->egress->getFilenames();
			if ( count( $filenameList ) != count( $this->checkpointFiles ) ) {
				throw new MWException( "One checkpointfile must be specified "
					. "for each output option, if maxtime is used.\n" );
			}
		}
	}

	/**
	 * @throws MWException Failure to parse XML input
	 * @param resource $input
	 * @return bool
	 */
	protected function readDump( $input ) {
		$this->buffer = "";
		$this->openElement = false;
		$this->atStart = true;
		$this->state = "";
		$this->lastName = "";
		$this->thisPage = 0;
		$this->thisRev = 0;
		$this->thisRole = null;
		$this->thisRevModel = null;
		$this->thisRevFormat = null;

		$parser = xml_parser_create( "UTF-8" );
		xml_parser_set_option( $parser, XML_OPTION_CASE_FOLDING, false );

		xml_set_element_handler(
			$parser,
			[ $this, 'startElement' ],
			[ $this, 'endElement' ]
		);
		xml_set_character_data_handler( $parser, [ $this, 'characterData' ] );

		$offset = 0; // for context extraction on error reporting
		do {
			if ( $this->checkIfTimeExceeded() ) {
				$this->setTimeExceeded();
			}
			$chunk = fread( $input, $this->bufferSize );
			if ( !xml_parse( $parser, $chunk, feof( $input ) ) ) {
				wfDebug( "TextDumpPass::readDump encountered XML parsing error" );

				$byte = xml_get_current_byte_index( $parser );
				$msg = wfMessage( 'xml-error-string',
					'XML import parse failure',
					xml_get_current_line_number( $parser ),
					xml_get_current_column_number( $parser ),
					$byte . ( $chunk === null ? null : ( '; "' . substr( $chunk, $byte - $offset, 16 ) . '"' ) ),
					xml_error_string( xml_get_error_code( $parser ) ) )->escaped();

				xml_parser_free( $parser );

				throw new MWException( $msg );
			}
			$offset += strlen( $chunk );
		} while ( $chunk !== false && !feof( $input ) );
		if ( $this->maxTimeAllowed ) {
			$filenameList = (array)$this->egress->getFilenames();
			// we wrote some stuff after last checkpoint that needs renamed
			if ( file_exists( $filenameList[0] ) ) {
				$newFilenames = [];
				# we might have just written the header and footer and had no
				# pages or revisions written... perhaps they were all deleted
				# there's no pageID 0 so we use that. the caller is responsible
				# for deciding what to do with a file containing only the
				# siteinfo information and the mw tags.
				if ( !$this->firstPageWritten ) {
					$firstPageID = str_pad( 0, 9, "0", STR_PAD_LEFT );
					$lastPageID = str_pad( 0, 9, "0", STR_PAD_LEFT );
				} else {
					$firstPageID = str_pad( $this->firstPageWritten, 9, "0", STR_PAD_LEFT );
					$lastPageID = str_pad( $this->lastPageWritten, 9, "0", STR_PAD_LEFT );
				}

				$filenameCount = count( $filenameList );
				for ( $i = 0; $i < $filenameCount; $i++ ) {
					$checkpointNameFilledIn = sprintf( $this->checkpointFiles[$i], $firstPageID, $lastPageID );
					$fileinfo = pathinfo( $filenameList[$i] );
					$newFilenames[] = $fileinfo['dirname'] . '/' . $checkpointNameFilledIn;
				}
				$this->egress->closeAndRename( $newFilenames );
			}
		}
		xml_parser_free( $parser );

		return true;
	}

	/**
	 * Applies applicable export transformations to $text.
	 *
	 * @param string $text
	 * @param string $model
	 * @param string|null $format
	 *
	 * @return string
	 */
	private function exportTransform( $text, $model, $format = null ) {
		try {
			$text = MediaWikiServices::getInstance()
				->getContentHandlerFactory()
				->getContentHandler( $model )
				->exportTransform( $text, $format );
		}
		catch ( MWException $ex ) {
			wfWarn( "Unable to apply export transformation for content model '$model': " .
				$ex->getMessage() );

			$this->progress(
				"Unable to apply export transformation for content model '$model': " .
				$ex->getMessage()
			);
		}

		return $text;
	}

	/**
	 * Tries to load revision text.
	 * Export transformations are applied if the content model is given or can be
	 * determined from the database.
	 *
	 * Upon errors, retries (Up to $this->maxFailures tries each call).
	 * If still no good revision could be found even after this retrying, "" is returned.
	 * If no good revision text could be returned for
	 * $this->maxConsecutiveFailedTextRetrievals consecutive calls to getText, MWException
	 * is thrown.
	 *
	 * @param int|string $id Content address, or text row ID.
	 * @param string|bool|null $model The content model used to determine
	 *  applicable export transformations. If $model is null, no transformation is applied.
	 * @param string|null $format The content format used when applying export transformations.
	 * @param int|null $expSize Expected length of the text, for sanity checks
	 *
	 * @return string The revision text for $id, or ""
	 * @throws MWException
	 */
	protected function getText( $id, $model = null, $format = null, $expSize = null ) {
		if ( !$this->isValidTextId( $id ) ) {
			$msg = "Skipping bad text id " . $id . " of revision " . $this->thisRev;
			$this->progress( $msg );
			return '';
		}

		$model = $model ?: null;
		$prefetchNotTried = true; // Whether or not we already tried to get the text via prefetch.
		$text = false; // The candidate for a good text. false if no proper value.
		$failures = 0; // The number of times, this invocation of getText already failed.

		// The number of times getText failed without yielding a good text in between.
		static $consecutiveFailedTextRetrievals = 0;

		$this->fetchCount++;

		// To allow to simply return on success and do not have to worry about book keeping,
		// we assume, this fetch works (possible after some retries). Nevertheless, we koop
		// the old value, so we can restore it, if problems occur (See after the while loop).
		$oldConsecutiveFailedTextRetrievals = $consecutiveFailedTextRetrievals;
		$consecutiveFailedTextRetrievals = 0;

		while ( $failures < $this->maxFailures ) {
			// As soon as we found a good text for the $id, we will return immediately.
			// Hence, if we make it past the try catch block, we know that we did not
			// find a good text.

			try {
				// Step 1: Get some text (or reuse from previous iteratuon if checking
				//         for plausibility failed)

				// Trying to get prefetch, if it has not been tried before
				// @phan-suppress-next-line PhanSuspiciousValueComparisonInLoop
				if ( $text === false && isset( $this->prefetch ) && $prefetchNotTried ) {
					$prefetchNotTried = false;
					$tryIsPrefetch = true;
					$text = $this->prefetch->prefetch(
						(int)$this->thisPage,
						(int)$this->thisRev,
						trim( $this->thisRole )
					);

					if ( $text === null ) {
						$text = false;
					}

					if ( is_string( $text ) && $model !== null ) {
						// Apply export transformation to text coming from an old dump.
						// The purpose of this transformation is to convert up from legacy
						// formats, which may still be used in the older dump that is used
						// for pre-fetching. Applying the transformation again should not
						// interfere with content that is already in the correct form.
						$text = $this->exportTransform( $text, $model, $format );
					}
				}

				if ( $text === false ) {
					// Fallback to asking the database
					$tryIsPrefetch = false;
					if ( $this->spawn ) {
						$text = $this->getTextSpawned( $id );
					} else {
						$text = $this->getTextDb( $id );
					}

					if ( $text !== false && $model !== null ) {
						// Apply export transformation to text coming from the database.
						// Prefetched text should already have transformations applied.
						$text = $this->exportTransform( $text, $model, $format );
					}

					// No more checks for texts from DB for now.
					// If we received something that is not false,
					// We treat it as good text, regardless of whether it actually is or is not
					if ( $text !== false ) {
						return $text;
					}
				}

				if ( $text === false ) {
					throw new MWException( "Generic error while obtaining text for id " . $id );
				}

				// We received a good candidate for the text of $id via some method

				// Step 2: Checking for plausibility and return the text if it is
				//         plausible

				if ( $expSize === null || strlen( $text ) == $expSize ) {
					if ( $tryIsPrefetch ) {
						$this->prefetchCount++;
					}

					return $text;
				}

				$text = false;
				throw new MWException( "Received text is unplausible for id " . $id );
			} catch ( Exception $e ) {
				$msg = "getting/checking text " . $id . " failed (" . $e->getMessage()
					. ") for revision " . $this->thisRev;
				if ( $failures + 1 < $this->maxFailures ) {
					$msg .= " (Will retry " . ( $this->maxFailures - $failures - 1 ) . " more times)";
				}
				$this->progress( $msg );
			}

			// Something went wrong; we did not a text that was plausible :(
			$failures++;

			// A failure in a prefetch hit does not warrant resetting db connection etc.
			if ( !$tryIsPrefetch ) {
				// After backing off for some time, we try to reboot the whole process as
				// much as possible to not carry over failures from one part to the other
				// parts
				sleep( $this->failureTimeout );
				try {
					$this->rotateDb();
					if ( $this->spawn ) {
						$this->closeSpawn();
						$this->openSpawn();
					}
				} catch ( Exception $e ) {
					$this->progress( "Rebooting getText infrastructure failed (" . $e->getMessage() . ")" .
						" Trying to continue anyways" );
				}
			}
		}

		// Retirieving a good text for $id failed (at least) maxFailures times.
		// We abort for this $id.

		// Restoring the consecutive failures, and maybe aborting, if the dump
		// is too broken.
		$consecutiveFailedTextRetrievals = $oldConsecutiveFailedTextRetrievals + 1;
		if ( $consecutiveFailedTextRetrievals > $this->maxConsecutiveFailedTextRetrievals ) {
			throw new MWException( "Graceful storage failure" );
		}

		return "";
	}

	/**
	 * Loads the serialized content from storage.
	 *
	 * @param int|string $id Content address, or text row ID.
	 * @return bool|string
	 */
	private function getTextDb( $id ) {
		$store = $this->getBlobStore();
		$address = ( is_int( $id ) || strpos( $id, ':' ) === false )
			? SqlBlobStore::makeAddressFromTextId( (int)$id )
			: $id;

		try {
			$text = $store->getBlob( $address );

			$stripped = str_replace( "\r", "", $text );
			$normalized = MediaWikiServices::getInstance()->getContentLanguage()
				->normalize( $stripped );

			return $normalized;
		} catch ( BlobAccessException $ex ) {
			// XXX: log a warning?
			return false;
		}
	}

	/**
	 * @param int|string $address Content address, or text row ID.
	 * @return bool|string
	 */
	private function getTextSpawned( $address ) {
		Wikimedia\suppressWarnings();
		if ( !$this->spawnProc ) {
			// First time?
			$this->openSpawn();
		}
		$text = $this->getTextSpawnedOnce( $address );
		Wikimedia\restoreWarnings();

		return $text;
	}

	protected function openSpawn() {
		global $IP;

		$wiki = WikiMap::getCurrentWikiId();
		if ( count( $this->php ) == 2 ) {
			$mwscriptpath = $this->php[1];
		} else {
			$mwscriptpath = "$IP/../multiversion/MWScript.php";
		}
		if ( file_exists( $mwscriptpath ) ) {
			$cmd = implode( " ",
				array_map( [ Shell::class, 'escape' ],
					[
						$this->php[0],
						$mwscriptpath,
						"fetchText.php",
						'--wiki', $wiki ] ) );
		} else {
			$cmd = implode( " ",
				array_map( [ Shell::class, 'escape' ],
					[
						$this->php[0],
						"$IP/maintenance/fetchText.php",
						'--wiki', $wiki ] ) );
		}
		$spec = [
			0 => [ "pipe", "r" ],
			1 => [ "pipe", "w" ],
			2 => [ "file", "/dev/null", "a" ] ];
		$pipes = [];

		$this->progress( "Spawning database subprocess: $cmd" );
		$this->spawnProc = proc_open( $cmd, $spec, $pipes );
		if ( !$this->spawnProc ) {
			$this->progress( "Subprocess spawn failed." );

			return false;
		}
		[
			$this->spawnWrite, // -> stdin
			$this->spawnRead, // <- stdout
		] = $pipes;

		return true;
	}

	private function closeSpawn() {
		Wikimedia\suppressWarnings();
		if ( $this->spawnRead ) {
			fclose( $this->spawnRead );
		}
		$this->spawnRead = null;
		if ( $this->spawnWrite ) {
			fclose( $this->spawnWrite );
		}
		$this->spawnWrite = null;
		if ( $this->spawnErr ) {
			fclose( $this->spawnErr );
		}
		$this->spawnErr = false;
		if ( $this->spawnProc ) {
			pclose( $this->spawnProc );
		}
		$this->spawnProc = false;
		Wikimedia\restoreWarnings();
	}

	/**
	 * @param int|string $address Content address, or text row ID.
	 * @return bool|string
	 */
	private function getTextSpawnedOnce( $address ) {
		if ( is_int( $address ) || intval( $address ) ) {
			$address = SqlBlobStore::makeAddressFromTextId( (int)$address );
		}

		$ok = fwrite( $this->spawnWrite, "$address\n" );
		// $this->progress( ">> $id" );
		if ( !$ok ) {
			return false;
		}

		$ok = fflush( $this->spawnWrite );
		// $this->progress( ">> [flush]" );
		if ( !$ok ) {
			return false;
		}

		// check that the text address they are sending is the one we asked for
		// this avoids out of sync revision text errors we have encountered in the past
		$newAddress = fgets( $this->spawnRead );
		if ( $newAddress === false ) {
			return false;
		}
		$newAddress = trim( $newAddress );
		if ( strpos( $newAddress, ':' ) === false ) {
			$newAddress = SqlBlobStore::makeAddressFromTextId( intval( $newAddress ) );
		}

		if ( $newAddress !== $address ) {
			return false;
		}

		$len = fgets( $this->spawnRead );
		// $this->progress( "<< " . trim( $len ) );
		if ( $len === false ) {
			return false;
		}

		$nbytes = intval( $len );
		// actual error, not zero-length text
		if ( $nbytes < 0 ) {
			return false;
		}

		$text = "";

		// Subprocess may not send everything at once, we have to loop.
		while ( $nbytes > strlen( $text ) ) {
			$buffer = fread( $this->spawnRead, $nbytes - strlen( $text ) );
			if ( $buffer === false ) {
				break;
			}
			$text .= $buffer;
		}

		$gotbytes = strlen( $text );
		if ( $gotbytes != $nbytes ) {
			$this->progress( "Expected $nbytes bytes from database subprocess, got $gotbytes " );

			return false;
		}

		// Do normalization in the dump thread...
		$stripped = str_replace( "\r", "", $text );
		$normalized = MediaWikiServices::getInstance()->getContentLanguage()->
			normalize( $stripped );

		return $normalized;
	}

	protected function startElement( $parser, $name, $attribs ) {
		$this->checkpointJustWritten = false;

		$this->clearOpenElement( null );
		$this->lastName = $name;

		if ( $name == 'revision' ) {
			$this->state = $name;
			$this->egress->writeOpenPage( null, $this->buffer );
			$this->buffer = "";
		} elseif ( $name == 'page' ) {
			$this->state = $name;
			if ( $this->atStart ) {
				$this->egress->writeOpenStream( $this->buffer );
				$this->buffer = "";
				$this->atStart = false;
			}
		} elseif ( $name === 'mediawiki' ) {
			if ( isset( $attribs['version'] ) ) {
				if ( $attribs['version'] !== $this->schemaVersion ) {
					throw new MWException( 'Mismatching schema version. '
						. 'Use the --schema-version option to set the output schema version to '
						. 'the version declared by the stub file, namely ' . $attribs['version'] );
				}
			}
		}

		if ( $name == "text" && ( isset( $attribs['id'] ) || isset( $attribs['location'] ) ) ) {
			$id = $attribs['location'] ?? $attribs['id'];
			$model = trim( $this->thisRevModel );
			$format = trim( $this->thisRevFormat );

			$model = $model === '' ? null : $model;
			$format = $format === '' ? null : $format;
			$expSize = !empty( $attribs['bytes'] ) && $model === CONTENT_MODEL_WIKITEXT
				? (int)$attribs['bytes'] : null;

			$text = $this->getText( $id, $model, $format, $expSize );

			unset( $attribs['id'] );
			unset( $attribs['location'] );
			if ( strlen( $text ) > 0 ) {
				$attribs['xml:space'] = 'preserve';
			}

			$this->openElement = [ $name, $attribs ];
			if ( strlen( $text ) > 0 ) {
				$this->characterData( $parser, $text );
			}
		} else {
			$this->openElement = [ $name, $attribs ];
		}
	}

	protected function endElement( $parser, $name ) {
		$this->checkpointJustWritten = false;

		if ( $this->openElement ) {
			$this->clearOpenElement( "" );
		} else {
			$this->buffer .= "</$name>";
		}

		if ( $name == 'revision' ) {
			$this->egress->writeRevision( null, $this->buffer );
			$this->buffer = "";
			$this->thisRev = "";
			$this->thisRole = null;
			$this->thisRevModel = null;
			$this->thisRevFormat = null;
		} elseif ( $name == 'page' ) {
			if ( !$this->firstPageWritten ) {
				$this->firstPageWritten = trim( $this->thisPage );
			}
			$this->lastPageWritten = trim( $this->thisPage );
			if ( $this->timeExceeded ) {
				$this->egress->writeClosePage( $this->buffer );
				// nasty hack, we can't just write the chardata after the
				// page tag, it will include leading blanks from the next line
				$this->egress->sink->write( "\n" );

				$this->buffer = $this->xmlwriterobj->closeStream();
				$this->egress->writeCloseStream( $this->buffer );

				$this->buffer = "";
				$this->thisPage = "";
				// this could be more than one file if we had more than one output arg

				$filenameList = (array)$this->egress->getFilenames();
				$newFilenames = [];
				$firstPageID = str_pad( $this->firstPageWritten, 9, "0", STR_PAD_LEFT );
				$lastPageID = str_pad( $this->lastPageWritten, 9, "0", STR_PAD_LEFT );
				$filenamesCount = count( $filenameList );
				for ( $i = 0; $i < $filenamesCount; $i++ ) {
					$checkpointNameFilledIn = sprintf( $this->checkpointFiles[$i], $firstPageID, $lastPageID );
					$fileinfo = pathinfo( $filenameList[$i] );
					$newFilenames[] = $fileinfo['dirname'] . '/' . $checkpointNameFilledIn;
				}
				$this->egress->closeRenameAndReopen( $newFilenames );
				$this->buffer = $this->xmlwriterobj->openStream();
				$this->timeExceeded = false;
				$this->timeOfCheckpoint = $this->lastTime;
				$this->firstPageWritten = false;
				$this->checkpointJustWritten = true;
			} else {
				$this->egress->writeClosePage( $this->buffer );
				$this->buffer = "";
				$this->thisPage = "";
			}
		} elseif ( $name == 'mediawiki' ) {
			$this->egress->writeCloseStream( $this->buffer );
			$this->buffer = "";
		}
	}

	protected function characterData( $parser, $data ) {
		$this->clearOpenElement( null );
		if ( $this->lastName == "id" ) {
			if ( $this->state == "revision" ) {
				$this->thisRev .= $data;
				$this->thisRole = SlotRecord::MAIN;
			} elseif ( $this->state == "page" ) {
				$this->thisPage .= $data;
			}
		} elseif ( $this->lastName == "model" ) {
			$this->thisRevModel .= $data;
		} elseif ( $this->lastName == "format" ) {
			$this->thisRevFormat .= $data;
		} elseif ( $this->lastName == "content" ) {
			$this->thisRole = "";
			$this->thisRevModel = "";
			$this->thisRevFormat = "";
		} elseif ( $this->lastName == "role" ) {
			$this->thisRole .= $data;
		}

		// have to skip the newline left over from closepagetag line of
		// end of checkpoint files. nasty hack!!
		if ( $this->checkpointJustWritten ) {
			if ( $data[0] == "\n" ) {
				$data = substr( $data, 1 );
			}
			$this->checkpointJustWritten = false;
		}
		$this->buffer .= htmlspecialchars( $data );
	}

	protected function clearOpenElement( $style ) {
		if ( $this->openElement ) {
			$this->buffer .= Xml::element( $this->openElement[0], $this->openElement[1], $style );
			$this->openElement = false;
		}
	}

	private function isValidTextId( $id ) {
		if ( preg_match( '/:/', $id ) ) {
			return $id !== 'tt:0';
		} elseif ( preg_match( '/^\d+$/', $id ) ) {
			return intval( $id ) > 0;
		}

		return false;
	}

}
