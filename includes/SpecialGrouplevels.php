<?php
/**
 * Provide an administration interface
 * DO NOT USE: INSECURE.
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/** */
require_once('HTMLForm.php');
require_once('Group.php');

/** Entry point */
function wfSpecialGrouplevels($par=null) {
	global $wgRequest;
	# Debug statement
	// print_r($_POST);
	$form = new GrouplevelsForm($wgRequest);
	$form->execute();
}

/**
 * A class to manage group levels rights.
 * @package MediaWiki
 * @subpackage SpecialPage
 */
class GrouplevelsForm extends HTMLForm {
	var $mPosted, $mRequest, $mSaveprefs;
	/** Escaped local url name*/
	var $action;

	/** Constructor*/
	function GrouplevelsForm ( &$request ) {
		$this->mPosted = $request->wasPosted();
		$this->mRequest = $request;
		$this->mName = 'grouplevels';
		
		$titleObj = Title::makeTitle( NS_SPECIAL, 'Grouplevels' );
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

			// save settings
			if($this->mRequest->getCheck('savegroup')) {
				$this->saveGroup($this->mRequest->getVal('editgroup-name'),
				                 $this->mRequest->getVal('editgroup-oldname'),
				                 $this->mRequest->getVal('editgroup-description'),
								 $this->mRequest->getArray('editgroup-getrights'));
			}
		}
	}

// save things !!
	/**
	 * Save a group
	 * @param string $newname Group name.
	 * @param string $oldname Old (current) group name.
	 * @param string $description Group description.
	 *
	 * @todo FIXME : doesnt validate anything. Log is incorrect.
	 */
	function saveGroup($newname, $oldname, $description, $rights) {
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
		if(isset($rights)) { $g->setRights( implode(',',$rights) ); }
		
		$g->save();

		$log = new LogPage( 'rights' );
		$log->addEntry( 'rights', Title::makeTitle( NS_SPECIAL, $g->getName()) , ' '.$g->getRights() );

	}

	/**
	 * The entry form
	 * It allows a user to edit or eventually add a group
	 */
	function switchForm() {
		global $wgOut;
		
		// group selection		
		$wgOut->addHTML( "<form name=\"ulgroup\" action=\"$this->action\" method=\"post\">\n" );
		$wgOut->addHTML( $this->fieldset( 'lookup-group',
				HTMLSelectGroups($this->mName.'-group-edit', array(0 => $this->mRequest->getVal($this->mName.'-group-edit')) ) .
				' <input type="submit" name="seditgroup" value="'.wfMsg('editgroup').'" />' .
				'<br /><input type="submit" name="saddgroup" value="'.wfMsg('addgroup').'" />'
			));
		$wgOut->addHTML( "</form>\n" );
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
			$fieldname = 'editgroup';
		} else {
		// default datas when we add a group
			$g = new group();
			$fieldname = 'addgroup';
		}

		$gName = $g->getName();
		$gDescription = $g->getDescription();


		$wgOut->addHTML( "<form name=\"editGroup\" action=\"$this->action\" method=\"post\">\n".
		                '<input type="hidden" name="editgroup-oldname" value="'.$gName.'" />');
		$wgOut->addHTML( $this->fieldset( $fieldname,
			$this->textbox( 'editgroup-name', $gName ) .
			$this->textareabox( 'editgroup-description', $gDescription ) .
			'<br /><table border="0" align="center"><tr><td>'.
			HTMLSelectRights($g->getRights()).
			'</td></tr></table>'."\n".
			'<input type="submit" name="savegroup" value="'.wfMsg('savegroup').'" />'
			));

		$wgOut->addHTML( "</form>\n" );
	}
} // end class GrouplevelsForm
?>