<?php
/*
 * Script to clean up broken, unparseable titles.
 *
 * Usage: php cleanupTitles.php [--dry-run]
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

class TitleCleanup extends FiveUpgrade {
	function TitleCleanup( $dryrun = false ) {
		parent::FiveUpgrade();
		
		$this->maxLag = 10; # if slaves are lagged more than 10 secs, wait
		$this->dryrun = $dryrun;
	}
	
	function cleanup() {
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
		
		global $wgDBname;
		printf( "%s %s: %6.2f%% done on %s; ETA %s [%d/%d] %.2f/sec <%.2f%% updated>\n",
			$wgDBname,
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
		
		$verified = UtfNormal::cleanUp( $display );
		$title = Title::newFromText( $verified );
		
		if( is_null( $title ) ) {
			$this->log( "page $row->page_id ($display) is illegal." );
			$this->moveIllegalPage( $row );
			return $this->progress( 1 );
		}
		
		if( !$title->equals( $current ) ) {
			$this->log( "page $row->page_id ($display) doesn't match self." );
			$this->moveInconsistentPage( $row, $title );
			return $this->progress( 1 );
		}
		
		$this->progress( 0 );
	}
	
	function moveIllegalPage( $row ) {
		$legal = 'A-Za-z0-9_/\\\\-';
		$legalized = preg_replace_callback( "!([^$legal])!",
			array( &$this, 'hexChar' ),
			$row->page_title );
		if( $legalized == '.' ) $legalized = '(dot)';
		if( $legalized == '_' ) $legalized = '(space)';
		$legalized = 'Broken/' . $legalized;
		
		$title = Title::newFromText( $legalized );
		if( is_null( $title ) ) {
			$clean = 'Broken/id:' . $row->page_id;
			$this->log( "Couldn't legalize; form '$legalized' still invalid; using '$clean'" );
			$title = Title::newFromText( $clean );
		} elseif( $title->exists() ) {
			$clean = 'Broken/id:' . $row->page_id;
			$this->log( "Legalized for '$legalized' exists; using '$clean'" );
			$title = Title::newFromText( $clean );
		}
		
		$dest = $title->getDbKey();
		if( $this->dryrun ) {
			$this->log( "DRY RUN: would rename $row->page_id ($row->page_namespace,'$row->page_title') to ($row->page_namespace,'$dest')" );
		} else {
			$this->log( "renaming $row->page_id ($row->page_namespace,'$row->page_title') to ($row->page_namespace,'$dest')" );
			$dbw =& wfGetDB( DB_MASTER );
			$dbw->update( 'page',
				array( 'page_title' => $dest ),
				array( 'page_id' => $row->page_id ),
				'cleanupTitles::moveInconsistentPage' );
		}
	}
	
	function moveInconsistentPage( $row, $title ) {
		if( $title->exists() ) {
			$prior = $title->getDbKey();
			$clean = 'Broken/' . $prior;
			$verified = Title::makeTitleSafe( $row->page_namespace, $clean );
			if( $verified->exists() ) {
				$blah = "Broken/id:" . $row->page_id;
				$this->log( "Couldn't legalize; form '$clean' exists; using '$blah'" );
				$verified = Title::makeTitleSafe( $row->page_namespace, $blah );
			}
			$title = $verified;
		}
		if( is_null( $title ) ) {
			die( "Something awry; empty title.\n" );
		}
		$dest = $title->getDbKey();
		if( $this->dryrun ) {
			$this->log( "DRY RUN: would rename $row->page_id ($row->page_namespace,'$row->page_title') to ($row->page_namespace,'$dest')" );
		} else {
			$this->log( "renaming $row->page_id ($row->page_namespace,'$row->page_title') to ($row->page_namespace,'$dest')" );
			$dbw =& wfGetDB( DB_MASTER );
			$dbw->update( 'page',
				array( 'page_title' => $dest ),
				array( 'page_id' => $row->page_id ),
				'cleanupTitles::moveInconsistentPage' );
		}
	}
	
	function hexChar( $matches ) {
		return sprintf( "\\x%02x", ord( $matches[1] ) );
	}
}

$wgUser->setName( 'Conversion script' );
$caps = new TitleCleanup( isset( $options['dry-run'] ) );
$caps->cleanup();

?>
