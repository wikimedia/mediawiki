<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Specials;

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
	 * @var UserIdentity The user object of the target username.
	 */
	protected UserIdentity $targetUser;

	private UserGroupManagerFactory $userGroupManagerFactory;

	/** @var UserGroupManager|null The UserGroupManager of the target username or null */
	private $userGroupManager = null;

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
		wfDeprecated( __METHOD__, '1.45' );
		return $this->userGroupAssignmentService->userCanChangeRights(
			$this->getAuthority(),
			$targetUser
		);
	}

	/**
	 * Manage forms to be shown according to posted data.
	 * Depending on the submit button used, call a form or a save function.
	 *
	 * @param string|null $subPage String if any subpage provided, else null
	 * @throws UserBlockedError|PermissionsError
	 */
	public function execute( $subPage ) {
		$user = $this->getUser();
		$request = $this->getRequest();
		$session = $request->getSession();
		$out = $this->getOutput();

		$this->setHeaders();
		$this->outputHeader();

		$out->addModules( [ 'mediawiki.special.userrights' ] );
		$out->addModuleStyles( 'mediawiki.special' );
		$out->addModuleStyles( 'mediawiki.codex.messagebox.styles' );
		$this->addHelpLink( 'Help:Assigning permissions' );

		$targetName = $subPage ?? $request->getText( 'user' );
		$this->switchForm( $targetName );

		// If the user just viewed this page, without trying to submit, return early
		// It prevents from showing "nouserspecified" error message on first view
		if ( $subPage === null && !$request->getCheck( 'user' ) ) {
			return;
		}

		// No need to check if $target is non-empty or non-canonical, this is done in the lookup service
		$fetchedStatus = $this->multiFormatUserIdentityLookup->getUserIdentity( $targetName, $this->getAuthority() );
		if ( !$fetchedStatus->isOK() ) {
			$out->addHTML( Html::warningBox(
				$this->statusFormatter->getMessage( $fetchedStatus )->parse()
			) );
			return;
		}

		$fetchedUser = $fetchedStatus->value;
		// Phan false positive on Status object - T323205
		'@phan-var UserIdentity $fetchedUser';
		$this->setTargetName( $fetchedUser->getName(), $this->getUsernameWithInterwiki( $fetchedUser ) );
		$this->targetUser = $fetchedUser;

		if ( !$this->userGroupAssignmentService->targetCanHaveUserGroups( $fetchedUser ) ) {
			// Differentiate between temp accounts and IP addresses. Eventually we might want
			// to edit the messages so that the same can be shown for both cases.
			$messageKey = $fetchedUser->isRegistered() ? 'userrights-no-group' : 'nosuchusershort';
			$out->addHTML( Html::warningBox(
				$this->msg( $messageKey, $fetchedUser->getName() )->parse()
			) );
			return;
		}

		$wikiId = $fetchedUser->getWikiId();
		if ( $wikiId === UserIdentity::LOCAL ) {
			// Set the 'relevant user' in the skin, so it displays links like Contributions,
			// User logs, UserRights, etc.
			$this->getSkin()->setRelevantUser( $fetchedUser );
		}
		$this->userGroupManager = $this->userGroupManagerFactory->getUserGroupManager( $wikiId );
		$this->explicitGroups = $this->userGroupManager->listAllGroups();
		$this->groupMemberships = $this->userGroupManager->getUserGroupMemberships( $fetchedUser );
		$this->enableWatchUser = $fetchedUser->getWikiId() === UserIdentity::LOCAL;

		$changeableGroups = $this->userGroupAssignmentService->getChangeableGroups(
			$this->getAuthority(), $fetchedUser );
		$this->addableGroups = $changeableGroups['add'];
		$this->removableGroups = $changeableGroups['remove'];
		foreach ( $changeableGroups['restricted'] as $group => $details ) {
			if ( !$details['condition-met'] ) {
				$this->addGroupAnnotation( $group, $details['message'] );
			}
		}

		// show a successbox, if the user rights was saved successfully
		if ( $session->get( 'specialUserrightsSaveSuccess' ) ) {
			// Remove session data for the success message
			$session->remove( 'specialUserrightsSaveSuccess' );

			$out->addModuleStyles( 'mediawiki.notification.convertmessagebox.styles' );
			$out->addHTML(
				Html::successBox(
					Html::element(
						'p',
						[],
						$this->msg( 'savedrights', $this->getUsernameWithInterwiki( $fetchedUser ) )->text()
					),
					'mw-notify-success'
				)
			);
		}

		if (
			$request->wasPosted() &&
			$request->getCheck( 'saveusergroups' ) &&
			$user->matchEditToken( $request->getVal( 'wpEditToken' ), $targetName )
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

			$conflictCheck = $request->getVal( 'conflictcheck-originalgroups' );
			$conflictCheck = ( $conflictCheck === '' ) ? [] : explode( ',', $conflictCheck );
			$userGroups = $this->userGroupManager->getUserGroups( $fetchedUser, IDBAccessObject::READ_LATEST );

			if ( $userGroups !== $conflictCheck ) {
				$out->addHTML( Html::errorBox(
					$this->msg( 'userrights-conflict' )->parse()
				) );
			} else {
				$status = $this->saveUserGroups(
					$request->getText( 'user-reason' ),
					$fetchedUser,
				);

				if ( $status->isOK() ) {
					// Set session data for the success message
					$session->set( 'specialUserrightsSaveSuccess', 1 );

					$out->redirect( $this->getSuccessURL( $targetName ) );
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

		// Show the form (either edit or view)
		$this->getOutput()->addHTML( $this->buildGroupsForm() );
		$this->showLogFragment( 'rights', 'rights' );
	}

	private function getSuccessURL( string $target ): string {
		return $this->getPageTitle( $target )->getFullURL();
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
		$newGroupsStatus = $this->readGroupsForm();

		if ( !$newGroupsStatus->isOK() ) {
			return $newGroupsStatus;
		}
		$newGroups = $newGroupsStatus->value;

		// addgroup contains also existing groups with changed expiry
		[ $addgroup, $removegroup, $groupExpiries ] = $this->splitGroupsIntoAddRemove(
			$newGroups, $this->groupMemberships );
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
	protected function switchForm( string $target ) {
		$formDescriptor = [
			'user' => [
				'class' => HTMLUserTextField::class,
				'label-message' => 'userrights-user-editname',
				'name' => 'user',
				'ipallowed' => true,
				'iprange' => true,
				'excludetemp' => true, // Do not show temp users: T341684
				'autofocus' => $target === '',
				'default' => $target,
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
	protected function getTargetUserToolLinks(): string {
		$targetWiki = $this->targetUser->getWikiId();
		$systemUser = $targetWiki === UserIdentity::LOCAL
			&& $this->userFactory->newFromUserIdentity( $this->targetUser )->isSystemUser();

		// Only add an email link if the user is not a system user
		$flags = $systemUser ? 0 : Linker::TOOL_LINKS_EMAIL;
		return Linker::userToolLinks(
			$this->targetUser->getId( $targetWiki ),
			$this->getUsernameWithInterwiki( $this->targetUser ),
			false, /* default for redContribsWhenNoEdits */
			$flags
		);
	}

	/** @inheritDoc */
	protected function getCurrentUserGroupsText(): string {
		$groupsText = parent::getCurrentUserGroupsText();

		// Apart from displaying the groups list, also display a note if this is a system user
		$systemUser = $this->targetUser->getWikiId() === UserIdentity::LOCAL
			&& $this->userFactory->newFromUserIdentity( $this->targetUser )->isSystemUser();
		if ( $systemUser ) {
			$systemUserNote = $this->msg( 'userrights-systemuser' )
				->params( $this->targetUser->getName() )
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
	protected function categorizeUserGroupsForDisplay( array $userGroups ): array {
		$autoGroups = [];

		// Listing autopromote groups works only on the local wiki
		if ( $this->targetUser->getWikiId() === UserIdentity::LOCAL ) {
			foreach ( $this->userGroupManager->getUserAutopromoteGroups( $this->targetUser ) as $group ) {
				$autoGroups[$group] = new UserGroupMembership( $this->targetUser->getId(), $group );
			}
			ksort( $autoGroups );
		}

		return [
			'userrights-groupsmember' => array_values( $userGroups ),
			'userrights-groupsmember-auto' => $autoGroups,
		];
	}

	/**
	 * @deprecated since 1.45, use {@see UserGroupAssignmentService::getChangeableGroups()} instead
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
		wfDeprecated( __METHOD__, '1.45' );
		$result = $this->userGroupAssignmentService->getChangeableGroups( $this->getAuthority(), $this->targetUser );
		// Add missing keys, just in case
		$result['add-self'] = [];
		$result['remove-self'] = [];
		return $result;
	}

	/**
	 * Returns the username together with an interwiki suffix if applicable (user{@}wiki).
	 * This form of the username is suitable for display in logs and other user-facing messages.
	 * However, it cannot be used for {{GENDER:}} - in that case, use UserIdentity::getName().
	 */
	private function getUsernameWithInterwiki( UserIdentity $targetUser ): string {
		$userName = $targetUser->getName();
		$targetWiki = $targetUser->getWikiId();
		if ( $targetWiki !== UserIdentity::LOCAL ) {
			$userName .= $this->getConfig()->get( MainConfigNames::UserrightsInterwikiDelimiter ) . $targetWiki;
		}
		return $userName;
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
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.40
 */
class_alias( SpecialUserRights::class, 'UserrightsPage' );
