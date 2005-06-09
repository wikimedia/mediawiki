<?php
# Copyright (C) 2004 Brion Vibber, lcrocker, Tim Starling,
# Domas Mituzas, Ashar Voultoiz, Jens Frank, Zhengzhu.
#
# http://www.mediawiki.org/
# 
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or 
# (at your option) any later version.
# 
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
# GNU General Public License for more details.
# 
# You should have received a copy of the GNU General Public License along
# with this program; if not, write to the Free Software Foundation, Inc.,
# 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
# http://www.gnu.org/copyleft/gpl.html
/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/**
 *
 */
require_once('QueryPage.php');

/**
 * This class is used to get a list of user. The ones with specials
 * rights (sysop, bureaucrat, developer) will have them displayed
 * next to their names.
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */
class ListUsersPage extends QueryPage {
	var $requestedGroup = '';
	var $requestedUser = '';
	var $previousResult = null;
	var $concatGroups = '';
	
	function getName() {
		return 'Listusers';
	}
	function isSyndicated() { return false; }

	/**
	 * Show a drop down list to select a group as well as a user name
	 * search box.
	 * @todo localize
	 */
	function getPageHeader( ) {
		global $wgScript;
		
		// Various variables used for the form
		$action = htmlspecialchars( $wgScript );
		$title = Title::makeTitle( NS_SPECIAL, 'Listusers' );
		$special = htmlspecialchars( $title->getPrefixedDBkey() );

		// form header
		$out = '<form method="get" action="'.$action.'">' .
				'<input type="hidden" name="title" value="'.$special.'" />' .
				wfMsg( 'groups-editgroup-name' ) . '<select name="group">';

		// get all group names and IDs
		$groups = User::getAllGroups();
		
		// we want a default empty group
		$out.= '<option value=""></option>';
		
		// build the dropdown list menu using datas from the database
		foreach ( $groups as $group ) {
			$selected = ($group == $this->requestedGroup);
			$out .= wfElement( 'option',
				array_merge(
					array( 'value' => $group ),
					$selected ? array( 'selected' => 'selected' ) : array() ),
				User::getGroupName( $group ) );
		}
		$out .= '</select> ';

		$out .= wfMsg( 'specialloguserlabel' ) . '<input type="text" name="username" /> ';

		// OK button, end of form.
		$out .= '<input type="submit" /></form>';
		// congratulations the form is now build
		return $out;	
	}
	
	function getSQL() {
		$dbr =& wfGetDB( DB_SLAVE );
		$user = $dbr->tableName( 'user' );
		$user_groups = $dbr->tableName( 'user_groups' );
		
		$userspace = NS_USER;
		$sql = "SELECT 'Listusers' as type, $userspace AS namespace, user_name AS title, ug_group as value " .
			"FROM $user ".
			"LEFT JOIN $user_groups ON user_id =ug_user ";

		if($this->requestedGroup != '') {
			$sql .=  'WHERE ug_group = ' . $dbr->addQuotes( $this->requestedGroup ) . ' ';
			if($this->requestedUser != '') {
				$sql .= "AND user_name = " . $dbr->addQuotes( $this->requestedUser ) . ' ';
			}
		} else {
			if($this->requestedUser !='') {
				$sql .= "WHERE user_name = " . $dbr->addQuotes( $this->requestedUser ) . ' ';
			}	
		}
		
		return $sql;
	}
	
	/**
	 * When calling formatResult we output the previous result instead of the
	 * current one. We need an additional step to flush out the last result.
	 */
	function tryLastResult( ) {
		return true;
	}
	
	function sortDescending() {
		return false;
	}

	function appendGroups($group) {
		$this->concatGroups	.= $group.' ';	
	}

	function clearGroups() {
		$this->concatGroups = '';	
	}
	
	function formatResult( $skin, $result ) {
		global $wgContLang;
		$name = false;
		
		if( is_object( $this->previousResult ) &&
			(is_null( $result ) || ( $this->previousResult->title != $result->title ) ) ) {
			// Different username, give back name(group1,group2)
			$name = $skin->makeLink( $wgContLang->getNsText($this->previousResult->namespace) . ':' . $this->previousResult->title, $this->previousResult->title );
			$name .= $this->concatGroups ? ' ('.substr($this->concatGroups,0,-1).')' : '';
			$this->clearGroups();
		}

		if( is_object( $result ) && $result->type != '') {
			$group = $result->value;
			if ( $group ) {
				$groupName = User::getGroupName( $group );
				$this->appendGroups( $skin->makeLink( wfMsgForContent( 'administrators' ), $groupName ) );
			}
		}

		$this->previousResult = $result;
		return $name;
	}
}

/**
 * constructor
 */
function wfSpecialListusers() {
	global $wgRequest;

	list( $limit, $offset ) = wfCheckLimits();

	$slu = new ListUsersPage();
	
	/**
	 * Get some parameters
	 */
	$slu->requestedGroup = $wgRequest->getVal('group');
	$slu->requestedUser = $wgRequest->getVal('username');

	return $slu->doQuery( $offset, $limit );
}

?>
