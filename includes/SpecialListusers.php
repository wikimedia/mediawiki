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
 * @addtogroup SpecialPage
 */

/**
 * This class is used to get a list of user. The ones with specials
 * rights (sysop, bureaucrat, developer) will have them displayed
 * next to their names.
 *
 * @addtogroup SpecialPage
 */

class UsersPager extends AlphabeticPager {

	function __construct($group=null) {
		global $wgRequest;
		$this->requestedGroup = $group != "" ? $group : $wgRequest->getVal( 'group' );
		$this->requestedUser = $wgRequest->getText( 'username', $this->mOffset );
		
		parent::__construct();
	}


	function getIndexField() {
		return 'user_name';
	}

	function getQueryInfo() {
		$conds=array();
		if ($this->requestedGroup != "") {
			$conds['ug_group'] = $this->requestedGroup;
		}
		if ($this->requestedUser != "") {
			$conds[] = 'user_name >= ' . wfGetDB()->addQuotes( $this->requestedUser );
		}
		
		list ($user,$user_groups) = wfGetDB()->tableNamesN('user','user_groups');
		
		return array(
			'tables' => " $user LEFT JOIN $user_groups ON user_id=ug_user ",
			'fields' => array('user_name',
				'MAX(user_id) AS user_id',
				'COUNT(ug_group) AS numgroups', 
				'MAX(ug_group) AS singlegroup'),
			'options' => array('GROUP BY' => 'user_name'), 
			'conds' => $conds
		);
	}
	
	function formatRow($row) {
		$userPage = Title::makeTitle(NS_USER, $row->user_name);
		$name = $this->getSkin()->makeLinkObj( $userPage, htmlspecialchars( $userPage->getText() ) );
		$groups = array();
		if ($row->numgroups > 1 || ( $this->requestedGroup and $row->numgroups == 1) ) {
			$dbr = wfGetDB(DB_SLAVE);
			$result = $dbr->select( 'user_groups', 'ug_group',
				array( 'ug_user' => $row->user_id ),
				'UsersPager::formatRow' );
			while ( $group = $dbr->fetchObject($result)) {
				$groups[$group->ug_group] = User::getGroupMember ( $group->ug_group );
			}
			$dbr->freeResult($result);
		} elseif ($row->numgroups == 1 ) { // MAX hack inside query :)
			$groups[$row->singlegroup] = User::getGroupMember( $row->singlegroup );
		}
		
		if ( count($groups) > 0 ) {
			foreach( $groups as $group => $desc ) {
				$list[] = User::makeGroupLinkHTML( $group, $desc);
			}
			$groups = implode( ', ', $list);
		} else {
			$groups ='';
		}
		return '<li>' . wfSpecialList ($name, $groups) .'</li>';
	}
	
	function getBody() {
		if (!$this->mQueryDone) {
			$this->doQuery();
		}
		$batch = new LinkBatch;
		$db = $this->mDb;
	
		$this->mResult->rewind();
		
		while ( $row = $this->mResult->fetchObject() ) {
			$batch->addObj( Title::makeTitleSafe( NS_USER, $row->user_name ) );
		}
		$batch->execute();
		$this->mResult->rewind();
		return parent::getBody();
	}
	
	function getPageHeader( ) {
		$self = $this->getTitle();

		# Form tag
		$out = wfOpenElement( 'form', array( 'method' => 'post', 'action' => $self->getLocalUrl() ) );
		
		# Group drop-down list
		$out .= wfElement( 'label', array( 'for' => 'group' ), wfMsg( 'group' ) ) . ' ';
		$out .= wfOpenElement( 'select', array( 'name' => 'group', 'id' => 'group' ) );
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
		$out .= wfElement( 'label', array( 'for' => 'offset' ), wfMsg( 'listusersfrom' ) ) . ' ';
		$out .= wfElement( 'input', array( 'type' => 'text', 'id' => 'username', 'name' => 'username',
							'value' => $this->requestedUser ) ) . ' ';

		if( $this->mLimit )
			$out .= wfElement( 'input', array( 'type' => 'hidden', 'name' => 'limit', 'value' => $this->mLimit ) );

		# Submit button and form bottom
		$out .= wfElement( 'input', array( 'type' => 'submit', 'value' => wfMsg( 'allpagessubmit' ) ) );
		$out .= wfCloseElement( 'form' );

		return $out;
	}
	
	/**
	 * Preserve group and username offset parameters when paging
	 * @return array
	 */
	function getDefaultQuery() {
		$query = parent::getDefaultQuery();
		if( $this->requestedGroup != '' )
			$query['group'] = $this->requestedGroup;
		if( $this->requestedUser != '' )
			$query['username'] = $this->requestedUser;
		return $query;
	}
}

/**
 * constructor
 * $par string (optional) A group to list users from
 */
function wfSpecialListusers( $par = null ) {
	global $wgRequest, $wgOut;

	list( $limit, $offset ) = wfCheckLimits();

	$groupTarget = isset($par) ? $par : $wgRequest->getVal( 'group' );
	
	$up = new UsersPager($par);
	$wgOut->addHTML(
		$up->getPageHeader().
		$up->getNavigationBar().
		'<ul>' .
		$up->getBody() .
		'</ul>' .
		$up->getNavigationBar()
	);
	
	
}

?>
