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
 * @subpackage Maintenance
 */

$optionsWithArgs = array( 'report' );

require_once( 'commandLine.inc' );
require_once( 'SpecialImport.php' );

class BackupReader {
	var $reportingInterval = 100;
	var $reporting = true;
	var $pageCount = 0;
	var $revCount  = 0;
	var $dryRun    = false;
	
	function BackupReader() {
		$this->stderr = fopen( "php://stderr", "wt" );
	}
	
	function reportPage( $page ) {
		$this->pageCount++;
	}
	
	function handleRevision( $rev ) {
		$title = $rev->getTitle();
		$display = $title->getPrefixedText();
		$timestamp = $rev->getTimestamp();
		#echo "$display $timestamp\n";
		
		$this->revCount++;
		$this->report();
		
		if( !$this->dryRun ) {
			call_user_func( $this->importCallback, $rev );
		}
	}
	
	function report( $final = false ) {
		if( $final xor ( $this->pageCount % $this->reportingInterval == 0 ) ) {
			$this->showReport();
		}
	}
	
	function showReport() {
		if( $this->reporting ) {
			$delta = wfTime() - $this->startTime;
			if( $delta ) {
				$rate = $this->pageCount / $delta;
				$revrate = $this->revCount / $delta;
			} else {
				$rate = '-';
				$revrate = '-';
			}
			$this->progress( "$this->pageCount ($rate pages/sec $revrate revs/sec)" );
		}
	}
	
	function progress( $string ) {
		fwrite( $this->stderr, $string . "\n" );
	}
	
	function importFromFile( $filename ) {
		if( preg_match( '/\.gz$/', $filename ) ) {
			$filename = 'compress.zlib://' . $filename;
		}
		$file = fopen( $filename, 'rt' );
		$this->importFromHandle( $file );
	}
	
	function importFromStdin() {
		$file = fopen( 'php://stdin', 'rt' );
		$this->importFromHandle( $file );
	}
	
	function importFromHandle( $handle ) {
		$this->startTime = wfTime();
		
		$source = new ImportStreamSource( $handle );
		$importer = new WikiImporter( $source );
		
		$importer->setPageCallback( array( &$this, 'reportPage' ) );
		$this->importCallback =  $importer->setRevisionCallback(
			array( &$this, 'handleRevision' ) );
		
		$importer->doImport();
	}
}

$reader = new BackupReader();
if( isset( $options['quiet'] ) ) {
	$reader->reporting = false;
}
if( isset( $options['report'] ) ) {
	$reader->reportingInterval = IntVal( $options['report'] );
}
if( isset( $options['dry-run'] ) ) {
	$reader->dryRun = true;
}

if( isset( $args[0] ) ) {
	$reader->importFromFile( $args[0] );
} else {
	$reader->importFromStdin();
}

?>
