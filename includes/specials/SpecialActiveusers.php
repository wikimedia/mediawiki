<?php
/**
 * Implements Special:Activeusers
 *
 * Copyright © 2008 Aaron Schulz
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
 * This class is used to get a list of active users. The ones with specials
 * rights (sysop, bureaucrat, developer) will have them displayed
 * next to their names.
 *
 * @ingroup SpecialPage
 */
class ActiveUsersPager extends UsersPager {

	/**
	 * @var FormOptions
	 */
	protected $opts;

	/**
	 * @var Array
	 */
	protected $groups;

	/**
	 * @param $context IContextSource
	 * @param $group null Unused
	 * @param $par string Parameter passed to the page
	 */
	function __construct( IContextSource $context = null, $group = null, $par = null ) {
		global $wgActiveUserDays;

		parent::__construct( $context );

		$this->RCMaxAge = $wgActiveUserDays;
		$un = $this->getRequest()->getText( 'wpUsername', $par );
		$this->requestedUser = '';
		if ( $un != '' ) {
			$username = Title::makeTitleSafe( NS_USER, $un );
			if( !is_null( $username ) ) {
				$this->requestedUser = $username->getText();
			}
		}

		$this->setupOptions();
	}

	public function setupOptions() {
		$this->opts = new FormOptions();

		$this->opts->add( 'wpHideBots', false, FormOptions::BOOL );
		$this->opts->add( 'wpHideSysops', false, FormOptions::BOOL );

		$this->opts->fetchValuesFromRequest( $this->getRequest() );

		$this->groups = array();
		if ( $this->opts->getValue( 'wpHideBots' ) == 1 ) {
			$this->groups['bot'] = true;
		}
		if ( $this->opts->getValue( 'wpHideSysops' ) == 1 ) {
			$this->groups['sysop'] = true;
		}
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
		$userName = $row->user_name;

		$ulinks = Linker::userLink( $row->user_id, $userName );
		$ulinks .= Linker::userToolLinks( $row->user_id, $userName );

		$lang = $this->getLanguage();

		$list = array();
		foreach( self::getGroups( $row->user_id ) as $group ) {
			if ( isset( $this->groups[$group] ) ) {
				return;
			}
			$list[] = self::buildGroupLink( $group, $userName );
		}
		$groups = $lang->commaList( $list );

		$item = $lang->specialList( $ulinks, $groups );
		$count = $this->msg( 'activeusers-count' )->numParams( $row->recentedits )
			->params( $userName )->numParams( $this->RCMaxAge )->escaped();
		$blocked = $row->blocked ? ' ' . $this->msg( 'listusers-blocked', $userName )->escaped() : '';

		return Html::rawElement( 'li', array(), "{$item} [{$count}]{$blocked}" );
	}

	protected function getHTMLFormFields() {
		$f = array(
			'Username' => array(
				'type' => 'text',
				'label-message' => 'activeusers-from',
				'size' => 30,
			),
			'HideBots' => array(
				'type' => 'check',
				'label-message' => 'activeusers-hidebots',
			),
			'HideSysops' => array(
				'type' => 'check',
				'label-message' => 'activeusers-hidesysops',
			),
		);

		return $f;
	}

	protected function getHTMLFormLegend() {
		return 'activeusers';
	}

	protected function getHTMLFormSubmit() {
		return 'allpagessubmit';
	}
}

/**
 * @ingroup SpecialPage
 */
class SpecialActiveUsers extends SpecialPage {

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct( 'Activeusers' );
	}

	/**
	 * Show the special page
	 *
	 * @param $par Mixed: parameter passed to the page or null
	 */
	public function execute( $par ) {
		global $wgActiveUserDays;

		$this->setHeaders();
		$this->outputHeader();

		$out = $this->getOutput();
		$out->wrapWikiMsg( "<div class='mw-activeusers-intro'>\n$1\n</div>",
			array( 'activeusers-intro', $this->getLanguage()->formatNum( $wgActiveUserDays ) ) );

		$up = new ActiveUsersPager( $this->getContext(), null, $par );

		# getBody() first to check, if empty
		$usersbody = $up->getBody();

		$out->addHTML( $up->buildHTMLForm() );
		if ( $usersbody ) {
			$out->addHTML(
				$up->getNavigationBar() .
				Html::rawElement( 'ul', array(), $usersbody ) .
				$up->getNavigationBar()
			);
		} else {
			$out->addWikiMsg( 'activeusers-noresult' );
		}
	}

}
