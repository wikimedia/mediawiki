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
 * 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */

$originalDir = getcwd();

$optionsWithArgs = array( 'server', 'pagelist', 'start', 'end' );

require_once( 'commandLine.inc' );
require_once( 'SpecialExport.php' );
require_once( 'maintenance/backup.inc' );

class TextPassDumper extends BackupDumper {
	var $prefetch = null;
	
	function dump() {
		# This shouldn't happen if on console... ;)
		header( 'Content-type: text/html; charset=UTF-8' );
		
		# Notice messages will foul up your XML output even if they're
		# relatively harmless.
//		ini_set( 'display_errors', false );
		
		$this->startTime = wfTime();
		
		$this->db =& wfGetDB( DB_SLAVE );
		$this->maxCount = $this->db->selectField( 'page', 'MAX(page_id)', '', 'BackupDumper::dump' );
		$this->startTime = wfTime();
		
		$this->egress = new ExportProgressFilter( $this->sink, $this );

		$input = fopen( "php://stdin", "rt" );
		$result = $this->readDump( $input );
		
		if( WikiError::isError( $result ) ) {
			$this->progress( $result->getMessage() );
		}
		
		$this->report( true );
	}
	
	function processOption( $opt, $val, $param ) {
		if( $opt == 'prefetch' ) {
			require_once 'maintenance/backupPrefetch.inc';
			$this->prefetch = new BaseDump( $val );
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
			$chunk = fread( $input, $bufferSize );
			if( !xml_parse( $parser, $chunk, feof( $input ) ) ) {
				wfDebug( "TextDumpPass::readDump encountered XML parsing error\n" );
				return new WikiXmlError( $parser, 'XML import parse failure', $chunk, $offset );
			}
			$offset += strlen( $chunk );
		} while( $chunk !== false && !feof( $input ) );
		xml_parser_free( $parser );
	}
	
	function getText( $id ) {
		if( isset( $this->prefetch ) ) {
			$text = $this->prefetch->prefetch( $this->thisPage, $this->thisRev );
			if( !is_null( $text ) )
				return $text;
		}
		$id = intval( $id );
		$row = $this->db->selectRow( 'text',
			array( 'old_text', 'old_flags' ),
			array( 'old_id' => $id ),
			'TextPassDumper::getText' );
		return UtfNormal::cleanUp( strval( Revision::getRevisionText( $row ) ) );
	}
	
	function startElement( $parser, $name, $attribs ) {
		$this->clearOpenElement( null );
		$this->lastName = $name;
		
		if( $name == 'revision' ) {
			$this->state = $name;
			$this->egress->writeOpenPage( null, $this->buffer );
			$this->buffer = "";
		} elseif( $name == 'page' ) {
			$this->state = $name;
			if( $this->atStart ) {
				$this->egress->writeOpenStream( $this->buffer );
				$this->buffer = "";
				$this->atStart = false;
			}
		}
		
		if( $name == "text" && isset( $attribs['id'] ) ) {
			$text = $this->getText( $attribs['id'] );
			$this->openElement = array( $name, array( 'xml:space' => 'preserve' ) );
			if( strlen( $text ) > 0 ) {
				$this->characterData( $parser, $text );
			}
		} else {
			$this->openElement = array( $name, $attribs );
		}
	}
	
	function endElement( $parser, $name ) {
		if( $this->openElement ) {
			$this->clearOpenElement( "" );
		} else {
			$this->buffer .= "</$name>";
		}
		
		if( $name == 'revision' ) {
			$this->egress->writeRevision( null, $this->buffer );
			$this->buffer = "";
		} elseif( $name == 'page' ) {
			$this->egress->writeClosePage( $this->buffer );
			$this->buffer = "";
		} elseif( $name == 'mediawiki' ) {
			$this->egress->writeCloseStream( $this->buffer );
			$this->buffer = "";
		}
	}
	
	function characterData( $parser, $data ) {
		$this->clearOpenElement( null );
		if( $this->lastName == "id" ) {
			if( $this->state == "revision" ) {
				$this->thisRev = intval( $data );
			} elseif( $this->state == "page" ) {
				$this->thisPage = intval( $data );
			}
		}
		$this->buffer .= htmlspecialchars( $data );
	}
	
	function clearOpenElement( $style ) {
		if( $this->openElement ) {
			$this->buffer .= wfElement( $this->openElement[0], $this->openElement[1], $style );
			$this->openElement = false;
		}
	}
}


$dumper = new TextPassDumper( $argv );

if( true ) {
	$dumper->dump();
} else {
	$dumper->progress( <<<END
This script postprocesses XML dumps from dumpBackup.php to add
page text which was stubbed out (using --stub).

XML input is accepted on stdin.
XML output is sent to stdout; progress reports are sent to stderr.

Usage: php dumpTextPass.php [<options>]
Options:
  --prefetch <file>  Use a prior dump file as a text source where possible.
              (Requires PHP 5.0+ and the XMLReader PECL extension)
  --quiet     Don't dump status reports to stderr.
  --report=n  Report position and speed after every n pages processed.
              (Default: 100)
END
);
}

?>
