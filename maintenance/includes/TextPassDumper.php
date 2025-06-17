<?php
/**
 * BackupDumper that postprocesses XML dumps from dumpBackup.php to add page text
 *
 * Copyright (C) 2005 Brooke Vibber <bvibber@wikimedia.org>
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
require_once __DIR__ . '/BackupDumper.php';
require_once __DIR__ . '/../../includes/export/WikiExporter.php';
// @codeCoverageIgnoreEnd

use BaseDump;
use Exception;
use ExportProgressFilter;
use MediaWiki\Exception\MWException;
use MediaWiki\Exception\MWUnknownContentModelException;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Settings\SettingsBuilder;
use MediaWiki\Shell\Shell;
use MediaWiki\Storage\BlobAccessException;
use MediaWiki\Storage\BlobStore;
use MediaWiki\Storage\SqlBlobStore;
use MediaWiki\WikiMap\WikiMap;
use MediaWiki\Xml\Xml;
use RuntimeException;
use WikiExporter;
use Wikimedia\AtEase\AtEase;
use XmlDumpWriter;

/**
 * @ingroup Maintenance
 */
class TextPassDumper extends BackupDumper {
	/** @var BaseDump|null */
	public $prefetch = null;
	/** @var string */
	private $thisPage;
	/** @var string */
	private $thisRev;
	/** @var string|null */
	private $thisRole = null;

	/**
	 * @var int when we spend more than maxTimeAllowed seconds on this run, we continue
	 * processing until we write out the next complete page, then save output file(s),
	 * rename it/them and open new one(s); 0 = no limit
	 */
	public $maxTimeAllowed = 0;

	/** @var string */
	protected $input = "php://stdin";
	/** @var int */
	protected $history = WikiExporter::FULL;
	/** @var int */
	protected $fetchCount = 0;
	/** @var int */
	protected $prefetchCount = 0;
	/** @var int */
	protected $prefetchCountLast = 0;
	/** @var int */
	protected $fetchCountLast = 0;

	/** @var int */
	protected $maxFailures = 5;
	/** @var int */
	protected $maxConsecutiveFailedTextRetrievals = 200;
	/** @var int Seconds to sleep after db failure */
	protected $failureTimeout = 5;

	/** @var int In bytes. Maximum size to read from the stub in on go. */
	protected $bufferSize = 524_288;

	/** @var array */
	protected $php = [ PHP_BINARY ];
	/** @var bool */
	protected $spawn = false;

	/**
	 * @var resource|false
	 */
	protected $spawnProc = false;

	/**
	 * @var resource|null
	 */
	protected $spawnWrite;

	/**
	 * @var resource|null
	 */
	protected $spawnRead;

	/**
	 * @var resource|false
	 */
	protected $spawnErr = false;

	/**
	 * @var XmlDumpWriter|false
	 */
	protected $xmlwriterobj = false;

	/** @var bool */
	protected $timeExceeded = false;
	/** @var string|false */
	protected $firstPageWritten = false;
	/** @var string|false */
	protected $lastPageWritten = false;
	/** @var bool */
	protected $checkpointJustWritten = false;
	/** @var string[] */
	protected $checkpointFiles = [];

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
			'(Default: 512 KiB, Minimum: 4 KiB)', false, true );

		if ( $args ) {
			$this->loadWithArgv( $args );
			$this->processOptions();
		}
	}

	public function finalSetup( SettingsBuilder $settingsBuilder ) {
		parent::finalSetup( $settingsBuilder );

		SevenZipStream::register();
	}

	/**
	 * @return BlobStore
	 */
	private function getBlobStore() {
		return $this->getServiceContainer()->getBlobStore();
	}

	/**
	 * @return RevisionStore
	 */
	private function getRevisionStore() {
		return $this->getServiceContainer()->getRevisionStore();
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

	protected function processFileOpt( string $opt ): string {
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

	private function checkIfTimeExceeded(): bool {
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
			throw new RuntimeException( "Options checkpointfile and maxtime must be specified together.\n" );
		}
		foreach ( $this->checkpointFiles as $checkpointFile ) {
			$count = substr_count( $checkpointFile, "%s" );
			if ( $count !== 2 ) {
				throw new RuntimeException( "Option checkpointfile must contain two '%s' "
					. "for substitution of first and last pageids, count is $count instead, "
					. "file is $checkpointFile.\n" );
			}
		}

		if ( $this->checkpointFiles ) {
			$filenameList = (array)$this->egress->getFilenames();
			if ( count( $filenameList ) !== count( $this->checkpointFiles ) ) {
				throw new RuntimeException( "One checkpointfile must be specified "
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
		$this->thisPage = "";
		$this->thisRev = "";
		$this->thisRole = null;
		$this->thisRevModel = null;
		$this->thisRevFormat = null;

		$parser = xml_parser_create( "UTF-8" );
		xml_parser_set_option( $parser, XML_OPTION_CASE_FOLDING, 0 );

		xml_set_element_handler(
			$parser,
			$this->startElement( ... ),
			$this->endElement( ... )
		);
		xml_set_character_data_handler( $parser, $this->characterData( ... ) );

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
					$byte . ( $chunk === false ? '' : ( '; "' . substr( $chunk, $byte - $offset, 16 ) . '"' ) ),
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
					$firstPageID = str_pad( '0', 9, "0", STR_PAD_LEFT );
					$lastPageID = str_pad( '0', 9, "0", STR_PAD_LEFT );
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
			$contentHandler = $this->getServiceContainer()
				->getContentHandlerFactory()
				->getContentHandler( $model );
		} catch ( MWUnknownContentModelException $ex ) {
			wfWarn( "Unable to apply export transformation for content model '$model': " .
				$ex->getMessage() );

			$this->progress(
				"Unable to apply export transformation for content model '$model': " .
				$ex->getMessage()
			);
			return $text;
		}

		return $contentHandler->exportTransform( $text, $format );
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
	 * @param string|false|null $model The content model used to determine
	 *  applicable export transformations. If $model is null, no transformation is applied.
	 * @param string|null $format The content format used when applying export transformations.
	 * @param int|null $expSize Expected length of the text, for checks
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
		$contentAddress = $id; // Where the content should be found

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
				if ( $text === false && $this->prefetch && $prefetchNotTried ) {
					$prefetchNotTried = false;
					$tryIsPrefetch = true;
					$text = $this->prefetch->prefetch(
						(int)$this->thisPage,
						(int)$this->thisRev,
						trim( $this->thisRole )
					) ?? false;

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
						$text = $this->getTextSpawned( $contentAddress );
					} else {
						$text = $this->getTextDb( $contentAddress );
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
					throw new RuntimeException( "Generic error while obtaining text for id " . $contentAddress );
				}

				// We received a good candidate for the text of $id via some method

				// Step 2: Checking for plausibility and return the text if it is
				//         plausible

				if ( $expSize === null || strlen( $text ) == $expSize ) {
					// @phan-suppress-next-line PhanPossiblyUndeclaredVariable Set when text is not false
					if ( $tryIsPrefetch ) {
						$this->prefetchCount++;
					}

					return $text;
				}

				$text = false;
				throw new RuntimeException( "Received text is unplausible for id " . $contentAddress );
			} catch ( Exception $e ) {
				$msg = "getting/checking text " . $contentAddress . " failed (" . $e->getMessage()
					. ") for revision " . $this->thisRev;
				if ( $failures + 1 < $this->maxFailures ) {
					$msg .= " (Will retry " . ( $this->maxFailures - $failures - 1 ) . " more times)";
				}
				$this->progress( $msg );
			}

			// Something went wrong; we did not get a text that was plausible :(
			$failures++;

			if ( $contentAddress === $id && $this->thisRev && trim( $this->thisRole ) ) {
				try {
					// MediaWiki doesn't guarantee that content addresses are valid
					// for any significant length of time. Try refreshing as the
					// previously retrieved address may no longer be valid.
					$revRecord = $this->getRevisionStore()->getRevisionById( (int)$this->thisRev );
					if ( $revRecord !== null ) {
						$refreshed = $revRecord->getSlot( trim( $this->thisRole ) )->getAddress();
						if ( $contentAddress !== $refreshed ) {
							$this->progress(
								"Updated content address for rev {$this->thisRev} from "
								. "{$contentAddress} to {$refreshed}"
							);
							$contentAddress = $refreshed;
							// Skip sleeping if we updated the address
							continue;
						}
					}
				} catch ( Exception $e ) {
					$this->progress(
						"refreshing content address for revision {$this->thisRev} failed ({$e->getMessage()})"
					);
				}
			}

			// A failure in a prefetch hit does not warrant resetting db connection etc.
			if ( !$tryIsPrefetch ) {
				// After backing off for some time, we try to reboot the whole process as
				// much as possible to not carry over failures from one part to the other
				// parts
				sleep( $this->failureTimeout );
				try {
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

		// Retrieving a good text for $id failed (at least) maxFailures times.
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
	 * @return string|false
	 */
	private function getTextDb( $id ) {
		$store = $this->getBlobStore();
		$address = ( is_int( $id ) || strpos( $id, ':' ) === false )
			? SqlBlobStore::makeAddressFromTextId( (int)$id )
			: $id;

		try {
			$text = $store->getBlob( $address );

			$stripped = str_replace( "\r", "", $text );
			$normalized = $this->getServiceContainer()->getContentLanguage()
				->normalize( $stripped );

			return $normalized;
		} catch ( BlobAccessException ) {
			// XXX: log a warning?
			return false;
		}
	}

	/**
	 * @param int|string $address Content address, or text row ID.
	 * @return string|false
	 */
	private function getTextSpawned( $address ) {
		AtEase::suppressWarnings();
		if ( !$this->spawnProc ) {
			// First time?
			$this->openSpawn();
		}
		$text = $this->getTextSpawnedOnce( $address );
		AtEase::restoreWarnings();

		return $text;
	}

	protected function openSpawn(): bool {
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
		AtEase::suppressWarnings();
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
			proc_close( $this->spawnProc );
		}
		$this->spawnProc = false;
		AtEase::restoreWarnings();
	}

	/**
	 * @param int|string $address Content address, or text row ID.
	 * @return string|false
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
		$normalized = $this->getServiceContainer()->getContentLanguage()->
			normalize( $stripped );

		return $normalized;
	}

	protected function startElement( $parser, string $name, array $attribs ) {
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
					throw new RuntimeException( 'Mismatching schema version. '
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
			if ( $text !== '' ) {
				$attribs['xml:space'] = 'preserve';
			}

			$this->openElement = [ $name, $attribs ];
			if ( $text !== '' ) {
				$this->characterData( $parser, $text );
			}
		} else {
			$this->openElement = [ $name, $attribs ];
		}
	}

	protected function endElement( $parser, string $name ) {
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

	protected function characterData( $parser, string $data ) {
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
		$this->buffer .= htmlspecialchars( $data, ENT_COMPAT );
	}

	protected function clearOpenElement( ?string $style ) {
		if ( $this->openElement ) {
			$this->buffer .= Xml::element( $this->openElement[0], $this->openElement[1], $style );
			$this->openElement = false;
		}
	}

	private function isValidTextId( string $id ): bool {
		if ( preg_match( '/:/', $id ) ) {
			return $id !== 'tt:0';
		} elseif ( preg_match( '/^\d+$/', $id ) ) {
			return intval( $id ) > 0;
		}

		return false;
	}

}

/** @deprecated class alias since 1.43 */
class_alias( TextPassDumper::class, 'TextPassDumper' );
