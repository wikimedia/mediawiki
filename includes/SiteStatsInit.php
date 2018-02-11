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
 * @file
 */
use Wikimedia\Rdbms\IDatabase;

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
	 * @param bool|IDatabase $database
	 * - bool: Whether to use the master DB
	 * - IDatabase: Database connection to use
	 */
	public function __construct( $database = false ) {
		if ( $database instanceof IDatabase ) {
			$this->db = $database;
		} elseif ( $database ) {
			$this->db = wfGetDB( DB_MASTER );
		} else {
			$this->db = wfGetDB( DB_REPLICA, 'vslow' );
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

		$tables = [ 'page' ];
		$conds = [
			'page_namespace' => MWNamespace::getContentNamespaces(),
			'page_is_redirect' => 0,
		];

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
	 * @param IDatabase|bool $database
	 * - bool: Whether to use the master DB
	 * - IDatabase: Database connection to use
	 * @param array $options Array of options, may contain the following values
	 * - activeUsers bool: Whether to update the number of active users (default: false)
	 */
	public static function doAllAndCommit( $database, array $options = [] ) {
		$options += [ 'update' => false, 'activeUsers' => false ];

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
	 * Insert a dummy row with all zeroes if no row is present
	 */
	public static function doPlaceholderInit() {
		$dbw = wfGetDB( DB_MASTER );
		$exists = $dbw->selectField( 'site_stats', '1', [ 'ss_row_id' => 1 ],  __METHOD__ );
		if ( $exists === false ) {
			$dbw->insert(
				'site_stats',
				[ 'ss_row_id' => 1 ] + array_fill_keys( SiteStats::selectFields(), 0 ),
				__METHOD__,
				[ 'IGNORE' ]
			);
		}
	}

	/**
	 * Refresh site_stats
	 */
	public function refresh() {
		$values = [
			'ss_row_id' => 1,
			'ss_total_edits' => ( $this->mEdits === null ? $this->edits() : $this->mEdits ),
			'ss_good_articles' => ( $this->mArticles === null ? $this->articles() : $this->mArticles ),
			'ss_total_pages' => ( $this->mPages === null ? $this->pages() : $this->mPages ),
			'ss_users' => ( $this->mUsers === null ? $this->users() : $this->mUsers ),
			'ss_images' => ( $this->mFiles === null ? $this->files() : $this->mFiles ),
		];

		$dbw = wfGetDB( DB_MASTER );
		$dbw->upsert( 'site_stats', $values, [ 'ss_row_id' ], $values, __METHOD__ );
	}
}
