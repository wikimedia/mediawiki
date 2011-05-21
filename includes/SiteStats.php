<?php

/**
 * Static accessor class for site_stats and related things
 */
class SiteStats {
	static $row, $loaded = false;
	static $admins, $jobs;
	static $pageCount = array();
	static $groupMemberCounts = array();

	static function recache() {
		self::load( true );
	}

	/**
	 * @param $recache bool
	 */
	static function load( $recache = false ) {
		if ( self::$loaded && !$recache ) {
			return;
		}

		self::$row = self::loadAndLazyInit();

		# This code is somewhat schema-agnostic, because I'm changing it in a minor release -- TS
		if ( !isset( self::$row->ss_total_pages ) && self::$row->ss_total_pages == -1 ) {
			# Update schema
			$u = new SiteStatsUpdate( 0, 0, 0 );
			$u->doUpdate();
			$dbr = wfGetDB( DB_SLAVE );
			self::$row = $dbr->selectRow( 'site_stats', '*', false, __METHOD__ );
		}

		self::$loaded = true;
	}

	/**
	 * @return Bool|ResultWrapper
	 */
	static function loadAndLazyInit() {
		wfDebug( __METHOD__ . ": reading site_stats from slave\n" );
		$row = self::doLoad( wfGetDB( DB_SLAVE ) );

		if( !self::isSane( $row ) ) {
			// Might have just been initialized during this request? Underflow?
			wfDebug( __METHOD__ . ": site_stats damaged or missing on slave\n" );
			$row = self::doLoad( wfGetDB( DB_MASTER ) );
		}

		if( !self::isSane( $row ) ) {
			// Normally the site_stats table is initialized at install time.
			// Some manual construction scenarios may leave the table empty or
			// broken, however, for instance when importing from a dump into a
			// clean schema with mwdumper.
			wfDebug( __METHOD__ . ": initializing damaged or missing site_stats\n" );

			SiteStatsInit::doAllAndCommit( false );

			$row = self::doLoad( wfGetDB( DB_MASTER ) );
		}

		if( !self::isSane( $row ) ) {
			wfDebug( __METHOD__ . ": site_stats persistently nonsensical o_O\n" );
		}
		return $row;
	}

	/**
	 * @param $db DatabaseBase
	 * @return Bool|ResultWrapper
	 */
	static function doLoad( $db ) {
		return $db->selectRow( 'site_stats', '*', false, __METHOD__ );
	}

	/**
	 * @return int
	 */
	static function views() {
		self::load();
		return self::$row->ss_total_views;
	}

	/**
	 * @return int
	 */
	static function edits() {
		self::load();
		return self::$row->ss_total_edits;
	}

	/**
	 * @return int
	 */
	static function articles() {
		self::load();
		return self::$row->ss_good_articles;
	}

	/**
	 * @return int
	 */
	static function pages() {
		self::load();
		return self::$row->ss_total_pages;
	}

	/**
	 * @return int
	 */
	static function users() {
		self::load();
		return self::$row->ss_users;
	}

	/**
	 * @return int
	 */
	static function activeUsers() {
		self::load();
		return self::$row->ss_active_users;
	}

	/**
	 * @return int
	 */
	static function images() {
		self::load();
		return self::$row->ss_images;
	}

	/**
	 * Find the number of users in a given user group.
	 * @param $group String: name of group
	 * @return Integer
	 */
	static function numberingroup( $group ) {
		if ( !isset( self::$groupMemberCounts[$group] ) ) {
			global $wgMemc;
			$key = wfMemcKey( 'SiteStats', 'groupcounts', $group );
			$hit = $wgMemc->get( $key );
			if ( !$hit ) {
				$dbr = wfGetDB( DB_SLAVE );
				$hit = $dbr->selectField(
					'user_groups',
					'COUNT(*)',
					array( 'ug_group' => $group ),
					__METHOD__
				);
				$wgMemc->set( $key, $hit, 3600 );
			}
			self::$groupMemberCounts[$group] = $hit;
		}
		return self::$groupMemberCounts[$group];
	}

	/**
	 * @return int
	 */
	static function jobs() {
		if ( !isset( self::$jobs ) ) {
			$dbr = wfGetDB( DB_SLAVE );
			self::$jobs = $dbr->estimateRowCount( 'job' );
			/* Zero rows still do single row read for row that doesn't exist, but people are annoyed by that */
			if ( self::$jobs == 1 ) {
				self::$jobs = 0;
			}
		}
		return self::$jobs;
	}

	/**
	 * @param $ns int
	 *
	 * @return int
	 */
	static function pagesInNs( $ns ) {
		wfProfileIn( __METHOD__ );
		if( !isset( self::$pageCount[$ns] ) ) {
			$dbr = wfGetDB( DB_SLAVE );
			$pageCount[$ns] = (int)$dbr->selectField(
				'page',
				'COUNT(*)',
				array( 'page_namespace' => $ns ),
				__METHOD__
			);
		}
		wfProfileOut( __METHOD__ );
		return $pageCount[$ns];
	}

	/**
	 * Is the provided row of site stats sane, or should it be regenerated?
	 *
	 * @param $row
	 *
	 * @return bool
	 */
	private static function isSane( $row ) {
		if(
			$row === false
			|| $row->ss_total_pages < $row->ss_good_articles
			|| $row->ss_total_edits < $row->ss_total_pages
		) {
			return false;
		}
		// Now check for underflow/overflow
		foreach( array( 'total_views', 'total_edits', 'good_articles',
		'total_pages', 'users', 'admins', 'images' ) as $member ) {
			if(
				$row->{"ss_$member"} > 2000000000
				|| $row->{"ss_$member"} < 0
			) {
				return false;
			}
		}
		return true;
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

	/**
	 * @param $sql
	 * @param $field
	 * @param $delta
	 */
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
		$dbw = wfGetDB( DB_MASTER );

		$updates = '';

		$this->appendUpdate( $updates, 'ss_total_views', $this->mViews );
		$this->appendUpdate( $updates, 'ss_total_edits', $this->mEdits );
		$this->appendUpdate( $updates, 'ss_good_articles', $this->mGood );
		$this->appendUpdate( $updates, 'ss_total_pages', $this->mPages );
		$this->appendUpdate( $updates, 'ss_users', $this->mUsers );

		if ( $updates ) {
			$site_stats = $dbw->tableName( 'site_stats' );
			$sql = "UPDATE $site_stats SET $updates";

			# Need a separate transaction because this a global lock
			$dbw->begin();
			$dbw->query( $sql, __METHOD__ );
			$dbw->commit();
		}
	}

	/**
	 * @param $dbw DatabaseBase
	 * @return bool|mixed
	 */
	public static function cacheUpdate( $dbw ) {
		global $wgActiveUserDays;
		$dbr = wfGetDB( DB_SLAVE, array( 'SpecialStatistics', 'vslow' ) );
		# Get non-bot users than did some recent action other than making accounts.
		# If account creation is included, the number gets inflated ~20+ fold on enwiki.
		$activeUsers = $dbr->selectField(
			'recentchanges',
			'COUNT( DISTINCT rc_user_text )',
			array(
				'rc_user != 0',
				'rc_bot' => 0,
				"rc_log_type != 'newusers' OR rc_log_type IS NULL",
				"rc_timestamp >= '{$dbw->timestamp( wfTimestamp( TS_UNIX ) - $wgActiveUserDays*24*3600 )}'",
			),
			__METHOD__
		);
		$dbw->update(
			'site_stats',
			array( 'ss_active_users' => intval( $activeUsers ) ),
			array( 'ss_row_id' => 1 ),
			__METHOD__
		);
		return $activeUsers;
	}
}

/**
 * Class designed for counting of stats.
 */
class SiteStatsInit {

	// Database connection
	private $db;

	// Various stats
	private $mEdits, $mArticles, $mPages, $mUsers, $mViews, $mFiles = 0;

	/**
	 * Constructor
	 * @param $useMaster Boolean: whether to use the master DB
	 */
	public function __construct( $useMaster = false ) {
		$this->db = wfGetDB( $useMaster ? DB_MASTER : DB_SLAVE );
	}

	/**
	 * Count the total number of edits
	 * @return Integer
	 */
	public function edits() {
		$this->mEdits = $this->db->selectField( 'revision', 'COUNT(*)', '', __METHOD__ );
		$this->mEdits += $this->db->selectField( 'archive', 'COUNT(*)', '', __METHOD__ );
		return $this->mEdits;
	}

	/**
	 * Count pages in article space(s)
	 * @return Integer
	 */
	public function articles() {
		global $wgArticleCountMethod;

		$tables = array( 'page' );
		$conds = array(
			'page_namespace' => MWNamespace::getContentNamespaces(),
			'page_is_redirect' => 0,
		);

		if ( $wgArticleCountMethod == 'link' ) {
			$tables[] = 'pagelinks';
			$conds[] = 'pl_from=page_id';
		} elseif ( $wgArticleCountMethod == 'comma' ) {
			// To make a correct check for this, we would need, for each page,
			// to load the text, maybe uncompress it, maybe decode it and then
			// check if there's one comma.
			// But one thing we are sure is that if the page is empty, it can't
			// contain a comma :)
			$conds[] = 'page_len > 0';
		}

		$this->mArticles = $this->db->selectField( $tables, 'COUNT(DISTINCT page_id)',
			$conds, __METHOD__ );
		return $this->mArticles;
	}

	/**
	 * Count total pages
	 * @return Integer
	 */
	public function pages() {
		$this->mPages = $this->db->selectField( 'page', 'COUNT(*)', '', __METHOD__ );
		return $this->mPages;
	}
	
	/**
	 * Count total users
	 * @return Integer
	 */
	public function users() {
		$this->mUsers = $this->db->selectField( 'user', 'COUNT(*)', '', __METHOD__ );
		return $this->mUsers;
	}
	
	/**
	 * Count views
	 * @return Integer
	 */
	public function views() {
		$this->mViews = $this->db->selectField( 'page', 'SUM(page_counter)', '', __METHOD__ );
		return $this->mViews;
	}

	/**
	 * Count total files
	 * @return Integer
	 */
	public function files() {
		$this->mFiles = $this->db->selectField( 'image', 'COUNT(*)', '', __METHOD__ );
		return $this->mFiles;
	}

	/**
	 * Do all updates and commit them. More or less a replacement
	 * for the original initStats, but without the calls to wfOut()
	 * @param $update Boolean: whether to update the current stats or write fresh
	 * @param $noViews Boolean: when true, do not update the number of page views
	 * @param $activeUsers Boolean: whether to update the number of active users
	 */
	public static function doAllAndCommit( $update, $noViews = false, $activeUsers = false ) {
		// Grab the object and count everything
		$counter = new SiteStatsInit( false );
		$counter->edits();
		$counter->articles();
		$counter->pages();
		$counter->users();
		$counter->files();

		// Only do views if we don't want to not count them
		if( !$noViews ) {
			$counter->views();
		}

		// Update/refresh
		if( $update ) {
			$counter->update();
		} else {
			$counter->refresh();
		}

		// Count active users if need be
		if( $activeUsers ) {
			SiteStatsUpdate::cacheUpdate( wfGetDB( DB_MASTER ) );
		}
	}

	/**
	 * Update the current row with the selected values
	 */
	public function update() {
		list( $values, $conds ) = $this->getDbParams();
		$dbw = wfGetDB( DB_MASTER );
		$dbw->update( 'site_stats', $values, $conds, __METHOD__ );
	}

	/**
	 * Refresh site_stats. Erase the current record and save all
	 * the new values.
	 */
	public function refresh() {
		list( $values, $conds, $views ) = $this->getDbParams();
		$dbw = wfGetDB( DB_MASTER );
		$dbw->delete( 'site_stats', $conds, __METHOD__ );
		$dbw->insert( 'site_stats', array_merge( $values, $conds, $views ), __METHOD__ );
	}

	/**
	 * Return three arrays of params for the db queries
	 * @return Array
	 */
	private function getDbParams() {
		$values = array(
			'ss_total_edits' => $this->mEdits,
			'ss_good_articles' => $this->mArticles,
			'ss_total_pages' => $this->mPages,
			'ss_users' => $this->mUsers,
			'ss_images' => $this->mFiles
		);
		$conds = array( 'ss_row_id' => 1 );
		$views = array( 'ss_total_views' => $this->mViews );
		return array( $values, $conds, $views );
	}
}
