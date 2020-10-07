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
use Article;
use Exception;
use MediaWiki\Block\BlockErrorFormatter;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Revision\RevisionLookup;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Session\SessionManager;
use MediaWiki\SpecialPage\SpecialPageFactory;
use MediaWiki\User\UserIdentity;
use MessageSpecifier;
use NamespaceInfo;
use RequestContext;
use SpecialPage;
use Title;
use User;
use Wikimedia\ScopedCallback;

/**
 * A service class for checking permissions
 * To obtain an instance, use MediaWikiServices::getInstance()->getPermissionManager().
 *
 * @since 1.33
 */
class PermissionManager {

	/** @var string Does cheap permission checks from replica DBs (usable for GUI creation) */
	public const RIGOR_QUICK = 'quick';

	/** @var string Does cheap and expensive checks possibly from a replica DB */
	public const RIGOR_FULL = 'full';

	/** @var string Does cheap and expensive checks, using the master as needed */
	public const RIGOR_SECURE = 'secure';

	/**
	 * @since 1.34
	 * @var array
	 */
	public const CONSTRUCTOR_OPTIONS = [
		'WhitelistRead',
		'WhitelistReadRegexp',
		'EmailConfirmToEdit',
		'BlockDisablesLogin',
		'GroupPermissions',
		'RevokePermissions',
		'AvailableRights',
		'NamespaceProtection',
		'RestrictionLevels'
	];

	/** @var ServiceOptions */
	private $options;

	/** @var SpecialPageFactory */
	private $specialPageFactory;

	/** @var RevisionLookup */
	private $revisionLookup;

	/** @var NamespaceInfo */
	private $nsInfo;

	/** @var string[]|null Cached results of getAllRights() */
	private $allRights;

	/** @var BlockErrorFormatter */
	private $blockErrorFormatter;

	/** @var HookRunner */
	private $hookRunner;

	/** @var string[][] Cached user rights */
	private $usersRights = null;

	/**
	 * Temporary user rights, valid for the current request only.
	 * @var string[][][] userid => override group => rights
	 */
	private $temporaryUserRights = [];

	/** @var bool[] Cached rights for isEveryoneAllowed, [ right => allowed ] */
	private $cachedRights = [];

	/**
	 * Array of Strings Core rights.
	 * Each of these should have a corresponding message of the form
	 * "right-$right".
	 * @showinitializer
	 */
	private $coreRights = [
		'apihighlimits',
		'applychangetags',
		'autoconfirmed',
		'autocreateaccount',
		'autopatrol',
		'bigdelete',
		'block',
		'blockemail',
		'bot',
		'browsearchive',
		'changetags',
		'createaccount',
		'createpage',
		'createtalk',
		'delete',
		'deletechangetags',
		'deletedhistory',
		'deletedtext',
		'deletelogentry',
		'deleterevision',
		'edit',
		'editcontentmodel',
		'editinterface',
		'editprotected',
		'editmyoptions',
		'editmyprivateinfo',
		'editmyusercss',
		'editmyuserjson',
		'editmyuserjs',
		'editmyuserjsredirect',
		'editmywatchlist',
		'editsemiprotected',
		'editsitecss',
		'editsitejson',
		'editsitejs',
		'editusercss',
		'edituserjson',
		'edituserjs',
		'hideuser',
		'import',
		'importupload',
		'ipblock-exempt',
		'managechangetags',
		'markbotedits',
		'mergehistory',
		'minoredit',
		'move',
		'movefile',
		'move-categorypages',
		'move-rootuserpages',
		'move-subpages',
		'nominornewtalk',
		'noratelimit',
		'override-export-depth',
		'pagelang',
		'patrol',
		'patrolmarks',
		'protect',
		'purge',
		'read',
		'reupload',
		'reupload-own',
		'reupload-shared',
		'rollback',
		'sendemail',
		'siteadmin',
		'suppressionlog',
		'suppressredirect',
		'suppressrevision',
		'unblockself',
		'undelete',
		'unwatchedpages',
		'upload',
		'upload_by_url',
		'userrights',
		'userrights-interwiki',
		'viewmyprivateinfo',
		'viewmywatchlist',
		'viewsuppressed',
		'writeapi',
	];

	/**
	 * @param ServiceOptions $options
	 * @param SpecialPageFactory $specialPageFactory
	 * @param RevisionLookup $revisionLookup
	 * @param NamespaceInfo $nsInfo
	 * @param BlockErrorFormatter $blockErrorFormatter
	 * @param HookContainer $hookContainer
	 */
	public function __construct(
		ServiceOptions $options,
		SpecialPageFactory $specialPageFactory,
		RevisionLookup $revisionLookup,
		NamespaceInfo $nsInfo,
		BlockErrorFormatter $blockErrorFormatter,
		HookContainer $hookContainer
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->options = $options;
		$this->specialPageFactory = $specialPageFactory;
		$this->revisionLookup = $revisionLookup;
		$this->nsInfo = $nsInfo;
		$this->blockErrorFormatter = $blockErrorFormatter;
		$this->hookRunner = new HookRunner( $hookContainer );
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
	 */
	public function userCan( $action, User $user, LinkTarget $page, $rigor = self::RIGOR_SECURE ) {
		return !count( $this->getPermissionErrorsInternal( $action, $user, $page, $rigor, true ) );
	}

	/**
	 * A convenience method for calling PermissionManager::userCan
	 * with PermissionManager::RIGOR_QUICK
	 *
	 * Suitable for use for nonessential UI controls in common cases, but
	 * _not_ for functional access control.
	 * May provide false positives, but should never provide a false negative.
	 *
	 * @see PermissionManager::userCan()
	 *
	 * @param string $action
	 * @param User $user
	 * @param LinkTarget $page
	 * @return bool
	 */
	public function quickUserCan( $action, User $user, LinkTarget $page ) {
		return $this->userCan( $action, $user, $page, self::RIGOR_QUICK );
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
	 * @return array[] Array of arrays of the arguments to wfMessage to explain permissions problems.
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
	 * Check if user is blocked from editing a particular article. If the user does not
	 * have a block, this will return false.
	 *
	 * @param User $user
	 * @param LinkTarget $page Title to check
	 * @param bool $fromReplica Whether to check the replica DB instead of the master
	 *
	 * @return bool
	 */
	public function isBlockedFrom( User $user, LinkTarget $page, $fromReplica = false ) {
		$block = $user->getBlock( $fromReplica );
		if ( !$block ) {
			return false;
		}

		// TODO: remove upon further migration to LinkTarget
		$title = Title::newFromLinkTarget( $page );

		$blocked = $user->isHidden();
		if ( !$blocked ) {
			// Special handling for a user's own talk page. The block is not aware
			// of the user, so this must be done here.
			if ( $title->equals( $user->getTalkPage() ) ) {
				$blocked = $block->appliesToUsertalk( $title );
			} else {
				$blocked = $block->appliesToTitle( $title );
			}
		}

		// only for the purpose of the hook. We really don't need this here.
		$allowUsertalk = $user->isAllowUsertalk();

		// Allow extensions to let a blocked user access a particular page
		$this->hookRunner->onUserIsBlockedFrom( $user, $title, $blocked, $allowUsertalk );

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
	 * @return array[] Array of arrays of the arguments to wfMessage to explain permissions problems.
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
		$title = Title::newFromLinkTarget( $page );
		// Use getUserPermissionsErrors instead
		$result = '';
		if ( !$this->hookRunner->onUserCan( $title, $user, $action, $result ) ) {
			return $result ? [] : [ [ 'badaccess-group0' ] ];
		}
		// Check getUserPermissionsErrors hook
		if ( !$this->hookRunner->onGetUserPermissionsErrors( $title, $user, $action, $result ) ) {
			$errors = $this->resultToError( $errors, $result );
		}
		// Check getUserPermissionsErrorsExpensive hook
		if (
			$rigor !== self::RIGOR_QUICK
			&& !( $short && count( $errors ) > 0 )
			&& !$this->hookRunner->onGetUserPermissionsErrorsExpensive(
				$title, $user, $action, $result )
		) {
			$errors = $this->resultToError( $errors, $result );
		}

		return $errors;
	}

	/**
	 * Add the resulting error code to the errors array
	 *
	 * @param array $errors List of current errors
	 * @param array|string|MessageSpecifier|false $result Result of errors
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
		$title = Title::newFromLinkTarget( $page );

		$whiteListRead = $this->options->get( 'WhitelistRead' );
		$whitelisted = false;
		if ( $this->isEveryoneAllowed( 'read' ) ) {
			# Shortcut for public wikis, allows skipping quite a bit of code
			$whitelisted = true;
		} elseif ( $this->userHasRight( $user, 'read' ) ) {
			# If the user is allowed to read pages, he is allowed to read all pages
			$whitelisted = true;
		} elseif ( $this->isSameSpecialPage( 'Userlogin', $title )
			|| $this->isSameSpecialPage( 'PasswordReset', $title )
			|| $this->isSameSpecialPage( 'Userlogout', $title )
		) {
			# Always grant access to the login page.
			# Even anons need to be able to log in.
			$whitelisted = true;
		} elseif ( is_array( $whiteListRead ) && count( $whiteListRead ) ) {
			# Time to check the whitelist
			# Only do these checks is there's something to check against
			$name = $title->getPrefixedText();
			$dbName = $title->getPrefixedDBkey();

			// Check for explicit whitelisting with and without underscores
			if ( in_array( $name, $whiteListRead, true )
				 || in_array( $dbName, $whiteListRead, true ) ) {
				$whitelisted = true;
			} elseif ( $title->getNamespace() == NS_MAIN ) {
				# Old settings might have the title prefixed with
				# a colon for main-namespace pages
				if ( in_array( ':' . $name, $whiteListRead ) ) {
					$whitelisted = true;
				}
			} elseif ( $title->isSpecialPage() ) {
				# If it's a special page, ditch the subpage bit and check again
				$name = $title->getDBkey();
				list( $name, /* $subpage */ ) =
					$this->specialPageFactory->resolveAlias( $name );
				if ( $name ) {
					$pure = SpecialPage::getTitleFor( $name )->getPrefixedText();
					if ( in_array( $pure, $whiteListRead, true ) ) {
						$whitelisted = true;
					}
				}
			}
		}

		$whitelistReadRegexp = $this->options->get( 'WhitelistReadRegexp' );
		if ( !$whitelisted && is_array( $whitelistReadRegexp )
			 && !empty( $whitelistReadRegexp ) ) {
			$name = $title->getPrefixedText();
			// Check for regex whitelisting
			foreach ( $whitelistReadRegexp as $listItem ) {
				if ( preg_match( $listItem, $name ) ) {
					$whitelisted = true;
					break;
				}
			}
		}

		if ( !$whitelisted ) {
			# If the title is not whitelisted, give extensions a chance to do so...
			$this->hookRunner->onTitleReadWhitelist( $title, $user, $whitelisted );
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
		if ( $action === 'read' && !$this->options->get( 'BlockDisablesLogin' ) ) {
			return $errors;
		}

		if ( $this->options->get( 'EmailConfirmToEdit' )
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
			// TODO: this drags a ton of dependencies in, would be good to avoid Article
			//  instantiation and decouple it creating an ActionPermissionChecker interface
			// Creating an action will perform several database queries to ensure that
			// the action has not been overridden by the content type.
			// FIXME: avoid use of RequestContext since it drags in User and Title dependencies
			//  probably we may use fake context object since it's unlikely that Action uses it
			//  anyway. It would be nice if we could avoid instantiating the Action at all.
			$title = Title::newFromLinkTarget( $page, 'clone' );
			$context = RequestContext::getMain();
			$actionObj = Action::factory(
				$action,
				Article::newFromTitle( $title, $context ),
				$context
			);
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
				$context = RequestContext::getMain();
				$message = $this->blockErrorFormatter->getMessage(
					$block,
					$context->getUser(),
					$context->getLanguage(),
					$context->getRequest()->getIP()
				);
				$errors[] = array_merge( [ $message->getKey() ], $message->getParams() );
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
		$title = Title::newFromLinkTarget( $page );

		if ( !$this->hookRunner->onTitleQuickPermissions( $title, $user, $action,
			$errors, $rigor !== self::RIGOR_QUICK, $short )
		) {
			return $errors;
		}

		$isSubPage = $this->nsInfo->hasSubpages( $title->getNamespace() ) ?
			strpos( $title->getText(), '/' ) !== false : false;

		if ( $action == 'create' ) {
			if (
				( $this->nsInfo->isTalk( $title->getNamespace() ) &&
					!$this->userHasRight( $user, 'createtalk' ) ) ||
				( !$this->nsInfo->isTalk( $title->getNamespace() ) &&
					!$this->userHasRight( $user, 'createpage' ) )
			) {
				$errors[] = $user->isAnon() ? [ 'nocreatetext' ] : [ 'nocreate-loggedin' ];
			}
		} elseif ( $action == 'move' ) {
			if ( !$this->userHasRight( $user, 'move-rootuserpages' )
				 && $title->getNamespace() == NS_USER && !$isSubPage ) {
				// Show user page-specific message only if the user can move other pages
				$errors[] = [ 'cant-move-user-page' ];
			}

			// Check if user is allowed to move files if it's a file
			if ( $title->getNamespace() == NS_FILE &&
					!$this->userHasRight( $user, 'movefile' ) ) {
				$errors[] = [ 'movenotallowedfile' ];
			}

			// Check if user is allowed to move category pages if it's a category page
			if ( $title->getNamespace() == NS_CATEGORY &&
					!$this->userHasRight( $user, 'move-categorypages' ) ) {
				$errors[] = [ 'cant-move-category-page' ];
			}

			if ( !$this->userHasRight( $user, 'move' ) ) {
				// User can't move anything
				$userCanMove = $this->groupHasPermission( 'user', 'move' );
				$autoconfirmedCanMove = $this->groupHasPermission( 'autoconfirmed', 'move' );
				if ( $user->isAnon() && ( $userCanMove || $autoconfirmedCanMove ) ) {
					// custom message if logged-in users without any special rights can move
					$errors[] = [ 'movenologintext' ];
				} else {
					$errors[] = [ 'movenotallowed' ];
				}
			}
		} elseif ( $action == 'move-target' ) {
			if ( !$this->userHasRight( $user, 'move' ) ) {
				// User can't move anything
				$errors[] = [ 'movenotallowed' ];
			} elseif ( !$this->userHasRight( $user, 'move-rootuserpages' )
				&& $title->getNamespace() == NS_USER
				&& !$isSubPage
			) {
				// Show user page-specific message only if the user can move other pages
				$errors[] = [ 'cant-move-to-user-page' ];
			} elseif ( !$this->userHasRight( $user, 'move-categorypages' )
				&& $title->getNamespace() == NS_CATEGORY
			) {
				// Show category page-specific message only if the user can move other pages
				$errors[] = [ 'cant-move-to-category-page' ];
			}
		} elseif ( !$this->userHasRight( $user, $action ) ) {
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
		$title = Title::newFromLinkTarget( $page );
		foreach ( $title->getRestrictions( $action ) as $right ) {
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
			if ( !$this->userHasRight( $user, $right ) ) {
				$errors[] = [ 'protectedpagetext', $right, $action ];
			} elseif ( $title->areRestrictionsCascading() &&
				!$this->userHasRight( $user, 'protect' )
			) {
				$errors[] = [ 'protectedpagetext', 'protect', $action ];
			}
		}

		return $errors;
	}

	/**
	 * Check restrictions on cascading pages.
	 *
	 * @param string $action The action to check
	 * @param UserIdentity $user User to check
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
		UserIdentity $user,
		$errors,
		$rigor,
		$short,
		LinkTarget $page
	) {
		// TODO: remove & rework upon further use of LinkTarget
		$title = Title::newFromLinkTarget( $page );
		if ( $rigor !== self::RIGOR_QUICK && !$title->isUserConfigPage() ) {
			list( $cascadingSources, $restrictions ) = $title->getCascadeProtectionSources();
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
					if ( $right != '' && !$this->userHasAllRights( $user, 'protect', $right ) ) {
						$wikiPages = '';
						/** @var Title $wikiPage */
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
		$title = Title::newFromLinkTarget( $page );

		if ( $action == 'protect' ) {
			if ( count( $this->getPermissionErrorsInternal( 'edit', $user, $title, $rigor, true ) ) ) {
				// If they can't edit, they shouldn't protect.
				$errors[] = [ 'protect-cantedit' ];
			}
		} elseif ( $action == 'create' ) {
			$title_protection = $title->getTitleProtection();
			if ( $title_protection ) {
				if ( $title_protection['permission'] == ''
					 || !$this->userHasRight( $user, $title_protection['permission'] )
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
			if ( !$this->nsInfo->isMovable( $title->getNamespace() ) ) {
				// Specific message for this case
				$nsText = $title->getNsText();
				if ( $nsText === '' ) {
					$nsText = wfMessage( 'blanknamespace' )->text();
				}
				$errors[] = [ 'immobile-source-namespace', $nsText ];
			} elseif ( !$title->isMovable() ) {
				// Less specific message for rarer cases
				$errors[] = [ 'immobile-source-page' ];
			}
		} elseif ( $action == 'move-target' ) {
			if ( !$this->nsInfo->isMovable( $title->getNamespace() ) ) {
				$nsText = $title->getNsText();
				if ( $nsText === '' ) {
					$nsText = wfMessage( 'blanknamespace' )->text();
				}
				$errors[] = [ 'immobile-target-namespace', $nsText ];
			} elseif ( !$title->isMovable() ) {
				$errors[] = [ 'immobile-target-page' ];
			}
		} elseif ( $action == 'delete' ) {
			$tempErrors = $this->checkPageRestrictions( 'edit', $user, [], $rigor, true, $title );
			if ( !$tempErrors ) {
				$tempErrors = $this->checkCascadingSourcesRestrictions( 'edit',
					$user, $tempErrors, $rigor, true, $title );
			}
			if ( $tempErrors ) {
				// If protection keeps them from editing, they shouldn't be able to delete.
				$errors[] = [ 'deleteprotected' ];
			}
			if ( $rigor !== self::RIGOR_QUICK && $wgDeleteRevisionsLimit
				 && !$this->userCan( 'bigdelete', $user, $title ) && $title->isBigDeletion()
			) {
				$errors[] = [ 'delete-toobig', $wgLang->formatNum( $wgDeleteRevisionsLimit ) ];
			}
		} elseif ( $action === 'undelete' ) {
			if ( count( $this->getPermissionErrorsInternal( 'edit', $user, $title, $rigor, true ) ) ) {
				// Undeleting implies editing
				$errors[] = [ 'undelete-cantedit' ];
			}
			if ( !$title->exists()
				 && count( $this->getPermissionErrorsInternal( 'create', $user, $title, $rigor, true ) )
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
	 * @param UserIdentity $user User to check
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
		UserIdentity $user,
		$errors,
		$rigor,
		$short,
		LinkTarget $page
	) {
		// TODO: remove & rework upon further use of LinkTarget
		$title = Title::newFromLinkTarget( $page );

		# Only 'createaccount' can be performed on special pages,
		# which don't actually exist in the DB.
		if ( $title->getNamespace() == NS_SPECIAL && $action !== 'createaccount' ) {
			$errors[] = [ 'ns-specialprotected' ];
		}

		# Check $wgNamespaceProtection for restricted namespaces
		if ( $this->isNamespaceProtected( $title->getNamespace(), $user ) ) {
			$ns = $title->getNamespace() == NS_MAIN ?
				wfMessage( 'nstab-main' )->text() : $title->getNsText();
			$errors[] = $title->getNamespace() == NS_MEDIAWIKI ?
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
		$title = Title::newFromLinkTarget( $page );

		if ( $action != 'patrol' ) {
			$error = null;
			// Sitewide CSS/JSON/JS/RawHTML changes, like all NS_MEDIAWIKI changes, also require the
			// editinterface right. That's implemented as a restriction so no check needed here.
			if ( $title->isSiteCssConfigPage() && !$this->userHasRight( $user, 'editsitecss' ) ) {
				$error = [ 'sitecssprotected', $action ];
			} elseif ( $title->isSiteJsonConfigPage() && !$this->userHasRight( $user, 'editsitejson' ) ) {
				$error = [ 'sitejsonprotected', $action ];
			} elseif ( $title->isSiteJsConfigPage() && !$this->userHasRight( $user, 'editsitejs' ) ) {
				$error = [ 'sitejsprotected', $action ];
			}
			if ( $title->isRawHtmlMessage() && !$this->userCanEditRawHtmlPage( $user ) ) {
				$error = [ 'siterawhtmlprotected', $action ];
			}

			if ( $error ) {
				if ( $this->userHasRight( $user, 'editinterface' ) ) {
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
	 * @param UserIdentity $user User to check
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
		UserIdentity $user,
		$errors,
		$rigor,
		$short,
		LinkTarget $page
	) {
		// TODO: remove & rework upon further use of LinkTarget
		$title = Title::newFromLinkTarget( $page );

		# Protect css/json/js subpages of user pages
		# XXX: this might be better using restrictions

		if ( $action === 'patrol' ) {
			return $errors;
		}

		if ( preg_match( '/^' . preg_quote( $user->getName(), '/' ) . '\//', $title->getText() ) ) {
			// Users need editmyuser* to edit their own CSS/JSON/JS subpages.
			if (
				$title->isUserCssConfigPage()
				&& !$this->userHasAnyRight( $user, 'editmyusercss', 'editusercss' )
			) {
				$errors[] = [ 'mycustomcssprotected', $action ];
			} elseif (
				$title->isUserJsonConfigPage()
				&& !$this->userHasAnyRight( $user, 'editmyuserjson', 'edituserjson' )
			) {
				$errors[] = [ 'mycustomjsonprotected', $action ];
			} elseif (
				$title->isUserJsConfigPage()
				&& !$this->userHasAnyRight( $user, 'editmyuserjs', 'edituserjs' )
			) {
				$errors[] = [ 'mycustomjsprotected', $action ];
			} elseif (
				$title->isUserJsConfigPage()
				&& !$this->userHasAnyRight( $user, 'edituserjs', 'editmyuserjsredirect' )
			) {
				// T207750 - do not allow users to edit a redirect if they couldn't edit the target
				$rev = $this->revisionLookup->getRevisionByTitle( $title );
				$content = $rev ? $rev->getContent( 'main', RevisionRecord::RAW ) : null;
				$target = $content ? $content->getUltimateRedirectTarget() : null;
				if ( $target && (
						!$target->inNamespace( NS_USER )
						|| !preg_match( '/^' . preg_quote( $user->getName(), '/' ) . '\//', $target->getText() )
				) ) {
					$errors[] = [ 'mycustomjsredirectprotected', $action ];
				}
			}
		} else {
			// Users need edituser* to edit others' CSS/JSON/JS subpages, except for
			// deletion/suppression which cannot be used for attacks and we want to avoid the
			// situation where an unprivileged user can post abusive content on their subpages
			// and only very highly privileged users could remove it.
			if ( !in_array( $action, [ 'delete', 'deleterevision', 'suppressrevision' ], true ) ) {
				if (
					$title->isUserCssConfigPage()
					&& !$this->userHasRight( $user, 'editusercss' )
				) {
					$errors[] = [ 'customcssprotected', $action ];
				} elseif (
					$title->isUserJsonConfigPage()
					&& !$this->userHasRight( $user, 'edituserjson' )
				) {
					$errors[] = [ 'customjsonprotected', $action ];
				} elseif (
					$title->isUserJsConfigPage()
					&& !$this->userHasRight( $user, 'edituserjs' )
				) {
					$errors[] = [ 'customjsprotected', $action ];
				}
			}
		}

		return $errors;
	}

	/**
	 * Testing a permission
	 *
	 * @since 1.34
	 *
	 * @param UserIdentity $user
	 * @param string $action
	 *
	 * @return bool
	 */
	public function userHasRight( UserIdentity $user, $action = '' ) {
		if ( $action === '' ) {
			return true; // In the spirit of DWIM
		}
		// Use strict parameter to avoid matching numeric 0 accidentally inserted
		// by misconfiguration: 0 == 'foo'
		return in_array( $action, $this->getUserPermissions( $user ), true );
	}

	/**
	 * Check if user is allowed to make any action
	 *
	 * @param UserIdentity $user
	 * @param string ...$actions
	 * @return bool True if user is allowed to perform *any* of the given actions
	 * @since 1.34
	 */
	public function userHasAnyRight( UserIdentity $user, ...$actions ) {
		foreach ( $actions as $action ) {
			if ( $this->userHasRight( $user, $action ) ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Check if user is allowed to make all actions
	 *
	 * @param UserIdentity $user
	 * @param string ...$actions
	 * @return bool True if user is allowed to perform *all* of the given actions
	 * @since 1.34
	 */
	public function userHasAllRights( UserIdentity $user, ...$actions ) {
		foreach ( $actions as $action ) {
			if ( !$this->userHasRight( $user, $action ) ) {
				return false;
			}
		}
		return true;
	}

	/**
	 * Get the permissions this user has.
	 *
	 * @since 1.34
	 *
	 * @param UserIdentity $user
	 *
	 * @return string[] permission names
	 */
	public function getUserPermissions( UserIdentity $user ) {
		$user = User::newFromIdentity( $user );
		$rightsCacheKey = $this->getRightsCacheKey( $user );
		if ( !isset( $this->usersRights[ $rightsCacheKey ] ) ) {
			$this->usersRights[ $rightsCacheKey ] = $this->getGroupPermissions(
				$user->getEffectiveGroups()
			);
			$this->hookRunner->onUserGetRights( $user, $this->usersRights[ $rightsCacheKey ] );

			// Deny any rights denied by the user's session, unless this
			// endpoint has no sessions.
			if ( !defined( 'MW_NO_SESSION' ) ) {
				// FIXME: $user->getRequest().. need to be replaced with something else
				$allowedRights = $user->getRequest()->getSession()->getAllowedUserRights();
				if ( $allowedRights !== null ) {
					$this->usersRights[ $rightsCacheKey ] = array_intersect(
						$this->usersRights[ $rightsCacheKey ],
						$allowedRights
					);
				}
			}

			$this->hookRunner->onUserGetRightsRemove(
				$user, $this->usersRights[ $rightsCacheKey ] );
			// Force reindexation of rights when a hook has unset one of them
			$this->usersRights[ $rightsCacheKey ] = array_values(
				array_unique( $this->usersRights[ $rightsCacheKey ] )
			);

			if (
				$user->isLoggedIn() &&
				$this->options->get( 'BlockDisablesLogin' ) &&
				$user->getBlock()
			) {
				$anon = new User;
				$this->usersRights[ $rightsCacheKey ] = array_intersect(
					$this->usersRights[ $rightsCacheKey ],
					$this->getUserPermissions( $anon )
				);
			}
		}
		$rights = $this->usersRights[ $rightsCacheKey ];
		foreach ( $this->temporaryUserRights[ $user->getId() ] ?? [] as $overrides ) {
			$rights = array_values( array_unique( array_merge( $rights, $overrides ) ) );
		}
		return $rights;
	}

	/**
	 * Clears users permissions cache, if specific user is provided it tries to clear
	 * permissions cache only for provided user.
	 *
	 * @since 1.34
	 *
	 * @param UserIdentity|null $user
	 */
	public function invalidateUsersRightsCache( $user = null ) {
		if ( $user !== null ) {
			$rightsCacheKey = $this->getRightsCacheKey( $user );
			if ( isset( $this->usersRights[ $rightsCacheKey ] ) ) {
				unset( $this->usersRights[ $rightsCacheKey ] );
			}
		} else {
			$this->usersRights = null;
		}
	}

	/**
	 * Gets a unique key for user rights cache.
	 * @param UserIdentity $user
	 * @return string
	 */
	private function getRightsCacheKey( UserIdentity $user ) {
		return $user->isRegistered() ? "u:{$user->getId()}" : "anon:{$user->getName()}";
	}

	/**
	 * Check, if the given group has the given permission
	 *
	 * If you're wanting to check whether all users have a permission, use
	 * PermissionManager::isEveryoneAllowed() instead. That properly checks if it's revoked
	 * from anyone.
	 *
	 * @since 1.34
	 *
	 * @param string $group Group to check
	 * @param string $role Role to check
	 *
	 * @return bool
	 */
	public function groupHasPermission( $group, $role ) {
		$groupPermissions = $this->options->get( 'GroupPermissions' );
		$revokePermissions = $this->options->get( 'RevokePermissions' );
		return isset( $groupPermissions[$group][$role] ) && $groupPermissions[$group][$role] &&
			!( isset( $revokePermissions[$group][$role] ) && $revokePermissions[$group][$role] );
	}

	/**
	 * Get the permissions associated with a given list of groups
	 *
	 * @since 1.34
	 *
	 * @param array $groups Array of Strings List of internal group names
	 * @return array Array of Strings List of permission key names for given groups combined
	 */
	public function getGroupPermissions( $groups ) {
		$rights = [];
		// grant every granted permission first
		foreach ( $groups as $group ) {
			if ( isset( $this->options->get( 'GroupPermissions' )[$group] ) ) {
				$rights = array_merge( $rights,
					// array_filter removes empty items
					array_keys( array_filter( $this->options->get( 'GroupPermissions' )[$group] ) ) );
			}
		}
		// now revoke the revoked permissions
		foreach ( $groups as $group ) {
			if ( isset( $this->options->get( 'RevokePermissions' )[$group] ) ) {
				$rights = array_diff( $rights,
					array_keys( array_filter( $this->options->get( 'RevokePermissions' )[$group] ) ) );
			}
		}
		return array_unique( $rights );
	}

	/**
	 * Get all the groups who have a given permission
	 *
	 * @since 1.34
	 *
	 * @param string $role Role to check
	 * @return array Array of Strings List of internal group names with the given permission
	 */
	public function getGroupsWithPermission( $role ) {
		$allowedGroups = [];
		foreach ( array_keys( $this->options->get( 'GroupPermissions' ) ) as $group ) {
			if ( $this->groupHasPermission( $group, $role ) ) {
				$allowedGroups[] = $group;
			}
		}
		return $allowedGroups;
	}

	/**
	 * Check if all users may be assumed to have the given permission
	 *
	 * We generally assume so if the right is granted to '*' and isn't revoked
	 * on any group. It doesn't attempt to take grants or other extension
	 * limitations on rights into account in the general case, though, as that
	 * would require it to always return false and defeat the purpose.
	 * Specifically, session-based rights restrictions (such as OAuth or bot
	 * passwords) are applied based on the current session.
	 *
	 * @param string $right Right to check
	 *
	 * @return bool
	 * @since 1.34
	 */
	public function isEveryoneAllowed( $right ) {
		// Use the cached results, except in unit tests which rely on
		// being able change the permission mid-request
		if ( isset( $this->cachedRights[$right] ) ) {
			return $this->cachedRights[$right];
		}

		if ( !isset( $this->options->get( 'GroupPermissions' )['*'][$right] )
			 || !$this->options->get( 'GroupPermissions' )['*'][$right] ) {
			$this->cachedRights[$right] = false;
			return false;
		}

		// If it's revoked anywhere, then everyone doesn't have it
		foreach ( $this->options->get( 'RevokePermissions' ) as $rights ) {
			if ( isset( $rights[$right] ) && $rights[$right] ) {
				$this->cachedRights[$right] = false;
				return false;
			}
		}

		// Remove any rights that aren't allowed to the global-session user,
		// unless there are no sessions for this endpoint.
		if ( !defined( 'MW_NO_SESSION' ) ) {

			// XXX: think what could be done with the below
			$allowedRights = SessionManager::getGlobalSession()->getAllowedUserRights();
			if ( $allowedRights !== null && !in_array( $right, $allowedRights, true ) ) {
				$this->cachedRights[$right] = false;
				return false;
			}
		}

		// Allow extensions to say false
		if ( !$this->hookRunner->onUserIsEveryoneAllowed( $right ) ) {
			$this->cachedRights[$right] = false;
			return false;
		}

		$this->cachedRights[$right] = true;
		return true;
	}

	/**
	 * Get a list of all available permissions.
	 *
	 * @since 1.34
	 *
	 * @return string[] Array of permission names
	 */
	public function getAllPermissions() {
		if ( $this->allRights === null ) {
			if ( count( $this->options->get( 'AvailableRights' ) ) ) {
				$this->allRights = array_unique( array_merge(
					$this->coreRights,
					$this->options->get( 'AvailableRights' )
				) );
			} else {
				$this->allRights = $this->coreRights;
			}
			$this->hookRunner->onUserGetAllRights( $this->allRights );
		}
		return $this->allRights;
	}

	/**
	 * Determines if $user is unable to edit pages in namespace because it has been protected.
	 * @param int $index
	 * @param UserIdentity $user
	 * @return bool
	 */
	private function isNamespaceProtected( $index, UserIdentity $user ) {
		$namespaceProtection = $this->options->get( 'NamespaceProtection' );
		if ( isset( $namespaceProtection[$index] ) ) {
			return !$this->userHasAllRights( $user, ...(array)$namespaceProtection[$index] );
		}
		return false;
	}

	/**
	 * Determine which restriction levels it makes sense to use in a namespace,
	 * optionally filtered by a user's rights.
	 *
	 * @param int $index Index to check
	 * @param UserIdentity|null $user User to check
	 * @return array
	 */
	public function getNamespaceRestrictionLevels( $index, UserIdentity $user = null ) {
		if ( !isset( $this->options->get( 'NamespaceProtection' )[$index] ) ) {
			// All levels are valid if there's no namespace restriction.
			// But still filter by user, if necessary
			$levels = $this->options->get( 'RestrictionLevels' );
			if ( $user ) {
				$levels = array_values( array_filter( $levels, function ( $level ) use ( $user ) {
					$right = $level;
					if ( $right == 'sysop' ) {
						$right = 'editprotected'; // BC
					}
					if ( $right == 'autoconfirmed' ) {
						$right = 'editsemiprotected'; // BC
					}
					return $this->userHasRight( $user, $right );
				} ) );
			}
			return $levels;
		}

		// $wgNamespaceProtection can require one or more rights to edit the namespace, which
		// may be satisfied by membership in multiple groups each giving a subset of those rights.
		// A restriction level is redundant if, for any one of the namespace rights, all groups
		// giving that right also give the restriction level's right. Or, conversely, a
		// restriction level is not redundant if, for every namespace right, there's at least one
		// group giving that right without the restriction level's right.
		//
		// First, for each right, get a list of groups with that right.
		$namespaceRightGroups = [];
		foreach ( (array)$this->options->get( 'NamespaceProtection' )[$index] as $right ) {
			if ( $right == 'sysop' ) {
				$right = 'editprotected'; // BC
			}
			if ( $right == 'autoconfirmed' ) {
				$right = 'editsemiprotected'; // BC
			}
			if ( $right != '' ) {
				$namespaceRightGroups[$right] = $this->getGroupsWithPermission( $right );
			}
		}

		// Now, go through the protection levels one by one.
		$usableLevels = [ '' ];
		foreach ( $this->options->get( 'RestrictionLevels' ) as $level ) {
			$right = $level;
			if ( $right == 'sysop' ) {
				$right = 'editprotected'; // BC
			}
			if ( $right == 'autoconfirmed' ) {
				$right = 'editsemiprotected'; // BC
			}

			if ( $right != '' &&
				 !isset( $namespaceRightGroups[$right] ) &&
				 ( !$user || $this->userHasRight( $user, $right ) )
			) {
				// Do any of the namespace rights imply the restriction right? (see explanation above)
				foreach ( $namespaceRightGroups as $groups ) {
					if ( !array_diff( $groups, $this->getGroupsWithPermission( $right ) ) ) {
						// Yes, this one does.
						continue 2;
					}
				}
				// No, keep the restriction level
				$usableLevels[] = $level;
			}
		}

		return $usableLevels;
	}

	/**
	 * Check if user is allowed to edit sitewide pages that contain raw HTML.
	 * Pages listed in $wgRawHtmlMessages allow raw HTML which can be used to deploy CSS or JS
	 * code to all users so both rights are required to edit them.
	 *
	 * @param UserIdentity $user
	 * @return bool True if user has both rights
	 */
	private function userCanEditRawHtmlPage( UserIdentity $user ) {
		return $this->userHasAllRights( $user, 'editsitecss', 'editsitejs' );
	}

	/**
	 * Add temporary user rights, only valid for the current scope.
	 * This is meant for making it possible to programatically trigger certain actions that
	 * the user wouldn't be able to trigger themselves; e.g. allow users without the bot right
	 * to make bot-flagged actions through certain special pages.
	 * Returns a "scope guard" variable; whenever that variable goes out of scope or is consumed
	 * via ScopedCallback::consume(), the temporary rights are revoked.
	 *
	 * @since 1.34
	 *
	 * @param UserIdentity $user
	 * @param string|string[] $rights
	 * @return ScopedCallback
	 */
	public function addTemporaryUserRights( UserIdentity $user, $rights ) {
		$userId = $user->getId();
		$nextKey = count( $this->temporaryUserRights[$userId] ?? [] );
		$this->temporaryUserRights[$userId][$nextKey] = (array)$rights;
		return new ScopedCallback( function () use ( $userId, $nextKey ) {
			unset( $this->temporaryUserRights[$userId][$nextKey] );
		} );
	}

	/**
	 * Overrides user permissions cache
	 *
	 * @since 1.34
	 *
	 * @param User $user
	 * @param string[]|string $rights
	 *
	 * @throws Exception
	 */
	public function overrideUserRightsForTesting( $user, $rights = [] ) {
		if ( !defined( 'MW_PHPUNIT_TEST' ) ) {
			throw new Exception( __METHOD__ . ' can not be called outside of tests' );
		}
		$this->usersRights[ $this->getRightsCacheKey( $user ) ] =
			is_array( $rights ) ? $rights : [ $rights ];
	}

}
