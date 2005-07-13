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
				wfMsgHtml( 'groups-editgroup-name' ) . '<select name="group">';

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

		$out .= wfMsgHtml( 'specialloguserlabel' ) . '<input type="text" name="username" /> ';

		// OK button, end of form.
		$out .= '<input type="submit" /></form>';
		// congratulations the form is now build
		return $out;	
	}
	
	function getSQL() {
		$dbr =& wfGetDB( DB_SLAVE );
		$user = $dbr->tableName( 'user' );
		$user_groups = $dbr->tableName( 'user_groups' );
		
		// We need to get an 'atomic' list of users, so that we
		// don't break the list half-way through a user's group set
		// and so that lists by group will show all group memberships.
		//
		// On MySQL 4.1 we could use GROUP_CONCAT to grab group
		// assignments together with users pretty easily. On other
		// versions, it's not so easy to do it consistently.
		// For now we'll just grab the number of memberships, so
		// we can then do targetted checks on those who are in
		// non-default groups as we go down the list.
		
		$userspace = NS_USER;
		$sql = "SELECT 'Listusers' as type, $userspace AS namespace, user_name AS title, " .
			"user_name as value, user_id, COUNT(ug_group) as numgroups " .
			"FROM $user ".
			"LEFT JOIN $user_groups ON user_id=ug_user " .
			$this->userQueryWhere( $dbr ) .
			" GROUP BY user_name";
		
		return $sql;
	}
	
	function userQueryWhere( &$dbr ) {
		$conds = $this->userQueryConditions();
		return empty( $conds )
			? ""
			: "WHERE " . $dbr->makeList( $conds, LIST_AND );
	}
	
	function userQueryConditions() {
		$conds = array();
		if( $this->requestedGroup != '' ) {
			$conds['ug_group'] = $this->requestedGroup;
		}
		if( $this->requestedUser != '' ) {
			$conds['user_name'] = $this->requestedUser;
		}
		return $conds;
	}
	
	function linkParameters() {
		$conds = array();
		if( $this->requestedGroup != '' ) {
			$conds['group'] = $this->requestedGroup;
		}
		if( $this->requestedUser != '' ) {
			$conds['username'] = $this->requestedUser;
		}
		return $conds;
	}
	
	function sortDescending() {
		return false;
	}

	function formatResult( $skin, $result ) {
		global $wgContLang;
		
		$userPage = Title::makeTitle( $result->namespace, $result->title );
		$name = $skin->makeLinkObj( $userPage, htmlspecialchars( $userPage->getText() ) );
		
		if( !isset( $result->numgroups ) || $result->numgroups > 0 ) {
			$dbr =& wfGetDB( DB_SLAVE );
			$result = $dbr->select( 'user_groups',
				array( 'ug_group' ),
				array( 'ug_user' => $result->user_id ),
				'ListUsersPage::formatResult' );
			$groups = array();
			while( $row = $dbr->fetchObject( $result ) ) {
				$groups[] = User::getGroupName( $row->ug_group );
			}
			$dbr->freeResult( $result );
			
			if( count( $groups ) > 0 ) {
				$name .= ' (' .
					$skin->makeLink( wfMsgForContent( 'administrators' ),
						htmlspecialchars( implode( ', ', $groups ) ) ) .
					')';
			}
		}

		return $name;
	}	
}

/**
 * constructor
 * $par string (optional) A group to list users from
 */
function wfSpecialListusers( $par = null ) {
	global $wgRequest;

	list( $limit, $offset ) = wfCheckLimits();


	$slu = new ListUsersPage();
	
	/**
	 * Get some parameters
	 */
	$groupTarget = isset($par) ? $par : $wgRequest->getVal( 'group' );
	$slu->requestedGroup = $groupTarget;
	$slu->requestedUser = $wgRequest->getVal('username');

	return $slu->doQuery( $offset, $limit );
}

?>
