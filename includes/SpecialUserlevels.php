<?php
/**
 * Provide an administration interface
 * DO NOT USE: INSECURE.
 */

/** */
require_once('HTMLForm.php');
require_once('Group.php');

/** Entry point */
function wfSpecialUserlevels($par=null) {
	global $wgRequest;
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
			if($this->mRequest->getCheck('seditgroup')) {
				$this->editGroupForm( $this->mRequest->getVal($this->mName.'-group-edit') ); }
			if($this->mRequest->getCheck('saddgroup')) {
				$this->editGroupForm( ); }
			if($this->mRequest->getCheck('ssearchuser')) {
				$this->editUserGroupsForm( $this->mRequest->getVal('user-editname')); }

			// save settings
			if($this->mRequest->getCheck('savegroup')) {
				$this->saveGroup($this->mRequest->getVal('editgroup-name'),
				                 $this->mRequest->getVal('editgroup-oldname'),
				                 $this->mRequest->getVal('editgroup-description'));
			
			} elseif($this->mRequest->getCheck('saveusergroups')) {
				$this->saveUserGroups($this->mRequest->getVal('user-editname'),
				                      $this->mRequest->getVal($this->mName.'-groupsmember'),
				                      $this->mRequest->getVal($this->mName.'-groupsavailable'));
			}
		}
	}

// save things !!
	/**
	 * Save a group
	 * @param string $newname Group name.
	 * @param string $oldname Old (current) group name.
	 * @param string $description Group description.
	 * @todo FIXME : doesnt validate anything.
	 */
	function saveGroup($newname, $oldname, $description) {
		$newame = trim($newname);
	
		if($oldname == '') {
		// We create a new group
			$g = new group();
			$g->addToDatabase();
		} else {
			$g = Group::newFromName($oldname);
		}
		
		// save stuff
		$g->setName($newname);
		$g->setDescription($description);
		$g->save();
	}

	/**
	 * Save user groups changes in the database.
	 * Datas comes from the editUserGroupsForm() form function
	 *
	 * @param string $username Username to apply changes to.
	 * @param array $removegroup id of groups to be removed.
	 * @param array $addgroup id of groups to be added.
	 */
	function saveUserGroups($username,$removegroup,$addgroup) {
		$u = User::NewFromName($username);
		
		print "ADD:\n";
		print_r($addgroup);
		print "DEL:\n";
		print_r($removegroup);
		
		if(is_null($u)) {
			$wgOut->addHTML('<p>'.wfMsg('nosuchusershort',$username).'</p>');
			return;
		}
		$id = $u->idForName();
		if($id == 0) {
			$wgOut->addHTML('<p>'.wfMsg('nosuchusershort',$username).'</p>');
			return;
		}		
		$u->setID( $id );

		$groups = $u->getGroups();
		// remove then add groups		
		$groups = array_diff($groups, $removegroup);
		$groups = array_merge($groups, $addgroup);
		// save groups in user object and database
		$u->setGroups($groups);
		$u->saveSettings();
	}

	/**
	 * The entry form
	 * It allow a user to select or eventually add a group as well as looking up
	 * for a username.
	 */
	function switchForm() {
		global $wgOut;
		
		// group selection		
		$wgOut->addHTML( "<form name=\"ulgroup\" action=\"$this->action\" method=\"post\">" );
		$wgOut->addHTML( $this->fieldset( 'lookup-group',
				$this->HTMLSelectGroups('group-edit', array(0 => $this->mRequest->getVal($this->mName.'-group-edit')) ) .
				' <input type="submit" name="seditgroup" value="'.wfMsg('editgroup').'">' .
				'<br /><input type="submit" name="saddgroup" value="'.wfMsg('addgroup').'">'
			));
		$wgOut->addHTML( "</form>" );
		
		// user selection
		$wgOut->addHTML( "<form name=\"uluser\" action=\"$this->action\" method=\"post\">" );
		$wgOut->addHTML( $this->fieldset( 'lookup-user',
				$this->textbox( 'user-editname' ) .
				'<input type="submit" name="ssearchuser" value="'.wfMsg('editusergroup').'">'
		));
		$wgOut->addHTML( "</form>" );
	}

	/**
	 * Edit a group properties and rights.
	 * @param string $groupname Name of a group to be edited.
	 */
	function editGroupForm($groupID = 0) {
		global $wgOut;
		
		if($this->mRequest->getVal('seditgroup')) {
		// fetch data if we edit a group
			$g = Group::newFromID($groupID);
			$gName = $g->getName();
			$gDescription = $g->getDescription();
			$fieldname = 'editgroup';
		} else {
		// default datas when we add a group
			$gName = '';
			$gDescription = '';
			$fieldname = 'addgroup';
		}

		$wgOut->addHTML( "<form name=\"editGroup\" action=\"$this->action\" method=\"post\">".
		                '<input type="hidden" name="editgroup-oldname" value="'.$gName.'">');
		$wgOut->addHTML( $this->fieldset( $fieldname,
			$this->textbox( 'editgroup-name', $gName ) .
			$this->textareabox( 'editgroup-description', $gDescription ) .
			'<input type="submit" name="savegroup" value="'.wfMsg('savegroup').'">'
			));
		$wgOut->addHTML( "</form>" );
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
		$id = $user->idForName();
		if($id == 0) {
			$wgOut->addHTML('<p>'.wfMsg('nosuchusershort',$username).'</p>');
			return;
		}		
		$user->setID( $id );
		
		$groups = $user->getGroups();

		$wgOut->addHTML( "<form name=\"editGroup\" action=\"$this->action\" method=\"post\">".
						 '<input type="hidden" name="user-editname" value="'.$username.'">');
		$wgOut->addHTML( $this->fieldset( 'editusergroup',
			wfMsg('editing', $this->mRequest->getVal('user-editname')).'.<br />' .
			'<table border="0"><tr><td>'.
			$this->HTMLSelectGroups('groupsmember', $groups,true,6).
			'</td><td>'.
			$this->HTMLSelectGroups('groupsavailable', $groups,true,6,true).
			'</td></tr></table>'.
			'<p>'.wfMsg('userlevels-groupshelp').'</p>'.
			'<input type="submit" name="saveusergroups" value="'.wfMsg('saveusergroups').'">'
			));
		$wgOut->addHTML( "</form>" );
	}


	/** Build a select with all existent groups
	 * @param string $selectname Name of this element. Name of form is automaticly prefixed.
	 * @param array $selected Array of element selected when posted. Multiples will only show them.
	 * @param boolean $multiple A multiple elements select.
	 * @param integer $size Number of element to be shown ignored for non multiple (default 6).
	 * @param boolean $reverse If true, multiple select will hide selected elements (default false).
	*/
	function HTMLSelectGroups($selectname, $selected=array(), $multiple=false, $size=6, $reverse=false) {
		$selectname = $this->mName.'-'.$selectname;
		$dbr =& wfGetDB( DB_SLAVE );
		$sql = 'SELECT group_id, group_name FROM `group`';
		$res = $dbr->query($sql,'wfSpecialAdmin');
		
		$out = wfMsg($selectname);
		$out .= '<select name="'.$selectname;
		if($multiple) {	$out.='[]" multiple size="'.$size; }
		$out.='">';
		
		while($g = $dbr->fetchObject( $res ) ) {
			if($multiple) {
				// for multiple will only show the things we want
				if(in_array($g->group_id, $selected) xor $reverse) { 
					$out .= '<option value="'.$g->group_id.'">'.$g->group_name.'</option>';
				}
			} else {
				$out .= '<option ';
				if(in_array($g->group_id, $selected)) { $out .= 'selected '; }
				$out .= 'value="'.$g->group_id.'">'.$g->group_name.'</option>';
			}
		}
			
		$out .= '</select>';
		return $out;
	}

}
?>
