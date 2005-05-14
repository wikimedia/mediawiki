<?php
/**
 * Provide an administration interface
 * DO NOT USE: INSECURE.
 * 
 * TODO : remove everything related to group editing (SpecialGrouplevels.php)
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/** */
require_once('HTMLForm.php');
require_once('Group.php');

/** Entry point */
function wfSpecialUserrights() {
	global $wgRequest;
	$form = new UserrightsForm($wgRequest);
	$form->execute();
}

/**
 * A class to manage user levels rights.
 * @package MediaWiki
 * @subpackage SpecialPage
 */
class UserrightsForm extends HTMLForm {
	var $mPosted, $mRequest, $mSaveprefs;
	/** Escaped local url name*/
	var $action;

	/** Constructor*/
	function UserrightsForm ( &$request ) {
		$this->mPosted = $request->wasPosted();
		$this->mRequest =& $request;
		$this->mName = 'userrights';
		
		$titleObj = Title::makeTitle( NS_SPECIAL, 'Userrights' );
		$this->action = $titleObj->escapeLocalURL();
	}

	/**
	 * Manage forms to be shown according to posted data.
	 * Depending on the submit button used, call a form or a save function.
	 */
	function execute() {
		// show the general form
		$this->switchForm();
		if ( $this->mPosted ) {
			// show some more forms
			if($this->mRequest->getCheck('ssearchuser')) {
				$this->editUserGroupsForm( $this->mRequest->getVal('user-editname')); }

			// save settings
			if($this->mRequest->getCheck('saveusergroups')) {
				$this->saveUserGroups($this->mRequest->getVal('user-editname'),
				                      $this->mRequest->getArray('member'),
				                      $this->mRequest->getArray('available'));
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
	 *
	 */
	function saveUserGroups($username,$removegroup,$addgroup) {
		$u = User::newFromName($username);

		if(is_null($u)) {
			$wgOut->addHTML('<p>'.wfMsg('nosuchusershort',$username).'</p>');
			return;
		}

		if($u->getID() == 0) {
			$wgOut->addHTML('<p>'.wfMsg('nosuchusershort',$username).'</p>');
			return;
		}		

		$oldGroups = $u->getGroups();
		$newGroups = $oldGroups;
		$logcomment = ' ';
		// remove then add groups		
		if(isset($removegroup)) {
			$newGroups = array_diff($newGroups, $removegroup);
		}
		if(isset($addgroup)) {
			$newGroups = array_merge($newGroups, $addgroup);
		}
		$newGroups = array_unique( $newGroups );

		// save groups in user object and database
		$u->setGroups($newGroups);
		$u->saveSettings();

		$log = new LogPage( 'rights' );
		$log->addEntry( 'rights', Title::makeTitle( NS_USER, $u->getName() ), '', array( $this->makeGroupNameList( $oldGroups ),
			$this->makeGroupNameList( $newGroups ) ) );
	}

	function makeGroupNameList( $ids ) {
		$s = '';
		foreach( $ids as $id ) {
			if ( $s != '' ) {
				$s .= ', ';
			}
			$groupObj = Group::newFromId( $id );
			$s .= $groupObj->getExpandedName();
		}
		return $s;
	}

	/**
	 * The entry form
	 * It allows a user to look for a username and edit its groups membership
	 */
	function switchForm() {
		global $wgOut;
		
		// user selection
		$wgOut->addHTML( "<form name=\"uluser\" action=\"$this->action\" method=\"post\">\n" );
		$wgOut->addHTML( $this->fieldset( 'lookup-user',
				$this->textbox( 'user-editname' ) .
				'<input type="submit" name="ssearchuser" value="'.wfMsg('editusergroup').'" />'
		));
		$wgOut->addHTML( "</form>\n" );
	}

	/**
	 * Edit user groups membership
	 * @param string $username Name of the user.
	 */
	function editUserGroupsForm($username) {
		global $wgOut;
		
		$user = User::newFromName($username);
		$encUser = htmlspecialchars( $username );
		if(is_null($user)) {
			$wgOut->addHTML('<p>'.wfMsg('nosuchusershort', $encUser).'</p>');
			return;
		}

		if($user->getID() == 0) {
			$wgOut->addHTML('<p>'.wfMsg('nosuchusershort', $encUser).'</p>');
			return;
		}		
		
		$groups = $user->getGroups();

		$wgOut->addHTML( "<form name=\"editGroup\" action=\"$this->action\" method=\"post\">\n".
						 '<input type="hidden" name="user-editname" value="'.$encUser.'" />');
		$wgOut->addHTML( $this->fieldset( 'editusergroup',
			wfMsg('editing', $this->mRequest->getVal('user-editname')).".<br />\n" .
			'<table border="0" align="center"><tr><td>'.
			HTMLSelectGroups('member', $this->mName.'-groupsmember', $groups,true,6).
			'</td><td>'.
			HTMLSelectGroups('available', $this->mName.'-groupsavailable', $groups,true,6,true).
			'</td></tr></table>'."\n".
			'<p>'.wfMsg('userrights-groupshelp').'</p>'."\n".
			'<input type="submit" name="saveusergroups" value="'.wfMsg('saveusergroups').'" />'
			));
		$wgOut->addHTML( "</form>\n" );
	}
} // end class UserrightsForm
?>
