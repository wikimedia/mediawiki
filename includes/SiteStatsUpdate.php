<?php
/**
 * See deferred.doc
 *
 * @package MediaWiki
 */

/**
 *
 * @package MediaWiki
 */
class SiteStatsUpdate {

	var $mViews, $mEdits, $mGood, $mPages, $mUsers, $mAdmins;

	function SiteStatsUpdate( $views, $edits, $good, $pages = 0, $users = 0, $admins = 0 ) {
		$this->mViews = $views;
		$this->mEdits = $edits;
		$this->mGood = $good;
		$this->mPages = $pages;
		$this->mUsers = $users;
		$this->mAdmins = $admins;
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

	function getAdminCount( $db = false ) {
		global $wgDBname;
		$fname = 'SiteStatsUpdate::getAdminCount';
		
		if ( $db === false ) {
			$db = $wgDBname;
		}
		$dbr =& wfGetDB( DB_SLAVE );
		extract( $dbr->tableNames( 'user_rights', 'site_stats' ) );

		$sql = "SELECT COUNT(ur_user) AS total FROM `$db`.$user_rights WHERE ur_rights LIKE '%sysop%'";
		$res = $dbr->query( $sql, $fname );
		$count = false;
		if ( $res ) {
			$row = $dbr->fetchObject( $res );
			if ( $row ) {
				$count = $row->total;
			}
		}
		return $count;
	}
		

	function /*static*/ updateAdminCount( $delta, $db = false ) {
		global $wgDBname;
		$fname = 'SiteStatsUpdate::updateAdminCount';

		if ( $db === false ) {
			$db = $wgDBname;
		}

		$dbw =& wfGetDB( DB_MASTER );
		extract( $dbw->tableNames( 'site_stats' ) );

		$delta = intval( $delta );
		
		$sql = "UPDATE `$db`.$site_stats SET ss_admins=ss_admins+$delta";
		$dbw->query( $sql, $fname, true );
	}
	
	function doUpdate() {
		global $wgDBname;
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
				extract( $dbr->tableNames( 'cur', 'user' ) );

				$sql = "SELECT COUNT(cur_namespace) AS total FROM $cur";
				$res = $dbr->query( $sql, $fname );
				$curRow = $dbr->fetchObject( $res );
				$pages = $curRow->total + $this->mPages;

				$sql = "SELECT COUNT(user_id) AS total FROM $user";
				$res = $dbr->query( $sql, $fname );
				$userRow = $dbr->fetchObject( $res );
				$users = $userRow->total + $this->mUsers;
				
				$admins = SiteStatsUpdate::getAdminCount();

				if ( $updates ) {
					$updates .= ',';
				}
				$updates .= "ss_total_pages=$pages, ss_users=$users, ss_admins=$admins";
			} else {	
				$this->appendUpdate( $updates, 'ss_total_pages', $this->mPages );
				$this->appendUpdate( $updates, 'ss_users', $this->mUsers );
				$this->appendUpdate( $updates, 'ss_admins', $this->mAdmins );
			}
		}
		if ( $updates ) {
			$site_stats = $dbw->tableName( 'site_stats' );
			$sql = "UPDATE $site_stats SET $updates LIMIT 1";
			$dbw->query( $sql, $fname );
		}
	}
}

?>
