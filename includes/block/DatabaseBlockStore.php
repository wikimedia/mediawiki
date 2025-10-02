<?php
/**
 * Class for DatabaseBlock objects to interact with the database
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Block;

use InvalidArgumentException;
use MediaWiki\CommentStore\CommentStore;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Deferred\AutoCommitUpdate;
use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\MainConfigNames;
use MediaWiki\Session\SessionManagerInterface;
use MediaWiki\User\ActorStoreFactory;
use MediaWiki\User\TempUser\TempUserConfig;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserIdentity;
use Psr\Log\LoggerInterface;
use RuntimeException;
use stdClass;
use Wikimedia\IPUtils;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\IExpression;
use Wikimedia\Rdbms\IReadableDatabase;
use Wikimedia\Rdbms\IResultWrapper;
use Wikimedia\Rdbms\LikeValue;
use Wikimedia\Rdbms\RawSQLExpression;
use Wikimedia\Rdbms\RawSQLValue;
use Wikimedia\Rdbms\ReadOnlyMode;
use Wikimedia\Rdbms\SelectQueryBuilder;
use function array_key_exists;

/**
 * @since 1.36
 *
 * @author DannyS712
 */
class DatabaseBlockStore {
	/** Load all autoblocks */
	public const AUTO_ALL = 'all';
	/** Load only autoblocks specified by ID */
	public const AUTO_SPECIFIED = 'specified';
	/** Do not load autoblocks */
	public const AUTO_NONE = 'none';

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

	private string|false $wikiId;

	private ServiceOptions $options;
	private LoggerInterface $logger;
	private ActorStoreFactory $actorStoreFactory;
	private BlockRestrictionStore $blockRestrictionStore;
	private CommentStore $commentStore;
	private HookRunner $hookRunner;
	private IConnectionProvider $dbProvider;
	private ReadOnlyMode $readOnlyMode;
	private UserFactory $userFactory;
	private TempUserConfig $tempUserConfig;
	private BlockTargetFactory $blockTargetFactory;
	private AutoblockExemptionList $autoblockExemptionList;
	private SessionManagerInterface $sessionManager;

	public function __construct(
		ServiceOptions $options,
		LoggerInterface $logger,
		ActorStoreFactory $actorStoreFactory,
		BlockRestrictionStore $blockRestrictionStore,
		CommentStore $commentStore,
		HookContainer $hookContainer,
		IConnectionProvider $dbProvider,
		ReadOnlyMode $readOnlyMode,
		UserFactory $userFactory,
		TempUserConfig $tempUserConfig,
		BlockTargetFactory $blockTargetFactory,
		AutoblockExemptionList $autoblockExemptionList,
		SessionManagerInterface $sessionManager,
		string|false $wikiId = DatabaseBlock::LOCAL
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );

		$this->wikiId = $wikiId;

		$this->options = $options;
		$this->logger = $logger;
		$this->actorStoreFactory = $actorStoreFactory;
		$this->blockRestrictionStore = $blockRestrictionStore;
		$this->commentStore = $commentStore;
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->dbProvider = $dbProvider;
		$this->readOnlyMode = $readOnlyMode;
		$this->userFactory = $userFactory;
		$this->tempUserConfig = $tempUserConfig;
		$this->blockTargetFactory = $blockTargetFactory;
		$this->autoblockExemptionList = $autoblockExemptionList;
		$this->sessionManager = $sessionManager;
	}

	/***************************************************************************/
	// region   Database read methods
	/** @name   Database read methods */

	/**
	 * Load a block from the block ID.
	 *
	 * @since 1.42
	 * @param int $id ID to search for
	 * @param bool $fromPrimary Whether to use the DB_PRIMARY database (since 1.44)
	 * @param bool $includeExpired Whether to include expired blocks (since 1.44)
	 * @return DatabaseBlock|null
	 */
	public function newFromID( $id, $fromPrimary = false, $includeExpired = false ) {
		$blocks = $this->newListFromConds( [ 'bl_id' => $id ], $fromPrimary, $includeExpired );
		return $blocks ? $blocks[0] : null;
	}

	/**
	 * Return the tables, fields, and join conditions to be selected to create
	 * a new block object.
	 *
	 * @since 1.42
	 * @internal Prefer newListFromConds() and deleteBlocksMatchingConds().
	 *
	 * @return array[] With three keys:
	 *   - tables: (string[]) to include in the `$table` to `IDatabase->select()`
	 *     or `SelectQueryBuilder::tables`
	 *   - fields: (string[]) to include in the `$vars` to `IDatabase->select()`
	 *     or `SelectQueryBuilder::fields`
	 *   - joins: (array) to include in the `$join_conds` to `IDatabase->select()`
	 *     or `SelectQueryBuilder::joinConds`
	 * @phan-return array{tables:string[],fields:string[],joins:array}
	 */
	public function getQueryInfo() {
		$commentQuery = $this->commentStore->getJoin( 'bl_reason' );
		return [
			'tables' => [
				'block',
				'block_target',
				'block_by_actor' => 'actor',
			] + $commentQuery['tables'],
			'fields' => [
				'bl_id',
				'bt_address',
				'bt_user',
				'bt_user_text',
				'bl_timestamp',
				'bt_auto',
				'bl_anon_only',
				'bl_create_account',
				'bl_enable_autoblock',
				'bl_expiry',
				'bl_deleted',
				'bl_block_email',
				'bl_allow_usertalk',
				'bl_parent_block_id',
				'bl_sitewide',
				'bl_by_actor',
				'bl_by' => 'block_by_actor.actor_user',
				'bl_by_text' => 'block_by_actor.actor_name',
			] + $commentQuery['fields'],
			'joins' => [
				'block_target' => [ 'JOIN', 'bt_id=bl_target' ],
				'block_by_actor' => [ 'JOIN', 'actor_id=bl_by_actor' ],
			] + $commentQuery['joins'],
		];
	}

	/**
	 * Load blocks from the database which target the specific target exactly, or which cover the
	 * vague target.
	 *
	 * @param BlockTarget|null $specificTarget
	 * @param bool $fromPrimary
	 * @param BlockTarget|null $vagueTarget Also search for blocks affecting
	 *     this target. Doesn't make any sense to use TYPE_AUTO here. Leave blank to
	 *     skip IP lookups.
	 * @param string $auto One of the self::AUTO_* constants
	 * @return DatabaseBlock[] Any relevant blocks
	 */
	private function newLoad(
		$specificTarget,
		$fromPrimary,
		$vagueTarget = null,
		$auto = self::AUTO_ALL
	) {
		if ( $fromPrimary ) {
			$db = $this->getPrimaryDB();
		} else {
			$db = $this->getReplicaDB();
		}

		$userIds = [];
		$userNames = [];
		$addresses = [];
		$ranges = [];
		if ( $specificTarget instanceof UserBlockTarget ) {
			$userId = $specificTarget->getUserIdentity()->getId( $this->wikiId );
			if ( $userId ) {
				$userIds[] = $userId;
			} else {
				// A nonexistent user can have no blocks.
				// This case is hit in testing, possibly production too.
				// Ignoring the user is optimal for production performance.
			}
		} elseif ( $specificTarget instanceof AnonIpBlockTarget
			|| $specificTarget instanceof RangeBlockTarget
		) {
			$addresses[] = (string)$specificTarget;
		}

		// Be aware that the != '' check is explicit, since empty values will be
		// passed by some callers (T31116)
		if ( $vagueTarget !== null ) {
			if ( $vagueTarget instanceof UserBlockTarget ) {
				// Slightly weird, but who are we to argue?
				$vagueUser = $vagueTarget->getUserIdentity();
				$userId = $vagueUser->getId( $this->wikiId );
				if ( $userId ) {
					$userIds[] = $userId;
				} else {
					$userNames[] = $vagueUser->getName();
				}
			} elseif ( $vagueTarget instanceof BlockTargetWithIp ) {
				$ranges[] = $vagueTarget->toHexRange();
			} else {
				$this->logger->debug( "Ignoring invalid vague target" );
			}
		}

		$orConds = [];
		if ( $userIds ) {
			// @phan-suppress-next-line PhanTypeMismatchArgument -- array_unique() result is non-empty
			$orConds[] = $db->expr( 'bt_user', '=', array_unique( $userIds ) );
		}
		if ( $userNames ) {
			// Add bt_ip_hex to the condition since it is in the index
			$orConds[] = $db->expr( 'bt_ip_hex', '=', null )
				// @phan-suppress-next-line PhanTypeMismatchArgument -- array_unique() result is non-empty
				->and( 'bt_user_text', '=', array_unique( $userNames ) );
		}
		if ( $addresses ) {
			// @phan-suppress-next-line PhanTypeMismatchArgument
			$orConds[] = $db->expr( 'bt_address', '=', array_unique( $addresses ) );
		}
		foreach ( $this->getConditionForRanges( $ranges ) as $cond ) {
			$orConds[] = new RawSQLExpression( $cond );
		}
		if ( !$orConds ) {
			return [];
		}

		// Exclude autoblocks unless AUTO_ALL was requested.
		$autoConds = $auto === self::AUTO_ALL ? [] : [ 'bt_auto' => 0 ];

		$blockQuery = $this->getQueryInfo();
		$res = $db->newSelectQueryBuilder()
			->queryInfo( $blockQuery )
			->where( $db->orExpr( $orConds ) )
			->andWhere( $autoConds )
			->caller( __METHOD__ )
			->fetchResultSet();

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
				$specificTarget instanceof UserBlockTarget &&
				!$block->isHardblock() &&
				!$this->tempUserConfig->isTempName( $specificTarget->toString() )
			) {
				continue;
			}

			// Check for duplicate autoblocks
			if ( $block->getType() === Block::TYPE_AUTO ) {
				$autoBlocks[] = $block;
			} else {
				$blocks[] = $block;
				$blockIds[] = $block->getId( $this->wikiId );
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
			$score = $block->getTarget()->getSpecificity();
			if ( $score < $bestBlockScore ) {
				$bestBlockScore = $score;
				$bestBlock = $block;
			}
		}

		return $bestBlock;
	}

	/**
	 * Get a set of SQL conditions which select range blocks encompassing the
	 * given ranges. For each range that is really a single IP (start=end), it will
	 * also select single IP blocks with that IP.
	 *
	 * @since 1.44
	 * @param string[][] $ranges List of elements with `[ start, end ]`, where `start` and `end` are hexadecimal
	 * IP representation, and `end` can be null to use `end = start`.
	 * @phan-param list<array{0:string,1:?string}> $ranges
	 * @return string[] List of conditions to be ORed.
	 */
	public function getConditionForRanges( array $ranges ): array {
		$dbr = $this->getReplicaDB();

		$conds = [];
		$individualIPs = [];
		foreach ( $ranges as [ $start, $end ] ) {
			// Per T16634, we want to include relevant active range blocks; for
			// range blocks, we want to include larger ranges which enclose the given
			// range. We know that all blocks must be smaller than $wgBlockCIDRLimit,
			// so we can improve performance by filtering on a LIKE clause
			$chunk = $this->getIpFragment( $start );
			$end ??= $start;

			$expr = $dbr->expr(
				'bt_range_start',
				IExpression::LIKE,
				new LikeValue( $chunk, $dbr->anyString() )
			)
				->and( 'bt_range_start', '<=', $start )
				->and( 'bt_range_end', '>=', $end );
			if ( $start === $end ) {
				$individualIPs[] = $start;
			}
			$conds[] = $expr->toSql( $dbr );
		}
		if ( $individualIPs ) {
			// Also select single IP blocks for these targets
			$conds[] = $dbr->expr( 'bt_ip_hex', '=', $individualIPs )
				->and( 'bt_range_start', '=', null )
				->toSql( $dbr );
		}
		return $conds;
	}

	/**
	 * Get a set of SQL conditions which select range blocks encompassing a
	 * given range. If the given range is a single IP with start=end, it will
	 * also select single IP blocks with that IP.
	 *
	 * @since 1.42
	 * @param string $start Hexadecimal IP representation
	 * @param string|null $end Hexadecimal IP representation, or null to use $start = $end
	 * @return string
	 */
	public function getRangeCond( $start, $end ) {
		$dbr = $this->getReplicaDB();
		$conds = $this->getConditionForRanges( [ [ $start, $end ] ] );
		return $dbr->makeList( $conds, IDatabase::LIST_OR );
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
	 * @param stdClass $row Row from the block table
	 * @return DatabaseBlock
	 */
	public function newFromRow( IReadableDatabase $db, $row ) {
		return new DatabaseBlock( [
			'target' => $this->blockTargetFactory->newFromRowRaw( $row ),
			'wiki' => $this->wikiId,
			'timestamp' => $row->bl_timestamp,
			'auto' => (bool)$row->bt_auto,
			'hideName' => (bool)$row->bl_deleted,
			'id' => (int)$row->bl_id,
			// Blocks with no parent ID should have bl_parent_block_id as null,
			// don't save that as 0 though, see T282890
			'parentBlockId' => $row->bl_parent_block_id
				? (int)$row->bl_parent_block_id : null,
			'by' => $this->actorStoreFactory
				->getActorStore( $this->wikiId )
				->newActorFromRowFields( $row->bl_by, $row->bl_by_text, $row->bl_by_actor ),
			'decodedExpiry' => $db->decodeExpiry( $row->bl_expiry ),
			'reason' => $this->commentStore->getComment( 'bl_reason', $row ),
			'anonOnly' => $row->bl_anon_only,
			'enableAutoblock' => (bool)$row->bl_enable_autoblock,
			'sitewide' => (bool)$row->bl_sitewide,
			'createAccount' => (bool)$row->bl_create_account,
			'blockEmail' => (bool)$row->bl_block_email,
			'allowUsertalk' => (bool)$row->bl_allow_usertalk
		] );
	}

	/**
	 * Given a target and the target's type, get an existing block object if possible.
	 *
	 * @since 1.42
	 * @param BlockTarget|string|UserIdentity|int|null $specificTarget A block target, which may be one of
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
	 * @param BlockTarget|string|UserIdentity|int|null $vagueTarget As above, but we will search for *any*
	 *     block which affects that target (so for an IP address, get ranges containing that IP;
	 *     and also get any relevant autoblocks). Leave empty or blank to skip IP-based lookups.
	 * @param bool $fromPrimary Whether to use the DB_PRIMARY database
	 * @param string $auto Since 1.44. One of the self::AUTO_* constants:
	 *    - AUTO_ALL: always load autoblocks
	 *    - AUTO_SPECIFIED: load only autoblocks specified in the input by ID
	 *    - AUTO_NONE: do not load autoblocks
	 * @return DatabaseBlock|null (null if no relevant block could be found). The target and type
	 *     of the returned block will refer to the actual block which was found, which might
	 *     not be the same as the target you gave if you used $vagueTarget!
	 */
	public function newFromTarget(
		$specificTarget,
		$vagueTarget = null,
		$fromPrimary = false,
		$auto = self::AUTO_ALL
	) {
		$blocks = $this->newListFromTarget( $specificTarget, $vagueTarget, $fromPrimary, $auto );
		return $this->chooseMostSpecificBlock( $blocks );
	}

	/**
	 * This is similar to DatabaseBlockStore::newFromTarget, but it returns all the relevant blocks.
	 *
	 * @since 1.42
	 * @param BlockTarget|string|UserIdentity|int|null $specificTarget
	 * @param BlockTarget|string|UserIdentity|int|null $vagueTarget
	 * @param bool $fromPrimary
	 * @param string $auto Since 1.44. One of the self::AUTO_* constants:
	 *   - AUTO_ALL: always load autoblocks
	 *   - AUTO_SPECIFIED: load only autoblocks specified in the input by ID
	 *   - AUTO_NONE: do not load autoblocks
	 * @return DatabaseBlock[] Any relevant blocks
	 */
	public function newListFromTarget(
		$specificTarget,
		$vagueTarget = null,
		$fromPrimary = false,
		$auto = self::AUTO_ALL
	) {
		if ( !( $specificTarget instanceof BlockTarget ) ) {
			$specificTarget = $this->blockTargetFactory->newFromLegacyUnion( $specificTarget );
		}
		if ( $vagueTarget !== null && !( $vagueTarget instanceof BlockTarget ) ) {
			$vagueTarget = $this->blockTargetFactory->newFromLegacyUnion( $vagueTarget );
		}
		if ( $specificTarget instanceof AutoBlockTarget ) {
			if ( $auto === self::AUTO_NONE ) {
				return [];
			}
			$block = $this->newFromID( $specificTarget->getId() );
			return $block ? [ $block ] : [];
		} elseif ( $specificTarget === null && $vagueTarget === null ) {
			// We're not going to find anything useful here
			return [];
		} else {
			return $this->newLoad( $specificTarget, $fromPrimary, $vagueTarget, $auto );
		}
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
		$addresses = array_unique( $addresses );
		if ( $addresses === [] ) {
			return [];
		}

		$ranges = [];
		foreach ( $addresses as $ipaddr ) {
			$ranges[] = [ IPUtils::toHex( $ipaddr ), null ];
		}
		$rangeConds = $this->getConditionForRanges( $ranges );

		if ( $fromPrimary ) {
			$db = $this->getPrimaryDB();
		} else {
			$db = $this->getReplicaDB();
		}
		$conds = $db->makeList( $rangeConds, LIST_OR );
		if ( !$applySoftBlocks ) {
			$conds = [ $conds, 'bl_anon_only' => 0 ];
		}
		$blockQuery = $this->getQueryInfo();
		$rows = $db->newSelectQueryBuilder()
			->queryInfo( $blockQuery )
			->fields( [ 'bt_range_start', 'bt_range_end' ] )
			->where( $conds )
			->caller( __METHOD__ )
			->fetchResultSet();

		$blocks = [];
		foreach ( $rows as $row ) {
			$block = $this->newFromRow( $db, $row );
			if ( !$block->isExpired() ) {
				$blocks[] = $block;
			}
		}

		return $blocks;
	}

	/**
	 * Construct an array of blocks from database conditions.
	 *
	 * @since 1.42
	 * @param array $conds Query conditions, given as an associative array
	 *   mapping field names to values.
	 * @param bool $fromPrimary
	 * @param bool $includeExpired
	 * @return DatabaseBlock[]
	 */
	public function newListFromConds( $conds, $fromPrimary = false, $includeExpired = false ) {
		$db = $fromPrimary ? $this->getPrimaryDB() : $this->getReplicaDB();
		$conds = self::mapActorAlias( $conds );
		if ( !$includeExpired ) {
			$conds[] = $db->expr( 'bl_expiry', '>=', $db->timestamp() );
		}
		$res = $db->newSelectQueryBuilder()
			->queryInfo( $this->getQueryInfo() )
			->conds( $conds )
			->caller( __METHOD__ )
			->fetchResultSet();
		$blocks = [];
		foreach ( $res as $row ) {
			$blocks[] = $this->newFromRow( $db, $row );
		}
		return $blocks;
	}

	// endregion -- end of database read methods

	/***************************************************************************/
	// region   Database write methods
	/** @name   Database write methods */

	/**
	 * Create a DatabaseBlock representing an unsaved block. Pass the returned
	 * object to insertBlock().
	 *
	 * @since 1.44
	 *
	 * @param array $options Options as documented in DatabaseBlock and
	 *   AbstractBlock, and additionally:
	 *   - address: (string) A string specifying the block target. This is not
	 *     the same as the legacy address parameter which allows UserIdentity.
	 *   - targetUser: (UserIdentity) The UserIdentity to block
	 * @return DatabaseBlock
	 */
	public function newUnsaved( array $options ): DatabaseBlock {
		if ( isset( $options['targetUser'] ) ) {
			$options['target'] = $this->blockTargetFactory
				->newFromUser( $options['targetUser'] );
			unset( $options['targetUser'] );
		}
		if ( isset( $options['address'] ) ) {
			$target = $this->blockTargetFactory
				->newFromString( $options['address'] );
			if ( !$target ) {
				throw new InvalidArgumentException( 'Invalid target address' );
			}
			$options['target'] = $target;
			unset( $options['address'] );
		}
		return new DatabaseBlock( $options );
	}

	/**
	 * Delete expired blocks from the block table
	 *
	 * @internal only public for use in DatabaseBlock
	 */
	public function purgeExpiredBlocks() {
		if ( $this->readOnlyMode->isReadOnly( $this->wikiId ) ) {
			return;
		}

		$dbw = $this->getPrimaryDB();

		DeferredUpdates::addUpdate( new AutoCommitUpdate(
			$dbw,
			__METHOD__,
			function ( IDatabase $dbw, $fname ) {
				$limit = $this->options->get( MainConfigNames::UpdateRowsPerQuery );
				$res = $dbw->newSelectQueryBuilder()
					->select( [ 'bl_id', 'bl_target' ] )
					->from( 'block' )
					->where( $dbw->expr( 'bl_expiry', '<', $dbw->timestamp() ) )
					// Set a limit to avoid causing replication lag (T301742)
					->limit( $limit )
					->caller( $fname )->fetchResultSet();
				$this->deleteBlockRows( $res );
			}
		) );
	}

	/**
	 * Delete all blocks matching the given conditions.
	 *
	 * @since 1.42
	 * @param array $conds An associative array mapping the field name to the
	 *   matched value.
	 * @param int|null $limit The maximum number of blocks to delete
	 * @return int The number of blocks deleted
	 */
	public function deleteBlocksMatchingConds( array $conds, $limit = null ) {
		$dbw = $this->getPrimaryDB();
		$conds = self::mapActorAlias( $conds );
		$qb = $dbw->newSelectQueryBuilder()
			->select( [ 'bl_id', 'bl_target' ] )
			->from( 'block' )
			// Typical input conds need block_target
			->join( 'block_target', null, 'bt_id=bl_target' )
			->where( $conds )
			->caller( __METHOD__ );
		if ( self::hasActorAlias( $conds ) ) {
			$qb->join( 'actor', 'ipblocks_actor', 'actor_id=bl_by_actor' );
		}
		if ( $limit !== null ) {
			$qb->limit( $limit );
		}
		$res = $qb->fetchResultSet();
		return $this->deleteBlockRows( $res );
	}

	/**
	 * Helper for deleteBlocksMatchingConds()
	 *
	 * @param array $conds
	 * @return array
	 */
	private static function mapActorAlias( $conds ) {
		return self::mapConds(
			[
				'bl_by' => 'ipblocks_actor.actor_user',
			],
			$conds
		);
	}

	/**
	 * @param array $conds
	 * @return bool
	 */
	private static function hasActorAlias( $conds ) {
		return array_key_exists( 'ipblocks_actor.actor_user', $conds )
			|| array_key_exists( 'ipblocks_actor.actor_name', $conds );
	}

	/**
	 * Remap the keys in an array
	 *
	 * @param array $map
	 * @param array $conds
	 * @return array
	 */
	private static function mapConds( $map, $conds ) {
		$newConds = [];
		foreach ( $conds as $field => $value ) {
			if ( isset( $map[$field] ) ) {
				$newConds[$map[$field]] = $value;
			} else {
				$newConds[$field] = $value;
			}
		}
		return $newConds;
	}

	/**
	 * Delete rows from the block table and update the block_target
	 * and ipblocks_restrictions tables accordingly.
	 *
	 * @param IResultWrapper $rows Rows containing bl_id and bl_target
	 * @return int Number of deleted block rows
	 */
	private function deleteBlockRows( $rows ) {
		$ids = [];
		$deltasByTarget = [];
		foreach ( $rows as $row ) {
			$ids[] = (int)$row->bl_id;
			$target = (int)$row->bl_target;
			if ( !isset( $deltasByTarget[$target] ) ) {
				$deltasByTarget[$target] = 0;
			}
			$deltasByTarget[$target]++;
		}
		if ( !$ids ) {
			return 0;
		}
		$dbw = $this->getPrimaryDB();
		$dbw->startAtomic( __METHOD__ );

		$maxTargetCount = max( $deltasByTarget );
		for ( $delta = 1; $delta <= $maxTargetCount; $delta++ ) {
			$targetsWithThisDelta = array_keys( $deltasByTarget, $delta, true );
			if ( $targetsWithThisDelta ) {
				$this->releaseTargets( $dbw, $targetsWithThisDelta, $delta );
			}
		}

		$dbw->newDeleteQueryBuilder()
			->deleteFrom( 'block' )
			->where( [ 'bl_id' => $ids ] )
			->caller( __METHOD__ )->execute();
		$numDeleted = $dbw->affectedRows();
		$dbw->endAtomic( __METHOD__ );
		$this->blockRestrictionStore->deleteByBlockId( $ids );
		return $numDeleted;
	}

	/**
	 * Decrement the bt_count field of a set of block_target rows and delete
	 * the rows if the count falls to zero.
	 *
	 * @param IDatabase $dbw
	 * @param int[] $targetIds
	 * @param int $delta The amount to decrement by
	 */
	private function releaseTargets( IDatabase $dbw, $targetIds, int $delta = 1 ) {
		if ( !$targetIds ) {
			return;
		}
		$dbw->newUpdateQueryBuilder()
			->update( 'block_target' )
			->set( [ 'bt_count' => new RawSQLValue( "bt_count-$delta" ) ] )
			->where( [ 'bt_id' => $targetIds ] )
			->caller( __METHOD__ )
			->execute();
		$dbw->newDeleteQueryBuilder()
			->deleteFrom( 'block_target' )
			->where( [
				'bt_count<1',
				'bt_id' => $targetIds
			] )
			->caller( __METHOD__ )
			->execute();
	}

	private function getReplicaDB(): IReadableDatabase {
		return $this->dbProvider->getReplicaDatabase( $this->wikiId );
	}

	private function getPrimaryDB(): IDatabase {
		return $this->dbProvider->getPrimaryDatabase( $this->wikiId );
	}

	/**
	 * Insert a block into the block table. Will fail if there is a conflicting
	 * block (same name and options) already in the database.
	 *
	 * @param DatabaseBlock $block
	 * @param int|null $expectedTargetCount The expected number of existing blocks
	 *   on the specified target. If this is zero but there is an existing
	 *   block, the insertion will fail.
	 * @return bool|array False on failure, assoc array on success:
	 *   - id: block ID
	 *   - autoIds: array of autoblock IDs
	 *   - finalTargetCount: The updated number of blocks for the specified target.
	 */
	public function insertBlock(
		DatabaseBlock $block,
		$expectedTargetCount = 0
	) {
		$block->assertWiki( $this->wikiId );

		$blocker = $block->getBlocker();
		if ( !$blocker || $blocker->getName() === '' ) {
			throw new InvalidArgumentException( 'Cannot insert a block without a blocker set' );
		}

		if ( $expectedTargetCount instanceof IDatabase ) {
			throw new InvalidArgumentException(
				'Old method signature: Passing a custom database connection to '
					. 'DatabaseBlockStore::insertBlock is no longer supported'
			);
		}

		$this->logger->debug( 'Inserting block; timestamp ' . $block->getTimestamp() );

		// Purge expired blocks. This now just queues a deferred update, so it
		// is possible for expired blocks to conflict with inserted blocks below.
		$this->purgeExpiredBlocks();

		$dbw = $this->getPrimaryDB();
		$dbw->startAtomic( __METHOD__ );
		$finalTargetCount = $this->attemptInsert( $block, $dbw, $expectedTargetCount );
		$purgeDone = false;

		// Don't collide with expired blocks.
		// Do this after trying to insert to avoid locking.
		if ( !$finalTargetCount ) {
			if ( $this->purgeExpiredConflicts( $block, $dbw ) ) {
				$finalTargetCount = $this->attemptInsert( $block, $dbw, $expectedTargetCount );
				$purgeDone = true;
			}
		}
		$dbw->endAtomic( __METHOD__ );

		if ( $finalTargetCount > 1 && !$purgeDone ) {
			// Subtract expired blocks from the target count
			$expiredBlockCount = $this->getExpiredConflictingBlockRows( $block, $dbw )->count();
			if ( $expiredBlockCount >= $finalTargetCount ) {
				$finalTargetCount = 1;
			} else {
				$finalTargetCount -= $expiredBlockCount;
			}
		}

		if ( $finalTargetCount ) {
			$autoBlockIds = $this->doRetroactiveAutoblock( $block );

			if ( $this->options->get( MainConfigNames::BlockDisablesLogin ) ) {
				$targetUserIdentity = $block->getTargetUserIdentity();
				if ( $targetUserIdentity ) {
					$targetUser = $this->userFactory->newFromUserIdentity( $targetUserIdentity );
					$this->sessionManager->invalidateSessionsForUser( $targetUser );
				}
			}

			return [
				'id' => $block->getId( $this->wikiId ),
				'autoIds' => $autoBlockIds,
				'finalTargetCount' => $finalTargetCount
			];
		}

		return false;
	}

	/**
	 * Create a block with an array of parameters and immediately insert it.
	 * Throw an exception on failure. This is a convenience method for testing.
	 *
	 * Duplicate blocks for a given target are allowed by default.
	 *
	 * @since 1.44
	 * @param array $params Parameters for newUnsaved(), and also:
	 *   - expectedTargetCount: Use this to override conflict checking
	 * @return DatabaseBlock The inserted Block
	 */
	public function insertBlockWithParams( array $params ): DatabaseBlock {
		$block = $this->newUnsaved( $params );
		$status = $this->insertBlock( $block, $params['expectedTargetCount'] ?? null );
		if ( !$status ) {
			throw new RuntimeException( 'Failed to insert block' );
		}
		return $block;
	}

	/**
	 * Attempt to insert rows into block, block_target and ipblocks_restrictions.
	 * If there is a conflict, return false.
	 *
	 * @param DatabaseBlock $block
	 * @param IDatabase $dbw
	 * @param int|null $expectedTargetCount
	 * @return int|false The updated number of blocks for the target, or false on failure
	 */
	private function attemptInsert(
		DatabaseBlock $block,
		IDatabase $dbw,
		$expectedTargetCount
	) {
		[ $targetId, $finalCount ] = $this->acquireTarget( $block, $dbw, $expectedTargetCount );
		if ( !$targetId ) {
			return false;
		}
		$row = $this->getArrayForBlockUpdate( $block, $dbw );
		$row['bl_target'] = $targetId;
		$dbw->newInsertQueryBuilder()
			->insertInto( 'block' )
			->row( $row )
			->caller( __METHOD__ )->execute();
		if ( !$dbw->affectedRows() ) {
			return false;
		}
		$id = $dbw->insertId();

		if ( !$id ) {
			throw new RuntimeException( 'block insert ID is falsey' );
		}
		$block->setId( $id );
		$restrictions = $block->getRawRestrictions();
		if ( $restrictions ) {
			$this->blockRestrictionStore->insert( $restrictions );
		}

		return $finalCount;
	}

	/**
	 * Purge expired blocks that have the same target as the specified block
	 *
	 * @param DatabaseBlock $block
	 * @param IDatabase $dbw
	 * @return bool True if a conflicting block was deleted
	 */
	private function purgeExpiredConflicts(
		DatabaseBlock $block,
		IDatabase $dbw
	) {
		return (bool)$this->deleteBlockRows(
			$this->getExpiredConflictingBlockRows( $block, $dbw )
		);
	}

	/**
	 * Get rows with bl_id/bl_target for expired blocks that have the same
	 * target as the specified block.
	 *
	 * @param DatabaseBlock $block
	 * @param IDatabase $dbw
	 * @return IResultWrapper
	 */
	private function getExpiredConflictingBlockRows(
		DatabaseBlock $block,
		IDatabase $dbw
	) {
		// @phan-suppress-next-line PhanTypeMismatchArgumentNullable
		$targetConds = $this->getTargetConds( $block->getTarget() );
		return $dbw->newSelectQueryBuilder()
			->select( [ 'bl_id', 'bl_target' ] )
			->from( 'block' )
			->join( 'block_target', null, [ 'bt_id=bl_target' ] )
			->where( $targetConds )
			->andWhere( $dbw->expr( 'bl_expiry', '<', $dbw->timestamp() ) )
			->caller( __METHOD__ )->fetchResultSet();
	}

	/**
	 * Get conditions matching an existing block's block_target row
	 *
	 * @param BlockTarget $target
	 * @return array
	 */
	private function getTargetConds( BlockTarget $target ) {
		if ( $target instanceof UserBlockTarget ) {
			return [
				'bt_user' => $target->getUserIdentity()->getId( $this->wikiId )
			];
		} elseif ( $target instanceof AnonIpBlockTarget || $target instanceof RangeBlockTarget ) {
			return [ 'bt_address' => $target->toString() ];
		} else {
			throw new \InvalidArgumentException( 'Invalid target type' );
		}
	}

	/**
	 * Insert a new block_target row, or update bt_count in the existing target
	 * row for a given block, and return the target ID and new bt_count.
	 *
	 * An atomic section should be active while calling this function.
	 *
	 * @param DatabaseBlock $block
	 * @param IDatabase $dbw
	 * @param int|null $expectedTargetCount If this is zero and a row already
	 *   exists, abort the insert and return null. If this is greater than zero
	 *   and the pre-increment bt_count value does not match, abort the update
	 *   and return null. If this is null, do not perform any conflict checks.
	 * @return array{?int,int}
	 */
	private function acquireTarget(
		DatabaseBlock $block,
		IDatabase $dbw,
		$expectedTargetCount
	) {
		$target = $block->getTarget();
		// Note: for new autoblocks, the target is an IpBlockTarget
		$isAuto = $block->getType() === Block::TYPE_AUTO;
		if ( $target instanceof UserBlockTarget ) {
			$targetAddress = null;
			$targetUserName = (string)$target;
			$targetUserId = $target->getUserIdentity()->getId( $this->wikiId );
			$targetConds = [ 'bt_user' => $targetUserId ];
			$targetLockKey = $dbw->getDomainID() . ':block:u:' . $targetUserId;
		} else {
			$targetAddress = (string)$target;
			$targetUserName = null;
			$targetUserId = null;
			$targetConds = [
				'bt_address' => $targetAddress,
				'bt_auto' => $isAuto,
			];
			$targetLockKey = $dbw->getDomainID() . ':block:' .
				( $isAuto ? 'a' : 'i' ) . ':' . $targetAddress;
		}

		$condsWithCount = $targetConds;
		if ( $expectedTargetCount !== null ) {
			$condsWithCount['bt_count'] = $expectedTargetCount;
		}

		$dbw->lock( $targetLockKey, __METHOD__ );
		$func = __METHOD__;
		$dbw->onTransactionCommitOrIdle(
			static function () use ( $dbw, $targetLockKey, $func ) {
				$dbw->unlock( $targetLockKey, "$func.closure" );
			},
			__METHOD__
		);

		// This query locks the index gap when the target doesn't exist yet,
		// so there is a risk of throttling adjacent block insertions,
		// especially on small wikis which have larger gaps. If this proves to
		// be a problem, we could have getPrimaryDB() return an autocommit
		// connection.
		$dbw->newUpdateQueryBuilder()
			->update( 'block_target' )
			->set( [ 'bt_count' => new RawSQLValue( 'bt_count+1' ) ] )
			->where( $condsWithCount )
			->caller( __METHOD__ )->execute();
		$numUpdatedRows = $dbw->affectedRows();

		// Now that the row is locked, find the target ID
		$res = $dbw->newSelectQueryBuilder()
			->select( [ 'bt_id', 'bt_count' ] )
			->from( 'block_target' )
			->where( $targetConds )
			->forUpdate()
			->caller( __METHOD__ )
			->fetchResultSet();
		if ( $res->numRows() > 1 ) {
			$ids = [];
			foreach ( $res as $row ) {
				$ids[] = $row->bt_id;
			}
			throw new RuntimeException( "Duplicate block_target rows detected: " .
				implode( ',', $ids ) );
		}
		$row = $res->fetchObject();

		if ( $row ) {
			$count = (int)$row->bt_count;
			if ( !$numUpdatedRows ) {
				// ID found but count update failed -- must be a conflict due to bt_count mismatch
				return [ null, $count ];
			}
			$id = (int)$row->bt_id;
		} else {
			if ( $numUpdatedRows ) {
				throw new RuntimeException(
					'block_target row unexpectedly missing after we locked it' );
			}
			if ( $expectedTargetCount !== 0 && $expectedTargetCount !== null ) {
				// Conflict (expectation failure)
				return [ null, 0 ];
			}

			// Insert new row
			$targetRow = [
				'bt_address' => $targetAddress,
				'bt_user' => $targetUserId,
				'bt_user_text' => $targetUserName,
				'bt_auto' => $isAuto,
				'bt_range_start' => $block->getRangeStart(),
				'bt_range_end' => $block->getRangeEnd(),
				'bt_ip_hex' => $block->getIpHex(),
				'bt_count' => 1
			];
			$dbw->newInsertQueryBuilder()
				->insertInto( 'block_target' )
				->row( $targetRow )
				->caller( __METHOD__ )->execute();
			$id = $dbw->insertId();
			if ( !$id ) {
				throw new RuntimeException(
					'block_target insert ID is falsey despite unconditional insert' );
			}
			$count = 1;
		}

		return [ $id, $count ];
	}

	/**
	 * Update a block in the DB with new parameters.
	 * The ID field needs to be loaded first. The target must stay the same.
	 *
	 * TODO: remove the possibility of false return. The cases where this
	 *   happens are exotic enough that they should just be exceptions.
	 *
	 * @param DatabaseBlock $block
	 * @return bool|array False on failure, array on success:
	 *   ('id' => block ID, 'autoIds' => array of autoblock IDs)
	 */
	public function updateBlock( DatabaseBlock $block ) {
		$this->logger->debug( 'Updating block; timestamp ' . $block->getTimestamp() );

		$block->assertWiki( $this->wikiId );

		$blockId = $block->getId( $this->wikiId );
		if ( !$blockId ) {
			throw new InvalidArgumentException(
				__METHOD__ . ' requires that a block id be set'
			);
		}

		// Update bl_timestamp to current when making any updates to a block (T389275)
		$block->setTimestamp( wfTimestamp() );

		$dbw = $this->getPrimaryDB();

		$dbw->startAtomic( __METHOD__ );

		$row = $this->getArrayForBlockUpdate( $block, $dbw );
		$dbw->newUpdateQueryBuilder()
			->update( 'block' )
			->set( $row )
			->where( [ 'bl_id' => $blockId ] )
			->caller( __METHOD__ )->execute();

		// Only update the restrictions if they have been modified.
		$result = true;
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
				->update( 'block' )
				->set( $this->getArrayForAutoblockUpdate( $block ) )
				->where( [ 'bl_parent_block_id' => $blockId ] )
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
			$this->deleteBlocksMatchingConds( [ 'bl_parent_block_id' => $blockId ] );
		}

		$dbw->endAtomic( __METHOD__ );

		if ( $result ) {
			$autoBlockIds = $this->doRetroactiveAutoblock( $block );
			return [ 'id' => $blockId, 'autoIds' => $autoBlockIds ];
		}

		return false;
	}

	/**
	 * Update the target in the specified object and in the database. The block
	 * ID must be set.
	 *
	 * This is an unusual operation, currently used only by the UserMerge
	 * extension.
	 *
	 * @since 1.42
	 * @param DatabaseBlock $block
	 * @param BlockTarget|UserIdentity|string $newTarget
	 * @return bool True if the update was successful, false if there was no
	 *   match for the block ID.
	 */
	public function updateTarget( DatabaseBlock $block, $newTarget ) {
		$dbw = $this->getPrimaryDB();
		$blockId = $block->getId( $this->wikiId );
		if ( !$blockId ) {
			throw new InvalidArgumentException(
				__METHOD__ . " requires that a block id be set\n"
			);
		}
		if ( !( $newTarget instanceof BlockTarget ) ) {
			$newTarget = $this->blockTargetFactory->newFromLegacyUnion( $newTarget );
		}

		// @phan-suppress-next-line PhanTypeMismatchArgumentNullable
		$oldTargetConds = $this->getTargetConds( $block->getTarget() );
		$block->setTarget( $newTarget );

		$dbw->startAtomic( __METHOD__ );
		[ $targetId, $count ] = $this->acquireTarget( $block, $dbw, null );
		if ( !$targetId ) {
			// This is an exotic and unlikely error -- perhaps an exception should be thrown
			$dbw->endAtomic( __METHOD__ );
			return false;
		}
		$oldTargetId = $dbw->newSelectQueryBuilder()
			->select( 'bt_id' )
			->from( 'block_target' )
			->where( $oldTargetConds )
			->caller( __METHOD__ )->fetchField();
		$this->releaseTargets( $dbw, [ $oldTargetId ] );

		$dbw->newUpdateQueryBuilder()
			->update( 'block' )
			->set( [ 'bl_target' => $targetId ] )
			->where( [ 'bl_id' => $blockId ] )
			->caller( __METHOD__ )
			->execute();
		$affected = $dbw->affectedRows();
		$dbw->endAtomic( __METHOD__ );
		return (bool)$affected;
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

		$block->assertWiki( $this->wikiId );

		$blockId = $block->getId( $this->wikiId );

		if ( !$blockId ) {
			throw new InvalidArgumentException(
				__METHOD__ . ' requires that a block id be set'
			);
		}
		$dbw = $this->getPrimaryDB();
		$dbw->startAtomic( __METHOD__ );
		$res = $dbw->newSelectQueryBuilder()
			->select( [ 'bl_id', 'bl_target' ] )
			->from( 'block' )
			->where(
				$dbw->orExpr( [
					'bl_parent_block_id' => $blockId,
					'bl_id' => $blockId,
				] )
			)
			->caller( __METHOD__ )->fetchResultSet();
		$this->deleteBlockRows( $res );
		$affected = $res->numRows();
		$dbw->endAtomic( __METHOD__ );

		return $affected > 0;
	}

	/**
	 * Get an array suitable for passing to $dbw->insert() or $dbw->update()
	 *
	 * @param DatabaseBlock $block
	 * @param IDatabase $dbw Database to use if not the same as the one in the load balancer.
	 *                       Must connect to the wiki identified by $block->getBlocker->getWikiId().
	 * @return array
	 */
	private function getArrayForBlockUpdate(
		DatabaseBlock $block,
		IDatabase $dbw
	): array {
		$expiry = $dbw->encodeExpiry( $block->getExpiry() );

		$blocker = $block->getBlocker();
		if ( !$blocker ) {
			throw new RuntimeException( __METHOD__ . ': this block does not have a blocker' );
		}
		// DatabaseBlockStore supports inserting cross-wiki blocks by passing
		// non-local IDatabase and blocker.
		$blockerActor = $this->actorStoreFactory
			->getActorStore( $dbw->getDomainID() )
			->acquireActorId( $blocker, $dbw );

		$blockArray = [
			'bl_by_actor'         => $blockerActor,
			'bl_timestamp'        => $dbw->timestamp( $block->getTimestamp() ),
			'bl_anon_only'        => !$block->isHardblock(),
			'bl_create_account'   => $block->isCreateAccountBlocked(),
			'bl_enable_autoblock' => $block->isAutoblocking(),
			'bl_expiry'           => $expiry,
			'bl_deleted'          => intval( $block->getHideName() ), // typecast required for SQLite
			'bl_block_email'      => $block->isEmailBlocked(),
			'bl_allow_usertalk'   => $block->isUsertalkEditAllowed(),
			'bl_parent_block_id'  => $block->getParentBlockId(),
			'bl_sitewide'         => $block->isSitewide(),
		];
		$commentArray = $this->commentStore->insert(
			$dbw,
			'bl_reason',
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
			throw new RuntimeException( __METHOD__ . ': this block does not have a blocker' );
		}
		$dbw = $this->getPrimaryDB();
		$blockerActor = $this->actorStoreFactory
			->getActorNormalization( $this->wikiId )
			->acquireActorId( $blocker, $dbw );

		$blockArray = [
			'bl_by_actor'       => $blockerActor,
			'bl_create_account' => $block->isCreateAccountBlocked(),
			'bl_deleted'        => (int)$block->getHideName(), // typecast required for SQLite
			'bl_allow_usertalk' => $block->isUsertalkEditAllowed(),
			'bl_sitewide'       => $block->isSitewide(),
		];

		// Shorten the autoblock expiry if the parent block expiry is sooner.
		// Don't lengthen -- that is only done when the IP address is actually
		// used by the blocked user.
		if ( $block->getExpiry() !== 'infinity' ) {
			$blockArray['bl_expiry'] = new RawSQLValue( $dbw->conditional(
					$dbw->expr( 'bl_expiry', '>', $dbw->timestamp( $block->getExpiry() ) ),
					$dbw->addQuotes( $dbw->timestamp( $block->getExpiry() ) ),
					'bl_expiry'
				) );
		}

		$commentArray = $this->commentStore->insert(
			$dbw,
			'bl_reason',
			$this->getAutoblockReason( $block )
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

		$target = $block->getTarget();
		if ( !( $target instanceof UserBlockTarget ) ) {
			// Autoblocks only apply to users
			return [];
		}

		$dbr = $this->getReplicaDB();

		$actor = $this->actorStoreFactory
			->getActorNormalization( $this->wikiId )
			->findActorId( $target->getUserIdentity(), $dbr );

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
		$parentBlock->assertWiki( $this->wikiId );

		$target = $this->blockTargetFactory->newFromIp( $autoblockIP );
		if ( !$target ) {
			$this->logger->debug( "Invalid autoblock IP" );
			return false;
		}

		// Check if autoblock exempt.
		if ( $this->autoblockExemptionList->isExempt( $autoblockIP ) ) {
			return false;
		}

		// Allow hooks to cancel the autoblock.
		if ( !$this->hookRunner->onAbortAutoblock( $autoblockIP, $parentBlock ) ) {
			$this->logger->debug( "Autoblock aborted by hook." );
			return false;
		}

		// It's okay to autoblock. Go ahead and insert/update the block...

		// Do not add a *new* block if the IP is already blocked.
		$blocks = $this->newLoad( $target, false );
		if ( $blocks ) {
			foreach ( $blocks as $ipblock ) {
				// Check if the block is an autoblock and would exceed the user block
				// if renewed. If so, do nothing, otherwise prolong the block time...
				if ( $ipblock->getType() === Block::TYPE_AUTO
					&& $parentBlock->getExpiry() > $ipblock->getExpiry()
				) {
					// Reset block timestamp to now and its expiry to
					// $wgAutoblockExpiry in the future
					$this->updateTimestamp( $ipblock );
				}
			}
			return false;
		}
		$blocker = $parentBlock->getBlocker();
		if ( !$blocker ) {
			throw new RuntimeException( __METHOD__ . ': this block does not have a blocker' );
		}

		$timestamp = wfTimestampNow();
		$expiry = $this->getAutoblockExpiry( $timestamp, $parentBlock->getExpiry() );
		$autoblock = new DatabaseBlock( [
			'wiki' => $this->wikiId,
			'target' => $target,
			'by' => $blocker,
			'reason' => $this->getAutoblockReason( $parentBlock ),
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

	private function getAutoblockReason( DatabaseBlock $parentBlock ): string {
		return wfMessage(
			'autoblocker',
			$parentBlock->getTargetName(),
			$parentBlock->getReasonComment()->text
		)->inContentLanguage()->plain();
	}

	/**
	 * Update the timestamp on autoblocks.
	 *
	 * @internal Public to support deprecated DatabaseBlock::updateTimestamp()
	 * @param DatabaseBlock $block
	 */
	public function updateTimestamp( DatabaseBlock $block ) {
		$block->assertWiki( $this->wikiId );
		if ( $block->getType() !== Block::TYPE_AUTO ) {
			return;
		}
		$now = wfTimestamp();
		$block->setTimestamp( $now );
		// No need to reduce the autoblock expiry to the expiry of the parent
		// block, since the caller already checked for that.
		$block->setExpiry( $this->getAutoblockExpiry( $now ) );

		$dbw = $this->getPrimaryDB();
		$dbw->newUpdateQueryBuilder()
			->update( 'block' )
			->set(
				[
					'bl_timestamp' => $dbw->timestamp( $block->getTimestamp() ),
					'bl_expiry' => $dbw->timestamp( $block->getExpiry() ),
				]
			)
			->where( [ 'bl_id' => $block->getId( $this->wikiId ) ] )
			->caller( __METHOD__ )->execute();
	}

	/**
	 * Get the expiry timestamp for an autoblock created at the given time.
	 *
	 * If the parent block expiry is specified, the return value will be earlier
	 * than or equal to the parent block expiry.
	 *
	 * @internal Public to support deprecated DatabaseBlock method
	 * @param string|int $timestamp
	 * @param string|null $parentExpiry
	 * @return string
	 */
	public function getAutoblockExpiry( $timestamp, ?string $parentExpiry = null ) {
		$maxDuration = $this->options->get( MainConfigNames::AutoblockExpiry );
		$expiry = wfTimestamp( TS_MW, (int)wfTimestamp( TS_UNIX, $timestamp ) + $maxDuration );
		if ( $parentExpiry !== null && $parentExpiry !== 'infinity' ) {
			$expiry = min( $parentExpiry, $expiry );
		}
		return $expiry;
	}

	// endregion -- end of database write methods

}
