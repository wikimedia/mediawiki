<?php

/**
 * Static accessor class for site_stats and related things
 */
class SiteStats {
	static $row, $loaded = false;
	static $admins;
	static $pageCount = array();

	static function recache() {
		self::load( true );
	}

	static function load( $recache = false ) {
		if ( self::$loaded && !$recache ) {
			return;
		}

		$dbr =& wfGetDB( DB_SLAVE );
		self::$row = $dbr->selectRow( 'site_stats', '*', false, __METHOD__ );

		# This code is somewhat schema-agnostic, because I'm changing it in a minor release -- TS
		if ( !isset( self::$row->ss_total_pages ) && self::$row->ss_total_pages == -1 ) {
			# Update schema
			$u = new SiteStatsUpdate( 0, 0, 0 );
			$u->doUpdate();
			self::$row = $dbr->selectRow( 'site_stats', '*', false, __METHOD__ );
		}
	}

	static function views() {
		self::load();
		return self::$row->ss_total_views;
	}

	static function edits() {
		self::load();
		return self::$row->ss_total_edits;
	}

	static function articles() {
		self::load();
		return self::$row->ss_good_articles;
	}

	static function pages() {
		self::load();
		return self::$row->ss_total_pages;
	}

	static function users() {
		self::load();
		return self::$row->ss_users;
	}
	
	static function images() {
		self::load();
		return self::$row->ss_images;
	}

	static function admins() {
		if ( !isset( self::$admins ) ) {
			$dbr =& wfGetDB( DB_SLAVE );
			self::$admins = $dbr->selectField( 'user_groups', 'COUNT(*)', array( 'ug_group' => 'sysop' ), __METHOD__ );
		}
		return self::$admins;
	}

	static function pagesInNs( $ns ) {
		wfProfileIn( __METHOD__ );
		if( !isset( self::$pageCount[$ns] ) ) {
			$dbr =& wfGetDB( DB_SLAVE );
			$pageCount[$ns] = (int)$dbr->selectField( 'page', 'COUNT(*)', array( 'page_namespace' => $ns ), __METHOD__ );
		}
		wfProfileOut( __METHOD__ );
		return $pageCount[$ns];
	}

}


/**
 *
 */
class SiteStatsUpdate {

	var $mViews, $mEdits, $mGood, $mPages, $mUsers;

	function __construct( $views, $edits, $good, $pages = 0, $users = 0 ) {
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
				list( $page, $user ) = $dbr->tableNamesN( 'page', 'user' );

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
			$dbw->begin();
			$dbw->query( $sql, $fname );
			$dbw->commit();
		}

		/*
		global $wgDBname, $wgTitle;
		if ( $this->mGood && $wgDBname == 'enwiki' ) {
			$good = $dbw->selectField( 'site_stats', 'ss_good_articles', '', $fname );
			error_log( $good . ' ' . $wgTitle->getPrefixedDBkey() . "\n", 3, '/home/wikipedia/logs/million.log' );
		}
		*/
	}
}
?>
