<?php

# Copyright (C) 2008 Aaron Schulz
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

class ActiveUsersPager extends AlphabeticPager {

	function __construct($group=null) {
		global $wgRequest;
		$un = $wgRequest->getText( 'username' );
		$this->requestedUser = '';
		if ( $un != '' ) {
			$username = Title::makeTitleSafe( NS_USER, $un );
			if( ! is_null( $username ) ) {
				$this->requestedUser = $username->getText();
			}
		}
		parent::__construct();
	}


	function getIndexField() {
		return 'rc_user_text';
	}

	function getQueryInfo() {
		$dbr = wfGetDB( DB_SLAVE );
		$conds=array();
		// don't show hidden names
		$conds[]='ipb_deleted IS NULL OR ipb_deleted = 0';
		$useIndex = $dbr->useIndexClause('rc_user_text');
		if ($this->requestedUser != "") {
			$conds[] = 'rc_user_text >= ' . $dbr->addQuotes( $this->requestedUser );
		}

		list ($recentchanges,$ipblocks) = $dbr->tableNamesN('recentchanges','ipblocks');

		$query = array(
			'tables' => " $recentchanges $useIndex 
				LEFT JOIN $ipblocks ON rc_user=ipb_user AND ipb_auto=0 ",
			'fields' => array('rc_user_text',
				'MAX(rc_user) AS user_id',
				'COUNT(*) AS recentedits',
				'MAX(ipb_user) AS blocked'),
			'options' => array('GROUP BY' => 'rc_user_text'),
			'conds' => $conds
		);

		return $query;
	}

	function formatRow( $row ) {
		$userPage = Title::makeTitle( NS_USER, $row->rc_user_text );
		$name = $this->getSkin()->makeLinkObj( $userPage, htmlspecialchars( $userPage->getText() ) );

		$list = array();
		foreach( self::getGroups( $row->user_id ) as $group )
			$list[] = self::buildGroupLink( $group );
		$groups = implode( ', ', $list );

		$item = wfSpecialList( $name, $groups );
		$count = wfMsgHtml( 'activeusers-count', $row->recentedits );
		$blocked = $row->blocked ? ' '.wfMsg('listusers-blocked') : '';

		return "<li>{$item} [{$count}]{$blocked}</li>";
	}

	function getBody() {
		if (!$this->mQueryDone) {
			$this->doQuery();
		}
		$batch = new LinkBatch();

		$this->mResult->rewind();
		while ( $row = $this->mResult->fetchObject() ) {
			$batch->addObj( Title::makeTitleSafe( NS_USER, $row->rc_user_text ) );
		}
		$batch->execute();
		$this->mResult->rewind();

		return parent::getBody();
	}

	function getPageHeader( ) {
		global $wgScript, $wgRequest;
		$self = $this->getTitle();

		# Form tag
		$out  = Xml::openElement( 'form', array( 'method' => 'get', 'action' => $wgScript ) ) .
			'<fieldset>' .
			Xml::element( 'legend', array(), wfMsg( 'activeusers' ) );
		$out .= Xml::hidden( 'title', $self->getPrefixedDbKey() );

		# Username field
		$out .= Xml::label( wfMsg( 'activeusers-from' ), 'offset' ) . ' ' .
			Xml::input( 'username', 20, $this->requestedUser, array( 'id' => 'offset' ) ) . ' ';

		# Submit button and form bottom
		if( $this->mLimit )
			$out .= Xml::hidden( 'limit', $this->mLimit );
		$out .= Xml::submitButton( wfMsg( 'allpagessubmit' ) );

		$out .= '</fieldset>' .
			Xml::closeElement( 'form' );

		return $out;
	}

	function getAllGroups() {
		$result = array();
		foreach( User::getAllGroups() as $group ) {
			$result[$group] = User::getGroupName( $group );
		}
		return $result;
	}

	/**
	 * Preserve group and username offset parameters when paging
	 * @return array
	 */
	function getDefaultQuery() {
		$query = parent::getDefaultQuery();
		if( $this->requestedUser != '' )
			$query['username'] = $this->requestedUser;
		return $query;
	}

	/**
	 * Get a list of groups the specified user belongs to
	 *
	 * @param int $uid
	 * @return array
	 */
	protected static function getGroups( $uid ) {
		$dbr = wfGetDB( DB_SLAVE );
		$groups = array();
		$res = $dbr->select( 'user_groups', 'ug_group', array( 'ug_user' => $uid ), __METHOD__ );
		if( $res && $dbr->numRows( $res ) > 0 ) {
			while( $row = $dbr->fetchObject( $res ) )
				$groups[] = $row->ug_group;
			$dbr->freeResult( $res );
		}
		return $groups;
	}

	/**
	 * Format a link to a group description page
	 *
	 * @param string $group
	 * @return string
	 */
	protected static function buildGroupLink( $group ) {
		static $cache = array();
		if( !isset( $cache[$group] ) )
			$cache[$group] = User::makeGroupLinkHtml( $group, User::getGroupMember( $group ) );
		return $cache[$group];
	}
}

/**
 * constructor
 * $par string (optional) A group to list users from
 */
function wfSpecialActiveusers( $par = null ) {
	global $wgRequest, $wgOut;

	$up = new ActiveUsersPager();

	# getBody() first to check, if empty
	$usersbody = $up->getBody();
	$s = $up->getPageHeader();
	if( $usersbody ) {
		$s .=	$up->getNavigationBar();
		$s .=	'<ul>' . $usersbody . '</ul>';
		$s .=	$up->getNavigationBar() ;
	} else {
		$s .=	'<p>' . wfMsgHTML('activeusers-noresult') . '</p>';
	};

	$wgOut->addHTML( $s );
}
