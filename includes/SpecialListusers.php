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
require_once("QueryPage.php");

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
		
	function getName() {
		return "Listusers";
	}
	function isSyndicated() { return false; }

	/**
	 * Show a drop down list to select a group as well as a user name
	 * search box.
	 * @TODO: localize
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
				'Group: <select name="group">' .

		// get all group names and id
		$dbr = & wfGetDB( DB_SLAVE );
		$group = $dbr->tableName( 'group' );
		$sql = "SELECT group_id, group_name FROM $group;";
		$result = $dbr->query($sql);
		
		// we want a default empty group
		$out.= '<option value=""></option>';
		
		// build the dropdown list menu using datas from the database
		while($agroup = $dbr->fetchObject( $result )) {
			$selected = ($agroup->group_id == $this->requestedGroup) ? " selected " : "" ;
			$out.= '<option value="'.$agroup->group_id.'" '.$selected.'>'.$agroup->group_name.'</option>';
		}
		$out .= '</select> ';

		$out .= 'User: <input type="text" name="username" /> ';

		// OK button, end of form.
		$out .= '<input type="submit" /></form>';
		// congratulations the form is now build
		return $out;	
	}
	
	function getSQL() {
		$dbr =& wfGetDB( DB_SLAVE );
	/* system showing possible actions for users
		$user = $dbr->tableName( 'user' );
		$user_rights = $dbr->tableName( 'user_rights' );
		$userspace = Namespace::getUser();
		return "SELECT ur_rights as type, $userspace as namespace, user_name as title, " .
			"user_name as value FROM $user LEFT JOIN $user_rights ON user_id = ur_user";
	*/
	/** Show groups instead */
		$user = $dbr->tableName( 'user' );
		$group = $dbr->tableName( 'group' );
		$user_groups = $dbr->tableName( 'user_groups' );
		
		$userspace = Namespace::getUser();
		$sql = "SELECT group_name as type, $userspace AS namespace, user_name AS title, user_name as value " .
			"FROM $user LEFT JOIN $user_groups ON user_id =ug_user " .
			"LEFT JOIN $group ON ug_group = group_id ";
		
		if($this->requestedGroup != '') {
			$sql .=  "WHERE group_id= '" . IntVal( $this->requestedGroup ) . "' ";
			if($this->requestedUser != '') {
				$sql .= "AND user_name = " . $dbr->addQuotes( $this->requestedUser ) . " ";
			}
		} else {
			if($this->requestedUser !='') {
				$sql .= "WHERE user_name = " . $dbr->addQuotes( $this->requestedUser ) . " ";
			}	
		}				
		
		return $sql;
	}
	
	function sortDescending() {
		return false;
	}

	function formatResult( $skin, $result ) {
		global $wgContLang;
		$name = $skin->makeLink( $wgContLang->getNsText($result->namespace) . ':' . $result->title, $result->title );
		if( '' != $result->type ) {
			$name .= ' (' .
			$skin->makeLink( wfMsgForContent( 'administrators' ), $result->type) .
			')';
		}
		return $name;
	}
}

/**
 * constructor
 */
function wfSpecialListusers() {
	global $wgUser, $wgOut, $wgLang, $wgRequest;

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
