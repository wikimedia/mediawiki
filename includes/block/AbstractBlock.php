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

use IContextSource;
use InvalidArgumentException;
use IP;
use MediaWiki\MediaWikiServices;
use RequestContext;
use Title;
use User;

/**
 * @since 1.34 Factored out from DatabaseBlock (previously Block).
 */
abstract class AbstractBlock {
	/**
	 * @deprecated since 1.34. Use getReason and setReason instead.
	 * @var string
	 */
	public $mReason;

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

	/** @var User|string */
	protected $target;

	/**
	 * @var int AbstractBlock::TYPE_ constant. After the block has been loaded
	 * from the database, this can only be USER, IP or RANGE.
	 */
	protected $type;

	/** @var User */
	protected $blocker;

	/** @var bool */
	protected $isSitewide = true;

	# TYPE constants
	const TYPE_USER = 1;
	const TYPE_IP = 2;
	const TYPE_RANGE = 3;
	const TYPE_AUTO = 4;
	const TYPE_ID = 5;

	/**
	 * Create a new block with specified parameters on a user, IP or IP range.
	 *
	 * @param array $options Parameters of the block, with supported options:
	 *  - address: (string|User) Target user name, User object, IP address or IP range
	 *  - by: (int) User ID of the blocker
	 *  - reason: (string) Reason of the block
	 *  - timestamp: (string) The time at which the block comes into effect
	 *  - byText: (string) Username of the blocker (for foreign users)
	 *  - hideName: (bool) Hide the target user name
	 */
	public function __construct( array $options = [] ) {
		$defaults = [
			'address'         => '',
			'by'              => null,
			'reason'          => '',
			'timestamp'       => '',
			'byText'          => '',
			'hideName'        => false,
		];

		$options += $defaults;

		$this->setTarget( $options['address'] );

		if ( $options['by'] ) {
			# Local user
			$this->setBlocker( User::newFromId( $options['by'] ) );
		} else {
			# Foreign user
			$this->setBlocker( $options['byText'] );
		}

		$this->setReason( $options['reason'] );
		$this->setTimestamp( wfTimestamp( TS_MW, $options['timestamp'] ) );
		$this->setHideName( (bool)$options['hideName'] );
	}

	/**
	 * Get the user id of the blocking sysop
	 *
	 * @return int (0 for foreign users)
	 */
	public function getBy() {
		return $this->getBlocker()->getId();
	}

	/**
	 * Get the username of the blocking sysop
	 *
	 * @return string
	 */
	public function getByName() {
		return $this->getBlocker()->getName();
	}

	/**
	 * Get the block ID
	 * @return int|null
	 */
	public function getId() {
		return null;
	}

	/**
	 * Get the reason given for creating the block
	 *
	 * @since 1.33
	 * @return string
	 */
	public function getReason() {
		return $this->mReason;
	}

	/**
	 * Set the reason for creating the block
	 *
	 * @since 1.33
	 * @param string $reason
	 */
	public function setReason( $reason ) {
		$this->mReason = $reason;
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
	 * Get/set whether the block prevents a given action
	 *
	 * @deprecated since 1.33, use appliesToRight to determine block
	 *  behaviour, and specific methods to get/set properties
	 * @param string $action Action to check
	 * @param bool|null $x Value for set, or null to just get value
	 * @return bool|null Null for unrecognized rights.
	 */
	public function prevents( $action, $x = null ) {
		$config = RequestContext::getMain()->getConfig();
		$blockDisablesLogin = $config->get( 'BlockDisablesLogin' );
		$blockAllowsUTEdit = $config->get( 'BlockAllowsUTEdit' );

		$res = null;
		switch ( $action ) {
			case 'edit':
				# For now... <evil laugh>
				$res = true;
				break;
			case 'createaccount':
				$res = wfSetVar( $this->blockCreateAccount, $x );
				break;
			case 'sendemail':
				$res = wfSetVar( $this->mBlockEmail, $x );
				break;
			case 'upload':
				// Until T6995 is completed
				$res = $this->isSitewide();
				break;
			case 'editownusertalk':
				// NOTE: this check is not reliable on partial blocks
				// since partially blocked users are always allowed to edit
				// their own talk page unless a restriction exists on the
				// page or User_talk: namespace
				wfSetVar( $this->allowUsertalk, $x === null ? null : !$x );
				$res = !$this->isUsertalkEditAllowed();

				// edit own user talk can be disabled by config
				if ( !$blockAllowsUTEdit ) {
					$res = true;
				}
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
			// prevent any action that all users cannot do
			$permissionManager = MediaWikiServices::getInstance()->getPermissionManager();
			$anon = new User;
			$res = $permissionManager->userHasRight( $anon, $action ) ? $res : true;
		}

		return $res;
	}

	/**
	 * From an existing block, get the target and the type of target.
	 * Note that, except for null, it is always safe to treat the target
	 * as a string; for User objects this will return User::__toString()
	 * which in turn gives User::getName().
	 *
	 * @param string|int|User|null $target
	 * @return array [ User|String|null, AbstractBlock::TYPE_ constant|null ]
	 */
	public static function parseTarget( $target ) {
		# We may have been through this before
		if ( $target instanceof User ) {
			if ( IP::isValid( $target->getName() ) ) {
				return [ $target, self::TYPE_IP ];
			} else {
				return [ $target, self::TYPE_USER ];
			}
		} elseif ( $target === null ) {
			return [ null, null ];
		}

		$target = trim( $target );

		if ( IP::isValid( $target ) ) {
			# We can still create a User if it's an IP address, but we need to turn
			# off validation checking (which would exclude IP addresses)
			return [
				User::newFromName( IP::sanitizeIP( $target ), false ),
				self::TYPE_IP
			];

		} elseif ( IP::isValidRange( $target ) ) {
			# Can't create a User from an IP range
			return [ IP::sanitizeRange( $target ), self::TYPE_RANGE ];
		}

		# Consider the possibility that this is not a username at all
		# but actually an old subpage (T31797)
		if ( strpos( $target, '/' ) !== false ) {
			# An old subpage, drill down to the user behind it
			$target = explode( '/', $target )[0];
		}

		$userObj = User::newFromName( $target );
		if ( $userObj instanceof User ) {
			# Note that since numbers are valid usernames, a $target of "12345" will be
			# considered a User.  If you want to pass a block ID, prepend a hash "#12345",
			# since hash characters are not valid in usernames or titles generally.
			return [ $userObj, self::TYPE_USER ];

		} elseif ( preg_match( '/^#\d+$/', $target ) ) {
			# Autoblock reference in the form "#12345"
			return [ substr( $target, 1 ), self::TYPE_AUTO ];

		} else {
			return [ null, null ];
		}
	}

	/**
	 * Get the type of target for this particular block.
	 * @return int AbstractBlock::TYPE_ constant, will never be TYPE_ID
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * Get the target and target type for this particular block. Note that for autoblocks,
	 * this returns the unredacted name; frontend functions need to call $block->getRedactedName()
	 * in this situation.
	 * @return array [ User|String, AbstractBlock::TYPE_ constant ]
	 * @todo FIXME: This should be an integral part of the block member variables
	 */
	public function getTargetAndType() {
		return [ $this->getTarget(), $this->getType() ];
	}

	/**
	 * Get the target for this particular block.  Note that for autoblocks,
	 * this returns the unredacted name; frontend functions need to call $block->getRedactedName()
	 * in this situation.
	 * @return User|string
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
	 * @param mixed $target
	 */
	public function setTarget( $target ) {
		list( $this->target, $this->type ) = static::parseTarget( $target );
	}

	/**
	 * Get the user who implemented this block
	 * @return User User object. May name a foreign user.
	 */
	public function getBlocker() {
		return $this->blocker;
	}

	/**
	 * Set the user who implemented (or will implement) this block
	 * @param User|string $user Local User object or username string
	 */
	public function setBlocker( $user ) {
		if ( is_string( $user ) ) {
			$user = User::newFromName( $user, false );
		}

		if ( $user->isAnon() && User::isUsableName( $user->getName() ) ) {
			throw new InvalidArgumentException(
				'Blocker must be a local user or a name that cannot be a local user'
			);
		}

		$this->blocker = $user;
	}

	/**
	 * Get the key and parameters for the corresponding error message.
	 *
	 * @since 1.22
	 * @param IContextSource $context
	 * @return array
	 */
	abstract public function getPermissionsError( IContextSource $context );

	/**
	 * Get block information used in different block error messages
	 *
	 * @since 1.33
	 * @param IContextSource $context
	 * @return array
	 */
	public function getBlockErrorParams( IContextSource $context ) {
		$lang = $context->getLanguage();

		$blocker = $this->getBlocker();
		if ( $blocker instanceof User ) { // local user
			$blockerUserpage = $blocker->getUserPage();
			$blockerText = $lang->embedBidi( $blockerUserpage->getText() );
			$link = "[[{$blockerUserpage->getPrefixedText()}|{$blockerText}]]";
		} else { // foreign user
			$link = $blocker;
		}

		$reason = $this->getReason();
		if ( $reason == '' ) {
			$reason = $context->msg( 'blockednoreason' )->text();
		}

		/* $ip returns who *is* being blocked, $intended contains who was meant to be blocked.
		 * This could be a username, an IP range, or a single IP. */
		$intended = (string)$this->getTarget();

		return [
			$link,
			$reason,
			$context->getRequest()->getIP(),
			$lang->embedBidi( $this->getByName() ),
			// TODO: SystemBlock replaces this with the system block type. Clean up
			// error params so that this is not necessary.
			$this->getId(),
			$lang->formatExpiry( $this->getExpiry() ),
			$lang->embedBidi( $intended ),
			$lang->userTimeAndDate( $this->getTimestamp(), $context->getUser() ),
		];
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
	 * Check if the block should be tracked with a cookie.
	 *
	 * @since 1.33
	 * @deprecated since 1.34 Use BlockManager::trackBlockWithCookie instead
	 *  of calling this directly.
	 * @param bool $isAnon The user is logged out
	 * @return bool The block should be tracked with a cookie
	 */
	public function shouldTrackWithCookie( $isAnon ) {
		wfDeprecated( __METHOD__, '1.34' );
		return false;
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
