<?php

/**
 * Special page to allow managing user group membership
 *
 * @addtogroup SpecialPage
 * @todo This code is disgusting and needs a total rewrite
 */

/** */
require_once( dirname(__FILE__) . '/HTMLForm.php');

/** Entry point */
function wfSpecialUserrights() {
	global $wgRequest;
	$form = new UserrightsForm($wgRequest);
	$form->execute();
}

/**
 * A class to manage user levels rights.
 * @addtogroup SpecialPage
 */
class UserrightsForm extends HTMLForm {
	var $mPosted, $mRequest, $mSaveprefs;
	/** Escaped local url name*/
	var $action;

	/** Constructor*/
	public function __construct( &$request ) {
		$this->mPosted = $request->wasPosted();
		$this->mRequest =& $request;
		$this->mName = 'userrights';
		$this->mReason = $request->getText( 'user-reason' );

		$titleObj = SpecialPage::getTitleFor( 'Userrights' );
		$this->action = $titleObj->escapeLocalURL();
	}

	/**
	 * Manage forms to be shown according to posted data.
	 * Depending on the submit button used, call a form or a save function.
	 */
	function execute() {
		// If the visitor doesn't have permissions to assign or remove
		// any groups, it's a bit silly to give them the user search prompt.
		$available = $this->changeableGroups();
		if( empty( $available['add'] ) && empty( $available['remove'] ) ) {
			// fixme... there may be intermediate groups we can mention.
			global $wgOut, $wgUser;
			$wgOut->showPermissionsErrorPage( array(
				$wgUser->isAnon()
					? 'userrights-nologin'
					: 'userrights-notallowed' ) );
			return;
		}
		
		// show the general form
		$this->switchForm();
		if( $this->mPosted ) {
			// show some more forms
			if( $this->mRequest->getCheck( 'ssearchuser' ) ) {
				$this->editUserGroupsForm( $this->mRequest->getVal( 'user-editname' ) );
			}

			// save settings
			if( $this->mRequest->getCheck( 'saveusergroups' ) ) {
				global $wgUser;
				$username = $this->mRequest->getVal( 'user-editname' );
				$reason = $this->mRequest->getVal( 'user-reason' );
				if( $wgUser->matchEditToken( $this->mRequest->getVal( 'wpEditToken' ), $username ) ) {
					$this->saveUserGroups( $username,
						$this->mRequest->getArray( 'member' ),
						$this->mRequest->getArray( 'available' ),
						$reason );
				}
			}
		}
	}


	/**
	 * Save user groups changes in the database.
	 * Data comes from the editUserGroupsForm() form function
	 *
	 * @param string $username Username to apply changes to.
	 * @param array $removegroup id of groups to be removed.
	 * @param array $addgroup id of groups to be added.
	 * @param string $reason Reason for group change
	 *
	 */
	function saveUserGroups( $username, $removegroup, $addgroup, $reason = '') {
		$split = $this->splitUsername( $username );
		if( !$split ) {
			return;
		}
		list( $database, $name ) = $split;

		$oldGroups = $this->getUserGroups();
		$newGroups = $oldGroups;
		// remove then add groups
		if(isset($removegroup)) {
			$newGroups = array_diff($newGroups, $removegroup);
			foreach( $removegroup as $group ) {
				$this->removeUserGroup( $group );
			}
		}
		if(isset($addgroup)) {
			$newGroups = array_merge($newGroups, $addgroup);
			foreach( $addgroup as $group ) {
				$this->addUserGroup( $group );
			}
		}
		$newGroups = array_unique( $newGroups );

		// Ensure that caches are cleared
		$this->touchUser( $database );

		wfDebug( 'oldGroups: ' . print_r( $oldGroups, true ) );
		wfDebug( 'newGroups: ' . print_r( $newGroups, true ) );
		wfRunHooks( 'UserRights', array( &$u, $addgroup, $removegroup ) );

		$log = new LogPage( 'rights' );
		$log->addEntry( 'rights', Title::makeTitleSafe( NS_USER, $username ), $this->mReason, array( $this->makeGroupNameList( $oldGroups ),
			$this->makeGroupNameList( $newGroups ) ) );
	}

	/**
	 * Edit user groups membership
	 * @param string $username Name of the user.
	 */
	function editUserGroupsForm($username) {
		global $wgOut;

		$split = $this->splitUsername( $username );
		if( !$split ) {
			return;
		}
		list( $database, $name ) = $split;

		$groups = $this->getUserGroups();
		
		$this->showEditUserGroupsForm( $username, $groups );

		if ($database == '' && $username{0} != '#') {
			$this->showLogFragment( User::newFromName($username), $wgOut );
		}
	}

	function splitUsername( $username ) {
		global $wgOut, $wgUser, $wgLocalDatabases;

		$parts = explode( '@', $username );
		if( count( $parts ) < 2 ) {
			$name = $username;
			$database = '';
		} else {
			list( $name, $database ) = $parts;
		}

		if( $database != '' && !in_array( $database, $wgLocalDatabases ) ) {
			$wgOut->addWikiText( wfMsg( 'userrights-nodatabase', $database ) );
			return;
		}

		if( $name == '' ) {
			$wgOut->addWikiText( wfMsg( 'nouserspecified' ) );
			return;
		}

		if( $name{0} != '#' ) {
			# Avoid normalization when the input is a user ID
			$name = User::getCanonicalName( $name );
			if( !$name ) {
				$wgOut->addWikiText( wfMsg( 'noname' ) );
				return;
			}
		}
		$this->db =& $this->getDB( $database );
		$this->userid = $this->getUserId( $name );

		if( $this->userid == 0 ) {
			$wgOut->addWikiText( wfMsg( 'nosuchusershort', wfEscapeWikiText( $username ) ) );
			return;
		}

		if( $database != '' && !$wgUser->isAllowed('userrights-interwiki') ) {
			$wgOut->addWikiText( wfMsg( 'userrights-no-interwiki' ) );
			return;
		}

		return array( $database, $name );
	}

	/**
	 * Open a database connection to work on for the requested user.
	 * This may be a new connection to another database for remote users.
	 * @param string $database
	 * @return Database
	 */
	function &getDB( $database ) {
		if( $database == '' ) {
			$db =& wfGetDB( DB_MASTER );
		} else {
			global $wgDBuser, $wgDBpassword;
			$server = $this->getMaster( $database );
			$db = new Database( $server, $wgDBuser, $wgDBpassword, $database );
		}
		return $db;
	}
	
	/**
	 * Return the master server to connect to for the requested database.
	 */
	function getMaster( $database ) {
		global $wgDBserver, $wgAlternateMaster;
		if( isset( $wgAlternateMaster[$database] ) ) {
			return $wgAlternateMaster[$database];
		}
		return $wgDBserver;
	}

	function getUserId( $name ) {
		if( $name === '' )
			return 0;
		return ( $name{0} == "#" )
			? IntVal( substr( $name, 1 ) )
			: IntVal( $this->db->selectField( 'user',
				'user_id',
				array( 'user_name' => $name ),
				__METHOD__ ) );
	}

	function getUserGroups() {
		$res = $this->db->select( 'user_groups',
			array( 'ug_group' ),
			array( 'ug_user' => $this->userid ),
			__METHOD__ );
		$groups = array();
		while( $row = $this->db->fetchObject( $res ) ) {
			$groups[] = $row->ug_group;
		}
		return $groups;
	}

	function addUserGroup( $group ) {
		$this->db->insert( 'user_groups',
			array(
				'ug_user' => $this->userid,
				'ug_group' => $group,
			),
			__METHOD__,
			array( 'IGNORE' ) );
	}

	function removeUserGroup( $group ) {
		$this->db->delete( 'user_groups',
			array(
				'ug_user' => $this->userid,
				'ug_group' => $group,
			),
			__METHOD__ );
	}

	function touchUser( $database ) {
		$this->db->update( 'user',
			array( 'user_touched' => $this->db->timestamp() ),
			array( 'user_id' => $this->userid ),
			__METHOD__ );
		
		global $wgMemc;
		if ( function_exists( 'wfForeignMemcKey' ) ) {
			$key = wfForeignMemcKey( $database, false, 'user', 'id', $this->userid );
		} else {
			$key = "$database:user:id:" . $this->userid;
		}
		$wgMemc->delete( $key );
	}

	function makeGroupNameList( $ids ) {
		return implode( ', ', $ids );
	}

	/**
	 * Output a form to allow searching for a user
	 */
	function switchForm() {
		global $wgOut, $wgRequest;
		$username = $wgRequest->getText( 'user-editname' );
		$form  = Xml::openElement( 'form', array( 'method' => 'post', 'action' => $this->action, 'name' => 'uluser' ) );
		$form .= '<fieldset><legend>' . wfMsgHtml( 'userrights-lookup-user' ) . '</legend>';
		$form .= '<p>' . Xml::inputLabel( wfMsg( 'userrights-user-editname' ), 'user-editname', 'username', 30, $username ) . '</p>';
		$form .= '<p>' . Xml::submitButton( wfMsg( 'editusergroup' ), array( 'name' => 'ssearchuser' ) ) . '</p>';
		$form .= '</fieldset>';
		$form .= '</form>';
		$wgOut->addHTML( $form );
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
		list($addable, $removable) = array_values( $this->changeableGroups() );
		$removable = array_intersect($removable, $groups ); // Can't remove groups the user doesn't have
		$addable   = array_diff(     $addable,   $groups ); // Can't add groups the user does have
		
		return array( $addable, $removable );
	}

	/**
	 * Show the form to edit group memberships.
	 *
	 * @todo make all CSS-y and semantic
	 * @param $username  String: Name of user you're editing
	 * @param $groups    Array:  Array of groups the user is in
	 */
	protected function showEditUserGroupsForm( $username, $groups ) {
		global $wgOut, $wgUser;
		
		list( $addable, $removable ) = $this->splitGroups( $groups );

		$wgOut->addHTML(
			Xml::openElement( 'form', array( 'method' => 'post', 'action' => $this->action, 'name' => 'editGroup' ) ) .
			Xml::hidden( 'user-editname', $username ) .
			Xml::hidden( 'wpEditToken', $wgUser->editToken( $username ) ) .
			Xml::openElement( 'fieldset' ) .
			Xml::element( 'legend', array(), wfMsg( 'userrights-editusergroup' ) ) .
			$wgOut->parse( wfMsg( 'editinguser', $username ) ) .
			$this->explainRights() .
			"<table border='0'>
			<tr>
				<td></td>
				<td>
				<table width='400'>
					<tr>
						<td width='50%'>" . $this->removeSelect( $removable ) . "</td>
						<td width='50%'>" . $this->addSelect( $addable ) . "</td>
					</tr>
				</table>
			</tr>
			<tr>
				<td colspan='2'>" .
					$wgOut->parse( wfMsg('userrights-groupshelp') ) .
				"</td>
			</tr>
			<tr>
				<td>" .
					Xml::label( wfMsg( 'userrights-reason' ), 'wpReason' ) .
				"</td>
				<td>" .
					Xml::input( 'user-reason', 60, false, array( 'id' => 'wpReason', 'maxlength' => 255 ) ) .
				"</td>
			</tr>
			<tr>
				<td></td>
				<td>" .
				Xml::submitButton( wfMsg( 'saveusergroups' ), array( 'name' => 'saveusergroups' ) ) .
				"</td>
			</tr>
			</table>\n" .
			Xml::closeElement( 'fieldset' ) .
			Xml::closeElement( 'form' ) . "\n"
		);
	}

	/**
	 * Prepare a list of groups the user is able to add and remove
	 *
	 * @return string
	 */
	private function explainRights() {
		global $wgUser, $wgLang;
		
		$out = array();
		list( $add, $remove ) = array_values( $this->changeableGroups() );
		
		if( count( $add ) > 0 )
			$out[] = wfMsgExt( 'userrights-available-add', 'parseinline', $wgLang->listToText( $add ) );
		if( count( $remove ) > 0 )
			$out[] = wfMsgExt( 'userrights-available-remove', 'parseinline', $wgLang->listToText( $remove ) );
			
		return count( $out ) > 0
			? implode( ' ', $out )
			: wfMsgExt( 'userrights-available-none', 'parseinline' );
	}

	/**
	 * Adds the <select> thingie where you can select what groups to remove
	 *
	 * @param array $groups The groups that can be removed
	 * @return string XHTML <select> element
	 */
	private function removeSelect( $groups ) {
		return $this->doSelect( $groups, 'member' );
	}

	/**
	 * Adds the <select> thingie where you can select what groups to add
	 *
	 * @param array $groups The groups that can be added
	 * @return string XHTML <select> element
	 */
	private function addSelect( $groups ) {
		return $this->doSelect( $groups, 'available' );
	}

	/**
	 * Adds the <select> thingie where you can select what groups to add/remove
	 *
	 * @param array  $groups The groups that can be added/removed
	 * @param string $name   'member' or 'available'
	 * @return string XHTML <select> element
	 */
	private function doSelect( $groups, $name ) {
		$ret = wfMsgHtml( "{$this->mName}-groups$name" ) .
		Xml::openElement( 'select', array(
				'name' => "{$name}[]",
				'multiple' => 'multiple',
				'size' => '6',
				'style' => 'width: 100%;'
			)
		);
		foreach ($groups as $group) {
			$ret .= Xml::element( 'option', array( 'value' => $group ), User::getGroupName( $group ) );
		}
		$ret .= Xml::closeElement( 'select' );
		return $ret;
	}

	/**
	 * @param  string $group The name of the group to check
	 * @return bool Can we remove the group?
	 */
	private function canRemove( $group ) {
		// $this->changeableGroups()['remove'] doesn't work, of course. Thanks,
		// PHP.
		$groups = $this->changeableGroups();
		return in_array( $group, $groups['remove'] );
	}

	/**
	 * @param  string $group The name of the group to check
	 * @return bool Can we add the group?
	 */
	private function canAdd( $group ) {
		$groups = $this->changeableGroups();
		return in_array( $group, $groups['add'] );
	}

	/**
	 * Returns an array of the groups that the user can add/remove.
	 *
	 * @return Array array( 'add' => array( addablegroups ), 'remove' => array( removablegroups ) )
	 */
	function changeableGroups() {
		global $wgUser;

		if( $wgUser->isAllowed( 'userrights' ) ) {
			// This group gives the right to modify everything (reverse-
			// compatibility with old "userrights lets you change
			// everything")
			// Using array_merge to make the groups reindexed
			$all = array_merge( User::getAllGroups() );
			return array(
				'add' => $all,
				'remove' => $all
			);
		}

		// Okay, it's not so simple, we will have to go through the arrays
		$groups = array( 'add' => array(), 'remove' => array() );
		$addergroups = $wgUser->getEffectiveGroups();

		foreach ($addergroups as $addergroup) {
			$groups = array_merge_recursive(
				$groups, $this->changeableByGroup($addergroup)
			);
			$groups['add']    = array_unique( $groups['add'] );
			$groups['remove'] = array_unique( $groups['remove'] );
		}
		return $groups;
	}

	/**
	 * Returns an array of the groups that a particular group can add/remove.
	 *
	 * @param String $group The group to check for whether it can add/remove
	 * @return Array array( 'add' => array( addablegroups ), 'remove' => array( removablegroups ) )
	 */
	private function changeableByGroup( $group ) {
		global $wgAddGroups, $wgRemoveGroups;

		$groups = array( 'add' => array(), 'remove' => array() );
		if( empty($wgAddGroups[$group]) ) {
			// Don't add anything to $groups
		} elseif( $wgAddGroups[$group] === true ) {
			// You get everything
			$groups['add'] = User::getAllGroups();
		} elseif( is_array($wgAddGroups[$group]) ) {
			$groups['add'] = $wgAddGroups[$group];
		}
		
		// Same thing for remove
		if( empty($wgRemoveGroups[$group]) ) {
		} elseif($wgRemoveGroups[$group] === true ) {
			$groups['remove'] = User::getAllGroups();
		} elseif( is_array($wgRemoveGroups[$group]) ) {
			$groups['remove'] = $wgRemoveGroups[$group];
		}
		return $groups;
	}
	
	/**
	 * Show a rights log fragment for the specified user
	 *
	 * @param User $user User to show log for
	 * @param OutputPage $output OutputPage to use
	 */
	protected function showLogFragment( $user, $output ) {
		$viewer = new LogViewer(
			new LogReader(
				new FauxRequest(
					array(
						'type' => 'rights',
						'page' => $user->getUserPage()->getPrefixedText(),
					)
				)
			)
		);
		$output->addHtml( "<h2>" . htmlspecialchars( LogPage::logName( 'rights' ) ) . "</h2>\n" );
		$viewer->showList( $output );
	}
	
}