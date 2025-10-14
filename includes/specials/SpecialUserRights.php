<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Specials;

use InvalidArgumentException;
use MediaWiki\Exception\PermissionsError;
use MediaWiki\Exception\UserBlockedError;
use MediaWiki\Html\Html;
use MediaWiki\HTMLForm\Field\HTMLUserTextField;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\Language\FormatterFactory;
use MediaWiki\Linker\Linker;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\SpecialPage\UserGroupsSpecialPage;
use MediaWiki\Status\Status;
use MediaWiki\Status\StatusFormatter;
use MediaWiki\Title\Title;
use MediaWiki\User\ActorStoreFactory;
use MediaWiki\User\MultiFormatUserIdentityLookup;
use MediaWiki\User\TempUser\TempUserConfig;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserGroupAssignmentService;
use MediaWiki\User\UserGroupManager;
use MediaWiki\User\UserGroupManagerFactory;
use MediaWiki\User\UserGroupMembership;
use MediaWiki\User\UserGroupsSpecialPageTarget;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserNamePrefixSearch;
use MediaWiki\User\UserNameUtils;
use MediaWiki\Watchlist\WatchlistManager;
use Wikimedia\Rdbms\IDBAccessObject;

/**
 * Special page to allow managing user group membership
 *
 * @ingroup SpecialPage
 */
class SpecialUserRights extends UserGroupsSpecialPage {
	/**
	 * The target of the local right-adjuster's interest.  Can be gotten from
	 * either a GET parameter or a subpage-style parameter, so have a member
	 * variable for it.
	 * @var null|string
	 */
	protected $mTarget;
	/**
	 * @var null|UserIdentity The user object of the target username or null.
	 */
	protected $mFetchedUser = null;
	/** @var bool */
	protected $isself = false;

	protected ?array $changeableGroups = null;

	private UserGroupManagerFactory $userGroupManagerFactory;

	/** @var UserGroupManager|null The UserGroupManager of the target username or null */
	private $userGroupManager = null;

	/**
	 * @var UserGroupManager The local UserGroupManager, for checking permissions of the
	 * performer. Different from $userGroupManager if the target is from an external wiki.
	 */
	private $localUserGroupManager;

	private UserNameUtils $userNameUtils;
	private UserNamePrefixSearch $userNamePrefixSearch;
	private UserFactory $userFactory;
	private WatchlistManager $watchlistManager;
	private TempUserConfig $tempUserConfig;
	private UserGroupAssignmentService $userGroupAssignmentService;
	private MultiFormatUserIdentityLookup $multiFormatUserIdentityLookup;
	private StatusFormatter $statusFormatter;

	public function __construct(
		?UserGroupManagerFactory $userGroupManagerFactory = null,
		?UserNameUtils $userNameUtils = null,
		?UserNamePrefixSearch $userNamePrefixSearch = null,
		?UserFactory $userFactory = null,
		?ActorStoreFactory $actorStoreFactory = null,
		?WatchlistManager $watchlistManager = null,
		?TempUserConfig $tempUserConfig = null,
		?UserGroupAssignmentService $userGroupAssignmentService = null,
		?MultiFormatUserIdentityLookup $multiFormatUserIdentityLookup = null,
		?FormatterFactory $formatterFactory = null,
	) {
		parent::__construct( 'Userrights' );
		$services = MediaWikiServices::getInstance();
		// This class is extended and therefore falls back to global state - T263207
		$this->userNameUtils = $userNameUtils ?? $services->getUserNameUtils();
		$this->userNamePrefixSearch = $userNamePrefixSearch ?? $services->getUserNamePrefixSearch();
		$this->userFactory = $userFactory ?? $services->getUserFactory();
		$this->userGroupManagerFactory = $userGroupManagerFactory ?? $services->getUserGroupManagerFactory();
		$this->localUserGroupManager = $this->userGroupManagerFactory->getUserGroupManager();
		$this->watchlistManager = $watchlistManager ?? $services->getWatchlistManager();
		$this->tempUserConfig = $tempUserConfig ?? $services->getTempUserConfig();
		$this->userGroupAssignmentService = $userGroupAssignmentService ?? $services->getUserGroupAssignmentService();
		$this->multiFormatUserIdentityLookup = $multiFormatUserIdentityLookup
			?? $services->getMultiFormatUserIdentityLookup();
		$this->statusFormatter = ( $formatterFactory ?? $services->getFormatterFactory() )
			->getStatusFormatter( $this->getContext() );
	}

	/**
	 * Check whether the current user (from context) can change the target user's rights.
	 *
	 * This function can be used without submitting the special page
	 * @deprecated since 1.45, use {@see UserGroupAssignmentService::canChangeUserGroups()} instead
	 *
	 * @param UserIdentity $targetUser User whose rights are being changed
	 * @param bool $checkIfSelf If false, assume that the current user can add/remove groups defined
	 *   in $wgGroupsAddToSelf / $wgGroupsRemoveFromSelf, without checking if it's the same as target
	 *   user
	 * @return bool
	 */
	public function userCanChangeRights( UserIdentity $targetUser, $checkIfSelf = true ) {
		// Some callers rely on this method to set the $userGroupManager property. These should be
		// fixed before removing this.
		if ( $this->userGroupManager === null ) {
			$this->userGroupManager = $this->userGroupManagerFactory
				->getUserGroupManager( $targetUser->getWikiId() );
		}

		return $this->userGroupAssignmentService->userCanChangeRights(
			$this->getAuthority(),
			$targetUser
		);
	}

	/**
	 * Manage forms to be shown according to posted data.
	 * Depending on the submit button used, call a form or a save function.
	 *
	 * @param string|null $par String if any subpage provided, else null
	 * @throws UserBlockedError|PermissionsError
	 */
	public function execute( $par ) {
		$user = $this->getUser();
		$request = $this->getRequest();
		$session = $request->getSession();
		$out = $this->getOutput();

		$out->addModules( [ 'mediawiki.special.userrights' ] );

		$this->mTarget = $par ?? $request->getVal( 'user' );
		if ( $this->mTarget === null ) {
			$fetchedStatus = Status::newFatal( 'nouserspecified' );

		} else {
			$this->mTarget = trim( $this->mTarget );

			if ( $this->userNameUtils->getCanonical( $this->mTarget ) === $user->getName() ) {
				$this->isself = true;
			}

			$fetchedStatus = $this->multiFormatUserIdentityLookup->getUserIdentity(
				$this->mTarget, $this->getAuthority() );
		}

		if ( $fetchedStatus->isOK() ) {
			$this->mFetchedUser = $fetchedUser = $fetchedStatus->value;
			// Phan false positive on Status object - T323205
			'@phan-var UserIdentity $fetchedUser';
			$wikiId = $fetchedUser->getWikiId();
			if ( $wikiId === UserIdentity::LOCAL ) {
				// Set the 'relevant user' in the skin, so it displays links like Contributions,
				// User logs, UserRights, etc.
				$this->getSkin()->setRelevantUser( $this->mFetchedUser );
			}
			$this->userGroupManager = $this->userGroupManagerFactory
				->getUserGroupManager( $wikiId );
		}

		// show a successbox, if the user rights was saved successfully
		if (
			$session->get( 'specialUserrightsSaveSuccess' ) &&
			$this->mFetchedUser !== null
		) {
			// Remove session data for the success message
			$session->remove( 'specialUserrightsSaveSuccess' );

			$out->addModuleStyles( 'mediawiki.notification.convertmessagebox.styles' );
			$out->addHTML(
				Html::successBox(
					Html::element(
						'p',
						[],
						$this->msg( 'savedrights', $this->getUsernameWithInterwiki( $this->mFetchedUser ) )->text()
					),
					'mw-notify-success'
				)
			);
		}

		$this->setHeaders();
		$this->outputHeader();

		$out->addModuleStyles( 'mediawiki.special' );
		$out->addModuleStyles( 'mediawiki.codex.messagebox.styles' );
		$this->addHelpLink( 'Help:Assigning permissions' );

		$this->switchForm();

		if (
			$request->wasPosted() &&
			$request->getCheck( 'saveusergroups' ) &&
			$this->mTarget !== null &&
			$user->matchEditToken( $request->getVal( 'wpEditToken' ), $this->mTarget )
		) {
			/*
			 * If the user is blocked and they only have "partial" access
			 * (e.g. they don't have the userrights permission), then don't
			 * allow them to change any user rights.
			 */
			if ( !$this->getAuthority()->isAllowed( 'userrights' ) ) {
				$block = $user->getBlock();
				if ( $block && $block->isSitewide() ) {
					throw new UserBlockedError(
						$block,
						$user,
						$this->getLanguage(),
						$request->getIP()
					);
				}
			}

			$this->checkReadOnly();

			// save settings
			if ( !$fetchedStatus->isOK() ) {
				$this->getOutput()->addWikiTextAsInterface(
					$fetchedStatus->getWikiText( false, false, $this->getLanguage() )
				);

				return;
			}

			$targetUser = $this->mFetchedUser;
			$conflictCheck = $request->getVal( 'conflictcheck-originalgroups' );
			$conflictCheck = ( $conflictCheck === '' ) ? [] : explode( ',', $conflictCheck );
			$userGroups = $this->userGroupManager->getUserGroups( $targetUser, IDBAccessObject::READ_LATEST );

			if ( $userGroups !== $conflictCheck ) {
				$out->addHTML( Html::errorBox(
					$this->msg( 'userrights-conflict' )->parse()
				) );
			} else {
				$status = $this->saveUserGroups(
					$request->getText( 'user-reason' ),
					$targetUser
				);

				if ( $status->isOK() ) {
					// Set session data for the success message
					$session->set( 'specialUserrightsSaveSuccess', 1 );

					$out->redirect( $this->getSuccessURL() );
					return;
				} else {
					// Print an error message and redisplay the form
					foreach ( $status->getMessages() as $msg ) {
						$out->addHTML( Html::errorBox(
							$this->msg( $msg )->parse()
						) );
					}
				}
			}
		}

		// If the target is valid, show the form (either edit or view)
		if ( !$fetchedStatus->isOK() ) {
			$out->addHTML( Html::warningBox(
				$this->statusFormatter->getMessage( $fetchedStatus )->parse()
			) );
			return;
		} elseif ( !$this->userGroupAssignmentService->targetCanHaveUserGroups( $this->mFetchedUser ) ) {
			// It's safe to check `targetCanHaveUserGroups` only here, because the service itself
			// also does the same check when trying to save the groups.
			// Differentiate between temp accounts and IP addresses. Eventually we might want
			// to edit the messages so that the same can be shown for both cases.
			$messageKey = $this->mFetchedUser->isRegistered() ? 'userrights-no-group' : 'nosuchusershort';
			$out->addHTML( Html::warningBox(
				$this->msg( $messageKey, $this->mFetchedUser->getName() )->parse()
			) );
			return;
		}

		$target = new UserGroupsSpecialPageTarget( $this->mFetchedUser->getName(), $this->mFetchedUser );
		$this->getOutput()->addHTML( $this->buildGroupsForm( $target ) );
		$this->showLogFragment( $target, $this->getOutput() );
	}

	private function getSuccessURL(): string {
		return $this->getPageTitle( $this->mTarget )->getFullURL();
	}

	/**
	 * Converts a user group membership expiry string into a timestamp. Words like
	 * 'existing' or 'other' should have been filtered out before calling this
	 * function.
	 *
	 * @param string $expiry
	 * @return string|null|false A string containing a valid timestamp, or null
	 *   if the expiry is infinite, or false if the timestamp is not valid
	 * @deprecated since 1.45, use UserGroupAssignmentService::expiryToTimestamp()
	 */
	public static function expiryToTimestamp( $expiry ) {
		return UserGroupAssignmentService::expiryToTimestamp( $expiry );
	}

	/**
	 * Save user groups changes in the database.
	 * Data comes from the editUserGroupsForm() form function
	 *
	 * @param string $reason Reason for group change
	 * @param UserIdentity $user The target user
	 * @return Status
	 */
	protected function saveUserGroups( string $reason, UserIdentity $user ) {
		if ( $this->userNameUtils->isTemp( $user->getName() ) ) {
			return Status::newFatal( 'userrights-no-tempuser' );
		}

		// Prevent cross-wiki assignment of groups to temporary accounts on wikis where the feature is not known.
		if (
			$user->getWikiId() !== UserIdentity::LOCAL &&
			!$this->tempUserConfig->isKnown() &&
			$this->tempUserConfig->isReservedName( $user->getName() )
		) {
			return Status::newFatal( 'userrights-cross-wiki-assignment-for-reserved-name' );
		}

		// This could possibly create a highly unlikely race condition if permissions are changed between
		// when the form is loaded and when the form is saved. Ignoring it for the moment.
		$existingUGMs = $this->userGroupManager->getUserGroupMemberships( $user );
		$newGroupsStatus = $this->readGroupsForm();

		if ( !$newGroupsStatus->isOK() ) {
			return $newGroupsStatus;
		}
		$newGroups = $newGroupsStatus->value;

		// addgroup contains also existing groups with changed expiry
		[ $addgroup, $removegroup, $groupExpiries ] = $this->splitGroupsIntoAddRemove( $newGroups, $existingUGMs );
		$this->userGroupAssignmentService->saveChangesToUserGroups( $this->getAuthority(), $user, $addgroup,
			$removegroup, $groupExpiries, $reason );

		if ( $user->getWikiId() === UserIdentity::LOCAL && $this->getRequest()->getCheck( 'wpWatch' ) ) {
			$this->watchlistManager->addWatchIgnoringRights(
				$this->getUser(),
				Title::makeTitle( NS_USER, $user->getName() )
			);
		}

		return Status::newGood();
	}

	/**
	 * Save user groups changes in the database. This function does not throw errors;
	 * instead, it ignores groups that the performer does not have permission to set.
	 *
	 * This function can be used without submitting the special page
	 * @deprecated since 1.45, use {@see UserGroupAssignmentService::saveChangesToUserGroups()} instead
	 *
	 * @param UserIdentity $user The target user
	 * @param string[] $add Array of groups to add
	 * @param string[] $remove Array of groups to remove
	 * @param string $reason Reason for group change
	 * @param string[] $tags Array of change tags to add to the log entry
	 * @param array<string,?string> $groupExpiries Associative array of (group name => expiry),
	 *   containing only those groups that are to have new expiry values set
	 * @return array Tuple of added, then removed groups
	 */
	public function doSaveUserGroups( $user, array $add, array $remove, string $reason = '',
		array $tags = [], array $groupExpiries = []
	) {
		return $this->userGroupAssignmentService->saveChangesToUserGroups( $this->getAuthority(), $user, $add, $remove,
			$groupExpiries, $reason, $tags );
	}

	/**
	 * Normalize the input username, which may be local or remote, and
	 * return a user identity object, use it on other services for manipulating rights
	 *
	 * Side effects: error output for invalid access
	 * @deprecated since 1.45, use {@see MultiFormatUserIdentityLookup::getUserIdentity()} instead
	 * @param string $username
	 * @param bool $writing
	 * @return Status
	 */
	public function fetchUser( $username, $writing = true ) {
		wfDeprecated( __METHOD__, '1.45' );
		return $this->multiFormatUserIdentityLookup->getUserIdentity( $username, $this->getAuthority() );
	}

	/**
	 * Display a HTMLUserTextField form to allow searching for a named user only
	 */
	protected function switchForm() {
		$formDescriptor = [
			'user' => [
				'class' => HTMLUserTextField::class,
				'label-message' => 'userrights-user-editname',
				'name' => 'user',
				'ipallowed' => true,
				'iprange' => true,
				'excludetemp' => true, // Do not show temp users: T341684
				'autofocus' => $this->mFetchedUser === null,
				'default' => $this->mTarget,
			]
		];

		$htmlForm = HTMLForm::factory( 'ooui', $formDescriptor, $this->getContext() );
		$htmlForm
			->setMethod( 'GET' )
			->setAction( wfScript() )
			->setName( 'uluser' )
			->setTitle( SpecialPage::getTitleFor( 'Userrights' ) )
			->setWrapperLegendMsg( 'userrights-lookup-user' )
			->setId( 'mw-userrights-form1' )
			->setSubmitTextMsg( 'editusergroup' )
			->prepareForm()
			->displayForm( true );
	}

	/** @inheritDoc */
	protected function makeConflictCheckKey( UserGroupsSpecialPageTarget $target ): string {
		$user = $this->assertIsUserIdentity( $target->userObject );
		return implode( ',', $this->userGroupManager->getUserGroups( $user ) );
	}

	/** @inheritDoc */
	protected function getTargetDescriptor(): string {
		return $this->mTarget;
	}

	/** @inheritDoc */
	protected function getTargetUserToolLinks( UserGroupsSpecialPageTarget $target ): string {
		$user = $this->assertIsUserIdentity( $target->userObject );
		$systemUser = $user->getWikiId() === UserIdentity::LOCAL
			&& $this->userFactory->newFromUserIdentity( $user )->isSystemUser();

		// Only add an email link if the user is not a system user
		$flags = $systemUser ? 0 : Linker::TOOL_LINKS_EMAIL;
		return Linker::userToolLinks(
			$user->getId( $user->getWikiId() ),
			$this->getDisplayUsername( $target ),
			false, /* default for redContribsWhenNoEdits */
			$flags
		);
	}

	/** @inheritDoc */
	protected function getCurrentUserGroupsText( UserGroupsSpecialPageTarget $target ): string {
		$user = $this->assertIsUserIdentity( $target->userObject );
		$groupsText = parent::getCurrentUserGroupsText( $target );

		// Apart from displaying the groups list, also display a note if this is a system user
		$systemUser = $user->getWikiId() === UserIdentity::LOCAL
			&& $this->userFactory->newFromUserIdentity( $user )->isSystemUser();
		if ( $systemUser ) {
			$systemUserNote = $this->msg( 'userrights-systemuser' )
				->params( $user->getName() )
				->parse();
			$groupsText .= Html::rawElement(
				'p',
				[],
				$systemUserNote
			);
		}
		return $groupsText;
	}

	/** @inheritDoc */
	protected function categorizeUserGroupsForDisplay(
		array $userGroups,
		UserGroupsSpecialPageTarget $target
	): array {
		$user = $this->assertIsUserIdentity( $target->userObject );
		$autoGroups = [];

		// Listing autopromote groups works only on the local wiki
		if ( $user->getWikiId() === UserIdentity::LOCAL ) {
			foreach ( $this->userGroupManager->getUserAutopromoteGroups( $user ) as $group ) {
				$autoGroups[$group] = new UserGroupMembership( $user->getId(), $group );
			}
			ksort( $autoGroups );
		}

		return [
			'userrights-groupsmember' => array_values( $userGroups ),
			'userrights-groupsmember-auto' => $autoGroups,
		];
	}

	/** @inheritDoc */
	protected function listAllExplicitGroups(): array {
		return $this->userGroupManager->listAllGroups();
	}

	/** @inheritDoc */
	protected function getGroupMemberships( UserGroupsSpecialPageTarget $target ): array {
		$user = $this->assertIsUserIdentity( $target->userObject );
		return $this->userGroupManager->getUserGroupMemberships( $user );
	}

	protected function getGroupAnnotations( string $group ): array {
		$groups = $this->changeableGroups();
		if ( !isset( $groups['restricted'][$group] ) || $groups['restricted'][$group]['condition-met'] ) {
			return parent::getGroupAnnotations( $group );
		}
		return [
			$groups['restricted'][$group]['message']
		];
	}

	/**
	 * @param string $group The name of the group to check
	 * @return bool Can we remove the group?
	 */
	protected function canRemove( string $group ): bool {
		$groups = $this->changeableGroups();

		return in_array(
			$group,
			$groups['remove'] ) || ( $this->isself && in_array( $group, $groups['remove-self'] )
		);
	}

	/**
	 * @param string $group The name of the group to check
	 * @return bool Can we add the group?
	 */
	protected function canAdd( string $group ): bool {
		$groups = $this->changeableGroups();

		return in_array(
			$group,
			$groups['add'] ) || ( $this->isself && in_array( $group, $groups['add-self'] )
		);
	}

	/**
	 * @return array [
	 *   'add' => [ addablegroups ],
	 *   'remove' => [ removablegroups ],
	 *   'add-self' => [ addablegroups to self ],
	 *   'remove-self' => [ removable groups from self ]
	 *   'restricted' => [ map of restricted groups to details about them ]
	 *  ]
	 * @phan-return array{add:list<string>,remove:list<string>,add-self:list<string>,remove-self:list<string>}
	 */
	protected function changeableGroups() {
		if ( $this->changeableGroups === null ) {
			$authority = $this->getContext()->getAuthority();
			$groups = $this->localUserGroupManager->getGroupsChangeableBy( $authority );

			if ( $this->mFetchedUser !== null ) {
				// If the target is an interwiki user, ensure that the performer is entitled to such changes
				// It assumes that the target wiki exists at all
				if (
					$this->mFetchedUser->getWikiId() !== UserIdentity::LOCAL &&
					!$authority->isAllowed( 'userrights-interwiki' )
				) {
					return [
						'add' => [],
						'remove' => [],
						'add-self' => [],
						'remove-self' => [],
						'restricted' => [],
					];
				}

				// Allow extensions to define groups that cannot be added, given the target user and
				// the performer. This allows policy restrictions to be enforced via software. This
				// could be done via configuration in the future, as discussed in T393615.
				$restrictedGroups = [];
				$this->getHookRunner()->onSpecialUserRightsChangeableGroups(
					$authority,
					$this->mFetchedUser,
					$groups['add'],
					$restrictedGroups
				);

				$unAddableRestrictedGroups = array_keys(
					array_filter( $restrictedGroups, static fn ( $group ) =>
						!$group['condition-met'] && !$group['ignore-condition'] )
				);

				$groups['add'] = array_diff( $groups['add'], $unAddableRestrictedGroups );
				$groups['restricted'] = $restrictedGroups;
			}

			$this->changeableGroups = $groups;
		}

		return $this->changeableGroups;
	}

	/** @inheritDoc */
	protected function getDisplayUsername( UserGroupsSpecialPageTarget $target ): string {
		$user = $this->assertIsUserIdentity( $target->userObject );
		return $this->getUsernameWithInterwiki( $user );
	}

	/**
	 * Get a display user name. This includes the {@}domain part for interwiki users.
	 * Use UserIdentity::getName for {{GENDER:}} in messages and
	 * use the "display user name" for visible user names in logs or messages
	 *
	 * TODO: Eventually, this will be not needed, once all code uses getDisplayUsername instead of
	 * calling this method directly. At that point, we can move this code to that method.
	 * It will likely happen in T405575
	 */
	private function getUsernameWithInterwiki( UserIdentity $user ): string {
		$userName = $user->getName();
		if ( $user->getWikiId() !== UserIdentity::LOCAL ) {
			$userName .= $this->getConfig()->get( MainConfigNames::UserrightsInterwikiDelimiter )
				. $user->getWikiId();
		}
		return $userName;
	}

	/** @inheritDoc */
	protected function supportsWatchUser( UserGroupsSpecialPageTarget $target ): bool {
		$user = $this->assertIsUserIdentity( $target->userObject );
		return $user->getWikiId() === UserIdentity::LOCAL;
	}

	/** @inheritDoc */
	protected function getLogType(): array {
		return [ 'rights', 'rights' ];
	}

	/**
	 * Return an array of subpages beginning with $search that this special page will accept.
	 *
	 * @param string $search Prefix to search for
	 * @param int $limit Maximum number of results to return (usually 10)
	 * @param int $offset Number of results to skip (usually 0)
	 * @return string[] Matching subpages
	 */
	public function prefixSearchSubpages( $search, $limit, $offset ) {
		$search = $this->userNameUtils->getCanonical( $search );
		if ( !$search ) {
			// No prefix suggestion for invalid user
			return [];
		}
		// Autocomplete subpage as user list - public to allow caching
		return $this->userNamePrefixSearch
			->search( UserNamePrefixSearch::AUDIENCE_PUBLIC, $search, $limit, $offset );
	}

	/**
	 * A helper function to assert that an object is of type {@see UserIdentity}.
	 * It's used when retrieving the user object from a {@see UserGroupsSpecialPageTarget}.
	 * @throws InvalidArgumentException If the object is not of the expected type
	 * @param mixed $object The object to check
	 * @return UserIdentity The input object, but with a type hint for IDEs
	 */
	private function assertIsUserIdentity( mixed $object ): UserIdentity {
		if ( !$object instanceof UserIdentity ) {
			throw new InvalidArgumentException( 'Target userObject must be a UserIdentity' );
		}
		return $object;
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.40
 */
class_alias( SpecialUserRights::class, 'UserrightsPage' );
