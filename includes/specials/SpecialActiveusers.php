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
 * This class is used to get a list of active users. The ones with specials
 * rights (sysop, bureaucrat, developer) will have them displayed
 * next to their names.
 *
 * @file
 * @ingroup SpecialPage
 */
class ActiveUsersPager extends UsersPager {

	function __construct( $group = null ) {
		global $wgRequest;
		$un = $wgRequest->getText( 'username' );
		$this->requestedUser = '';
		if ( $un != '' ) {
			$username = Title::makeTitleSafe( NS_USER, $un );
			if( !is_null( $username ) ) {
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
		$conds = array('rc_user > 0'); // Users - no anons
		$conds[] = 'ipb_deleted IS NULL'; // don't show hidden names
		$useIndex = $dbr->useIndexClause( 'rc_user_text' );
		if( $this->requestedUser != '' ) {
			$conds[] = 'rc_user_text >= ' . $dbr->addQuotes( $this->requestedUser );
		}

		list( $recentchanges, $ipblocks, $user ) = $dbr->tableNamesN( 'recentchanges', 'ipblocks', 'user' );

		$query = array(
			'tables' => " $recentchanges $useIndex 
				LEFT JOIN $ipblocks ON rc_user=ipb_user AND ipb_auto=0 AND ipb_deleted=1
				INNER JOIN $user ON rc_user=user_id ",
			'fields' => array( 'rc_user_text AS user_name', // inheritance
				'rc_user_text', // for Pager
				'MAX(rc_user) AS user_id',
				'COUNT(*) AS recentedits',
				'MAX(ipb_user) AS blocked' ),
			'options' => array( 'GROUP BY' => 'rc_user_text' ),
			'conds' => $conds
		);
		return $query;
	}

	function formatRow( $row ) {
		$userName = $row->rc_user_text;
		$userPage = Title::makeTitle( NS_USER, $userName );
		$name = $this->getSkin()->makeLinkObj( $userPage, htmlspecialchars( $userPage->getText() ) );

		$list = array();
		foreach( self::getGroups( $row->user_id ) as $group )
			$list[] = self::buildGroupLink( $group );
		$groups = implode( ', ', $list );

		$item = wfSpecialList( $name, $groups );
		$count = wfMsgExt( 'activeusers-count', array( 'parsemag' ), $row->recentedits, $userName );
		$blocked = $row->blocked ? ' ' . wfMsgExt( 'listusers-blocked', array( 'parsemag' ), $userName ) : '';

		return "<li>{$item} [{$count}]{$blocked}</li>";
	}

	function getPageHeader() {
		global $wgScript, $wgRequest;
		$self = $this->getTitle();

		# Form tag
		$out  = Xml::openElement( 'form', array( 'method' => 'get', 'action' => $wgScript ) ) .
			'<fieldset>' .
			Xml::element( 'legend', array(), wfMsg( 'activeusers' ) );
		$out .= Xml::hidden( 'title', $self->getPrefixedDBkey() );

		# Username field
		$out .= Xml::label( wfMsg( 'activeusers-from' ), 'offset' ) . ' ' .
			Xml::input( 'username', 20, $this->requestedUser, array( 'id' => 'offset' ) ) . ' ';

		# Submit button and form bottom
		if( $this->mLimit )
			$out .= Xml::hidden( 'limit', $this->mLimit );
		$out .= Xml::submitButton( wfMsg( 'allpagessubmit' ) );

		$out .= '</fieldset>' . Xml::closeElement( 'form' );

		return $out;
	}
}

/**
 * @ingroup SpecialPage
 */
class SpecialActiveUsers extends SpecialPage {

	/**
	 * Constructor
	 */
	public function  __construct() {
		parent::__construct( 'ActiveUsers' );
	}

	/**
	 * Show the special page
	 *
	 * @param $par Mixed: parameter passed to the page or null
	 */
	public function execute( $par ) {
		global $wgOut;

		$this->setHeaders();

		$up = new ActiveUsersPager();

		# getBody() first to check, if empty
		$usersbody = $up->getBody();
		$s = $up->getPageHeader();
		if( $usersbody ) {
			$s .= $up->getNavigationBar();
			$s .= '<ul>' . $usersbody . '</ul>';
			$s .= $up->getNavigationBar();
		} else {
			$s .= '<p>' . wfMsgHtml( 'activeusers-noresult' ) . '</p>';
		}

		$wgOut->addHTML( $s );
	}

}