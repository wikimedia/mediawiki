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

use ActorMigration;
use CommentStore;
use Hooks;
use Html;
use MediaWiki\Block\Restriction\NamespaceRestriction;
use MediaWiki\Block\Restriction\PageRestriction;
use MediaWiki\Block\Restriction\Restriction;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MediaWikiServices;
use MediaWiki\User\UserFactory;
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
	/**
	 * @deprecated since 1.34. Use getType to check whether a block is autoblocking.
	 * @var bool
	 */
	public $mAuto;

	/**
	 * @deprecated since 1.34. Use getParentBlockId instead.
	 * @var int
	 */
	public $mParentBlockId;

	/** @var int */
	private $mId;

	/** @var bool */
	private $mFromMaster;

	/** @var int Hack for foreign blocking (CentralAuth) */
	private $forcedTargetID;

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
	 *  - by: (int|UserIdentity) UserIdentity object or an ID of the blocker.
	 *      Calling with ID is deprecated since 1.36
	 *  - byText: (string) Username of the blocker (for foreign users)
	 *      Deprecated since 1.36. Use 'by' parameter instead.
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
			'byText'          => '',
		];

		$options += $defaults;

		if ( $this->target instanceof UserIdentity && $options['user'] ) {
			# Needed for foreign users
			$this->forcedTargetID = $options['user'];
		}

		if ( $options['by'] ) {
			if ( $options['by'] instanceof UserIdentity ) {
				$this->setBlocker( $options['by'] );
			} else {
				// Local user, passed by ID. Deprecated case,
				// callers should provide UserIdentity in the 'by'
				// option.
				$localBlocker = MediaWikiServices::getInstance()
					->getUserFactory()
					->newFromId( $options['by'] );
				$this->setBlocker( $localBlocker );
			}
		} elseif ( $options['byText'] ) {
			// Foreign user. Deprecated case, callers should
			// provide UserIdentity in the 'by' option.
			$foreignBlocker = MediaWikiServices::getInstance()
				->getActorStore()
				->getUserIdentityByName( $options['byText'] );
			if ( !$foreignBlocker ) {
				// An actor for an interwiki user might not exist on this wiki,
				// so it's ok to create one. Interwiki actors are still local actors.
				$foreignBlocker = new UserIdentityValue( 0, $options['byText'], UserIdentity::LOCAL );
			}
			$this->setBlocker( $foreignBlocker );
		}

		$this->setExpiry( wfGetDB( DB_REPLICA )->decodeExpiry( $options['expiry'] ) );

		# Boolean settings
		$this->mAuto = (bool)$options['auto'];
		$this->isAutoblocking( (bool)$options['enableAutoblock'] );
		$this->isSitewide( (bool)$options['sitewide'] );
		$this->isEmailBlocked( (bool)$options['blockEmail'] );
		$this->isCreateAccountBlocked( (bool)$options['createAccount'] );
		$this->isUsertalkEditAllowed( (bool)$options['allowUsertalk'] );

		$this->mFromMaster = false;
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
	 * @since 1.31
	 * @return array With three keys:
	 *   - tables: (string[]) to include in the `$table` to `IDatabase->select()`
	 *   - fields: (string[]) to include in the `$vars` to `IDatabase->select()`
	 *   - joins: (array) to include in the `$join_conds` to `IDatabase->select()`
	 */
	public static function getQueryInfo() {
		$commentQuery = CommentStore::getStore()->getJoin( 'ipb_reason' );
		$actorQuery = ActorMigration::newMigration()->getJoin( 'ipb_by' );
		return [
			'tables' => [ 'ipblocks' ] + $commentQuery['tables'] + $actorQuery['tables'],
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
			] + $commentQuery['fields'] + $actorQuery['fields'],
			'joins' => $commentQuery['joins'] + $actorQuery['joins'],
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
	 * @param bool $fromMaster
	 * @param UserIdentity|string|null $vagueTarget Also search for blocks affecting this target.
	 *     Doesn't make any sense to use TYPE_AUTO / TYPE_ID here. Leave blank to skip IP lookups.
	 * @throws MWException
	 * @return DatabaseBlock[] Any relevant blocks
	 */
	protected static function newLoad(
		$specificTarget,
		$specificType,
		$fromMaster,
		$vagueTarget = null
	) {
		$db = wfGetDB( $fromMaster ? DB_MASTER : DB_REPLICA );

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
					break;

				case self::TYPE_IP:
					$conds['ipb_address'][] = (string)$target;
					$conds['ipb_address'] = array_unique( $conds['ipb_address'] );
					$conds[] = self::getRangeCond( IPUtils::toHex( $target ) );
					$conds = $db->makeList( $conds, LIST_OR );
					break;

				case self::TYPE_RANGE:
					list( $start, $end ) = IPUtils::parseRange( $target );
					$conds['ipb_address'][] = (string)$target;
					$conds[] = self::getRangeCond( $start, $end );
					$conds = $db->makeList( $conds, LIST_OR );
					break;

				default:
					throw new MWException( "Tried to load block with invalid type" );
			}
		}

		$blockQuery = self::getQueryInfo();
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
	 * Note that DatabaseBlock::chooseBlock chooses blocks in a different way.
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
				$target = $block->getTarget();
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
		global $wgBlockCIDRLimit;
		if ( substr( $hex, 0, 3 ) == 'v6-' ) {
			return 'v6-' . substr( substr( $hex, 3 ), 0, floor( $wgBlockCIDRLimit['IPv6'] / 4 ) );
		} else {
			return substr( $hex, 0, floor( $wgBlockCIDRLimit['IPv4'] / 4 ) );
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
		$db = wfGetDB( DB_REPLICA );
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
	 *
	 * @deprecated since 1.36; use DatabaseBlock::isExemptedFromAutoblocks()
	 *
	 * @param string $ip The IP to check
	 * @return bool
	 */
	public static function isWhitelistedFromAutoblocks( $ip ) {
		return self::isExemptedFromAutoblocks( $ip );
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
					// Temporarily still load the old Message
					. "\n" . wfMessage( 'autoblock_whitelist' )->inContentLanguage()->plain()
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

		# Make a new block object with the desired properties.
		$autoblock = new DatabaseBlock;
		wfDebug( "Autoblocking {$this->getTarget()}@" . $autoblockIP );
		$autoblock->setTarget( $autoblockIP );
		$autoblock->setBlocker( $this->getBlocker() );
		$autoblock->setReason(
			wfMessage(
				'autoblocker',
				(string)$this->getTarget(),
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
			$autoblock
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

		if ( !$this->getExpiry() ) {
			return false;
		} else {
			return $timestamp > $this->getExpiry();
		}
	}

	/**
	 * Update the timestamp on autoblocks.
	 */
	public function updateTimestamp() {
		if ( $this->mAuto ) {
			$this->setTimestamp( wfTimestamp() );
			$this->setExpiry( self::getAutoblockExpiry( $this->getTimestamp() ) );

			$dbw = wfGetDB( DB_MASTER );
			$dbw->update( 'ipblocks',
				[ /* SET */
					'ipb_timestamp' => $dbw->timestamp( $this->getTimestamp() ),
					'ipb_expiry' => $dbw->timestamp( $this->getExpiry() ),
				],
				[ /* WHERE */
					'ipb_id' => $this->getId(),
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
	public function getId() {
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
	public function isHardblock( $x = null ) {
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
			return htmlspecialchars( $this->getTarget() );
		}
	}

	/**
	 * Get a timestamp of the expiry for autoblocks
	 *
	 * @param string|int $timestamp
	 * @return string
	 */
	public static function getAutoblockExpiry( $timestamp ) {
		global $wgAutoblockExpiry;

		return wfTimestamp( TS_MW, (int)wfTimestamp( TS_UNIX, $timestamp ) + $wgAutoblockExpiry );
	}

	/**
	 * Purge expired blocks from the ipblocks table
	 *
	 * @deprecated since 1.36 Use DatabaseBlockStore::purgeExpiredBlocks instead.
	 */
	public static function purgeExpired() {
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
	 * @param bool $fromMaster Whether to use the DB_MASTER database
	 * @return DatabaseBlock|null (null if no relevant block could be found). The target and type
	 *     of the returned block will refer to the actual block which was found, which might
	 *     not be the same as the target you gave if you used $vagueTarget!
	 */
	public static function newFromTarget( $specificTarget, $vagueTarget = null, $fromMaster = false ) {
		$blocks = self::newListFromTarget( $specificTarget, $vagueTarget, $fromMaster );
		return self::chooseMostSpecificBlock( $blocks );
	}

	/**
	 * This is similar to DatabaseBlock::newFromTarget, but it returns all the relevant blocks.
	 *
	 * @since 1.34
	 * @param string|UserIdentity|int|null $specificTarget
	 * @param string|UserIdentity|int|null $vagueTarget
	 * @param bool $fromMaster
	 * @return DatabaseBlock[] Any relevant blocks
	 */
	public static function newListFromTarget(
		$specificTarget,
		$vagueTarget = null,
		$fromMaster = false
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
			return self::newLoad( $target, $type, $fromMaster, $vagueTarget );
		}
		return [];
	}

	/**
	 * Get all blocks that match any IP from an array of IP addresses
	 *
	 * @param array $ipChain List of IPs (strings), usually retrieved from the
	 *     X-Forwarded-For header of the request
	 * @param bool $isAnon Exclude anonymous-only blocks if false
	 * @param bool $fromMaster Whether to query the master or replica DB
	 * @return self[]
	 * @since 1.22
	 */
	public static function getBlocksForIPList( array $ipChain, $isAnon, $fromMaster = false ) {
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

		if ( $fromMaster ) {
			$db = wfGetDB( DB_MASTER );
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
	 * From a list of multiple blocks, find the most exact and strongest block.
	 *
	 * The logic for finding the "best" block is:
	 *  - Blocks that match the block's target IP are preferred over ones in a range
	 *  - Hardblocks are chosen over softblocks that prevent account creation
	 *  - Softblocks that prevent account creation are chosen over other softblocks
	 *  - Other softblocks are chosen over autoblocks
	 *  - If there are multiple exact or range blocks at the same level, the one chosen
	 *    is random
	 * This should be used when $blocks were retrieved from the user's IP address
	 * and $ipChain is populated from the same IP address information.
	 *
	 * @deprecated since 1.35 No longer needed in core, since the introduction of
	 *  CompositeBlock (T206163)
	 * @param array $blocks Array of DatabaseBlock objects
	 * @param array $ipChain List of IPs (strings). This is used to determine how "close"
	 *  a block is to the server, and if a block matches exactly, or is in a range.
	 *  The order is furthest from the server to nearest e.g., (Browser, proxy1, proxy2,
	 *  local-cdn, ...)
	 * @throws MWException
	 * @return DatabaseBlock|null The "best" block from the list
	 */
	public static function chooseBlock( array $blocks, array $ipChain ) {
		wfDeprecated( __METHOD__, '1.35' );
		if ( $blocks === [] ) {
			return null;
		} elseif ( count( $blocks ) == 1 ) {
			return $blocks[0];
		}

		// Sort hard blocks before soft ones and secondarily sort blocks
		// that disable account creation before those that don't.
		usort( $blocks, static function ( DatabaseBlock $a, DatabaseBlock $b ) {
			$aWeight = (int)$a->isHardblock() . (int)$a->appliesToRight( 'createaccount' );
			$bWeight = (int)$b->isHardblock() . (int)$b->appliesToRight( 'createaccount' );
			return strcmp( $bWeight, $aWeight ); // highest weight first
		} );

		$blocksListExact = [
			'hard' => false,
			'disable_create' => false,
			'other' => false,
			'auto' => false
		];
		$blocksListRange = [
			'hard' => false,
			'disable_create' => false,
			'other' => false,
			'auto' => false
		];
		$ipChain = array_reverse( $ipChain );

		foreach ( $blocks as $block ) {
			// Stop searching if we have already have a "better" block. This
			// is why the order of the blocks matters
			if ( !$block->isHardblock() && $blocksListExact['hard'] ) {
				break;
			} elseif ( !$block->appliesToRight( 'createaccount' ) && $blocksListExact['disable_create'] ) {
				break;
			}

			foreach ( $ipChain as $checkip ) {
				$checkipHex = IPUtils::toHex( $checkip );
				if ( (string)$block->getTarget() === $checkip ) {
					if ( $block->isHardblock() ) {
						$blocksListExact['hard'] = $blocksListExact['hard'] ?: $block;
					} elseif ( $block->appliesToRight( 'createaccount' ) ) {
						$blocksListExact['disable_create'] = $blocksListExact['disable_create'] ?: $block;
					} elseif ( $block->mAuto ) {
						$blocksListExact['auto'] = $blocksListExact['auto'] ?: $block;
					} else {
						$blocksListExact['other'] = $blocksListExact['other'] ?: $block;
					}
					// We found closest exact match in the ip list, so go to the next block
					break;
				} elseif ( array_filter( $blocksListExact ) == []
					&& $block->getRangeStart() <= $checkipHex
					&& $block->getRangeEnd() >= $checkipHex
				) {
					if ( $block->isHardblock() ) {
						$blocksListRange['hard'] = $blocksListRange['hard'] ?: $block;
					} elseif ( $block->appliesToRight( 'createaccount' ) ) {
						$blocksListRange['disable_create'] = $blocksListRange['disable_create'] ?: $block;
					} elseif ( $block->mAuto ) {
						$blocksListRange['auto'] = $blocksListRange['auto'] ?: $block;
					} else {
						$blocksListRange['other'] = $blocksListRange['other'] ?: $block;
					}
					break;
				}
			}
		}

		if ( array_filter( $blocksListExact ) == [] ) {
			$blocksList = &$blocksListRange;
		} else {
			$blocksList = &$blocksListExact;
		}

		$chosenBlock = null;
		if ( $blocksList['hard'] ) {
			$chosenBlock = $blocksList['hard'];
		} elseif ( $blocksList['disable_create'] ) {
			$chosenBlock = $blocksList['disable_create'];
		} elseif ( $blocksList['other'] ) {
			$chosenBlock = $blocksList['other'];
		} elseif ( $blocksList['auto'] ) {
			$chosenBlock = $blocksList['auto'];
		} else {
			throw new MWException( "Proxy block found, but couldn't be classified." );
		}

		return $chosenBlock;
	}

	/**
	 * @inheritDoc
	 *
	 * Autoblocks have whichever type corresponds to their target, so to detect if a block is an
	 * autoblock, we have to check the mAuto property instead.
	 */
	public function getType() {
		return $this->mAuto
			? self::TYPE_AUTO
			: parent::getType();
	}

	/**
	 * @inheritDoc
	 */
	public function getIdentifier() {
		return $this->getId();
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
	public function getRawRestrictions() : ?array {
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
	private function getBlockRestrictionStore() : BlockRestrictionStore {
		return MediaWikiServices::getInstance()->getBlockRestrictionStore();
	}

	/**
	 * @inheritDoc
	 */
	public function getBy() {
		return ( $this->blocker ) ? $this->blocker->getId() : 0;
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
	 * Get the forcedTargetID if set
	 *
	 * @internal
	 * @return ?int
	 */
	public function getForcedTargetID() : ?int {
		return $this->forcedTargetID;
	}

	/**
	 * Set the user who implemented (or will implement) this block
	 *
	 * @param UserIdentity|string $user Local user object or username string.
	 *   Calling with string is deprecated since 1.36
	 */
	public function setBlocker( $user ) {
		if ( is_string( $user ) ) {
			wfDeprecatedMsg( 'Calling ' . __METHOD__ . ' with string as $user', '1.36' );
			$user = MediaWikiServices::getInstance()
				->getUserFactory()
				->newFromName( $user, UserFactory::RIGOR_NONE );
		}

		if ( !$user->isRegistered() &&
			MediaWikiServices::getInstance()->getUserNameUtils()->isUsable( $user->getName() )
		) {
			// Temporarily log some block details to debug T192964
			$logger = LoggerFactory::getInstance( 'BlockManager' );
			$logger->warning(
				'Blocker is neither a local user nor an invalid username',
				[
					'blocker' => (string)$user,
					'blockId' => $this->getId(),
				]
			);
			throw new \InvalidArgumentException(
				'Blocker must be a local user or a name that cannot be a local user'
			);
		}

		$this->blocker = $user;
	}
}

/**
 * @deprecated since 1.34
 */
class_alias( DatabaseBlock::class, 'Block' );
