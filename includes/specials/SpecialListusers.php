<?php
/**
 * Copyright (C) 2004 Brion Vibber, lcrocker, Tim Starling,
 * Domas Mituzas, Ashar Voultoiz, Jens Frank, Zhengzhu.
 *
 * © 2006 Rob Church <robchur@gmail.com>
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 */

/**
 * @file
 */

/**
 * This class is used to get a list of user. The ones with specials
 * rights (sysop, bureaucrat, developer) will have them displayed
 * next to their names.
 *
 * @ingroup SpecialPage
 */
class UsersPager extends AlphabeticPager {

	function __construct( $par=null ) {
		global $wgRequest;
		$parms = explode( '/', ($par = ( $par !== null ) ? $par : '' ) );
		$symsForAll = array( '*', 'user' );
		if ( $parms[0] != '' && ( in_array( $par, User::getAllGroups() ) || in_array( $par, $symsForAll ) ) ) {
			$this->requestedGroup = $par;
			$un = $wgRequest->getText( 'username' );
		} else if ( count( $parms ) == 2 ) {
			$this->requestedGroup = $parms[0];
			$un = $parms[1];
		} else {
			$this->requestedGroup = $wgRequest->getVal( 'group' );
			$un = ( $par != '' ) ? $par : $wgRequest->getText( 'username' );
		}
		if ( in_array( $this->requestedGroup, $symsForAll ) ) {
			$this->requestedGroup = '';
		}
		$this->editsOnly = $wgRequest->getBool( 'editsOnly' );
		$this->creationSort = $wgRequest->getBool( 'creationSort' );

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
		return $this->creationSort ? 'user_id' : 'user_name';
	}

	function getQueryInfo() {
		global $wgUser;
		$dbr = wfGetDB( DB_SLAVE );
		$conds = array();
		// Don't show hidden names
		if( !$wgUser->isAllowed('hideuser') )
			$conds[] = 'ipb_deleted IS NULL';
		if( $this->requestedGroup != '' ) {
			$conds['ug_group'] = $this->requestedGroup;
			$useIndex = '';
		} else {
			$useIndex = $dbr->useIndexClause( $this->creationSort ? 'PRIMARY' : 'user_name');
		}
		if( $this->requestedUser != '' ) {
			# Sorted either by account creation or name
			if( $this->creationSort ) {
				$conds[] = 'user_id >= ' . intval( User::idFromName( $this->requestedUser ) );
			} else {
				$conds[] = 'user_name >= ' . $dbr->addQuotes( $this->requestedUser );
			}
		}
		if( $this->editsOnly ) {
			$conds[] = 'user_editcount > 0';
		}

		list ($user,$user_groups,$ipblocks) = $dbr->tableNamesN('user','user_groups','ipblocks');

		$query = array(
			'tables' => " $user $useIndex LEFT JOIN $user_groups ON user_id=ug_user
				LEFT JOIN $ipblocks ON user_id=ipb_user AND ipb_deleted=1 AND ipb_auto=0 ",
			'fields' => array(
				$this->creationSort ? 'MAX(user_name) AS user_name' : 'user_name',
				$this->creationSort ? 'user_id' : 'MAX(user_id) AS user_id',
				'MAX(user_editcount) AS edits',
				'COUNT(ug_group) AS numgroups',
				'MAX(ug_group) AS singlegroup', // the usergroup if there is only one
				'MIN(user_registration) AS creation',
				'MAX(ipb_deleted) AS ipb_deleted' // block/hide status
			),
			'options' => array('GROUP BY' => $this->creationSort ? 'user_id' : 'user_name'),
			'conds' => $conds
		);

		wfRunHooks( 'SpecialListusersQueryInfo', array( $this, &$query ) );
		return $query;
	}

	function formatRow( $row ) {
		global $wgLang;

		if ($row->user_id == 0) #Bug 16487
			return '';

		$userPage = Title::makeTitle( NS_USER, $row->user_name );
		$name = $this->getSkin()->link( $userPage, htmlspecialchars( $userPage->getText() ) );

		if( $row->numgroups > 1 || ( $this->requestedGroup && $row->numgroups == 1 ) ) {
			$list = array();
			foreach( self::getGroups( $row->user_id ) as $group )
				$list[] = self::buildGroupLink( $group );
			$groups = $wgLang->commaList( $list );
		} elseif( $row->numgroups == 1 ) {
			$groups = self::buildGroupLink( $row->singlegroup );
		} else {
			$groups = '';
		}

		$item = wfSpecialList( $name, $groups );
		if( $row->ipb_deleted ) {
			$item = "<span class=\"deleted\">$item</span>";
		}

		global $wgEdititis;
		if ( $wgEdititis ) {
			$editCount = $wgLang->formatNum( $row->edits );
			$edits = ' [' . wfMsgExt( 'usereditcount', array( 'parsemag', 'escape' ), $editCount ) . ']';
		} else {
			$edits = '';
		}

		$created = '';
		# Some rows may be NULL
		if( $row->creation ) {
			$d = $wgLang->date( wfTimestamp( TS_MW, $row->creation ), true );
			$t = $wgLang->time( wfTimestamp( TS_MW, $row->creation ), true );
			$created = ' (' . wfMsg( 'usercreated', $d, $t ) . ')';
			$created = htmlspecialchars( $created );
		}

		wfRunHooks( 'SpecialListusersFormatRow', array( &$item, $row ) );
		return "<li>{$item}{$edits}{$created}</li>";
	}

	function getBody() {
		if( !$this->mQueryDone ) {
			$this->doQuery();
		}
		$this->mResult->rewind();
		$batch = new LinkBatch;
		while ( $row = $this->mResult->fetchObject() ) {
			$batch->addObj( Title::makeTitleSafe( NS_USER, $row->user_name ) );
		}
		$batch->execute();
		$this->mResult->rewind();
		return parent::getBody();
	}

	function getPageHeader( ) {
		global $wgScript, $wgRequest;
		$self = $this->getTitle();

		# Form tag
		$out  = Xml::openElement( 'form', array( 'method' => 'get', 'action' => $wgScript, 'id' => 'mw-listusers-form' ) ) .
			Xml::fieldset( wfMsg( 'listusers' ) ) .
			Xml::hidden( 'title', $self->getPrefixedDbKey() );

		# Username field
		$out .= Xml::label( wfMsg( 'listusersfrom' ), 'offset' ) . ' ' .
			Xml::input( 'username', 20, $this->requestedUser, array( 'id' => 'offset' ) ) . ' ';

		# Group drop-down list
		$out .= Xml::label( wfMsg( 'group' ), 'group' ) . ' ' .
			Xml::openElement('select',  array( 'name' => 'group', 'id' => 'group' ) ) .
			Xml::option( wfMsg( 'group-all' ), '' );
		foreach( $this->getAllGroups() as $group => $groupText )
			$out .= Xml::option( $groupText, $group, $group == $this->requestedGroup );
		$out .= Xml::closeElement( 'select' ) . '<br />';
		$out .= Xml::checkLabel( wfMsg('listusers-editsonly'), 'editsOnly', 'editsOnly', $this->editsOnly );
		$out .= '&#160;';
		$out .= Xml::checkLabel( wfMsg('listusers-creationsort'), 'creationSort', 'creationSort', $this->creationSort );
		$out .= '<br />';

		wfRunHooks( 'SpecialListusersHeaderForm', array( $this, &$out ) );

		# Submit button and form bottom
		$out .= Xml::hidden( 'limit', $this->mLimit );
		$out .= Xml::submitButton( wfMsg( 'allpagessubmit' ) );
		wfRunHooks( 'SpecialListusersHeader', array( $this, &$out ) );
		$out .= Xml::closeElement( 'fieldset' ) .
			Xml::closeElement( 'form' );

		return $out;
	}

	/**
	 * Get a list of all explicit groups
	 * @return array
	 */
	function getAllGroups() {
		$result = array();
		foreach( User::getAllGroups() as $group ) {
			$result[$group] = User::getGroupName( $group );
		}
		asort( $result );
		return $result;
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
		wfRunHooks( 'SpecialListusersDefaultQuery', array( $this, &$query ) );
		return $query;
	}

	/**
	 * Get a list of groups the specified user belongs to
	 *
	 * @param $uid Integer: user id
	 * @return array
	 */
	protected static function getGroups( $uid ) {
		$user = User::newFromId( $uid );
		$groups = array_diff( $user->getEffectiveGroups(), $user->getImplicitGroups() );
		return $groups;
	}

	/**
	 * Format a link to a group description page
	 *
	 * @param $group String: group name
	 * @return string
	 */
	protected static function buildGroupLink( $group ) {
		static $cache = array();
		if( !isset( $cache[$group] ) )
			$cache[$group] = User::makeGroupLinkHtml( $group, htmlspecialchars( User::getGroupMember( $group ) ) );
		return $cache[$group];
	}
}

/**
 * constructor
 * $par string (optional) A group to list users from
 */
function wfSpecialListusers( $par = null ) {
	global $wgRequest, $wgOut;

	$up = new UsersPager($par);

	# getBody() first to check, if empty
	$usersbody = $up->getBody();
	$s = XML::openElement( 'div', array('class' => 'mw-spcontent') );
	$s .= $up->getPageHeader();
	if( $usersbody ) {
		$s .=	$up->getNavigationBar();
		$s .=	'<ul>' . $usersbody . '</ul>';
		$s .=	$up->getNavigationBar() ;
	} else {
		$s .=	'<p>' . wfMsgHTML('listusers-noresult') . '</p>';
	};
	$s .= XML::closeElement( 'div' );
	$wgOut->addHTML( $s );
}
