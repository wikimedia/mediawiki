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

$options = array( 'full', 'current', 'server' );

require_once( 'commandLine.inc' );
require_once( 'SpecialExport.php' );

class BackupDumper {
	var $reportingInterval = 100;
	var $reporting = true;
	var $pageCount = 0;
	var $revCount  = 0;
	var $server    = null; // use default
	
	function BackupDumper() {
		$this->stderr = fopen( "php://stderr", "wt" );
	}
	
	function dump( $history ) {
		# This shouldn't happen if on console... ;)
		header( 'Content-type: text/html; charset=UTF-8' );
		
		# Notice messages will foul up your XML output even if they're
		# relatively harmless.
		ini_set( 'display_errors', false );
		
		$this->startTime = wfTime();
		
		$db =& $this->backupDb();
		$exporter = new WikiExporter( $db, $history, MW_EXPORT_STREAM );
		$exporter->setPageCallback( array( &$this, 'reportPage' ) );
		$exporter->setRevisionCallback( array( &$this, 'revCount' ) );
		
		$exporter->openStream();
		$exporter->allPages();
		$exporter->closeStream();
		
		$this->report( true );
	}
	
	function &backupDb() {
		global $wgDBadminuser, $wgDBadminpassword;
		global $wgDBname;
		$db =& new Database( $this->backupServer(), $wgDBadminuser, $wgDBadminpassword, $wgDBname );
		return $db;
	}
	
	function backupServer() {
		global $wgDBserver;
		return $this->server
			? $this->server
			: $wgDBserver;
	}

	function reportPage( $page ) {
		$this->pageCount++;
		$this->report();
	}
	
	function revCount( $rev ) {
		$this->revCount++;
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
			global $wgDBname;
			$this->progress( "$wgDBname $this->pageCount ($rate pages/sec $revrate revs/sec)" );
		}
	}
	
	function progress( $string ) {
		fwrite( $this->stderr, $string . "\n" );
	}
}

$dumper = new BackupDumper();
if( isset( $options['quiet'] ) ) {
	$dumper->reporting = false;
}
if( isset( $options['report'] ) ) {
	$dumper->reportingInterval = IntVal( $options['report'] );
}
if( isset( $options['server'] ) ) {
	$dumper->server = $options['server'];
}
if( isset( $options['full'] ) ) {
	$dumper->dump( MW_EXPORT_FULL );
} elseif( isset( $options['current'] ) ) {
	$dumper->dump( MW_EXPORT_CURRENT );
} else {
	$dumper->progress( <<<END
This script dumps the wiki page database into an XML interchange wrapper
format for export or backup.

XML output is sent to stdout; progress reports are sent to stderr.

Usage: php dumpBackup.php <action> [<options>]
Actions:
  --full      Dump complete history of every page.
  --current   Includes only the latest revision of each page.
Options:
  --quiet     Don't dump status reports to stderr.
  --report=n  Report position and speed after every n pages processed.
              (Default: 100)
END
);
}

?>