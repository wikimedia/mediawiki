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

namespace MediaWiki\Block;

use InvalidArgumentException;
use LogicException;
use MediaWiki\CommentStore\CommentStoreComment;
use MediaWiki\DAO\WikiAwareEntityTrait;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Message\Message;
use MediaWiki\Title\Title;
use MediaWiki\User\UserIdentity;

/**
 * @note Extensions should not subclass this, as MediaWiki currently does not
 *   support custom block types.
 * @since 1.34 Factored out from DatabaseBlock (previously Block).
 */
abstract class AbstractBlock implements Block {
	use WikiAwareEntityTrait;

	/** @var CommentStoreComment */
	protected $reason;

	/** @var string */
	protected $timestamp = '';

	/** @var string */
	protected $expiry = '';

	/** @var bool */
	protected $blockEmail = false;

	/** @var bool */
	protected $allowUsertalk = false;

	/** @var bool */
	protected $blockCreateAccount = false;

	/** @var bool */
	protected $hideName = false;

	/** @var bool */
	protected $isHardblock;

	/** @var BlockTarget|null */
	protected $target;

	/** @var bool */
	protected $isSitewide = true;

	/** @var string|false */
	protected $wikiId;

	/**
	 * Create a new block with specified parameters on a user, IP or IP range.
	 *
	 * @param array $options Parameters of the block, with supported options:
	 *  - target: (BlockTarget) The target object (since 1.44)
	 *  - address: (string|UserIdentity) Target user name, user identity object,
	 *     IP address or IP range.
	 *  - wiki: (string|false) The wiki the block has been issued in,
	 *    self::LOCAL for the local wiki (since 1.38)
	 *  - reason: (string|Message|CommentStoreComment) Reason for the block
	 *  - timestamp: (string) The time at which the block comes into effect,
	 *    in any format supported by wfTimestamp()
	 *  - decodedTimestamp: (string) The timestamp in MW 14-character format
	 *  - hideName: (bool) Hide the target user name
	 *  - anonOnly: (bool) Used if the target is an IP address. The block only
	 *    applies to anon and temporary users using this IP address, and not to
	 *    logged-in users.
	 */
	public function __construct( array $options = [] ) {
		$defaults = [
			'wiki'            => self::LOCAL,
			'reason'          => '',
			'timestamp'       => '',
			'hideName'        => false,
			'anonOnly'        => false,
		];

		$options += $defaults;

		$this->wikiId = $options['wiki'];
		if ( isset( $options['target'] ) ) {
			if ( !( $options['target'] instanceof BlockTarget ) ) {
				throw new InvalidArgumentException( 'Invalid block target' );
			}
			$this->setTarget( $options['target'] );
		} elseif ( isset( $options['address'] ) ) {
			$this->setTarget( $options['address'] );
		} else {
			$this->setTarget( null );
		}
		$this->setReason( $options['reason'] );
		if ( isset( $options['decodedTimestamp'] ) ) {
			$this->setTimestamp( $options['decodedTimestamp'] );
		} else {
			$this->setTimestamp( wfTimestamp( TS_MW, $options['timestamp'] ) );
		}
		$this->setHideName( (bool)$options['hideName'] );
		$this->isHardblock( !$options['anonOnly'] );
	}

	/**
	 * Get the user id of the blocking sysop
	 *
	 * @param string|false $wikiId (since 1.38)
	 * @return int (0 for foreign users)
	 */
	abstract public function getBy( $wikiId = self::LOCAL ): int;

	/**
	 * Get the username of the blocking sysop
	 *
	 * @return string
	 */
	abstract public function getByName();

	/**
	 * @inheritDoc
	 */
	public function getId( $wikiId = self::LOCAL ): ?int {
		$this->assertWiki( $wikiId );
		return null;
	}

	/**
	 * Get the reason for creating the block.
	 *
	 * @since 1.35
	 * @return CommentStoreComment
	 */
	public function getReasonComment(): CommentStoreComment {
		return $this->reason;
	}

	/**
	 * Set the reason for creating the block.
	 *
	 * @since 1.33
	 * @param string|Message|CommentStoreComment $reason
	 */
	public function setReason( $reason ) {
		$this->reason = CommentStoreComment::newUnsavedComment( $reason );
	}

	/**
	 * Get whether the block hides the target's username
	 *
	 * @since 1.33
	 * @return bool The block hides the username
	 */
	public function getHideName() {
		return $this->hideName;
	}

	/**
	 * Set whether the block hides the target's username
	 *
	 * @since 1.33
	 * @param bool $hideName The block hides the username
	 */
	public function setHideName( $hideName ) {
		$this->hideName = $hideName;
	}

	/**
	 * Indicates that the block is a sitewide block. This means the user is
	 * prohibited from editing any page on the site (other than their own talk
	 * page).
	 *
	 * @since 1.33
	 * @param null|bool $x
	 * @return bool
	 */
	public function isSitewide( $x = null ): bool {
		return wfSetVar( $this->isSitewide, $x );
	}

	/**
	 * Get or set the flag indicating whether this block blocks the target from
	 * creating an account. (Note that the flag may be overridden depending on
	 * global configs.)
	 *
	 * @since 1.33
	 * @param null|bool $x Value to set (if null, just get the property value)
	 * @return bool Value of the property
	 */
	public function isCreateAccountBlocked( $x = null ): bool {
		return wfSetVar( $this->blockCreateAccount, $x );
	}

	/**
	 * Get or set the flag indicating whether this block blocks the target from
	 * sending emails. (Note that the flag may be overridden depending on
	 * global configs.)
	 *
	 * @since 1.33
	 * @param null|bool $x Value to set (if null, just get the property value)
	 * @return bool Value of the property
	 */
	public function isEmailBlocked( $x = null ) {
		return wfSetVar( $this->blockEmail, $x );
	}

	/**
	 * Get or set the flag indicating whether this block blocks the target from
	 * editing their own user talk page. (Note that the flag may be overridden
	 * depending on global configs.)
	 *
	 * @since 1.33
	 * @param null|bool $x Value to set (if null, just get the property value)
	 * @return bool Value of the property
	 */
	public function isUsertalkEditAllowed( $x = null ) {
		return wfSetVar( $this->allowUsertalk, $x );
	}

	/**
	 * Get/set whether the block is a hard block (affects logged-in users on a
	 * given IP/range).
	 *
	 * Note that temporary users are not considered logged-in here - they are
	 * always blocked by IP-address blocks.
	 *
	 * Note that user blocks are always hard blocks, since the target is logged
	 * in by definition.
	 *
	 * @since 1.36 Moved up from DatabaseBlock
	 * @param bool|null $x
	 * @return bool
	 */
	public function isHardblock( $x = null ): bool {
		wfSetVar( $this->isHardblock, $x );

		return $this->getType() == self::TYPE_USER
			? true
			: $this->isHardblock;
	}

	/**
	 * Determine whether the block prevents a given right. A right may be
	 * allowed or disallowed by default, or determined from a property on the
	 * block object. For certain rights, the property may be overridden
	 * according to global configs.
	 *
	 * @since 1.33
	 * @param string $right
	 * @return bool|null The block applies to the right, or null if
	 *  unsure (e.g. unrecognized right or unset property)
	 */
	public function appliesToRight( $right ) {
		$blockDisablesLogin = MediaWikiServices::getInstance()->getMainConfig()
			->get( MainConfigNames::BlockDisablesLogin );

		$res = null;
		switch ( $right ) {
			case 'autocreateaccount':
			case 'createaccount':
				$res = $this->isCreateAccountBlocked();
				break;
			case 'sendemail':
				$res = $this->isEmailBlocked();
				break;
			case 'upload':
				// Sitewide blocks always block upload. This may be overridden in a subclass.
				$res = $this->isSitewide();
				break;
			case 'read':
				$res = false;
				break;
		}
		if ( !$res && $blockDisablesLogin ) {
			// If a block would disable login, then it should
			// prevent any right that all users cannot do
			$permissionManager = MediaWikiServices::getInstance()->getPermissionManager();
			$anon = MediaWikiServices::getInstance()->getUserFactory()->newAnonymous();
			$res = $permissionManager->userHasRight( $anon, $right ) ? $res : true;
		}

		return $res;
	}

	public function getTarget(): ?BlockTarget {
		return $this->target;
	}

	public function getRedactedTarget(): ?BlockTarget {
		$target = $this->getTarget();
		if ( $this->getType() === Block::TYPE_AUTO
			&& !( $target instanceof AutoBlockTarget )
		) {
			$id = $this->getId( $this->wikiId );
			if ( $id === null ) {
				throw new LogicException( 'no ID available for autoblock redaction' );
			}
			$target = new AutoBlockTarget( $id, $this->wikiId );
		}
		return $target;
	}

	/**
	 * Get the type of target for this particular block.
	 * @return int|null AbstractBlock::TYPE_ constant
	 */
	public function getType(): ?int {
		return $this->target ? $this->target->getType() : null;
	}

	/**
	 * @since 1.37
	 * @return ?UserIdentity
	 */
	public function getTargetUserIdentity(): ?UserIdentity {
		return $this->target instanceof BlockTargetWithUserIdentity
			? $this->target->getUserIdentity() : null;
	}

	/**
	 * @since 1.37
	 * @return string
	 */
	public function getTargetName(): string {
		return (string)$this->target;
	}

	/**
	 * @param BlockTarget|UserIdentity|string $target
	 *
	 * @return bool
	 * @since 1.37
	 */
	public function isBlocking( $target ): bool {
		$targetName = $target instanceof UserIdentity
			? $target->getName()
			: (string)$target;

		return $targetName === $this->getTargetName();
	}

	/**
	 * Get the block expiry time
	 *
	 * @since 1.19
	 * @return string
	 */
	public function getExpiry(): string {
		return $this->expiry;
	}

	/** @inheritDoc */
	public function isIndefinite(): bool {
		return wfIsInfinity( $this->getExpiry() );
	}

	/**
	 * Set the block expiry time
	 *
	 * @since 1.33
	 * @param string $expiry
	 */
	public function setExpiry( $expiry ) {
		// Force string so getExpiry() return typehint doesn't break things
		$this->expiry = (string)$expiry;
	}

	/**
	 * Get the timestamp indicating when the block was created
	 *
	 * @since 1.33
	 * @return string
	 */
	public function getTimestamp(): string {
		return $this->timestamp;
	}

	/**
	 * Set the timestamp indicating when the block was created
	 *
	 * @since 1.33
	 * @param string $timestamp
	 */
	public function setTimestamp( $timestamp ) {
		// Force string so getTimestamp() return typehint doesn't break things
		$this->timestamp = (string)$timestamp;
	}

	/**
	 * Set the target for this block
	 * @param BlockTarget|string|UserIdentity|null $target
	 */
	public function setTarget( $target ) {
		// Small optimization to make this code testable, this is what would happen anyway
		if ( $target === '' || $target === null ) {
			$this->target = null;
		} elseif ( $target instanceof BlockTarget ) {
			$this->assertWiki( $target->getWikiId() );
			$this->target = $target;
		} else {
			$parsedTarget = MediaWikiServices::getInstance()
				->getCrossWikiBlockTargetFactory()
				->getFactory( $this->wikiId )
				->newFromLegacyUnion( $target );
			$this->assertWiki( $parsedTarget->getWikiId() );
			$this->target = $parsedTarget;
		}
	}

	/**
	 * @since 1.38
	 * @return string|false
	 */
	public function getWikiId() {
		return $this->wikiId;
	}

	/**
	 * Determine whether the block allows the user to edit their own
	 * user talk page. This is done separately from
	 * AbstractBlock::appliesToRight because there is no right for
	 * editing one's own user talk page and because the user's talk
	 * page needs to be passed into the block object, which is unaware
	 * of the user.
	 *
	 * The bl_allow_usertalk flag (which corresponds to the property
	 * allowUsertalk) is used on sitewide blocks and partial blocks
	 * that contain a namespace restriction on the user talk namespace,
	 * but do not contain a page restriction on the user's talk page.
	 * For all other (i.e. most) partial blocks, the flag is ignored,
	 * and the user can always edit their user talk page unless there
	 * is a page restriction on their user talk page, in which case
	 * they can never edit it. (Ideally the flag would be stored as
	 * null in these cases, but the database field isn't nullable.)
	 *
	 * This method does not validate that the passed in talk page belongs to the
	 * block target since the target (an IP) might not be the same as the user's
	 * talk page (if they are logged in).
	 *
	 * @since 1.33
	 * @param Title|null $usertalk The user's user talk page. If null,
	 *  and if the target is a User, the target's userpage is used
	 * @return bool The user can edit their talk page
	 */
	public function appliesToUsertalk( ?Title $usertalk = null ) {
		if ( !$usertalk ) {
			if ( $this->target instanceof BlockTargetWithUserPage ) {
				$usertalk = Title::makeTitle(
					NS_USER_TALK,
					$this->target->getUserPage()->getDBkey()
				);
			} else {
				throw new InvalidArgumentException(
					'$usertalk must be provided if block target is not a user/IP'
				);
			}
		}

		if ( $usertalk->getNamespace() !== NS_USER_TALK ) {
			throw new InvalidArgumentException(
				'$usertalk must be a user talk page'
			);
		}

		if ( !$this->isSitewide() ) {
			if ( $this->appliesToPage( $usertalk->getArticleID() ) ) {
				return true;
			}
			if ( !$this->appliesToNamespace( NS_USER_TALK ) ) {
				return false;
			}
		}

		// This is a type of block which uses the bl_allow_usertalk
		// flag. The flag can still be overridden by global configs.
		if ( !MediaWikiServices::getInstance()->getMainConfig()
			->get( MainConfigNames::BlockAllowsUTEdit )
		) {
			return true;
		}
		return !$this->isUsertalkEditAllowed();
	}

	/**
	 * Checks if a block applies to a particular title
	 *
	 * This check does not consider whether `$this->isUsertalkEditAllowed`
	 * returns false, as the identity of the user making the hypothetical edit
	 * isn't known here (particularly in the case of IP hard blocks, range
	 * blocks, and auto-blocks).
	 *
	 * @param Title $title
	 * @return bool
	 */
	public function appliesToTitle( Title $title ) {
		return $this->isSitewide();
	}

	/**
	 * Checks if a block applies to a particular namespace
	 *
	 * @since 1.33
	 *
	 * @param int $ns
	 * @return bool
	 */
	public function appliesToNamespace( $ns ) {
		return $this->isSitewide();
	}

	/**
	 * Checks if a block applies to a particular page
	 *
	 * This check does not consider whether `$this->isUsertalkEditAllowed`
	 * returns false, as the identity of the user making the hypothetical edit
	 * isn't known here (particularly in the case of IP hard blocks, range
	 * blocks, and auto-blocks).
	 *
	 * @since 1.33
	 *
	 * @param int $pageId
	 * @return bool
	 */
	public function appliesToPage( $pageId ) {
		return $this->isSitewide();
	}

	/**
	 * Check if the block prevents a user from resetting their password
	 *
	 * @since 1.33
	 * @return bool The block blocks password reset
	 */
	public function appliesToPasswordReset() {
		return $this->isCreateAccountBlocked();
	}

	/**
	 * @return AbstractBlock[]
	 */
	public function toArray(): array {
		return [ $this ];
	}

}
