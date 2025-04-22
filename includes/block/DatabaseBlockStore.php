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

use InvalidArgumentException;
use MediaWiki\CommentStore\CommentStore;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Deferred\AutoCommitUpdate;
use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\MainConfigNames;
use MediaWiki\Session\SessionManager;
use MediaWiki\User\ActorStoreFactory;
use MediaWiki\User\TempUser\TempUserConfig;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityValue;
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
	/** The old schema */
	public const SCHEMA_IPBLOCKS = 'ipblocks';
	/** The new schema */
	public const SCHEMA_BLOCK = 'block';
	/** The schema currently selected by the read stage */
	public const SCHEMA_CURRENT = 'current';

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
	private BlockUtils $blockUtils;
	private AutoblockExemptionList $autoblockExemptionList;

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
		BlockUtils $blockUtils,
		AutoblockExemptionList $autoblockExemptionList,
		/* string|false */ $wikiId = DatabaseBlock::LOCAL
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
		$this->blockUtils = $blockUtils;
		$this->autoblockExemptionList = $autoblockExemptionList;
	}

	/**
	 * Get the read stage of the block_target migration
	 *
	 * @since 1.42
	 * @deprecated since 1.43
	 * @return int
	 */
	public function getReadStage() {
		wfDeprecated( __METHOD__, '1.43' );
		return SCHEMA_COMPAT_NEW;
	}

	/**
	 * Get the write stage of the block_target migration
	 *
	 * @since 1.42
	 * @deprecated since 1.43
	 * @return int
	 */
	public function getWriteStage() {
		wfDeprecated( __METHOD__, '1.43' );
		return SCHEMA_COMPAT_NEW;
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
		$blockQuery = $this->getQueryInfo();
		$res = $dbr->newSelectQueryBuilder()
			->queryInfo( $blockQuery )
			->where( [ 'bl_id' => $id ] )
			->caller( __METHOD__ )
			->fetchRow();
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
	 * @internal Avoid this method and DatabaseBlock::getQueryInfo() in new
	 *   external code, since they are not schema-independent. Use
	 *   newListFromConds() and deleteBlocksMatchingConds().
	 *
	 * @param string $schema What schema to use for field aliases. May be either
	 *   self::SCHEMA_IPBLOCKS or self::SCHEMA_BLOCK. This parameter will soon be
	 *   removed.
	 * @return array[] With three keys:
	 *   - tables: (string[]) to include in the `$table` to `IDatabase->select()`
	 *     or `SelectQueryBuilder::tables`
	 *   - fields: (string[]) to include in the `$vars` to `IDatabase->select()`
	 *     or `SelectQueryBuilder::fields`
	 *   - joins: (array) to include in the `$join_conds` to `IDatabase->select()`
	 *     or `SelectQueryBuilder::joinConds`
	 * @phan-return array{tables:string[],fields:string[],joins:array}
	 */
	public function getQueryInfo( $schema = self::SCHEMA_BLOCK ) {
		$commentQuery = $this->commentStore->getJoin( 'bl_reason' );
		if ( $schema === self::SCHEMA_IPBLOCKS ) {
			return [
				'tables' => [
					'block',
					'block_by_actor' => 'actor',
				] + $commentQuery['tables'],
				'fields' => [
					'ipb_id' => 'bl_id',
					'ipb_address' => 'COALESCE(bt_address, bt_user_text)',
					'ipb_timestamp' => 'bl_timestamp',
					'ipb_auto' => 'bt_auto',
					'ipb_anon_only' => 'bl_anon_only',
					'ipb_create_account' => 'bl_create_account',
					'ipb_enable_autoblock' => 'bl_enable_autoblock',
					'ipb_expiry' => 'bl_expiry',
					'ipb_deleted' => 'bl_deleted',
					'ipb_block_email' => 'bl_block_email',
					'ipb_allow_usertalk' => 'bl_allow_usertalk',
					'ipb_parent_block_id' => 'bl_parent_block_id',
					'ipb_sitewide' => 'bl_sitewide',
					'ipb_by_actor' => 'bl_by_actor',
					'ipb_by' => 'block_by_actor.actor_user',
					'ipb_by_text' => 'block_by_actor.actor_name',
					'ipb_reason_text' => $commentQuery['fields']['bl_reason_text'],
					'ipb_reason_data' => $commentQuery['fields']['bl_reason_data'],
					'ipb_reason_cid' => $commentQuery['fields']['bl_reason_cid'],
				],
				'joins' => [
					'block_by_actor' => [ 'JOIN', 'actor_id=bl_by_actor' ],
				] + $commentQuery['joins'],
			];
		} elseif ( $schema === self::SCHEMA_BLOCK ) {
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
		throw new InvalidArgumentException(
			'$schema must be SCHEMA_IPBLOCKS or SCHEMA_BLOCK' );
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

		$userIds = [];
		$userNames = [];
		$addresses = [];
		$ranges = [];
		if ( $specificType === Block::TYPE_USER ) {
			if ( $specificTarget instanceof UserIdentity ) {
				$userId = $specificTarget->getId( $this->wikiId );
				if ( $userId ) {
					$userIds[] = $specificTarget->getId( $this->wikiId );
				} else {
					// A nonexistent user can have no blocks.
					// This case is hit in testing, possibly production too.
					// Ignoring the user is optimal for production performance.
				}
			} else {
				$userNames[] = (string)$specificTarget;
			}
		} elseif ( in_array( $specificType, [ Block::TYPE_IP, Block::TYPE_RANGE ], true ) ) {
			$addresses[] = (string)$specificTarget;
		}

		// Be aware that the != '' check is explicit, since empty values will be
		// passed by some callers (T31116)
		if ( $vagueTarget != '' ) {
			[ $target, $type ] = $this->blockUtils->parseBlockTarget( $vagueTarget );
			switch ( $type ) {
				case Block::TYPE_USER:
					// Slightly weird, but who are we to argue?
					/** @var UserIdentity $vagueUser */
					$vagueUser = $target;
					if ( $vagueUser->getId( $this->wikiId ) ) {
						$userIds[] = $vagueUser->getId( $this->wikiId );
					} else {
						$userNames[] = $vagueUser->getName();
					}
					break;

				case Block::TYPE_IP:
					$ranges[] = [ IPUtils::toHex( $target ), null ];
					break;

				case Block::TYPE_RANGE:
					$ranges[] = IPUtils::parseRange( $target );
					break;

				default:
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
		foreach ( $ranges as $range ) {
			$orConds[] = new RawSQLExpression( $this->getRangeCond( $range[0], $range[1] ) );
		}
		if ( !$orConds ) {
			return [];
		}

		$blockQuery = $this->getQueryInfo();
		$res = $db->newSelectQueryBuilder()
			->queryInfo( $blockQuery )
			->where( $db->orExpr( $orConds ) )
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
				$specificType == Block::TYPE_USER && $specificTarget &&
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
	 * Get a set of SQL conditions which select range blocks encompassing a
	 * given range. If the given range is a single IP with start=end, it will
	 * also select single IP blocks with that IP.
	 *
	 * @since 1.42
	 * @param string $start Hexadecimal IP representation
	 * @param string|null $end Hexadecimal IP representation, or null to use $start = $end
	 * @param string $schema What schema to use for field aliases. Can be one of:
	 *    - self::SCHEMA_IPBLOCKS for the old schema
	 *    - self::SCHEMA_BLOCK for the new schema
	 *    - self::SCHEMA_CURRENT formerly used the configured schema, but now
	 *      acts the same as SCHEMA_BLOCK
	 *   In future this parameter will be removed.
	 * @return string
	 */
	public function getRangeCond( $start, $end, $schema = self::SCHEMA_BLOCK ) {
		// Per T16634, we want to include relevant active range blocks; for
		// range blocks, we want to include larger ranges which enclose the given
		// range. We know that all blocks must be smaller than $wgBlockCIDRLimit,
		// so we can improve performance by filtering on a LIKE clause
		$chunk = $this->getIpFragment( $start );
		$dbr = $this->getReplicaDB();
		$end ??= $start;

		if ( $schema === self::SCHEMA_CURRENT ) {
			$schema = self::SCHEMA_BLOCK;
		}

		if ( $schema === self::SCHEMA_IPBLOCKS ) {
			return $dbr->makeList(
				[
					$dbr->expr( 'ipb_range_start', IExpression::LIKE,
						new LikeValue( $chunk, $dbr->anyString() ) ),
					$dbr->expr( 'ipb_range_start', '<=', $start ),
					$dbr->expr( 'ipb_range_end', '>=', $end ),
				],
				LIST_AND
			);
		} elseif ( $schema === self::SCHEMA_BLOCK ) {
			$expr = $dbr->expr(
					'bt_range_start',
					IExpression::LIKE,
					new LikeValue( $chunk, $dbr->anyString() )
				)
				->and( 'bt_range_start', '<=', $start )
				->and( 'bt_range_end', '>=', $end );
			if ( $start === $end ) {
				// Also select single IP blocks for this target
				$expr = $dbr->orExpr( [
					$dbr->expr( 'bt_ip_hex', '=', $start )
						->and( 'bt_range_start', '=', null ),
					$expr
				] );
			}
			return $expr->toSql( $dbr );
		} else {
			throw new InvalidArgumentException(
				'$schema must be SCHEMA_IPBLOCKS or SCHEMA_BLOCK' );
		}
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
		if ( isset( $row->ipb_id ) ) {
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
		} else {
			$address = $row->bt_address
				?? new UserIdentityValue( $row->bt_user, $row->bt_user_text, $this->wikiId );
			return new DatabaseBlock( [
				'address' => $address,
				'wiki' => $this->wikiId,
				'timestamp' => $row->bl_timestamp,
				'auto' => (bool)$row->bt_auto,
				'hideName' => (bool)$row->bl_deleted,
				'id' => (int)$row->bl_id,
				// Blocks with no parent ID should have ipb_parent_block_id as null,
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
			$conds[] = $this->getRangeCond( IPUtils::toHex( $ipaddr ), null );
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
	 * @param array $conds For schema-independence this should be an associative
	 *   array mapping field names to values. Field names from the new schema
	 *   should be used.
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
	 * Delete expired blocks from the ipblocks table
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
	 *   matched value. Some limited schema abstractions are implemented, to
	 *   allow new field names to be used with the old schema.
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
			$this->releaseTargets( $dbw, $targetsWithThisDelta, $delta );
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
	 *      ('id' => block ID, 'autoIds' => array of autoblock IDs)
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
		$success = $this->attemptInsert( $block, $dbw, $expectedTargetCount );

		// Don't collide with expired blocks.
		// Do this after trying to insert to avoid locking.
		if ( !$success ) {
			if ( $this->purgeExpiredConflicts( $block, $dbw ) ) {
				$success = $this->attemptInsert( $block, $dbw, $expectedTargetCount );
			}
		}
		$dbw->endAtomic( __METHOD__ );

		if ( $success ) {
			$autoBlockIds = $this->doRetroactiveAutoblock( $block );

			if ( $this->options->get( MainConfigNames::BlockDisablesLogin ) ) {
				$targetUserIdentity = $block->getTargetUserIdentity();
				if ( $targetUserIdentity ) {
					$targetUser = $this->userFactory->newFromUserIdentity( $targetUserIdentity );
					SessionManager::singleton()->invalidateSessionsForUser( $targetUser );
				}
			}

			return [ 'id' => $block->getId( $this->wikiId ), 'autoIds' => $autoBlockIds ];
		}

		return false;
	}

	/**
	 * Attempt to insert rows into ipblocks/block, block_target and
	 * ipblocks_restrictions. If there is a conflict, return false.
	 *
	 * @param DatabaseBlock $block
	 * @param IDatabase $dbw
	 * @param int|null $expectedTargetCount
	 * @return bool True if block successfully inserted
	 */
	private function attemptInsert(
		DatabaseBlock $block,
		IDatabase $dbw,
		$expectedTargetCount
	) {
		$targetId = $this->acquireTarget( $block, $dbw, $expectedTargetCount );
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

		return true;
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
		$targetConds = $this->getTargetConds( $block );
		$res = $dbw->newSelectQueryBuilder()
			->select( [ 'bl_id', 'bl_target' ] )
			->from( 'block' )
			->join( 'block_target', null, [ 'bt_id=bl_target' ] )
			->where( $targetConds )
			->andWhere( $dbw->expr( 'bl_expiry', '<', $dbw->timestamp() ) )
			->caller( __METHOD__ )->fetchResultSet();
		return (bool)$this->deleteBlockRows( $res );
	}

	/**
	 * Get conditions matching the block's block_target row
	 *
	 * @param DatabaseBlock $block
	 * @return array
	 */
	private function getTargetConds( DatabaseBlock $block ) {
		if ( $block->getType() === Block::TYPE_USER ) {
			return [
				'bt_user' => $block->getTargetUserIdentity()->getId( $this->wikiId )
			];
		} else {
			return [ 'bt_address' => $block->getTargetName() ];
		}
	}

	/**
	 * Insert a new block_target row, or update bt_count in the existing target
	 * row for a given block, and return the target ID.
	 *
	 * An atomic section should be active while calling this function.
	 *
	 * @param DatabaseBlock $block
	 * @param IDatabase $dbw
	 * @param int|null $expectedTargetCount If this is zero and a row already
	 *   exists, abort the insert and return null. If this is greater than zero
	 *   and the pre-increment bt_count value does not match, abort the update
	 *   and return null. If this is null, do not perform any conflict checks.
	 * @return int|null
	 */
	private function acquireTarget(
		DatabaseBlock $block,
		IDatabase $dbw,
		$expectedTargetCount
	) {
		$isUser = $block->getType() === Block::TYPE_USER;
		$isRange = $block->getType() === Block::TYPE_RANGE;
		$isAuto = $block->getType() === Block::TYPE_AUTO;
		$isSingle = !$isUser && !$isRange;
		$targetAddress = $isUser ? null : $block->getTargetName();
		$targetUserName = $isUser ? $block->getTargetName() : null;
		$targetUserId = $isUser
			? $block->getTargetUserIdentity()->getId( $this->wikiId ) : null;

		// Update bt_count field in existing target, if there is one
		if ( $isUser ) {
			$targetConds = [ 'bt_user' => $targetUserId ];
			$targetLockKey = $dbw->getDomainID() . ':block:u:' . $targetUserId;
		} else {
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
		$ids = $dbw->newSelectQueryBuilder()
			->select( 'bt_id' )
			->from( 'block_target' )
			->where( $targetConds )
			->forUpdate()
			->caller( __METHOD__ )
			->fetchFieldValues();
		if ( count( $ids ) > 1 ) {
			throw new RuntimeException( "Duplicate block_target rows detected: " .
				implode( ',', $ids ) );
		}
		$id = $ids[0] ?? false;

		if ( $id === false ) {
			if ( $numUpdatedRows ) {
				throw new RuntimeException(
					'block_target row unexpectedly missing after we locked it' );
			}
			if ( $expectedTargetCount !== 0 && $expectedTargetCount !== null ) {
				// Conflict (expectation failure)
				return null;
			}

			// Insert new row
			$targetRow = [
				'bt_address' => $targetAddress,
				'bt_user' => $targetUserId,
				'bt_user_text' => $targetUserName,
				'bt_auto' => $isAuto,
				'bt_range_start' => $isRange ? $block->getRangeStart() : null,
				'bt_range_end' => $isRange ? $block->getRangeEnd() : null,
				'bt_ip_hex' => $isSingle || $isRange ? $block->getRangeStart() : null,
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
		} elseif ( !$numUpdatedRows ) {
			// ID found but count update failed -- must be a conflict due to bt_count mismatch
			return null;
		}

		return (int)$id;
	}

	/**
	 * Update a block in the DB with new parameters.
	 * The ID field needs to be loaded first. The target must stay the same.
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
	 * @param UserIdentity|string $newTarget
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

		$oldTargetConds = $this->getTargetConds( $block );
		$block->setTarget( $newTarget );

		$dbw->startAtomic( __METHOD__ );
		$targetId = $this->acquireTarget( $block, $dbw, null );
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
		$parentBlock->assertWiki( $this->wikiId );

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
		$blocks = $this->newLoad( $target, Block::TYPE_IP, false );
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
			'address' => UserIdentityValue::newAnonymous( $target, $this->wikiId ),
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

	private function getAutoblockReason( DatabaseBlock $parentBlock ) {
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
