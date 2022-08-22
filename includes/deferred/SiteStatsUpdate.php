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
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use Wikimedia\Assert\Assert;
use Wikimedia\Rdbms\IDatabase;

/**
 * Class for handling updates to the site_stats table
 */
class SiteStatsUpdate implements DeferrableUpdate, MergeableUpdate {
	/** @var int */
	protected $edits = 0;
	/** @var int */
	protected $pages = 0;
	/** @var int */
	protected $articles = 0;
	/** @var int */
	protected $users = 0;
	/** @var int */
	protected $images = 0;

	private const SHARDS_OFF = 1;
	public const SHARDS_ON = 10;

	/** @var string[] Map of (table column => counter type) */
	private const COUNTERS = [
		'ss_total_edits'   => 'edits',
		'ss_total_pages'   => 'pages',
		'ss_good_articles' => 'articles',
		'ss_users'         => 'users',
		'ss_images'        => 'images'
	];

	/**
	 * @deprecated since 1.39 Use SiteStatsUpdate::factory() instead.
	 */
	public function __construct( $views, $edits, $good, $pages = 0, $users = 0 ) {
		$this->edits = $edits;
		$this->articles = $good;
		$this->pages = $pages;
		$this->users = $users;
	}

	public function merge( MergeableUpdate $update ) {
		/** @var SiteStatsUpdate $update */
		Assert::parameterType( __CLASS__, $update, '$update' );
		'@phan-var SiteStatsUpdate $update';

		foreach ( self::COUNTERS as $field ) {
			$this->$field += $update->$field;
		}
	}

	/**
	 * @param int[] $deltas Map of (counter type => integer delta) e.g.
	 * 		```
	 * 		SiteStatsUpdate::factory( [
	 *			'edits'    => 10,
	 *			'articles' => 2,
	 *			'pages'    => 7,
	 *			'users'    => 5,
	 *		] );
	 * 		```
	 * @return SiteStatsUpdate
	 * @throws UnexpectedValueException
	 */
	public static function factory( array $deltas ) {
		$update = new self( 0, 0, 0 );

		foreach ( $deltas as $name => $unused ) {
			if ( !in_array( $name, self::COUNTERS ) ) { // T187585
				throw new UnexpectedValueException( __METHOD__ . ": no field called '$name'" );
			}
		}

		foreach ( self::COUNTERS as $field ) {
			$update->$field = $deltas[$field] ?? 0;
		}

		return $update;
	}

	public function doUpdate() {
		$services = MediaWikiServices::getInstance();
		$stats = $services->getStatsdDataFactory();
		$shards = $services->getMainConfig()->get( MainConfigNames::MultiShardSiteStats ) ?
			self::SHARDS_ON : self::SHARDS_OFF;

		$deltaByType = [];
		foreach ( self::COUNTERS as $type ) {
			$delta = $this->$type;
			if ( $delta !== 0 ) {
				$stats->updateCount( "site.$type", $delta );
			}
			$deltaByType[$type] = $delta;
		}

		( new AutoCommitUpdate(
			$services->getDBLoadBalancer()->getConnectionRef( DB_PRIMARY ),
			__METHOD__,
			static function ( IDatabase $dbw, $fname ) use ( $deltaByType, $shards ) {
				$set = [];
				$initValues = [];
				if ( $shards > 1 ) {
					$shard = mt_rand( 1, $shards );
				} else {
					$shard = 1;
				}

				$hasNegativeDelta = false;
				foreach ( self::COUNTERS as $field => $type ) {
					$delta = (int)$deltaByType[$type];
					$initValues[$field] = $delta;
					if ( $delta > 0 ) {
						$set[] = "$field=" . $dbw->buildGreatest(
							[ $field => $dbw->addIdentifierQuotes( $field ) . '+' . abs( $delta ) ],
							0
						);
					} elseif ( $delta < 0 ) {
						$hasNegativeDelta = true;
						$set[] = "$field=" . $dbw->buildGreatest(
							[ 'new' => $dbw->addIdentifierQuotes( $field ) . '-' . abs( $delta ) ],
							0
						);
					}
				}

				if ( $set ) {
					if ( $hasNegativeDelta ) {
						$dbw->update( 'site_stats', $set, [ 'ss_row_id' => $shard ], $fname );
					} else {
						$dbw->upsert(
							'site_stats',
							array_merge( [ 'ss_row_id' => $shard ], $initValues ),
							'ss_row_id',
							$set,
							$fname
						);
					}
				}
			}
		) )->doUpdate();

		// Invalidate cache used by parser functions
		SiteStats::unload();
	}

	/**
	 * @param IDatabase $dbw
	 * @return bool|mixed
	 */
	public static function cacheUpdate( IDatabase $dbw ) {
		$services = MediaWikiServices::getInstance();
		$config = $services->getMainConfig();

		$dbr = $services->getDBLoadBalancer()->getConnectionRef( DB_REPLICA, 'vslow' );
		# Get non-bot users than did some recent action other than making accounts.
		# If account creation is included, the number gets inflated ~20+ fold on enwiki.
		$activeUsers = $dbr->newSelectQueryBuilder()
			->select( 'COUNT(DISTINCT rc_actor)' )
			->from( 'recentchanges' )
			->join( 'actor', 'actor', 'actor_id=rc_actor' )
			->where( [
				'rc_type != ' . $dbr->addQuotes( RC_EXTERNAL ), // Exclude external (Wikidata)
				'actor_user IS NOT NULL',
				'rc_bot' => 0,
				'rc_log_type != ' . $dbr->addQuotes( 'newusers' ) . ' OR rc_log_type IS NULL',
				'rc_timestamp >= ' . $dbr->addQuotes(
					$dbr->timestamp( time() - $config->get( MainConfigNames::ActiveUserDays ) * 24 * 3600 ) ),
			] )
			->caller( __METHOD__ )
			->fetchField();
		$dbw->update(
			'site_stats',
			[ 'ss_active_users' => intval( $activeUsers ) ],
			[ 'ss_row_id' => 1 ],
			__METHOD__
		);

		// Invalid cache used by parser functions
		SiteStats::unload();

		return $activeUsers;
	}
}
