<?php
/**
 * Implements Special:Listusers
 *
 * Copyright © 2004 Brion Vibber, lcrocker, Tim Starling,
 * Domas Mituzas, Ashar Voultoiz, Jens Frank, Zhengzhu,
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
class SpecialListUsers extends QueryPage {

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

		$this->limit = $wgRequest->getVal( 'limit' );
		$this->offset = $wgRequest->getVal( 'offset' );

		parent::__construct( 'Listusers' );
	}

	function isExpensive() { return false; }
	function isCacheable() { return false; }
	function isSyndicated() { return false; }
	function sortDescending() { return false; }

	function openList( $offset ) {
		return "\n<ul class='special'>\n";
	}

	function closeList() {
		return "</ul>\n";
	}

	function linkParameters() {
		return array(
			'group' => $this->requestedGroup,
			'editsOnly' => $this->editsOnly,
			'creationSort' => $this->creationSort,
			'requestedUser' => $this->requestedUser,
		);
	}

	function getQueryInfo() {
		
		global $wgUser;
		$dbr = wfGetDB( DB_SLAVE );
		$conds = $jconds = array();
		$tables = array( 'user' );
		
		// Don't show hidden names
		if( !$wgUser->isAllowed('hideuser') ) {
			$tables[] = 'ipblocks';
			$conds[] = 'ipb_deleted IS NULL';
			$jconds['ipblocks'] = array( 'LEFT JOIN', array(
				# Unique index on (ipb_address,ipb_user,ipb_auto)
				'ipb_address = user_name',
				'ipb_user = user_id',
				'ipb_auto = 0',
				'ipb_deleted = 1',
			) );
		}

		if( $this->requestedGroup != '' ) {
			$tables[] = 'user_groups';
			$conds['ug_group'] = $this->requestedGroup;
			# Unique index on (ug_user,ug_group)
			$jconds['user_groups'] = array( 'LEFT JOIN', 'user_id = ug_user' );
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

		$query = array(
			'tables' => $tables,
			'fields' => '*',
			'join_conds' => $jconds,
			'conds' => $conds
		);

		wfRunHooks( 'SpecialListusersQueryInfo', array( $this, &$query ) );
		return $query;
	}

	function formatResult( $skin, $row ) {
		global $wgLang;

		if ( $row->user_id == 0 ){
			#Bug 16487
			return false;
		}

		$user = User::newFromId( $row->user_id );
		$name = $skin->link(
			$user->getUserpage(),
			$user->getName()
		);

		$groups_list = array_diff( $user->getEffectiveGroups(), $user->getImplicitGroups() );
		if( count( $groups_list ) > 0 ) {
			$list = array();
			foreach( $groups_list as $group )
				$list[] = self::buildGroupLink( $group );
			$groups = $wgLang->commaList( $list );
		} else {
			$groups = '';
		}

		$item = wfSpecialList( $name, $groups );
		if( isset( $row->ipb_deleted ) ) {
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
		if( $row->user_registration ) {
			$d = $wgLang->date( wfTimestamp( TS_MW, $row->user_registration ), true );
			$t = $wgLang->time( wfTimestamp( TS_MW, $row->user_registration ), true );
			$created = ' (' . wfMessage( 'usercreated', $d, $t ) . ')';
		}

		wfRunHooks( 'SpecialListusersFormatRow', array( &$item, $row ) );
		return "{$item}{$edits}{$created}";
	}

	function getOrderFields() {
		return $this->creationSort ? array( 'user_id' ) : array( 'user_name' );
	}

	/**
	 * Cache page existence for performance
	 */
	function preprocessResults( $db, $res ) {
		$batch = new LinkBatch;
		foreach ( $res as $row ) {
			$batch->add( NS_USER, $row->user_name );
			$batch->add( NS_USER_TALK, $row->user_name );
		}
		$batch->execute();

		// Back to start for display
		if ( $db->numRows( $res ) > 0 ) {
			// If there are no rows we get an error seeking.
			$db->dataSeek( $res, 0 );
		}
	}

	function getPageHeader( ) {
		global $wgScript;
		$self = $this->getTitle();

		# Form tag
		$out  = Xml::openElement( 'form', array( 'method' => 'get', 'action' => $wgScript, 'id' => 'mw-listusers-form' ) ) .
			Xml::fieldset( wfMsg( 'listusers' ) ) .
			Html::hidden( 'title', $self->getPrefixedDbKey() );

		# Username field
		$out .= Xml::label( wfMsg( 'listusersfrom' ), 'offset' ) . ' ' .
			Xml::input( 'username', 20, $this->requestedUser, array( 'id' => 'offset' ) ) . ' ';

		# Group drop-down list
		$out .= Xml::label( wfMsg( 'group' ), 'group' ) . ' ' .
			Xml::openElement('select',  array( 'name' => 'group', 'id' => 'group' ) ) .
			Xml::option( wfMsg( 'group-all' ), '' );

		$groups = array_unique( array_diff( User::getAllGroups(), array( '*', 'user' ) ) );
		foreach( $groups as $group ){
			$out .= Xml::option(
				User::getGroupName( $group ),
				$group,
				$group == $this->requestedGroup
			);
		}

		$out .= Xml::closeElement( 'select' ) . '<br />';
		$out .= Xml::checkLabel( wfMsg('listusers-editsonly'), 'editsOnly', 'editsOnly', $this->editsOnly );
		$out .= '&#160;';
		$out .= Xml::checkLabel( wfMsg('listusers-creationsort'), 'creationSort', 'creationSort', $this->creationSort );
		$out .= '<br />';

		wfRunHooks( 'SpecialListusersHeaderForm', array( $this, &$out ) );

		# Submit button and form bottom
		$out .= Html::hidden( 'limit', $this->limit );
		$out .= Xml::submitButton( wfMsg( 'allpagessubmit' ) );
		wfRunHooks( 'SpecialListusersHeader', array( $this, &$out ) );
		$out .= Xml::closeElement( 'fieldset' ) .
			Xml::closeElement( 'form' );

		return $out;
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