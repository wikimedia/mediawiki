<?php
/**
 * Implements Special:Userrights
 *
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
 * @ingroup SpecialPage
 */

/**
 * Special page to allow managing user group membership
 *
 * @ingroup SpecialPage
 */
class UserrightsPage extends SpecialPage {
	/**
	 * The target of the local right-adjuster's interest.  Can be gotten from
	 * either a GET parameter or a subpage-style parameter, so have a member
	 * variable for it.
	 * @var null|string $mTarget
	 */
	protected $mTarget;
	/*
	 * @var null|User $mFetchedUser The user object of the target username or null.
	 */
	protected $mFetchedUser = null;
	protected $isself = false;

	public function __construct() {
		parent::__construct( 'Userrights' );
	}

	public function doesWrites() {
		return true;
	}

	/**
	 * @param User $user
	 * @param bool $checkIfSelf
	 * @return bool
	 */
	public function userCanChangeRights( $user, $checkIfSelf = true ) {
		$available = $this->changeableGroups();
		if ( $user->getId() == 0 ) {
			return false;
		}

		return !empty( $available['add'] )
			|| !empty( $available['remove'] )
			|| ( ( $this->isself || !$checkIfSelf ) &&
				( !empty( $available['add-self'] )
					|| !empty( $available['remove-self'] ) ) );
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
		$out = $this->getOutput();

		if ( $par !== null ) {
			$this->mTarget = $par;
		} else {
			$this->mTarget = $request->getVal( 'user' );
		}

		if ( is_string( $this->mTarget ) ) {
			$this->mTarget = trim( $this->mTarget );
		}

		$fetchedStatus = $this->fetchUser( $this->mTarget, true );
		if ( $fetchedStatus->isOK() ) {
			$this->mFetchedUser = $fetchedStatus->value;
			if ( $this->mFetchedUser instanceof User ) {
				// Set the 'relevant user' in the skin, so it displays links like Contributions,
				// User logs, UserRights, etc.
				$this->getSkin()->setRelevantUser( $this->mFetchedUser );
			}
		}

		// show a successbox, if the user rights was saved successfully
		if ( $request->getCheck( 'success' ) && $this->mFetchedUser !== null ) {
			$out->addModules( [ 'mediawiki.special.userrights' ] );
			$out->addModuleStyles( 'mediawiki.notification.convertmessagebox.styles' );
			$out->addHTML(
				Html::rawElement(
					'div',
					[
						'class' => 'mw-notify-success successbox',
						'id' => 'mw-preferences-success',
						'data-mw-autohide' => 'false',
					],
					Html::element(
						'p',
						[],
						$this->msg( 'savedrights', $this->mFetchedUser->getName() )->text()
					)
				)
			);
		}

		$this->setHeaders();
		$this->outputHeader();

		$out->addModuleStyles( 'mediawiki.special' );
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
			if ( $user->isBlocked() && !$user->isAllowed( 'userrights' ) ) {
				throw new UserBlockedError( $user->getBlock() );
			}

			$this->checkReadOnly();

			// save settings
			if ( !$fetchedStatus->isOK() ) {
				$this->getOutput()->addWikiText( $fetchedStatus->getWikiText() );

				return;
			}

			$targetUser = $this->mFetchedUser;
			if ( $targetUser instanceof User ) { // UserRightsProxy doesn't have this method (bug 61252)
				$targetUser->clearInstanceCache(); // bug 38989
			}

			if ( $request->getVal( 'conflictcheck-originalgroups' )
				!== implode( ',', $targetUser->getGroups() )
			) {
				$out->addWikiMsg( 'userrights-conflict' );
			} else {
				$this->saveUserGroups(
					$this->mTarget,
					$request->getVal( 'user-reason' ),
					$targetUser
				);

				$out->redirect( $this->getSuccessURL() );

				return;
			}
		}

		// show some more forms
		if ( $this->mTarget !== null ) {
			$this->editUserGroupsForm( $this->mTarget );
		}
	}

	function getSuccessURL() {
		return $this->getPageTitle( $this->mTarget )->getFullURL( [ 'success' => 1 ] );
	}

	/**
	 * Save user groups changes in the database.
	 * Data comes from the editUserGroupsForm() form function
	 *
	 * @param string $username Username to apply changes to.
	 * @param string $reason Reason for group change
	 * @param User|UserRightsProxy $user Target user object.
	 * @return null
	 */
	function saveUserGroups( $username, $reason, $user ) {
		$allgroups = $this->getAllGroups();
		$addgroup = [];
		$removegroup = [];

		// This could possibly create a highly unlikely race condition if permissions are changed between
		//  when the form is loaded and when the form is saved. Ignoring it for the moment.
		$wpgrouplist = $this->getRequest()->getArray( "wpgrouplist" );
		foreach ( $allgroups as $group ) {
			// We'll tell it to remove all unchecked groups, and add all checked groups.
			// Later on, this gets filtered for what can actually be removed
			if ( in_array("wpGroup-$group", $wpgrouplist ) ) {
				$addgroup[] = $group;
			} else {
				$removegroup[] = $group;
			}
		}

		$this->doSaveUserGroups( $user, $addgroup, $removegroup, $reason );
	}

	/**
	 * Save user groups changes in the database.
	 *
	 * @param User|UserRightsProxy $user
	 * @param array $add Array of groups to add
	 * @param array $remove Array of groups to remove
	 * @param string $reason Reason for group change
	 * @return array Tuple of added, then removed groups
	 */
	function doSaveUserGroups( $user, $add, $remove, $reason = '' ) {
		// Validate input set...
		$isself = $user->getName() == $this->getUser()->getName();
		$groups = $user->getGroups();
		$changeable = $this->changeableGroups();
		$addable = array_merge( $changeable['add'], $isself ? $changeable['add-self'] : [] );
		$removable = array_merge( $changeable['remove'], $isself ? $changeable['remove-self'] : [] );

		$remove = array_unique(
			array_intersect( (array)$remove, $removable, $groups ) );
		$add = array_unique( array_diff(
			array_intersect( (array)$add, $addable ),
			$groups )
		);

		$oldGroups = $user->getGroups();
		$newGroups = $oldGroups;

		// Remove then add groups
		if ( $remove ) {
			foreach ( $remove as $index => $group ) {
				if ( !$user->removeGroup( $group ) ) {
					unset( $remove[$index] );
				}
			}
			$newGroups = array_diff( $newGroups, $remove );
		}
		if ( $add ) {
			foreach ( $add as $index => $group ) {
				if ( !$user->addGroup( $group ) ) {
					unset( $add[$index] );
				}
			}
			$newGroups = array_merge( $newGroups, $add );
		}
		$newGroups = array_unique( $newGroups );

		// Ensure that caches are cleared
		$user->invalidateCache();

		// update groups in external authentication database
		Hooks::run( 'UserGroupsChanged', [ $user, $add, $remove, $this->getUser(), $reason ] );
		MediaWiki\Auth\AuthManager::callLegacyAuthPlugin(
			'updateExternalDBGroups', [ $user, $add, $remove ]
		);

		wfDebug( 'oldGroups: ' . print_r( $oldGroups, true ) . "\n" );
		wfDebug( 'newGroups: ' . print_r( $newGroups, true ) . "\n" );
		// Deprecated in favor of UserGroupsChanged hook
		Hooks::run( 'UserRights', [ &$user, $add, $remove ], '1.26' );

		if ( $newGroups != $oldGroups ) {
			$this->addLogEntry( $user, $oldGroups, $newGroups, $reason );
		}

		return [ $add, $remove ];
	}

	/**
	 * Add a rights log entry for an action.
	 * @param User $user
	 * @param array $oldGroups
	 * @param array $newGroups
	 * @param array $reason
	 */
	function addLogEntry( $user, $oldGroups, $newGroups, $reason ) {
		$logEntry = new ManualLogEntry( 'rights', 'rights' );
		$logEntry->setPerformer( $this->getUser() );
		$logEntry->setTarget( $user->getUserPage() );
		$logEntry->setComment( $reason );
		$logEntry->setParameters( [
			'4::oldgroups' => $oldGroups,
			'5::newgroups' => $newGroups,
		] );
		$logid = $logEntry->insert();
		$logEntry->publish( $logid );
	}

	/**
	 * Edit user groups membership
	 * @param string $username Name of the user.
	 */
	function editUserGroupsForm( $username ) {
		$status = $this->fetchUser( $username, true );
		if ( !$status->isOK() ) {
			$this->getOutput()->addWikiText( $status->getWikiText() );

			return;
		} else {
			$user = $status->value;
		}

		$groups = $user->getGroups();

		$this->showEditUserGroupsForm( $user, $groups );

		// This isn't really ideal logging behavior, but let's not hide the
		// interwiki logs if we're using them as is.
		$this->showLogFragment( $user, $this->getOutput() );
	}

	/**
	 * Normalize the input username, which may be local or remote, and
	 * return a user (or proxy) object for manipulating it.
	 *
	 * Side effects: error output for invalid access
	 * @param string $username
	 * @param bool $writing
	 * @return Status
	 */
	public function fetchUser( $username, $writing = true ) {
		$parts = explode( $this->getConfig()->get( 'UserrightsInterwikiDelimiter' ), $username );
		if ( count( $parts ) < 2 ) {
			$name = trim( $username );
			$database = '';
		} else {
			list( $name, $database ) = array_map( 'trim', $parts );

			if ( $database == wfWikiID() ) {
				$database = '';
			} else {
				if ( $writing && !$this->getUser()->isAllowed( 'userrights-interwiki' ) ) {
					return Status::newFatal( 'userrights-no-interwiki' );
				}
				if ( !UserRightsProxy::validDatabase( $database ) ) {
					return Status::newFatal( 'userrights-nodatabase', $database );
				}
			}
		}

		if ( $name === '' ) {
			return Status::newFatal( 'nouserspecified' );
		}

		if ( $name[0] == '#' ) {
			// Numeric ID can be specified...
			// We'll do a lookup for the name internally.
			$id = intval( substr( $name, 1 ) );

			if ( $database == '' ) {
				$name = User::whoIs( $id );
			} else {
				$name = UserRightsProxy::whoIs( $database, $id );
			}

			if ( !$name ) {
				return Status::newFatal( 'noname' );
			}
		} else {
			$name = User::getCanonicalName( $name );
			if ( $name === false ) {
				// invalid name
				return Status::newFatal( 'nosuchusershort', $username );
			}
		}

		if ( $database == '' ) {
			$user = User::newFromName( $name );
		} else {
			$user = UserRightsProxy::newFromName( $database, $name );
		}

		if ( !$user || $user->isAnon() ) {
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
		if ( empty( $ids ) ) {
			return $this->msg( 'rightsnone' )->inContentLanguage()->text();
		} else {
			return implode( ', ', $ids );
		}
	}

	/**
	 * Output a form to allow searching for a user
	 */
	function switchForm() {
		$out = $this->getOutput();
		$out->enableOOUI();
		$out->addModules( 'mediawiki.widgets' );

		$fieldset = new OOUI\FieldsetLayout( [
			'items' => [
				new OOUI\ActionFieldLayout(
					new MediaWiki\Widget\UserInputWidget( [
						'id' => 'username',
						'name' => 'user',
						'value' => str_replace( '_', ' ', $this->mTarget ),
						'align' => 'top'
					] + (
						// Set autofocus on blank input and error input
						$this->mFetchedUser === null ? [ 'autofocus' => '' ] : []
					) ),
					new OOUI\ButtonInputWidget( [
						'type' => 'submit',
						'label' => $this->msg( 'editusergroup' )->text(),
						'flags' => [ 'progressive', 'primary' ],
					] ),
					[
						'label' => $this->msg( 'userrights-user-editname' )->text(),
						'align' => 'top',
					]
				),
			],
		] );
		$out->addHTML(
			Html::openElement(
				'form',
				[
					'method' => 'get',
					'action' => wfScript(),
					'name' => 'uluser',
					'id' => 'mw-userrights-form1'
				]
			) .
			Html::hidden( 'title', $this->getPageTitle()->getPrefixedText() ) .
			new OOUI\PanelLayout( [
				'classes' => [ 'mw-userrights-ooui-wrapper' ],
				'expanded' => false,
				'padded' => true,
				'framed' => true,
				'content' => $fieldset,
			] ) .
			Html::closeElement( 'form' ) . "\n"
		);
	}

	/**
	 * Go through used and available groups and return the ones that this
	 * form will be able to manipulate based on the current user's system
	 * permissions.
	 *
	 * @param array $groups List of groups the given user is in
	 * @return array Tuple of addable, then removable groups
	 */
	protected function splitGroups( $groups ) {
		list( $addable, $removable, $addself, $removeself ) = array_values( $this->changeableGroups() );

		$removable = array_intersect(
			array_merge( $this->isself ? $removeself : [], $removable ),
			$groups
		); // Can't remove groups the user doesn't have
		$addable = array_diff(
			array_merge( $this->isself ? $addself : [], $addable ),
			$groups
		); // Can't add groups the user does have

		return [ $addable, $removable ];
	}

	/**
	 * Show the form to edit group memberships.
	 *
	 * @param User|UserRightsProxy $user User or UserRightsProxy you're editing
	 * @param array $groups Array of groups the user is in
	 */
	protected function showEditUserGroupsForm( $user, $groups ) {
		$list = [];
		$membersList = [];
		foreach ( $groups as $group ) {
			$list[] = self::buildGroupLink( $group );
			$membersList[] = self::buildGroupMemberLink( $group );
		}

		$autoList = [];
		$autoMembersList = [];
		if ( $user instanceof User ) {
			foreach ( Autopromote::getAutopromoteGroups( $user ) as $group ) {
				$autoList[] = self::buildGroupLink( $group );
				$autoMembersList[] = self::buildGroupMemberLink( $group );
			}
		}

		$language = $this->getLanguage();
		$displayedList = $this->msg( 'userrights-groupsmember-type' )
			->rawParams(
				$language->listToText( $list ),
				$language->listToText( $membersList )
			)->escaped();
		$displayedAutolist = $this->msg( 'userrights-groupsmember-type' )
			->rawParams(
				$language->listToText( $autoList ),
				$language->listToText( $autoMembersList )
			)->escaped();

		$grouplist = '';
		$count = count( $list );
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

		$userToolLinks = Linker::userToolLinks(
			$user->getId(),
			$user->getName(),
			false, /* default for redContribsWhenNoEdits */
			Linker::TOOL_LINKS_EMAIL /* Add "send e-mail" link */
		);

		$formDescriptor = [
			'grouplist' => $this->groupCheckboxes( $groups, $user ),
			'user-reason' =>
				[
					'id' => 'wpReason',
					'maxlength' => 255,
					'value' => $this->getRequest()->getVal( 'user-reason', false ),
					'label' => $this->msg( 'userrights-reason' )->text(),
					'type' => 'text',

				],
		];

		HTMLForm::factory( 'ooui', $formDescriptor, $this->getContext() )
			->setName('editGroup')
			->setFormIdentifier( 'mw-userrights-form2' )
			->setWrapperLegendMsg( 'userrights-editusergroup' )
			->setHeaderText(
				$this->msg( 'editinguser' )->params( wfEscapeWikiText( $user->getName() ) )
					->rawParams( $userToolLinks )->parse() .
				$this->msg( 'userrights-groups-help', $user->getName() )->parse() .
				$grouplist
			)
			->setSubmitTextMsg( 'saveusergroups' )
			->setSubmitName( 'saveusergroups' )
			->setSubmitID( 'saveusergroups' )
			->setAction( $this->getPageTitle()->getLocalURL() )
			->setMethod( 'post' )
			->addHiddenField( 'user', $this->mTarget )
			->addHiddenField( 'wpEditToken', $this->getUser()->getEditToken( $this->mTarget ) )
			->addHiddenField(
				'conflictcheck-originalgroups',
				implode( ',', $user->getGroups() )
			)
			->setSubmitTooltip( 'userrights-set' )
			->prepareForm()
			->displayForm( false );
	}

	/**
	 * Format a link to a group description page
	 *
	 * @param string $group
	 * @return string
	 */
	private static function buildGroupLink( $group ) {
		return User::makeGroupLinkHTML( $group, User::getGroupName( $group ) );
	}

	/**
	 * Format a link to a group member description page
	 *
	 * @param string $group
	 * @return string
	 */
	private static function buildGroupMemberLink( $group ) {
		return User::makeGroupLinkHTML( $group, User::getGroupMember( $group ) );
	}

	/**
	 * Returns an array of all groups that may be edited
	 * @return array Array of groups that may be edited.
	 */
	protected static function getAllGroups() {
		return User::getAllGroups();
	}

	/**
	 * Adds a table with checkboxes where you can select what groups to add/remove
	 *
	 * @todo Just pass the username string?
	 * @param array $usergroups Groups the user belongs to
	 * @param User $user
	 * @return Array with 2 elements: the XHTML table element with checkxboes, and
	 * whether any groups are changeable
	 */
	private function groupCheckboxes( $usergroups, $user ) {
		$allgroups = $this->getAllGroups();

		$checkedGroups = [];
		$disabledGroups = [];
		$checkboxData = [];

		foreach ( $allgroups as $group ) {
			$set = in_array( $group, $usergroups );
			if ( $set ) {
				$checkedGroups[] = "wpGroup-$group";
			}

			// Should the checkbox be disabled?
			$disabled =
				!( ( $set && $this->canRemove( $group ) ) || ( !$set && $this->canAdd( $group ) ) );

			if ( $disabled ) {
				$disabledGroups[] = "wpGroup-$group";
			}


			// Do we need to point out that this action is irreversible?
			$irreversible =
				!$disabled && ( ( $set && !$this->canAdd( $group ) ) ||
				                ( !$set && !$this->canRemove( $group ) ) );

			$member = User::getGroupMember( $group, $user->getName() );
			var_dump( $irreversible );
			if ( $irreversible ) {
				$text = $this->msg( 'userrights-irreversible-marker', $member )->text();
			} else {
				$text = $member;
			}
			$checkboxData[$text] = "wpGroup-$group";
		}

		$ret = [
			'type' => 'multiselect',
			'flatlist' => true,
		    'options' => $checkboxData,
		    'default' => $checkedGroups,
		    'disabledOptions' => $disabledGroups,
		];
		return $ret;
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
	 * Returns $this->getUser()->changeableGroups()
	 *
	 * @return array Array(
	 *   'add' => array( addablegroups ),
	 *   'remove' => array( removablegroups ),
	 *   'add-self' => array( addablegroups to self ),
	 *   'remove-self' => array( removable groups from self )
	 *  )
	 */
	function changeableGroups() {
		return $this->getUser()->changeableGroups();
	}

	/**
	 * Show a rights log fragment for the specified user
	 *
	 * @param User $user User to show log for
	 * @param OutputPage $output OutputPage to use
	 */
	protected function showLogFragment( $user, $output ) {
		$rightsLogPage = new LogPage( 'rights' );
		$output->addHTML( Xml::element( 'h2', null, $rightsLogPage->getName()->text() ) );
		LogEventsList::showLogExtract( $output, 'rights', $user->getUserPage() );
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
		$user = User::newFromName( $search );
		if ( !$user ) {
			// No prefix suggestion for invalid user
			return [];
		}
		// Autocomplete subpage as user list - public to allow caching
		return UserNamePrefixSearch::search( 'public', $search, $limit, $offset );
	}

	protected function getGroupName() {
		return 'users';
	}
}
