<?php
/**
 * See deferred.txt
 *
 * @package MediaWiki
 */

/**
 *
 * @package MediaWiki
 */
class SiteStatsUpdate {

	var $mViews, $mEdits, $mGood, $mPages, $mUsers;

	function SiteStatsUpdate( $views, $edits, $good, $pages = 0, $users = 0 ) {
		$this->mViews = $views;
		$this->mEdits = $edits;
		$this->mGood = $good;
		$this->mPages = $pages;
		$this->mUsers = $users;
	}

	function appendUpdate( &$sql, $field, $delta ) {
		if ( $delta ) {
			if ( $sql ) {
				$sql .= ',';
			}
			if ( $delta < 0 ) {
				$sql .= "$field=$field-1";
			} else {
				$sql .= "$field=$field+1";
			}
		}
	}

	function doUpdate() {
		$fname = 'SiteStatsUpdate::doUpdate';
		$dbw =& wfGetDB( DB_MASTER );

		# First retrieve the row just to find out which schema we're in
		$row = $dbw->selectRow( 'site_stats', '*', false, $fname );

		$updates = '';

		$this->appendUpdate( $updates, 'ss_total_views', $this->mViews );
		$this->appendUpdate( $updates, 'ss_total_edits', $this->mEdits );
		$this->appendUpdate( $updates, 'ss_good_articles', $this->mGood );

		if ( isset( $row->ss_total_pages ) ) {
			# Update schema if required
			if ( $row->ss_total_pages == -1 && !$this->mViews ) {
				$dbr =& wfGetDB( DB_SLAVE, array( 'SpecialStatistics', 'vslow') );
				extract( $dbr->tableNames( 'page', 'user' ) );

				$sql = "SELECT COUNT(page_namespace) AS total FROM $page";
				$res = $dbr->query( $sql, $fname );
				$pageRow = $dbr->fetchObject( $res );
				$pages = $pageRow->total + $this->mPages;

				$sql = "SELECT COUNT(user_id) AS total FROM $user";
				$res = $dbr->query( $sql, $fname );
				$userRow = $dbr->fetchObject( $res );
				$users = $userRow->total + $this->mUsers;

				if ( $updates ) {
					$updates .= ',';
				}
				$updates .= "ss_total_pages=$pages, ss_users=$users";
			} else {
				$this->appendUpdate( $updates, 'ss_total_pages', $this->mPages );
				$this->appendUpdate( $updates, 'ss_users', $this->mUsers );
			}
		}
		if ( $updates ) {
			$site_stats = $dbw->tableName( 'site_stats' );
			$sql = $dbw->limitResultForUpdate("UPDATE $site_stats SET $updates", 1);
			$dbw->query( $sql, $fname );
		}
	}
}
?>
