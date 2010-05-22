<?php
/**
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
 * @ingroup Maintenance
 */

require_once( dirname( __FILE__ ) . '/Maintenance.php' );

class FixSlaveDesync extends Maintenance {
	public function __construct() {
		global $wgUseRootUser;
		$wgUseRootUser = true;

		parent::__construct();
		$this->mDescription = "";
		
	}
	
	public function execute() {
		global $slaveIndexes, $wgDBservers;
		$slaveIndexes = array();
		for ( $i = 1; $i < count( $wgDBservers ); $i++ ) {
			if ( wfGetLB()->isNonZeroLoad( $i ) ) {
				$slaveIndexes[] = $i;
			}
		}

		if ( $this->hasArg() ) {
			$this->desyncFixPage( $this->getArg() );
		} else {
			$dbw = wfGetDB( DB_MASTER );
			$maxPage = $dbw->selectField( 'page', 'MAX(page_id)', false, __METHOD__ );
			$corrupt = $this->findPageLatestCorruption();
			foreach ( $corrupt as $id => $dummy ) {
				$this->desyncFixPage( $id );
			}
		}
	}

	/**
	 * Find all pages that have a corrupted page_latest
	 * @return array
	 */
	private function findPageLatestCorruption() {
		$desync = array();
		$n = 0;
		$dbw = wfGetDB( DB_MASTER );
		$masterIDs = array();
		$res = $dbw->select( 'page', array( 'page_id', 'page_latest' ), array( 'page_id<6054123' ), __METHOD__ );
		$this->output( "Number of pages: " . $dbw->numRows( $res ) . "\n" );
		foreach ( $res as $row ) {
			$masterIDs[$row->page_id] = $row->page_latest;
			if ( !( ++$n % 10000 ) ) {
				$this->output( "$n\r" );
			}
		}
		$this->output( "\n" );
		$dbw->freeResult( $res );

		global $slaveIndexes;
		foreach ( $slaveIndexes as $i ) {
			$db = wfGetDB( $i );
			$res = $db->select( 'page', array( 'page_id', 'page_latest' ), array( 'page_id<6054123' ), __METHOD__ );
			foreach ( $res as $row ) {
				if ( isset( $masterIDs[$row->page_id] ) && $masterIDs[$row->page_id] != $row->page_latest ) {
					$desync[$row->page_id] = true;
					$this->output( $row->page_id . "\t" );
				}
			}
			$db->freeResult( $res );
		}
		$this->output( "\n" );
		return $desync;
	}

	/**
	 * Fix a broken page entry
	 * @param $pageID int The page_id to fix
	 */
	private function desyncFixPage( $pageID ) {
		global $slaveIndexes;

		# Check for a corrupted page_latest
		$dbw = wfGetDB( DB_MASTER );
		$dbw->begin();
		$realLatest = $dbw->selectField( 'page', 'page_latest', array( 'page_id' => $pageID ),
			__METHOD__, 'FOR UPDATE' );
		# list( $masterFile, $masterPos ) = $dbw->getMasterPos();
		$found = false;
		foreach ( $slaveIndexes as $i ) {
			$db = wfGetDB( $i );
			/*
			if ( !$db->masterPosWait( $masterFile, $masterPos, 10 ) ) {
				   $this->output( "Slave is too lagged, aborting\n" );
				   $dbw->commit();
				   sleep(10);
				   return;
			}*/
			$latest = $db->selectField( 'page', 'page_latest', array( 'page_id' => $pageID ), __METHOD__ );
			$max = $db->selectField( 'revision', 'MAX(rev_id)', false, __METHOD__ );
			if ( $latest != $realLatest && $realLatest < $max ) {
				$this->output( "page_latest corrupted in page $pageID, server $i\n" );
				$found = true;
				break;
			}
		}
		if ( !$found ) {
			$this->output( "page_id $pageID seems fine\n" );
			$dbw->commit();
			return;
		}

		# Find the missing revisions
		$res = $dbw->select( 'revision', array( 'rev_id' ), array( 'rev_page' => $pageID ),
			__METHOD__, 'FOR UPDATE' );
		$masterIDs = array();
		foreach ( $res as $row ) {
			$masterIDs[] = $row->rev_id;
		}
		$dbw->freeResult( $res );

		$res = $db->select( 'revision', array( 'rev_id' ), array( 'rev_page' => $pageID ), __METHOD__ );
		$slaveIDs = array();
		foreach ( $res as $row ) {
			$slaveIDs[] = $row->rev_id;
		}
		$db->freeResult( $res );
		if ( count( $masterIDs ) < count( $slaveIDs ) ) {
			$missingIDs = array_diff( $slaveIDs, $masterIDs );
			if ( count( $missingIDs ) ) {
				$this->output( "Found " . count( $missingIDs ) . " lost in master, copying from slave... " );
				$dbFrom = $db;
				$found = true;
				$toMaster = true;
			} else {
				$found = false;
			}
		} else {
			$missingIDs = array_diff( $masterIDs, $slaveIDs );
			if ( count( $missingIDs ) ) {
				$this->output( "Found " . count( $missingIDs ) . " missing revision(s), copying from master... " );
				$dbFrom = $dbw;
				$found = true;
				$toMaster = false;
			} else {
				$found = false;
			}
		}

		if ( $found ) {
			foreach ( $missingIDs as $rid ) {
				$this->output( "$rid " );
				# Revision
				$row = $dbFrom->selectRow( 'revision', '*', array( 'rev_id' => $rid ), __METHOD__ );
				if ( $toMaster ) {
					$id = $dbw->selectField( 'revision', 'rev_id', array( 'rev_id' => $rid ),
						__METHOD__, 'FOR UPDATE' );
					if ( $id ) {
						$this->output( "Revision already exists\n" );
						$found = false;
						break;
					} else {
						$dbw->insert( 'revision', get_object_vars( $row ), __METHOD__, 'IGNORE' );
					}
				} else {
					foreach ( $slaveIndexes as $i ) {
						$db = wfGetDB( $i );
						$db->insert( 'revision', get_object_vars( $row ), __METHOD__, 'IGNORE' );
					}
				}

				# Text
				$row = $dbFrom->selectRow( 'text', '*', array( 'old_id' => $row->rev_text_id ), __METHOD__ );
				if ( $toMaster ) {
					$dbw->insert( 'text', get_object_vars( $row ), __METHOD__, 'IGNORE' );
				} else {
					foreach ( $slaveIndexes as $i ) {
						$db = wfGetDB( $i );
						$db->insert( 'text', get_object_vars( $row ), __METHOD__, 'IGNORE' );
					}
				}
			}
			$this->output( "done\n" );
		}

		if ( $found ) {
			$this->output( "Fixing page_latest... " );
			if ( $toMaster ) {
				# $dbw->update( 'page', array( 'page_latest' => $realLatest ), array( 'page_id' => $pageID ), __METHOD__ );
			} else {
				foreach ( $slaveIndexes as $i ) {
					$db = wfGetDB( $i );
					$db->update( 'page', array( 'page_latest' => $realLatest ), array( 'page_id' => $pageID ), __METHOD__ );
				}
			}
			$this->output( "done\n" );
		}
		$dbw->commit();
	}
}

$maintClass = "FixSlaveDesync";
require_once( DO_MAINTENANCE );
