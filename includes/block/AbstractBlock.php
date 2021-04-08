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

use CommentStoreComment;
use IContextSource;
use InvalidArgumentException;
use MediaWiki\MediaWikiServices;
use MediaWiki\User\UserIdentity;
use Message;
use RequestContext;
use Title;
use User;

/**
 * @note Extensions should not subclass this, as MediaWiki currently does not support custom block types.
 * @since 1.34 Factored out from DatabaseBlock (previously Block).
 */
abstract class AbstractBlock {
	/** @var CommentStoreComment */
	protected $reason;

	/**
	 * @deprecated since 1.34. Use getTimestamp and setTimestamp instead.
	 * @var string
	 */
	public $mTimestamp;

	/**
	 * @deprecated since 1.34. Use getExpiry and setExpiry instead.
	 * @var string
	 */
	public $mExpiry = '';

	/** @var bool */
	protected $mBlockEmail = false;

	/** @var bool */
	protected $allowUsertalk = false;

	/** @var bool */
	protected $blockCreateAccount = false;

	/**
	 * @deprecated since 1.34. Use getHideName and setHideName instead.
	 * @var bool
	 */
	public $mHideName = false;

	/** @var bool */
	protected $isHardblock;

	/** @var User|string|null */
	protected $target;

	/**
	 * @var int|null AbstractBlock::TYPE_ constant. After the block has been loaded
	 * from the database, this can only be USER, IP or RANGE.
	 */
	protected $type;

	/** @var bool */
	protected $isSitewide = true;

	# TYPE constants
	# Do not introduce negative constants without changing BlockUser command object.
	public const TYPE_USER = 1;
	public const TYPE_IP = 2;
	public const TYPE_RANGE = 3;
	public const TYPE_AUTO = 4;
	public const TYPE_ID = 5;

	/**
	 * Create a new block with specified parameters on a user, IP or IP range.
	 *
	 * @param array $options Parameters of the block, with supported options:
	 *  - address: (string|UserIdentity) Target user name, user identity object, IP address or IP range
	 *  - reason: (string|Message|CommentStoreComment) Reason for the block
	 *  - timestamp: (string) The time at which the block comes into effect
	 *  - hideName: (bool) Hide the target user name
	 */
	public function __construct( array $options = [] ) {
		$defaults = [
			'address'         => '',
			'reason'          => '',
			'timestamp'       => '',
			'hideName'        => false,
			'anonOnly'        => false,
		];

		$options += $defaults;

		$this->setTarget( $options['address'] );

		$this->setReason( $options['reason'] );
		$this->setTimestamp( wfTimestamp( TS_MW, $options['timestamp'] ) );
		$this->setHideName( (bool)$options['hideName'] );
		$this->isHardblock( !$options['anonOnly'] );
	}

	/**
	 * Get the user id of the blocking sysop
	 *
	 * @return int (0 for foreign users)
	 */
	abstract public function getBy();

	/**
	 * Get the username of the blocking sysop
	 *
	 * @return string
	 */
	abstract public function getByName();

	/**
	 * Get the block ID
	 * @return int|null
	 */
	public function getId() {
		return null;
	}

	/**
	 * Get the information that identifies this block, such that a user could
	 * look up everything that can be found about this block. May be an ID,
	 * array of IDs, type, etc.
	 *
	 * @return mixed Identifying information
	 */
	abstract public function getIdentifier();

	/**
	 * Get the reason given for creating the block, as a string.
	 *
	 * Deprecated, since this gives the caller no control over the language
	 * or format, and no access to the comment's data.
	 *
	 * @deprecated since 1.35. Use getReasonComment instead.
	 * @since 1.33
	 * @return string
	 */
	public function getReason() {
		$language = RequestContext::getMain()->getLanguage();
		return $this->reason->message->inLanguage( $language )->plain();
	}

	/**
	 * Get the reason for creating the block.
	 *
	 * @since 1.35
	 * @return CommentStoreComment
	 */
	public function getReasonComment() {
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
		return $this->mHideName;
	}

	/**
	 * Set whether ths block hides the target's username
	 *
	 * @since 1.33
	 * @param bool $hideName The block hides the username
	 */
	public function setHideName( $hideName ) {
		$this->mHideName = $hideName;
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
	public function isSitewide( $x = null ) {
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
	public function isCreateAccountBlocked( $x = null ) {
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
		return wfSetVar( $this->mBlockEmail, $x );
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
	 * Get/set whether the block is a hardblock (affects logged-in users on a given IP/range)
	 *
	 * Note that users are always hardblocked, since they're logged in by definition.
	 *
	 * @since 1.36 Moved up from DatabaseBlock
	 * @param bool|null $x
	 * @return bool
	 */
	public function isHardblock( $x = null ) {
		wfSetVar( $this->isHardblock, $x );

		return $this->getType() == self::TYPE_USER
			? true
			: $this->isHardblock;
	}

	/**
	 * Determine whether the block prevents a given right. A right
	 * may be blacklisted or whitelisted, or determined from a
	 * property on the block object. For certain rights, the property
	 * may be overridden according to global configs.
	 *
	 * @since 1.33
	 * @param string $right
	 * @return bool|null The block applies to the right, or null if
	 *  unsure (e.g. unrecognized right or unset property)
	 */
	public function appliesToRight( $right ) {
		$config = RequestContext::getMain()->getConfig();
		$blockDisablesLogin = $config->get( 'BlockDisablesLogin' );

		$res = null;
		switch ( $right ) {
			case 'edit':
				// TODO: fix this case to return proper value
				$res = true;
				break;
			case 'createaccount':
				$res = $this->isCreateAccountBlocked();
				break;
			case 'sendemail':
				$res = $this->isEmailBlocked();
				break;
			case 'upload':
				// Until T6995 is completed
				$res = $this->isSitewide();
				break;
			case 'read':
				$res = false;
				break;
			case 'purge':
				$res = false;
				break;
		}
		if ( !$res && $blockDisablesLogin ) {
			// If a block would disable login, then it should
			// prevent any right that all users cannot do
			$permissionManager = MediaWikiServices::getInstance()->getPermissionManager();
			$anon = new User;
			$res = $permissionManager->userHasRight( $anon, $right ) ? $res : true;
		}

		return $res;
	}

	/**
	 * From an existing block, get the target and the type of target.
	 * Note that, except for null, it is always safe to treat the target
	 * as a string; for User objects this will return User::__toString()
	 * which in turn gives User::getName().
	 *
	 * If the type is not null, it will be an AbstractBlock::TYPE_ constant.
	 *
	 * @deprecated since 1.36. Use BlockUtils service instead.
	 * @param string|UserIdentity|null $target
	 * @return array [ User|string|null, int|null ]
	 */
	public static function parseTarget( $target ) {
		wfDeprecated( __METHOD__, '1.36' );
		return MediaWikiServices::getInstance()
			->getBlockUtils()
			->parseBlockTarget( $target );
	}

	/**
	 * Get the type of target for this particular block.
	 * @return int|null AbstractBlock::TYPE_ constant, will never be TYPE_ID
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * Get the target and target type for this particular block. Note that for autoblocks,
	 * this returns the unredacted name; frontend functions need to call $block->getRedactedName()
	 * in this situation.
	 *
	 * If the type is not null, it will be an AbstractBlock::TYPE_ constant.
	 *
	 * @return array [ User|String|null, int|null ]
	 * @todo FIXME: This should be an integral part of the block member variables
	 */
	public function getTargetAndType() {
		return [ $this->getTarget(), $this->getType() ];
	}

	/**
	 * Get the target for this particular block.  Note that for autoblocks,
	 * this returns the unredacted name; frontend functions need to call $block->getRedactedName()
	 * in this situation.
	 * @return User|string|null
	 */
	public function getTarget() {
		return $this->target;
	}

	/**
	 * Get the block expiry time
	 *
	 * @since 1.19
	 * @return string
	 */
	public function getExpiry() {
		return $this->mExpiry;
	}

	/**
	 * Set the block expiry time
	 *
	 * @since 1.33
	 * @param string $expiry
	 */
	public function setExpiry( $expiry ) {
		$this->mExpiry = $expiry;
	}

	/**
	 * Get the timestamp indicating when the block was created
	 *
	 * @since 1.33
	 * @return string
	 */
	public function getTimestamp() {
		return $this->mTimestamp;
	}

	/**
	 * Set the timestamp indicating when the block was created
	 *
	 * @since 1.33
	 * @param string $timestamp
	 */
	public function setTimestamp( $timestamp ) {
		$this->mTimestamp = $timestamp;
	}

	/**
	 * Set the target for this block, and update $this->type accordingly
	 * @param string|UserIdentity|null $target
	 */
	public function setTarget( $target ) {
		// Small optimization to make this code testable, this is what would happen anyway
		if ( $target === '' ) {
			$this->target = null;
			$this->type = null;
		} else {
			list( $this->target, $this->type ) = MediaWikiServices::getInstance()
				->getBlockUtils()
				->parseBlockTarget( $target );
		}
	}

	/**
	 * Get the key and parameters for the corresponding error message.
	 *
	 * @deprecated since 1.35 Use BlockErrorFormatter::getMessage instead, and
	 *  build the array using Message::getKey and Message::getParams.
	 * @since 1.22
	 * @param IContextSource $context
	 * @return array
	 */
	public function getPermissionsError( IContextSource $context ) {
		$message = MediaWikiServices::getInstance()
			->getBlockErrorFormatter()->getMessage(
				$this,
				$context->getUser(),
				$context->getLanguage(),
				$context->getRequest()->getIP()
			);
		return array_merge( [ [ $message->getKey() ], $message->getParams() ] );
	}

	/**
	 * Determine whether the block allows the user to edit their own
	 * user talk page. This is done separately from
	 * AbstractBlock::appliesToRight because there is no right for
	 * editing one's own user talk page and because the user's talk
	 * page needs to be passed into the block object, which is unaware
	 * of the user.
	 *
	 * The ipb_allow_usertalk flag (which corresponds to the property
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
	public function appliesToUsertalk( Title $usertalk = null ) {
		if ( !$usertalk ) {
			if ( $this->target instanceof User ) {
				$usertalk = $this->target->getTalkPage();
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

		// This is a type of block which uses the ipb_allow_usertalk
		// flag. The flag can still be overridden by global configs.
		$config = RequestContext::getMain()->getConfig();
		if ( !$config->get( 'BlockAllowsUTEdit' ) ) {
			return true;
		}
		return !$this->isUsertalkEditAllowed();
	}

	/**
	 * Checks if a block applies to a particular title
	 *
	 * This check does not consider whether `$this->isUsertalkEditAllowed`
	 * returns false, as the identity of the user making the hypothetical edit
	 * isn't known here (particularly in the case of IP hardblocks, range
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
	 * isn't known here (particularly in the case of IP hardblocks, range
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

}
