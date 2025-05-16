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

namespace MediaWiki\Specials;

use MediaWiki\CommentStore\CommentStore;
use MediaWiki\Exception\PermissionsError;
use MediaWiki\Exception\UserBlockedError;
use MediaWiki\Html\Html;
use MediaWiki\HTMLForm\Field\HTMLUserTextField;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\Linker\Linker;
use MediaWiki\Logging\LogEventsList;
use MediaWiki\Logging\LogPage;
use MediaWiki\Logging\ManualLogEntry;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Output\OutputPage;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Status\Status;
use MediaWiki\Title\Title;
use MediaWiki\User\ActorStoreFactory;
use MediaWiki\User\TempUser\TempUserConfig;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserGroupManager;
use MediaWiki\User\UserGroupManagerFactory;
use MediaWiki\User\UserGroupMembership;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserNamePrefixSearch;
use MediaWiki\User\UserNameUtils;
use MediaWiki\Watchlist\WatchlistManager;
use MediaWiki\WikiMap\WikiMap;
use MediaWiki\Xml\XmlSelect;
use Wikimedia\Rdbms\IDBAccessObject;

/**
 * Special page to allow managing user group membership
 *
 * @ingroup SpecialPage
 */
class SpecialUserRights extends SpecialPage {
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

	private UserNameUtils $userNameUtils;
	private UserNamePrefixSearch $userNamePrefixSearch;
	private UserFactory $userFactory;
	private ActorStoreFactory $actorStoreFactory;
	private WatchlistManager $watchlistManager;
	private TempUserConfig $tempUserConfig;

	public function __construct(
		?UserGroupManagerFactory $userGroupManagerFactory = null,
		?UserNameUtils $userNameUtils = null,
		?UserNamePrefixSearch $userNamePrefixSearch = null,
		?UserFactory $userFactory = null,
		?ActorStoreFactory $actorStoreFactory = null,
		?WatchlistManager $watchlistManager = null,
		?TempUserConfig $tempUserConfig = null
	) {
		parent::__construct( 'Userrights' );
		$services = MediaWikiServices::getInstance();
		// This class is extended and therefore falls back to global state - T263207
		$this->userNameUtils = $userNameUtils ?? $services->getUserNameUtils();
		$this->userNamePrefixSearch = $userNamePrefixSearch ?? $services->getUserNamePrefixSearch();
		$this->userFactory = $userFactory ?? $services->getUserFactory();
		$this->userGroupManagerFactory = $userGroupManagerFactory ?? $services->getUserGroupManagerFactory();
		$this->actorStoreFactory = $actorStoreFactory ?? $services->getActorStoreFactory();
		$this->watchlistManager = $watchlistManager ?? $services->getWatchlistManager();
		$this->tempUserConfig = $tempUserConfig ?? $services->getTempUserConfig();
	}

	public function doesWrites() {
		return true;
	}

	/**
	 * Check whether the current user (from context) can change the target user's rights.
	 *
	 * This function can be used without submitting the special page
	 *
	 * @param UserIdentity $targetUser User whose rights are being changed
	 * @param bool $checkIfSelf If false, assume that the current user can add/remove groups defined
	 *   in $wgGroupsAddToSelf / $wgGroupsRemoveFromSelf, without checking if it's the same as target
	 *   user
	 * @return bool
	 */
	public function userCanChangeRights( UserIdentity $targetUser, $checkIfSelf = true ) {
		if (
			!$targetUser->isRegistered() ||
			$this->userNameUtils->isTemp( $targetUser->getName() )
		) {
			return false;
		}

		if ( $this->userGroupManager === null ) {
			$this->userGroupManager = $this->userGroupManagerFactory
				->getUserGroupManager( $targetUser->getWikiId() );
		}
		$available = $this->changeableGroups();
		if ( $available['add'] || $available['remove'] ) {
			// can change some rights for any user
			return true;
		}

		$isself = $this->getUser()->equals( $targetUser );
		if ( ( $available['add-self'] || $available['remove-self'] )
			&& ( $isself || !$checkIfSelf )
		) {
			// can change some rights for self
			return true;
		}

		return false;
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

			$fetchedStatus = $this->fetchUser( $this->mTarget, true );
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
						$this->msg( 'savedrights', $this->getDisplayUsername( $this->mFetchedUser ) )->text()
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

		// show some more forms
		if ( $this->mTarget !== null ) {
			$this->editUserGroupsForm( $this->mTarget );
		}
	}

	private function getSuccessURL(): string {
		return $this->getPageTitle( $this->mTarget )->getFullURL();
	}

	/**
	 * Returns true if this user rights form can set and change user group expiries.
	 * Subclasses may wish to override this to return false.
	 *
	 * @return bool
	 */
	public function canProcessExpiries() {
		return true;
	}

	/**
	 * Converts a user group membership expiry string into a timestamp. Words like
	 * 'existing' or 'other' should have been filtered out before calling this
	 * function.
	 *
	 * @param string $expiry
	 * @return string|null|false A string containing a valid timestamp, or null
	 *   if the expiry is infinite, or false if the timestamp is not valid
	 */
	public static function expiryToTimestamp( $expiry ) {
		if ( wfIsInfinity( $expiry ) ) {
			return null;
		}

		$unix = strtotime( $expiry );

		if ( !$unix || $unix === -1 ) {
			return false;
		}

		// @todo FIXME: Non-qualified absolute times are not in users specified timezone
		// and there isn't notice about it in the ui (see ProtectionForm::getExpiry)
		return wfTimestamp( TS_MW, $unix );
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

		$allgroups = $this->userGroupManager->listAllGroups();
		$addgroup = [];
		$groupExpiries = []; // associative array of (group name => expiry)
		$removegroup = [];
		$existingUGMs = $this->userGroupManager->getUserGroupMemberships( $user );

		// This could possibly create a highly unlikely race condition if permissions are changed between
		//  when the form is loaded and when the form is saved. Ignoring it for the moment.
		foreach ( $allgroups as $group ) {
			// We'll tell it to remove all unchecked groups, and add all checked groups.
			// Later on, this gets filtered for what can actually be removed
			if ( $this->getRequest()->getCheck( "wpGroup-$group" ) ) {
				$addgroup[] = $group;

				if ( $this->canProcessExpiries() ) {
					// read the expiry information from the request
					$expiryDropdown = $this->getRequest()->getVal( "wpExpiry-$group" );
					if ( $expiryDropdown === 'existing' ) {
						continue;
					}

					if ( $expiryDropdown === 'other' ) {
						$expiryValue = $this->getRequest()->getVal( "wpExpiry-$group-other" );
					} else {
						$expiryValue = $expiryDropdown;
					}

					// validate the expiry
					$groupExpiries[$group] = self::expiryToTimestamp( $expiryValue );

					if ( $groupExpiries[$group] === false ) {
						return Status::newFatal( 'userrights-invalid-expiry', $group );
					}

					// not allowed to have things expiring in the past
					if ( $groupExpiries[$group] && $groupExpiries[$group] < wfTimestampNow() ) {
						return Status::newFatal( 'userrights-expiry-in-past', $group );
					}

					// if the user can only add this group (not remove it), the expiry time
					// cannot be brought forward (T156784)
					if ( !$this->canRemove( $group ) &&
						isset( $existingUGMs[$group] ) &&
						( $existingUGMs[$group]->getExpiry() ?: 'infinity' ) >
							( $groupExpiries[$group] ?: 'infinity' )
					) {
						return Status::newFatal( 'userrights-cannot-shorten-expiry', $group );
					}
				}
			} else {
				$removegroup[] = $group;
			}
		}

		$this->doSaveUserGroups( $user, $addgroup, $removegroup, $reason, [], $groupExpiries );

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
		// Set properties that other methods rely on if not already set, e.g. if called from the API
		$this->mTarget ??= $user->getName();
		$this->mFetchedUser ??= $user;

		// Validate input set...
		$isself = $user->getName() == $this->getUser()->getName();
		if ( $this->userGroupManager === null ) {
			// This is being called as a backend-function, rather than after form submit
			$this->userGroupManager = $this->userGroupManagerFactory
				->getUserGroupManager( $user->getWikiId() );
		}
		$groups = $this->userGroupManager->getUserGroups( $user );
		$ugms = $this->userGroupManager->getUserGroupMemberships( $user );
		$changeable = $this->changeableGroups();
		$addable = array_merge( $changeable['add'], $isself ? $changeable['add-self'] : [] );
		$removable = array_merge( $changeable['remove'], $isself ? $changeable['remove-self'] : [] );

		$remove = array_unique( array_intersect( $remove, $removable, $groups ) );
		$add = array_intersect( $add, $addable );

		// add only groups that are not already present or that need their expiry updated,
		// UNLESS the user can only add this group (not remove it) and the expiry time
		// is being brought forward (T156784)
		$add = array_filter( $add,
			static function ( $group ) use ( $groups, $groupExpiries, $removable, $ugms ) {
				if ( isset( $groupExpiries[$group] ) &&
					!in_array( $group, $removable ) &&
					isset( $ugms[$group] ) &&
					( $ugms[$group]->getExpiry() ?: 'infinity' ) >
						( $groupExpiries[$group] ?: 'infinity' )
				) {
					return false;
				}
				return !in_array( $group, $groups ) || array_key_exists( $group, $groupExpiries );
			} );

		if ( $user->getWikiId() === UserIdentity::LOCAL ) {
			// For compatibility local changes are provided as User object to the hook
			$hookUser = $this->userFactory->newFromUserIdentity( $user );
		} else {
			// Interwiki changes are provided as UserIdentity since 1.41, was UserRightsProxy before
			$hookUser = $user;
		}
		$this->getHookRunner()->onChangeUserGroups( $this->getUser(), $hookUser, $add, $remove );

		$oldGroups = $groups;
		$oldUGMs = $this->userGroupManager->getUserGroupMemberships( $user );
		$newGroups = $oldGroups;

		// Remove groups, then add new ones/update expiries of existing ones
		if ( $remove ) {
			foreach ( $remove as $index => $group ) {
				if ( !$this->userGroupManager->removeUserFromGroup( $user, $group ) ) {
					unset( $remove[$index] );
				}
			}
			$newGroups = array_diff( $newGroups, $remove );
		}
		if ( $add ) {
			foreach ( $add as $index => $group ) {
				$expiry = $groupExpiries[$group] ?? null;
				if ( !$this->userGroupManager->addUserToGroup( $user, $group, $expiry, true ) ) {
					unset( $add[$index] );
				}
			}
			$newGroups = array_merge( $newGroups, $add );
		}
		$newGroups = array_unique( $newGroups );
		$newUGMs = $this->userGroupManager->getUserGroupMemberships( $user );

		// Ensure that caches are cleared
		$this->userFactory->invalidateCache( $user );

		// update groups in external authentication database
		$this->getHookRunner()->onUserGroupsChanged( $hookUser, $add, $remove,
			$this->getUser(), $reason, $oldUGMs, $newUGMs );

		wfDebug( 'oldGroups: ' . print_r( $oldGroups, true ) );
		wfDebug( 'newGroups: ' . print_r( $newGroups, true ) );
		wfDebug( 'oldUGMs: ' . print_r( $oldUGMs, true ) );
		wfDebug( 'newUGMs: ' . print_r( $newUGMs, true ) );

		// Only add a log entry if something actually changed
		if ( $newGroups != $oldGroups || $newUGMs != $oldUGMs ) {
			$this->addLogEntry( $user, $oldGroups, $newGroups, $reason, $tags, $oldUGMs, $newUGMs );
		}

		return [ $add, $remove ];
	}

	/**
	 * Serialise a UserGroupMembership object for storage in the log_params section
	 * of the logging table. Only keeps essential data, removing redundant fields.
	 *
	 * @param UserGroupMembership|null $ugm May be null if things get borked
	 * @return array|null
	 */
	protected static function serialiseUgmForLog( $ugm ) {
		if ( !$ugm instanceof UserGroupMembership ) {
			return null;
		}
		return [ 'expiry' => $ugm->getExpiry() ];
	}

	/**
	 * Add a rights log entry for an action.
	 * @param UserIdentity $user The target user
	 * @param array $oldGroups
	 * @param array $newGroups
	 * @param string $reason
	 * @param string[] $tags Change tags for the log entry
	 * @param array $oldUGMs Associative array of (group name => UserGroupMembership)
	 * @param array $newUGMs Associative array of (group name => UserGroupMembership)
	 */
	protected function addLogEntry( $user, array $oldGroups, array $newGroups, string $reason,
		array $tags, array $oldUGMs, array $newUGMs
	) {
		// make sure $oldUGMs and $newUGMs are in the same order, and serialise
		// each UGM object to a simplified array
		$oldUGMs = array_map( static function ( $group ) use ( $oldUGMs ) {
			return isset( $oldUGMs[$group] ) ?
				self::serialiseUgmForLog( $oldUGMs[$group] ) :
				null;
		}, $oldGroups );
		$newUGMs = array_map( static function ( $group ) use ( $newUGMs ) {
			return isset( $newUGMs[$group] ) ?
				self::serialiseUgmForLog( $newUGMs[$group] ) :
				null;
		}, $newGroups );

		$logEntry = new ManualLogEntry( 'rights', 'rights' );
		$logEntry->setPerformer( $this->getUser() );
		$logEntry->setTarget( Title::makeTitle( NS_USER, $this->getDisplayUsername( $user ) ) );
		$logEntry->setComment( $reason );
		$logEntry->setParameters( [
			'4::oldgroups' => $oldGroups,
			'5::newgroups' => $newGroups,
			'oldmetadata' => $oldUGMs,
			'newmetadata' => $newUGMs,
		] );
		$logid = $logEntry->insert();
		if ( count( $tags ) ) {
			$logEntry->addTags( $tags );
		}
		$logEntry->publish( $logid );
	}

	/**
	 * Edit user groups membership
	 * @param string $username Name of the user.
	 */
	private function editUserGroupsForm( $username ) {
		$status = $this->fetchUser( $username, true );
		if ( !$status->isOK() ) {
			$this->getOutput()->addWikiTextAsInterface(
				$status->getWikiText( false, false, $this->getLanguage() )
			);

			return;
		}

		/** @var UserIdentity $user */
		$user = $status->value;
		'@phan-var UserIdentity $user';

		$groups = $this->userGroupManager->getUserGroups( $user );
		$groupMemberships = $this->userGroupManager->getUserGroupMemberships( $user );
		$this->showEditUserGroupsForm( $user, $groups, $groupMemberships );

		// This isn't really ideal logging behavior, but let's not hide the
		// interwiki logs if we're using them as is.
		$this->showLogFragment( $user, $this->getOutput() );
	}

	/**
	 * Normalize the input username, which may be local or remote, and
	 * return a user identity object, use it on other services for manipulating rights
	 *
	 * Side effects: error output for invalid access
	 * @param string $username
	 * @param bool $writing
	 * @return Status
	 */
	public function fetchUser( $username, $writing = true ) {
		$parts = explode( $this->getConfig()->get( MainConfigNames::UserrightsInterwikiDelimiter ),
			$username );
		if ( count( $parts ) < 2 ) {
			$name = trim( $username );
			$wikiId = UserIdentity::LOCAL;
		} else {
			[ $name, $wikiId ] = array_map( 'trim', $parts );

			if ( WikiMap::isCurrentWikiId( $wikiId ) ) {
				$wikiId = UserIdentity::LOCAL;
			} else {
				if ( $writing &&
					!$this->getAuthority()->isAllowed( 'userrights-interwiki' )
				) {
					return Status::newFatal( 'userrights-no-interwiki' );
				}
				$localDatabases = $this->getConfig()->get( MainConfigNames::LocalDatabases );
				if ( !in_array( $wikiId, $localDatabases ) ) {
					return Status::newFatal( 'userrights-nodatabase', $wikiId );
				}
			}
		}

		if ( $name === '' ) {
			return Status::newFatal( 'nouserspecified' );
		}

		$userIdentityLookup = $this->actorStoreFactory->getUserIdentityLookup( $wikiId );
		if ( $name[0] == '#' ) {
			// Numeric ID can be specified...
			$id = intval( substr( $name, 1 ) );

			$user = $userIdentityLookup->getUserIdentityByUserId( $id );
			if ( !$user ) {
				// Different error message for compatibility
				return Status::newFatal( 'noname' );
			}
			$name = $user->getName();
		} else {
			$name = $this->userNameUtils->getCanonical( $name );
			if ( $name === false ) {
				// invalid name
				return Status::newFatal( 'nosuchusershort', $username );
			}
			$user = $userIdentityLookup->getUserIdentityByName( $name );
		}

		if ( $this->userNameUtils->isTemp( $name ) ) {
			return Status::newFatal( 'userrights-no-group' );
		}

		if ( !$user || !$user->isRegistered() ) {
			return Status::newFatal( 'nosuchusershort', $username );
		}

		// Prevent cross-wiki assignment of groups to temporary accounts on wikis where the feature is not known.
		// We have to check this here, as ApiUserrights uses this to validate before assigning user rights.
		if (
			$user->getWikiId() !== UserIdentity::LOCAL &&
			!$this->tempUserConfig->isKnown() &&
			$this->tempUserConfig->isReservedName( $name )
		) {
			return Status::newFatal( 'userrights-no-group' );
		}

		if ( $user->getWikiId() === UserIdentity::LOCAL &&
			$this->userFactory->newFromUserIdentity( $user )->isHidden() &&
			!$this->getAuthority()->isAllowed( 'hideuser' )
		) {
			// Cannot see hidden users, pretend they don't exist
			return Status::newFatal( 'nosuchusershort', $username );
		}

		return Status::newGood( $user );
	}

	/**
	 * @since 1.15
	 *
	 * @param array $ids
	 *
	 * @return string
	 */
	public function makeGroupNameList( $ids ) {
		if ( !$ids ) {
			return $this->msg( 'rightsnone' )->inContentLanguage()->text();
		} else {
			return implode( ', ', $ids );
		}
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

	/**
	 * Show the form to edit group memberships.
	 *
	 * @param UserIdentity $user The target user
	 * @param string[] $groups Array of groups the user is in. Not used by this implementation
	 *   anymore, but kept for backward compatibility with subclasses
	 * @param UserGroupMembership[] $groupMemberships Associative array of (group name => UserGroupMembership
	 *   object) containing the groups the user is in
	 */
	protected function showEditUserGroupsForm( $user, $groups, $groupMemberships ) {
		$list = $membersList = $tempList = $tempMembersList = [];
		foreach ( $groupMemberships as $ugm ) {
			$linkG = UserGroupMembership::getLinkHTML( $ugm, $this->getContext() );
			$linkM = UserGroupMembership::getLinkHTML( $ugm, $this->getContext(), $user->getName() );
			if ( $ugm->getExpiry() ) {
				$tempList[] = $linkG;
				$tempMembersList[] = $linkM;
			} else {
				$list[] = $linkG;
				$membersList[] = $linkM;

			}
		}

		$autoList = [];
		$autoMembersList = [];

		if ( $user->getWikiId() === UserIdentity::LOCAL ) {
			// Listing autopromote groups works only on the local wiki
			foreach ( $this->userGroupManager->getUserAutopromoteGroups( $user ) as $group ) {
				$autoList[] = UserGroupMembership::getLinkHTML( $group, $this->getContext() );
				$autoMembersList[] = UserGroupMembership::getLinkHTML( $group, $this->getContext(), $user->getName() );
			}
		}

		$language = $this->getLanguage();
		$displayedList = $this->msg( 'userrights-groupsmember-type' )
			->rawParams(
				$language->commaList( array_merge( $tempList, $list ) ),
				$language->commaList( array_merge( $tempMembersList, $membersList ) )
			)->escaped();
		$displayedAutolist = $this->msg( 'userrights-groupsmember-type' )
			->rawParams(
				$language->commaList( $autoList ),
				$language->commaList( $autoMembersList )
			)->escaped();

		$grouplist = '';
		$count = count( $list ) + count( $tempList );
		if ( $count > 0 ) {
			$grouplist = $this->msg( 'userrights-groupsmember' )
				->numParams( $count )
				->params( $user->getName() )
				->parse();
			$grouplist = '<p>' . $grouplist . ' ' . $displayedList . "</p>\n";
		}

		$count = count( $autoList );
		if ( $count > 0 ) {
			$autogrouplistintro = $this->msg( 'userrights-groupsmember-auto' )
				->numParams( $count )
				->params( $user->getName() )
				->parse();
			$grouplist .= '<p>' . $autogrouplistintro . ' ' . $displayedAutolist . "</p>\n";
		}

		$systemUser = $user->getWikiId() === UserIdentity::LOCAL
			&& $this->userFactory->newFromUserIdentity( $user )->isSystemUser();
		if ( $systemUser ) {
			$systemusernote = $this->msg( 'userrights-systemuser' )
				->params( $user->getName() )
				->parse();
			$grouplist .= '<p>' . $systemusernote . "</p>\n";
		}

		// Only add an email link if the user is not a system user
		$flags = $systemUser ? 0 : Linker::TOOL_LINKS_EMAIL;
		$userToolLinks = Linker::userToolLinks(
			$user->getId( $user->getWikiId() ),
			$this->getDisplayUsername( $user ),
			false, /* default for redContribsWhenNoEdits */
			$flags
		);

		[ $groupCheckboxes, $canChangeAny ] =
			$this->groupCheckboxes( $groupMemberships, $user );
		$this->getOutput()->addHTML(
			Html::openElement(
				'form',
				[
					'method' => 'post',
					'action' => $this->getPageTitle()->getLocalURL(),
					'name' => 'editGroup',
					'id' => 'mw-userrights-form2'
				]
			) .
			Html::hidden( 'user', $this->mTarget ) .
			Html::hidden( 'wpEditToken', $this->getUser()->getEditToken( $this->mTarget ) ) .
			Html::hidden(
				'conflictcheck-originalgroups',
				implode( ',', $this->userGroupManager->getUserGroups( $user ) )
			) . // Conflict detection
			Html::openElement( 'fieldset' ) .
			Html::element(
				'legend',
				[],
				$this->msg(
					$canChangeAny ? 'userrights-editusergroup' : 'userrights-viewusergroup',
					$user->getName()
				)->text()
			) .
			$this->msg(
				$canChangeAny ? 'editinguser' : 'viewinguserrights'
			)->params( wfEscapeWikiText( $this->getDisplayUsername( $user ) ) )
				->rawParams( $userToolLinks )->parse()
		);
		if ( $canChangeAny ) {
			$this->getOutput()->addHTML(
				$this->msg( 'userrights-groups-help', $user->getName() )->parse() .
				$grouplist .
				$groupCheckboxes .
				Html::openElement( 'table', [ 'id' => 'mw-userrights-table-outer' ] ) .
					"<tr>
						<td class='mw-label'>" .
							Html::label( $this->msg( 'userrights-reason' )->text(), 'wpReason' ) .
						"</td>
						<td class='mw-input'>" .
							Html::input( 'user-reason', $this->getRequest()->getVal( 'user-reason' ) ?? false, 'text', [
								'size' => 60,
								'id' => 'wpReason',
								// HTML maxlength uses "UTF-16 code units", which means that characters outside BMP
								// (e.g. emojis) count for two each. This limit is overridden in JS to instead count
								// Unicode codepoints.
								'maxlength' => CommentStore::COMMENT_CHARACTER_LIMIT,
							] ) .
						"</td>
					</tr>
					<tr>
						<td></td>
						<td class='mw-submit'>" .
							Html::submitButton( $this->msg( 'saveusergroups', $user->getName() )->text(),
								[ 'name' => 'saveusergroups' ] +
									Linker::tooltipAndAccesskeyAttribs( 'userrights-set' )
							) .
						"</td>
					</tr>
					<tr>
						<td></td>
						<td class='mw-input'>" .
							Html::check( 'wpWatch', false, [ 'id' => 'wpWatch' ] ) .
							'&nbsp;' . Html::label( $this->msg( 'userrights-watchuser' )->text(), 'wpWatch' ) .
						"</td>
					</tr>" .
				Html::closeElement( 'table' ) . "\n"
			);
		} else {
			$this->getOutput()->addHTML( $grouplist );
		}
		$this->getOutput()->addHTML(
			Html::closeElement( 'fieldset' ) .
			Html::closeElement( 'form' ) . "\n"
		);
	}

	/**
	 * Adds a table with checkboxes where you can select what groups to add/remove
	 *
	 * @param UserGroupMembership[] $usergroups Associative array of (group name as string =>
	 *   UserGroupMembership object) for groups the user belongs to
	 * @param UserIdentity $user The target user
	 * @return array Array with 2 elements: the XHTML table element with checkxboes, and
	 * whether any groups are changeable
	 */
	private function groupCheckboxes( $usergroups, UserIdentity $user ) {
		$allgroups = $this->userGroupManager->listAllGroups();
		$ret = '';

		// Get the list of preset expiry times from the system message
		$expiryOptionsMsg = $this->msg( 'userrights-expiry-options' )->inContentLanguage();
		$expiryOptions = $expiryOptionsMsg->isDisabled()
			? []
			: XmlSelect::parseOptionsMessage( $expiryOptionsMsg->text() );

		// Put all column info into an associative array so that extensions can
		// more easily manage it.
		$columns = [ 'unchangeable' => [], 'changeable' => [] ];

		foreach ( $allgroups as $group ) {
			$set = isset( $usergroups[$group] );
			// Users who can add the group, but not remove it, can only lengthen
			// expiries, not shorten them. So they should only see the expiry
			// dropdown if the group currently has a finite expiry
			$canOnlyLengthenExpiry = ( $set && $this->canAdd( $group ) &&
				!$this->canRemove( $group ) && $usergroups[$group]->getExpiry() );
			// Should the checkbox be disabled?
			$disabledCheckbox = !(
				( $set && $this->canRemove( $group ) ) ||
				( !$set && $this->canAdd( $group ) ) );
			// Should the expiry elements be disabled?
			$disabledExpiry = $disabledCheckbox && !$canOnlyLengthenExpiry;
			// Do we need to point out that this action is irreversible?
			$irreversible = !$disabledCheckbox && (
				( $set && !$this->canAdd( $group ) ) ||
				( !$set && !$this->canRemove( $group ) ) );

			$checkbox = [
				'set' => $set,
				'disabled' => $disabledCheckbox,
				'disabled-expiry' => $disabledExpiry,
				'irreversible' => $irreversible
			];

			if ( $disabledCheckbox && $disabledExpiry ) {
				$columns['unchangeable'][$group] = $checkbox;
			} else {
				$columns['changeable'][$group] = $checkbox;
			}
		}

		// Build the HTML table
		$ret .= Html::openElement( 'table', [ 'class' => 'mw-userrights-groups' ] ) .
			"<tr>\n";
		foreach ( $columns as $name => $column ) {
			if ( $column === [] ) {
				continue;
			}
			// Messages: userrights-changeable-col, userrights-unchangeable-col
			$ret .= Html::element(
				'th',
				[],
				$this->msg( 'userrights-' . $name . '-col', count( $column ) )->text()
			);
		}

		$ret .= "</tr>\n<tr>\n";
		$uiLanguage = $this->getLanguage();
		$userName = $user->getName();
		foreach ( $columns as $column ) {
			if ( $column === [] ) {
				continue;
			}
			$ret .= "\t<td style='vertical-align:top;'>\n";
			foreach ( $column as $group => $checkbox ) {
				$member = $uiLanguage->getGroupMemberName( $group, $userName );
				if ( $checkbox['irreversible'] ) {
					$text = $this->msg( 'userrights-irreversible-marker', $member )->text();
				} elseif ( $checkbox['disabled'] && !$checkbox['disabled-expiry'] ) {
					$text = $this->msg( 'userrights-no-shorten-expiry-marker', $member )->text();
				} else {
					$text = $member;
				}
				$checkboxHtml = Html::element( 'input', [
					'type' => 'checkbox', 'name' => "wpGroup-$group", 'value' => '1',
					'id' => "wpGroup-$group", 'checked' => $checkbox['set'],
					'class' => 'mw-userrights-groupcheckbox',
					'disabled' => $checkbox['disabled'],
				] ) . '&nbsp;' . Html::label( $text, "wpGroup-$group" );

				$groups = $this->changeableGroups();
				if ( isset( $groups['unaddable'][$group] ) ) {
					$checkboxHtml .= Html::rawElement(
						'div',
						[ 'class' => 'mw-userrights-unaddable-reason' ],
						$this->msg( $groups['unaddable'][$group] )->parse()
					);
				}

				if ( $this->canProcessExpiries() ) {
					$uiUser = $this->getUser();

					$currentExpiry = isset( $usergroups[$group] ) ?
						$usergroups[$group]->getExpiry() :
						null;

					// If the user can't modify the expiry, print the current expiry below
					// it in plain text. Otherwise provide UI to set/change the expiry
					if ( $checkbox['set'] &&
						( $checkbox['irreversible'] || $checkbox['disabled-expiry'] )
					) {
						if ( $currentExpiry ) {
							$expiryFormatted = $uiLanguage->userTimeAndDate( $currentExpiry, $uiUser );
							$expiryFormattedD = $uiLanguage->userDate( $currentExpiry, $uiUser );
							$expiryFormattedT = $uiLanguage->userTime( $currentExpiry, $uiUser );
							$expiryHtml = Html::element( 'span', [],
								$this->msg( 'userrights-expiry-current' )->params(
								$expiryFormatted, $expiryFormattedD, $expiryFormattedT )->text() );
						} else {
							$expiryHtml = Html::element( 'span', [],
								$this->msg( 'userrights-expiry-none' )->text() );
						}
						// T171345: Add a hidden form element so that other groups can still be manipulated,
						// otherwise saving errors out with an invalid expiry time for this group.
						$expiryHtml .= Html::hidden( "wpExpiry-$group",
							$currentExpiry ? 'existing' : 'infinite' );
						$expiryHtml .= "<br />\n";
					} else {
						$expiryHtml = Html::element( 'span', [],
							$this->msg( 'userrights-expiry' )->text() );
						$expiryHtml .= Html::openElement( 'span' );

						// add a form element to set the expiry date
						$expiryFormOptions = new XmlSelect(
							"wpExpiry-$group",
							"mw-input-wpExpiry-$group", // forward compatibility with HTMLForm
							$currentExpiry ? 'existing' : 'infinite'
						);
						if ( $checkbox['disabled-expiry'] ) {
							$expiryFormOptions->setAttribute( 'disabled', 'disabled' );
						}

						if ( $currentExpiry ) {
							$timestamp = $uiLanguage->userTimeAndDate( $currentExpiry, $uiUser );
							$d = $uiLanguage->userDate( $currentExpiry, $uiUser );
							$t = $uiLanguage->userTime( $currentExpiry, $uiUser );
							$existingExpiryMessage = $this->msg( 'userrights-expiry-existing',
								$timestamp, $d, $t );
							$expiryFormOptions->addOption( $existingExpiryMessage->text(), 'existing' );
						}

						$expiryFormOptions->addOption(
							$this->msg( 'userrights-expiry-none' )->text(),
							'infinite'
						);
						$expiryFormOptions->addOption(
							$this->msg( 'userrights-expiry-othertime' )->text(),
							'other'
						);

						$expiryFormOptions->addOptions( $expiryOptions );

						// Add expiry dropdown
						$expiryHtml .= $expiryFormOptions->getHTML() . '<br />';

						// Add custom expiry field
						$expiryHtml .= Html::element( 'input', [
							'name' => "wpExpiry-$group-other", 'size' => 30, 'value' => '',
							'id' => "mw-input-wpExpiry-$group-other",
							'class' => 'mw-userrights-expiryfield',
							'disabled' => $checkbox['disabled-expiry'],
						] );

						// If the user group is set but the checkbox is disabled, mimic a
						// checked checkbox in the form submission
						if ( $checkbox['set'] && $checkbox['disabled'] ) {
							$expiryHtml .= Html::hidden( "wpGroup-$group", 1 );
						}

						$expiryHtml .= Html::closeElement( 'span' );
					}

					$divAttribs = [
						'id' => "mw-userrights-nested-wpGroup-$group",
						'class' => 'mw-userrights-nested',
					];
					$checkboxHtml .= "\t\t\t" . Html::rawElement( 'div', $divAttribs, $expiryHtml ) . "\n";
				}
				$ret .= "\t\t" . ( ( $checkbox['disabled'] && $checkbox['disabled-expiry'] )
					? Html::rawElement( 'div', [ 'class' => 'mw-userrights-disabled' ], $checkboxHtml )
					: Html::rawElement( 'div', [], $checkboxHtml )
				) . "\n";
			}
			$ret .= "\t</td>\n";
		}
		$ret .= Html::closeElement( 'tr' ) . Html::closeElement( 'table' );

		return [ $ret, (bool)$columns['changeable'] ];
	}

	/**
	 * @param string $group The name of the group to check
	 * @return bool Can we remove the group?
	 */
	private function canRemove( $group ) {
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
	private function canAdd( $group ) {
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
	 *   'unaddable' => [ map of unchangeable groups to reasons ]
	 *  ]
	 * @phan-return array{add:list<string>,remove:list<string>,add-self:list<string>,remove-self:list<string>}
	 */
	protected function changeableGroups() {
		if ( $this->changeableGroups === null ) {
			$authority = $this->getContext()->getAuthority();
			$groups = $this->userGroupManager->getGroupsChangeableBy( $authority );

			if ( $this->mFetchedUser !== null ) {
				// Allow extensions to define groups that cannot be added, given the target user and
				// the performer. This allows policy restrictions to be enforced via software. This
				// could be done via configuration in the future, as discussed in T393615.
				$unaddableGroups = [];
				$this->getHookRunner()->onSpecialUserRightsChangeableGroups(
					$authority,
					$this->mFetchedUser,
					$groups['add'],
					$unaddableGroups
				);

				$unaddableGroupNames = array_keys( $unaddableGroups );
				$groups['add'] = array_diff( $groups['add'], $unaddableGroupNames );
				$groups['unaddable'] = $unaddableGroups;
			}

			$this->changeableGroups = $groups;
		}

		return $this->changeableGroups;
	}

	/**
	 * Get a display user name. This includes the {@}domain part for interwiki users.
	 * Use UserIdentity::getName for {{GENDER:}} in messages and
	 * use the "display user name" for visible user names in logs or messages
	 *
	 * @param UserIdentity $user The target user
	 * @return string
	 */
	private function getDisplayUsername( UserIdentity $user ) {
		$userName = $user->getName();
		if ( $user->getWikiId() !== UserIdentity::LOCAL ) {
			$userName .= $this->getConfig()->get( MainConfigNames::UserrightsInterwikiDelimiter )
				. $user->getWikiId();
		}
		return $userName;
	}

	/**
	 * Show a rights log fragment for the specified user
	 *
	 * @param UserIdentity $user User to show log for
	 * @param OutputPage $output OutputPage to use
	 */
	protected function showLogFragment( $user, $output ) {
		$rightsLogPage = new LogPage( 'rights' );
		$output->addHTML( Html::element( 'h2', [], $rightsLogPage->getName()->text() ) );
		LogEventsList::showLogExtract( $output, 'rights',
			Title::makeTitle( NS_USER, $this->getDisplayUsername( $user ) ) );
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

	protected function getGroupName() {
		return 'users';
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.40
 */
class_alias( SpecialUserRights::class, 'UserrightsPage' );
