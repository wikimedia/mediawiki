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

use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\IDatabase;
use MediaWiki\MediaWikiServices;

/**
 * Static accessor class for site_stats and related things
 */
class SiteStats {
	/** @var bool|stdClass */
	private static $row;

	/** @var bool */
	private static $loaded = false;
	/** @var int[] */
	private static $pageCount = [];

	static function unload() {
		self::$loaded = false;
	}

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

		self::$loaded = true;
	}

	/**
	 * @return bool|stdClass
	 */
	static function loadAndLazyInit() {
		global $wgMiserMode;

		wfDebug( __METHOD__ . ": reading site_stats from replica DB\n" );
		$row = self::doLoad( wfGetDB( DB_REPLICA ) );

		if ( !self::isSane( $row ) ) {
			$lb = MediaWikiServices::getInstance()->getDBLoadBalancer();
			if ( $lb->hasOrMadeRecentMasterChanges() ) {
				// Might have just been initialized during this request? Underflow?
				wfDebug( __METHOD__ . ": site_stats damaged or missing on replica DB\n" );
				$row = self::doLoad( wfGetDB( DB_MASTER ) );
			}
		}

		if ( !self::isSane( $row ) ) {
			if ( $wgMiserMode ) {
				// Start off with all zeroes, assuming that this is a new wiki or any
				// repopulations where done manually via script.
				SiteStatsInit::doPlaceholderInit();
			} else {
				// Normally the site_stats table is initialized at install time.
				// Some manual construction scenarios may leave the table empty or
				// broken, however, for instance when importing from a dump into a
				// clean schema with mwdumper.
				wfDebug( __METHOD__ . ": initializing damaged or missing site_stats\n" );
				SiteStatsInit::doAllAndCommit( wfGetDB( DB_REPLICA ) );
			}

			$row = self::doLoad( wfGetDB( DB_MASTER ) );
		}

		if ( !self::isSane( $row ) ) {
			wfDebug( __METHOD__ . ": site_stats persistently nonsensical o_O\n" );
			$row = (object)array_fill_keys( self::selectFields(), 0 );
		}

		return $row;
	}

	/**
	 * @param IDatabase $db
	 * @return bool|stdClass
	 */
	static function doLoad( $db ) {
		return $db->selectRow(
			'site_stats',
			self::selectFields(),
			[ 'ss_row_id' => 1 ],
			__METHOD__
		);
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
		$cache = MediaWikiServices::getInstance()->getMainWANObjectCache();
		return $cache->getWithSetCallback(
			$cache->makeKey( 'SiteStats', 'groupcounts', $group ),
			$cache::TTL_HOUR,
			function ( $oldValue, &$ttl, array &$setOpts ) use ( $group ) {
				$dbr = wfGetDB( DB_REPLICA );

				$setOpts += Database::getCacheSetOptions( $dbr );

				return $dbr->selectField(
					'user_groups',
					'COUNT(*)',
					[
						'ug_group' => $group,
						'ug_expiry IS NULL OR ug_expiry >= ' . $dbr->addQuotes( $dbr->timestamp() )
					],
					__METHOD__
				);
			},
			[ 'pcTTL' => $cache::TTL_PROC_LONG ]
		);
	}

	/**
	 * Total number of jobs in the job queue.
	 * @return int
	 */
	static function jobs() {
		$cache = MediaWikiServices::getInstance()->getMainWANObjectCache();
		return $cache->getWithSetCallback(
			$cache->makeKey( 'SiteStats', 'jobscount' ),
			$cache::TTL_MINUTE,
			function ( $oldValue, &$ttl, array &$setOpts ) {
				try{
					$jobs = array_sum( JobQueueGroup::singleton()->getQueueSizes() );
				} catch ( JobQueueError $e ) {
					$jobs = 0;
				}
				return $jobs;
			},
			[ 'pcTTL' => $cache::TTL_PROC_LONG ]
		);
	}

	/**
	 * @param int $ns
	 *
	 * @return int
	 */
	static function pagesInNs( $ns ) {
		if ( !isset( self::$pageCount[$ns] ) ) {
			$dbr = wfGetDB( DB_REPLICA );
			self::$pageCount[$ns] = (int)$dbr->selectField(
				'page',
				'COUNT(*)',
				[ 'page_namespace' => $ns ],
				__METHOD__
			);
		}
		return self::$pageCount[$ns];
	}

	/**
	 * @return array
	 */
	public static function selectFields() {
		return [
			'ss_total_edits',
			'ss_good_articles',
			'ss_total_pages',
			'ss_users',
			'ss_active_users',
			'ss_images',
		];
	}

	/**
	 * Is the provided row of site stats sane, or should it be regenerated?
	 *
	 * Checks only fields which are filled by SiteStatsInit::refresh.
	 *
	 * @param bool|object $row
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
		foreach ( [
			'ss_total_edits',
			'ss_good_articles',
			'ss_total_pages',
			'ss_users',
			'ss_images',
		] as $member ) {
			if ( $row->$member > 2000000000 || $row->$member < 0 ) {
				return false;
			}
		}
		return true;
	}
}
