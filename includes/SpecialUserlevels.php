<?php
/**
 * Provide an administration interface
 * DO NOT USE: INSECURE.
 * 
 * TODO : remove everything related to group editing (SpecialGrouplevels.php)
 */

/** */
require_once('HTMLForm.php');
require_once('Group.php');

/** Entry point */
function wfSpecialUserlevels($par=null) {
	global $wgRequest;
	# Debug statement
	// print_r($_POST);
	$form = new UserlevelsForm($wgRequest);
	$form->execute();
}

/**
 * A class to manage user levels rights.
 */
class UserlevelsForm extends HTMLForm {
	var $mPosted, $mRequest, $mSaveprefs;
	/** Escaped local url name*/
	var $action;

	/** Constructor*/
	function UserlevelsForm ( &$request ) {
		$this->mPosted = $request->wasPosted();
		$this->mRequest = $request;
		$this->mName = 'userlevels';
		
		$titleObj = Title::makeTitle( NS_SPECIAL, 'Userlevels' );
		$this->action = $titleObj->escapeLocalURL();
	}

	/**
	 * Manage forms to be shown according to posted datas.
	 * Depending on the submit button used : Call a form or a saving function.
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
				                      $this->mRequest->getArray($this->mName.'-groupsmember'),
				                      $this->mRequest->getArray($this->mName.'-groupsavailable'));
			}
		}
	}

// save things !!
	/**
	 * Save user groups changes in the database.
	 * Datas comes from the editUserGroupsForm() form function
	 *
	 * @param string $username Username to apply changes to.
	 * @param array $removegroup id of groups to be removed.
	 * @param array $addgroup id of groups to be added.
	 *
	 * @todo Log groupname instead of group id.
	 */
	function saveUserGroups($username,$removegroup,$addgroup) {
		$u = User::NewFromName($username);

		if(is_null($u)) {
			$wgOut->addHTML('<p>'.wfMsg('nosuchusershort',$username).'</p>');
			return;
		}

		if($u->getID() == 0) {
			$wgOut->addHTML('<p>'.wfMsg('nosuchusershort',$username).'</p>');
			return;
		}		

		$groups = $u->getGroups();
		$logcomment = ' ';
		// remove then add groups		
		if(isset($removegroup)) {
			$groups = array_diff($groups, $removegroup);
			$logcomment .= implode( ' -', $removegroup);
			}
		if(isset($addgroup)) {
			$groups = array_merge($groups, $addgroup);
			$logcomment .= implode( ' +', $addgroup );
			}
		// save groups in user object and database
		$u->setGroups($groups);
		$u->saveSettings();

		$log = new LogPage( 'rights' );
		$log->addEntry( 'rights', Title::makeTitle( NS_USER, $u->getName() ), $logcomment );
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
		if(is_null($user)) {
			$wgOut->addHTML('<p>'.wfMsg('nosuchusershort',$username).'</p>');
			return;
		}

		if($user->getID() == 0) {
			$wgOut->addHTML('<p>'.wfMsg('nosuchusershort',$username).'</p>');
			return;
		}		
		
		$groups = $user->getGroups();

		$wgOut->addHTML( "<form name=\"editGroup\" action=\"$this->action\" method=\"post\">\n".
						 '<input type="hidden" name="user-editname" value="'.$username.'" />');
		$wgOut->addHTML( $this->fieldset( 'editusergroup',
			wfMsg('editing', $this->mRequest->getVal('user-editname')).".<br />\n" .
			'<table border="0" align="center"><tr><td>'.
			HTMLSelectGroups($this->mName.'-groupsmember', $groups,true,6).
			'</td><td>'.
			HTMLSelectGroups($this->mName.'-groupsavailable', $groups,true,6,true).
			'</td></tr></table>'."\n".
			'<p>'.wfMsg('userlevels-groupshelp').'</p>'."\n".
			'<input type="submit" name="saveusergroups" value="'.wfMsg('saveusergroups').'" />'
			));
		$wgOut->addHTML( "</form>\n" );
	}
} // end class UserlevelsForm
?>
