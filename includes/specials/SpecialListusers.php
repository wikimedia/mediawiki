<?php
/**
 * Implements Special:Listusers
 *
 * Copyright © 2004 Brion Vibber, lcrocker, Tim Starling,
 * Domas Mituzas, Antoine Musso, Jens Frank, Zhengzhu,
 * 2006 Rob Church <robchur@gmail.com>
 *
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
 *
 * @file
 * @ingroup SpecialPage
 */

/**
 * This class is used to get a list of user. The ones with specials
 * rights (sysop, bureaucrat, developer) will have them displayed
 * next to their names.
 *
 * @ingroup SpecialPage
 */
class UsersPager extends AlphabeticPager {

	function __construct( IContextSource $context = null, $par = null ) {
		if ( $context ) {
			$this->setContext( $context );
		}

		$request = $this->getRequest();
		$par = ( $par !== null ) ? $par : '';
		$parms = explode( '/', $par );
		$symsForAll = array( '*', 'user' );
		if ( $parms[0] != '' && ( in_array( $par, User::getAllGroups() ) || in_array( $par, $symsForAll ) ) ) {
			$this->requestedGroup = $par;
			$un = $request->getText( 'username' );
		} elseif ( count( $parms ) == 2 ) {
			$this->requestedGroup = $parms[0];
			$un = $parms[1];
		} else {
			$this->requestedGroup = $request->getVal( 'group' );
			$un = ( $par != '' ) ? $par : $request->getText( 'username' );
		}
		if ( in_array( $this->requestedGroup, $symsForAll ) ) {
			$this->requestedGroup = '';
		}
		$this->editsOnly = $request->getBool( 'editsOnly' );
		$this->creationSort = $request->getBool( 'creationSort' );

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
		$dbr = wfGetDB( DB_SLAVE );
		$conds = array();
		// Don't show hidden names
		if( !$this->getUser()->isAllowed('hideuser') ) {
			$conds[] = 'ipb_deleted IS NULL';
		}

		$options = array();

		if( $this->requestedGroup != '' ) {
			$conds['ug_group'] = $this->requestedGroup;
		} else {
			//$options['USE INDEX'] = $this->creationSort ? 'PRIMARY' : 'user_name';
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

		$options['GROUP BY'] = $this->creationSort ? 'user_id' : 'user_name';

		$query = array(
			'tables' => array( 'user', 'user_groups', 'ipblocks'),
			'fields' => array(
				$this->creationSort ? 'MAX(user_name) AS user_name' : 'user_name',
				$this->creationSort ? 'user_id' : 'MAX(user_id) AS user_id',
				'MAX(user_editcount) AS edits',
				'COUNT(ug_group) AS numgroups',
				'MAX(ug_group) AS singlegroup', // the usergroup if there is only one
				'MIN(user_registration) AS creation',
				'MAX(ipb_deleted) AS ipb_deleted' // block/hide status
			),
			'options' => $options,
			'join_conds' => array(
				'user_groups' => array( 'LEFT JOIN', 'user_id=ug_user' ),
				'ipblocks' => array( 'LEFT JOIN', 'user_id=ipb_user AND ipb_deleted=1 AND ipb_auto=0' ),
			),
			'conds' => $conds
		);

		wfRunHooks( 'SpecialListusersQueryInfo', array( $this, &$query ) );
		return $query;
	}

	function formatRow( $row ) {
		if ($row->user_id == 0) #Bug 16487
			return '';

		$userPage = Title::makeTitle( NS_USER, $row->user_name );
		$name = Linker::link( $userPage, htmlspecialchars( $userPage->getText() ) );

		$lang = $this->getLanguage();

		$groups_list = self::getGroups( $row->user_id );
		if( count( $groups_list ) > 0 ) {
			$list = array();
			foreach( $groups_list as $group )
				$list[] = self::buildGroupLink( $group, $userPage->getText() );
			$groups = $lang->commaList( $list );
		} else {
			$groups = '';
		}

		$item = $lang->specialList( $name, $groups );
		if( $row->ipb_deleted ) {
			$item = "<span class=\"deleted\">$item</span>";
		}

		global $wgEdititis;
		if ( $wgEdititis ) {
			$editCount = $lang->formatNum( $row->edits );
			$edits = ' [' . wfMsgExt( 'usereditcount', array( 'parsemag', 'escape' ), $editCount ) . ']';
		} else {
			$edits = '';
		}

		$created = '';
		# Some rows may be NULL
		if( $row->creation ) {
			$d = $lang->date( wfTimestamp( TS_MW, $row->creation ), true );
			$t = $lang->time( wfTimestamp( TS_MW, $row->creation ), true );
			$created = ' (' . wfMsgExt( 'usercreated', array( 'parsemag', 'escape' ), $d, $t, $row->user_name ) . ')';
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
		foreach ( $this->mResult as $row ) {
			$batch->addObj( Title::makeTitleSafe( NS_USER, $row->user_name ) );
		}
		$batch->execute();
		$this->mResult->rewind();
		return parent::getBody();
	}

	function getPageHeader( ) {
		global $wgScript;
		// @todo Add a PrefixedBaseDBKey
		list( $self ) = explode( '/', $this->getTitle()->getPrefixedDBkey() );

		# Form tag
		$out  = Xml::openElement( 'form', array( 'method' => 'get', 'action' => $wgScript, 'id' => 'mw-listusers-form' ) ) .
			Xml::fieldset( wfMsg( 'listusers' ) ) .
			Html::hidden( 'title', $self );

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
		$out .= Html::hidden( 'limit', $this->mLimit );
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
		$groups = array_diff( $user->getEffectiveGroups(), User::getImplicitGroups() );
		return $groups;
	}

	/**
	 * Format a link to a group description page
	 *
	 * @param $group String: group name
	 * @param $username String Username
	 * @return string
	 */
	protected static function buildGroupLink( $group, $username ) {
		return User::makeGroupLinkHtml( $group, htmlspecialchars( User::getGroupMember( $group, $username ) ) );
	}
}

/**
 * @ingroup SpecialPage
 */
class SpecialListUsers extends SpecialPage {

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct( 'Listusers' );
	}

	/**
	 * Show the special page
	 *
	 * @param $par string (optional) A group to list users from
	 */
	public function execute( $par ) {
		$this->setHeaders();
		$this->outputHeader();

		$up = new UsersPager( $this->getContext(), $par );

		# getBody() first to check, if empty
		$usersbody = $up->getBody();

		$s = $up->getPageHeader();
		if( $usersbody ) {
			$s .= $up->getNavigationBar();
			$s .= Html::rawElement( 'ul', array(), $usersbody );
			$s .= $up->getNavigationBar();
		} else {
			$s .= wfMessage( 'listusers-noresult' )->parseAsBlock();
		}

		$this->getOutput()->addHTML( $s );
	}
}
