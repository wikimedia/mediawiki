<?php
/**
 * Script for periodic off-peak updating of the search index
 *
 * Usage: php updateSearchIndex.php [-s START] [-e END] [-p POSFILE] [-l LOCKTIME] [-q]
 * Where START is the starting timestamp
 * END is the ending timestamp
 * POSFILE is a file to load timestamps from and save them to, searchUpdate.WIKI_ID.pos by default
 * LOCKTIME is how long the searchindex and revision tables will be locked for
 * -q means quiet
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

require_once( dirname( __FILE__ ) . '/Maintenance.php' );

class UpdateSearchIndex extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->mDescription = "Script for periodic off-peak updating of the search index";
		$this->addOption( 's', 'starting timestamp', false, true );
		$this->addOption( 'e', 'Ending timestamp', false, true );
		$this->addOption( 'p', 'File for saving/loading timestamps, searchUpdate.WIKI_ID.pos by default', false, true );
		$this->addOption( 'l', 'How long the searchindex and revision tables will be locked for', false, true );
	}

	public function getDbType() {
		return Maintenance::DB_ADMIN;
	}

	public function execute() {
		$posFile = $this->getOption( 'p', 'searchUpdate.' . wfWikiId() . '.pos' );
		$end = $this->getOption( 'e', wfTimestampNow() );
		if ( $this->hasOption( 's' ) ) {
			$start = $this->getOption( 's' );
		} elseif ( is_readable( 'searchUpdate.pos' ) ) {
			# B/c to the old position file name which was hardcoded
			# We can safely delete the file when we're done though.
			$start = file_get_contents( 'searchUpdate.pos' );
			unlink( 'searchUpdate.pos' );
		} else {
			$start = @file_get_contents( $posFile );
			if ( !$start ) {
				$start = wfTimestamp( TS_MW, time() - 86400 );
			}
		}
		$lockTime = $this->getOption( 'l', 20 );

		$this->doUpdateSearchIndex( $start, $end, $lockTime );
		if ( is_writable( dirname( realpath( $posFile ) ) ) ) {
			$file = fopen( $posFile, 'w' );
			if ( $file !== false ) {
				fwrite( $file, $end );
				fclose( $file );
			} else {
				$this->output( "*** Couldn't write to the $posFile!\n" );
			}
		} else {
			$this->output( "*** Couldn't write to the $posFile!\n" );
		}
	}

	private function doUpdateSearchIndex( $start, $end, $maxLockTime ) {
		global $wgDisableSearchUpdate;

		$wgDisableSearchUpdate = false;

		$dbw = wfGetDB( DB_MASTER );
		$recentchanges = $dbw->tableName( 'recentchanges' );

		$this->output( "Updating searchindex between $start and $end\n" );

		# Select entries from recentchanges which are on top and between the specified times
		$start = $dbw->timestamp( $start );
		$end = $dbw->timestamp( $end );

		$page = $dbw->tableName( 'page' );
		$sql = "SELECT rc_cur_id,rc_type,rc_moved_to_ns,rc_moved_to_title FROM $recentchanges
		  JOIN $page ON rc_cur_id=page_id AND rc_this_oldid=page_latest
		  WHERE rc_timestamp BETWEEN '$start' AND '$end'
		  ";
		$res = $dbw->query( $sql, __METHOD__ );

		$this->updateSearchIndex( $maxLockTime, array( $this, 'searchIndexUpdateCallback' ), $dbw, $res );

		$this->output( "Done\n" );
	}

	public function searchIndexUpdateCallback( $dbw, $row ) {
		if ( $row->rc_type == RC_MOVE || $row->rc_type == RC_MOVE_OVER_REDIRECT ) {
			# Rename searchindex entry
			$titleObj = Title::makeTitle( $row->rc_moved_to_ns, $row->rc_moved_to_title );
			$title = $titleObj->getPrefixedDBkey();
			$this->output( "$title..." );
			$u = new SearchUpdate( $row->rc_cur_id, $title, false );
			$u->doUpdate();
			$this->output( "\n" );
		} elseif ( $row->rc_type !== RC_LOG ) {
			$this->updateSearchIndexForPage( $dbw, $row->rc_cur_id );
		}
	}
}

$maintClass = "UpdateSearchIndex";
require_once( RUN_MAINTENANCE_IF_MAIN );
