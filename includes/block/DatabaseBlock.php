<?php
/**
 * Class for blocks stored in the database.
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

use CommentStore;
use Hooks;
use Html;
use InvalidArgumentException;
use MediaWiki\Block\Restriction\ActionRestriction;
use MediaWiki\Block\Restriction\NamespaceRestriction;
use MediaWiki\Block\Restriction\PageRestriction;
use MediaWiki\Block\Restriction\Restriction;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityValue;
use MWException;
use stdClass;
use Title;
use Wikimedia\IPUtils;
use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\IDatabase;

/**
 * A DatabaseBlock (unlike a SystemBlock) is stored in the database, may
 * give rise to autoblocks and may be tracked with cookies. Such blocks
 * are more customizable than system blocks: they may be hardblocks, and
 * they may be sitewide or partial.
 *
 * @since 1.34 Renamed from Block.
 */
class DatabaseBlock extends AbstractBlock {
	/** @var bool */
	private $mAuto;

	/** @var int */
	private $mParentBlockId;

	/** @var int */
	private $mId;

	/** @var bool */
	private $isAutoblocking;

	/** @var Restriction[] */
	private $restrictions;

	/** @var UserIdentity|null */
	private $blocker;

	/**
	 * Create a new block with specified option parameters on a user, IP or IP range.
	 *
	 * @param array $options Parameters of the block, with options supported by
	 *  `AbstractBlock::__construct`, and also:
	 *  - user: (int) Override target user ID (for foreign users)
	 *  - auto: (bool) Is this an automatic block?
	 *  - expiry: (string) Timestamp of expiration of the block or 'infinity'
	 *  - anonOnly: (bool) Only disallow anonymous actions
	 *  - createAccount: (bool) Disallow creation of new accounts
	 *  - enableAutoblock: (bool) Enable automatic blocking
	 *  - blockEmail: (bool) Disallow sending emails
	 *  - allowUsertalk: (bool) Allow the target to edit its own talk page
	 *  - sitewide: (bool) Disallow editing all pages and all contribution actions,
	 *    except those specifically allowed by other block flags
	 *  - by: (UserIdentity) UserIdentity object of the blocker.
	 *
	 * @since 1.26 $options array
	 */
	public function __construct( array $options = [] ) {
		parent::__construct( $options );

		$defaults = [
			'user'            => null,
			'auto'            => false,
			'expiry'          => '',
			'createAccount'   => false,
			'enableAutoblock' => false,
			'blockEmail'      => false,
			'allowUsertalk'   => false,
			'sitewide'        => true,
			'by'              => null,
		];

		$options += $defaults;

		if ( $options['by'] && $options['by'] instanceof UserIdentity ) {
			$this->setBlocker( $options['by'] );
		}

		$this->setExpiry( $this->getDBConnection( DB_REPLICA )->decodeExpiry( $options['expiry'] ) );

		# Boolean settings
		$this->mAuto = (bool)$options['auto'];
		$this->isAutoblocking( (bool)$options['enableAutoblock'] );
		$this->isSitewide( (bool)$options['sitewide'] );
		$this->isEmailBlocked( (bool)$options['blockEmail'] );
		$this->isCreateAccountBlocked( (bool)$options['createAccount'] );
		$this->isUsertalkEditAllowed( (bool)$options['allowUsertalk'] );

		// hard deprecated since 1.39
		$this->deprecatePublicProperty( 'mAuto', '1.34', __CLASS__ );
		$this->deprecatePublicProperty( 'mParentBlockId', '1.34', __CLASS__ );
	}

	/**
	 * Load a block from the block id.
	 *
	 * @param int $id id to search for
	 * @return DatabaseBlock|null
	 */
	public static function newFromID( $id ) {
		$dbr = wfGetDB( DB_REPLICA );
		$blockQuery = self::getQueryInfo();
		$res = $dbr->selectRow(
			$blockQuery['tables'],
			$blockQuery['fields'],
			[ 'ipb_id' => $id ],
			__METHOD__,
			[],
			$blockQuery['joins']
		);
		if ( $res ) {
			return self::newFromRow( $res );
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
	 * @since 1.31
	 * @return array[] With three keys:
	 *   - tables: (string[]) to include in the `$table` to `IDatabase->select()` or `SelectQueryBuilder::tables`
	 *   - fields: (string[]) to include in the `$vars` to `IDatabase->select()` or `SelectQueryBuilder::fields`
	 *   - joins: (array) to include in the `$join_conds` to `IDatabase->select()` or `SelectQueryBuilder::joinConds`
	 * @phan-return array{tables:string[],fields:string[],joins:array}
	 */
	public static function getQueryInfo() {
		$commentQuery = CommentStore::getStore()->getJoin( 'ipb_reason' );
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
	 * Check if two blocks are effectively equal.  Doesn't check irrelevant things like
	 * the blocking user or the block timestamp, only things which affect the blocked user
	 *
	 * @param DatabaseBlock $block
	 * @return bool
	 */
	public function equals( DatabaseBlock $block ) {
		return (
			(string)$this->target == (string)$block->target
			&& $this->type == $block->type
			&& $this->mAuto == $block->mAuto
			&& $this->isHardblock() == $block->isHardblock()
			&& $this->isCreateAccountBlocked() == $block->isCreateAccountBlocked()
			&& $this->getExpiry() == $block->getExpiry()
			&& $this->isAutoblocking() == $block->isAutoblocking()
			&& $this->getHideName() == $block->getHideName()
			&& $this->isEmailBlocked() == $block->isEmailBlocked()
			&& $this->isUsertalkEditAllowed() == $block->isUsertalkEditAllowed()
			&& $this->getReasonComment()->text == $block->getReasonComment()->text
			&& $this->isSitewide() == $block->isSitewide()
			// DatabaseBlock::getRestrictions() may perform a database query, so
			// keep it at the end.
			&& $this->getBlockRestrictionStore()->equals(
				$this->getRestrictions(), $block->getRestrictions()
			)
		);
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
	 * @throws MWException
	 * @return DatabaseBlock[] Any relevant blocks
	 */
	protected static function newLoad(
		$specificTarget,
		$specificType,
		$fromPrimary,
		$vagueTarget = null
	) {
		$db = wfGetDB( $fromPrimary ? DB_PRIMARY : DB_REPLICA );

		if ( $specificType !== null ) {
			$conds = [ 'ipb_address' => [ (string)$specificTarget ] ];
		} else {
			$conds = [ 'ipb_address' => [] ];
		}

		# Be aware that the != '' check is explicit, since empty values will be
		# passed by some callers (T31116)
		if ( $vagueTarget != '' ) {
			list( $target, $type ) = MediaWikiServices::getInstance()
				->getBlockUtils()
				->parseBlockTarget( $vagueTarget );
			switch ( $type ) {
				case self::TYPE_USER:
					# Slightly weird, but who are we to argue?
					$conds['ipb_address'][] = (string)$target;
					$conds = $db->makeList( $conds, LIST_OR );
					break;

				case self::TYPE_IP:
					$conds['ipb_address'][] = (string)$target;
					$conds['ipb_address'] = array_unique( $conds['ipb_address'] );
					$conds[] = self::getRangeCond( IPUtils::toHex( $target ) );
					// @phan-suppress-next-line SecurityCheck-SQLInjection
					$conds = $db->makeList( $conds, LIST_OR );
					break;

				case self::TYPE_RANGE:
					list( $start, $end ) = IPUtils::parseRange( $target );
					$conds['ipb_address'][] = (string)$target;
					$conds[] = self::getRangeCond( $start, $end );
					// @phan-suppress-next-line SecurityCheck-SQLInjection
					$conds = $db->makeList( $conds, LIST_OR );
					break;

				default:
					throw new MWException( "Tried to load block with invalid type" );
			}
		}

		$blockQuery = self::getQueryInfo();
		// @phan-suppress-next-line SecurityCheck-SQLInjection
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
			$block = self::newFromRow( $row );

			# Don't use expired blocks
			if ( $block->isExpired() ) {
				continue;
			}

			# Don't use anon only blocks on users
			if ( $specificType == self::TYPE_USER && !$block->isHardblock() ) {
				continue;
			}

			// Check for duplicate autoblocks
			if ( $block->getType() === self::TYPE_AUTO ) {
				$autoBlocks[] = $block;
			} else {
				$blocks[] = $block;
				$blockIds[] = $block->getId();
			}
		}

		// Only add autoblocks that aren't duplicates
		foreach ( $autoBlocks as $block ) {
			if ( !in_array( $block->mParentBlockId, $blockIds ) ) {
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
	 * This is refactored out from DatabaseBlock::newLoad.
	 *
	 * @param DatabaseBlock[] $blocks These should not include autoblocks or ID blocks
	 * @return DatabaseBlock|null The block with the most specific target
	 */
	protected static function chooseMostSpecificBlock( array $blocks ) {
		if ( count( $blocks ) === 1 ) {
			return $blocks[0];
		}

		# This result could contain a block on the user, a block on the IP, and a russian-doll
		# set of rangeblocks.  We want to choose the most specific one, so keep a leader board.
		$bestBlock = null;

		# Lower will be better
		$bestBlockScore = 100;
		foreach ( $blocks as $block ) {
			if ( $block->getType() == self::TYPE_RANGE ) {
				# This is the number of bits that are allowed to vary in the block, give
				# or take some floating point errors
				$target = $block->getTargetName();
				$max = IPUtils::isIPv6( $target ) ? 128 : 32;
				list( $network, $bits ) = IPUtils::parseCIDR( $target );
				$size = $max - $bits;

				# Rank a range block covering a single IP equally with a single-IP block
				$score = self::TYPE_RANGE - 1 + ( $size / $max );

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
	 * Get a set of SQL conditions which will select rangeblocks encompassing a given range
	 * @param string $start Hexadecimal IP representation
	 * @param string|null $end Hexadecimal IP representation, or null to use $start = $end
	 * @return string
	 */
	public static function getRangeCond( $start, $end = null ) {
		if ( $end === null ) {
			$end = $start;
		}
		# Per T16634, we want to include relevant active rangeblocks; for
		# rangeblocks, we want to include larger ranges which enclose the given
		# range. We know that all blocks must be smaller than $wgBlockCIDRLimit,
		# so we can improve performance by filtering on a LIKE clause
		$chunk = self::getIpFragment( $start );
		$dbr = wfGetDB( DB_REPLICA );
		$like = $dbr->buildLike( $chunk, $dbr->anyString() );

		# Fairly hard to make a malicious SQL statement out of hex characters,
		# but stranger things have happened...
		$safeStart = $dbr->addQuotes( $start );
		$safeEnd = $dbr->addQuotes( $end );

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
	 * address and a rangeblock containing that IP address.
	 * @param string $hex Hexadecimal IP representation
	 * @return string
	 */
	protected static function getIpFragment( $hex ) {
		$blockCIDRLimit = MediaWikiServices::getInstance()->getMainConfig()->get( MainConfigNames::BlockCIDRLimit );
		if ( substr( $hex, 0, 3 ) == 'v6-' ) {
			return 'v6-' . substr( substr( $hex, 3 ), 0, (int)floor( $blockCIDRLimit['IPv6'] / 4 ) );
		} else {
			return substr( $hex, 0, (int)floor( $blockCIDRLimit['IPv4'] / 4 ) );
		}
	}

	/**
	 * Given a database row from the ipblocks table, initialize
	 * member variables
	 * @param stdClass $row A row from the ipblocks table
	 */
	protected function initFromRow( $row ) {
		$this->setTarget( $row->ipb_address );

		$this->setTimestamp( wfTimestamp( TS_MW, $row->ipb_timestamp ) );
		$this->mAuto = (bool)$row->ipb_auto;
		$this->setHideName( (bool)$row->ipb_deleted );
		$this->mId = (int)$row->ipb_id;
		$this->mParentBlockId = (int)$row->ipb_parent_block_id;

		$this->setBlocker( MediaWikiServices::getInstance()
			->getActorNormalization()
			->newActorFromRowFields( $row->ipb_by, $row->ipb_by_text, $row->ipb_by_actor ) );

		// I wish I didn't have to do this
		$db = $this->getDBConnection( DB_REPLICA );
		$this->setExpiry( $db->decodeExpiry( $row->ipb_expiry ) );
		$this->setReason(
			CommentStore::getStore()
			// Legacy because $row may have come from self::selectFields()
			->getCommentLegacy( $db, 'ipb_reason', $row )
		);

		$this->isHardblock( !$row->ipb_anon_only );
		$this->isAutoblocking( (bool)$row->ipb_enable_autoblock );
		$this->isSitewide( (bool)$row->ipb_sitewide );

		$this->isCreateAccountBlocked( (bool)$row->ipb_create_account );
		$this->isEmailBlocked( (bool)$row->ipb_block_email );
		$this->isUsertalkEditAllowed( (bool)$row->ipb_allow_usertalk );
	}

	/**
	 * Create a new DatabaseBlock object from a database row
	 * @param stdClass $row Row from the ipblocks table
	 * @return DatabaseBlock
	 */
	public static function newFromRow( $row ) {
		$block = new DatabaseBlock;
		$block->initFromRow( $row );
		return $block;
	}

	/**
	 * Delete the row from the IP blocks table.
	 *
	 * @deprecated since 1.36 Use DatabaseBlockStore::deleteBlock instead.
	 * @throws MWException
	 * @return bool
	 */
	public function delete() {
		return MediaWikiServices::getInstance()
			->getDatabaseBlockStore()
			->deleteBlock( $this );
	}

	/**
	 * Insert a block into the block table. Will fail if there is a conflicting
	 * block (same name and options) already in the database.
	 *
	 * @deprecated since 1.36 Use DatabaseBlockStore::insertBlock instead.
	 * @param IDatabase|null $dbw If you have one available
	 * @return bool|array False on failure, assoc array on success:
	 * 	('id' => block ID, 'autoIds' => array of autoblock IDs)
	 */
	public function insert( IDatabase $dbw = null ) {
		return MediaWikiServices::getInstance()
			->getDatabaseBlockStore()
			->insertBlock( $this, $dbw );
	}

	/**
	 * Update a block in the DB with new parameters.
	 * The ID field needs to be loaded first.
	 *
	 * @deprecated since 1.36 Use DatabaseBlockStore::updateBlock instead.
	 * @return bool|array False on failure, array on success:
	 *   ('id' => block ID, 'autoIds' => array of autoblock IDs)
	 */
	public function update() {
		return MediaWikiServices::getInstance()
			->getDatabaseBlockStore()
			->updateBlock( $this );
	}

	/**
	 * Checks whether a given IP is on the autoblock exemption list.
	 * TODO: this probably belongs somewhere else, but not sure where...
	 *
	 * @since 1.36
	 *
	 * @param string $ip The IP to check
	 * @return bool
	 */
	public static function isExemptedFromAutoblocks( $ip ) {
		// Try to get the ip-autoblock_exemption from the cache, as it's faster
		// than getting the msg raw and explode()'ing it.
		$cache = MediaWikiServices::getInstance()->getMainWANObjectCache();
		$lines = $cache->getWithSetCallback(
			$cache->makeKey( 'ip-autoblock', 'exemption' ),
			$cache::TTL_DAY,
			static function ( $curValue, &$ttl, array &$setOpts ) {
				$setOpts += Database::getCacheSetOptions( wfGetDB( DB_REPLICA ) );

				return explode( "\n",
					wfMessage( 'block-autoblock-exemptionlist' )->inContentLanguage()->plain()
				);
			}
		);

		wfDebug( "Checking the autoblock exemption list.." );

		foreach ( $lines as $line ) {
			# List items only
			if ( substr( $line, 0, 1 ) !== '*' ) {
				continue;
			}

			$wlEntry = substr( $line, 1 );
			$wlEntry = trim( $wlEntry );

			wfDebug( "Checking $ip against $wlEntry..." );

			# Is the IP in this range?
			if ( IPUtils::isInRange( $ip, $wlEntry ) ) {
				wfDebug( " IP $ip matches $wlEntry, not autoblocking" );
				return true;
			} else {
				wfDebug( " No match" );
			}
		}

		return false;
	}

	/**
	 * Autoblocks the given IP, referring to this block.
	 *
	 * @param string $autoblockIP The IP to autoblock.
	 * @return int|bool ID if an autoblock was inserted, false if not.
	 */
	public function doAutoblock( $autoblockIP ) {
		# If autoblocks are disabled, go away.
		if ( !$this->isAutoblocking() ) {
			return false;
		}

		# Check if autoblock exempt.
		if ( self::isExemptedFromAutoblocks( $autoblockIP ) ) {
			return false;
		}

		# Allow hooks to cancel the autoblock.
		if ( !Hooks::runner()->onAbortAutoblock( $autoblockIP, $this ) ) {
			wfDebug( "Autoblock aborted by hook." );
			return false;
		}

		# It's okay to autoblock. Go ahead and insert/update the block...

		# Do not add a *new* block if the IP is already blocked.
		$ipblock = self::newFromTarget( $autoblockIP );
		if ( $ipblock ) {
			# Check if the block is an autoblock and would exceed the user block
			# if renewed. If so, do nothing, otherwise prolong the block time...
			if ( $ipblock->mAuto && // @todo Why not compare $ipblock->mExpiry?
				$this->getExpiry() > self::getAutoblockExpiry( $ipblock->getTimestamp() )
			) {
				# Reset block timestamp to now and its expiry to
				# $wgAutoblockExpiry in the future
				$ipblock->updateTimestamp();
			}
			return false;
		}
		$blocker = $this->getBlocker();
		if ( !$blocker ) {
			throw new \RuntimeException( __METHOD__ . ': this block does not have a blocker' );
		}

		# Make a new block object with the desired properties.
		$autoblock = new DatabaseBlock( [ 'wiki' => $this->getWikiId() ] );
		wfDebug( "Autoblocking {$this->getTargetName()}@" . $autoblockIP );
		$autoblock->setTarget( UserIdentityValue::newAnonymous( $autoblockIP, $this->getWikiId() ) );
		$autoblock->setBlocker( $blocker );
		$autoblock->setReason(
			wfMessage(
				'autoblocker',
				$this->getTargetName(),
				$this->getReasonComment()->text
			)->inContentLanguage()->plain()
		);
		$timestamp = wfTimestampNow();
		$autoblock->setTimestamp( $timestamp );
		$autoblock->mAuto = true;
		$autoblock->isCreateAccountBlocked( $this->isCreateAccountBlocked() );
		# Continue suppressing the name if needed
		$autoblock->setHideName( $this->getHideName() );
		$autoblock->isUsertalkEditAllowed( $this->isUsertalkEditAllowed() );
		$autoblock->mParentBlockId = $this->mId;
		$autoblock->isSitewide( $this->isSitewide() );
		$autoblock->setRestrictions( $this->getRestrictions() );

		if ( $this->getExpiry() == 'infinity' ) {
			# Original block was indefinite, start an autoblock now
			$autoblock->setExpiry( self::getAutoblockExpiry( $timestamp ) );
		} else {
			# If the user is already blocked with an expiry date, we don't
			# want to pile on top of that.
			$autoblock->setExpiry( min( $this->getExpiry(), self::getAutoblockExpiry( $timestamp ) ) );
		}

		# Insert the block...
		$status = MediaWikiServices::getInstance()->getDatabaseBlockStore()->insertBlock(
			$autoblock,
			$this->getDBConnection( DB_PRIMARY )
		);
		return $status
			? $status['id']
			: false;
	}

	/**
	 * Has the block expired?
	 * @return bool
	 */
	public function isExpired() {
		$timestamp = wfTimestampNow();
		wfDebug( __METHOD__ . " checking current " . $timestamp . " vs $this->mExpiry" );

		return $this->getExpiry() && $timestamp > $this->getExpiry();
	}

	/**
	 * Update the timestamp on autoblocks.
	 */
	public function updateTimestamp() {
		if ( $this->mAuto ) {
			$this->setTimestamp( wfTimestamp() );
			$this->setExpiry( self::getAutoblockExpiry( $this->getTimestamp() ) );

			$dbw = $this->getDBConnection( DB_PRIMARY );
			$dbw->update( 'ipblocks',
				[ /* SET */
					'ipb_timestamp' => $dbw->timestamp( $this->getTimestamp() ),
					'ipb_expiry' => $dbw->timestamp( $this->getExpiry() ),
				],
				[ /* WHERE */
					'ipb_id' => $this->getId( $this->getWikiId() ),
				],
				__METHOD__
			);
		}
	}

	/**
	 * Get the IP address at the start of the range in Hex form
	 * @throws MWException
	 * @return string IP in Hex form
	 */
	public function getRangeStart() {
		switch ( $this->type ) {
			case self::TYPE_USER:
				return '';
			case self::TYPE_IP:
				return IPUtils::toHex( $this->target );
			case self::TYPE_RANGE:
				list( $start, /*...*/ ) = IPUtils::parseRange( $this->target );
				return $start;
			default:
				throw new MWException( "Block with invalid type" );
		}
	}

	/**
	 * Get the IP address at the end of the range in Hex form
	 * @throws MWException
	 * @return string IP in Hex form
	 */
	public function getRangeEnd() {
		switch ( $this->type ) {
			case self::TYPE_USER:
				return '';
			case self::TYPE_IP:
				return IPUtils::toHex( $this->target );
			case self::TYPE_RANGE:
				list( /*...*/, $end ) = IPUtils::parseRange( $this->target );
				return $end;
			default:
				throw new MWException( "Block with invalid type" );
		}
	}

	/**
	 * @inheritDoc
	 * @deprecated since 1.35. Use getReasonComment instead.
	 */
	public function getReason() {
		if ( $this->getType() === self::TYPE_AUTO ) {
			return $this->reason->message->inContentLanguage()->plain();
		}
		return $this->reason->text;
	}

	/**
	 * @inheritDoc
	 */
	public function getId( $wikiId = self::LOCAL ): ?int {
		// TODO: Enable deprecation warnings once cross-wiki accesses have been removed, see T274817
		// $this->deprecateInvalidCrossWiki( $wikiId, '1.38' );
		return $this->mId;
	}

	/**
	 * Set the block ID
	 *
	 * @internal Only for use in DatabaseBlockStore; private until 1.36
	 * @param int $blockId
	 * @return self
	 */
	public function setId( $blockId ) {
		$this->mId = (int)$blockId;

		if ( is_array( $this->restrictions ) ) {
			$this->restrictions = $this->getBlockRestrictionStore()->setBlockId(
				$blockId, $this->restrictions
			);
		}

		return $this;
	}

	/**
	 * @since 1.34
	 * @return int|null If this is an autoblock, ID of the parent block; otherwise null
	 */
	public function getParentBlockId() {
		return $this->mParentBlockId;
	}

	/**
	 * Get/set whether the block is a hardblock (affects logged-in users on a given IP/range)
	 * @param bool|null $x
	 * @return bool
	 */
	public function isHardblock( $x = null ): bool {
		wfSetVar( $this->isHardblock, $x );

		# You can't *not* hardblock a user
		return $this->getType() == self::TYPE_USER
			? true
			: $this->isHardblock;
	}

	/**
	 * @param null|bool $x
	 * @return bool
	 */
	public function isAutoblocking( $x = null ) {
		wfSetVar( $this->isAutoblocking, $x );

		# You can't put an autoblock on an IP or range as we don't have any history to
		# look over to get more IPs from
		return $this->getType() == self::TYPE_USER
			? $this->isAutoblocking
			: false;
	}

	/**
	 * Get the block name, but with autoblocked IPs hidden as per standard privacy policy
	 * @return string Text is escaped
	 */
	public function getRedactedName() {
		if ( $this->mAuto ) {
			return Html::element(
				'span',
				[ 'class' => 'mw-autoblockid' ],
				wfMessage( 'autoblockid', $this->mId )->text()
			);
		} else {
			return htmlspecialchars( $this->getTargetName() );
		}
	}

	/**
	 * Get a timestamp of the expiry for autoblocks
	 *
	 * @param string|int $timestamp
	 * @return string
	 */
	public static function getAutoblockExpiry( $timestamp ) {
		$autoblockExpiry = MediaWikiServices::getInstance()->getMainConfig()->get( MainConfigNames::AutoblockExpiry );

		return wfTimestamp( TS_MW, (int)wfTimestamp( TS_UNIX, $timestamp ) + $autoblockExpiry );
	}

	/**
	 * Purge expired blocks from the ipblocks table
	 *
	 * @deprecated since 1.36, hard deprecated since 1.38
	 * Use DatabaseBlockStore::purgeExpiredBlocks instead.
	 */
	public static function purgeExpired() {
		wfDeprecated( __METHOD__, '1.36' );
		MediaWikiServices::getInstance()->getDatabaseBlockStore()->purgeExpiredBlocks();
	}

	/**
	 * Given a target and the target's type, get an existing block object if possible.
	 * @param string|UserIdentity|int|null $specificTarget A block target, which may be one of several types:
	 *     * A user to block, in which case $target will be a User
	 *     * An IP to block, in which case $target will be a User generated by using
	 *       User::newFromName( $ip, false ) to turn off name validation
	 *     * An IP range, in which case $target will be a String "123.123.123.123/18" etc
	 *     * The ID of an existing block, in the format "#12345" (since pure numbers are valid
	 *       usernames
	 *     Calling this with a user, IP address or range will not select autoblocks, and will
	 *     only select a block where the targets match exactly (so looking for blocks on
	 *     1.2.3.4 will not select 1.2.0.0/16 or even 1.2.3.4/32)
	 * @param string|UserIdentity|int|null $vagueTarget As above, but we will search for *any* block which
	 *     affects that target (so for an IP address, get ranges containing that IP; and also
	 *     get any relevant autoblocks). Leave empty or blank to skip IP-based lookups.
	 * @param bool $fromPrimary Whether to use the DB_PRIMARY database
	 * @return DatabaseBlock|null (null if no relevant block could be found). The target and type
	 *     of the returned block will refer to the actual block which was found, which might
	 *     not be the same as the target you gave if you used $vagueTarget!
	 */
	public static function newFromTarget( $specificTarget, $vagueTarget = null, $fromPrimary = false ) {
		$blocks = self::newListFromTarget( $specificTarget, $vagueTarget, $fromPrimary );
		return self::chooseMostSpecificBlock( $blocks );
	}

	/**
	 * This is similar to DatabaseBlock::newFromTarget, but it returns all the relevant blocks.
	 *
	 * @since 1.34
	 * @param string|UserIdentity|int|null $specificTarget
	 * @param string|UserIdentity|int|null $vagueTarget
	 * @param bool $fromPrimary
	 * @return DatabaseBlock[] Any relevant blocks
	 */
	public static function newListFromTarget(
		$specificTarget,
		$vagueTarget = null,
		$fromPrimary = false
	) {
		list( $target, $type ) = MediaWikiServices::getInstance()
			->getBlockUtils()
			->parseBlockTarget( $specificTarget );
		if ( $type == self::TYPE_ID || $type == self::TYPE_AUTO ) {
			$block = self::newFromID( $target );
			return $block ? [ $block ] : [];
		} elseif ( $target === null && $vagueTarget == '' ) {
			# We're not going to find anything useful here
			# Be aware that the == '' check is explicit, since empty values will be
			# passed by some callers (T31116)
			return [];
		} elseif ( in_array(
			$type,
			[ self::TYPE_USER, self::TYPE_IP, self::TYPE_RANGE, null ] )
		) {
			return self::newLoad( $target, $type, $fromPrimary, $vagueTarget );
		}
		return [];
	}

	/**
	 * Get all blocks that match any IP from an array of IP addresses
	 *
	 * @param array $ipChain List of IPs (strings), usually retrieved from the
	 *     X-Forwarded-For header of the request
	 * @param bool $isAnon Exclude anonymous-only blocks if false
	 * @param bool $fromPrimary Whether to query the primary or replica DB
	 * @return self[]
	 * @since 1.22
	 */
	public static function getBlocksForIPList( array $ipChain, $isAnon, $fromPrimary = false ) {
		if ( $ipChain === [] ) {
			return [];
		}

		$conds = [];
		$proxyLookup = MediaWikiServices::getInstance()->getProxyLookup();
		foreach ( array_unique( $ipChain ) as $ipaddr ) {
			# Discard invalid IP addresses. Since XFF can be spoofed and we do not
			# necessarily trust the header given to us, make sure that we are only
			# checking for blocks on well-formatted IP addresses (IPv4 and IPv6).
			# Do not treat private IP spaces as special as it may be desirable for wikis
			# to block those IP ranges in order to stop misbehaving proxies that spoof XFF.
			if ( !IPUtils::isValid( $ipaddr ) ) {
				continue;
			}
			# Don't check trusted IPs (includes local CDNs which will be in every request)
			if ( $proxyLookup->isTrustedProxy( $ipaddr ) ) {
				continue;
			}
			# Check both the original IP (to check against single blocks), as well as build
			# the clause to check for rangeblocks for the given IP.
			$conds['ipb_address'][] = $ipaddr;
			$conds[] = self::getRangeCond( IPUtils::toHex( $ipaddr ) );
		}

		if ( $conds === [] ) {
			return [];
		}

		if ( $fromPrimary ) {
			$db = wfGetDB( DB_PRIMARY );
		} else {
			$db = wfGetDB( DB_REPLICA );
		}
		$conds = $db->makeList( $conds, LIST_OR );
		if ( !$isAnon ) {
			$conds = [ $conds, 'ipb_anon_only' => 0 ];
		}
		$blockQuery = self::getQueryInfo();
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
			$block = self::newFromRow( $row );
			if ( !$block->isExpired() ) {
				$blocks[] = $block;
			}
		}

		return $blocks;
	}

	/**
	 * @inheritDoc
	 *
	 * Autoblocks have whichever type corresponds to their target, so to detect if a block is an
	 * autoblock, we have to check the mAuto property instead.
	 */
	public function getType(): ?int {
		return $this->mAuto
			? self::TYPE_AUTO
			: parent::getType();
	}

	/**
	 * @inheritDoc
	 */
	public function getIdentifier( $wikiId = self::LOCAL ) {
		return $this->getId( $wikiId );
	}

	/**
	 * Getting the restrictions will perform a database query if the restrictions
	 * are not already loaded.
	 *
	 * @since 1.33
	 * @return Restriction[]
	 */
	public function getRestrictions() {
		if ( $this->restrictions === null ) {
			// If the block id has not been set, then do not attempt to load the
			// restrictions.
			if ( !$this->mId ) {
				return [];
			}
			$this->restrictions = $this->getBlockRestrictionStore()->loadByBlockId( $this->mId );
		}

		return $this->restrictions;
	}

	/**
	 * Get restrictions without loading from database if not yet loaded
	 *
	 * @internal
	 * @return ?Restriction[]
	 */
	public function getRawRestrictions(): ?array {
		return $this->restrictions;
	}

	/**
	 * @since 1.33
	 * @param Restriction[] $restrictions
	 * @return self
	 */
	public function setRestrictions( array $restrictions ) {
		$this->restrictions = array_filter( $restrictions, static function ( $restriction ) {
			return $restriction instanceof Restriction;
		} );

		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function appliesToTitle( Title $title ) {
		if ( $this->isSitewide() ) {
			return true;
		}

		$restrictions = $this->getRestrictions();
		foreach ( $restrictions as $restriction ) {
			if ( $restriction->matches( $title ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @inheritDoc
	 */
	public function appliesToNamespace( $ns ) {
		if ( $this->isSitewide() ) {
			return true;
		}

		// Blocks do not apply to virtual namespaces.
		if ( $ns < 0 ) {
			return false;
		}

		$restriction = $this->findRestriction( NamespaceRestriction::TYPE, $ns );

		return (bool)$restriction;
	}

	/**
	 * @inheritDoc
	 */
	public function appliesToPage( $pageId ) {
		if ( $this->isSitewide() ) {
			return true;
		}

		// If the pageId is not over zero, the block cannot apply to it.
		if ( $pageId <= 0 ) {
			return false;
		}

		$restriction = $this->findRestriction( PageRestriction::TYPE, $pageId );

		return (bool)$restriction;
	}

	/**
	 * @inheritDoc
	 */
	public function appliesToRight( $right ) {
		// Temporarily access service container until the feature flag is removed: T280532
		$config = MediaWikiServices::getInstance()->getMainConfig();

		$res = parent::appliesToRight( $right );

		if ( !$res && $config->get( MainConfigNames::EnablePartialActionBlocks ) ) {
			$blockActions = MediaWikiServices::getInstance()->getBlockActionInfo()
				->getAllBlockActions();

			if ( isset( $blockActions[$right] ) ) {
				$restriction = $this->findRestriction(
					ActionRestriction::TYPE,
					$blockActions[$right]
				);

				// $res may be null or false. This should be preserved if there is no restriction.
				if ( $restriction ) {
					$res = true;
				}
			}
		}

		return $res;
	}

	/**
	 * Find Restriction by type and value.
	 *
	 * @param string $type
	 * @param int $value
	 * @return Restriction|null
	 */
	private function findRestriction( $type, $value ) {
		$restrictions = $this->getRestrictions();
		foreach ( $restrictions as $restriction ) {
			if ( $restriction->getType() !== $type ) {
				continue;
			}

			if ( $restriction->getValue() === $value ) {
				return $restriction;
			}
		}

		return null;
	}

	/**
	 * Get a BlockRestrictionStore instance
	 *
	 * @return BlockRestrictionStore
	 */
	private function getBlockRestrictionStore(): BlockRestrictionStore {
		// TODO: get rid of global state here
		return MediaWikiServices::getInstance()
			->getBlockRestrictionStoreFactory()
			->getBlockRestrictionStore( $this->getWikiId() );
	}

	/**
	 * @inheritDoc
	 */
	public function getBy( $wikiId = self::LOCAL ): int {
		$this->assertWiki( $wikiId );
		return ( $this->blocker ) ? $this->blocker->getId( $wikiId ) : 0;
	}

	/**
	 * @inheritDoc
	 */
	public function getByName() {
		return ( $this->blocker ) ? $this->blocker->getName() : '';
	}

	/**
	 * Get the user who implemented this block
	 *
	 * @return UserIdentity|null user object or null. May be a foreign user.
	 */
	public function getBlocker(): ?UserIdentity {
		return $this->blocker;
	}

	/**
	 * Set the user who implemented (or will implement) this block
	 *
	 * @param UserIdentity $user
	 */
	public function setBlocker( $user ) {
		if ( !$user->isRegistered() &&
			MediaWikiServices::getInstance()->getUserNameUtils()->isUsable( $user->getName() )
		) {
			// Temporarily log some block details to debug T192964
			$logger = LoggerFactory::getInstance( 'BlockManager' );
			$logger->warning(
				'Blocker is neither a local user nor an invalid username',
				[
					'blocker' => (string)$user,
					'blockId' => $this->getId( $this->getWikiId() ),
				]
			);
			throw new InvalidArgumentException(
				'Blocker must be a local user or a name that cannot be a local user'
			);
		}
		$this->assertWiki( $user->getWikiId() );
		$this->blocker = $user;
	}

	/**
	 * @param int $i Specific or virtual (DB_PRIMARY/DB_REPLICA) server index
	 * @return IDatabase
	 */
	private function getDBConnection( int $i ) {
		return MediaWikiServices::getInstance()
			->getDBLoadBalancerFactory()
			->getMainLB( $this->getWikiId() )
			->getConnectionRef( $i, [], $this->getWikiId() );
	}
}

/**
 * @deprecated since 1.34
 */
class_alias( DatabaseBlock::class, 'Block' );
