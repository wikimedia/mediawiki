<?php
/**
 * Accessors and mutators for the site-wide statistics.
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
 */

/**
 * Static accessor class for site_stats and related things
 */
class SiteStats {
	static $row, $loaded = false;
	static $jobs;
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
			self::$row = self::doLoad( wfGetDB( DB_SLAVE ) );
		}

		self::$loaded = true;
	}

	/**
	 * @return Bool|ResultWrapper
	 */
	static function loadAndLazyInit() {
		wfDebug( __METHOD__ . ": reading site_stats from slave\n" );
		$row = self::doLoad( wfGetDB( DB_SLAVE ) );

		if ( !self::isSane( $row ) ) {
			// Might have just been initialized during this request? Underflow?
			wfDebug( __METHOD__ . ": site_stats damaged or missing on slave\n" );
			$row = self::doLoad( wfGetDB( DB_MASTER ) );
		}

		if ( !self::isSane( $row ) ) {
			// Normally the site_stats table is initialized at install time.
			// Some manual construction scenarios may leave the table empty or
			// broken, however, for instance when importing from a dump into a
			// clean schema with mwdumper.
			wfDebug( __METHOD__ . ": initializing damaged or missing site_stats\n" );

			SiteStatsInit::doAllAndCommit( wfGetDB( DB_SLAVE ) );

			$row = self::doLoad( wfGetDB( DB_MASTER ) );
		}

		if ( !self::isSane( $row ) ) {
			wfDebug( __METHOD__ . ": site_stats persistently nonsensical o_O\n" );
		}
		return $row;
	}

	/**
	 * @param $db DatabaseBase
	 * @return Bool|ResultWrapper
	 */
	static function doLoad( $db ) {
		return $db->selectRow( 'site_stats', array(
				'ss_row_id',
				'ss_total_views',
				'ss_total_edits',
				'ss_good_articles',
				'ss_total_pages',
				'ss_users',
				'ss_active_users',
				'ss_images',
			), false, __METHOD__ );
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
	 * @param string $group name of group
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
			self::$jobs = array_sum( JobQueueGroup::singleton()->getQueueSizes() );
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
		if ( !isset( self::$pageCount[$ns] ) ) {
			$dbr = wfGetDB( DB_SLAVE );
			self::$pageCount[$ns] = (int)$dbr->selectField(
				'page',
				'COUNT(*)',
				array( 'page_namespace' => $ns ),
				__METHOD__
			);
		}
		wfProfileOut( __METHOD__ );
		return self::$pageCount[$ns];
	}

	/**
	 * Is the provided row of site stats sane, or should it be regenerated?
	 *
	 * @param $row
	 *
	 * @return bool
	 */
	private static function isSane( $row ) {
		if ( $row === false
			|| $row->ss_total_pages < $row->ss_good_articles
			|| $row->ss_total_edits < $row->ss_total_pages
			|| $row->ss_users < $row->ss_active_users
		) {
			return false;
		}
		// Now check for underflow/overflow
		foreach ( array(
			'ss_total_views',
			'ss_total_edits',
			'ss_good_articles',
			'ss_total_pages',
			'ss_users',
			'ss_active_users',
			'ss_images',
		) as $member ) {
			if ( $row->$member > 2000000000 || $row->$member < 0 ) {
				return false;
			}
		}
		return true;
	}
}

/**
 * Class for handling updates to the site_stats table
 */
class SiteStatsUpdate implements DeferrableUpdate {
	protected $views = 0;
	protected $edits = 0;
	protected $pages = 0;
	protected $articles = 0;
	protected $users = 0;
	protected $images = 0;

	// @todo deprecate this constructor
	function __construct( $views, $edits, $good, $pages = 0, $users = 0 ) {
		$this->views = $views;
		$this->edits = $edits;
		$this->articles = $good;
		$this->pages = $pages;
		$this->users = $users;
	}

	/**
	 * @param $deltas Array
	 * @return SiteStatsUpdate
	 */
	public static function factory( array $deltas ) {
		$update = new self( 0, 0, 0 );

		$fields = array( 'views', 'edits', 'pages', 'articles', 'users', 'images' );
		foreach ( $fields as $field ) {
			if ( isset( $deltas[$field] ) && $deltas[$field] ) {
				$update->$field = $deltas[$field];
			}
		}

		return $update;
	}

	public function doUpdate() {
		global $wgSiteStatsAsyncFactor;

		$rate = $wgSiteStatsAsyncFactor; // convenience
		// If set to do so, only do actual DB updates 1 every $rate times.
		// The other times, just update "pending delta" values in memcached.
		if ( $rate && ( $rate < 0 || mt_rand( 0, $rate - 1 ) != 0 ) ) {
			$this->doUpdatePendingDeltas();
		} else {
			// Need a separate transaction because this a global lock
			wfGetDB( DB_MASTER )->onTransactionIdle( array( $this, 'tryDBUpdateInternal' ) );
		}
	}

	/**
	 * Do not call this outside of SiteStatsUpdate
	 *
	 * @return void
	 */
	public function tryDBUpdateInternal() {
		global $wgSiteStatsAsyncFactor;

		$dbw = wfGetDB( DB_MASTER );
		$lockKey = wfMemcKey( 'site_stats' ); // prepend wiki ID
		if ( $wgSiteStatsAsyncFactor ) {
			// Lock the table so we don't have double DB/memcached updates
			if ( !$dbw->lockIsFree( $lockKey, __METHOD__ )
				|| !$dbw->lock( $lockKey, __METHOD__, 1 ) // 1 sec timeout
			) {
				$this->doUpdatePendingDeltas();
				return;
			}
			$pd = $this->getPendingDeltas();
			// Piggy-back the async deltas onto those of this stats update....
			$this->views += ( $pd['ss_total_views']['+'] - $pd['ss_total_views']['-'] );
			$this->edits += ( $pd['ss_total_edits']['+'] - $pd['ss_total_edits']['-'] );
			$this->articles += ( $pd['ss_good_articles']['+'] - $pd['ss_good_articles']['-'] );
			$this->pages += ( $pd['ss_total_pages']['+'] - $pd['ss_total_pages']['-'] );
			$this->users += ( $pd['ss_users']['+'] - $pd['ss_users']['-'] );
			$this->images += ( $pd['ss_images']['+'] - $pd['ss_images']['-'] );
		}

		// Build up an SQL query of deltas and apply them...
		$updates = '';
		$this->appendUpdate( $updates, 'ss_total_views', $this->views );
		$this->appendUpdate( $updates, 'ss_total_edits', $this->edits );
		$this->appendUpdate( $updates, 'ss_good_articles', $this->articles );
		$this->appendUpdate( $updates, 'ss_total_pages', $this->pages );
		$this->appendUpdate( $updates, 'ss_users', $this->users );
		$this->appendUpdate( $updates, 'ss_images', $this->images );
		if ( $updates != '' ) {
			$dbw->update( 'site_stats', array( $updates ), array(), __METHOD__ );
		}

		if ( $wgSiteStatsAsyncFactor ) {
			// Decrement the async deltas now that we applied them
			$this->removePendingDeltas( $pd );
			// Commit the updates and unlock the table
			$dbw->unlock( $lockKey, __METHOD__ );
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
				'rc_log_type != ' . $dbr->addQuotes( 'newusers' ) . ' OR rc_log_type IS NULL',
				'rc_timestamp >= ' . $dbr->addQuotes( $dbr->timestamp( wfTimestamp( TS_UNIX ) - $wgActiveUserDays * 24 * 3600 ) ),
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

	protected function doUpdatePendingDeltas() {
		$this->adjustPending( 'ss_total_views', $this->views );
		$this->adjustPending( 'ss_total_edits', $this->edits );
		$this->adjustPending( 'ss_good_articles', $this->articles );
		$this->adjustPending( 'ss_total_pages', $this->pages );
		$this->adjustPending( 'ss_users', $this->users );
		$this->adjustPending( 'ss_images', $this->images );
	}

	/**
	 * @param $sql string
	 * @param $field string
	 * @param $delta integer
	 */
	protected function appendUpdate( &$sql, $field, $delta ) {
		if ( $delta ) {
			if ( $sql ) {
				$sql .= ',';
			}
			if ( $delta < 0 ) {
				$sql .= "$field=$field-" . abs( $delta );
			} else {
				$sql .= "$field=$field+" . abs( $delta );
			}
		}
	}

	/**
	 * @param $type string
	 * @param string $sign ('+' or '-')
	 * @return string
	 */
	private function getTypeCacheKey( $type, $sign ) {
		return wfMemcKey( 'sitestatsupdate', 'pendingdelta', $type, $sign );
	}

	/**
	 * Adjust the pending deltas for a stat type.
	 * Each stat type has two pending counters, one for increments and decrements
	 * @param $type string
	 * @param $delta integer Delta (positive or negative)
	 * @return void
	 */
	protected function adjustPending( $type, $delta ) {
		global $wgMemc;

		if ( $delta < 0 ) { // decrement
			$key = $this->getTypeCacheKey( $type, '-' );
		} else { // increment
			$key = $this->getTypeCacheKey( $type, '+' );
		}

		$magnitude = abs( $delta );
		if ( !$wgMemc->incr( $key, $magnitude ) ) { // not there?
			if ( !$wgMemc->add( $key, $magnitude ) ) { // race?
				$wgMemc->incr( $key, $magnitude );
			}
		}
	}

	/**
	 * Get pending delta counters for each stat type
	 * @return Array Positive and negative deltas for each type
	 * @return void
	 */
	protected function getPendingDeltas() {
		global $wgMemc;

		$pending = array();
		foreach ( array( 'ss_total_views', 'ss_total_edits',
			'ss_good_articles', 'ss_total_pages', 'ss_users', 'ss_images' ) as $type )
		{
			// Get pending increments and pending decrements
			$pending[$type]['+'] = (int)$wgMemc->get( $this->getTypeCacheKey( $type, '+' ) );
			$pending[$type]['-'] = (int)$wgMemc->get( $this->getTypeCacheKey( $type, '-' ) );
		}

		return $pending;
	}

	/**
	 * Reduce pending delta counters after updates have been applied
	 * @param array $pd Result of getPendingDeltas(), used for DB update
	 * @return void
	 */
	protected function removePendingDeltas( array $pd ) {
		global $wgMemc;

		foreach ( $pd as $type => $deltas ) {
			foreach ( $deltas as $sign => $magnitude ) {
				// Lower the pending counter now that we applied these changes
				$wgMemc->decr( $this->getTypeCacheKey( $type, $sign ), $magnitude );
			}
		}
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
	 * @param $database Boolean or DatabaseBase:
	 * - Boolean: whether to use the master DB
	 * - DatabaseBase: database connection to use
	 */
	public function __construct( $database = false ) {
		if ( $database instanceof DatabaseBase ) {
			$this->db = $database;
		} else {
			$this->db = wfGetDB( $database ? DB_MASTER : DB_SLAVE );
		}
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
	 * for the original initStats, but without output.
	 *
	 * @param $database DatabaseBase|bool
	 * - Boolean: whether to use the master DB
	 * - DatabaseBase: database connection to use
	 * @param array $options of options, may contain the following values
	 * - update Boolean: whether to update the current stats (true) or write fresh (false) (default: false)
	 * - views Boolean: when true, do not update the number of page views (default: true)
	 * - activeUsers Boolean: whether to update the number of active users (default: false)
	 */
	public static function doAllAndCommit( $database, array $options = array() ) {
		$options += array( 'update' => false, 'views' => true, 'activeUsers' => false );

		// Grab the object and count everything
		$counter = new SiteStatsInit( $database );

		$counter->edits();
		$counter->articles();
		$counter->pages();
		$counter->users();
		$counter->files();

		// Only do views if we don't want to not count them
		if ( $options['views'] ) {
			$counter->views();
		}

		// Update/refresh
		if ( $options['update'] ) {
			$counter->update();
		} else {
			$counter->refresh();
		}

		// Count active users if need be
		if ( $options['activeUsers'] ) {
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
