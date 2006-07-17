<?php

# Copyright (C) 2004 Brion Vibber, lcrocker, Tim Starling,
# Domas Mituzas, Ashar Voultoiz, Jens Frank, Zhengzhu.
#
# © 2006 Rob Church <robchur@gmail.com>
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
# 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
# http://www.gnu.org/copyleft/gpl.html
/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */

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
		return 'Listusers';
	}
	function isSyndicated() { return false; }

	/**
	 * Not expensive, this class won't work properly with the caching system anyway
	 */
	function isExpensive() {
		return false;
	}

	/**
	 * Fetch user page links and cache their existence
	 */
	function preprocessResults( &$db, &$res ) {
		$batch = new LinkBatch;
		while ( $row = $db->fetchObject( $res ) ) {
			$batch->addObj( Title::makeTitleSafe( $row->namespace, $row->title ) );
		}
		$batch->execute();

		// Back to start for display
		if( $db->numRows( $res ) > 0 ) {
			// If there are no rows we get an error seeking.
			$db->dataSeek( $res, 0 );
		}
	}

	/**
	 * Show a drop down list to select a group as well as a user name
	 * search box.
	 * @todo localize
	 */
	function getPageHeader( ) {
		$self = $this->getTitle();

		# Form tag
		$out = wfOpenElement( 'form', array( 'method' => 'post', 'action' => $self->getLocalUrl() ) );
		
		# Group drop-down list
		$out .= wfElement( 'label', array( 'for' => 'group' ), wfMsg( 'group' ) ) . ' ';
		$out .= wfOpenElement( 'select', array( 'name' => 'group' ) );
		$out .= wfElement( 'option', array( 'value' => '' ), wfMsg( 'group-all' ) ); # Item for "all groups"
		$groups = User::getAllGroups();
		foreach( $groups as $group ) {
			$attribs = array( 'value' => $group );
			if( $group == $this->requestedGroup )
				$attribs['selected'] = 'selected';
			$out .= wfElement( 'option', $attribs, User::getGroupName( $group ) );
		}
		$out .= wfCloseElement( 'select' ) . ' ';;# . wfElement( 'br' );

		# Username field
		$out .= wfElement( 'label', array( 'for' => 'username' ), wfMsg( 'specialloguserlabel' ) ) . ' ';
		$out .= wfElement( 'input', array( 'type' => 'text', 'id' => 'username', 'name' => 'username',
							'value' => $this->requestedUser ) ) . ' ';

		# Preserve offset and limit
		if( $this->offset )
			$out .= wfElement( 'input', array( 'type' => 'hidden', 'name' => 'offset', 'value' => $this->offset ) );
		if( $this->limit )
			$out .= wfElement( 'input', array( 'type' => 'hidden', 'name' => 'limit', 'value' => $this->limit ) );

		# Submit button and form bottom
		$out .= wfElement( 'input', array( 'type' => 'submit', 'value' => wfMsg( 'allpagessubmit' ) ) );
		$out .= wfCloseElement( 'form' );

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
		$userPage = Title::makeTitle( $result->namespace, $result->title );
		$name = $skin->makeLinkObj( $userPage, htmlspecialchars( $userPage->getText() ) );
		$groups = null;

		if( !isset( $result->numgroups ) || $result->numgroups > 0 ) {
			$dbr =& wfGetDB( DB_SLAVE );
			$result = $dbr->select( 'user_groups',
				array( 'ug_group' ),
				array( 'ug_user' => $result->user_id ),
				'ListUsersPage::formatResult' );
			$groups = array();
			while( $row = $dbr->fetchObject( $result ) ) {
				$groups[$row->ug_group] = User::getGroupMember( $row->ug_group );
			}
			$dbr->freeResult( $result );

			if( count( $groups ) > 0 ) {
				foreach( $groups as $group => $desc ) {
					if( $page = User::getGroupPage( $group ) ) {
						$list[] = $skin->makeLinkObj( $page, htmlspecialchars( $desc ) );
					} else {
						$list[] = htmlspecialchars( $desc );
					}
				}
				$groups = implode( ', ', $list );
			} else {
				$groups = '';
			}				

		}

		return wfSpecialList( $name, $groups );
	}
}

/**
 * constructor
 * $par string (optional) A group to list users from
 */
function wfSpecialListusers( $par = null ) {
	global $wgRequest, $wgContLang;

	list( $limit, $offset ) = wfCheckLimits();


	$slu = new ListUsersPage();

	/**
	 * Get some parameters
	 */
	$groupTarget = isset($par) ? $par : $wgRequest->getVal( 'group' );
	$slu->requestedGroup = $groupTarget;

	# 'Validate' the username first
	$username = $wgRequest->getText( 'username', '' );
	$user = User::newFromName( $username );
	$slu->requestedUser = is_object( $user ) ? $user->getName() : '';

	return $slu->doQuery( $offset, $limit );
}

?>
