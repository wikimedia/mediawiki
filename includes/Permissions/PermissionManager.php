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
namespace MediaWiki\Permissions;

use Action;
use Exception;
use FatalError;
use Hooks;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Special\SpecialPageFactory;
use MessageSpecifier;
use MWException;
use MWNamespace;
use RequestContext;
use SpecialPage;
use Title;
use User;
use WikiPage;

/**
 * A service class for checking permissions
 * To obtain an instance, use MediaWikiServices::getInstance()->getPermissionManager().
 *
 * @since 1.33
 */
class PermissionManager {

	/** @var string Does cheap permission checks from replica DBs (usable for GUI creation) */
	const RIGOR_QUICK = 'quick';

	/** @var string Does cheap and expensive checks possibly from a replica DB */
	const RIGOR_FULL = 'full';

	/** @var string Does cheap and expensive checks, using the master as needed */
	const RIGOR_SECURE = 'secure';

	/** @var SpecialPageFactory */
	private $specialPageFactory;

	/** @var string[] List of pages names anonymous user may see */
	private $whitelistRead;

	/** @var string[] Whitelists publicly readable titles with regular expressions */
	private $whitelistReadRegexp;

	/** @var bool Require users to confirm email address before they can edit */
	private $emailConfirmToEdit;

	/** @var bool If set to true, blocked users will no longer be allowed to log in */
	private $blockDisablesLogin;

	/**
	 * @param SpecialPageFactory $specialPageFactory
	 * @param string[] $whitelistRead
	 * @param string[] $whitelistReadRegexp
	 * @param bool $emailConfirmToEdit
	 * @param bool $blockDisablesLogin
	 */
	public function __construct(
		SpecialPageFactory $specialPageFactory,
		$whitelistRead,
		$whitelistReadRegexp,
		$emailConfirmToEdit,
		$blockDisablesLogin
	) {
		$this->specialPageFactory = $specialPageFactory;
		$this->whitelistRead = $whitelistRead;
		$this->whitelistReadRegexp = $whitelistReadRegexp;
		$this->emailConfirmToEdit = $emailConfirmToEdit;
		$this->blockDisablesLogin = $blockDisablesLogin;
	}

	/**
	 * Can $user perform $action on a page?
	 *
	 * The method is intended to replace Title::userCan()
	 * The $user parameter need to be superseded by UserIdentity value in future
	 * The $title parameter need to be superseded by PageIdentity value in future
	 *
	 * @see Title::userCan()
	 *
	 * @param string $action
	 * @param User $user
	 * @param LinkTarget $page
	 * @param string $rigor One of PermissionManager::RIGOR_ constants
	 *   - RIGOR_QUICK  : does cheap permission checks from replica DBs (usable for GUI creation)
	 *   - RIGOR_FULL   : does cheap and expensive checks possibly from a replica DB
	 *   - RIGOR_SECURE : does cheap and expensive checks, using the master as needed
	 *
	 * @return bool
	 * @throws Exception
	 */
	public function userCan( $action, User $user, LinkTarget $page, $rigor = self::RIGOR_SECURE ) {
		return !count( $this->getPermissionErrorsInternal( $action, $user, $page, $rigor, true ) );
	}

	/**
	 * Can $user perform $action on a page?
	 *
	 * @todo FIXME: This *does not* check throttles (User::pingLimiter()).
	 *
	 * @param string $action Action that permission needs to be checked for
	 * @param User $user User to check
	 * @param LinkTarget $page
	 * @param string $rigor One of PermissionManager::RIGOR_ constants
	 *   - RIGOR_QUICK  : does cheap permission checks from replica DBs (usable for GUI creation)
	 *   - RIGOR_FULL   : does cheap and expensive checks possibly from a replica DB
	 *   - RIGOR_SECURE : does cheap and expensive checks, using the master as needed
	 * @param array $ignoreErrors Array of Strings Set this to a list of message keys
	 *   whose corresponding errors may be ignored.
	 *
	 * @return array Array of arrays of the arguments to wfMessage to explain permissions problems.
	 * @throws Exception
	 */
	public function getPermissionErrors(
		$action,
		User $user,
		LinkTarget $page,
		$rigor = self::RIGOR_SECURE,
		$ignoreErrors = []
	) {
		$errors = $this->getPermissionErrorsInternal( $action, $user, $page, $rigor );

		// Remove the errors being ignored.
		foreach ( $errors as $index => $error ) {
			$errKey = is_array( $error ) ? $error[0] : $error;

			if ( in_array( $errKey, $ignoreErrors ) ) {
				unset( $errors[$index] );
			}
			if ( $errKey instanceof MessageSpecifier && in_array( $errKey->getKey(), $ignoreErrors ) ) {
				unset( $errors[$index] );
			}
		}

		return $errors;
	}

	/**
	 * Check if user is blocked from editing a particular article
	 *
	 * @param User $user
	 * @param LinkTarget $page Title to check
	 * @param bool $fromReplica Whether to check the replica DB instead of the master
	 *
	 * @return bool
	 * @throws FatalError
	 * @throws MWException
	 */
	public function isBlockedFrom( User $user, LinkTarget $page, $fromReplica = false ) {
		$blocked = $user->isHidden();

		// TODO: remove upon further migration to LinkTarget
		$page = Title::newFromLinkTarget( $page );

		if ( !$blocked ) {
			$block = $user->getBlock( $fromReplica );
			if ( $block ) {
				// Special handling for a user's own talk page. The block is not aware
				// of the user, so this must be done here.
				if ( $page->equals( $user->getTalkPage() ) ) {
					$blocked = $block->appliesToUsertalk( $page );
				} else {
					$blocked = $block->appliesToTitle( $page );
				}
			}
		}

		// only for the purpose of the hook. We really don't need this here.
		$allowUsertalk = $user->isAllowUsertalk();

		Hooks::run( 'UserIsBlockedFrom', [ $user, $page, &$blocked, &$allowUsertalk ] );

		return $blocked;
	}

	/**
	 * Can $user perform $action on a page? This is an internal function,
	 * with multiple levels of checks depending on performance needs; see $rigor below.
	 * It does not check wfReadOnly().
	 *
	 * @param string $action Action that permission needs to be checked for
	 * @param User $user User to check
	 * @param LinkTarget $page
	 * @param string $rigor One of PermissionManager::RIGOR_ constants
	 *   - RIGOR_QUICK  : does cheap permission checks from replica DBs (usable for GUI creation)
	 *   - RIGOR_FULL   : does cheap and expensive checks possibly from a replica DB
	 *   - RIGOR_SECURE : does cheap and expensive checks, using the master as needed
	 * @param bool $short Set this to true to stop after the first permission error.
	 *
	 * @return array Array of arrays of the arguments to wfMessage to explain permissions problems.
	 * @throws Exception
	 */
	private function getPermissionErrorsInternal(
		$action,
		User $user,
		LinkTarget $page,
		$rigor = self::RIGOR_SECURE,
		$short = false
	) {
		if ( !in_array( $rigor, [ self::RIGOR_QUICK, self::RIGOR_FULL, self::RIGOR_SECURE ] ) ) {
			throw new Exception( "Invalid rigor parameter '$rigor'." );
		}

		# Read has special handling
		if ( $action == 'read' ) {
			$checks = [
				'checkPermissionHooks',
				'checkReadPermissions',
				'checkUserBlock', // for wgBlockDisablesLogin
			];
			# Don't call checkSpecialsAndNSPermissions, checkSiteConfigPermissions
			# or checkUserConfigPermissions here as it will lead to duplicate
			# error messages. This is okay to do since anywhere that checks for
			# create will also check for edit, and those checks are called for edit.
		} elseif ( $action == 'create' ) {
			$checks = [
				'checkQuickPermissions',
				'checkPermissionHooks',
				'checkPageRestrictions',
				'checkCascadingSourcesRestrictions',
				'checkActionPermissions',
				'checkUserBlock'
			];
		} else {
			$checks = [
				'checkQuickPermissions',
				'checkPermissionHooks',
				'checkSpecialsAndNSPermissions',
				'checkSiteConfigPermissions',
				'checkUserConfigPermissions',
				'checkPageRestrictions',
				'checkCascadingSourcesRestrictions',
				'checkActionPermissions',
				'checkUserBlock'
			];
		}

		$errors = [];
		foreach ( $checks as $method ) {
			$errors = $this->$method( $action, $user, $errors, $rigor, $short, $page );

			if ( $short && $errors !== [] ) {
				break;
			}
		}

		return $errors;
	}

	/**
	 * Check various permission hooks
	 *
	 * @param string $action The action to check
	 * @param User $user User to check
	 * @param array $errors List of current errors
	 * @param string $rigor One of PermissionManager::RIGOR_ constants
	 *   - RIGOR_QUICK  : does cheap permission checks from replica DBs (usable for GUI creation)
	 *   - RIGOR_FULL   : does cheap and expensive checks possibly from a replica DB
	 *   - RIGOR_SECURE : does cheap and expensive checks, using the master as needed
	 * @param bool $short Short circuit on first error
	 *
	 * @param LinkTarget $page
	 *
	 * @return array List of errors
	 * @throws FatalError
	 * @throws MWException
	 */
	private function checkPermissionHooks(
		$action,
		User $user,
		$errors,
		$rigor,
		$short,
		LinkTarget $page
	) {
		// TODO: remove when LinkTarget usage will expand further
		$page = Title::newFromLinkTarget( $page );
		// Use getUserPermissionsErrors instead
		$result = '';
		if ( !Hooks::run( 'userCan', [ &$page, &$user, $action, &$result ] ) ) {
			return $result ? [] : [ [ 'badaccess-group0' ] ];
		}
		// Check getUserPermissionsErrors hook
		if ( !Hooks::run( 'getUserPermissionsErrors', [ &$page, &$user, $action, &$result ] ) ) {
			$errors = $this->resultToError( $errors, $result );
		}
		// Check getUserPermissionsErrorsExpensive hook
		if (
			$rigor !== self::RIGOR_QUICK
			&& !( $short && count( $errors ) > 0 )
			&& !Hooks::run( 'getUserPermissionsErrorsExpensive', [ &$page, &$user, $action, &$result ] )
		) {
			$errors = $this->resultToError( $errors, $result );
		}

		return $errors;
	}

	/**
	 * Add the resulting error code to the errors array
	 *
	 * @param array $errors List of current errors
	 * @param array $result Result of errors
	 *
	 * @return array List of errors
	 */
	private function resultToError( $errors, $result ) {
		if ( is_array( $result ) && count( $result ) && !is_array( $result[0] ) ) {
			// A single array representing an error
			$errors[] = $result;
		} elseif ( is_array( $result ) && is_array( $result[0] ) ) {
			// A nested array representing multiple errors
			$errors = array_merge( $errors, $result );
		} elseif ( $result !== '' && is_string( $result ) ) {
			// A string representing a message-id
			$errors[] = [ $result ];
		} elseif ( $result instanceof MessageSpecifier ) {
			// A message specifier representing an error
			$errors[] = [ $result ];
		} elseif ( $result === false ) {
			// a generic "We don't want them to do that"
			$errors[] = [ 'badaccess-group0' ];
		}
		return $errors;
	}

	/**
	 * Check that the user is allowed to read this page.
	 *
	 * @param string $action The action to check
	 * @param User $user User to check
	 * @param array $errors List of current errors
	 * @param string $rigor One of PermissionManager::RIGOR_ constants
	 *   - RIGOR_QUICK  : does cheap permission checks from replica DBs (usable for GUI creation)
	 *   - RIGOR_FULL   : does cheap and expensive checks possibly from a replica DB
	 *   - RIGOR_SECURE : does cheap and expensive checks, using the master as needed
	 * @param bool $short Short circuit on first error
	 *
	 * @param LinkTarget $page
	 *
	 * @return array List of errors
	 * @throws FatalError
	 * @throws MWException
	 */
	private function checkReadPermissions(
		$action,
		User $user,
		$errors,
		$rigor,
		$short,
		LinkTarget $page
	) {
		// TODO: remove when LinkTarget usage will expand further
		$page = Title::newFromLinkTarget( $page );

		$whitelisted = false;
		if ( User::isEveryoneAllowed( 'read' ) ) {
			# Shortcut for public wikis, allows skipping quite a bit of code
			$whitelisted = true;
		} elseif ( $user->isAllowed( 'read' ) ) {
			# If the user is allowed to read pages, he is allowed to read all pages
			$whitelisted = true;
		} elseif ( $this->isSameSpecialPage( 'Userlogin', $page )
				   || $this->isSameSpecialPage( 'PasswordReset', $page )
				   || $this->isSameSpecialPage( 'Userlogout', $page )
		) {
			# Always grant access to the login page.
			# Even anons need to be able to log in.
			$whitelisted = true;
		} elseif ( is_array( $this->whitelistRead ) && count( $this->whitelistRead ) ) {
			# Time to check the whitelist
			# Only do these checks is there's something to check against
			$name = $page->getPrefixedText();
			$dbName = $page->getPrefixedDBkey();

			// Check for explicit whitelisting with and without underscores
			if ( in_array( $name, $this->whitelistRead, true )
				 || in_array( $dbName, $this->whitelistRead, true ) ) {
				$whitelisted = true;
			} elseif ( $page->getNamespace() == NS_MAIN ) {
				# Old settings might have the title prefixed with
				# a colon for main-namespace pages
				if ( in_array( ':' . $name, $this->whitelistRead ) ) {
					$whitelisted = true;
				}
			} elseif ( $page->isSpecialPage() ) {
				# If it's a special page, ditch the subpage bit and check again
				$name = $page->getDBkey();
				list( $name, /* $subpage */ ) =
					$this->specialPageFactory->resolveAlias( $name );
				if ( $name ) {
					$pure = SpecialPage::getTitleFor( $name )->getPrefixedText();
					if ( in_array( $pure, $this->whitelistRead, true ) ) {
						$whitelisted = true;
					}
				}
			}
		}

		if ( !$whitelisted && is_array( $this->whitelistReadRegexp )
			 && !empty( $this->whitelistReadRegexp ) ) {
			$name = $page->getPrefixedText();
			// Check for regex whitelisting
			foreach ( $this->whitelistReadRegexp as $listItem ) {
				if ( preg_match( $listItem, $name ) ) {
					$whitelisted = true;
					break;
				}
			}
		}

		if ( !$whitelisted ) {
			# If the title is not whitelisted, give extensions a chance to do so...
			Hooks::run( 'TitleReadWhitelist', [ $page, $user, &$whitelisted ] );
			if ( !$whitelisted ) {
				$errors[] = $this->missingPermissionError( $action, $short );
			}
		}

		return $errors;
	}

	/**
	 * Get a description array when the user doesn't have the right to perform
	 * $action (i.e. when User::isAllowed() returns false)
	 *
	 * @param string $action The action to check
	 * @param bool $short Short circuit on first error
	 * @return array Array containing an error message key and any parameters
	 */
	private function missingPermissionError( $action, $short ) {
		// We avoid expensive display logic for quickUserCan's and such
		if ( $short ) {
			return [ 'badaccess-group0' ];
		}

		// TODO: it would be a good idea to replace the method below with something else like
		//  maybe callback injection
		return User::newFatalPermissionDeniedStatus( $action )->getErrorsArray()[0];
	}

	/**
	 * Returns true if this title resolves to the named special page
	 *
	 * @param string $name The special page name
	 * @param LinkTarget $page
	 *
	 * @return bool
	 */
	private function isSameSpecialPage( $name, LinkTarget $page ) {
		if ( $page->getNamespace() == NS_SPECIAL ) {
			list( $thisName, /* $subpage */ ) =
				$this->specialPageFactory->resolveAlias( $page->getDBkey() );
			if ( $name == $thisName ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Check that the user isn't blocked from editing.
	 *
	 * @param string $action The action to check
	 * @param User $user User to check
	 * @param array $errors List of current errors
	 * @param string $rigor One of PermissionManager::RIGOR_ constants
	 *   - RIGOR_QUICK  : does cheap permission checks from replica DBs (usable for GUI creation)
	 *   - RIGOR_FULL   : does cheap and expensive checks possibly from a replica DB
	 *   - RIGOR_SECURE : does cheap and expensive checks, using the master as needed
	 * @param bool $short Short circuit on first error
	 *
	 * @param LinkTarget $page
	 *
	 * @return array List of errors
	 * @throws MWException
	 */
	private function checkUserBlock(
		$action,
		User $user,
		$errors,
		$rigor,
		$short,
		LinkTarget $page
	) {
		// Account creation blocks handled at userlogin.
		// Unblocking handled in SpecialUnblock
		if ( $rigor === self::RIGOR_QUICK || in_array( $action, [ 'createaccount', 'unblock' ] ) ) {
			return $errors;
		}

		// Optimize for a very common case
		if ( $action === 'read' && !$this->blockDisablesLogin ) {
			return $errors;
		}

		if ( $this->emailConfirmToEdit
			 && !$user->isEmailConfirmed()
			 && $action === 'edit'
		) {
			$errors[] = [ 'confirmedittext' ];
		}

		$useReplica = ( $rigor !== self::RIGOR_SECURE );
		$block = $user->getBlock( $useReplica );

		// If the user does not have a block, or the block they do have explicitly
		// allows the action (like "read" or "upload").
		if ( !$block || $block->appliesToRight( $action ) === false ) {
			return $errors;
		}

		// Determine if the user is blocked from this action on this page.
		// What gets passed into this method is a user right, not an action name.
		// There is no way to instantiate an action by restriction. However, this
		// will get the action where the restriction is the same. This may result
		// in actions being blocked that shouldn't be.
		$actionObj = null;
		if ( Action::exists( $action ) ) {
			// TODO: this drags a ton of dependencies in, would be good to avoid WikiPage
			//  instantiation and decouple it creating an ActionPermissionChecker interface
			$wikiPage = WikiPage::factory( Title::newFromLinkTarget( $page, 'clone' ) );
			// Creating an action will perform several database queries to ensure that
			// the action has not been overridden by the content type.
			// FIXME: avoid use of RequestContext since it drags in User and Title dependencies
			//  probably we may use fake context object since it's unlikely that Action uses it
			//  anyway. It would be nice if we could avoid instantiating the Action at all.
			$actionObj = Action::factory( $action, $wikiPage, RequestContext::getMain() );
			// Ensure that the retrieved action matches the restriction.
			if ( $actionObj && $actionObj->getRestriction() !== $action ) {
				$actionObj = null;
			}
		}

		// If no action object is returned, assume that the action requires unblock
		// which is the default.
		if ( !$actionObj || $actionObj->requiresUnblock() ) {
			if ( $this->isBlockedFrom( $user, $page, $useReplica ) ) {
				// @todo FIXME: Pass the relevant context into this function.
				$errors[] = $block->getPermissionsError( RequestContext::getMain() );
			}
		}

		return $errors;
	}

	/**
	 * Permissions checks that fail most often, and which are easiest to test.
	 *
	 * @param string $action The action to check
	 * @param User $user User to check
	 * @param array $errors List of current errors
	 * @param string $rigor One of PermissionManager::RIGOR_ constants
	 *   - RIGOR_QUICK  : does cheap permission checks from replica DBs (usable for GUI creation)
	 *   - RIGOR_FULL   : does cheap and expensive checks possibly from a replica DB
	 *   - RIGOR_SECURE : does cheap and expensive checks, using the master as needed
	 * @param bool $short Short circuit on first error
	 *
	 * @param LinkTarget $page
	 *
	 * @return array List of errors
	 * @throws FatalError
	 * @throws MWException
	 */
	private function checkQuickPermissions(
		$action,
		User $user,
		$errors,
		$rigor,
		$short,
		LinkTarget $page
	) {
		// TODO: remove when LinkTarget usage will expand further
		$page = Title::newFromLinkTarget( $page );

		if ( !Hooks::run( 'TitleQuickPermissions',
			[ $page, $user, $action, &$errors, ( $rigor !== self::RIGOR_QUICK ), $short ] )
		) {
			return $errors;
		}

		$isSubPage = MWNamespace::hasSubpages( $page->getNamespace() ) ?
			strpos( $page->getText(), '/' ) !== false : false;

		if ( $action == 'create' ) {
			if (
				( MWNamespace::isTalk( $page->getNamespace() ) && !$user->isAllowed( 'createtalk' ) ) ||
				( !MWNamespace::isTalk( $page->getNamespace() ) && !$user->isAllowed( 'createpage' ) )
			) {
				$errors[] = $user->isAnon() ? [ 'nocreatetext' ] : [ 'nocreate-loggedin' ];
			}
		} elseif ( $action == 'move' ) {
			if ( !$user->isAllowed( 'move-rootuserpages' )
				 && $page->getNamespace() == NS_USER && !$isSubPage ) {
				// Show user page-specific message only if the user can move other pages
				$errors[] = [ 'cant-move-user-page' ];
			}

			// Check if user is allowed to move files if it's a file
			if ( $page->getNamespace() == NS_FILE && !$user->isAllowed( 'movefile' ) ) {
				$errors[] = [ 'movenotallowedfile' ];
			}

			// Check if user is allowed to move category pages if it's a category page
			if ( $page->getNamespace() == NS_CATEGORY && !$user->isAllowed( 'move-categorypages' ) ) {
				$errors[] = [ 'cant-move-category-page' ];
			}

			if ( !$user->isAllowed( 'move' ) ) {
				// User can't move anything
				$userCanMove = User::groupHasPermission( 'user', 'move' );
				$autoconfirmedCanMove = User::groupHasPermission( 'autoconfirmed', 'move' );
				if ( $user->isAnon() && ( $userCanMove || $autoconfirmedCanMove ) ) {
					// custom message if logged-in users without any special rights can move
					$errors[] = [ 'movenologintext' ];
				} else {
					$errors[] = [ 'movenotallowed' ];
				}
			}
		} elseif ( $action == 'move-target' ) {
			if ( !$user->isAllowed( 'move' ) ) {
				// User can't move anything
				$errors[] = [ 'movenotallowed' ];
			} elseif ( !$user->isAllowed( 'move-rootuserpages' )
					   && $page->getNamespace() == NS_USER && !$isSubPage ) {
				// Show user page-specific message only if the user can move other pages
				$errors[] = [ 'cant-move-to-user-page' ];
			} elseif ( !$user->isAllowed( 'move-categorypages' )
					   && $page->getNamespace() == NS_CATEGORY ) {
				// Show category page-specific message only if the user can move other pages
				$errors[] = [ 'cant-move-to-category-page' ];
			}
		} elseif ( !$user->isAllowed( $action ) ) {
			$errors[] = $this->missingPermissionError( $action, $short );
		}

		return $errors;
	}

	/**
	 * Check against page_restrictions table requirements on this
	 * page. The user must possess all required rights for this
	 * action.
	 *
	 * @param string $action The action to check
	 * @param User $user User to check
	 * @param array $errors List of current errors
	 * @param string $rigor One of PermissionManager::RIGOR_ constants
	 *   - RIGOR_QUICK  : does cheap permission checks from replica DBs (usable for GUI creation)
	 *   - RIGOR_FULL   : does cheap and expensive checks possibly from a replica DB
	 *   - RIGOR_SECURE : does cheap and expensive checks, using the master as needed
	 * @param bool $short Short circuit on first error
	 *
	 * @param LinkTarget $page
	 *
	 * @return array List of errors
	 */
	private function checkPageRestrictions(
		$action,
		User $user,
		$errors,
		$rigor,
		$short,
		LinkTarget $page
	) {
		// TODO: remove & rework upon further use of LinkTarget
		$page = Title::newFromLinkTarget( $page );
		foreach ( $page->getRestrictions( $action ) as $right ) {
			// Backwards compatibility, rewrite sysop -> editprotected
			if ( $right == 'sysop' ) {
				$right = 'editprotected';
			}
			// Backwards compatibility, rewrite autoconfirmed -> editsemiprotected
			if ( $right == 'autoconfirmed' ) {
				$right = 'editsemiprotected';
			}
			if ( $right == '' ) {
				continue;
			}
			if ( !$user->isAllowed( $right ) ) {
				$errors[] = [ 'protectedpagetext', $right, $action ];
			} elseif ( $page->areRestrictionsCascading() && !$user->isAllowed( 'protect' ) ) {
				$errors[] = [ 'protectedpagetext', 'protect', $action ];
			}
		}

		return $errors;
	}

	/**
	 * Check restrictions on cascading pages.
	 *
	 * @param string $action The action to check
	 * @param User $user User to check
	 * @param array $errors List of current errors
	 * @param string $rigor One of PermissionManager::RIGOR_ constants
	 *   - RIGOR_QUICK  : does cheap permission checks from replica DBs (usable for GUI creation)
	 *   - RIGOR_FULL   : does cheap and expensive checks possibly from a replica DB
	 *   - RIGOR_SECURE : does cheap and expensive checks, using the master as needed
	 * @param bool $short Short circuit on first error
	 *
	 * @param LinkTarget $page
	 *
	 * @return array List of errors
	 */
	private function checkCascadingSourcesRestrictions(
		$action,
		User $user,
		$errors,
		$rigor,
		$short,
		LinkTarget $page
	) {
		// TODO: remove & rework upon further use of LinkTarget
		$page = Title::newFromLinkTarget( $page );
		if ( $rigor !== self::RIGOR_QUICK && !$page->isUserConfigPage() ) {
			# We /could/ use the protection level on the source page, but it's
			# fairly ugly as we have to establish a precedence hierarchy for pages
			# included by multiple cascade-protected pages. So just restrict
			# it to people with 'protect' permission, as they could remove the
			# protection anyway.
			list( $cascadingSources, $restrictions ) = $page->getCascadeProtectionSources();
			# Cascading protection depends on more than this page...
			# Several cascading protected pages may include this page...
			# Check each cascading level
			# This is only for protection restrictions, not for all actions
			if ( isset( $restrictions[$action] ) ) {
				foreach ( $restrictions[$action] as $right ) {
					// Backwards compatibility, rewrite sysop -> editprotected
					if ( $right == 'sysop' ) {
						$right = 'editprotected';
					}
					// Backwards compatibility, rewrite autoconfirmed -> editsemiprotected
					if ( $right == 'autoconfirmed' ) {
						$right = 'editsemiprotected';
					}
					if ( $right != '' && !$user->isAllowedAll( 'protect', $right ) ) {
						$wikiPages = '';
						foreach ( $cascadingSources as $wikiPage ) {
							$wikiPages .= '* [[:' . $wikiPage->getPrefixedText() . "]]\n";
						}
						$errors[] = [ 'cascadeprotected', count( $cascadingSources ), $wikiPages, $action ];
					}
				}
			}
		}

		return $errors;
	}

	/**
	 * Check action permissions not already checked in checkQuickPermissions
	 *
	 * @param string $action The action to check
	 * @param User $user User to check
	 * @param array $errors List of current errors
	 * @param string $rigor One of PermissionManager::RIGOR_ constants
	 *   - RIGOR_QUICK  : does cheap permission checks from replica DBs (usable for GUI creation)
	 *   - RIGOR_FULL   : does cheap and expensive checks possibly from a replica DB
	 *   - RIGOR_SECURE : does cheap and expensive checks, using the master as needed
	 * @param bool $short Short circuit on first error
	 *
	 * @param LinkTarget $page
	 *
	 * @return array List of errors
	 * @throws Exception
	 */
	private function checkActionPermissions(
		$action,
		User $user,
		$errors,
		$rigor,
		$short,
		LinkTarget $page
	) {
		global $wgDeleteRevisionsLimit, $wgLang;

		// TODO: remove & rework upon further use of LinkTarget
		$page = Title::newFromLinkTarget( $page );

		if ( $action == 'protect' ) {
			if ( count( $this->getPermissionErrorsInternal( 'edit', $user, $page, $rigor, true ) ) ) {
				// If they can't edit, they shouldn't protect.
				$errors[] = [ 'protect-cantedit' ];
			}
		} elseif ( $action == 'create' ) {
			$title_protection = $page->getTitleProtection();
			if ( $title_protection ) {
				if ( $title_protection['permission'] == ''
					 || !$user->isAllowed( $title_protection['permission'] )
				) {
					$errors[] = [
						'titleprotected',
						// TODO: get rid of the User dependency
						User::whoIs( $title_protection['user'] ),
						$title_protection['reason']
					];
				}
			}
		} elseif ( $action == 'move' ) {
			// Check for immobile pages
			if ( !MWNamespace::isMovable( $page->getNamespace() ) ) {
				// Specific message for this case
				$errors[] = [ 'immobile-source-namespace', $page->getNsText() ];
			} elseif ( !$page->isMovable() ) {
				// Less specific message for rarer cases
				$errors[] = [ 'immobile-source-page' ];
			}
		} elseif ( $action == 'move-target' ) {
			if ( !MWNamespace::isMovable( $page->getNamespace() ) ) {
				$errors[] = [ 'immobile-target-namespace', $page->getNsText() ];
			} elseif ( !$page->isMovable() ) {
				$errors[] = [ 'immobile-target-page' ];
			}
		} elseif ( $action == 'delete' ) {
			$tempErrors = $this->checkPageRestrictions( 'edit', $user, [], $rigor, true, $page );
			if ( !$tempErrors ) {
				$tempErrors = $this->checkCascadingSourcesRestrictions( 'edit',
					$user, $tempErrors, $rigor, true, $page );
			}
			if ( $tempErrors ) {
				// If protection keeps them from editing, they shouldn't be able to delete.
				$errors[] = [ 'deleteprotected' ];
			}
			if ( $rigor !== self::RIGOR_QUICK && $wgDeleteRevisionsLimit
				 && !$this->userCan( 'bigdelete', $user, $page ) && $page->isBigDeletion()
			) {
				$errors[] = [ 'delete-toobig', $wgLang->formatNum( $wgDeleteRevisionsLimit ) ];
			}
		} elseif ( $action === 'undelete' ) {
			if ( count( $this->getPermissionErrorsInternal( 'edit', $user, $page, $rigor, true ) ) ) {
				// Undeleting implies editing
				$errors[] = [ 'undelete-cantedit' ];
			}
			if ( !$page->exists()
				 && count( $this->getPermissionErrorsInternal( 'create', $user, $page, $rigor, true ) )
			) {
				// Undeleting where nothing currently exists implies creating
				$errors[] = [ 'undelete-cantcreate' ];
			}
		}
		return $errors;
	}

	/**
	 * Check permissions on special pages & namespaces
	 *
	 * @param string $action The action to check
	 * @param User $user User to check
	 * @param array $errors List of current errors
	 * @param string $rigor One of PermissionManager::RIGOR_ constants
	 *   - RIGOR_QUICK  : does cheap permission checks from replica DBs (usable for GUI creation)
	 *   - RIGOR_FULL   : does cheap and expensive checks possibly from a replica DB
	 *   - RIGOR_SECURE : does cheap and expensive checks, using the master as needed
	 * @param bool $short Short circuit on first error
	 *
	 * @param LinkTarget $page
	 *
	 * @return array List of errors
	 */
	private function checkSpecialsAndNSPermissions(
		$action,
		User $user,
		$errors,
		$rigor,
		$short,
		LinkTarget $page
	) {
		// TODO: remove & rework upon further use of LinkTarget
		$page = Title::newFromLinkTarget( $page );

		# Only 'createaccount' can be performed on special pages,
		# which don't actually exist in the DB.
		if ( $page->getNamespace() == NS_SPECIAL && $action !== 'createaccount' ) {
			$errors[] = [ 'ns-specialprotected' ];
		}

		# Check $wgNamespaceProtection for restricted namespaces
		if ( $page->isNamespaceProtected( $user ) ) {
			$ns = $page->getNamespace() == NS_MAIN ?
				wfMessage( 'nstab-main' )->text() : $page->getNsText();
			$errors[] = $page->getNamespace() == NS_MEDIAWIKI ?
				[ 'protectedinterface', $action ] : [ 'namespaceprotected', $ns, $action ];
		}

		return $errors;
	}

	/**
	 * Check sitewide CSS/JSON/JS permissions
	 *
	 * @param string $action The action to check
	 * @param User $user User to check
	 * @param array $errors List of current errors
	 * @param string $rigor One of PermissionManager::RIGOR_ constants
	 *   - RIGOR_QUICK  : does cheap permission checks from replica DBs (usable for GUI creation)
	 *   - RIGOR_FULL   : does cheap and expensive checks possibly from a replica DB
	 *   - RIGOR_SECURE : does cheap and expensive checks, using the master as needed
	 * @param bool $short Short circuit on first error
	 *
	 * @param LinkTarget $page
	 *
	 * @return array List of errors
	 */
	private function checkSiteConfigPermissions(
		$action,
		User $user,
		$errors,
		$rigor,
		$short,
		LinkTarget $page
	) {
		// TODO: remove & rework upon further use of LinkTarget
		$page = Title::newFromLinkTarget( $page );

		if ( $action != 'patrol' ) {
			$error = null;
			// Sitewide CSS/JSON/JS changes, like all NS_MEDIAWIKI changes, also require the
			// editinterface right. That's implemented as a restriction so no check needed here.
			if ( $page->isSiteCssConfigPage() && !$user->isAllowed( 'editsitecss' ) ) {
				$error = [ 'sitecssprotected', $action ];
			} elseif ( $page->isSiteJsonConfigPage() && !$user->isAllowed( 'editsitejson' ) ) {
				$error = [ 'sitejsonprotected', $action ];
			} elseif ( $page->isSiteJsConfigPage() && !$user->isAllowed( 'editsitejs' ) ) {
				$error = [ 'sitejsprotected', $action ];
			} elseif ( $page->isRawHtmlMessage() ) {
				// Raw HTML can be used to deploy CSS or JS so require rights for both.
				if ( !$user->isAllowed( 'editsitejs' ) ) {
					$error = [ 'sitejsprotected', $action ];
				} elseif ( !$user->isAllowed( 'editsitecss' ) ) {
					$error = [ 'sitecssprotected', $action ];
				}
			}

			if ( $error ) {
				if ( $user->isAllowed( 'editinterface' ) ) {
					// Most users / site admins will probably find out about the new, more restrictive
					// permissions by failing to edit something. Give them more info.
					// TODO remove this a few release cycles after 1.32
					$error = [ 'interfaceadmin-info', wfMessage( $error[0], $error[1] ) ];
				}
				$errors[] = $error;
			}
		}

		return $errors;
	}

	/**
	 * Check CSS/JSON/JS sub-page permissions
	 *
	 * @param string $action The action to check
	 * @param User $user User to check
	 * @param array $errors List of current errors
	 * @param string $rigor One of PermissionManager::RIGOR_ constants
	 *   - RIGOR_QUICK  : does cheap permission checks from replica DBs (usable for GUI creation)
	 *   - RIGOR_FULL   : does cheap and expensive checks possibly from a replica DB
	 *   - RIGOR_SECURE : does cheap and expensive checks, using the master as needed
	 * @param bool $short Short circuit on first error
	 *
	 * @param LinkTarget $page
	 *
	 * @return array List of errors
	 */
	private function checkUserConfigPermissions(
		$action,
		User $user,
		$errors,
		$rigor,
		$short,
		LinkTarget $page
	) {
		// TODO: remove & rework upon further use of LinkTarget
		$page = Title::newFromLinkTarget( $page );

		# Protect css/json/js subpages of user pages
		# XXX: this might be better using restrictions

		if ( $action === 'patrol' ) {
			return $errors;
		}

		if ( preg_match( '/^' . preg_quote( $user->getName(), '/' ) . '\//', $page->getText() ) ) {
			// Users need editmyuser* to edit their own CSS/JSON/JS subpages.
			if (
				$page->isUserCssConfigPage()
				&& !$user->isAllowedAny( 'editmyusercss', 'editusercss' )
			) {
				$errors[] = [ 'mycustomcssprotected', $action ];
			} elseif (
				$page->isUserJsonConfigPage()
				&& !$user->isAllowedAny( 'editmyuserjson', 'edituserjson' )
			) {
				$errors[] = [ 'mycustomjsonprotected', $action ];
			} elseif (
				$page->isUserJsConfigPage()
				&& !$user->isAllowedAny( 'editmyuserjs', 'edituserjs' )
			) {
				$errors[] = [ 'mycustomjsprotected', $action ];
			}
		} else {
			// Users need editmyuser* to edit their own CSS/JSON/JS subpages, except for
			// deletion/suppression which cannot be used for attacks and we want to avoid the
			// situation where an unprivileged user can post abusive content on their subpages
			// and only very highly privileged users could remove it.
			if ( !in_array( $action, [ 'delete', 'deleterevision', 'suppressrevision' ], true ) ) {
				if (
					$page->isUserCssConfigPage()
					&& !$user->isAllowed( 'editusercss' )
				) {
					$errors[] = [ 'customcssprotected', $action ];
				} elseif (
					$page->isUserJsonConfigPage()
					&& !$user->isAllowed( 'edituserjson' )
				) {
					$errors[] = [ 'customjsonprotected', $action ];
				} elseif (
					$page->isUserJsConfigPage()
					&& !$user->isAllowed( 'edituserjs' )
				) {
					$errors[] = [ 'customjsprotected', $action ];
				}
			}
		}

		return $errors;
	}

}
