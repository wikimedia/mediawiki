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
	/** @var bool|ResultWrapper */
	private static $row;

	/** @var bool */
	private static $loaded = false;

	/** @var int */
	private static $jobs;

	/** @var int[] */
	private static $pageCount = array();

	/** @var int[] */
	private static $groupMemberCounts = array();

	static function recache() {
		self::load( true );
	}

	/**
	 * @param bool $recache
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
	 * @return bool|ResultWrapper
	 */
	static function loadAndLazyInit() {
		global $wgMiserMode;

		wfDebug( __METHOD__ . ": reading site_stats from slave\n" );
		$row = self::doLoad( wfGetDB( DB_SLAVE ) );

		if ( !self::isSane( $row ) ) {
			// Might have just been initialized during this request? Underflow?
			wfDebug( __METHOD__ . ": site_stats damaged or missing on slave\n" );
			$row = self::doLoad( wfGetDB( DB_MASTER ) );
		}

		if ( !$wgMiserMode && !self::isSane( $row ) ) {
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
	 * @param DatabaseBase $db
	 * @return bool|ResultWrapper
	 */
	static function doLoad( $db ) {
		return $db->selectRow( 'site_stats', array(
				'ss_row_id',
				'ss_total_edits',
				'ss_good_articles',
				'ss_total_pages',
				'ss_users',
				'ss_active_users',
				'ss_images',
			), false, __METHOD__ );
	}

	/**
	 * Return the total number of page views. Except we don't track those anymore.
	 * Stop calling this function, it will be removed some time in the future. It's
	 * kept here simply to prevent fatal errors.
	 *
	 * @deprecated since 1.25
	 * @return int
	 */
	static function views() {
		wfDeprecated( __METHOD__, '1.25' );
		return 0;
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
	 * @param string $group Name of group
	 * @return int
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
			/**
			 * Zero rows still do single row read for row that doesn't exist,
			 * but people are annoyed by that
			 */
			if ( self::$jobs == 1 ) {
				self::$jobs = 0;
			}
		}
		return self::$jobs;
	}

	/**
	 * @param int $ns
	 *
	 * @return int
	 */
	static function pagesInNs( $ns ) {
		if ( !isset( self::$pageCount[$ns] ) ) {
			$dbr = wfGetDB( DB_SLAVE );
			self::$pageCount[$ns] = (int)$dbr->selectField(
				'page',
				'COUNT(*)',
				array( 'page_namespace' => $ns ),
				__METHOD__
			);
		}
		return self::$pageCount[$ns];
	}

	/**
	 * Is the provided row of site stats sane, or should it be regenerated?
	 *
	 * Checks only fields which are filled by SiteStatsInit::refresh.
	 *
	 * @param bool|object $row
	 *
	 * @return bool
	 */
	private static function isSane( $row ) {
		if ( $row === false
			|| $row->ss_total_pages < $row->ss_good_articles
			|| $row->ss_total_edits < $row->ss_total_pages
		) {
			return false;
		}
		// Now check for underflow/overflow
		foreach ( array(
			'ss_total_edits',
			'ss_good_articles',
			'ss_total_pages',
			'ss_users',
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
 * Class designed for counting of stats.
 */
class SiteStatsInit {

	// Database connection
	private $db;

	// Various stats
	private $mEdits = null, $mArticles = null, $mPages = null;
	private $mUsers = null, $mFiles = null;

	/**
	 * Constructor
	 * @param bool|DatabaseBase $database
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
	 * @return int
	 */
	public function edits() {
		$this->mEdits = $this->db->selectField( 'revision', 'COUNT(*)', '', __METHOD__ );
		$this->mEdits += $this->db->selectField( 'archive', 'COUNT(*)', '', __METHOD__ );
		return $this->mEdits;
	}

	/**
	 * Count pages in article space(s)
	 * @return int
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
	 * @return int
	 */
	public function pages() {
		$this->mPages = $this->db->selectField( 'page', 'COUNT(*)', '', __METHOD__ );
		return $this->mPages;
	}

	/**
	 * Count total users
	 * @return int
	 */
	public function users() {
		$this->mUsers = $this->db->selectField( 'user', 'COUNT(*)', '', __METHOD__ );
		return $this->mUsers;
	}

	/**
	 * Count total files
	 * @return int
	 */
	public function files() {
		$this->mFiles = $this->db->selectField( 'image', 'COUNT(*)', '', __METHOD__ );
		return $this->mFiles;
	}

	/**
	 * Do all updates and commit them. More or less a replacement
	 * for the original initStats, but without output.
	 *
	 * @param DatabaseBase|bool $database
	 * - Boolean: whether to use the master DB
	 * - DatabaseBase: database connection to use
	 * @param array $options Array of options, may contain the following values
	 * - activeUsers Boolean: whether to update the number of active users (default: false)
	 */
	public static function doAllAndCommit( $database, array $options = array() ) {
		$options += array( 'update' => false, 'activeUsers' => false );

		// Grab the object and count everything
		$counter = new SiteStatsInit( $database );

		$counter->edits();
		$counter->articles();
		$counter->pages();
		$counter->users();
		$counter->files();

		$counter->refresh();

		// Count active users if need be
		if ( $options['activeUsers'] ) {
			SiteStatsUpdate::cacheUpdate( wfGetDB( DB_MASTER ) );
		}
	}

	/**
	 * Refresh site_stats
	 */
	public function refresh() {
		$values = array(
			'ss_row_id' => 1,
			'ss_total_edits' => ( $this->mEdits === null ? $this->edits() : $this->mEdits ),
			'ss_good_articles' => ( $this->mArticles === null ? $this->articles() : $this->mArticles ),
			'ss_total_pages' => ( $this->mPages === null ? $this->pages() : $this->mPages ),
			'ss_users' => ( $this->mUsers === null ? $this->users() : $this->mUsers ),
			'ss_images' => ( $this->mFiles === null ? $this->files() : $this->mFiles ),
		);

		$dbw = wfGetDB( DB_MASTER );
		$dbw->upsert( 'site_stats', $values, array( 'ss_row_id' ), $values, __METHOD__ );
	}
}
