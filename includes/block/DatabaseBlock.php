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

use InvalidArgumentException;
use MediaWiki\Block\Restriction\ActionRestriction;
use MediaWiki\Block\Restriction\NamespaceRestriction;
use MediaWiki\Block\Restriction\PageRestriction;
use MediaWiki\Block\Restriction\Restriction;
use MediaWiki\Html\Html;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Title\Title;
use MediaWiki\User\UserIdentity;
use stdClass;
use UnexpectedValueException;
use Wikimedia\IPUtils;
use Wikimedia\Rdbms\IDatabase;

/**
 * A DatabaseBlock (unlike a SystemBlock) is stored in the database, may give
 * rise to autoblocks and may be tracked with cookies. Such blocks* are more
 * customizable than system blocks: they may be hard blocks, and they may be
 * sitewide or partial.
 *
 * @since 1.34 Renamed from Block.
 */
class DatabaseBlock extends AbstractBlock {
	/** @var bool */
	private $auto;

	/** @var int|null */
	private $parentBlockId;

	/** @var int|null */
	private $id;

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
	 *  - auto: (bool) Is this an automatic block?
	 *  - expiry: (string) Database timestamp of expiration of the block or 'infinity'
	 *  - decodedExpiry: (string) The decoded expiry in MW 14-char format or 'infinity'
	 *  - anonOnly: (bool) Only disallow anonymous actions
	 *  - createAccount: (bool) Disallow creation of new accounts
	 *  - enableAutoblock: (bool) Enable automatic blocking
	 *  - blockEmail: (bool) Disallow sending emails
	 *  - allowUsertalk: (bool) Allow the target to edit its own talk page
	 *  - sitewide: (bool) Disallow editing all pages and all contribution actions,
	 *    except those specifically allowed by other block flags
	 *  - by: (UserIdentity) UserIdentity object of the blocker.
	 *  - restrictions: (Restriction[]) Array of partial block restrictions
	 *
	 * @since 1.26 $options array
	 */
	public function __construct( array $options = [] ) {
		parent::__construct( $options );

		$defaults = [
			'id'              => null,
			'parentBlockId'   => null,
			'auto'            => false,
			'expiry'          => '',
			'createAccount'   => false,
			'enableAutoblock' => false,
			'blockEmail'      => false,
			'allowUsertalk'   => false,
			'sitewide'        => true,
			'by'              => null,
			'restrictions'    => null,
		];

		$options += $defaults;

		$this->id = $options['id'];
		$this->parentBlockId = $options['parentBlockId'];

		if ( $options['by'] instanceof UserIdentity ) {
			$this->setBlocker( $options['by'] );
		}

		if ( isset( $options['decodedExpiry'] ) ) {
			$this->setExpiry( $options['decodedExpiry'] );
		} else {
			$this->setExpiry( $this->getDBConnection( DB_REPLICA )->decodeExpiry( $options['expiry'] ) );
		}

		if ( $options['restrictions'] !== null ) {
			$this->setRestrictions( $options['restrictions'] );
		}

		// Boolean settings
		$this->auto = (bool)$options['auto'];
		$this->isAutoblocking( (bool)$options['enableAutoblock'] );
		$this->isSitewide( (bool)$options['sitewide'] );
		$this->isEmailBlocked( (bool)$options['blockEmail'] );
		$this->isCreateAccountBlocked( (bool)$options['createAccount'] );
		$this->isUsertalkEditAllowed( (bool)$options['allowUsertalk'] );
	}

	/**
	 * Load a block from the block ID.
	 *
	 * @deprecated since 1.42 use DatabaseBlockStore::newFromID()
	 * @param int $id ID to search for
	 * @return DatabaseBlock|null
	 */
	public static function newFromID( $id ) {
		wfDeprecated( __METHOD__, '1.42' );
		return MediaWikiServices::getInstance()->getDatabaseBlockStore()
			->newFromID( $id );
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
	 *   - tables: (string[]) to include in the `$table` to `IDatabase->select()`
	 *     or `SelectQueryBuilder::tables`
	 *   - fields: (string[]) to include in the `$vars` to `IDatabase->select()`
	 *     or `SelectQueryBuilder::fields`
	 *   - joins: (array) to include in the `$join_conds` to `IDatabase->select()`
	 *     or `SelectQueryBuilder::joinConds`
	 * @phan-return array{tables:string[],fields:string[],joins:array}
	 */
	public static function getQueryInfo() {
		return MediaWikiServices::getInstance()->getDatabaseBlockStore()
			->getQueryInfo( DatabaseBlockStore::SCHEMA_IPBLOCKS );
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
			&& $this->auto == $block->auto
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
	 * Get a set of SQL conditions which will select range blocks encompassing a given range
	 * @param string $start Hexadecimal IP representation
	 * @param string|null $end Hexadecimal IP representation, or null to use $start = $end
	 * @return string
	 */
	public static function getRangeCond( $start, $end = null ) {
		return MediaWikiServices::getInstance()->getDatabaseBlockStore()
			->getRangeCond( $start, $end, DatabaseBlockStore::SCHEMA_IPBLOCKS );
	}

	/**
	 * Create a new DatabaseBlock object from a database row
	 * @param stdClass $row Row from the ipblocks table
	 * @return DatabaseBlock
	 */
	public static function newFromRow( $row ) {
		$services = MediaWikiServices::getInstance();
		$db = $services->getConnectionProvider()->getReplicaDatabase();
		return $services->getDatabaseBlockStore()->newFromRow( $db, $row );
	}

	/**
	 * Delete the row from the IP blocks table.
	 *
	 * @deprecated since 1.36 Use DatabaseBlockStore::deleteBlock instead.
	 * @return bool
	 */
	public function delete() {
		return MediaWikiServices::getInstance()
			->getDatabaseBlockStoreFactory()
			->getDatabaseBlockStore( $this->getWikiId() )
			->deleteBlock( $this );
	}

	/**
	 * Insert a block into the block table. Will fail if there is a conflicting
	 * block (same name and options) already in the database.
	 *
	 * @deprecated since 1.36 Use DatabaseBlockStore::insertBlock instead.
	 *             Passing a custom db connection is no longer supported since 1.42.
	 *
	 * @return bool|array False on failure, assoc array on success:
	 * 	('id' => block ID, 'autoIds' => array of autoblock IDs)
	 */
	public function insert() {
		return MediaWikiServices::getInstance()
			->getDatabaseBlockStoreFactory()
			->getDatabaseBlockStore( $this->getWikiId() )
			->insertBlock( $this );
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
			->getDatabaseBlockStoreFactory()
			->getDatabaseBlockStore( $this->getWikiId() )
			->updateBlock( $this );
	}

	/**
	 * Checks whether a given IP is on the autoblock exemption list.
	 *
	 * @since 1.36
	 *
	 * @param string $ip The IP to check
	 * @return bool
	 */
	public static function isExemptedFromAutoblocks( $ip ) {
		return MediaWikiServices::getInstance()->getAutoblockExemptionList()
			->isExempt( $ip );
	}

	/**
	 * Autoblocks the given IP, referring to this block.
	 *
	 * @deprecated since 1.42, use DatabaseBlockStore::doAutoblock instead
	 *
	 * @param string $autoblockIP The IP to autoblock.
	 * @return int|false ID if an autoblock was inserted, false if not.
	 */
	public function doAutoblock( $autoblockIP ) {
		return MediaWikiServices::getInstance()
			->getDatabaseBlockStoreFactory()
			->getDatabaseBlockStore( $this->getWikiId() )
			->doAutoblock( $this, $autoblockIP );
	}

	/**
	 * Has the block expired?
	 * @return bool
	 */
	public function isExpired() {
		$timestamp = wfTimestampNow();
		wfDebug( __METHOD__ . " checking current " . $timestamp . " vs $this->expiry" );

		return $this->getExpiry() && $timestamp > $this->getExpiry();
	}

	/**
	 * Update the timestamp on autoblocks.
	 *
	 * @deprecated since 1.42, use DatabaseBlockStore::updateTimestamp instead
	 */
	public function updateTimestamp() {
		MediaWikiServices::getInstance()
			->getDatabaseBlockStoreFactory()
			->getDatabaseBlockStore( $this->getWikiId() )
			->updateTimestamp( $this );
	}

	/**
	 * Get the IP address at the start of the range in Hex form
	 * @return string IP in Hex form
	 */
	public function getRangeStart() {
		switch ( $this->type ) {
			case self::TYPE_USER:
				return '';
			case self::TYPE_IP:
				return IPUtils::toHex( $this->target );
			case self::TYPE_RANGE:
				[ $start, /*...*/ ] = IPUtils::parseRange( $this->target );
				return $start;
			default:
				throw new UnexpectedValueException( "Block with invalid type" );
		}
	}

	/**
	 * Get the IP address at the end of the range in Hex form
	 * @return string IP in Hex form
	 */
	public function getRangeEnd() {
		switch ( $this->type ) {
			case self::TYPE_USER:
				return '';
			case self::TYPE_IP:
				return IPUtils::toHex( $this->target );
			case self::TYPE_RANGE:
				[ /*...*/, $end ] = IPUtils::parseRange( $this->target );
				return $end;
			default:
				throw new UnexpectedValueException( "Block with invalid type" );
		}
	}

	/**
	 * @inheritDoc
	 */
	public function getId( $wikiId = self::LOCAL ): ?int {
		$this->assertWiki( $wikiId );
		return $this->id;
	}

	/**
	 * Set the block ID
	 *
	 * @internal Only for use in DatabaseBlockStore; private until 1.36
	 * @param int $blockId
	 * @return self
	 */
	public function setId( $blockId ) {
		$this->id = (int)$blockId;

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
		// Sanity: this shouldn't have been 0, because when it was set in
		// initFromRow() we converted 0 to null, in case the object was serialized
		// and then unserialized, force 0 back to null, see T282890
		return $this->parentBlockId ?: null;
	}

	/**
	 * Get/set whether the block is a hard block (affects logged-in users on a given IP/range)
	 * @param bool|null $x
	 * @return bool
	 */
	public function isHardblock( $x = null ): bool {
		wfSetVar( $this->isHardblock, $x );

		// All user blocks are hard blocks
		return $this->getType() == self::TYPE_USER
			? true
			: $this->isHardblock;
	}

	/**
	 * Does the block cause autoblocks to be created?
	 *
	 * @param null|bool $x
	 * @return bool
	 */
	public function isAutoblocking( $x = null ) {
		wfSetVar( $this->isAutoblocking, $x );

		// You can't put an autoblock on an IP or range as we don't have any history to
		// look over to get more IPs from
		return $this->getType() == self::TYPE_USER
			? $this->isAutoblocking
			: false;
	}

	/**
	 * Get the block name, but with autoblocked IPs hidden as per standard privacy policy
	 * @return string Text is escaped
	 */
	public function getRedactedName() {
		if ( $this->auto ) {
			return Html::element(
				'span',
				[ 'class' => 'mw-autoblockid' ],
				wfMessage( 'autoblockid', $this->id )->text()
			);
		} else {
			return htmlspecialchars( $this->getTargetName() );
		}
	}

	/**
	 * Get the expiry timestamp for an autoblock created at the given time.
	 *
	 * @deprecated since 1.42 No replacement, no known callers.
	 *
	 * @param string|int $timestamp
	 * @return string
	 */
	public static function getAutoblockExpiry( $timestamp ) {
		wfDeprecated( __METHOD__, '1.42' );
		return MediaWikiServices::getInstance()->getDatabaseBlockStore()
			->getAutoblockExpiry( $timestamp );
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
	public static function newFromTarget(
		$specificTarget,
		$vagueTarget = null,
		$fromPrimary = false
	) {
		return MediaWikiServices::getInstance()->getDatabaseBlockStore()
			->newFromTarget( $specificTarget, $vagueTarget, $fromPrimary );
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
		return MediaWikiServices::getInstance()->getDatabaseBlockStore()
			->newListFromTarget( $specificTarget, $vagueTarget, $fromPrimary );
	}

	/**
	 * Get all blocks that match any IP from an array of IP addresses
	 *
	 * @param array $ipChain List of IPs (strings), usually retrieved from the
	 *     X-Forwarded-For header of the request
	 * @param bool $applySoftBlocks Include soft blocks (anonymous-only blocks). These
	 *     should only block anonymous and temporary users.
	 * @param bool $fromPrimary Whether to query the primary or replica DB
	 * @return self[]
	 * @since 1.22
	 */
	public static function getBlocksForIPList( array $ipChain, $applySoftBlocks, $fromPrimary = false ) {
		return MediaWikiServices::getInstance()->getBlockManager()
			->getBlocksForIPList( $ipChain, $applySoftBlocks, $fromPrimary );
	}

	/**
	 * @inheritDoc
	 *
	 * Autoblocks have whichever type corresponds to their target, so to detect if a block is an
	 * autoblock, we have to check the mAuto property instead.
	 */
	public function getType(): ?int {
		return $this->auto
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
			// If the block ID has not been set, then do not attempt to load the
			// restrictions.
			if ( !$this->id ) {
				return [];
			}
			$this->restrictions = $this->getBlockRestrictionStore()->loadByBlockId( $this->id );
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
		$this->restrictions = $restrictions;
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
