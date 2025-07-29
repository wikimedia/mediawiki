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

namespace MediaWiki\Linker;

use InvalidArgumentException;
use MediaWiki\Title\TitleValue;
use RuntimeException;
use stdClass;
use Wikimedia\ObjectCache\BagOStuff;
use Wikimedia\ObjectCache\WANObjectCache;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IDatabase;

/**
 * Service for retrieving and storing link targets.
 *
 * @since 1.38
 */
class LinkTargetStore implements LinkTargetLookup {

	/** @var IConnectionProvider */
	private $dbProvider;

	/** @var BagOStuff */
	private $localCache;

	/** @var WANObjectCache */
	private $wanObjectCache;

	/** @var array<int, LinkTarget> */
	private $byIdCache;

	/** @var array<string, int> */
	private $byTitleCache;

	/**
	 * @param IConnectionProvider $dbProvider
	 * @param BagOStuff $localCache
	 * @param WANObjectCache $WanObjectCache
	 */
	public function __construct(
		IConnectionProvider $dbProvider,
		BagOStuff $localCache,
		WANObjectCache $WanObjectCache
	) {
		$this->dbProvider = $dbProvider;
		$this->localCache = $localCache;
		$this->wanObjectCache = $WanObjectCache;
		$this->byIdCache = [];
		$this->byTitleCache = [];
	}

	/**
	 * @inheritDoc
	 */
	public function newLinkTargetFromRow( stdClass $row ): LinkTarget {
		$ltId = (int)$row->lt_id;
		if ( $ltId === 0 ) {
			throw new InvalidArgumentException(
				"LinkTarget ID is 0 for {$row->lt_title} (ns: {$row->lt_namespace})"
			);
		}

		$titlevalue = new TitleValue( (int)$row->lt_namespace, $row->lt_title );
		$this->addToClassCache( $ltId, $titlevalue );
		return $titlevalue;
	}

	/**
	 * Find a link target by $id.
	 *
	 * @param int $linkTargetId
	 * @return LinkTarget|null Returns null if no link target with this $linkTargetId exists in the database.
	 */
	public function getLinkTargetById( int $linkTargetId ): ?LinkTarget {
		if ( !$linkTargetId ) {
			return null;
		}

		if ( isset( $this->byIdCache[$linkTargetId] ) ) {
			return $this->byIdCache[$linkTargetId];
		}

		$value = $this->dbProvider->getReplicaDatabase()->newSelectQueryBuilder()
			->caller( __METHOD__ )
			->table( 'linktarget' )
			->conds( [ 'lt_id' => $linkTargetId ] )
			->fields( [ 'lt_id', 'lt_namespace', 'lt_title' ] )
			->fetchRow();
		if ( !$value ) {
			return null;
		}

		// TODO: Use local and wan cache when writing read new code.
		$linkTarget = $this->newLinkTargetFromRow( $value );
		$this->addToClassCache( $linkTargetId, $linkTarget );
		return $linkTarget;
	}

	/**
	 * Return link target id if exists
	 *
	 * @param LinkTarget $linkTarget
	 * @return int|null linktarget ID greater then 0, null if not found
	 */
	public function getLinkTargetId( LinkTarget $linkTarget ): ?int {
		// allow cache to be used, because if it is in the cache, it already has a linktarget id
		return $this->getLinkTargetIdFromCache( $linkTarget ) ?: null;
	}

	/**
	 * Attempt to assign a link target ID to the given $linkTarget. If it is already assigned,
	 * return the existing ID.
	 *
	 * @note If called within a transaction, the returned ID might become invalid
	 * if the transaction is rolled back, so it should not be passed outside of the
	 * transaction context.
	 *
	 * @param LinkTarget $linkTarget
	 * @param IDatabase $dbw The database connection to acquire the ID from.
	 * @return int linktarget ID greater then 0
	 * @throws RuntimeException if no linktarget ID has been assigned to this $linkTarget
	 */
	public function acquireLinkTargetId( LinkTarget $linkTarget, IDatabase $dbw ): int {
		// allow cache to be used, because if it is in the cache, it already has a linktarget id
		$existingLinktargetId = $this->getLinkTargetIdFromCache( $linkTarget );
		if ( $existingLinktargetId ) {
			return $existingLinktargetId;
		}

		// Checking primary when it doesn't exist in replica is not that useful but given
		// the fact that failed inserts waste an auto_increment id better to avoid that.
		$linkTargetId = $this->fetchIdFromDbPrimary( $linkTarget );
		if ( $linkTargetId ) {
			$this->addToClassCache( $linkTargetId, $linkTarget );
			return $linkTargetId;
		}

		$ns = $linkTarget->getNamespace();
		$title = $linkTarget->getDBkey();

		$dbw->newInsertQueryBuilder()
			->insertInto( 'linktarget' )
			->ignore()
			->row( [ 'lt_namespace' => $ns, 'lt_title' => $title ] )
			->caller( __METHOD__ )->execute();

		if ( $dbw->affectedRows() ) {
			$linkTargetId = $dbw->insertId();
		} else {
			// Use LOCK IN SHARE MODE to bypass any MySQL REPEATABLE-READ snapshot.
			$linkTargetId = $this->fetchIdFromDbPrimary( $linkTarget, true );
			if ( !$linkTargetId ) {
				throw new RuntimeException(
					"Failed to create link target ID for " .
					"lt_namespace={$ns} lt_title=\"{$title}\""
				);
			}
		}
		$this->addToClassCache( $linkTargetId, $linkTarget );

		return $linkTargetId;
	}

	/**
	 * Find lt_id of the given $linkTarget
	 *
	 * @param LinkTarget $linkTarget
	 * @param bool $lockInShareMode
	 * @return int|null
	 */
	private function fetchIdFromDbPrimary(
		LinkTarget $linkTarget,
		bool $lockInShareMode = false
	): ?int {
		$queryBuilder = $this->dbProvider->getPrimaryDatabase()->newSelectQueryBuilder()
			->select( [ 'lt_id', 'lt_namespace', 'lt_title' ] )
			->from( 'linktarget' )
			->where( [ 'lt_namespace' => $linkTarget->getNamespace(), 'lt_title' => $linkTarget->getDBkey() ] );
		if ( $lockInShareMode ) {
			$queryBuilder->lockInShareMode();
		}
		$row = $queryBuilder->caller( __METHOD__ )->fetchRow();

		if ( !$row || !$row->lt_id ) {
			return null;
		}
		$this->addToClassCache( (int)$row->lt_id, $linkTarget );

		return (int)$row->lt_id;
	}

	private function addToClassCache( int $id, LinkTarget $linkTarget ) {
		$this->byIdCache[$id] = $linkTarget;
		$this->byTitleCache[(string)$linkTarget] = $id;
	}

	/**
	 * @internal use by tests only
	 */
	public function clearClassCache() {
		$this->byIdCache = [];
		$this->byTitleCache = [];
	}

	/**
	 * @param LinkTarget $linkTarget
	 * @return int|false
	 */
	private function getLinkTargetIdFromCache( LinkTarget $linkTarget ) {
		$linkTargetString = (string)$linkTarget;
		if ( isset( $this->byTitleCache[$linkTargetString] ) ) {
			return $this->byTitleCache[$linkTargetString];
		}
		$fname = __METHOD__;
		$res = $this->localCache->getWithSetCallback(
			$this->localCache->makeKey(
				'linktargetstore-id',
				$linkTargetString
			),
			$this->localCache::TTL_HOUR,
			function () use ( $linkTarget, $fname ) {
				return $this->wanObjectCache->getWithSetCallback(
					$this->wanObjectCache->makeKey(
						'linktargetstore-id',
						(string)$linkTarget
					),
					WANObjectCache::TTL_DAY,
					function () use ( $linkTarget, $fname ) {
						$row = $this->dbProvider->getReplicaDatabase()->newSelectQueryBuilder()
							->select( [ 'lt_id', 'lt_namespace', 'lt_title' ] )
							->from( 'linktarget' )
							->where( [
								'lt_namespace' => $linkTarget->getNamespace(),
								'lt_title' => $linkTarget->getDBkey()
							] )
							->caller( $fname )->fetchRow();
						return $row && $row->lt_id ? (int)$row->lt_id : false;
					}
				);
			}
		);

		if ( $res ) {
			$this->addToClassCache( $res, $linkTarget );
		}

		return $res;
	}

}
