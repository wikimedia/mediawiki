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

use Article;
use Exception;
use MediaWiki\Actions\ActionFactory;
use MediaWiki\Block\BlockErrorFormatter;
use MediaWiki\Block\DatabaseBlock;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\RedirectLookup;
use MediaWiki\Session\SessionManager;
use MediaWiki\SpecialPage\SpecialPageFactory;
use MediaWiki\User\TempUser\TempUserConfig;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserGroupManager;
use MediaWiki\User\UserIdentity;
use MessageSpecifier;
use NamespaceInfo;
use PermissionsError;
use RequestContext;
use SpecialPage;
use Title;
use TitleFormatter;
use User;
use UserCache;
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

	/** @var string Does cheap and expensive checks, using the primary DB as needed */
	public const RIGOR_SECURE = 'secure';

	/**
	 * @internal For use by ServiceWiring
	 */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::WhitelistRead,
		MainConfigNames::WhitelistReadRegexp,
		MainConfigNames::EmailConfirmToEdit,
		MainConfigNames::BlockDisablesLogin,
		MainConfigNames::EnablePartialActionBlocks,
		MainConfigNames::GroupPermissions,
		MainConfigNames::RevokePermissions,
		MainConfigNames::AvailableRights,
		MainConfigNames::NamespaceProtection,
		MainConfigNames::RestrictionLevels,
		MainConfigNames::DeleteRevisionsLimit,
	];

	/** @var ServiceOptions */
	private $options;

	/** @var SpecialPageFactory */
	private $specialPageFactory;

	/** @var RedirectLookup */
	private $redirectLookup;

	/** @var NamespaceInfo */
	private $nsInfo;

	/** @var GroupPermissionsLookup */
	private $groupPermissionsLookup;

	/** @var UserGroupManager */
	private $userGroupManager;

	/** @var string[]|null Cached results of getAllPermissions() */
	private $allRights;

	/** @var BlockErrorFormatter */
	private $blockErrorFormatter;

	/** @var HookRunner */
	private $hookRunner;

	/** @var UserCache */
	private $userCache;

	/** @var RestrictionStore */
	private $restrictionStore;

	/** @var TitleFormatter */
	private $titleFormatter;

	/** @var TempUserConfig */
	private $tempUserConfig;

	/** @var UserFactory */
	private $userFactory;

	/** @var ActionFactory */
	private $actionFactory;

	/** @var string[][] Cached user rights */
	private $usersRights = [];

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
		'delete-redirect',
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
	 * @param NamespaceInfo $nsInfo
	 * @param GroupPermissionsLookup $groupPermissionsLookup
	 * @param UserGroupManager $userGroupManager
	 * @param BlockErrorFormatter $blockErrorFormatter
	 * @param HookContainer $hookContainer
	 * @param UserCache $userCache
	 * @param RedirectLookup $redirectLookup
	 * @param RestrictionStore $restrictionStore
	 * @param TitleFormatter $titleFormatter
	 * @param TempUserConfig $tempUserConfig
	 * @param UserFactory $userFactory
	 * @param ActionFactory $actionFactory
	 */
	public function __construct(
		ServiceOptions $options,
		SpecialPageFactory $specialPageFactory,
		NamespaceInfo $nsInfo,
		GroupPermissionsLookup $groupPermissionsLookup,
		UserGroupManager $userGroupManager,
		BlockErrorFormatter $blockErrorFormatter,
		HookContainer $hookContainer,
		UserCache $userCache,
		RedirectLookup $redirectLookup,
		RestrictionStore $restrictionStore,
		TitleFormatter $titleFormatter,
		TempUserConfig $tempUserConfig,
		UserFactory $userFactory,
		ActionFactory $actionFactory
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->options = $options;
		$this->specialPageFactory = $specialPageFactory;
		$this->nsInfo = $nsInfo;
		$this->groupPermissionsLookup = $groupPermissionsLookup;
		$this->userGroupManager = $userGroupManager;
		$this->blockErrorFormatter = $blockErrorFormatter;
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->userCache = $userCache;
		$this->redirectLookup = $redirectLookup;
		$this->restrictionStore = $restrictionStore;
		$this->titleFormatter = $titleFormatter;
		$this->tempUserConfig = $tempUserConfig;
		$this->userFactory = $userFactory;
		$this->actionFactory = $actionFactory;
	}

	/**
	 * Can $user perform $action on a page?
	 *
	 * The method replaced Title::userCan()
	 * The $user parameter need to be superseded by UserIdentity value in future
	 * The $title parameter need to be superseded by PageIdentity value in future
	 *
	 * @param string $action
	 * @param User $user
	 * @param LinkTarget $page
	 * @param string $rigor One of PermissionManager::RIGOR_ constants
	 *   - RIGOR_QUICK  : does cheap permission checks from replica DBs (usable for GUI creation)
	 *   - RIGOR_FULL   : does cheap and expensive checks possibly from a replica DB
	 *   - RIGOR_SECURE : does cheap and expensive checks, using the primary DB as needed
	 *
	 * @return bool
	 */
	public function userCan( $action, User $user, LinkTarget $page, $rigor = self::RIGOR_SECURE ): bool {
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
	public function quickUserCan( $action, User $user, LinkTarget $page ): bool {
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
	 *   - RIGOR_SECURE : does cheap and expensive checks, using the primary DB as needed
	 * @param string[] $ignoreErrors Set this to a list of message keys
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
	): array {
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

		return array_values( $errors );
	}

	/**
	 * Like {@link getPermissionErrors}, but immediately throw if there are any errors.
	 *
	 * @param string $action Action that permission needs to be checked for
	 * @param User $user User to check
	 * @param LinkTarget $page
	 * @param string $rigor One of PermissionManager::RIGOR_ constants
	 *   - RIGOR_QUICK  : does cheap permission checks from replica DBs (usable for GUI creation)
	 *   - RIGOR_FULL   : does cheap and expensive checks possibly from a replica DB
	 *   - RIGOR_SECURE : does cheap and expensive checks, using the primary DB as needed
	 * @param string[] $ignoreErrors Set this to a list of message keys
	 *   whose corresponding errors may be ignored.
	 *
	 * @throws PermissionsError
	 */
	public function throwPermissionErrors(
		$action,
		User $user,
		LinkTarget $page,
		$rigor = self::RIGOR_SECURE,
		$ignoreErrors = []
	): void {
		$permissionErrors = $this->getPermissionErrors(
			$action, $user, $page, $rigor, $ignoreErrors );
		if ( $permissionErrors !== [] ) {
			throw new PermissionsError( $action, $permissionErrors );
		}
	}

	/**
	 * Check if user is blocked from editing a particular article. If the user does not
	 * have a block, this will return false.
	 *
	 * @param User $user
	 * @param PageIdentity|LinkTarget $page Title to check
	 * @param bool $fromReplica Whether to check the replica DB instead of the primary DB
	 * @return bool
	 */
	public function isBlockedFrom( User $user, $page, $fromReplica = false ): bool {
		$block = $user->getBlock( $fromReplica );
		if ( !$block ) {
			return false;
		}

		if ( $page instanceof PageIdentity ) {
			$title = Title::castFromPageIdentity( $page );
		} else {
			$title = Title::castFromLinkTarget( $page );
		}

		$blocked = $user->isHidden();
		if ( !$blocked ) {
			// Special handling for a user's own talk page. The block is not aware
			// of the user, so this must be done here.
			if ( $title->equals( $user->getTalkPage() ) ) {
				$blocked = $block->appliesToUsertalk( $title );
			} else {
				// @phan-suppress-next-line PhanTypeMismatchArgumentNullable castFrom does not return null here
				$blocked = $block->appliesToTitle( $title );
			}
		}

		// only for the purpose of the hook. We really don't need this here.
		$allowUsertalk = $block->isUsertalkEditAllowed();

		// Allow extensions to let a blocked user access a particular page
		// @phan-suppress-next-line PhanTypeMismatchArgumentNullable castFrom does not return null here
		$this->hookRunner->onUserIsBlockedFrom( $user, $title, $blocked, $allowUsertalk );

		return $blocked;
	}

	/**
	 * Can $user perform $action on a page? This is an internal function,
	 * with multiple levels of checks depending on performance needs; see $rigor below.
	 * It does not check ReadOnlyMode::isReadOnly().
	 *
	 * @param string $action Action that permission needs to be checked for
	 * @param User $user User to check
	 * @param LinkTarget $page
	 * @param string $rigor One of PermissionManager::RIGOR_ constants
	 *   - RIGOR_QUICK  : does cheap permission checks from replica DBs (usable for GUI creation)
	 *   - RIGOR_FULL   : does cheap and expensive checks possibly from a replica DB
	 *   - RIGOR_SECURE : does cheap and expensive checks, using the primary DB as needed
	 * @param bool $short Set this to true to stop after the first permission error.
	 * @return array[] Array of arrays of the arguments to wfMessage to explain permissions problems.
	 * @throws Exception
	 */
	private function getPermissionErrorsInternal(
		$action,
		User $user,
		LinkTarget $page,
		$rigor = self::RIGOR_SECURE,
		$short = false
	): array {
		if ( !in_array( $rigor, [ self::RIGOR_QUICK, self::RIGOR_FULL, self::RIGOR_SECURE ] ) ) {
			throw new Exception( "Invalid rigor parameter '$rigor'." );
		}

		// With RIGOR_QUICK we can assume automatic account creation will
		// occur. At a higher rigor level, the caller is required to opt
		// in by either setting the create intent or actually creating
		// the account.
		if ( $rigor === self::RIGOR_QUICK
			&& !$user->isRegistered()
			&& $this->tempUserConfig->isAutoCreateAction( $action )
		) {
			$user = $this->userFactory->newTempPlaceholder();
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

			// Exclude checkUserConfigPermissions on actions that cannot change the
			// content of the configuration pages.
			$skipUserConfigActions = [
				// Allow patrolling per T21818
				'patrol',

				// Allow admins and oversighters to delete. For user pages we want to avoid the
				// situation where an unprivileged user can post abusive content on
				// their subpages and only very highly privileged users could remove it.
				// See T200176.
				'delete',
				'deleterevision',
				'suppressrevision',

				// Allow admins and oversighters to view deleted content, even if they
				// cannot restore it. See T202989
				'deletedhistory',
				'deletedtext',
				'viewsuppressed',
			];

			if ( in_array( $action, $skipUserConfigActions, true ) ) {
				$checks = array_diff(
					$checks,
					[ 'checkUserConfigPermissions' ]
				);
				// Reset numbering
				$checks = array_values( $checks );
			}
		}

		$errors = [];
		foreach ( $checks as $method ) {
			$errors = $this->$method( $action, $user, $errors, $rigor, $short, $page );

			if ( $short && $errors !== [] ) {
				break;
			}
		}
		// remove duplicate errors
		$errors = array_unique( $errors, SORT_REGULAR );
		if ( $errors ) {
			$this->hookRunner->onPermissionErrorAudit( $page, $user, $action, $rigor, $errors );
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
	 *   - RIGOR_SECURE : does cheap and expensive checks, using the primary DB as needed
	 * @param bool $short Short circuit on first error
	 * @param LinkTarget $page
	 * @return array List of errors
	 */
	private function checkPermissionHooks(
		$action,
		User $user,
		$errors,
		$rigor,
		$short,
		LinkTarget $page
	): array {
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
	 * @return array List of errors
	 */
	private function resultToError( $errors, $result ): array {
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
	 *   - RIGOR_SECURE : does cheap and expensive checks, using the primary DB as needed
	 * @param bool $short Short circuit on first error
	 * @param LinkTarget $page
	 * @return array List of errors
	 */
	private function checkReadPermissions(
		$action,
		User $user,
		$errors,
		$rigor,
		$short,
		LinkTarget $page
	): array {
		// TODO: remove when LinkTarget usage will expand further
		$title = Title::newFromLinkTarget( $page );

		$whiteListRead = $this->options->get( MainConfigNames::WhitelistRead );
		$allowed = false;
		if ( $this->isEveryoneAllowed( 'read' ) ) {
			// Shortcut for public wikis, allows skipping quite a bit of code
			$allowed = true;
		} elseif ( $this->userHasRight( $user, 'read' ) ) {
			// If the user is allowed to read pages, he is allowed to read all pages
			$allowed = true;
		} elseif ( $this->isSameSpecialPage( 'Userlogin', $title )
			|| $this->isSameSpecialPage( 'PasswordReset', $title )
			|| $this->isSameSpecialPage( 'Userlogout', $title )
		) {
			// Always grant access to the login page.
			// Even anons need to be able to log in.
			$allowed = true;
		} elseif ( $this->isSameSpecialPage( 'RunJobs', $title ) ) {
			// relies on HMAC key signature alone
			$allowed = true;
		} elseif ( is_array( $whiteListRead ) && count( $whiteListRead ) ) {
			// Time to check the whitelist
			// Only do these checks is there's something to check against
			$name = $title->getPrefixedText();
			$dbName = $title->getPrefixedDBkey();

			// Check for explicit whitelisting with and without underscores
			if ( in_array( $name, $whiteListRead, true )
				|| in_array( $dbName, $whiteListRead, true )
			) {
				$allowed = true;
			} elseif ( $title->getNamespace() === NS_MAIN ) {
				// Old settings might have the title prefixed with
				// a colon for main-namespace pages
				if ( in_array( ':' . $name, $whiteListRead ) ) {
					$allowed = true;
				}
			} elseif ( $title->isSpecialPage() ) {
				// If it's a special page, ditch the subpage bit and check again
				$name = $title->getDBkey();
				list( $name, /* $subpage */ ) =
					$this->specialPageFactory->resolveAlias( $name );
				if ( $name ) {
					$pure = SpecialPage::getTitleFor( $name )->getPrefixedText();
					if ( in_array( $pure, $whiteListRead, true ) ) {
						$allowed = true;
					}
				}
			}
		}

		$whitelistReadRegexp = $this->options->get( MainConfigNames::WhitelistReadRegexp );
		if ( !$allowed && is_array( $whitelistReadRegexp )
			&& !empty( $whitelistReadRegexp )
		) {
			$name = $title->getPrefixedText();
			// Check for regex whitelisting
			foreach ( $whitelistReadRegexp as $listItem ) {
				if ( preg_match( $listItem, $name ) ) {
					$allowed = true;
					break;
				}
			}
		}

		if ( !$allowed ) {
			# If the title is not whitelisted, give extensions a chance to do so...
			$this->hookRunner->onTitleReadWhitelist( $title, $user, $allowed );
			if ( !$allowed ) {
				$errors[] = $this->missingPermissionError( $action, $short );
			}
		}

		return $errors;
	}

	/**
	 * Get a description array for when an action isn't allowed to be performed.
	 *
	 * @param string $action The action to check
	 * @param bool $short Short circuit on first error
	 * @return array Array containing an error message key and any parameters
	 */
	private function missingPermissionError( string $action, bool $short ): array {
		// We avoid expensive display logic for quickUserCan's and such
		if ( $short ) {
			return [ 'badaccess-group0' ];
		}

		// TODO: it would be a good idea to replace the method below with something else like
		// maybe callback injection
		return User::newFatalPermissionDeniedStatus( $action )->getErrorsArray()[0];
	}

	/**
	 * Whether a title resolves to the named special page.
	 *
	 * @param string $name The special page name
	 * @param LinkTarget $page
	 * @return bool
	 */
	private function isSameSpecialPage( $name, LinkTarget $page ): bool {
		if ( $page->getNamespace() === NS_SPECIAL ) {
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
	 *   - RIGOR_SECURE : does cheap and expensive checks, using the primary DB as needed
	 * @param bool $short Short circuit on first error
	 * @param LinkTarget $page
	 * @return array List of errors
	 */
	private function checkUserBlock(
		$action,
		User $user,
		$errors,
		$rigor,
		$short,
		LinkTarget $page
	): array {
		// Unblocking handled in SpecialUnblock
		if ( $rigor === self::RIGOR_QUICK || in_array( $action, [ 'unblock' ] ) ) {
			return $errors;
		}

		// Optimize for a very common case
		if ( $action === 'read' && !$this->options->get( MainConfigNames::BlockDisablesLogin ) ) {
			return $errors;
		}

		if ( $this->options->get( MainConfigNames::EmailConfirmToEdit )
			&& !$user->isEmailConfirmed()
			&& $action === 'edit'
		) {
			$errors[] = [ 'confirmedittext' ];
		}

		switch ( $rigor ) {
			case self::RIGOR_SECURE:
				$blockInfoFreshness = Authority::READ_LATEST;
				$useReplica = false;
				break;
			case self::RIGOR_FULL:
				$blockInfoFreshness = Authority::READ_NORMAL;
				$useReplica = true;
				break;
			default:
				$useReplica = true;
				$blockInfoFreshness = Authority::READ_NORMAL;
		}

		$block = $user->getBlock( $blockInfoFreshness );

		if ( $action === 'createaccount' ) {
			$applicableBlock = null;
			if ( $block && $block->appliesToRight( 'createaccount' ) ) {
				$applicableBlock = $block;
			}

			// T15611: if the IP address the user is trying to create an account from is
			// blocked with createaccount disabled, prevent new account creation there even
			// when the user is logged in
			if ( !$this->userHasRight( $user, 'ipblock-exempt' ) ) {
				$ipBlock = DatabaseBlock::newFromTarget(
					null, $user->getRequest()->getIP()
				);
				if ( $ipBlock && $ipBlock->appliesToRight( 'createaccount' ) ) {
					$applicableBlock = $ipBlock;
				}
			}
			// @todo FIXME: Pass the relevant context into this function.
			if ( $applicableBlock ) {
				$context = RequestContext::getMain();
				$message = $this->blockErrorFormatter->getMessage(
					$applicableBlock,
					$context->getUser(),
					$context->getLanguage(),
					$context->getRequest()->getIP()
				);
				$errors[] = array_merge( [ $message->getKey() ], $message->getParams() );
				return $errors;
			}
		}

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
		$title = Title::newFromLinkTarget( $page, 'clone' );
		if ( $title->canExist() ) {
			// TODO: this drags a ton of dependencies in, would be good to avoid Article
			//  instantiation and decouple it creating an ActionPermissionChecker interface
			// Creating an action will perform several database queries to ensure that
			// the action has not been overridden by the content type.
			// FIXME: avoid use of RequestContext since it drags in User and Title dependencies
			//  probably we may use fake context object since it's unlikely that Action uses it
			//  anyway. It would be nice if we could avoid instantiating the Action at all.
			$context = RequestContext::getMain();
			$actionObj = $this->actionFactory->getAction(
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
			if (
				$this->isBlockedFrom( $user, $page, $useReplica ) ||
				(
					$this->options->get( MainConfigNames::EnablePartialActionBlocks ) &&
					$block->appliesToRight( $action )
				)
			) {
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
	 * Run easy-to-test (or "quick") permissions checks for a given action.
	 *
	 * @param string $action The action to check
	 * @param User $user User to check
	 * @param array $errors List of current errors
	 * @param string $rigor One of PermissionManager::RIGOR_ constants
	 *   - RIGOR_QUICK  : does cheap permission checks from replica DBs (usable for GUI creation)
	 *   - RIGOR_FULL   : does cheap and expensive checks possibly from a replica DB
	 *   - RIGOR_SECURE : does cheap and expensive checks, using the primary DB as needed
	 * @param bool $short Short circuit on first error
	 * @param LinkTarget $page
	 * @return array List of errors
	 */
	private function checkQuickPermissions(
		$action,
		User $user,
		$errors,
		$rigor,
		$short,
		LinkTarget $page
	): array {
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
				$errors[] = $user->isNamed() ? [ 'nocreate-loggedin' ] : [ 'nocreatetext' ];
			}
		} elseif ( $action == 'move' ) {
			if ( !$this->userHasRight( $user, 'move-rootuserpages' )
				&& $title->getNamespace() === NS_USER && !$isSubPage
			) {
				// Show user page-specific message only if the user can move other pages
				$errors[] = [ 'cant-move-user-page' ];
			}

			// Check if user is allowed to move files if it's a file
			if ( $title->getNamespace() === NS_FILE &&
				!$this->userHasRight( $user, 'movefile' )
			) {
				$errors[] = [ 'movenotallowedfile' ];
			}

			// Check if user is allowed to move category pages if it's a category page
			if ( $title->getNamespace() === NS_CATEGORY &&
				!$this->userHasRight( $user, 'move-categorypages' )
			) {
				$errors[] = [ 'cant-move-category-page' ];
			}

			if ( !$this->userHasRight( $user, 'move' ) ) {
				// User can't move anything
				$userCanMove = $this->groupPermissionsLookup
					->groupHasPermission( 'user', 'move' );
				$namedCanMove = $this->groupPermissionsLookup
					->groupHasPermission( 'named', 'move' );
				$autoconfirmedCanMove = $this->groupPermissionsLookup
					->groupHasPermission( 'autoconfirmed', 'move' );
				if ( $user->isAnon()
					&& ( $userCanMove || $namedCanMove || $autoconfirmedCanMove )
				) {
					// custom message if logged-in users without any special rights can move
					$errors[] = [ 'movenologintext' ];
				} elseif ( $user->isTemp() && ( $namedCanMove || $autoconfirmedCanMove ) ) {
					// Temp user may be able to move if they log in as a proper account
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
				&& $title->getNamespace() === NS_USER
				&& !$isSubPage
			) {
				// Show user page-specific message only if the user can move other pages
				$errors[] = [ 'cant-move-to-user-page' ];
			} elseif ( !$this->userHasRight( $user, 'move-categorypages' )
				&& $title->getNamespace() === NS_CATEGORY
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
	 * Check for any page_restrictions table requirements on this page.
	 *
	 * If the page has multiple restrictions, the user must have
	 * all of those rights to perform the action in question.
	 *
	 * @param string $action The action to check
	 * @param User $user User to check
	 * @param array $errors List of current errors
	 * @param string $rigor One of PermissionManager::RIGOR_ constants
	 *   - RIGOR_QUICK  : does cheap permission checks from replica DBs (usable for GUI creation)
	 *   - RIGOR_FULL   : does cheap and expensive checks possibly from a replica DB
	 *   - RIGOR_SECURE : does cheap and expensive checks, using the primary DB as needed
	 * @param bool $short Short circuit on first error
	 * @param LinkTarget $page
	 * @return array List of errors
	 */
	private function checkPageRestrictions(
		$action,
		User $user,
		$errors,
		$rigor,
		$short,
		LinkTarget $page
	): array {
		// TODO: remove & rework upon further use of LinkTarget
		$title = Title::newFromLinkTarget( $page );
		foreach ( $this->restrictionStore->getRestrictions( $title, $action ) as $right ) {
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
			} elseif ( $this->restrictionStore->areRestrictionsCascading( $title ) &&
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
	 *   - RIGOR_SECURE : does cheap and expensive checks, using the primary DB as needed
	 * @param bool $short Short circuit on first error
	 * @param LinkTarget $page
	 * @return array List of errors
	 */
	private function checkCascadingSourcesRestrictions(
		$action,
		UserIdentity $user,
		$errors,
		$rigor,
		$short,
		LinkTarget $page
	): array {
		// TODO: remove & rework upon further use of LinkTarget
		$title = Title::newFromLinkTarget( $page );
		if ( $rigor !== self::RIGOR_QUICK && !$title->isUserConfigPage() ) {
			list( $cascadingSources, $restrictions ) = $this->restrictionStore->getCascadeProtectionSources( $title );
			// Cascading protection depends on more than this page...
			// Several cascading protected pages may include this page...
			// Check each cascading level
			// This is only for protection restrictions, not for all actions
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
						foreach ( $cascadingSources as $pageIdentity ) {
							$wikiPages .= '* [[:' . $this->titleFormatter->getPrefixedText( $pageIdentity ) . "]]\n";
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
	 *   - RIGOR_SECURE : does cheap and expensive checks, using the primary DB as needed
	 * @param bool $short Short circuit on first error
	 * @param LinkTarget $page
	 * @return array List of errors
	 */
	private function checkActionPermissions(
		$action,
		User $user,
		$errors,
		$rigor,
		$short,
		LinkTarget $page
	): array {
		global $wgLang;

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
						$this->userCache->getProp( $title_protection['user'], 'name' ),
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
		} elseif ( $action == 'delete' || $action == 'delete-redirect' ) {
			$tempErrors = $this->checkPageRestrictions( 'edit', $user, [], $rigor, true, $title );
			if ( !$tempErrors ) {
				$tempErrors = $this->checkCascadingSourcesRestrictions( 'edit',
					$user, $tempErrors, $rigor, true, $title );
			}
			if ( $tempErrors ) {
				// If protection keeps them from editing, they shouldn't be able to delete.
				$errors[] = [ 'deleteprotected' ];
			}
			if ( $rigor !== self::RIGOR_QUICK
				&& $action == 'delete'
				&& $this->options->get( MainConfigNames::DeleteRevisionsLimit )
				&& !$this->userCan( 'bigdelete', $user, $title )
				&& $title->isBigDeletion()
			) {
				// NOTE: This check is deprecated since 1.37, see T288759
				$errors[] = [
					'delete-toobig',
					$wgLang->formatNum( $this->options->get( MainConfigNames::DeleteRevisionsLimit ) )
				];
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
		} elseif ( $action === 'edit' ) {
			if ( !$title->exists() ) {
				$errors = array_merge(
					$errors,
					$this->getPermissionErrorsInternal(
						'create',
						$user,
						$title,
						$rigor,
						true
					)
				);
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
	 *   - RIGOR_SECURE : does cheap and expensive checks, using the primary DB as needed
	 * @param bool $short Short circuit on first error
	 * @param LinkTarget $page
	 * @return array List of errors
	 */
	private function checkSpecialsAndNSPermissions(
		$action,
		UserIdentity $user,
		$errors,
		$rigor,
		$short,
		LinkTarget $page
	): array {
		// TODO: remove & rework upon further use of LinkTarget
		$title = Title::newFromLinkTarget( $page );

		// Only 'createaccount' can be performed on special pages,
		// which don't actually exist in the DB.
		if ( $title->getNamespace() === NS_SPECIAL && $action !== 'createaccount' ) {
			$errors[] = [ 'ns-specialprotected' ];
		}

		// Check $wgNamespaceProtection for restricted namespaces
		if ( $this->isNamespaceProtected( $title->getNamespace(), $user ) ) {
			$ns = $title->getNamespace() === NS_MAIN ?
				wfMessage( 'nstab-main' )->text() : $title->getNsText();
			$errors[] = $title->getNamespace() === NS_MEDIAWIKI ?
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
	 *   - RIGOR_SECURE : does cheap and expensive checks, using the primary DB as needed
	 * @param bool $short Short circuit on first error
	 * @param LinkTarget $page
	 * @return array List of errors
	 */
	private function checkSiteConfigPermissions(
		$action,
		User $user,
		$errors,
		$rigor,
		$short,
		LinkTarget $page
	): array {
		// TODO: remove & rework upon further use of LinkTarget
		$title = Title::newFromLinkTarget( $page );

		if ( $action === 'patrol' ) {
			return $errors;
		}

		if ( in_array( $action, [ 'deletedhistory', 'deletedtext', 'viewsuppressed' ], true ) ) {
			// Allow admins and oversighters to view deleted content, even if they
			// cannot restore it. See T202989
			// Not using the same handling in `getPermissionErrorsInternal` as the checks
			// for skipping `checkUserConfigPermissions` since normal admins can delete
			// user scripts, but not sitedwide scripts
			return $errors;
		}

		// Sitewide CSS/JSON/JS/RawHTML changes, like all NS_MEDIAWIKI changes, also require the
		// editinterface right. That's implemented as a restriction so no check needed here.
		if ( $title->isSiteCssConfigPage() && !$this->userHasRight( $user, 'editsitecss' ) ) {
			$errors[] = [ 'sitecssprotected', $action ];
		} elseif ( $title->isSiteJsonConfigPage() && !$this->userHasRight( $user, 'editsitejson' ) ) {
			$errors[] = [ 'sitejsonprotected', $action ];
		} elseif ( $title->isSiteJsConfigPage() && !$this->userHasRight( $user, 'editsitejs' ) ) {
			$errors[] = [ 'sitejsprotected', $action ];
		}
		if ( $title->isRawHtmlMessage() && !$this->userCanEditRawHtmlPage( $user ) ) {
			$errors[] = [ 'siterawhtmlprotected', $action ];
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
	 *   - RIGOR_SECURE : does cheap and expensive checks, using the primary DB as needed
	 * @param bool $short Short circuit on first error
	 * @param LinkTarget $page
	 * @return array List of errors
	 */
	private function checkUserConfigPermissions(
		$action,
		UserIdentity $user,
		$errors,
		$rigor,
		$short,
		LinkTarget $page
	): array {
		// TODO: remove & rework upon further use of LinkTarget
		$title = Title::newFromLinkTarget( $page );

		// Protect css/json/js subpages of user pages
		// XXX: this might be better using restrictions
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
				$target = $this->redirectLookup->getRedirectTarget( $title );
				if ( $target && (
						!$target->inNamespace( NS_USER )
						|| !preg_match( '/^' . preg_quote( $user->getName(), '/' ) . '\//', $target->getText() )
				) ) {
					$errors[] = [ 'mycustomjsredirectprotected', $action ];
				}
			}
		} else {
			// Users need edituser* to edit others' CSS/JSON/JS subpages.
			// The checks to exclude deletion/suppression, which cannot be used for
			// attacks and should be excluded to avoid the situation where an
			// unprivileged user can post abusive content on their subpages
			// and only very highly privileged users could remove it,
			// are now a part of `getPermissionErrorsInternal` and this method isn't called.
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

		return $errors;
	}

	/**
	 * Whether the user is generally allowed to perform the given action.
	 *
	 * @since 1.34
	 * @param UserIdentity $user
	 * @param string $action
	 * @return bool True if allowed
	 */
	public function userHasRight( UserIdentity $user, $action = '' ): bool {
		if ( $action === '' ) {
			// In the spirit of DWIM
			return true;
		}
		// Use strict parameter to avoid matching numeric 0 accidentally inserted
		// by misconfiguration: 0 == 'foo'
		return in_array( $action, $this->getUserPermissions( $user ), true );
	}

	/**
	 * Whether the user is generally allowed to perform at least one of the actions.
	 *
	 * @since 1.34
	 * @param UserIdentity $user
	 * @param string ...$actions
	 * @return bool True if user is allowed to perform *any* of the actions
	 */
	public function userHasAnyRight( UserIdentity $user, ...$actions ): bool {
		foreach ( $actions as $action ) {
			if ( $this->userHasRight( $user, $action ) ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Whether the user is allowed to perform all of the given actions.
	 *
	 * @since 1.34
	 * @param UserIdentity $user
	 * @param string ...$actions
	 * @return bool True if user is allowed to perform *all* of the given actions
	 */
	public function userHasAllRights( UserIdentity $user, ...$actions ): bool {
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
	 * @param UserIdentity $user
	 * @return string[] permission names
	 */
	public function getUserPermissions( UserIdentity $user ): array {
		$rightsCacheKey = $this->getRightsCacheKey( $user );
		if ( !isset( $this->usersRights[ $rightsCacheKey ] ) ) {
			$userObj = User::newFromIdentity( $user );
			$this->usersRights[ $rightsCacheKey ] = $this->getGroupPermissions(
				$this->userGroupManager->getUserEffectiveGroups( $user )
			);
			// Hook requires a full User object
			$this->hookRunner->onUserGetRights( $userObj, $this->usersRights[ $rightsCacheKey ] );

			// Deny any rights denied by the user's session, unless this
			// endpoint has no sessions.
			if ( !defined( 'MW_NO_SESSION' ) ) {
				// FIXME: $userObj->getRequest().. need to be replaced with something else
				$allowedRights = $userObj->getRequest()->getSession()->getAllowedUserRights();
				if ( $allowedRights !== null ) {
					$this->usersRights[ $rightsCacheKey ] = array_intersect(
						$this->usersRights[ $rightsCacheKey ],
						$allowedRights
					);
				}
			}

			// Hook requires a full User object
			$this->hookRunner->onUserGetRightsRemove(
				$userObj, $this->usersRights[ $rightsCacheKey ] );
			// Force reindexation of rights when a hook has unset one of them
			$this->usersRights[ $rightsCacheKey ] = array_values(
				array_unique( $this->usersRights[ $rightsCacheKey ] )
			);

			if (
				$userObj->isRegistered() &&
				$this->options->get( MainConfigNames::BlockDisablesLogin ) &&
				$userObj->getBlock()
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
	 * Clear the in-process permission cache for one or all users.
	 *
	 * @since 1.34
	 * @param UserIdentity|null $user If a specific user is provided it will clear
	 *  the permission cache only for that user.
	 */
	public function invalidateUsersRightsCache( $user = null ): void {
		if ( $user !== null ) {
			$rightsCacheKey = $this->getRightsCacheKey( $user );
			unset( $this->usersRights[ $rightsCacheKey ] );
		} else {
			$this->usersRights = [];
		}
	}

	/**
	 * Get a unique key for user rights cache.
	 *
	 * @param UserIdentity $user
	 * @return string
	 */
	private function getRightsCacheKey( UserIdentity $user ): string {
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
	 * @deprecated since 1.36 Use GroupPermissionsLookup instead
	 * @param string $group Group to check
	 * @param string $role Role to check
	 * @return bool
	 */
	public function groupHasPermission( $group, $role ): bool {
		return $this->groupPermissionsLookup->groupHasPermission( $group, $role );
	}

	/**
	 * Get the permissions associated with a given list of groups
	 *
	 * @since 1.34
	 * @deprecated since 1.36 Use GroupPermissionsLookup instead
	 * @param string[] $groups internal group names
	 * @return string[] permission key names for given groups combined
	 */
	public function getGroupPermissions( $groups ): array {
		return $this->groupPermissionsLookup->getGroupPermissions( $groups );
	}

	/**
	 * Get all the groups who have a given permission
	 *
	 * @since 1.34
	 * @deprecated since 1.36, use GroupPermissionsLookup instead.
	 * @param string $role Role to check
	 * @return string[] internal group names with the given permission
	 */
	public function getGroupsWithPermission( $role ): array {
		return $this->groupPermissionsLookup->getGroupsWithPermission( $role );
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
	 * @since 1.34
	 * @param string $right Right to check
	 * @return bool
	 */
	public function isEveryoneAllowed( $right ): bool {
		// Use the cached results, except in unit tests which rely on
		// being able change the permission mid-request
		if ( isset( $this->cachedRights[$right] ) ) {
			return $this->cachedRights[$right];
		}

		if ( !isset( $this->options->get( MainConfigNames::GroupPermissions )['*'][$right] )
			|| !$this->options->get( MainConfigNames::GroupPermissions )['*'][$right]
		) {
			$this->cachedRights[$right] = false;
			return false;
		}

		// If it's revoked anywhere, then everyone doesn't have it
		foreach ( $this->options->get( MainConfigNames::RevokePermissions ) as $rights ) {
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
	 * @return string[] Array of permission names
	 */
	public function getAllPermissions(): array {
		if ( $this->allRights === null ) {
			if ( count( $this->options->get( MainConfigNames::AvailableRights ) ) ) {
				$this->allRights = array_unique( array_merge(
					$this->coreRights,
					$this->options->get( MainConfigNames::AvailableRights )
				) );
			} else {
				$this->allRights = $this->coreRights;
			}
			$this->hookRunner->onUserGetAllRights( $this->allRights );
		}
		return $this->allRights;
	}

	/**
	 * Determine if $user is unable to edit pages in namespace because it has been protected.
	 *
	 * @param int $index
	 * @param UserIdentity $user
	 * @return bool
	 */
	private function isNamespaceProtected( $index, UserIdentity $user ): bool {
		$namespaceProtection = $this->options->get( MainConfigNames::NamespaceProtection );
		if ( isset( $namespaceProtection[$index] ) ) {
			return !$this->userHasAllRights( $user, ...(array)$namespaceProtection[$index] );
		}
		return false;
	}

	/**
	 * Determine which restriction levels it makes sense to use in a namespace,
	 * optionally filtered by a user's rights.
	 *
	 * @param int $index Namespace ID (index) to check
	 * @param UserIdentity|null $user User to check
	 * @return string[]
	 */
	public function getNamespaceRestrictionLevels( $index, UserIdentity $user = null ): array {
		if ( !isset( $this->options->get( MainConfigNames::NamespaceProtection )[$index] ) ) {
			// All levels are valid if there's no namespace restriction.
			// But still filter by user, if necessary
			$levels = $this->options->get( MainConfigNames::RestrictionLevels );
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
		foreach ( (array)$this->options->get( MainConfigNames::NamespaceProtection )[$index] as $right ) {
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
		foreach ( $this->options->get( MainConfigNames::RestrictionLevels ) as $level ) {
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
	 *
	 * Pages listed in $wgRawHtmlMessages allow raw HTML which can be used to deploy CSS or JS
	 * code to all users so both rights are required to edit them.
	 *
	 * @param UserIdentity $user
	 * @return bool True if user has both rights
	 */
	private function userCanEditRawHtmlPage( UserIdentity $user ): bool {
		return $this->userHasAllRights( $user, 'editsitecss', 'editsitejs' );
	}

	/**
	 * Add temporary user rights, only valid for the current function scope.
	 *
	 * This is meant for making it possible to programatically trigger certain actions that
	 * the user wouldn't be able to trigger themselves; e.g. allow users without the bot right
	 * to make bot-flagged actions through certain special pages.
	 *
	 * This returns a "scope guard" variable. Its only purpose is to be stored in a variable
	 * by the caller, which is automatically closed at the end of the function, at which point
	 * the rights are revoked again. Alternatively, you can close it earlier by consuming it
	 * via ScopedCallback::consume().
	 *
	 * @since 1.34
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
	 * Override the user permissions cache
	 *
	 * @internal For testing only
	 * @since 1.34
	 * @param User $user
	 * @param string[]|string $rights
	 */
	public function overrideUserRightsForTesting( $user, $rights = [] ) {
		if ( !defined( 'MW_PHPUNIT_TEST' ) ) {
			throw new Exception( __METHOD__ . ' can not be called outside of tests' );
		}
		$this->usersRights[ $this->getRightsCacheKey( $user ) ] =
			is_array( $rights ) ? $rights : [ $rights ];
	}

}
