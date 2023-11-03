<?php
/**
 * Class for DatabaseBlock objects to interact with the database
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

namespace MediaWiki\Block;

use AutoCommitUpdate;
use DeferredUpdates;
use InvalidArgumentException;
use MediaWiki\CommentStore\CommentStore;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\MainConfigNames;
use MediaWiki\User\ActorStoreFactory;
use MediaWiki\User\TempUser\TempUserConfig;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityValue;
use Psr\Log\LoggerInterface;
use stdClass;
use UnexpectedValueException;
use Wikimedia\IPUtils;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\ILoadBalancer;
use Wikimedia\Rdbms\IReadableDatabase;
use Wikimedia\Rdbms\ReadOnlyMode;
use Wikimedia\Rdbms\SelectQueryBuilder;

/**
 * @since 1.36
 *
 * @author DannyS712
 */
class DatabaseBlockStore {
	public const SCHEMA_IPBLOCKS = 'ipblocks';

	/**
	 * @internal For use by ServiceWiring
	 */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::AutoblockExpiry,
		MainConfigNames::BlockCIDRLimit,
		MainConfigNames::BlockDisablesLogin,
		MainConfigNames::PutIPinRC,
		MainConfigNames::UpdateRowsPerQuery,
	];

	/** @var string|false */
	private $wikiId;

	/** @var ServiceOptions */
	private $options;

	/** @var LoggerInterface */
	private $logger;

	/** @var ActorStoreFactory */
	private $actorStoreFactory;

	/** @var BlockRestrictionStore */
	private $blockRestrictionStore;

	/** @var CommentStore */
	private $commentStore;

	/** @var HookRunner */
	private $hookRunner;

	/** @var ILoadBalancer */
	private $loadBalancer;

	/** @var ReadOnlyMode */
	private $readOnlyMode;

	/** @var UserFactory */
	private $userFactory;

	/** @var TempUserConfig */
	private $tempUserConfig;

	/** @var BlockUtils */
	private $blockUtils;

	/** @var AutoblockExemptionList */
	private $autoblockExemptionList;

	/**
	 * @param ServiceOptions $options
	 * @param LoggerInterface $logger
	 * @param ActorStoreFactory $actorStoreFactory
	 * @param BlockRestrictionStore $blockRestrictionStore
	 * @param CommentStore $commentStore
	 * @param HookContainer $hookContainer
	 * @param ILoadBalancer $loadBalancer
	 * @param ReadOnlyMode $readOnlyMode
	 * @param UserFactory $userFactory
	 * @param TempUserConfig $tempUserConfig
	 * @param BlockUtils $blockUtils
	 * @param AutoblockExemptionList $autoblockExemptionList
	 * @param string|false $wikiId
	 */
	public function __construct(
		ServiceOptions $options,
		LoggerInterface $logger,
		ActorStoreFactory $actorStoreFactory,
		BlockRestrictionStore $blockRestrictionStore,
		CommentStore $commentStore,
		HookContainer $hookContainer,
		ILoadBalancer $loadBalancer,
		ReadOnlyMode $readOnlyMode,
		UserFactory $userFactory,
		TempUserConfig $tempUserConfig,
		BlockUtils $blockUtils,
		AutoblockExemptionList $autoblockExemptionList,
		$wikiId = DatabaseBlock::LOCAL
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );

		$this->wikiId = $wikiId;

		$this->options = $options;
		$this->logger = $logger;
		$this->actorStoreFactory = $actorStoreFactory;
		$this->blockRestrictionStore = $blockRestrictionStore;
		$this->commentStore = $commentStore;
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->loadBalancer = $loadBalancer;
		$this->readOnlyMode = $readOnlyMode;
		$this->userFactory = $userFactory;
		$this->tempUserConfig = $tempUserConfig;
		$this->blockUtils = $blockUtils;
		$this->autoblockExemptionList = $autoblockExemptionList;
	}

	/***************************************************************************/
	// region   Database read methods
	/** @name   Database read methods */

	/**
	 * Load a block from the block ID.
	 *
	 * @since 1.42
	 * @param int $id ID to search for
	 * @return DatabaseBlock|null
	 */
	public function newFromID( $id ) {
		$dbr = $this->getReplicaDB();
		$blockQuery = $this->getQueryInfo( self::SCHEMA_IPBLOCKS );
		$res = $dbr->selectRow(
			$blockQuery['tables'],
			$blockQuery['fields'],
			[ 'ipb_id' => $id ],
			__METHOD__,
			[],
			$blockQuery['joins']
		);
		if ( $res ) {
			return $this->newFromRow( $dbr, $res );
		} else {
			return null;
		}
	}

	/**
	 * Return the tables, fields, and join conditions to be selected to create
	 * a new block object.
	 *
	 * Since 1.34, ipb_by and ipb_by_text have not been present in the
	 * database, but they continue to be available in query results as
	 * aliases.
	 *
	 * @since 1.42
	 * @param string $schema What schema to use for field aliases. Must be
	 *   self::SCHEMA_IPBLOCKS. In future this will default to a new schema
	 *   and later the parameter will be removed.
	 * @return array[] With three keys:
	 *   - tables: (string[]) to include in the `$table` to `IDatabase->select()`
	 *     or `SelectQueryBuilder::tables`
	 *   - fields: (string[]) to include in the `$vars` to `IDatabase->select()`
	 *     or `SelectQueryBuilder::fields`
	 *   - joins: (array) to include in the `$join_conds` to `IDatabase->select()`
	 *     or `SelectQueryBuilder::joinConds`
	 * @phan-return array{tables:string[],fields:string[],joins:array}
	 */
	public function getQueryInfo( $schema ) {
		if ( $schema !== self::SCHEMA_IPBLOCKS ) {
			throw new InvalidArgumentException( '$schema must be SCHEMA_IPBLOCKS' );
		}
		$commentQuery = $this->commentStore->getJoin( 'ipb_reason' );
		return [
			'tables' => [
				'ipblocks',
				'ipblocks_actor' => 'actor'
			] + $commentQuery['tables'],
			'fields' => [
				'ipb_id',
				'ipb_address',
				'ipb_timestamp',
				'ipb_auto',
				'ipb_anon_only',
				'ipb_create_account',
				'ipb_enable_autoblock',
				'ipb_expiry',
				'ipb_deleted',
				'ipb_block_email',
				'ipb_allow_usertalk',
				'ipb_parent_block_id',
				'ipb_sitewide',
				'ipb_by_actor',
				'ipb_by' => 'ipblocks_actor.actor_user',
				'ipb_by_text' => 'ipblocks_actor.actor_name'
			] + $commentQuery['fields'],
			'joins' => [
				'ipblocks_actor' => [ 'JOIN', 'actor_id=ipb_by_actor' ]
			] + $commentQuery['joins'],
		];
	}

	/**
	 * Load blocks from the database which target the specific target exactly, or which cover the
	 * vague target.
	 *
	 * @param UserIdentity|string|null $specificTarget
	 * @param int|null $specificType
	 * @param bool $fromPrimary
	 * @param UserIdentity|string|null $vagueTarget Also search for blocks affecting this target.
	 *     Doesn't make any sense to use TYPE_AUTO / TYPE_ID here. Leave blank to skip IP lookups.
	 * @return DatabaseBlock[] Any relevant blocks
	 */
	private function newLoad(
		$specificTarget,
		$specificType,
		$fromPrimary,
		$vagueTarget = null
	) {
		if ( $fromPrimary ) {
			$db = $this->getPrimaryDB();
		} else {
			$db = $this->getReplicaDB();
		}

		$specificTarget = $specificTarget instanceof UserIdentity ?
			$specificTarget->getName() :
			(string)$specificTarget;

		if ( $specificType !== null ) {
			$conds = [ 'ipb_address' => [ $specificTarget ] ];
		} else {
			$conds = [ 'ipb_address' => [] ];
		}

		// Be aware that the != '' check is explicit, since empty values will be
		// passed by some callers (T31116)
		if ( $vagueTarget != '' ) {
			[ $target, $type ] = $this->blockUtils->parseBlockTarget( $vagueTarget );
			switch ( $type ) {
				case Block::TYPE_USER:
					// Slightly weird, but who are we to argue?
					$conds['ipb_address'][] = (string)$target;
					$conds = $db->makeList( $conds, LIST_OR );
					break;

				case Block::TYPE_IP:
					$conds['ipb_address'][] = (string)$target;
					$conds['ipb_address'] = array_unique( $conds['ipb_address'] );
					$conds[] = $this->getRangeCond( IPUtils::toHex( $target ), null, self::SCHEMA_IPBLOCKS );
					$conds = $db->makeList( $conds, LIST_OR );
					break;

				case Block::TYPE_RANGE:
					[ $start, $end ] = IPUtils::parseRange( $target );
					$conds['ipb_address'][] = (string)$target;
					$conds[] = $this->getRangeCond( $start, $end, self::SCHEMA_IPBLOCKS );
					$conds = $db->makeList( $conds, LIST_OR );
					break;

				default:
					throw new UnexpectedValueException( "Tried to load block with invalid type" );
			}
		}

		$blockQuery = $this->getQueryInfo( self::SCHEMA_IPBLOCKS );
		$res = $db->select(
			$blockQuery['tables'],
			$blockQuery['fields'],
			$conds,
			__METHOD__,
			[],
			$blockQuery['joins']
		);

		$blocks = [];
		$blockIds = [];
		$autoBlocks = [];
		foreach ( $res as $row ) {
			$block = $this->newFromRow( $db, $row );

			// Don't use expired blocks
			if ( $block->isExpired() ) {
				continue;
			}

			// Don't use anon only blocks on users
			if (
				$specificType == Block::TYPE_USER &&
				!$block->isHardblock() &&
				!$this->tempUserConfig->isTempName( $specificTarget )
			) {
				continue;
			}

			// Check for duplicate autoblocks
			if ( $block->getType() === Block::TYPE_AUTO ) {
				$autoBlocks[] = $block;
			} else {
				$blocks[] = $block;
				$blockIds[] = $block->getId();
			}
		}

		// Only add autoblocks that aren't duplicates
		foreach ( $autoBlocks as $block ) {
			if ( !in_array( $block->getParentBlockId(), $blockIds ) ) {
				$blocks[] = $block;
			}
		}

		return $blocks;
	}

	/**
	 * Choose the most specific block from some combination of user, IP and IP range
	 * blocks. Decreasing order of specificity: user > IP > narrower IP range > wider IP
	 * range. A range that encompasses one IP address is ranked equally to a singe IP.
	 *
	 * @param DatabaseBlock[] $blocks These should not include autoblocks or ID blocks
	 * @return DatabaseBlock|null The block with the most specific target
	 */
	private function chooseMostSpecificBlock( array $blocks ) {
		if ( count( $blocks ) === 1 ) {
			return $blocks[0];
		}

		// This result could contain a block on the user, a block on the IP, and a russian-doll
		// set of range blocks.  We want to choose the most specific one, so keep a leader board.
		$bestBlock = null;

		// Lower will be better
		$bestBlockScore = 100;
		foreach ( $blocks as $block ) {
			if ( $block->getType() == Block::TYPE_RANGE ) {
				// This is the number of bits that are allowed to vary in the block, give
				// or take some floating point errors
				$target = $block->getTargetName();
				$max = IPUtils::isIPv6( $target ) ? 128 : 32;
				[ , $bits ] = IPUtils::parseCIDR( $target );
				$size = $max - $bits;

				// Rank a range block covering a single IP equally with a single-IP block
				$score = Block::TYPE_RANGE - 1 + ( $size / $max );

			} else {
				$score = $block->getType();
			}

			if ( $score < $bestBlockScore ) {
				$bestBlockScore = $score;
				$bestBlock = $block;
			}
		}

		return $bestBlock;
	}

	/**
	 * Get a set of SQL conditions which will select range blocks encompassing a given range
	 *
	 * @since 1.42
	 * @param string $start Hexadecimal IP representation
	 * @param string|null $end Hexadecimal IP representation, or null to use $start = $end
	 * @param string $schema What schema to use for field aliases. Must be
	 *   self::SCHEMA_IPBLOCKS. In future this will default to a new schema
	 *   and later the parameter will be removed.
	 * @return string
	 */
	public function getRangeCond( $start, $end, $schema ) {
		if ( $schema !== self::SCHEMA_IPBLOCKS ) {
			throw new InvalidArgumentException( '$schema must be SCHEMA_IPBLOCKS' );
		}
		// Per T16634, we want to include relevant active range blocks; for
		// range blocks, we want to include larger ranges which enclose the given
		// range. We know that all blocks must be smaller than $wgBlockCIDRLimit,
		// so we can improve performance by filtering on a LIKE clause
		$chunk = $this->getIpFragment( $start );
		$dbr = $this->getReplicaDB();
		$like = $dbr->buildLike( $chunk, $dbr->anyString() );

		$safeStart = $dbr->addQuotes( $start );
		$safeEnd = $dbr->addQuotes( $end ?? $start );

		return $dbr->makeList(
			[
				"ipb_range_start $like",
				"ipb_range_start <= $safeStart",
				"ipb_range_end >= $safeEnd",
			],
			LIST_AND
		);
	}

	/**
	 * Get the component of an IP address which is certain to be the same between an IP
	 * address and a range block containing that IP address.
	 *
	 * @param string $hex Hexadecimal IP representation
	 * @return string
	 */
	private function getIpFragment( $hex ) {
		$blockCIDRLimit = $this->options->get( MainConfigNames::BlockCIDRLimit );
		if ( str_starts_with( $hex, 'v6-' ) ) {
			return 'v6-' . substr( substr( $hex, 3 ), 0, (int)floor( $blockCIDRLimit['IPv6'] / 4 ) );
		} else {
			return substr( $hex, 0, (int)floor( $blockCIDRLimit['IPv4'] / 4 ) );
		}
	}

	/**
	 * Create a new DatabaseBlock object from a database row
	 *
	 * @since 1.42
	 * @param IReadableDatabase $db The database you got the row from
	 * @param stdClass $row Row from the ipblocks table
	 * @return DatabaseBlock
	 */
	public function newFromRow( IReadableDatabase $db, $row ) {
		return new DatabaseBlock( [
			'address' => $row->ipb_address,
			'wiki' => $this->wikiId,
			'timestamp' => $row->ipb_timestamp,
			'auto' => (bool)$row->ipb_auto,
			'hideName' => (bool)$row->ipb_deleted,
			'id' => (int)$row->ipb_id,
			// Blocks with no parent ID should have ipb_parent_block_id as null,
			// don't save that as 0 though, see T282890
			'parentBlockId' => $row->ipb_parent_block_id
				? (int)$row->ipb_parent_block_id : null,
			'by' => $this->actorStoreFactory
				->getActorStore( $this->wikiId )
				->newActorFromRowFields( $row->ipb_by, $row->ipb_by_text, $row->ipb_by_actor ),
			'decodedExpiry' => $db->decodeExpiry( $row->ipb_expiry ),
			'reason' => $this->commentStore
				// Legacy because $row may have come from self::selectFields()
				->getCommentLegacy( $db, 'ipb_reason', $row ),
			'anonOnly' => $row->ipb_anon_only,
			'enableAutoblock' => (bool)$row->ipb_enable_autoblock,
			'sitewide' => (bool)$row->ipb_sitewide,
			'createAccount' => (bool)$row->ipb_create_account,
			'blockEmail' => (bool)$row->ipb_block_email,
			'allowUsertalk' => (bool)$row->ipb_allow_usertalk
		] );
	}

	/**
	 * Given a target and the target's type, get an existing block object if possible.
	 *
	 * @since 1.42
	 * @param string|UserIdentity|int|null $specificTarget A block target, which may be one of
	 *   several types:
	 *     * A user to block, in which case $target will be a User
	 *     * An IP to block, in which case $target will be a User generated by using
	 *       User::newFromName( $ip, false ) to turn off name validation
	 *     * An IP range, in which case $target will be a String "123.123.123.123/18" etc
	 *     * The ID of an existing block, in the format "#12345" (since pure numbers are valid
	 *       usernames
	 *     Calling this with a user, IP address or range will not select autoblocks, and will
	 *     only select a block where the targets match exactly (so looking for blocks on
	 *     1.2.3.4 will not select 1.2.0.0/16 or even 1.2.3.4/32)
	 * @param string|UserIdentity|int|null $vagueTarget As above, but we will search for *any*
	 *     block which affects that target (so for an IP address, get ranges containing that IP;
	 *     and also get any relevant autoblocks). Leave empty or blank to skip IP-based lookups.
	 * @param bool $fromPrimary Whether to use the DB_PRIMARY database
	 * @return DatabaseBlock|null (null if no relevant block could be found). The target and type
	 *     of the returned block will refer to the actual block which was found, which might
	 *     not be the same as the target you gave if you used $vagueTarget!
	 */
	public function newFromTarget(
		$specificTarget,
		$vagueTarget = null,
		$fromPrimary = false
	) {
		$blocks = $this->newListFromTarget( $specificTarget, $vagueTarget, $fromPrimary );
		return $this->chooseMostSpecificBlock( $blocks );
	}

	/**
	 * This is similar to DatabaseBlockStore::newFromTarget, but it returns all the relevant blocks.
	 *
	 * @since 1.42
	 * @param string|UserIdentity|int|null $specificTarget
	 * @param string|UserIdentity|int|null $vagueTarget
	 * @param bool $fromPrimary
	 * @return DatabaseBlock[] Any relevant blocks
	 */
	public function newListFromTarget(
		$specificTarget,
		$vagueTarget = null,
		$fromPrimary = false
	) {
		[ $target, $type ] = $this->blockUtils->parseBlockTarget( $specificTarget );
		if ( $type == Block::TYPE_ID || $type == Block::TYPE_AUTO ) {
			$block = $this->newFromID( $target );
			return $block ? [ $block ] : [];
		} elseif ( $target === null && $vagueTarget == '' ) {
			// We're not going to find anything useful here
			// Be aware that the == '' check is explicit, since empty values will be
			// passed by some callers (T31116)
			return [];
		} elseif ( in_array(
			$type,
			[ Block::TYPE_USER, Block::TYPE_IP, Block::TYPE_RANGE, null ] )
		) {
			return $this->newLoad( $target, $type, $fromPrimary, $vagueTarget );
		}
		return [];
	}

	/**
	 * Get all blocks that match any IP from an array of IP addresses
	 *
	 * @since 1.42
	 * @param string[] $addresses Validated list of IP addresses
	 * @param bool $applySoftBlocks Include soft blocks (anonymous-only blocks). These
	 *     should only block anonymous and temporary users.
	 * @param bool $fromPrimary Whether to query the primary or replica DB
	 * @return DatabaseBlock[]
	 */
	public function newListFromIPs( array $addresses, $applySoftBlocks, $fromPrimary = false ) {
		if ( $addresses === [] ) {
			return [];
		}

		$conds = [];
		foreach ( array_unique( $addresses ) as $ipaddr ) {
			// Check both the original IP (to check against single blocks), as well as build
			// the clause to check for range blocks for the given IP.
			$conds['ipb_address'][] = $ipaddr;
			$conds[] = $this->getRangeCond( IPUtils::toHex( $ipaddr ), null, self::SCHEMA_IPBLOCKS );
		}

		if ( $conds === [] ) {
			return [];
		}

		if ( $fromPrimary ) {
			$db = $this->getPrimaryDB();
		} else {
			$db = $this->getReplicaDB();
		}
		$conds = $db->makeList( $conds, LIST_OR );
		if ( !$applySoftBlocks ) {
			$conds = [ $conds, 'ipb_anon_only' => 0 ];
		}
		$blockQuery = $this->getQueryInfo( self::SCHEMA_IPBLOCKS );
		$rows = $db->select(
			$blockQuery['tables'],
			array_merge( [ 'ipb_range_start', 'ipb_range_end' ], $blockQuery['fields'] ),
			$conds,
			__METHOD__,
			[],
			$blockQuery['joins']
		);

		$blocks = [];
		foreach ( $rows as $row ) {
			$block = $this->newFromRow( $db, $row );
			if ( !$block->isExpired() ) {
				$blocks[] = $block;
			}
		}

		return $blocks;
	}

	// endregion -- end of database read methods

	/***************************************************************************/
	// region   Database write methods
	/** @name   Database write methods */

	/**
	 * Delete expired blocks from the ipblocks table
	 *
	 * @internal only public for use in DatabaseBlock
	 */
	public function purgeExpiredBlocks() {
		if ( $this->readOnlyMode->isReadOnly( $this->wikiId ) ) {
			return;
		}

		$dbw = $this->getPrimaryDB();
		$store = $this->blockRestrictionStore;
		$limit = $this->options->get( MainConfigNames::UpdateRowsPerQuery );

		DeferredUpdates::addUpdate( new AutoCommitUpdate(
			$dbw,
			__METHOD__,
			static function ( IDatabase $dbw, $fname ) use ( $store, $limit ) {
				$ids = $dbw->newSelectQueryBuilder()
					->select( 'ipb_id' )
					->from( 'ipblocks' )
					->where( $dbw->expr( 'ipb_expiry', '<', $dbw->timestamp() ) )
					// Set a limit to avoid causing replication lag (T301742)
					->limit( $limit )
					->caller( $fname )->fetchFieldValues();
				if ( $ids ) {
					$ids = array_map( 'intval', $ids );
					$store->deleteByBlockId( $ids );
					$dbw->newDeleteQueryBuilder()
						->deleteFrom( 'ipblocks' )
						->where( [ 'ipb_id' => $ids ] )
						->caller( $fname )->execute();
				}
			}
		) );
	}

	/**
	 * Throw an exception if the given database connection does not match the
	 * given wiki ID.
	 *
	 * @param string|false $expectedWiki
	 * @param ?IDatabase $db
	 */
	private function checkDatabaseDomain( $expectedWiki, ?IDatabase $db = null ) {
		if ( $db ) {
			$dbDomain = $db->getDomainID();
			$storeDomain = $this->loadBalancer->resolveDomainID( $expectedWiki );
			if ( $dbDomain !== $storeDomain ) {
				throw new InvalidArgumentException(
					"DB connection domain '$dbDomain' does not match '$storeDomain'"
				);
			}
		} else {
			if ( $expectedWiki !== $this->wikiId ) {
				throw new InvalidArgumentException(
					"Must provide a database connection for wiki '$expectedWiki'."
				);
			}
		}
	}

	private function getReplicaDB(): IDatabase {
		return $this->loadBalancer->getConnection( DB_REPLICA, [], $this->wikiId );
	}

	private function getPrimaryDB(): IDatabase {
		return $this->loadBalancer->getConnection( DB_PRIMARY, [], $this->wikiId );
	}

	/**
	 * Insert a block into the block table. Will fail if there is a conflicting
	 * block (same name and options) already in the database.
	 *
	 * @param DatabaseBlock $block
	 * @param IDatabase|null $database Database to use if not the same as the one in the load
	 *   balancer. Must connect to the wiki identified by $block->getBlocker->getWikiId().
	 *   Deprecated since 1.41, should be null. Use DatabaseBlockStoreFactory to fetch a
	 *   DatabaseBlockStore with a database injected.
	 * @return bool|array False on failure, assoc array on success:
	 *      ('id' => block ID, 'autoIds' => array of autoblock IDs)
	 */
	public function insertBlock(
		DatabaseBlock $block,
		IDatabase $database = null
	) {
		$blocker = $block->getBlocker();
		if ( !$blocker || $blocker->getName() === '' ) {
			throw new InvalidArgumentException( 'Cannot insert a block without a blocker set' );
		}

		if ( $database !== null ) {
			wfDeprecatedMsg(
				'Old method signature: Passing a $database is no longer supported',
				'1.41'
			);
		}

		$this->checkDatabaseDomain( $block->getWikiId(), $database );

		$this->logger->debug( 'Inserting block; timestamp ' . $block->getTimestamp() );

		$this->purgeExpiredBlocks();

		$dbw = $database ?: $this->getPrimaryDB();
		$row = $this->getArrayForDatabaseBlock( $block, $dbw );

		$dbw->newInsertQueryBuilder()
			->insertInto( 'ipblocks' )
			->ignore()
			->row( $row )
			->caller( __METHOD__ )->execute();
		$affected = $dbw->affectedRows();

		if ( $affected ) {
			$block->setId( $dbw->insertId() );
			$restrictions = $block->getRawRestrictions();
			if ( $restrictions ) {
				$this->blockRestrictionStore->insert( $restrictions );
			}
		}

		// Don't collide with expired blocks.
		// Do this after trying to insert to avoid locking.
		if ( !$affected ) {
			// T96428: The ipb_address index uses a prefix on a field, so
			// use a standard SELECT + DELETE to avoid annoying gap locks.
			$ids = $dbw->newSelectQueryBuilder()
				->select( 'ipb_id' )
				->from( 'ipblocks' )
				->where( [ 'ipb_address' => $row['ipb_address'], 'ipb_user' => $row['ipb_user'] ] )
				->andWhere( $dbw->expr( 'ipb_expiry', '<', $dbw->timestamp() ) )
				->caller( __METHOD__ )->fetchFieldValues();
			if ( $ids ) {
				$ids = array_map( 'intval', $ids );
				$dbw->newDeleteQueryBuilder()
					->deleteFrom( 'ipblocks' )
					->where( [ 'ipb_id' => $ids ] )
					->caller( __METHOD__ )->execute();
				$this->blockRestrictionStore->deleteByBlockId( $ids );
				$dbw->newInsertQueryBuilder()
					->insertInto( 'ipblocks' )
					->ignore()
					->row( $row )
					->caller( __METHOD__ )->execute();
				$affected = $dbw->affectedRows();
				if ( $affected ) {
					$block->setId( $dbw->insertId() );
					$restrictions = $block->getRawRestrictions();
					if ( $restrictions ) {
						$this->blockRestrictionStore->insert( $restrictions );
					}
				}
			}
		}

		if ( $affected ) {
			$autoBlockIds = $this->doRetroactiveAutoblock( $block );

			if ( $this->options->get( MainConfigNames::BlockDisablesLogin ) ) {
				$targetUserIdentity = $block->getTargetUserIdentity();
				if ( $targetUserIdentity ) {
					$targetUser = $this->userFactory->newFromUserIdentity( $targetUserIdentity );
					// TODO: respect the wiki the block belongs to here
					// Change user login token to force them to be logged out.
					$targetUser->setToken();
					$targetUser->saveSettings();
				}
			}

			return [ 'id' => $block->getId( $this->wikiId ), 'autoIds' => $autoBlockIds ];
		}

		return false;
	}

	/**
	 * Update a block in the DB with new parameters.
	 * The ID field needs to be loaded first.
	 *
	 * @param DatabaseBlock $block
	 * @return bool|array False on failure, array on success:
	 *   ('id' => block ID, 'autoIds' => array of autoblock IDs)
	 */
	public function updateBlock( DatabaseBlock $block ) {
		$this->logger->debug( 'Updating block; timestamp ' . $block->getTimestamp() );

		$this->checkDatabaseDomain( $block->getWikiId() );

		$blockId = $block->getId( $this->wikiId );
		if ( !$blockId ) {
			throw new InvalidArgumentException(
				__METHOD__ . " requires that a block id be set\n"
			);
		}

		$dbw = $this->getPrimaryDB();

		$row = $this->getArrayForDatabaseBlock( $block, $dbw );
		$dbw->startAtomic( __METHOD__ );

		$result = true;

		$dbw->newUpdateQueryBuilder()
			->update( 'ipblocks' )
			->set( $row )
			->where( [ 'ipb_id' => $blockId ] )
			->caller( __METHOD__ )->execute();

		// Only update the restrictions if they have been modified.
		$restrictions = $block->getRawRestrictions();
		if ( $restrictions !== null ) {
			// An empty array should remove all of the restrictions.
			if ( $restrictions === [] ) {
				$result = $this->blockRestrictionStore->deleteByBlockId( $blockId );
			} else {
				$result = $this->blockRestrictionStore->update( $restrictions );
			}
		}

		if ( $block->isAutoblocking() ) {
			// Update corresponding autoblock(s) (T50813)
			$dbw->newUpdateQueryBuilder()
				->update( 'ipblocks' )
				->set( $this->getArrayForAutoblockUpdate( $block ) )
				->where( [ 'ipb_parent_block_id' => $blockId ] )
				->caller( __METHOD__ )->execute();

			// Only update the restrictions if they have been modified.
			if ( $restrictions !== null ) {
				$this->blockRestrictionStore->updateByParentBlockId(
					$blockId,
					$restrictions
				);
			}
		} else {
			// Autoblock no longer required, delete corresponding autoblock(s)
			$ids = $dbw->newSelectQueryBuilder()
				->select( 'ipb_id' )
				->from( 'ipblocks' )
				->where( [ 'ipb_parent_block_id' => $blockId ] )
				->caller( __METHOD__ )->fetchFieldValues();
			if ( $ids ) {
				$ids = array_map( 'intval', $ids );
				$this->blockRestrictionStore->deleteByBlockId( $ids );
				$dbw->newDeleteQueryBuilder()
					->deleteFrom( 'ipblocks' )
					->where( [ 'ipb_id' => $ids ] )
					->caller( __METHOD__ )->execute();
			}
		}

		$dbw->endAtomic( __METHOD__ );

		if ( $result ) {
			$autoBlockIds = $this->doRetroactiveAutoblock( $block );
			return [ 'id' => $blockId, 'autoIds' => $autoBlockIds ];
		}

		return false;
	}

	/**
	 * Delete a DatabaseBlock from the database
	 *
	 * @param DatabaseBlock $block
	 * @return bool whether it was deleted
	 */
	public function deleteBlock( DatabaseBlock $block ): bool {
		if ( $this->readOnlyMode->isReadOnly( $this->wikiId ) ) {
			return false;
		}

		$this->checkDatabaseDomain( $block->getWikiId() );

		$blockId = $block->getId( $this->wikiId );

		if ( !$blockId ) {
			throw new InvalidArgumentException(
				__METHOD__ . " requires that a block id be set\n"
			);
		}
		$dbw = $this->getPrimaryDB();
		$ids = $dbw->newSelectQueryBuilder()
			->select( 'ipb_id' )
			->from( 'ipblocks' )
			->where( [ 'ipb_parent_block_id' => $blockId ] )
			->caller( __METHOD__ )->fetchFieldValues();
		$ids = array_map( 'intval', $ids );
		$ids[] = $blockId;

		$this->blockRestrictionStore->deleteByBlockId( $ids );
		$dbw->newDeleteQueryBuilder()
			->deleteFrom( 'ipblocks' )
			->where( [ 'ipb_id' => $ids ] )
			->caller( __METHOD__ )->execute();

		return $dbw->affectedRows() > 0;
	}

	/**
	 * Get an array suitable for passing to $dbw->insert() or $dbw->update()
	 *
	 * @param DatabaseBlock $block
	 * @param IDatabase $dbw Database to use if not the same as the one in the load balancer.
	 *                       Must connect to the wiki identified by $block->getBlocker->getWikiId().
	 * @return array
	 */
	private function getArrayForDatabaseBlock(
		DatabaseBlock $block,
		IDatabase $dbw
	): array {
		$expiry = $dbw->encodeExpiry( $block->getExpiry() );

		if ( $block->getTargetUserIdentity() ) {
			$userId = $block->getTargetUserIdentity()->getId( $this->wikiId );
		} else {
			$userId = 0;
		}
		$blocker = $block->getBlocker();
		if ( !$blocker ) {
			throw new \RuntimeException( __METHOD__ . ': this block does not have a blocker' );
		}
		// DatabaseBlockStore supports inserting cross-wiki blocks by passing
		// non-local IDatabase and blocker.
		$blockerActor = $this->actorStoreFactory
			->getActorStore( $dbw->getDomainID() )
			->acquireActorId( $blocker, $dbw );

		$blockArray = [
			'ipb_address'          => $block->getTargetName(),
			'ipb_user'             => $userId,
			'ipb_by_actor'         => $blockerActor,
			'ipb_timestamp'        => $dbw->timestamp( $block->getTimestamp() ),
			'ipb_auto'             => $block->getType() === AbstractBlock::TYPE_AUTO,
			'ipb_anon_only'        => !$block->isHardblock(),
			'ipb_create_account'   => $block->isCreateAccountBlocked(),
			'ipb_enable_autoblock' => $block->isAutoblocking(),
			'ipb_expiry'           => $expiry,
			'ipb_range_start'      => $block->getRangeStart(),
			'ipb_range_end'        => $block->getRangeEnd(),
			'ipb_deleted'          => intval( $block->getHideName() ), // typecast required for SQLite
			'ipb_block_email'      => $block->isEmailBlocked(),
			'ipb_allow_usertalk'   => $block->isUsertalkEditAllowed(),
			'ipb_parent_block_id'  => $block->getParentBlockId(),
			'ipb_sitewide'         => $block->isSitewide(),
		];
		$commentArray = $this->commentStore->insert(
			$dbw,
			'ipb_reason',
			$block->getReasonComment()
		);

		$combinedArray = $blockArray + $commentArray;
		return $combinedArray;
	}

	/**
	 * Get an array suitable for autoblock updates
	 *
	 * @param DatabaseBlock $block
	 * @return array
	 */
	private function getArrayForAutoblockUpdate( DatabaseBlock $block ): array {
		$blocker = $block->getBlocker();
		if ( !$blocker ) {
			throw new \RuntimeException( __METHOD__ . ': this block does not have a blocker' );
		}
		$dbw = $this->getPrimaryDB();
		$blockerActor = $this->actorStoreFactory
			->getActorNormalization( $this->wikiId )
			->acquireActorId( $blocker, $dbw );
		$blockArray = [
			'ipb_by_actor'       => $blockerActor,
			'ipb_create_account' => $block->isCreateAccountBlocked(),
			'ipb_deleted'        => (int)$block->getHideName(), // typecast required for SQLite
			'ipb_allow_usertalk' => $block->isUsertalkEditAllowed(),
			'ipb_sitewide'       => $block->isSitewide(),
		];

		$commentArray = $this->commentStore->insert(
			$dbw,
			'ipb_reason',
			$block->getReasonComment()
		);

		$combinedArray = $blockArray + $commentArray;
		return $combinedArray;
	}

	/**
	 * Handle retroactively autoblocking the last IP used by the user (if it is a user)
	 * blocked by an auto block.
	 *
	 * @param DatabaseBlock $block
	 * @return array IDs of retroactive autoblocks made
	 */
	private function doRetroactiveAutoblock( DatabaseBlock $block ): array {
		$autoBlockIds = [];
		// If autoblock is enabled, autoblock the LAST IP(s) used
		if ( $block->isAutoblocking() && $block->getType() == AbstractBlock::TYPE_USER ) {
			$this->logger->debug(
				'Doing retroactive autoblocks for ' . $block->getTargetName()
			);

			$hookAutoBlocked = [];
			$continue = $this->hookRunner->onPerformRetroactiveAutoblock(
				$block,
				$hookAutoBlocked
			);

			if ( $continue ) {
				$coreAutoBlocked = $this->performRetroactiveAutoblock( $block );
				$autoBlockIds = array_merge( $hookAutoBlocked, $coreAutoBlocked );
			} else {
				$autoBlockIds = $hookAutoBlocked;
			}
		}
		return $autoBlockIds;
	}

	/**
	 * Actually retroactively autoblocks the last IP used by the user (if it is a user)
	 * blocked by this block. This will use the recentchanges table.
	 *
	 * @param DatabaseBlock $block
	 * @return array
	 */
	private function performRetroactiveAutoblock( DatabaseBlock $block ): array {
		if ( !$this->options->get( MainConfigNames::PutIPinRC ) ) {
			// No IPs in the recent changes table to autoblock
			return [];
		}

		$type = $block->getType();
		if ( $type !== AbstractBlock::TYPE_USER ) {
			// Autoblocks only apply to users
			return [];
		}

		$dbr = $this->getReplicaDB();

		$targetUser = $block->getTargetUserIdentity();
		$actor = $targetUser ? $this->actorStoreFactory
			->getActorNormalization( $this->wikiId )
			->findActorId( $targetUser, $dbr ) : null;

		if ( !$actor ) {
			$this->logger->debug( 'No actor found to retroactively autoblock' );
			return [];
		}

		$rcIp = $dbr->newSelectQueryBuilder()
			->select( 'rc_ip' )
			->from( 'recentchanges' )
			->where( [ 'rc_actor' => $actor ] )
			->orderBy( 'rc_timestamp', SelectQueryBuilder::SORT_DESC )
			->caller( __METHOD__ )->fetchField();

		if ( !$rcIp ) {
			$this->logger->debug( 'No IP found to retroactively autoblock' );
			return [];
		}

		$id = $this->doAutoblock( $block, $rcIp );
		if ( !$id ) {
			return [];
		}
		return [ $id ];
	}

	/**
	 * Autoblocks the given IP, referring to the specified block.
	 *
	 * @since 1.42
	 * @param DatabaseBlock $parentBlock
	 * @param string $autoblockIP The IP to autoblock.
	 * @return int|false ID if an autoblock was inserted, false if not.
	 */
	public function doAutoblock( DatabaseBlock $parentBlock, $autoblockIP ) {
		// If autoblocks are disabled, go away.
		if ( !$parentBlock->isAutoblocking() ) {
			return false;
		}
		$this->checkDatabaseDomain( $parentBlock->getWikiId() );

		[ $target, $type ] = $this->blockUtils->parseBlockTarget( $autoblockIP );
		if ( $type != Block::TYPE_IP ) {
			$this->logger->debug( "Autoblock not supported for ip ranges." );
			return false;
		}
		$target = (string)$target;

		// Check if autoblock exempt.
		if ( $this->autoblockExemptionList->isExempt( $target ) ) {
			return false;
		}

		// Allow hooks to cancel the autoblock.
		if ( !$this->hookRunner->onAbortAutoblock( $target, $parentBlock ) ) {
			$this->logger->debug( "Autoblock aborted by hook." );
			return false;
		}

		// It's okay to autoblock. Go ahead and insert/update the block...

		// Do not add a *new* block if the IP is already blocked.
		$dbr = $this->getReplicaDB();

		$blockQuery = $this->getQueryInfo( self::SCHEMA_IPBLOCKS );

		$res = $dbr->select(
			$blockQuery['tables'],
			$blockQuery['fields'],
			[ 'ipb_address' => $target ],
			__METHOD__,
			[],
			$blockQuery['joins']
		);

		$blocks = [];
		foreach ( $res as $row ) {
			$block = $this->newFromRow( $dbr, $row );

			if ( $block->isExpired() ) {
				continue;
			}

			$blocks[] = $block;
		}
		$ipblock = $this->chooseMostSpecificBlock( $blocks );

		if ( $ipblock ) {
			// Check if the block is an autoblock and would exceed the user block
			// if renewed. If so, do nothing, otherwise prolong the block time...
			if ( $ipblock->getType() === Block::TYPE_AUTO
				&& $parentBlock->getExpiry() > $ipblock->getExpiry()
			) {
				// Reset block timestamp to now and its expiry to
				// $wgAutoblockExpiry in the future
				$this->updateTimestamp( $ipblock );
			}
			return false;
		}
		$blocker = $parentBlock->getBlocker();
		if ( !$blocker ) {
			throw new \RuntimeException( __METHOD__ . ': this block does not have a blocker' );
		}

		$timestamp = wfTimestampNow();
		if ( $parentBlock->getExpiry() == 'infinity' ) {
			// Original block was indefinite, start an autoblock now
			$expiry = $this->getAutoblockExpiry( $timestamp );
		} else {
			// If the user is already blocked with an expiry date, we don't
			// want to pile on top of that.
			$expiry = min(
				$parentBlock->getExpiry(),
				$this->getAutoblockExpiry( $timestamp )
			);
		}

		$autoblock = new DatabaseBlock( [
			'wiki' => $this->wikiId,
			'address' => UserIdentityValue::newAnonymous( $target, $this->wikiId ),
			'by' => $blocker,
			'reason' =>
				wfMessage(
					'autoblocker',
					$parentBlock->getTargetName(),
					$parentBlock->getReasonComment()->text
				)->inContentLanguage()->plain(),
			'decodedTimestamp' => $timestamp,
			'auto' => true,
			'createAccount' => $parentBlock->isCreateAccountBlocked(),
			// Continue suppressing the name if needed
			'hideName' => $parentBlock->getHideName(),
			'allowUsertalk' => $parentBlock->isUsertalkEditAllowed(),
			'parentBlockId' => $parentBlock->getId( $this->wikiId ),
			'sitewide' => $parentBlock->isSitewide(),
			'restrictions' => $parentBlock->getRestrictions(),
			'decodedExpiry' => $expiry,
		] );

		$this->logger->debug( "Autoblocking {$parentBlock->getTargetName()}@" . $target );

		$status = $this->insertBlock( $autoblock );
		return $status
			? $status['id']
			: false;
	}

	/**
	 * Update the timestamp on autoblocks.
	 *
	 * @param DatabaseBlock $block
	 */
	public function updateTimestamp( DatabaseBlock $block ) {
		$this->checkDatabaseDomain( $block->getWikiId() );
		if ( $block->getType() === Block::TYPE_AUTO ) {
			$block->setTimestamp( wfTimestamp() );
			$block->setExpiry( $this->getAutoblockExpiry( $block->getTimestamp() ) );

			$dbw = $this->getPrimaryDB();
			$dbw->newUpdateQueryBuilder()
				->update( 'ipblocks' )
				->set(
					[
						'ipb_timestamp' => $dbw->timestamp( $block->getTimestamp() ),
						'ipb_expiry' => $dbw->timestamp( $block->getExpiry() ),
					]
				)
				->where( [ 'ipb_id' => $block->getId( $this->wikiId ) ] )
				->caller( __METHOD__ )->execute();
		}
	}

	/**
	 * Get the expiry timestamp for an autoblock created at the given time.
	 *
	 * @internal Public to support deprecated DatabaseBlock method
	 * @param string|int $timestamp
	 * @return string
	 */
	public function getAutoblockExpiry( $timestamp ) {
		$autoblockExpiry = $this->options->get( MainConfigNames::AutoblockExpiry );

		return wfTimestamp( TS_MW, (int)wfTimestamp( TS_UNIX, $timestamp ) + $autoblockExpiry );
	}

	// endregion -- end of database write methods

}
