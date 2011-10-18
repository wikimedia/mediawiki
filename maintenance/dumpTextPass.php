<?php
/**
 * Script that postprocesses XML dumps from dumpBackup.php to add page text
 *
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

$originalDir = getcwd();

require_once( dirname( __FILE__ ) . '/commandLine.inc' );
require_once( 'backup.inc' );

/**
 * @ingroup Maintenance
 */
class TextPassDumper extends BackupDumper {
	var $prefetch = null;
	var $input = "php://stdin";
	var $history = WikiExporter::FULL;
	var $fetchCount = 0;
	var $prefetchCount = 0;
	var $prefetchCountLast = 0;
	var $fetchCountLast = 0;

	var $failures = 0;
	var $maxFailures = 5;
	var $failedTextRetrievals = 0;
	var $maxConsecutiveFailedTextRetrievals = 200;
	var $failureTimeout = 5; // Seconds to sleep after db failure

	var $php = "php";
	var $spawn = false;
	var $spawnProc = false;
	var $spawnWrite = false;
	var $spawnRead = false;
	var $spawnErr = false;

	var $xmlwriterobj = false;

	// when we spend more than maxTimeAllowed seconds on this run, we continue
	// processing until we write out the next complete page, then save output file(s),
	// rename it/them and open new one(s)
	var $maxTimeAllowed = 0;  // 0 = no limit
	var $timeExceeded = false;
	var $firstPageWritten = false;
	var $lastPageWritten = false;
	var $checkpointJustWritten = false;
	var $checkpointFiles = array();

	/**
	 * @var DatabaseBase
	 */
	protected $db;

	function initProgress( $history ) {
		parent::initProgress();
		$this->timeOfCheckpoint = $this->startTime;
	}

	function dump( $history, $text = WikiExporter::TEXT ) {
		// This shouldn't happen if on console... ;)
		header( 'Content-type: text/html; charset=UTF-8' );

		// Notice messages will foul up your XML output even if they're
		// relatively harmless.
		if ( ini_get( 'display_errors' ) )
			ini_set( 'display_errors', 'stderr' );

		$this->initProgress( $this->history );

		$this->db = $this->backupDb();

		$this->egress = new ExportProgressFilter( $this->sink, $this );

		// it would be nice to do it in the constructor, oh well. need egress set
		$this->finalOptionCheck();

		// we only want this so we know how to close a stream :-P
		$this->xmlwriterobj = new XmlDumpWriter();

		$input = fopen( $this->input, "rt" );
		$result = $this->readDump( $input );

		if ( WikiError::isError( $result ) ) {
			throw new MWException( $result->getMessage() );
		}

		if ( $this->spawnProc ) {
			$this->closeSpawn();
		}

		$this->report( true );
	}

	function processOption( $opt, $val, $param ) {
		global $IP;
		$url = $this->processFileOpt( $val, $param );

		switch( $opt ) {
		case 'prefetch':
			require_once "$IP/maintenance/backupPrefetch.inc";
			$this->prefetch = new BaseDump( $url );
			break;
		case 'stub':
			$this->input = $url;
			break;
		case 'maxtime':
			$this->maxTimeAllowed = intval($val)*60;
			break;
		case 'checkpointfile':
			$this->checkpointFiles[] = $val;
			break;
		case 'current':
			$this->history = WikiExporter::CURRENT;
			break;
		case 'full':
			$this->history = WikiExporter::FULL;
			break;
		case 'spawn':
			$this->spawn = true;
			if ( $val ) {
				$this->php = $val;
			}
			break;
		}
	}

	function processFileOpt( $val, $param ) {
		$fileURIs = explode(';',$param);
		foreach ( $fileURIs as $URI ) {
			switch( $val ) {
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
	function showReport() {
		if ( !$this->prefetch ) {
			parent::showReport();
			return;
		}

		if ( $this->reporting ) {
			$now = wfTimestamp( TS_DB );
			$nowts = wfTime();
			$deltaAll = wfTime() - $this->startTime;
			$deltaPart = wfTime() - $this->lastTime;
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
			$this->progress( sprintf( "%s: %s (ID %d) %d pages (%0.1f|%0.1f/sec all|curr), %d revs (%0.1f|%0.1f/sec all|curr), %0.1f%%|%0.1f%% prefetched (all|curr), ETA %s [max %d]",
					$now, wfWikiID(), $this->ID, $this->pageCount, $pageRate, $pageRatePart, $this->revCount, $revRate, $revRatePart, $fetchRate, $fetchRatePart, $etats, $this->maxCount ) );
			$this->lastTime = $nowts;
			$this->revCountLast = $this->revCount;
			$this->prefetchCountLast = $this->prefetchCount;
			$this->fetchCountLast = $this->fetchCount;
		}
	}

	function setTimeExceeded() {
		$this->timeExceeded = True;
	}

	function checkIfTimeExceeded() {
		if ( $this->maxTimeAllowed &&  ( $this->lastTime - $this->timeOfCheckpoint  > $this->maxTimeAllowed ) ) {
			return true;
		}
		return false;
	}

	function finalOptionCheck() {
		if ( ( $this->checkpointFiles && ! $this->maxTimeAllowed ) ||
			( $this->maxTimeAllowed && !$this->checkpointFiles ) ) {
			throw new MWException("Options checkpointfile and maxtime must be specified together.\n");
		}
		foreach ($this->checkpointFiles as $checkpointFile) {
			$count = substr_count ( $checkpointFile,"%s" );
			if ( $count != 2 ) {
				throw new MWException("Option checkpointfile must contain two '%s' for substitution of first and last pageids, count is $count instead, file is $checkpointFile.\n");
			}
		}

		if ( $this->checkpointFiles ) {
			$filenameList = (array)$this->egress->getFilenames();
			if ( count( $filenameList ) != count( $this->checkpointFiles ) ) {
				throw new MWException("One checkpointfile must be specified for each output option, if maxtime is used.\n");
			}
		}
	}

	function readDump( $input ) {
		$this->buffer = "";
		$this->openElement = false;
		$this->atStart = true;
		$this->state = "";
		$this->lastName = "";
		$this->thisPage = 0;
		$this->thisRev = 0;

		$parser = xml_parser_create( "UTF-8" );
		xml_parser_set_option( $parser, XML_OPTION_CASE_FOLDING, false );

		xml_set_element_handler( $parser, array( &$this, 'startElement' ), array( &$this, 'endElement' ) );
		xml_set_character_data_handler( $parser, array( &$this, 'characterData' ) );

		$offset = 0; // for context extraction on error reporting
		$bufferSize = 512 * 1024;
		do {
			if ($this->checkIfTimeExceeded()) {
				$this->setTimeExceeded();
			}
			$chunk = fread( $input, $bufferSize );
			if ( !xml_parse( $parser, $chunk, feof( $input ) ) ) {
				wfDebug( "TextDumpPass::readDump encountered XML parsing error\n" );
				return new WikiXmlError( $parser, 'XML import parse failure', $chunk, $offset );
			}
			$offset += strlen( $chunk );
		} while ( $chunk !== false && !feof( $input ) );
		if ($this->maxTimeAllowed) {
			$filenameList = (array)$this->egress->getFilenames();
			// we wrote some stuff after last checkpoint that needs renamed
			if (file_exists($filenameList[0])) {
				$newFilenames = array();
				# we might have just written the header and footer and had no
				# pages or revisions written... perhaps they were all deleted
				# there's no pageID 0 so we use that. the caller is responsible
				# for deciding what to do with a file containing only the
				# siteinfo information and the mw tags.
				if (! $this->firstPageWritten) {
					$firstPageID = str_pad(0,9,"0",STR_PAD_LEFT);
					$lastPageID = str_pad(0,9,"0",STR_PAD_LEFT);
				}
				else {
					$firstPageID = str_pad($this->firstPageWritten,9,"0",STR_PAD_LEFT);
					$lastPageID = str_pad($this->lastPageWritten,9,"0",STR_PAD_LEFT);
				}
				for ( $i = 0; $i < count( $filenameList ); $i++ ) {
					$checkpointNameFilledIn = sprintf( $this->checkpointFiles[$i], $firstPageID, $lastPageID );
					$fileinfo = pathinfo($filenameList[$i]);
					$newFilenames[] = $fileinfo['dirname'] . '/' . $checkpointNameFilledIn;
				}
				$this->egress->closeAndRename( $newFilenames );
			}
		}
		xml_parser_free( $parser );

		return true;
	}

	function getText( $id ) {
		$this->fetchCount++;
		if ( isset( $this->prefetch ) ) {
			$text = $this->prefetch->prefetch( $this->thisPage, $this->thisRev );
			if ( $text !== null ) { // Entry missing from prefetch dump
				$dbr = wfGetDB( DB_SLAVE );
				$revID = intval( $this->thisRev );
				$revLength = $dbr->selectField( 'revision', 'rev_len', array( 'rev_id' => $revID ) );
				// if length of rev text in file doesn't match length in db, we reload
				// this avoids carrying forward broken data from previous xml dumps
				if( strlen( $text ) == $revLength ) {
					$this->prefetchCount++;
					return $text;
				}
			}
		}
		return $this->doGetText( $id );
	}

	private function doGetText( $id ) {
		$id = intval( $id );
		$this->failures = 0;
		$ex = new MWException( "Graceful storage failure" );
		while (true) {
			if ( $this->spawn ) {
				if ($this->failures) {
					// we don't know why it failed, could be the child process
					// borked, could be db entry busted, could be db server out to lunch,
					// so cover all bases
					$this->closeSpawn();
					$this->openSpawn();
				}
				$text = $this->getTextSpawned( $id );
			} else {
				$text = $this->getTextDbSafe( $id );
			}
			if ( $text === false ) {
				$this->failures++;
				if ( $this->failures > $this->maxFailures) {
					$this->progress( "Failed to retrieve revision text for text id ".
									 "$id after $this->maxFailures tries, giving up" );
					// were there so many bad retrievals in a row we want to bail?
					// at some point we have to declare the dump irretrievably broken
					$this->failedTextRetrievals++;
					if ($this->failedTextRetrievals > $this->maxConsecutiveFailedTextRetrievals) {
						throw $ex;
					} else {
						// would be nice to return something better to the caller someday,
						// log what we know about the failure and about the revision
						return "";
					}
				} else {
					$this->progress( "Error $this->failures " .
								 "of allowed $this->maxFailures retrieving revision text for text id $id! " .
								 "Pausing $this->failureTimeout seconds before retry..." );
					sleep( $this->failureTimeout );
				}
			} else {
				$this->failedTextRetrievals= 0;
				return $text;
			}
		}
		return '';
	}

	/**
	 * Fetch a text revision from the database, retrying in case of failure.
	 * This may survive some transitory errors by reconnecting, but
	 * may not survive a long-term server outage.
	 *
	 * FIXME: WTF? Why is it using a loop and then returning unconditionally?
	 */
	private function getTextDbSafe( $id ) {
		while ( true ) {
			try {
				$text = $this->getTextDb( $id );
			} catch ( DBQueryError $ex ) {
				$text = false;
			}
			return $text;
		}
	}

	/**
	 * May throw a database error if, say, the server dies during query.
	 * @param $id
	 * @return bool|string
	 */
	private function getTextDb( $id ) {
		global $wgContLang;
		$row = $this->db->selectRow( 'text',
			array( 'old_text', 'old_flags' ),
			array( 'old_id' => $id ),
			__METHOD__ );
		$text = Revision::getRevisionText( $row );
		if ( $text === false ) {
			return false;
		}
		$stripped = str_replace( "\r", "", $text );
		$normalized = $wgContLang->normalize( $stripped );
		return $normalized;
	}

	private function getTextSpawned( $id ) {
		wfSuppressWarnings();
		if ( !$this->spawnProc ) {
			// First time?
			$this->openSpawn();
		}
		$text = $this->getTextSpawnedOnce( $id );
		wfRestoreWarnings();
		return $text;
	}

	function openSpawn() {
		global $IP;

		if ( file_exists( "$IP/../multiversion/MWScript.php" ) ) {
			$cmd = implode( " ",
				array_map( 'wfEscapeShellArg',
					array(
						$this->php,
						"$IP/../multiversion/MWScript.php",
						"fetchText.php",
						'--wiki', wfWikiID() ) ) );
		}
		else {
			$cmd = implode( " ",
				array_map( 'wfEscapeShellArg',
					array(
						$this->php,
						"$IP/maintenance/fetchText.php",
						'--wiki', wfWikiID() ) ) );
		}
		$spec = array(
			0 => array( "pipe", "r" ),
			1 => array( "pipe", "w" ),
			2 => array( "file", "/dev/null", "a" ) );
		$pipes = array();

		$this->progress( "Spawning database subprocess: $cmd" );
		$this->spawnProc = proc_open( $cmd, $spec, $pipes );
		if ( !$this->spawnProc ) {
			// shit
			$this->progress( "Subprocess spawn failed." );
			return false;
		}
		list(
			$this->spawnWrite, // -> stdin
			$this->spawnRead,  // <- stdout
		) = $pipes;

		return true;
	}

	private function closeSpawn() {
		wfSuppressWarnings();
		if ( $this->spawnRead )
			fclose( $this->spawnRead );
		$this->spawnRead = false;
		if ( $this->spawnWrite )
			fclose( $this->spawnWrite );
		$this->spawnWrite = false;
		if ( $this->spawnErr )
			fclose( $this->spawnErr );
		$this->spawnErr = false;
		if ( $this->spawnProc )
			pclose( $this->spawnProc );
		$this->spawnProc = false;
		wfRestoreWarnings();
	}

	private function getTextSpawnedOnce( $id ) {
		global $wgContLang;

		$ok = fwrite( $this->spawnWrite, "$id\n" );
		// $this->progress( ">> $id" );
		if ( !$ok ) return false;

		$ok = fflush( $this->spawnWrite );
		// $this->progress( ">> [flush]" );
		if ( !$ok ) return false;

		// check that the text id they are sending is the one we asked for
		// this avoids out of sync revision text errors we have encountered in the past
		$newId = fgets( $this->spawnRead );
		if ( $newId === false ) {
			return false;
		}
		if ( $id != intval( $newId ) ) {
			return false;
		}

		$len = fgets( $this->spawnRead );
		// $this->progress( "<< " . trim( $len ) );
		if ( $len === false ) return false;

		$nbytes = intval( $len );
		// actual error, not zero-length text
		if ($nbytes < 0 ) return false;

		$text = "";

		// Subprocess may not send everything at once, we have to loop.
		while ( $nbytes > strlen( $text ) ) {
			$buffer = fread( $this->spawnRead, $nbytes - strlen( $text ) );
			if ( $buffer === false ) break;
			$text .= $buffer;
		}

		$gotbytes = strlen( $text );
		if ( $gotbytes != $nbytes ) {
			$this->progress( "Expected $nbytes bytes from database subprocess, got $gotbytes " );
			return false;
		}

		// Do normalization in the dump thread...
		$stripped = str_replace( "\r", "", $text );
		$normalized = $wgContLang->normalize( $stripped );
		return $normalized;
	}

	function startElement( $parser, $name, $attribs ) {
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
		}

		if ( $name == "text" && isset( $attribs['id'] ) ) {
			$text = $this->getText( $attribs['id'] );
			$this->openElement = array( $name, array( 'xml:space' => 'preserve' ) );
			if ( strlen( $text ) > 0 ) {
				$this->characterData( $parser, $text );
			}
		} else {
			$this->openElement = array( $name, $attribs );
		}
	}

	function endElement( $parser, $name ) {
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
		} elseif ( $name == 'page' ) {
			if (! $this->firstPageWritten) {
				$this->firstPageWritten = trim($this->thisPage);
			}
			$this->lastPageWritten = trim($this->thisPage);
			if ($this->timeExceeded) {
				$this->egress->writeClosePage( $this->buffer );
				// nasty hack, we can't just write the chardata after the
				// page tag, it will include leading blanks from the next line
				$this->egress->sink->write("\n");

				$this->buffer = $this->xmlwriterobj->closeStream();
				$this->egress->writeCloseStream( $this->buffer );

				$this->buffer = "";
				$this->thisPage = "";
				// this could be more than one file if we had more than one output arg

				$filenameList = (array)$this->egress->getFilenames();
				$newFilenames = array();
				$firstPageID = str_pad($this->firstPageWritten,9,"0",STR_PAD_LEFT);
				$lastPageID = str_pad($this->lastPageWritten,9,"0",STR_PAD_LEFT);
				for ( $i = 0; $i < count( $filenameList ); $i++ ) {
					$checkpointNameFilledIn = sprintf( $this->checkpointFiles[$i], $firstPageID, $lastPageID );
					$fileinfo = pathinfo($filenameList[$i]);
					$newFilenames[] = $fileinfo['dirname'] . '/' . $checkpointNameFilledIn;
				}
				$this->egress->closeRenameAndReopen( $newFilenames );
				$this->buffer = $this->xmlwriterobj->openStream();
				$this->timeExceeded = false;
				$this->timeOfCheckpoint = $this->lastTime;
				$this->firstPageWritten = false;
				$this->checkpointJustWritten = true;
			}
			else {
				$this->egress->writeClosePage( $this->buffer );
				$this->buffer = "";
				$this->thisPage = "";
			}

		} elseif ( $name == 'mediawiki' ) {
			$this->egress->writeCloseStream( $this->buffer );
			$this->buffer = "";
		}
	}

	function characterData( $parser, $data ) {
		$this->clearOpenElement( null );
		if ( $this->lastName == "id" ) {
			if ( $this->state == "revision" ) {
				$this->thisRev .= $data;
			} elseif ( $this->state == "page" ) {
				$this->thisPage .= $data;
			}
		}
		// have to skip the newline left over from closepagetag line of
		// end of checkpoint files. nasty hack!!
		if ($this->checkpointJustWritten) {
			if ($data[0] == "\n") {
				$data = substr($data,1);
			}
			$this->checkpointJustWritten = false;
		}
		$this->buffer .= htmlspecialchars( $data );
	}

	function clearOpenElement( $style ) {
		if ( $this->openElement ) {
			$this->buffer .= Xml::element( $this->openElement[0], $this->openElement[1], $style );
			$this->openElement = false;
		}
	}
}


$dumper = new TextPassDumper( $argv );

if ( !isset( $options['help'] ) ) {
	$dumper->dump( true );
} else {
	$dumper->progress( <<<ENDS
This script postprocesses XML dumps from dumpBackup.php to add
page text which was stubbed out (using --stub).

XML input is accepted on stdin.
XML output is sent to stdout; progress reports are sent to stderr.

Usage: php dumpTextPass.php [<options>]
Options:
  --stub=<type>:<file> To load a compressed stub dump instead of stdin
  --prefetch=<type>:<file> Use a prior dump file as a text source, to save
			  pressure on the database.
			  (Requires the XMLReader extension)
  --maxtime=<minutes> Write out checkpoint file after this many minutes (writing
	          out complete page, closing xml file properly, and opening new one
	          with header).  This option requires the checkpointfile option.
  --checkpointfile=<filenamepattern> Use this string for checkpoint filenames,
		      substituting first pageid written for the first %s (required) and the
              last pageid written for the second %s if it exists.
  --quiet	  Don't dump status reports to stderr.
  --report=n  Report position and speed after every n pages processed.
			  (Default: 100)
  --server=h  Force reading from MySQL server h
  --current	  Base ETA on number of pages in database instead of all revisions
  --spawn	  Spawn a subprocess for loading text records
  --help      Display this help message
ENDS
);
}


