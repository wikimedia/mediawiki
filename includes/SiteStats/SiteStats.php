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

namespace MediaWiki\SiteStats;

use MediaWiki\JobQueue\Exceptions\JobQueueError;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use stdClass;
use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\ILoadBalancer;
use Wikimedia\Rdbms\IReadableDatabase;

/**
 * Static accessor class for site_stats and related things
 */
class SiteStats {
	/** @var stdClass|null */
	private static $row;

	/**
	 * Trigger a reload next time a field is accessed
	 */
	public static function unload() {
		self::$row = null;
	}

	protected static function load() {
		if ( self::$row === null ) {
			self::$row = self::loadAndLazyInit();
		}
	}

	/**
	 * @return stdClass
	 */
	protected static function loadAndLazyInit() {
		$config = MediaWikiServices::getInstance()->getMainConfig();

		$lb = self::getLB();
		$dbr = $lb->getConnection( DB_REPLICA );
		wfDebug( __METHOD__ . ": reading site_stats from replica DB" );
		$row = self::doLoadFromDB( $dbr );

		if ( !self::isRowSensible( $row ) && $lb->hasOrMadeRecentPrimaryChanges() ) {
			// Might have just been initialized during this request? Underflow?
			wfDebug( __METHOD__ . ": site_stats damaged or missing on replica DB" );
			$row = self::doLoadFromDB( $lb->getConnection( DB_PRIMARY ) );
		}

		if ( !self::isRowSensible( $row ) ) {
			if ( $config->get( MainConfigNames::MiserMode ) ) {
				// Start off with all zeroes, assuming that this is a new wiki or any
				// repopulations where done manually via script.
				SiteStatsInit::doPlaceholderInit();
			} else {
				// Normally the site_stats table is initialized at install time.
				// Some manual construction scenarios may leave the table empty or
				// broken, however, for instance when importing from a dump into a
				// clean schema with mwdumper.
				wfDebug( __METHOD__ . ": initializing damaged or missing site_stats" );
				SiteStatsInit::doAllAndCommit( $dbr );
			}

			$row = self::doLoadFromDB( $lb->getConnection( DB_PRIMARY ) );
		}

		return $row;
	}

	/**
	 * @return int
	 */
	public static function edits() {
		self::load();

		return (int)self::$row->ss_total_edits;
	}

	/**
	 * @return int
	 */
	public static function articles() {
		self::load();

		return (int)self::$row->ss_good_articles;
	}

	/**
	 * @return int
	 */
	public static function pages() {
		self::load();

		return (int)self::$row->ss_total_pages;
	}

	/**
	 * @return int
	 */
	public static function users() {
		self::load();

		return (int)self::$row->ss_users;
	}

	/**
	 * @return int
	 */
	public static function activeUsers() {
		self::load();

		return (int)self::$row->ss_active_users;
	}

	/**
	 * @return int
	 */
	public static function images() {
		self::load();

		return (int)self::$row->ss_images;
	}

	/**
	 * Find the number of users in a given user group.
	 * @param string $group Name of group
	 * @return int
	 */
	public static function numberingroup( $group ) {
		$cache = MediaWikiServices::getInstance()->getMainWANObjectCache();
		$fname = __METHOD__;

		return $cache->getWithSetCallback(
			$cache->makeKey( 'SiteStats', 'groupcounts', $group ),
			$cache::TTL_HOUR,
			static function ( $oldValue, &$ttl, array &$setOpts ) use ( $group, $fname ) {
				$dbr = self::getLB()->getConnection( DB_REPLICA );
				$setOpts += Database::getCacheSetOptions( $dbr );
				return (int)$dbr->newSelectQueryBuilder()
					->select( 'COUNT(*)' )
					->from( 'user_groups' )
					->where(
						[
							'ug_group' => $group,
							$dbr->expr( 'ug_expiry', '=', null )->or( 'ug_expiry', '>=', $dbr->timestamp() )
						]
					)
					->caller( $fname )
					->fetchField();
			},
			[ 'pcTTL' => $cache::TTL_PROC_LONG ]
		);
	}

	/**
	 * Total number of jobs in the job queue.
	 * @return int
	 */
	public static function jobs() {
		$cache = MediaWikiServices::getInstance()->getMainWANObjectCache();

		return $cache->getWithSetCallback(
			$cache->makeKey( 'SiteStats', 'jobscount' ),
			$cache::TTL_MINUTE,
			static function ( $oldValue, &$ttl, array &$setOpts ) {
				try {
					$jobs = array_sum( MediaWikiServices::getInstance()->getJobQueueGroup()->getQueueSizes() );
				} catch ( JobQueueError ) {
					$jobs = 0;
				}
				return $jobs;
			},
			[ 'pcTTL' => $cache::TTL_PROC_LONG ]
		);
	}

	/**
	 * @param int $ns
	 * @return int
	 */
	public static function pagesInNs( $ns ) {
		$cache = MediaWikiServices::getInstance()->getMainWANObjectCache();
		$fname = __METHOD__;

		return $cache->getWithSetCallback(
			$cache->makeKey( 'SiteStats', 'page-in-namespace', $ns ),
			$cache::TTL_HOUR,
			static function ( $oldValue, &$ttl, array &$setOpts ) use ( $ns, $fname ) {
				$dbr = self::getLB()->getConnection( DB_REPLICA );
				$setOpts += Database::getCacheSetOptions( $dbr );

				return (int)$dbr->newSelectQueryBuilder()
					->select( 'COUNT(*)' )
					->from( 'page' )
					->where( [ 'page_namespace' => $ns ] )
					->caller( $fname )->fetchField();
			},
			[ 'pcTTL' => $cache::TTL_PROC_LONG ]
		);
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
	 * @param IReadableDatabase $db
	 * @return stdClass|false
	 */
	private static function doLoadFromDB( IReadableDatabase $db ) {
		$fields = self::selectFields();
		$rows = $db->newSelectQueryBuilder()
			->select( $fields )
			->from( 'site_stats' )
			->caller( __METHOD__ )
			->fetchResultSet();
		if ( !$rows->numRows() ) {
			return false;
		}
		$finalRow = new stdClass();
		foreach ( $rows as $row ) {
			foreach ( $fields as $field ) {
				$finalRow->$field ??= 0;
				if ( $row->$field ) {
					$finalRow->$field += $row->$field;
				}
			}

		}
		return $finalRow;
	}

	/**
	 * Is the provided row of site stats sensible, or should it be regenerated?
	 *
	 * Checks only fields which are filled by SiteStatsInit::refresh.
	 *
	 * @param stdClass|false $row
	 * @return bool
	 */
	private static function isRowSensible( $row ) {
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
			if ( $row->$member < 0 ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * @return ILoadBalancer
	 */
	private static function getLB() {
		return MediaWikiServices::getInstance()->getDBLoadBalancer();
	}
}

/** @deprecated class alias since 1.41 */
class_alias( SiteStats::class, 'SiteStats' );
