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
use MediaWiki\MediaWikiServices;
use Wikimedia\Assert\Assert;
use Wikimedia\Rdbms\IDatabase;

/**
 * Class for handling updates to the site_stats table
 */
class SiteStatsUpdate implements DeferrableUpdate, MergeableUpdate {
	/** @var BagOStuff */
	protected $stash;
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

	private static $counters = [ 'edits', 'pages', 'articles', 'users', 'images' ];

	// @todo deprecate this constructor
	function __construct( $views, $edits, $good, $pages = 0, $users = 0 ) {
		$this->edits = $edits;
		$this->articles = $good;
		$this->pages = $pages;
		$this->users = $users;

		$this->stash = MediaWikiServices::getInstance()->getMainObjectStash();
	}

	public function merge( MergeableUpdate $update ) {
		/** @var SiteStatsUpdate $update */
		Assert::parameterType( __CLASS__, $update, '$update' );

		foreach ( self::$counters as $field ) {
			$this->$field += $update->$field;
		}
	}

	/**
	 * @param array $deltas
	 * @return SiteStatsUpdate
	 */
	public static function factory( array $deltas ) {
		$update = new self( 0, 0, 0 );

		foreach ( $deltas as $name => $unused ) {
			if ( !in_array( $name, self::$counters ) ) { // T187585
				throw new UnexpectedValueException( __METHOD__ . ": no field called '$name'" );
			}
		}

		foreach ( self::$counters as $field ) {
			if ( isset( $deltas[$field] ) && $deltas[$field] ) {
				$update->$field = $deltas[$field];
			}
		}

		return $update;
	}

	public function doUpdate() {
		$this->doUpdateContextStats();

		$rate = MediaWikiServices::getInstance()->getMainConfig()->get( 'SiteStatsAsyncFactor' );
		// If set to do so, only do actual DB updates 1 every $rate times.
		// The other times, just update "pending delta" values in memcached.
		if ( $rate && ( $rate < 0 || mt_rand( 0, $rate - 1 ) != 0 ) ) {
			$this->doUpdatePendingDeltas();
		} else {
			// Need a separate transaction because this a global lock
			DeferredUpdates::addCallableUpdate( [ $this, 'tryDBUpdateInternal' ] );
		}
	}

	/**
	 * Do not call this outside of SiteStatsUpdate
	 */
	public function tryDBUpdateInternal() {
		$services = MediaWikiServices::getInstance();
		$config = $services->getMainConfig();

		$dbw = $services->getDBLoadBalancer()->getConnection( DB_MASTER );
		$lockKey = $dbw->getDomainID() . ':site_stats'; // prepend wiki ID
		$pd = [];
		if ( $config->get( 'SiteStatsAsyncFactor' ) ) {
			// Lock the table so we don't have double DB/memcached updates
			if ( !$dbw->lock( $lockKey, __METHOD__, 0 ) ) {
				$this->doUpdatePendingDeltas();

				return;
			}
			$pd = $this->getPendingDeltas();
			// Piggy-back the async deltas onto those of this stats update....
			$this->edits += ( $pd['ss_total_edits']['+'] - $pd['ss_total_edits']['-'] );
			$this->articles += ( $pd['ss_good_articles']['+'] - $pd['ss_good_articles']['-'] );
			$this->pages += ( $pd['ss_total_pages']['+'] - $pd['ss_total_pages']['-'] );
			$this->users += ( $pd['ss_users']['+'] - $pd['ss_users']['-'] );
			$this->images += ( $pd['ss_images']['+'] - $pd['ss_images']['-'] );
		}

		// Build up an SQL query of deltas and apply them...
		$updates = '';
		$this->appendUpdate( $updates, 'ss_total_edits', $this->edits );
		$this->appendUpdate( $updates, 'ss_good_articles', $this->articles );
		$this->appendUpdate( $updates, 'ss_total_pages', $this->pages );
		$this->appendUpdate( $updates, 'ss_users', $this->users );
		$this->appendUpdate( $updates, 'ss_images', $this->images );
		if ( $updates != '' ) {
			$dbw->update( 'site_stats', [ $updates ], [], __METHOD__ );
		}

		if ( $config->get( 'SiteStatsAsyncFactor' ) ) {
			// Decrement the async deltas now that we applied them
			$this->removePendingDeltas( $pd );
			// Commit the updates and unlock the table
			$dbw->unlock( $lockKey, __METHOD__ );
		}

		// Invalid cache used by parser functions
		SiteStats::unload();
	}

	/**
	 * @param IDatabase $dbw
	 * @return bool|mixed
	 */
	public static function cacheUpdate( IDatabase $dbw ) {
		$services = MediaWikiServices::getInstance();
		$config = $services->getMainConfig();

		$dbr = $services->getDBLoadBalancer()->getConnection( DB_REPLICA, 'vslow' );
		# Get non-bot users than did some recent action other than making accounts.
		# If account creation is included, the number gets inflated ~20+ fold on enwiki.
		$rcQuery = RecentChange::getQueryInfo();
		$activeUsers = $dbr->selectField(
			$rcQuery['tables'],
			'COUNT( DISTINCT ' . $rcQuery['fields']['rc_user_text'] . ' )',
			[
				'rc_type != ' . $dbr->addQuotes( RC_EXTERNAL ), // Exclude external (Wikidata)
				ActorMigration::newMigration()->isNotAnon( $rcQuery['fields']['rc_user'] ),
				'rc_bot' => 0,
				'rc_log_type != ' . $dbr->addQuotes( 'newusers' ) . ' OR rc_log_type IS NULL',
				'rc_timestamp >= ' . $dbr->addQuotes(
					$dbr->timestamp( time() - $config->get( 'ActiveUserDays' ) * 24 * 3600 ) ),
			],
			__METHOD__,
			[],
			$rcQuery['joins']
		);
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

	protected function doUpdateContextStats() {
		$stats = MediaWikiServices::getInstance()->getStatsdDataFactory();
		foreach ( [ 'edits', 'articles', 'pages', 'users', 'images' ] as $type ) {
			$delta = $this->$type;
			if ( $delta !== 0 ) {
				$stats->updateCount( "site.$type", $delta );
			}
		}
	}

	protected function doUpdatePendingDeltas() {
		$this->adjustPending( 'ss_total_edits', $this->edits );
		$this->adjustPending( 'ss_good_articles', $this->articles );
		$this->adjustPending( 'ss_total_pages', $this->pages );
		$this->adjustPending( 'ss_users', $this->users );
		$this->adjustPending( 'ss_images', $this->images );
	}

	/**
	 * @param string &$sql
	 * @param string $field
	 * @param int $delta
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
	 * @param BagOStuff $stash
	 * @param string $type
	 * @param string $sign ('+' or '-')
	 * @return string
	 */
	private function getTypeCacheKey( BagOStuff $stash, $type, $sign ) {
		return $stash->makeKey( 'sitestatsupdate', 'pendingdelta', $type, $sign );
	}

	/**
	 * Adjust the pending deltas for a stat type.
	 * Each stat type has two pending counters, one for increments and decrements
	 * @param string $type
	 * @param int $delta Delta (positive or negative)
	 */
	protected function adjustPending( $type, $delta ) {
		if ( $delta < 0 ) { // decrement
			$key = $this->getTypeCacheKey( $this->stash, $type, '-' );
		} else { // increment
			$key = $this->getTypeCacheKey( $this->stash, $type, '+' );
		}

		$magnitude = abs( $delta );
		$this->stash->incrWithInit( $key, 0, $magnitude, $magnitude );
	}

	/**
	 * Get pending delta counters for each stat type
	 * @return array Positive and negative deltas for each type
	 */
	protected function getPendingDeltas() {
		$pending = [];
		foreach ( [ 'ss_total_edits',
			'ss_good_articles', 'ss_total_pages', 'ss_users', 'ss_images' ] as $type
		) {
			// Get pending increments and pending decrements
			$flg = BagOStuff::READ_LATEST;
			$pending[$type]['+'] = (int)$this->stash->get(
				$this->getTypeCacheKey( $this->stash, $type, '+' ),
				$flg
			);
			$pending[$type]['-'] = (int)$this->stash->get(
				$this->getTypeCacheKey( $this->stash, $type, '-' ),
				$flg
			);
		}

		return $pending;
	}

	/**
	 * Reduce pending delta counters after updates have been applied
	 * @param array $pd Result of getPendingDeltas(), used for DB update
	 */
	protected function removePendingDeltas( array $pd ) {
		foreach ( $pd as $type => $deltas ) {
			foreach ( $deltas as $sign => $magnitude ) {
				// Lower the pending counter now that we applied these changes
				$key = $this->getTypeCacheKey( $this->stash, $type, $sign );
				$this->stash->decr( $key, $magnitude );
			}
		}
	}
}
