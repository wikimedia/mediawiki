<?php
/**
 * Copyright (C) 2008 Aaron Schulz
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
 */

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
		global $wgRequest, $wgActiveUserDays;
		$this->RCMaxAge = $wgActiveUserDays;
		$un = $wgRequest->getText( 'username' );
		$this->requestedUser = '';
		if ( $un != '' ) {
			$username = Title::makeTitleSafe( NS_USER, $un );
			if( !is_null( $username ) ) {
				$this->requestedUser = $username->getText();
			}
		}
		
		$this->setupOptions();
		
		parent::__construct();
	}

	public function setupOptions() {
		global $wgRequest;
		
		$this->opts = new FormOptions();

		$this->opts->add( 'hidebots', false, FormOptions::BOOL );
		$this->opts->add( 'hidesysops', false, FormOptions::BOOL );

		$this->opts->fetchValuesFromRequest( $wgRequest );

		$this->groups = array();
		if ($this->opts->getValue('hidebots') == 1)
			$this->groups['bot'] = true;
		if ($this->opts->getValue('hidesysops') == 1)
			$this->groups['sysop'] = true;
	}

	function getIndexField() {
		return 'rc_user_text';
	}

	function getQueryInfo() {
		$dbr = wfGetDB( DB_SLAVE );
		$conds = array( 'rc_user > 0' ); // Users - no anons
		$conds[] = 'ipb_deleted IS NULL'; // don't show hidden names
		$conds[] = "rc_log_type IS NULL OR rc_log_type != 'newusers'";
		$conds[] = "rc_timestamp >= '{$dbr->timestamp( wfTimestamp( TS_UNIX ) - $this->RCMaxAge*24*3600 )}'";
		
		if( $this->requestedUser != '' ) {
			$conds[] = 'rc_user_text >= ' . $dbr->addQuotes( $this->requestedUser );
		}

		$query = array(
			'tables' => array( 'recentchanges', 'user', 'ipblocks' ),
			'fields' => array( 'rc_user_text AS user_name', // inheritance
				'rc_user_text', // for Pager
				'user_id',
				'COUNT(*) AS recentedits',
				'MAX(ipb_user) AS blocked'
			),
			'options' => array(
				'GROUP BY' => 'rc_user_text, user_id',
				'USE INDEX' => array( 'recentchanges' => 'rc_user_text' )
			),
			'join_conds' => array(
				'user' => array( 'INNER JOIN', 'rc_user_text=user_name' ),
				'ipblocks' => array( 'LEFT JOIN', 'user_id=ipb_user AND ipb_auto=0 AND ipb_deleted=1' ),
			),
			'conds' => $conds
		);
		return $query;
	}

	function formatRow( $row ) {
		global $wgLang;
		$userName = $row->user_name;
		
		$ulinks = $this->getSkin()->userLink( $row->user_id, $userName );
		$ulinks .= $this->getSkin()->userToolLinks( $row->user_id, $userName );

		$list = array();
		foreach( self::getGroups( $row->user_id ) as $group ) {
			if (isset($this->groups[$group]))
				return;
			$list[] = self::buildGroupLink( $group );
		}
		$groups = $wgLang->commaList( $list );

		$item = wfSpecialList( $ulinks, $groups );
		$count = wfMsgExt( 'activeusers-count',
			array( 'parsemag' ),
			$wgLang->formatNum( $row->recentedits ),
			$userName,
			$wgLang->formatNum ( $this->RCMaxAge )
		);
		$blocked = $row->blocked ? ' ' . wfMsgExt( 'listusers-blocked', array( 'parsemag' ), $userName ) : '';

		return Html::rawElement( 'li', array(), "{$item} [{$count}]{$blocked}" );
	}

	function getPageHeader() {
		global $wgScript;

		$self = $this->getTitle();
		$limit = $this->mLimit ? Xml::hidden( 'limit', $this->mLimit ) : '';

		$out  = Xml::openElement( 'form', array( 'method' => 'get', 'action' => $wgScript ) ); # Form tag
		$out .= Xml::fieldset( wfMsg( 'activeusers' ) ) . "\n";
		$out .= Xml::hidden( 'title', $self->getPrefixedDBkey() ) . $limit . "\n";

		$out .= Xml::inputLabel( wfMsg( 'activeusers-from' ), 'username', 'offset', 20, $this->requestedUser ) . '<br />';# Username field

		$out .= Xml::checkLabel( wfMsg('activeusers-hidebots'), 'hidebots', 'hidebots', $this->opts->getValue( 'hidebots' ) );

		$out .= Xml::checkLabel( wfMsg('activeusers-hidesysops'), 'hidesysops', 'hidesysops', $this->opts->getValue( 'hidesysops' ) ) . '<br />';

		$out .= Xml::submitButton( wfMsg( 'allpagessubmit' ) ) .  "\n";# Submit button and form bottom
		$out .= Xml::closeElement( 'fieldset' );
		$out .= Xml::closeElement( 'form' );
		
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
		parent::__construct( 'Activeusers' );
	}

	/**
	 * Show the special page
	 *
	 * @param $par Mixed: parameter passed to the page or null
	 */
	public function execute( $par ) {
		global $wgOut, $wgLang, $wgActiveUserDays;

		$this->setHeaders();
		$this->outputHeader();

		$up = new ActiveUsersPager();

		# getBody() first to check, if empty
		$usersbody = $up->getBody();

		$s = Html::rawElement( 'div', array( 'class' => 'mw-activeusers-intro' ),
			wfMsgExt( 'activeusers-intro', array( 'parsemag', 'escape' ), $wgLang->formatNum( $wgActiveUserDays ) )
		);

		$s .= $up->getPageHeader();
		if( $usersbody ) {
			$s .= $up->getNavigationBar();
			$s .= Html::rawElement( 'ul', array(), $usersbody );
			$s .= $up->getNavigationBar();
		} else {
			$s .= Html::element( 'p', array(), wfMsg( 'activeusers-noresult' ) );
		}

		$wgOut->addHTML( $s );
	}

}
