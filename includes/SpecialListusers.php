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
		$un = $wgRequest->getText( 'username' );
		$this->requestedUser = '';
		if ( $un != '' ) {
			$username = Title::makeTitleSafe( NS_USER, $un );
			$this->requestedUser = $username->getText();
		}
		parent::__construct();
	}


	function getIndexField() {
		return 'user_name';
	}

	function getQueryInfo() {
		$conds=array();
		// don't show hidden names
		$conds[]='ipb_deleted IS NULL OR ipb_deleted = 0';
		if ($this->requestedGroup != "") {
			$conds['ug_group'] = $this->requestedGroup;
		}
		if ($this->requestedUser != "") {
			$conds[] = 'user_name >= ' . wfGetDB()->addQuotes( $this->requestedUser );
		}

		list ($user,$user_groups,$ipblocks) = wfGetDB()->tableNamesN('user','user_groups','ipblocks');

		return array(
			'tables' => " $user LEFT JOIN $user_groups ON user_id=ug_user LEFT JOIN $ipblocks ON user_id=ipb_user AND ipb_auto=0 ",
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
		//$ulink = $skin->userLink( $result->user, $result->user_text ) . ' ' . $skin->userToolLinks( $result->user, $result->user_text );
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
		global $wgRequest;
		$self = $this->getTitle();

		# Form tag
		$out  = Xml::openElement( 'form', array( 'method' => 'get', 'action' => $self->getLocalUrl() ) ) .
			'<fieldset>' .
			Xml::element( 'legend', array(), wfMsg( 'listusers' ) );

		# Username field
		$out .= Xml::label( wfMsg( 'listusersfrom' ), 'offset' ) . ' ' .
			Xml::input( 'username', 20, $this->requestedUser ) . ' ';

		if( $this->mLimit )
			$out .= Xml::hidden( 'limit', $this->mLimit );

		# Group drop-down list
		$out .= Xml::label( wfMsg( 'group' ), 'group' ) . ' ' .
			Xml::openElement('select',  array( 'name' => 'group', 'id' => 'group' ) ) .
			Xml::option( wfMsg( 'group-all' ), '' );  # Item for "all groups"

		$groups = User::getAllGroups();
		foreach( $groups as $group ) {
			$attribs = array( 'value' => $group );
			$attribs['selected'] = ( $group == $this->requestedGroup ) ? 'selected' : '';
			$out .= Xml::option( User::getGroupName( $group ), $attribs['value'], $attribs['selected'] );
		}
		$out .= Xml::closeElement( 'select' ) . ' ';

		# Submit button and form bottom
		$out .= Xml::submitButton( wfMsg( 'allpagessubmit' ) ) .
			'</fieldset>' .
			Xml::closeElement( 'form' );

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

	# getBody() first to check, if empty
	$usersbody = $up->getBody();
	$s = $up->getPageHeader();
	if( $usersbody ) {
		$s .=	$up->getNavigationBar();
		$s .=	'<ul>' . $usersbody . '</ul>';
		$s .=	$up->getNavigationBar() ;
	} else {
		$s .=	'<p>' . wfMsgHTML('listusers-noresult') . '</p>';
	};
        $wgOut->addHTML( $s );
}

?>
