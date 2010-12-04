<?php
/**
 * Look for 'orphan' revisions hooked to pages which don't exist
 * And 'childless' pages with no revisions.
 * Then, kill the poor widows and orphans.
 * Man this is depressing.
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
 * @author <brion@pobox.com>
 * @ingroup Maintenance
 */

require_once( dirname( __FILE__ ) . '/Maintenance.php' );

class Orphans extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Look for 'orphan' revisions hooked to pages which don't exist\n" .
								"And 'childless' pages with no revisions\n" .
								"Then, kill the poor widows and orphans\n" .
								"Man this is depressing";
		$this->addOption( 'fix', 'Actually fix broken entries' );
	}

	public function execute() {
		global $wgTitle;
		$wgTitle = Title::newFromText( 'Orphan revision cleanup script' );
		$this->checkOrphans( $this->hasOption( 'fix' ) );
		$this->checkSeparation( $this->hasOption( 'fix' ) );
		# Does not work yet, do not use
		# $this->checkWidows( $this->hasOption( 'fix' ) );
	}

	/**
	 * Lock the appropriate tables for the script
	 * @param $db Database object
	 * @param $extraTable String The name of any extra tables to lock (eg: text)
	 */
	private function lockTables( &$db, $extraTable = null ) {
		$tbls = array( 'page', 'revision', 'redirect' );
		if ( $extraTable )
			$tbls[] = $extraTable;
		$db->lockTables( array(), $tbls, __METHOD__, false );
	}

	/**
	 * Check for orphan revisions
	 * @param $fix bool Whether to fix broken revisions when found
	 */
	private function checkOrphans( $fix ) {
		$dbw = wfGetDB( DB_MASTER );
		$page = $dbw->tableName( 'page' );
		$revision = $dbw->tableName( 'revision' );

		if ( $fix ) {
			$this->lockTables( $dbw );
		}

		$this->output( "Checking for orphan revision table entries... (this may take a while on a large wiki)\n" );
		$result = $dbw->query( "
			SELECT *
			FROM $revision LEFT OUTER JOIN $page ON rev_page=page_id
			WHERE page_id IS NULL
		" );
		$orphans = $dbw->numRows( $result );
		if ( $orphans > 0 ) {
			global $wgContLang;
			$this->output( "$orphans orphan revisions...\n" );
			$this->output( sprintf( "%10s %10s %14s %20s %s\n", 'rev_id', 'rev_page', 'rev_timestamp', 'rev_user_text', 'rev_comment' ) );
			foreach ( $result as $row ) {
				$comment = ( $row->rev_comment == '' )
					? ''
					: '(' . $wgContLang->truncate( $row->rev_comment, 40 ) . ')';
				$this->output( sprintf( "%10d %10d %14s %20s %s\n",
					$row->rev_id,
					$row->rev_page,
					$row->rev_timestamp,
					$wgContLang->truncate( $row->rev_user_text, 17 ),
					$comment ) );
				if ( $fix ) {
					$dbw->delete( 'revision', array( 'rev_id' => $row->rev_id ) );
				}
			}
			if ( !$fix ) {
				$this->output( "Run again with --fix to remove these entries automatically.\n" );
			}
		} else {
			$this->output( "No orphans! Yay!\n" );
		}

		if ( $fix ) {
			$dbw->unlockTables( __METHOD__ );
		}
	}

	/**
	 * @param $fix bool
	 * @todo DON'T USE THIS YET! It will remove entries which have children,
	 *       but which aren't properly attached (eg if page_latest is bogus
	 *       but valid revisions do exist)
	 */
	private function checkWidows( $fix ) {
		$dbw = wfGetDB( DB_MASTER );
		$page = $dbw->tableName( 'page' );
		$revision = $dbw->tableName( 'revision' );

		if ( $fix ) {
			$this->lockTables( $dbw );
		}

		$this->output( "\nChecking for childless page table entries... (this may take a while on a large wiki)\n" );
		$result = $dbw->query( "
			SELECT *
			FROM $page LEFT OUTER JOIN $revision ON page_latest=rev_id
			WHERE rev_id IS NULL
		" );
		$widows = $dbw->numRows( $result );
		if ( $widows > 0 ) {
			$this->output( "$widows childless pages...\n" );
			$this->output( sprintf( "%10s %11s %2s %s\n", 'page_id', 'page_latest', 'ns', 'page_title' ) );
			foreach ( $result as $row ) {
				printf( "%10d %11d %2d %s\n",
					$row->page_id,
					$row->page_latest,
					$row->page_namespace,
					$row->page_title );
				if ( $fix ) {
					$dbw->delete( 'page', array( 'page_id' => $row->page_id ) );
				}
			}
			if ( !$fix ) {
				$this->output( "Run again with --fix to remove these entries automatically.\n" );
			}
		} else {
			$this->output( "No childless pages! Yay!\n" );
		}

		if ( $fix ) {
			$dbw->unlockTables( __METHOD__ );
		}
	}

	/**
	 * Check for pages where page_latest is wrong
	 * @param $fix bool Whether to fix broken entries
	 */
	private function checkSeparation( $fix ) {
		$dbw = wfGetDB( DB_MASTER );
		$page     = $dbw->tableName( 'page' );
		$revision = $dbw->tableName( 'revision' );

		if ( $fix ) {
			$dbw->lockTables( $dbw, 'text', __METHOD__ );
		}

		$this->output( "\nChecking for pages whose page_latest links are incorrect... (this may take a while on a large wiki)\n" );
		$result = $dbw->query( "
			SELECT *
			FROM $page LEFT OUTER JOIN $revision ON page_latest=rev_id
		" );
		$found = 0;
		foreach ( $result as $row ) {
			$result2 = $dbw->query( "
				SELECT MAX(rev_timestamp) as max_timestamp
				FROM $revision
				WHERE rev_page=$row->page_id
			" );
			$row2 = $dbw->fetchObject( $result2 );
			if ( $row2 ) {
				if ( $row->rev_timestamp != $row2->max_timestamp ) {
					if ( $found == 0 ) {
						$this->output( sprintf( "%10s %10s %14s %14s\n",
							'page_id', 'rev_id', 'timestamp', 'max timestamp' ) );
					}
					++$found;
					$this->output( sprintf( "%10d %10d %14s %14s\n",
						$row->page_id,
						$row->page_latest,
						$row->rev_timestamp,
						$row2->max_timestamp ) );
					if ( $fix ) {
						# ...
						$maxId = $dbw->selectField(
							'revision',
							'rev_id',
							array(
								'rev_page'      => $row->page_id,
								'rev_timestamp' => $row2->max_timestamp ) );
						$this->output( "... updating to revision $maxId\n" );
						$maxRev = Revision::newFromId( $maxId );
						$title = Title::makeTitle( $row->page_namespace, $row->page_title );
						$article = new Article( $title );
						$article->updateRevisionOn( $dbw, $maxRev );
					}
				}
			} else {
				$this->output( "wtf\n" );
			}
		}

		if ( $found ) {
			$this->output( "Found $found pages with incorrect latest revision.\n" );
		} else {
			$this->output( "No pages with incorrect latest revision. Yay!\n" );
		}
		if ( !$fix && $found > 0 ) {
			$this->output( "Run again with --fix to remove these entries automatically.\n" );
		}

		if ( $fix ) {
			$dbw->unlockTables( __METHOD__ );
		}
	}
}

$maintClass = "Orphans";
require_once( DO_MAINTENANCE );
