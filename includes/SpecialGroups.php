<?php
/**
 * Provide an administration interface
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/** */
require_once('HTMLForm.php');
require_once('Group.php');

/** Entry point */
function wfSpecialGroups() {
	global $wgRequest;
	
	$form = new GroupsForm($wgRequest);
	$form->execute();
}

/**
 * A class to manage group levels rights.
 * @package MediaWiki
 * @subpackage SpecialPage
 */
class GroupsForm extends HTMLForm {
	var $mPosted, $mRequest, $mSaveprefs, $mChangeAllowed;
	var $mNewName, $mDescription, $mOldName, $mRights, $mId;
	var $mAdd, $mEdit;
	
	/** Escaped local url name*/
	var $action, $location;

	/** Constructor*/
	function GroupsForm ( &$request ) {
		global $wgUser;
		
		$this->mPosted = $request->wasPosted();
		$this->mRequest =& $request;
		$this->mName = 'groups';

		$this->mNewName = trim( $request->getText('editgroup-name') );
		$this->mOldName = trim( $request->getText('editgroup-oldname' ) );
		$this->mDescription = trim( $request->getText( 'editgroup-description' ) );
		$this->mRights = $request->getArray( 'editgroup-getrights' );
		$this->mId = $this->mRequest->getInt('id');
		$this->mEdit = $request->getCheck('edit');
		$this->mAdd = $request->getCheck('add');


		$titleObj = Title::makeTitle( NS_SPECIAL, 'Groups' );
		$this->action = $titleObj->escapeLocalURL();
		if ( $this->mAdd ) {
			$this->location = $titleObj->getLocalURL( "add=1&id={$this->mId}" );
		} elseif ( $this->mEdit ) {
			$this->location = $titleObj->getLocalURL( "edit=1&id={$this->mId}" );
		} else {
			$this->location = $this->action;
		}

		$this->mChangeAllowed = $wgUser->isAllowed( 'grouprights' ) && !Group::getStaticGroups();
	}

	/**
	 * Manage forms to be shown according to posted data
	 * Depending on the submit button used, call a form or a saving function.
	 */
	function execute() {
		global $wgOut;

		if ( $this->mRequest->getBool( 'showrecord' ) ) {
			$this->showRecord();
		} elseif ( $this->mPosted && $this->mChangeAllowed && $this->mRequest->getCheck('savegroup') ) {
			// save settings
			$this->saveGroup();
		} elseif ( $this->mEdit ) {
			if ( $this->mPosted ) {
				$wgOut->redirect( $this->location );
			} else {			
				$this->switchForm();
				$this->editGroupForm( $this->mId ); 
			}
		} elseif ( $this->mAdd ) {
			if ( $this->mPosted ) {
				$wgOut->redirect( $this->location );
			} else {
				$this->switchForm();
				$this->editGroupForm( ); 
			}
		} else {
			$this->showAllGroups();
			if ( $this->mChangeAllowed ) {
				$this->switchForm();
			}
		}
	}

	/**
	 * Save a group
	 */
	function saveGroup() {
		global $wgOut;

		$this->mNewName = trim($this->mNewName);
	
		if ( $this->mNewName == '' ) {
			$this->editGroupForm( $this->mGroupID, 'groups-noname' );
			return false;
		}

		if($this->mOldName == '') {
			// Check if the group already exists
			$add = true;
			$g = Group::newFromName( $this->mNewName );
			if ( $g ) {
				$this->editGroupForm( 0, 'groups-already-exists' );
				return;
			}

			// Create a new group
			$g = new Group();
			$g->addToDatabase();
		} else {
			$add = false;
			$g = Group::newFromName($this->mOldName);
			if ( !$g ) {
				$this->editGroupForm( 0, 'groups-noname' );
				return;
			}
		}
		
		// save stuff
		$g->setName($this->mNewName);
		$g->setDescription($this->mDescription);
		if( is_array( $this->mRights ) ) { 
			$g->setRights( implode(',',$this->mRights) ); 
		}
		
		$g->save();
		
		// Make the log entry
		$log = new LogPage( 'rights' );
		$dummyTitle = Title::makeTitle( 0, '' );
		if ( $add ) {
			$log->addEntry( 'addgroup', $dummyTitle, '', array( $g->getNameForContent() ) );
		} else {
			if ( $this->mOldName != $this->mNewName ) {
				// Abbreviated action name, must be less than 10 bytes
				$log->addEntry( 'rngroup', $dummyTitle, '', array( Group::getMessageForContent( $this->mOldName ), 
				$g->getNameForContent() ) );
			} else {
				$log->addEntry( 'chgroup', $dummyTitle, '', array( $g->getNameForContent() ) );
			}
		}

		// Success, go back to all groups page
		$titleObj = Title::makeTitle( NS_SPECIAL, 'Groups' );
		$url = $titleObj->getLocalURL();

		$wgOut->redirect( $url );
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
				HTMLSelectGroups('id', $this->mName.'-group-edit', array(0 => $this->mRequest->getVal('id')) ) .
				' <input type="submit" name="edit" value="'.wfMsg('editgroup').'" />' .
				'<br /><input type="submit" name="add" value="'.wfMsg('addgroup').'" />'
			));
		$wgOut->addHTML( "</form>\n" );
	}

	/**
	 * Edit a group properties and rights.
	 * @param string $groupname Name of a group to be edited.
	 * @param string $error message name of the error to display
	 */
	function editGroupForm($groupID = 0, $error = '') {
		global $wgOut;

		if ( $error ) {
			$errText = wfMsg( $error );
			$wgOut->addHTML( "<p class='error'>$errText</p>" );
		}

		if($this->mRequest->getVal('edit')) {
		// fetch data if we edit a group
			$g = Group::newFromID($groupID);
			$fieldname = 'editgroup';
		} else {
		// default data when we add a group
			$g = new Group();
			$fieldname = 'addgroup';
		}

		$gName = htmlspecialchars( $g->getName() );
		$gDescription = htmlspecialchars( $g->getDescription() );


		$wgOut->addHTML( "<form name=\"editGroup\" action=\"{$this->action}\" method=\"post\">\n".
		                '<input type="hidden" name="editgroup-oldname" value="'.$gName."\" />\n" );

		$wgOut->addHTML( $this->fieldset( $fieldname,
			'<p>' . wfMsg( 'groups-editgroup-preamble' ) . "</p>\n" .
			$this->textbox( 'editgroup-name', $gName ) .
			$this->textareabox( 'editgroup-description', $gDescription ) .
			'<br /><table border="0" align="center"><tr><td>'.
			HTMLSelectRights($g->getRights()).
			'</td></tr></table>'."\n".
			'<input type="submit" name="savegroup" value="'.wfMsg('savegroup').'" />'
			));

		$wgOut->addHTML( "</form>\n" );
	}

	function showAllGroups() {
		global $wgOut;
		$groups =& Group::getAllGroups();

		$groupsExisting = wfMsg( 'groups-existing' );
		$groupsHeader = wfMsg( 'groups-tableheader' );

		$s = "{| border=1
|+'''$groupsExisting'''
|-
!$groupsHeader
";
		foreach ( $groups as $group ) {
			$s .= "|-\n| " . $group->getId() . ' || ' .
				$group->getExpandedName() . ' || ' .
				$group->getExpandedDescription() . ' || '. 
				// Insert spaces to make it wrap
				str_replace( ',', ', ', $group->getRights() ) . "\n";
		}
		$s .= "|}\n";
		$wgOut->addWikiText( $s );
	}
		
	function showRecord() {
		global $wgOut;
		
		$groups =& Group::getAllGroups();
		$rec = serialize( $groups );
		// Split it into lines
		$rec = explode( "\r\n", chunk_split( $rec ) );
		$s = '';
		foreach ( $rec as $index => $line ) {
			if ( trim( $line ) != '' ) {
				if ( $s ) {
					$s .= "' .\n\t'";
				}
				// Escape it for PHP
				$line = str_replace( array( '\\', "'" ), array( '\\\\', "\\'" ), $line );
				// Escape it for HTML
				$line = htmlspecialchars( $line );
				// Add it to the string
				$s .= $line;
			}
		}
		$s .= "';";
		$s = "<p>Copy the following into LocalSettings.php:</p>\n" .
		  "<textarea readonly rows=20>\n" .
		  "\$wgStaticGroups = \n\t'$s\n" .
		  "</textarea>";
		$wgOut->addHTML( $s );
	}

} // end class GroupsForm
?>
