<?php

/**
 * Remove pages with only 1 revision from the MediaWiki namespace, without
 * flooding recent changes, delete logs, etc.
 * Irreversible (can't use standard undelete) and does not update link tables
 *
 * This is mainly useful to run before maintenance/update.php when upgrading
 * to 1.9, to prevent flooding recent changes/deletion logs.  It's intended
 * to be conservative, so it's possible that a few entries will be left for
 * deletion by the upgrade script.  It's also possible that it hasn't been
 * tested thouroughly enough, and will delete something it shouldn't; so
 * back up your DB if there's anything in the MediaWiki that is important to
 * you.
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
 * @ingroup Maintenance
 * @author Steve Sanbeg
 * based on nukePage by Rob Church
 */

require_once( "Maintenance.php" );

class NukeNS extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Remove pages with only 1 revision from any namespace";
		$this->addOption( 'delete', "Actually delete the page" );
		$this->addOption( 'ns', 'Namespace to delete from, default NS_MEDIAWIKI', false, true );
	}

	public function execute() {
		$ns = $this->getOption( 'ns', NS_MEDIAWIKI );
		$delete = $this->getOption( 'delete', false );
		$dbw = wfGetDB( DB_MASTER );
		$dbw->begin();

		$tbl_pag = $dbw->tableName( 'page' );
		$tbl_rev = $dbw->tableName( 'revision' );
		$res = $dbw->query( "SELECT page_title FROM $tbl_pag WHERE page_namespace = $ns" );

		$n_deleted = 0;

		while( $row = $dbw->fetchObject( $res ) ) {
			//echo "$ns_name:".$row->page_title, "\n";
			$title = Title::newFromText($row->page_title, $ns);
			$id   = $title->getArticleID();

			// Get corresponding revisions
			$res2 = $dbw->query( "SELECT rev_id FROM $tbl_rev WHERE rev_page = $id" );
			$revs = array();

			while( $row2 = $dbw->fetchObject( $res2 ) ) {
				$revs[] = $row2->rev_id;
			}
			$count = count( $revs );

			//skip anything that looks modified (i.e. multiple revs)
			if (($count == 1)) {
				#echo $title->getPrefixedText(), "\t", $count, "\n";
				$this->output( "delete: ", $title->getPrefixedText(), "\n" );

				//as much as I hate to cut & paste this, it's a little different, and
				//I already have the id & revs
				if( $delete ) {
					$dbw->query( "DELETE FROM $tbl_pag WHERE page_id = $id" );
					$dbw->commit();
					// Delete revisions as appropriate
					$child = $this->spawnChild( 'NukePage', 'NukePage.php' );
					$child->deleteRevisions( $revs );
					$this->purgeRedundantText( true );
					$n_deleted ++;
				}
			} else {
			  $this->output( "skip: ", $title->getPrefixedText(), "\n" );
			}
		}
		$dbw->commit();

		if ($n_deleted > 0) {
		#update statistics - better to decrement existing count, or just count
		#the page table?
		$pages = $dbw->selectField('site_stats', 'ss_total_pages');
		$pages -= $n_deleted;
		$dbw->update( 'site_stats', 
			  array('ss_total_pages' => $pages ), 
			  array( 'ss_row_id' => 1),
			  __METHOD__ );
		}
	
		if (!$delete) {
			$this->output( "To update the database, run the script with the --delete option.\n" );
		}
	}
}

$maintClass = "NukeNS";
require_once( DO_MAINTENANCE );
