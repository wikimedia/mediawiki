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

use InvalidArgumentException;
use LogicException;
use MediaWiki\Actions\ActionFactory;
use MediaWiki\Block\AbstractBlock;
use MediaWiki\Block\Block;
use MediaWiki\Block\BlockErrorFormatter;
use MediaWiki\Block\BlockManager;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Context\IContextSource;
use MediaWiki\Context\RequestContext;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\MainConfigNames;
use MediaWiki\Message\Message;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageReference;
use MediaWiki\Page\RedirectLookup;
use MediaWiki\Request\WebRequest;
use MediaWiki\Session\SessionManager;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\SpecialPage\SpecialPageFactory;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleFormatter;
use MediaWiki\User\TempUser\TempUserConfig;
use MediaWiki\User\User;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserGroupManager;
use MediaWiki\User\UserGroupMembership;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityLookup;
use PermissionsError;
use StatusValue;
use Wikimedia\Message\MessageSpecifier;
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
		MainConfigNames::RateLimits,
		MainConfigNames::ImplicitRights,
	];

	private ServiceOptions $options;
	private SpecialPageFactory $specialPageFactory;
	private NamespaceInfo $nsInfo;
	private GroupPermissionsLookup $groupPermissionsLookup;
	private UserGroupManager $userGroupManager;
	private BlockManager $blockManager;
	private BlockErrorFormatter $blockErrorFormatter;
	private HookRunner $hookRunner;
	private UserIdentityLookup $userIdentityLookup;
	private RedirectLookup $redirectLookup;
	private RestrictionStore $restrictionStore;
	private TitleFormatter $titleFormatter;
	private TempUserConfig $tempUserConfig;
	private UserFactory $userFactory;
	private ActionFactory $actionFactory;

	/** @var string[]|null Cached results of getAllPermissions() */
	private $allRights;

	/** @var string[]|null Cached results of getImplicitRights() */
	private $implicitRights;

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
	 * Array of core rights.
	 * Each of these should have a corresponding message of the form
	 * "right-$right".
	 * @showinitializer
	 */
	private const CORE_RIGHTS = [
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
		'read',
		'renameuser',
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
	];

	/**
	 * List of implicit rights.
	 * These should not have a corresponding message of the form
	 * "right-$right".
	 * @showinitializer
	 */
	private const CORE_IMPLICIT_RIGHTS = [
		'renderfile',
		'renderfile-nonstandard',
		'stashedit',
		'stashbasehtml',
		'mailpassword',
		'changeemail',
		'confirmemail',
		'linkpurge',
		'purge',
	];

	public function __construct(
		ServiceOptions $options,
		SpecialPageFactory $specialPageFactory,
		NamespaceInfo $nsInfo,
		GroupPermissionsLookup $groupPermissionsLookup,
		UserGroupManager $userGroupManager,
		BlockManager $blockManager,
		BlockErrorFormatter $blockErrorFormatter,
		HookContainer $hookContainer,
		UserIdentityLookup $userIdentityLookup,
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
		$this->blockManager = $blockManager;
		$this->blockErrorFormatter = $blockErrorFormatter;
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->userIdentityLookup = $userIdentityLookup;
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
		return $this->getPermissionStatus( $action, $user, $page, $rigor, true )->isGood();
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
	 * This *does not* check throttles (User::pingLimiter()). If that's desired, use the Authority
	 * interface methods instead.
	 *
	 * @deprecated since 1.43 Use getPermissionStatus() instead.
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
	 * @return array[] Permission errors.
	 *   Each entry contains valid arguments for wfMessage() / MessageLocalizer::msg().
	 *   The format is *different* from the normal "legacy error array", as used by
	 *   Status::getErrorsArray() or PermissionStatus::toLegacyErrorArray():
	 *   the first element of each entry can be a MessageSpecifier, not just a string.
	 * @phan-return non-empty-array[]
	 */
	public function getPermissionErrors(
		$action,
		User $user,
		LinkTarget $page,
		$rigor = self::RIGOR_SECURE,
		$ignoreErrors = []
	): array {
		$status = $this->getPermissionStatus( $action, $user, $page, $rigor );
		$result = [];

		// Produce a result in the weird format used by this function
		foreach ( $status->getErrors() as [ 'message' => $keyOrMsg, 'params' => $params ] ) {
			$key = $keyOrMsg instanceof MessageSpecifier ? $keyOrMsg->getKey() : $keyOrMsg;
			// Remove the errors being ignored.
			if ( !in_array( $key, $ignoreErrors ) ) {
				$result[] = [ $keyOrMsg, ...$params ];
			}
		}
		return $result;
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
		$status = $this->getPermissionStatus(
			$action, $user, $page, $rigor );
		if ( $status->hasMessagesExcept( ...$ignoreErrors ) ) {
			throw new PermissionsError( $action, $status );
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
		return (bool)$this->getApplicableBlock(
			'edit',
			$user,
			$fromReplica ? self::RIGOR_FULL : self::RIGOR_SECURE,
			$page,
			$user->getRequest()
		);
	}

	/**
	 * Can $user perform $action on a page?
	 *
	 * This *does not* check throttles (User::pingLimiter()). If that's desired, use the Authority
	 * interface methods instead.
	 *
	 * @param string $action Action that permission needs to be checked for
	 * @param User $user User to check
	 * @param LinkTarget $page
	 * @param string $rigor One of PermissionManager::RIGOR_ constants
	 *   - RIGOR_QUICK  : does cheap permission checks from replica DBs (usable for GUI creation)
	 *   - RIGOR_FULL   : does cheap and expensive checks possibly from a replica DB
	 *   - RIGOR_SECURE : does cheap and expensive checks, using the primary DB as needed
	 * @param bool $short Set this to true to stop after the first permission error.
	 * @return PermissionStatus Permission errors as a status.
	 *   Check `$status->isGood()` to tell if the user can perform the action.
	 *   Use `$status->getMessages()` to display errors if the status is not good.
	 */
	public function getPermissionStatus(
		$action,
		User $user,
		LinkTarget $page,
		$rigor = self::RIGOR_SECURE,
		$short = false
	): PermissionStatus {
		if ( !in_array( $rigor, [ self::RIGOR_QUICK, self::RIGOR_FULL, self::RIGOR_SECURE ] ) ) {
			throw new InvalidArgumentException( "Invalid rigor parameter '$rigor'." );
		}

		// With RIGOR_QUICK we can assume automatic account creation will
		// occur. At a higher rigor level, the caller is required to opt
		// in by either passing in a temp placeholder user or by actually
		// creating the account.
		if ( $rigor === self::RIGOR_QUICK
			&& !$user->isRegistered()
			&& $this->tempUserConfig->isAutoCreateAction( $action )
		) {
			$user = $this->userFactory->newTempPlaceholder();
		}

		# Read has special handling
		if ( $action === 'read' ) {
			$checks = [
				[ $this, 'checkPermissionHooks' ],
				[ $this, 'checkReadPermissions' ],
				[ $this, 'checkUserBlock' ], // for wgBlockDisablesLogin
			];
		} elseif ( $action === 'create' ) {
			# Don't call checkSpecialsAndNSPermissions, checkSiteConfigPermissions
			# or checkUserConfigPermissions here as it will lead to duplicate
			# error messages. This is okay to do since anywhere that checks for
			# create will also check for edit, and those checks are called for edit.
			$checks = [
				[ $this, 'checkQuickPermissions' ],
				[ $this, 'checkPermissionHooks' ],
				[ $this, 'checkPageRestrictions' ],
				[ $this, 'checkCascadingSourcesRestrictions' ],
				[ $this, 'checkActionPermissions' ],
				[ $this, 'checkUserBlock' ],
			];
		} else {
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

			$checks = [
				[ $this, 'checkQuickPermissions' ],
				[ $this, 'checkPermissionHooks' ],
				[ $this, 'checkSpecialsAndNSPermissions' ],
				[ $this, 'checkSiteConfigPermissions' ],
			];
			if ( !in_array( $action, $skipUserConfigActions, true ) ) {
				$checks[] = [ $this, 'checkUserConfigPermissions' ];
			}
			$checks = [
				...$checks,
				[ $this, 'checkPageRestrictions' ],
				[ $this, 'checkCascadingSourcesRestrictions' ],
				[ $this, 'checkActionPermissions' ],
				[ $this, 'checkUserBlock' ]
			];
		}

		$status = PermissionStatus::newEmpty();
		foreach ( $checks as $method ) {
			$method( $action, $user, $status, $rigor, $short, $page );

			if ( $short && !$status->isGood() ) {
				break;
			}
		}
		if ( !$status->isGood() ) {
			$errors = $status->toLegacyErrorArray();
			$this->hookRunner->onPermissionErrorAudit( $page, $user, $action, $rigor, $errors );
		}

		return $status;
	}

	/**
	 * Check various permission hooks
	 *
	 * @param string $action The action to check
	 * @param User $user User to check
	 * @param PermissionStatus $status Current errors
	 * @param string $rigor One of PermissionManager::RIGOR_ constants
	 *   - RIGOR_QUICK  : does cheap permission checks from replica DBs (usable for GUI creation)
	 *   - RIGOR_FULL   : does cheap and expensive checks possibly from a replica DB
	 *   - RIGOR_SECURE : does cheap and expensive checks, using the primary DB as needed
	 * @param bool $short Short circuit on first error
	 * @param LinkTarget $page
	 */
	private function checkPermissionHooks(
		$action,
		User $user,
		PermissionStatus $status,
		$rigor,
		$short,
		LinkTarget $page
	): void {
		// TODO: remove when LinkTarget usage will expand further
		$title = Title::newFromLinkTarget( $page );
		// Use getUserPermissionsErrors instead
		$result = '';
		if ( !$this->hookRunner->onUserCan( $title, $user, $action, $result ) ) {
			if ( !$result ) {
				$status->fatal( 'badaccess-group0' );
			}
			return;
		}
		// Check getUserPermissionsErrors hook
		if ( !$this->hookRunner->onGetUserPermissionsErrors( $title, $user, $action, $result ) ) {
			$this->resultToStatus( $status, $result );
		}
		// Check getUserPermissionsErrorsExpensive hook
		if (
			$rigor !== self::RIGOR_QUICK
			&& !( $short && !$status->isGood() )
			&& !$this->hookRunner->onGetUserPermissionsErrorsExpensive(
				$title, $user, $action, $result )
		) {
			$this->resultToStatus( $status, $result );
		}
	}

	/**
	 * Add the resulting error code to the errors array
	 *
	 * @param PermissionStatus $status Current errors
	 * @param array|string|MessageSpecifier|false $result Result of errors
	 */
	private function resultToStatus( PermissionStatus $status, $result ): void {
		if ( is_array( $result ) && count( $result ) && !is_array( $result[0] ) ) {
			// A single array representing an error
			$status->fatal( ...$result );
		} elseif ( is_array( $result ) && count( $result ) && is_array( $result[0] ) ) {
			// A nested array representing multiple errors
			foreach ( $result as $result1 ) {
				$this->resultToStatus( $status, $result1 );
			}
		} elseif ( is_string( $result ) && $result !== '' ) {
			// A string representing a message-id
			$status->fatal( $result );
		} elseif ( $result instanceof MessageSpecifier ) {
			// A message specifier representing an error
			$status->fatal( $result );
		} elseif ( $result === false ) {
			// a generic "We don't want them to do that"
			$status->fatal( 'badaccess-group0' );
		}
		// If we got here, $results is the empty array or empty string, which mean no errors.
	}

	/**
	 * Check that the user is allowed to read this page.
	 *
	 * @param string $action The action to check
	 * @param User $user User to check
	 * @param PermissionStatus $status Current errors
	 * @param string $rigor One of PermissionManager::RIGOR_ constants
	 *   - RIGOR_QUICK  : does cheap permission checks from replica DBs (usable for GUI creation)
	 *   - RIGOR_FULL   : does cheap and expensive checks possibly from a replica DB
	 *   - RIGOR_SECURE : does cheap and expensive checks, using the primary DB as needed
	 * @param bool $short Short circuit on first error
	 * @param LinkTarget $page
	 */
	private function checkReadPermissions(
		$action,
		User $user,
		PermissionStatus $status,
		$rigor,
		$short,
		LinkTarget $page
	): void {
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
		} elseif ( $this->isSameSpecialPage( 'Userlogin', $page )
			|| $this->isSameSpecialPage( 'PasswordReset', $page )
			|| $this->isSameSpecialPage( 'Userlogout', $page )
		) {
			// Always grant access to the login page.
			// Even anons need to be able to log in.
			$allowed = true;
		} elseif ( $this->isSameSpecialPage( 'RunJobs', $page ) ) {
			// relies on HMAC key signature alone
			$allowed = true;
		} elseif ( is_array( $whiteListRead ) && count( $whiteListRead ) ) {
			// Time to check the whitelist
			// Only do these checks if there's something to check against
			$name = $title->getPrefixedText();
			$dbName = $title->getPrefixedDBkey();

			// Check for explicit whitelisting with and without underscores
			if ( in_array( $name, $whiteListRead, true )
				|| in_array( $dbName, $whiteListRead, true )
			) {
				$allowed = true;
			} elseif ( $page->getNamespace() === NS_MAIN ) {
				// Old settings might have the title prefixed with
				// a colon for main-namespace pages
				if ( in_array( ':' . $name, $whiteListRead ) ) {
					$allowed = true;
				}
			} elseif ( $title->isSpecialPage() ) {
				// If it's a special page, ditch the subpage bit and check again
				$name = $title->getDBkey();
				[ $name, /* $subpage */ ] =
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
			&& $whitelistReadRegexp
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
				$this->missingPermissionError( $action, $short, $status );
			}
		}
	}

	/**
	 * Add an error to the status when an action isn't allowed to be performed.
	 *
	 * @param string $action The action to check
	 * @param bool $short Short circuit on first error
	 * @param PermissionStatus $status
	 */
	private function missingPermissionError( string $action, bool $short, PermissionStatus $status ): void {
		// We avoid expensive display logic for quickUserCan's and such
		if ( $short ) {
			$status->fatal( 'badaccess-group0' );
			return;
		}

		// TODO: it would be a good idea to replace the method below with something else like
		// maybe callback injection
		$context = RequestContext::getMain();
		$fatalStatus = $this->newFatalPermissionDeniedStatus( $action, $context );
		$status->merge( $fatalStatus );
		$statusPermission = $fatalStatus->getPermission();
		if ( $statusPermission ) {
			$status->setPermission( $statusPermission );
		}
	}

	/**
	 * Factory function for fatal permission-denied errors
	 *
	 * @internal for use by UserAuthority
	 *
	 * @param string $permission User right required
	 * @param IContextSource $context
	 *
	 * @return PermissionStatus
	 */
	public function newFatalPermissionDeniedStatus( $permission, IContextSource $context ): StatusValue {
		$groups = [];
		foreach ( $this->groupPermissionsLookup->getGroupsWithPermission( $permission ) as $group ) {
			$groups[] = UserGroupMembership::getLinkWiki( $group, $context );
		}

		if ( $groups ) {
			return PermissionStatus::newFatal(
				'badaccess-groups',
				Message::listParam( $groups, 'comma' ),
				count( $groups )
			);
		}

		$status = PermissionStatus::newFatal( 'badaccess-group0' );
		$status->setPermission( $permission );
		return $status;
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
			[ $pageName ] = $this->specialPageFactory->resolveAlias( $page->getDBkey() );
			if ( $name === $pageName ) {
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
	 * @param PermissionStatus $status Current errors
	 * @param string $rigor One of PermissionManager::RIGOR_ constants
	 *   - RIGOR_QUICK  : does cheap permission checks from replica DBs (usable for GUI creation)
	 *   - RIGOR_FULL   : does cheap and expensive checks possibly from a replica DB
	 *   - RIGOR_SECURE : does cheap and expensive checks, using the primary DB as needed
	 * @param bool $short Short circuit on first error
	 * @param LinkTarget $page
	 */
	private function checkUserBlock(
		$action,
		User $user,
		PermissionStatus $status,
		$rigor,
		$short,
		LinkTarget $page
	): void {
		$block = $this->getApplicableBlock(
			$action,
			$user,
			$rigor,
			$page,
			$user->getRequest()
		);

		if ( $block ) {
			// @todo FIXME: Pass the relevant context into this function.
			$context = RequestContext::getMain();
			$messages = $this->blockErrorFormatter->getMessages(
				$block,
				$user,
				$context->getRequest()->getIP()
			);

			foreach ( $messages as $message ) {
				// TODO: We can pass $message directly once getPermissionErrors() is removed.
				// For now we store the message key as a string here out of overabundance of caution,
				// because there is a test case verifying that block messages use strings in that format.
				$status->fatal( $message->getKey(), ...$message->getParams() );
			}
		}
	}

	/**
	 * Return the Block object applicable for the given permission check, if any.
	 *
	 * @internal for use by UserAuthority only
	 *
	 * @param string $action The action to check
	 * @param User $user User to check
	 * @param string $rigor One of PermissionManager::RIGOR_ constants
	 *   - RIGOR_QUICK  : does cheap permission checks from replica DBs (usable for GUI creation)
	 *   - RIGOR_FULL   : does cheap and expensive checks possibly from a replica DB
	 *   - RIGOR_SECURE : does cheap and expensive checks, using the primary DB as needed
	 * @param LinkTarget|PageReference|null $page
	 * @param WebRequest|null $request The request to get the IP and cookies
	 *   from. If this is null, IP and cookie blocks will not be checked.
	 * @return ?Block
	 */
	public function getApplicableBlock(
		string $action,
		User $user,
		string $rigor,
		$page,
		?WebRequest $request
	): ?Block {
		// Unblocking handled in SpecialUnblock
		if ( $rigor === self::RIGOR_QUICK || in_array( $action, [ 'unblock' ] ) ) {
			return null;
		}

		// Optimize for a very common case
		if ( $action === 'read' && !$this->options->get( MainConfigNames::BlockDisablesLogin ) ) {
			return null;
		}

		// Implicit rights aren't blockable (T350117, T350202).
		if ( in_array( $action, $this->getImplicitRights(), true ) ) {
			return null;
		}

		$useReplica = $rigor !== self::RIGOR_SECURE;
		$isExempt = $this->userHasRight( $user, 'ipblock-exempt' );
		$requestIfNotExempt = $isExempt ? null : $request;

		// Create account blocks are implemented separately due to weird IP exemption rules
		if ( in_array( $action, [ 'createaccount', 'autocreateaccount' ], true ) ) {
			return $this->blockManager->getCreateAccountBlock(
				$user,
				$requestIfNotExempt,
				$useReplica
			);
		}

		$block = $this->blockManager->getBlock( $user, $requestIfNotExempt, $useReplica );
		if ( !$block ) {
			return null;
		}
		$userIsHidden = $block->getHideName();

		// Remove elements from the block that explicitly allow the action
		// (like "read" or "upload").
		$block = $this->blockManager->filter(
			$block,
			static function ( AbstractBlock $originalBlock ) use ( $action ) {
				// Remove the block if it explicitly allows the action
				return $originalBlock->appliesToRight( $action ) !== false;
			}
		);
		if ( !$block ) {
			return null;
		}

		// Convert the input page to a Title
		$targetTitle = null;
		if ( $page ) {
			$targetTitle = $page instanceof PageReference ?
				Title::castFromPageReference( $page ) :
				Title::castFromLinkTarget( $page );

			if ( !$targetTitle->canExist() ) {
				$targetTitle = null;
			}
		}

		// What gets passed into this method is a user right, not an action name.
		// There is no way to instantiate an action by restriction. However, this
		// will get the action where the restriction is the same. This may result
		// in actions being blocked that shouldn't be.
		$actionInfo = $this->actionFactory->getActionInfo( $action, $targetTitle );

		// Ensure that the retrieved action matches the restriction.
		if ( $actionInfo && $actionInfo->getRestriction() !== $action ) {
			$actionInfo = null;
		}

		// Return null if the action does not require an unblocked user.
		// If no ActionInfo is returned, assume that the action requires unblock
		// which is the default.
		// NOTE: We may get null here even for known actions, if a wiki's main page
		// is set to a special page, e.g. Special:MyLanguage/Main_Page (T348451, T346036).
		if ( $actionInfo && !$actionInfo->requiresUnblock() ) {
			return null;
		}

		// Remove elements from the block that do not apply to the specific page
		if ( $targetTitle ) {
			$targetIsUserTalk = !$userIsHidden && $targetTitle->equals( $user->getTalkPage() );
			$block = $this->blockManager->filter(
				$block,
				static function ( AbstractBlock $originalBlock )
				use ( $action, $targetTitle, $targetIsUserTalk ) {
					if ( $originalBlock->appliesToRight( $action ) ) {
						// An action block takes precedence over appliesToTitle().
						// Block::appliesToRight('edit') always returns null,
						// allowing title-based exemptions to take effect.
						return true;
					} elseif ( $targetIsUserTalk ) {
						// Special handling for a user's own talk page. The block is not aware
						// of the user, so this must be done here.
						return $originalBlock->appliesToUsertalk( $targetTitle );
					} else {
						return $originalBlock->appliesToTitle( $targetTitle );
					}
				}
			);
		}

		if ( $targetTitle && $block
			&& $block instanceof AbstractBlock // for phan
		) {
			// Allow extensions to let a blocked user access a particular page
			$allowUsertalk = $block->isUsertalkEditAllowed();
			$blocked = true;
			$this->hookRunner->onUserIsBlockedFrom( $user, $targetTitle, $blocked, $allowUsertalk );
			if ( !$blocked ) {
				$block = null;
			}
		}
		return $block;
	}

	/**
	 * Run easy-to-test (or "quick") permissions checks for a given action.
	 *
	 * @param string $action The action to check
	 * @param User $user User to check
	 * @param PermissionStatus $status Current errors
	 * @param string $rigor One of PermissionManager::RIGOR_ constants
	 *   - RIGOR_QUICK  : does cheap permission checks from replica DBs (usable for GUI creation)
	 *   - RIGOR_FULL   : does cheap and expensive checks possibly from a replica DB
	 *   - RIGOR_SECURE : does cheap and expensive checks, using the primary DB as needed
	 * @param bool $short Short circuit on first error
	 * @param LinkTarget $page
	 */
	private function checkQuickPermissions(
		$action,
		User $user,
		PermissionStatus $status,
		$rigor,
		$short,
		LinkTarget $page
	): void {
		// TODO: remove when LinkTarget usage will expand further
		$title = Title::newFromLinkTarget( $page );

		// This method is always called first, so $status is guaranteed to be empty, so we can
		// just pass an empty $errors array, instead of converting it to the legacy format and back.
		$errors = [];
		if ( !$this->hookRunner->onTitleQuickPermissions( $title, $user, $action,
			$errors, $rigor !== self::RIGOR_QUICK, $short )
		) {
			// $errors is an array of results, not a result, but resultToStatus() handles
			// arrays of arrays with recursion so this will work
			$this->resultToStatus( $status, $errors );
			return;
		}

		$isSubPage =
			$this->nsInfo->hasSubpages( $title->getNamespace() ) &&
			strpos( $title->getText(), '/' ) !== false;

		if ( $action === 'create' ) {
			if (
				( $this->nsInfo->isTalk( $title->getNamespace() ) &&
					!$this->userHasRight( $user, 'createtalk' ) ) ||
				( !$this->nsInfo->isTalk( $title->getNamespace() ) &&
					!$this->userHasRight( $user, 'createpage' ) )
			) {
				$status->fatal( $user->isNamed() ? 'nocreate-loggedin' : 'nocreatetext' );
			}
		} elseif ( $action === 'move' ) {
			if ( !$this->userHasRight( $user, 'move-rootuserpages' )
				&& $title->getNamespace() === NS_USER && !$isSubPage
			) {
				// Show user page-specific message only if the user can move other pages
				$status->fatal( 'cant-move-user-page' );
			}

			// Check if user is allowed to move files if it's a file
			if ( $title->getNamespace() === NS_FILE &&
				!$this->userHasRight( $user, 'movefile' )
			) {
				$status->fatal( 'movenotallowedfile' );
			}

			// Check if user is allowed to move category pages if it's a category page
			if ( $title->getNamespace() === NS_CATEGORY &&
				!$this->userHasRight( $user, 'move-categorypages' )
			) {
				$status->fatal( 'cant-move-category-page' );
			}

			if ( !$this->userHasRight( $user, 'move' ) ) {
				// User can't move anything
				$userCanMove = $this->groupPermissionsLookup
					->groupHasPermission( 'user', 'move' );
				$autoconfirmedCanMove = $this->groupPermissionsLookup
					->groupHasPermission( 'autoconfirmed', 'move' );
				if ( $user->isAnon()
					&& ( $userCanMove || $autoconfirmedCanMove )
				) {
					// custom message if logged-in users without any special rights can move
					$status->fatal( 'movenologintext' );
				} elseif ( $user->isTemp() && $autoconfirmedCanMove ) {
					// Temp user may be able to move if they log in as a proper account
					$status->fatal( 'movenologintext' );
				} else {
					$status->fatal( 'movenotallowed' );
				}
			}
		} elseif ( $action === 'move-target' ) {
			if ( !$this->userHasRight( $user, 'move' ) ) {
				// User can't move anything
				$status->fatal( 'movenotallowed' );
			} elseif ( !$this->userHasRight( $user, 'move-rootuserpages' )
				&& $title->getNamespace() === NS_USER
				&& !$isSubPage
			) {
				// Show user page-specific message only if the user can move other pages
				$status->fatal( 'cant-move-to-user-page' );
			} elseif ( !$this->userHasRight( $user, 'move-categorypages' )
				&& $title->getNamespace() === NS_CATEGORY
			) {
				// Show category page-specific message only if the user can move other pages
				$status->fatal( 'cant-move-to-category-page' );
			}
		} elseif ( $action === 'autocreateaccount' ) {
			// createaccount implies autocreateaccount
			if ( !$this->userHasAnyRight( $user, 'autocreateaccount', 'createaccount' ) ) {
				$this->missingPermissionError( $action, $short, $status );
			}
		} elseif ( !$this->userHasRight( $user, $action ) ) {
			$this->missingPermissionError( $action, $short, $status );
		}
	}

	/**
	 * Check for any page_restrictions table requirements on this page.
	 *
	 * If the page has multiple restrictions, the user must have
	 * all of those rights to perform the action in question.
	 *
	 * @param string $action The action to check
	 * @param UserIdentity $user User to check
	 * @param PermissionStatus $status Current errors
	 * @param string $rigor One of PermissionManager::RIGOR_ constants
	 *   - RIGOR_QUICK  : does cheap permission checks from replica DBs (usable for GUI creation)
	 *   - RIGOR_FULL   : does cheap and expensive checks possibly from a replica DB
	 *   - RIGOR_SECURE : does cheap and expensive checks, using the primary DB as needed
	 * @param bool $short Short circuit on first error
	 * @param LinkTarget $page
	 */
	private function checkPageRestrictions(
		$action,
		UserIdentity $user,
		PermissionStatus $status,
		$rigor,
		$short,
		LinkTarget $page
	): void {
		// TODO: remove & rework upon further use of LinkTarget
		$title = Title::newFromLinkTarget( $page );
		foreach ( $this->restrictionStore->getRestrictions( $title, $action ) as $right ) {
			// Backwards compatibility, rewrite sysop -> editprotected
			if ( $right === 'sysop' ) {
				$right = 'editprotected';
			}
			// Backwards compatibility, rewrite autoconfirmed -> editsemiprotected
			if ( $right === 'autoconfirmed' ) {
				$right = 'editsemiprotected';
			}
			if ( $right == '' ) {
				continue;
			}
			if ( !$this->userHasRight( $user, $right ) ) {
				$status->fatal( 'protectedpagetext', $right, $action );
			} elseif ( $this->restrictionStore->areRestrictionsCascading( $title ) &&
				!$this->userHasRight( $user, 'protect' )
			) {
				$status->fatal( 'protectedpagetext', 'protect', $action );
			}
		}
	}

	/**
	 * Check restrictions on cascading pages.
	 *
	 * @param string $action The action to check
	 * @param UserIdentity $user User to check
	 * @param PermissionStatus $status Current errors
	 * @param string $rigor One of PermissionManager::RIGOR_ constants
	 *   - RIGOR_QUICK  : does cheap permission checks from replica DBs (usable for GUI creation)
	 *   - RIGOR_FULL   : does cheap and expensive checks possibly from a replica DB
	 *   - RIGOR_SECURE : does cheap and expensive checks, using the primary DB as needed
	 * @param bool $short Short circuit on first error
	 * @param LinkTarget $page
	 */
	private function checkCascadingSourcesRestrictions(
		$action,
		UserIdentity $user,
		PermissionStatus $status,
		$rigor,
		$short,
		LinkTarget $page
	): void {
		// TODO: remove & rework upon further use of LinkTarget
		$title = Title::newFromLinkTarget( $page );

		if ( $rigor !== self::RIGOR_QUICK && !$title->isUserConfigPage() ) {
			[ $sources, $restrictions, $tlSources, $ilSources ] = $this->restrictionStore
				->getCascadeProtectionSources( $title );

			// If the file Wikitext isn't transcluded then we
			// don't care about edit cascade restrictions for edit action
			if ( $action === 'edit' && $page->getNamespace() === NS_FILE && !$tlSources ) {
				return;
			}

			// For the purposes of cascading protection, edit restrictions should apply to uploads or moves
			// Thus remap upload and move to edit
			// Unless the file content itself is not transcluded
			if ( $ilSources && ( $action === 'upload' || $action === 'move' ) ) {
				$restrictedAction = 'edit';
			} else {
				$restrictedAction = $action;
			}

			// Cascading protection depends on more than this page...
			// Several cascading protected pages may include this page...
			// Check each cascading level
			// This is only for protection restrictions, not for all actions
			if ( isset( $restrictions[$restrictedAction] ) ) {
				foreach ( $restrictions[$restrictedAction] as $right ) {
					// Backwards compatibility, rewrite sysop -> editprotected
					if ( $right === 'sysop' ) {
						$right = 'editprotected';
					}
					// Backwards compatibility, rewrite autoconfirmed -> editsemiprotected
					if ( $right === 'autoconfirmed' ) {
						$right = 'editsemiprotected';
					}
					if ( $right != '' && !$this->userHasAllRights( $user, 'protect', $right ) ) {
						$wikiPages = '';
						foreach ( $sources as $pageIdentity ) {
							$wikiPages .= '* [[:' . $this->titleFormatter->getPrefixedText( $pageIdentity ) . "]]\n";
						}
						$status->fatal( 'cascadeprotected', count( $sources ), $wikiPages, $action );
					}
				}
			}
		}
	}

	/**
	 * Check action permissions not already checked in checkQuickPermissions
	 *
	 * @param string $action The action to check
	 * @param User $user User to check
	 * @param PermissionStatus $status Current errors
	 * @param string $rigor One of PermissionManager::RIGOR_ constants
	 *   - RIGOR_QUICK  : does cheap permission checks from replica DBs (usable for GUI creation)
	 *   - RIGOR_FULL   : does cheap and expensive checks possibly from a replica DB
	 *   - RIGOR_SECURE : does cheap and expensive checks, using the primary DB as needed
	 * @param bool $short Short circuit on first error
	 * @param LinkTarget $page
	 */
	private function checkActionPermissions(
		$action,
		User $user,
		PermissionStatus $status,
		$rigor,
		$short,
		LinkTarget $page
	): void {
		// TODO: remove & rework upon further use of LinkTarget
		$title = Title::newFromLinkTarget( $page );

		if ( $rigor !== self::RIGOR_QUICK && !defined( 'MW_NO_SESSION' ) ) {
			$sessionRestrictions = $user->getRequest()->getSession()->getRestrictions();
			if ( $sessionRestrictions ) {
				$userCan = $sessionRestrictions->userCan( $title );
				if ( !$userCan->isOK() ) {
					$status->merge( $userCan );
				}
			}
		}

		if ( $action === 'protect' ) {
			if ( !$this->getPermissionStatus( 'edit', $user, $title, $rigor, true )->isGood() ) {
				// If they can't edit, they shouldn't protect.
				$status->fatal( 'protect-cantedit' );
			}
		} elseif ( $action === 'create' ) {
			$createProtection = $this->restrictionStore->getCreateProtection( $title );
			if ( $createProtection ) {
				if ( $createProtection['permission'] == ''
					|| !$this->userHasRight( $user, $createProtection['permission'] )
				) {
					$protectUserIdentity = $this->userIdentityLookup
						->getUserIdentityByUserId( $createProtection['user'] );
					$status->fatal(
						'titleprotected',
						$protectUserIdentity ? $protectUserIdentity->getName() : '',
						$createProtection['reason']
					);
				}
			}
		} elseif ( $action === 'move' ) {
			// Check for immobile pages
			if ( !$this->nsInfo->isMovable( $title->getNamespace() ) ) {
				// Specific message for this case
				$nsText = $title->getNsText();
				if ( $nsText === '' ) {
					$nsText = wfMessage( 'blanknamespace' )->text();
				}
				$status->fatal( 'immobile-source-namespace', $nsText );
			} elseif ( !$title->isMovable() ) {
				// Less specific message for rarer cases
				$status->fatal( 'immobile-source-page' );
			}
		} elseif ( $action === 'move-target' ) {
			if ( !$this->nsInfo->isMovable( $title->getNamespace() ) ) {
				$nsText = $title->getNsText();
				if ( $nsText === '' ) {
					$nsText = wfMessage( 'blanknamespace' )->text();
				}
				$status->fatal( 'immobile-target-namespace', $nsText );
			} elseif ( !$title->isMovable() ) {
				$status->fatal( 'immobile-target-page' );
			}
		} elseif ( $action === 'delete' || $action === 'delete-redirect' ) {
			$tempStatus = PermissionStatus::newEmpty();
			$this->checkPageRestrictions( 'edit', $user, $tempStatus, $rigor, true, $title );
			if ( $tempStatus->isGood() ) {
				$this->checkCascadingSourcesRestrictions( 'edit',
					$user, $tempStatus, $rigor, true, $title );
			}
			if ( !$tempStatus->isGood() ) {
				// If protection keeps them from editing, they shouldn't be able to delete.
				$status->fatal( 'deleteprotected' );
			}
			if ( $rigor !== self::RIGOR_QUICK
				&& $action === 'delete'
				&& $this->options->get( MainConfigNames::DeleteRevisionsLimit )
				&& !$this->userCan( 'bigdelete', $user, $title )
				&& $title->isBigDeletion()
			) {
				// NOTE: This check is deprecated since 1.37, see T288759
				$status->fatal(
					'delete-toobig',
					Message::numParam( $this->options->get( MainConfigNames::DeleteRevisionsLimit ) )
				);
			}
		} elseif ( $action === 'undelete' ) {
			if ( !$this->getPermissionStatus( 'edit', $user, $title, $rigor, true )->isGood() ) {
				// Undeleting implies editing
				$status->fatal( 'undelete-cantedit' );
			}
			if ( !$title->exists()
				&& !$this->getPermissionStatus( 'create', $user, $title, $rigor, true )->isGood()
			) {
				// Undeleting where nothing currently exists implies creating
				$status->fatal( 'undelete-cantcreate' );
			}
		} elseif ( $action === 'edit' ) {
			if ( $this->options->get( MainConfigNames::EmailConfirmToEdit )
				&& !$user->isEmailConfirmed()
			) {
				$status->fatal( 'confirmedittext' );
			}

			if ( !$title->exists() ) {
				$status->merge(
					$this->getPermissionStatus(
						'create',
						$user,
						$title,
						$rigor,
						true
					)
				);
			}
		}
	}

	/**
	 * Check permissions on special pages & namespaces
	 *
	 * @param string $action The action to check
	 * @param UserIdentity $user User to check
	 * @param PermissionStatus $status Current errors
	 * @param string $rigor One of PermissionManager::RIGOR_ constants
	 *   - RIGOR_QUICK  : does cheap permission checks from replica DBs (usable for GUI creation)
	 *   - RIGOR_FULL   : does cheap and expensive checks possibly from a replica DB
	 *   - RIGOR_SECURE : does cheap and expensive checks, using the primary DB as needed
	 * @param bool $short Short circuit on first error
	 * @param LinkTarget $page
	 */
	private function checkSpecialsAndNSPermissions(
		$action,
		UserIdentity $user,
		PermissionStatus $status,
		$rigor,
		$short,
		LinkTarget $page
	): void {
		// TODO: remove & rework upon further use of LinkTarget
		$title = Title::newFromLinkTarget( $page );

		// Only 'createaccount' can be performed on special pages,
		// which don't actually exist in the DB.
		if ( $title->getNamespace() === NS_SPECIAL
			&& !in_array( $action, [ 'createaccount', 'autocreateaccount' ], true )
		) {
			$status->fatal( 'ns-specialprotected' );
		}

		// Check $wgNamespaceProtection for restricted namespaces
		if ( $this->isNamespaceProtected( $title->getNamespace(), $user )
			// Allow admins and oversighters to view deleted content, even if they
			// cannot restore it. See T362536.
			&& !in_array( $action, [ 'deletedhistory', 'deletedtext', 'viewsuppressed' ], true )
		) {
			$ns = $title->getNamespace() === NS_MAIN ?
				wfMessage( 'nstab-main' )->text() : $title->getNsText();
			if ( $title->getNamespace() === NS_MEDIAWIKI ) {
				$status->fatal( 'protectedinterface', $action );
			} else {
				$status->fatal( 'namespaceprotected', $ns, $action );
			}
		}
	}

	/**
	 * Check sitewide CSS/JSON/JS permissions
	 *
	 * @param string $action The action to check
	 * @param UserIdentity $user User to check
	 * @param PermissionStatus $status Current errors
	 * @param string $rigor One of PermissionManager::RIGOR_ constants
	 *   - RIGOR_QUICK  : does cheap permission checks from replica DBs (usable for GUI creation)
	 *   - RIGOR_FULL   : does cheap and expensive checks possibly from a replica DB
	 *   - RIGOR_SECURE : does cheap and expensive checks, using the primary DB as needed
	 * @param bool $short Short circuit on first error
	 * @param LinkTarget $page
	 */
	private function checkSiteConfigPermissions(
		$action,
		UserIdentity $user,
		PermissionStatus $status,
		$rigor,
		$short,
		LinkTarget $page
	): void {
		// TODO: remove & rework upon further use of LinkTarget
		$title = Title::newFromLinkTarget( $page );

		if ( $action === 'patrol' ) {
			return;
		}

		if ( in_array( $action, [ 'deletedhistory', 'deletedtext', 'viewsuppressed' ], true ) ) {
			// Allow admins and oversighters to view deleted content, even if they
			// cannot restore it. See T202989
			// Not using the same handling in `getPermissionStatus` as the checks
			// for skipping `checkUserConfigPermissions` since normal admins can delete
			// user scripts, but not sitewide scripts
			return;
		}

		// Sitewide CSS/JSON/JS/RawHTML changes, like all NS_MEDIAWIKI changes, also require the
		// editinterface right. That's implemented as a restriction so no check needed here.
		if ( $title->isSiteCssConfigPage() && !$this->userHasRight( $user, 'editsitecss' ) ) {
			$status->fatal( 'sitecssprotected', $action );
		} elseif ( $title->isSiteJsonConfigPage() && !$this->userHasRight( $user, 'editsitejson' ) ) {
			$status->fatal( 'sitejsonprotected', $action );
		} elseif ( $title->isSiteJsConfigPage() && !$this->userHasRight( $user, 'editsitejs' ) ) {
			$status->fatal( 'sitejsprotected', $action );
		}
		if ( $title->isRawHtmlMessage() && !$this->userCanEditRawHtmlPage( $user ) ) {
			$status->fatal( 'siterawhtmlprotected', $action );
		}
	}

	/**
	 * Check CSS/JSON/JS subpage permissions
	 *
	 * @param string $action The action to check
	 * @param UserIdentity $user User to check
	 * @param PermissionStatus $status Current errors
	 * @param string $rigor One of PermissionManager::RIGOR_ constants
	 *   - RIGOR_QUICK  : does cheap permission checks from replica DBs (usable for GUI creation)
	 *   - RIGOR_FULL   : does cheap and expensive checks possibly from a replica DB
	 *   - RIGOR_SECURE : does cheap and expensive checks, using the primary DB as needed
	 * @param bool $short Short circuit on first error
	 * @param LinkTarget $page
	 */
	private function checkUserConfigPermissions(
		$action,
		UserIdentity $user,
		PermissionStatus $status,
		$rigor,
		$short,
		LinkTarget $page
	): void {
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
				$status->fatal( 'mycustomcssprotected', $action );
			} elseif (
				$title->isUserJsonConfigPage()
				&& !$this->userHasAnyRight( $user, 'editmyuserjson', 'edituserjson' )
			) {
				$status->fatal( 'mycustomjsonprotected', $action );
			} elseif (
				$title->isUserJsConfigPage()
				&& !$this->userHasAnyRight( $user, 'editmyuserjs', 'edituserjs' )
			) {
				$status->fatal( 'mycustomjsprotected', $action );
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
					$status->fatal( 'mycustomjsredirectprotected', $action );
				}
			}
		} else {
			// Users need edituser* to edit others' CSS/JSON/JS subpages.
			// The checks to exclude deletion/suppression, which cannot be used for
			// attacks and should be excluded to avoid the situation where an
			// unprivileged user can post abusive content on their subpages
			// and only very highly privileged users could remove it,
			// are now a part of `getPermissionStatus` and this method isn't called.
			if (
				$title->isUserCssConfigPage()
				&& !$this->userHasRight( $user, 'editusercss' )
			) {
				$status->fatal( 'customcssprotected', $action );
			} elseif (
				$title->isUserJsonConfigPage()
				&& !$this->userHasRight( $user, 'edituserjson' )
			) {
				$status->fatal( 'customjsonprotected', $action );
			} elseif (
				$title->isUserJsConfigPage()
				&& !$this->userHasRight( $user, 'edituserjs' )
			) {
				$status->fatal( 'customjsprotected', $action );
			}
		}
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
		return in_array( $action, $this->getImplicitRights(), true )
			|| in_array( $action, $this->getUserPermissions( $user ), true );
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
			$userObj = $this->userFactory->newFromUserIdentity( $user );
			$rights = $this->groupPermissionsLookup->getGroupPermissions(
				$this->userGroupManager->getUserEffectiveGroups( $user )
			);
			// Hook requires a full User object
			$this->hookRunner->onUserGetRights( $userObj, $rights );

			// Deny any rights denied by the user's session, unless this
			// endpoint has no sessions.
			if ( !defined( 'MW_NO_SESSION' ) ) {
				// FIXME: $userObj->getRequest().. need to be replaced with something else
				$allowedRights = $userObj->getRequest()->getSession()->getAllowedUserRights();
				if ( $allowedRights !== null ) {
					$rights = array_intersect( $rights, $allowedRights );
				}
			}

			// Hook requires a full User object
			$this->hookRunner->onUserGetRightsRemove( $userObj, $rights );
			// Force reindexation of rights when a hook has unset one of them
			$rights = array_values( array_unique( $rights ) );

			// If BlockDisablesLogin is true, remove rights that anonymous
			// users don't have. This has to be done after the hooks so that
			// we know whether the user is exempt. (T129738)
			if (
				$userObj->isRegistered()
				&& $this->options->get( MainConfigNames::BlockDisablesLogin )
			) {
				// Stash the permissions as they are before triggering any block checks for BlockDisablesLogin
				// to avoid a potential infinite loop, since GetUserBlock handlers may themselves check
				// permissions on this user. (T384197)
				$this->usersRights[ $rightsCacheKey ] = $rights;

				$isExempt = in_array( 'ipblock-exempt', $rights, true );
				if ( $this->blockManager->getBlock(
					$userObj,
					$isExempt ? null : $userObj->getRequest()
				) ) {
					$anon = $this->userFactory->newAnonymous();
					$rights = array_intersect( $rights, $this->getUserPermissions( $anon ) );
				}
			}

			$this->usersRights[ $rightsCacheKey ] = $rights;
		} else {
			$rights = $this->usersRights[ $rightsCacheKey ];
		}
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
	 * Get a list of all permissions that can be managed through group permissions.
	 * This does not include implicit rights which are granted to all users automatically.
	 *
	 * @see getImplicitRights()
	 *
	 * @since 1.34
	 * @return string[] Array of permission names
	 */
	public function getAllPermissions(): array {
		if ( $this->allRights === null ) {
			if ( count( $this->options->get( MainConfigNames::AvailableRights ) ) ) {
				$this->allRights = array_unique( array_merge(
					self::CORE_RIGHTS,
					$this->options->get( MainConfigNames::AvailableRights )
				) );
			} else {
				$this->allRights = self::CORE_RIGHTS;
			}
			$this->hookRunner->onUserGetAllRights( $this->allRights );
		}
		return $this->allRights;
	}

	/**
	 * Get a list of implicit rights.
	 *
	 * Rights in this list should be granted to all users implicitly.
	 *
	 * Implicit rights are defined to allow rate limits to be imposed
	 * on permissions
	 *
	 * @since 1.41
	 * @return string[] Array of permission names
	 */
	public function getImplicitRights(): array {
		if ( $this->implicitRights === null ) {
			$rights = array_unique( array_merge(
				self::CORE_IMPLICIT_RIGHTS,
				$this->options->get( MainConfigNames::ImplicitRights )
			) );

			$this->implicitRights = array_diff( $rights, $this->getAllPermissions() );
		}
		return $this->implicitRights;
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
	public function getNamespaceRestrictionLevels( $index, ?UserIdentity $user = null ): array {
		if ( !isset( $this->options->get( MainConfigNames::NamespaceProtection )[$index] ) ) {
			// All levels are valid if there's no namespace restriction.
			// But still filter by user, if necessary
			$levels = $this->options->get( MainConfigNames::RestrictionLevels );
			if ( $user ) {
				$levels = array_values( array_filter( $levels, function ( $level ) use ( $user ) {
					$right = $level;
					if ( $right === 'sysop' ) {
						$right = 'editprotected'; // BC
					}
					if ( $right === 'autoconfirmed' ) {
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
			if ( $right === 'sysop' ) {
				$right = 'editprotected'; // BC
			}
			if ( $right === 'autoconfirmed' ) {
				$right = 'editsemiprotected'; // BC
			}
			if ( $right != '' ) {
				$namespaceRightGroups[$right] = $this->groupPermissionsLookup->getGroupsWithPermission( $right );
			}
		}

		// Now, go through the protection levels one by one.
		$usableLevels = [ '' ];
		foreach ( $this->options->get( MainConfigNames::RestrictionLevels ) as $level ) {
			$right = $level;
			if ( $right === 'sysop' ) {
				$right = 'editprotected'; // BC
			}
			if ( $right === 'autoconfirmed' ) {
				$right = 'editsemiprotected'; // BC
			}

			if ( $right != '' &&
				!isset( $namespaceRightGroups[$right] ) &&
				( !$user || $this->userHasRight( $user, $right ) )
			) {
				// Do any of the namespace rights imply the restriction right? (see explanation above)
				foreach ( $namespaceRightGroups as $groups ) {
					if ( !array_diff( $groups, $this->groupPermissionsLookup->getGroupsWithPermission( $right ) ) ) {
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
	 * @param UserIdentity $user
	 * @param string[]|string $rights
	 */
	public function overrideUserRightsForTesting( $user, $rights = [] ) {
		if ( !defined( 'MW_PHPUNIT_TEST' ) ) {
			throw new LogicException( __METHOD__ . ' can not be called outside of tests' );
		}
		$this->usersRights[ $this->getRightsCacheKey( $user ) ] =
			is_array( $rights ) ? $rights : [ $rights ];
	}

}
