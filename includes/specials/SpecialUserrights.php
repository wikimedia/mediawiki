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
	# The target of the local right-adjuster's interest.  Can be gotten from
	# either a GET parameter or a subpage-style parameter, so have a member
	# variable for it.
	protected $mTarget;
	protected $isself = false;

	public function __construct() {
		parent::__construct( 'Userrights' );
	}

	public function isRestricted() {
		return true;
	}

	public function userCanExecute( User $user ) {
		return $this->userCanChangeRights( $user, false );
	}

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
	 * @param $par Mixed: string if any subpage provided, else null
	 */
	public function execute( $par ) {
		// If the visitor doesn't have permissions to assign or remove
		// any groups, it's a bit silly to give them the user search prompt.

		$user = $this->getUser();

		/*
		 * If the user is blocked and they only have "partial" access
		 * (e.g. they don't have the userrights permission), then don't
		 * allow them to use Special:UserRights.
		 */
		if( $user->isBlocked() && !$user->isAllowed( 'userrights' ) ) {
			throw new UserBlockedError( $user->getBlock() );
		}

		$request = $this->getRequest();

		if( $par !== null ) {
			$this->mTarget = $par;
		} else {
			$this->mTarget = $request->getVal( 'user' );
		}

		$available = $this->changeableGroups();

		if ( $this->mTarget === null ) {
			/*
			 * If the user specified no target, and they can only
			 * edit their own groups, automatically set them as the
			 * target.
			 */
			if ( !count( $available['add'] ) && !count( $available['remove'] ) )
				$this->mTarget = $user->getName();
		}

		if ( User::getCanonicalName( $this->mTarget ) == $user->getName() ) {
			$this->isself = true;
		}

		if( !$this->userCanChangeRights( $user, true ) ) {
			// @todo FIXME: There may be intermediate groups we can mention.
			$msg = $user->isAnon() ? 'userrights-nologin' : 'userrights-notallowed';
			throw new PermissionsError( null, array( array( $msg ) ) );
		}

		$this->checkReadOnly();

		$this->setHeaders();
		$this->outputHeader();

		$out = $this->getOutput();
		$out->addModuleStyles( 'mediawiki.special' );

		// show the general form
		if ( count( $available['add'] ) || count( $available['remove'] ) ) {
			$this->switchForm();
		}

		if( $request->wasPosted() ) {
			// save settings
			if( $request->getCheck( 'saveusergroups' ) ) {
				$reason = $request->getVal( 'user-reason' );
				$tok = $request->getVal( 'wpEditToken' );
				if( $user->matchEditToken( $tok, $this->mTarget ) ) {
					$this->saveUserGroups(
						$this->mTarget,
						$reason
					);

					$out->redirect( $this->getSuccessURL() );
					return;
				}
			}
		}

		// show some more forms
		if( $this->mTarget !== null ) {
			$this->editUserGroupsForm( $this->mTarget );
		}
	}

	function getSuccessURL() {
		return $this->getTitle( $this->mTarget )->getFullURL();
	}

	/**
	 * Save user groups changes in the database.
	 * Data comes from the editUserGroupsForm() form function
	 *
	 * @param $username String: username to apply changes to.
	 * @param $reason String: reason for group change
	 * @return null
	 */
	function saveUserGroups( $username, $reason = '' ) {
		$status = $this->fetchUser( $username );
		if( !$status->isOK() ) {
			$this->getOutput()->addWikiText( $status->getWikiText() );
			return;
		} else {
			$user = $status->value;
		}

		$allgroups = $this->getAllGroups();
		$addgroup = array();
		$removegroup = array();

		// This could possibly create a highly unlikely race condition if permissions are changed between
		//  when the form is loaded and when the form is saved. Ignoring it for the moment.
		foreach ( $allgroups as $group ) {
			// We'll tell it to remove all unchecked groups, and add all checked groups.
			// Later on, this gets filtered for what can actually be removed
			if ( $this->getRequest()->getCheck( "wpGroup-$group" ) ) {
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
	 * @param $user User object
	 * @param $add Array of groups to add
	 * @param $remove Array of groups to remove
	 * @param $reason String: reason for group change
	 * @return Array: Tuple of added, then removed groups
	 */
	function doSaveUserGroups( $user, $add, $remove, $reason = '' ) {
		// Validate input set...
		$isself = ( $user->getName() == $this->getUser()->getName() );
		$groups = $user->getGroups();
		$changeable = $this->changeableGroups();
		$addable = array_merge( $changeable['add'], $isself ? $changeable['add-self'] : array() );
		$removable = array_merge( $changeable['remove'], $isself ? $changeable['remove-self'] : array() );

		$remove = array_unique(
			array_intersect( (array)$remove, $removable, $groups ) );
		$add = array_unique( array_diff(
			array_intersect( (array)$add, $addable ),
			$groups )
		);

		$oldGroups = $user->getGroups();
		$newGroups = $oldGroups;

		// remove then add groups
		if( $remove ) {
			$newGroups = array_diff( $newGroups, $remove );
			foreach( $remove as $group ) {
				$user->removeGroup( $group );
			}
		}
		if( $add ) {
			$newGroups = array_merge( $newGroups, $add );
			foreach( $add as $group ) {
				$user->addGroup( $group );
			}
		}
		$newGroups = array_unique( $newGroups );

		// Ensure that caches are cleared
		$user->invalidateCache();

		wfDebug( 'oldGroups: ' . print_r( $oldGroups, true ) );
		wfDebug( 'newGroups: ' . print_r( $newGroups, true ) );
		wfRunHooks( 'UserRights', array( &$user, $add, $remove ) );

		if( $newGroups != $oldGroups ) {
			$this->addLogEntry( $user, $oldGroups, $newGroups, $reason );
		}
		return array( $add, $remove );
	}


	/**
	 * Add a rights log entry for an action.
	 */
	function addLogEntry( $user, $oldGroups, $newGroups, $reason ) {
		$log = new LogPage( 'rights' );

		$log->addEntry( 'rights',
			$user->getUserPage(),
			$reason,
			array(
				$this->makeGroupNameListForLog( $oldGroups ),
				$this->makeGroupNameListForLog( $newGroups )
			)
		);
	}

	/**
	 * Edit user groups membership
	 * @param $username String: name of the user.
	 */
	function editUserGroupsForm( $username ) {
		$status = $this->fetchUser( $username );
		if( !$status->isOK() ) {
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
	 * @return Status object
	 */
	public function fetchUser( $username ) {
		global $wgUserrightsInterwikiDelimiter;

		$parts = explode( $wgUserrightsInterwikiDelimiter, $username );
		if( count( $parts ) < 2 ) {
			$name = trim( $username );
			$database = '';
		} else {
			list( $name, $database ) = array_map( 'trim', $parts );

			if( $database == wfWikiID() ) {
				$database = '';
			} else {
				if( !$this->getUser()->isAllowed( 'userrights-interwiki' ) ) {
					return Status::newFatal( 'userrights-no-interwiki' );
				}
				if( !UserRightsProxy::validDatabase( $database ) ) {
					return Status::newFatal( 'userrights-nodatabase', $database );
				}
			}
		}

		if( $name === '' ) {
			return Status::newFatal( 'nouserspecified' );
		}

		if( $name[0] == '#' ) {
			// Numeric ID can be specified...
			// We'll do a lookup for the name internally.
			$id = intval( substr( $name, 1 ) );

			if( $database == '' ) {
				$name = User::whoIs( $id );
			} else {
				$name = UserRightsProxy::whoIs( $database, $id );
			}

			if( !$name ) {
				return Status::newFatal( 'noname' );
			}
		} else {
			$name = User::getCanonicalName( $name );
			if( $name === false ) {
				// invalid name
				return Status::newFatal( 'nosuchusershort', $username );
			}
		}

		if( $database == '' ) {
			$user = User::newFromName( $name );
		} else {
			$user = UserRightsProxy::newFromName( $database, $name );
		}

		if( !$user || $user->isAnon() ) {
			return Status::newFatal( 'nosuchusershort', $username );
		}

		return Status::newGood( $user );
	}

	function makeGroupNameList( $ids ) {
		if( empty( $ids ) ) {
			return $this->msg( 'rightsnone' )->inContentLanguage()->text();
		} else {
			return implode( ', ', $ids );
		}
	}

	function makeGroupNameListForLog( $ids ) {
		if( empty( $ids ) ) {
			return '';
		} else {
			return $this->makeGroupNameList( $ids );
		}
	}

	/**
	 * Output a form to allow searching for a user
	 */
	function switchForm() {
		global $wgScript;
		$this->getOutput()->addHTML(
			Html::openElement( 'form', array( 'method' => 'get', 'action' => $wgScript, 'name' => 'uluser', 'id' => 'mw-userrights-form1' ) ) .
			Html::hidden( 'title',  $this->getTitle()->getPrefixedText() ) .
			Xml::fieldset( $this->msg( 'userrights-lookup-user' )->text() ) .
			Xml::inputLabel( $this->msg( 'userrights-user-editname' )->text(), 'user', 'username', 30, str_replace( '_', ' ', $this->mTarget ) ) . ' ' .
			Xml::submitButton( $this->msg( 'editusergroup' )->text() ) .
			Html::closeElement( 'fieldset' ) .
			Html::closeElement( 'form' ) . "\n"
		);
	}

	/**
	 * Go through used and available groups and return the ones that this
	 * form will be able to manipulate based on the current user's system
	 * permissions.
	 *
	 * @param $groups Array: list of groups the given user is in
	 * @return Array:  Tuple of addable, then removable groups
	 */
	protected function splitGroups( $groups ) {
		list( $addable, $removable, $addself, $removeself ) = array_values( $this->changeableGroups() );

		$removable = array_intersect(
			array_merge( $this->isself ? $removeself : array(), $removable ),
			$groups
		); // Can't remove groups the user doesn't have
		$addable = array_diff(
			array_merge( $this->isself ? $addself : array(), $addable ),
			$groups
		); // Can't add groups the user does have

		return array( $addable, $removable );
	}

	/**
	 * Show the form to edit group memberships.
	 *
	 * @param $user      User or UserRightsProxy you're editing
	 * @param $groups    Array:  Array of groups the user is in
	 */
	protected function showEditUserGroupsForm( $user, $groups ) {
		$list = array();
		foreach( $groups as $group ) {
			$list[] = self::buildGroupLink( $group );
		}

		$autolist = array();
		if ( $user instanceof User ) {
			foreach( Autopromote::getAutopromoteGroups( $user ) as $group ) {
				$autolist[] = self::buildGroupLink( $group );
			}
		}

		$grouplist = '';
		$count = count( $list );
		if( $count > 0 ) {
			$grouplist = $this->msg( 'userrights-groupsmember', $count, $user->getName() )->parse();
			$grouplist = '<p>' . $grouplist  . ' ' . $this->getLanguage()->listToText( $list ) . "</p>\n";
		}
		$count = count( $autolist );
		if( $count > 0 ) {
			$autogrouplistintro = $this->msg( 'userrights-groupsmember-auto', $count, $user->getName() )->parse();
			$grouplist .= '<p>' . $autogrouplistintro  . ' ' . $this->getLanguage()->listToText( $autolist ) . "</p>\n";
		}

		$userToolLinks = Linker::userToolLinks(
				$user->getId(),
				$user->getName(),
				false, /* default for redContribsWhenNoEdits */
				Linker::TOOL_LINKS_EMAIL /* Add "send e-mail" link */
		);

		$this->getOutput()->addHTML(
			Xml::openElement( 'form', array( 'method' => 'post', 'action' => $this->getTitle()->getLocalURL(), 'name' => 'editGroup', 'id' => 'mw-userrights-form2' ) ) .
			Html::hidden( 'user', $this->mTarget ) .
			Html::hidden( 'wpEditToken', $this->getUser()->getEditToken( $this->mTarget ) ) .
			Xml::openElement( 'fieldset' ) .
			Xml::element( 'legend', array(), $this->msg( 'userrights-editusergroup', $user->getName() )->text() ) .
			$this->msg( 'editinguser' )->params( wfEscapeWikiText( $user->getName() ) )->rawParams( $userToolLinks )->parse() .
			$this->msg( 'userrights-groups-help', $user->getName() )->parse() .
			$grouplist .
			Xml::tags( 'p', null, $this->groupCheckboxes( $groups, $user ) ) .
			Xml::openElement( 'table', array( 'id' => 'mw-userrights-table-outer' ) ) .
				"<tr>
					<td class='mw-label'>" .
						Xml::label( $this->msg( 'userrights-reason' )->text(), 'wpReason' ) .
					"</td>
					<td class='mw-input'>" .
						Xml::input( 'user-reason', 60, $this->getRequest()->getVal( 'user-reason', false ),
							array( 'id' => 'wpReason', 'maxlength' => 255 ) ) .
					"</td>
				</tr>
				<tr>
					<td></td>
					<td class='mw-submit'>" .
						Xml::submitButton( $this->msg( 'saveusergroups' )->text(),
							array( 'name' => 'saveusergroups' ) + Linker::tooltipAndAccesskeyAttribs( 'userrights-set' ) ) .
					"</td>
				</tr>" .
			Xml::closeElement( 'table' ) . "\n" .
			Xml::closeElement( 'fieldset' ) .
			Xml::closeElement( 'form' ) . "\n"
		);
	}

	/**
	 * Format a link to a group description page
	 *
	 * @param $group string
	 * @return string
	 */
	private static function buildGroupLink( $group ) {
		static $cache = array();
		if( !isset( $cache[$group] ) )
			$cache[$group] = User::makeGroupLinkHtml( $group, htmlspecialchars( User::getGroupName( $group ) ) );
		return $cache[$group];
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
	 * @param $usergroups Array: groups the user belongs to
	 * @param $user User a user object
	 * @return string XHTML table element with checkboxes
	 */
	private function groupCheckboxes( $usergroups, $user ) {
		$allgroups = $this->getAllGroups();
		$ret = '';

		# Put all column info into an associative array so that extensions can
		# more easily manage it.
		$columns = array( 'unchangeable' => array(), 'changeable' => array() );

		foreach( $allgroups as $group ) {
			$set = in_array( $group, $usergroups );
			# Should the checkbox be disabled?
			$disabled = !(
				( $set && $this->canRemove( $group ) ) ||
				( !$set && $this->canAdd( $group ) ) );
			# Do we need to point out that this action is irreversible?
			$irreversible = !$disabled && (
				( $set && !$this->canAdd( $group ) ) ||
				( !$set && !$this->canRemove( $group ) ) );

			$checkbox = array(
				'set' => $set,
				'disabled' => $disabled,
				'irreversible' => $irreversible
			);

			if( $disabled ) {
				$columns['unchangeable'][$group] = $checkbox;
			} else {
				$columns['changeable'][$group] = $checkbox;
			}
		}

		# Build the HTML table
		$ret .=	Xml::openElement( 'table', array( 'class' => 'mw-userrights-groups' ) ) .
			"<tr>\n";
		foreach( $columns as $name => $column ) {
			if( $column === array() )
				continue;
			$ret .= Xml::element( 'th', null, $this->msg( 'userrights-' . $name . '-col', count( $column ) )->text() );
		}
		$ret.= "</tr>\n<tr>\n";
		foreach( $columns as $column ) {
			if( $column === array() )
				continue;
			$ret .= "\t<td style='vertical-align:top;'>\n";
			foreach( $column as $group => $checkbox ) {
				$attr = $checkbox['disabled'] ? array( 'disabled' => 'disabled' ) : array();

				$member = User::getGroupMember( $group, $user->getName() );
				if ( $checkbox['irreversible'] ) {
					$text = $this->msg( 'userrights-irreversible-marker', $member )->escaped();
				} else {
					$text = htmlspecialchars( $member );
				}
				$checkboxHtml = Xml::checkLabel( $text, "wpGroup-" . $group,
					"wpGroup-" . $group, $checkbox['set'], $attr );
				$ret .= "\t\t" . ( $checkbox['disabled']
					? Xml::tags( 'span', array( 'class' => 'mw-userrights-disabled' ), $checkboxHtml )
					: $checkboxHtml
				) . "<br />\n";
			}
			$ret .= "\t</td>\n";
		}
		$ret .= Xml::closeElement( 'tr' ) . Xml::closeElement( 'table' );

		return $ret;
	}

	/**
	 * @param  $group String: the name of the group to check
	 * @return bool Can we remove the group?
	 */
	private function canRemove( $group ) {
		// $this->changeableGroups()['remove'] doesn't work, of course. Thanks,
		// PHP.
		$groups = $this->changeableGroups();
		return in_array( $group, $groups['remove'] ) || ( $this->isself && in_array( $group, $groups['remove-self'] ) );
	}

	/**
	 * @param $group string: the name of the group to check
	 * @return bool Can we add the group?
	 */
	private function canAdd( $group ) {
		$groups = $this->changeableGroups();
		return in_array( $group, $groups['add'] ) || ( $this->isself && in_array( $group, $groups['add-self'] ) );
	}

	/**
	 * Returns $this->getUser()->changeableGroups()
	 *
	 * @return Array array( 'add' => array( addablegroups ), 'remove' => array( removablegroups ) , 'add-self' => array( addablegroups to self), 'remove-self' => array( removable groups from self) )
	 */
	function changeableGroups() {
		return $this->getUser()->changeableGroups();
	}

	/**
	 * Show a rights log fragment for the specified user
	 *
	 * @param $user User to show log for
	 * @param $output OutputPage to use
	 */
	protected function showLogFragment( $user, $output ) {
		$rightsLogPage = new LogPage( 'rights' );
		$output->addHTML( Xml::element( 'h2', null, $rightsLogPage->getName()->text() ) );
		LogEventsList::showLogExtract( $output, 'rights', $user->getUserPage() );
	}
}
