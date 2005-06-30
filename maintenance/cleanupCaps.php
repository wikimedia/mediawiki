<?php
/*
 * Script to clean up broken page links when somebody turns on $wgCapitalLinks.
 *
 * Usage: php cleanupCaps.php [--dry-run]
 * Options:
 *   --dry-run  don't actually try moving them
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
 * 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @author Brion Vibber <brion at pobox.com>
 * @package MediaWiki
 * @subpackage maintenance
 */

$options = array( 'dry-run' );

require_once( 'commandLine.inc' );
require_once( 'FiveUpgrade.inc' );

class CapsCleanup extends FiveUpgrade {
	function CapsCleanup( $dryrun = false ) {
		parent::FiveUpgrade();
		
		$this->maxLag = 10; # if slaves are lagged more than 10 secs, wait
		$this->dryrun = $dryrun;
	}
	
	function cleanup() {
		global $wgCapitalLinks;
		if( $wgCapitalLinks ) {
			echo "\$wgCapitalLinks is on -- no need for caps links cleanup.\n";
			return false;
		}
		
		$this->runTable( 'page', 'WHERE page_namespace=0',
			array( &$this, 'processPage' ) );
	}
	
	function init( $count, $table ) {
		$this->processed = 0;
		$this->updated = 0;
		$this->count = $count;
		$this->startTime = wfTime();
		$this->table = $table;
	}
	
	function progress( $updated ) {
		$this->updated += $updated;
		$this->processed++;
		if( $this->processed % 100 != 0 ) {
			return;
		}
		$portion = $this->processed / $this->count;
		$updateRate = $this->updated / $this->processed;
		
		$now = wfTime();
		$delta = $now - $this->startTime;
		$estimatedTotalTime = $delta / $portion;
		$eta = $this->startTime + $estimatedTotalTime;
		
		printf( "%s: %6.2f%% done on %s; ETA %s [%d/%d] %.2f/sec <%.2f%% updated>\n",
			wfTimestamp( TS_DB, intval( $now ) ),
			$portion * 100.0,
			$this->table,
			wfTimestamp( TS_DB, intval( $eta ) ),
			$this->processed,
			$this->count,
			$this->processed / $delta,
			$updateRate * 100.0 );
		flush();
	}
	
	function runTable( $table, $where, $callback ) {
		$fname = 'CapsCleanup::buildTable';
		
		$count = $this->dbw->selectField( $table, 'count(*)', '', $fname );
		$this->init( $count, 'page' );
		$this->log( "Processing $table..." );
		
		$tableName = $this->dbr->tableName( $table );
		$sql = "SELECT * FROM $tableName $where";
		$result = $this->dbr->query( $sql, $fname );
		
		while( $row = $this->dbr->fetchObject( $result ) ) {
			$updated = call_user_func( $callback, $row );
		}
		$this->log( "Finished $table... $this->updated of $this->processed rows updated" );
		$this->dbr->freeResult( $result );
	}
	
	function processPage( $row ) {
		global $wgContLang;
		
		$current = Title::makeTitle( $row->page_namespace, $row->page_title );
		$display = $current->getPrefixedText();
		$upper = $row->page_title;
		$lower = $wgContLang->lcfirst( $row->page_title );
		if( $upper == $lower ) {
			$this->log( "\"$display\" already lowercase." );
			return $this->progress( 0 );
		}
		
		$target = Title::makeTitle( $row->page_namespace, $lower );
		$targetDisplay = $target->getPrefixedText();
		if( $target->exists() ) {
			$this->log( "\"$display\" skipped; \"$targetDisplay\" already exists" );
			return $this->progress( 0 );
		}
		
		if( $this->dryrun ) {
			$this->log( "\"$display\" -> \"$targetDisplay\": DRY RUN, NOT MOVED" );
			$ok = true;
		} else {
			$ok = $current->moveTo( $target, false, 'Converting page titles to lowercase' );
			$this->log( "\"$display\" -> \"$targetDisplay\": $ok" );
		}
		if( $ok === true ) {
			$this->progress( 1 );
			
			if( $row->page_namespace == NS_MAIN ) {
				$talk = Title::makeTitle( NS_TALK, $row->page_title );
				$xrow = $row;
				$row->page_namespace = NS_TALK;
				if( $talk->exists() ) {
					return $this->processPage( $row );
				}
			}
		} else {
			$this->progress( 0 );
		}
	}

}

$wgUser->setName( 'Conversion script' );
$caps = new CapsCleanup( isset( $options['dry-run'] ) );
$caps->cleanup();

?>
