<?php
/**
 * Script that postprocesses XML dumps from dumpBackup.php to add page text
 *
 * Copyright © 2005 Brion Vibber <brion@pobox.com>, 2010 Alexandre Emsenhuber
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
	var $fetchCount = 0;
	var $prefetchCount = 0;

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

	function dump( $history, $text = WikiExporter::TEXT ) {
		# This shouldn't happen if on console... ;)
		header( 'Content-type: text/html; charset=UTF-8' );

		# Notice messages will foul up your XML output even if they're
		# relatively harmless.
		if ( ini_get( 'display_errors' ) )
			ini_set( 'display_errors', 'stderr' );

		$this->initProgress( $history );

		$this->db = $this->backupDb();

		$this->readDump();

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
		case 'spawn':
			$this->spawn = true;
			if ( $val ) {
				$this->php = $val;
			}
			break;
		}
	}

	function processFileOpt( $val, $param ) {
		switch( $val ) {
		case "file":
			return $param;
		case "gzip":
			return "compress.zlib://$param";
		case "bzip2":
			return "compress.bzip2://$param";
		case "7zip":
			return "mediawiki.compress.7z://$param";
		default:
			return $val;
		}
	}

	/**
	 * Overridden to include prefetch ratio if enabled.
	 */
	function showReport() {
		if ( !$this->prefetch ) {
			return parent::showReport();
		}

		if ( $this->reporting ) {
			$delta = wfTime() - $this->startTime;
			$now = wfTimestamp( TS_DB );
			if ( $delta ) {
				$rate = $this->pageCount / $delta;
				$revrate = $this->revCount / $delta;
				$portion = $this->revCount / $this->maxCount;
				$eta = $this->startTime + $delta / $portion;
				$etats = wfTimestamp( TS_DB, intval( $eta ) );
				$fetchrate = 100.0 * $this->prefetchCount / $this->fetchCount;
			} else {
				$rate = '-';
				$revrate = '-';
				$etats = '-';
				$fetchrate = '-';
			}
			$this->progress( sprintf( "%s: %s %d pages (%0.3f/sec), %d revs (%0.3f/sec), %0.1f%% prefetched, ETA %s [max %d]",
				$now, wfWikiID(), $this->pageCount, $rate, $this->revCount, $revrate, $fetchrate, $etats, $this->maxCount ) );
		}
	}

	function readDump() {
		$state = '';
		$lastName = '';
		$this->thisPage = 0;
		$this->thisRev = 0;

		$reader = new XMLReader();
		$reader->open( $this->input );
		$writer = new XMLWriter();
		$writer->openURI( 'php://stdout' );


		while ( $reader->read() ) {
			$tag = $reader->name;
			$type = $reader->nodeType;

			if ( $type == XmlReader::END_ELEMENT ) {
				$writer->endElement();

				if ( $tag == 'revision' ) {
					$this->revCount();
					$this->thisRev = '';
				} elseif ( $tag == 'page' ) {
					$this->reportPage();
					$this->thisPage = '';
				}
			} elseif ( $type == XmlReader::ELEMENT ) {
				$attribs = array();
				if ( $reader->hasAttributes ) {
					for ( $i = 0; $reader->moveToAttributeNo( $i ); $i++ ) {
						$attribs[$reader->name] = $reader->value;
					}
				}

				if ( $reader->isEmptyElement && $tag == 'text' && isset( $attribs['id'] ) ) {
					$writer->startElement( 'text' );
					$writer->writeAttribute( 'xml:space', 'preserve' );
					$text = $this->getText( $attribs['id'] );
					if ( strlen( $text ) ) {
						$writer->text( $text );
					}
					$writer->endElement();
				} else {
					$writer->startElement( $tag );
					foreach( $attribs as $name => $val ) {
						$writer->writeAttribute( $name, $val );
					}
					if ( $reader->isEmptyElement ) {
						$writer->endElement();
					}
				}

				$lastName = $tag;
				if ( $tag == 'revision' ) {
					$state = 'revision';
				} elseif ( $tag == 'page' ) {
					$state = 'page';
				}
			} elseif ( $type == XMLReader::SIGNIFICANT_WHITESPACE || $type = XMLReader::TEXT ) {
				if ( $lastName == 'id' ) {
					if ( $state == 'revision' ) {
						$this->thisRev .= $reader->value;
					} elseif ( $state == 'page' ) {
						$this->thisPage .= $reader->value;
					}
				}
				$writer->text( $reader->value );
			}
		}
		$writer->flush();
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
				$text =	 $this->getTextSpawned( $id );
			} else {
				$text =	 $this->getTextDbSafe( $id );
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
					}
					else {
						// would be nice to return something better to the caller someday,
						// log what we know about the failure and about the revision
						return("");
					}
				} else {
					$this->progress( "Error $this->failures " .
								 "of allowed $this->maxFailures retrieving revision text for text id $id! " .
								 "Pausing $this->failureTimeout seconds before retry..." );
					sleep( $this->failureTimeout );
				}
			} else {
				$this->failedTextRetrievals= 0;
				return( $text );
			}
		}

	}

	/**
	 * Fetch a text revision from the database, retrying in case of failure.
	 * This may survive some transitory errors by reconnecting, but
	 * may not survive a long-term server outage.
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

		$cmd = implode( " ",
			array_map( 'wfEscapeShellArg',
				array(
					$this->php,
					"$IP/maintenance/fetchText.php",
					'--wiki', wfWikiID() ) ) );
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
}


$dumper = new TextPassDumper( $argv );

if ( !isset( $options['help'] ) ) {
	$dumper->dump( WikiExporter::FULL );
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


