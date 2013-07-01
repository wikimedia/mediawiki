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
	protected $hideGroups = array();

	/**
	 * @var Array
	 */
	protected $hideRights = array();

	/**
	 * @param $context IContextSource
	 * @param $group null Unused
	 * @param string $par Parameter passed to the page
	 */
	function __construct( IContextSource $context = null, $group = null, $par = null ) {
		global $wgActiveUserDays;

		parent::__construct( $context );

		$this->RCMaxAge = $wgActiveUserDays;
		$un = $this->getRequest()->getText( 'username', $par );
		$this->requestedUser = '';
		if ( $un != '' ) {
			$username = Title::makeTitleSafe( NS_USER, $un );
			if ( !is_null( $username ) ) {
				$this->requestedUser = $username->getText();
			}
		}

		$this->setupOptions();
	}

	public function setupOptions() {
		$this->opts = new FormOptions();

		$this->opts->add( 'hidebots', false, FormOptions::BOOL );
		$this->opts->add( 'hidesysops', false, FormOptions::BOOL );

		$this->opts->fetchValuesFromRequest( $this->getRequest() );

		if ( $this->opts->getValue( 'hidebots' ) == 1 ) {
			$this->hideRights[] = 'bot';
		}
		if ( $this->opts->getValue( 'hidesysops' ) == 1 ) {
			$this->hideGroups[] = 'sysop';
		}
	}

	function getIndexField() {
		return 'rc_user_text';
	}

	function getQueryInfo() {
		$dbr = $this->getDatabase();

		$conds = array( 'rc_user > 0' ); // Users - no anons
		$conds[] = 'rc_log_type IS NULL OR rc_log_type != ' . $dbr->addQuotes( 'newusers' );
		$conds[] = 'rc_timestamp >= ' . $dbr->addQuotes(
			$dbr->timestamp( wfTimestamp( TS_UNIX ) - $this->RCMaxAge * 24 * 3600 ) );

		if ( $this->requestedUser != '' ) {
			$conds[] = 'rc_user_text >= ' . $dbr->addQuotes( $this->requestedUser );
		}

		if ( !$this->getUser()->isAllowed( 'hideuser' ) ) {
			$conds[] = 'NOT EXISTS (' . $dbr->selectSQLText(
				'ipblocks', '1', array( 'rc_user=ipb_user', 'ipb_deleted' => 1 )
			) . ')';
		}

		return array(
			'tables' => array( 'recentchanges' ),
			'fields' => array(
				'user_name' => 'rc_user_text', // for Pager inheritance
				'rc_user_text', // for Pager
				'user_id' => 'MAX(rc_user)', // Postgres
				'recentedits' => 'COUNT(*)'
			),
			'options' => array(
				'GROUP BY' => array( 'rc_user_text' ),
				'USE INDEX' => array( 'recentchanges' => 'rc_user_text' )
			),
			'conds' => $conds
		);
	}

	function doBatchLookups() {
		$uids = array();
		foreach ( $this->mResult as $row ) {
			$uids[] = $row->user_id;
		}
		// Fetch the block status of the user for showing "(blocked)" text and for
		// striking out names of suppressed users when privileged user views the list.
		// Although the first query already hits the block table for un-privileged, this
		// is done in two queries to avoid huge quicksorts and to make COUNT(*) correct.
		$dbr = $this->getDatabase();
		$res = $dbr->select( 'ipblocks',
			array( 'ipb_user', 'MAX(ipb_deleted) AS block_status' ),
			array( 'ipb_user' => $uids ),
			__METHOD__,
			array( 'GROUP BY' => array( 'ipb_user' ) )
		);
		$this->blockStatusByUid = array();
		foreach ( $res as $row ) {
			$this->blockStatusByUid[$row->ipb_user] = $row->block_status; // 0 or 1
		}
		$this->mResult->seek( 0 );
	}

	function formatRow( $row ) {
		$userName = $row->user_name;

		$ulinks = Linker::userLink( $row->user_id, $userName );
		$ulinks .= Linker::userToolLinks( $row->user_id, $userName );

		$lang = $this->getLanguage();

		$list = array();
		$user = User::newFromId( $row->user_id );

		// User right filter
		foreach ( $this->hideRights as $right ) {
			// Calling User::getRights() within the loop so that
			// if the hideRights() filter is empty, we don't have to
			// trigger the lazy-init of the big userrights array in the
			// User object
			if ( in_array( $right, $user->getRights() ) ) {
				return '';
			}
		}

		// User group filter
		// Note: This is a different loop than for user rights,
		// because we're reusing it to build the group links
		// at the same time
		foreach ( $user->getGroups() as $group ) {
			if ( in_array( $group, $this->hideGroups ) ) {
				return '';
			}
			$list[] = self::buildGroupLink( $group, $userName );
		}

		$groups = $lang->commaList( $list );

		$item = $lang->specialList( $ulinks, $groups );

		$isBlocked = isset( $this->blockStatusByUid[$row->user_id] );
		if ( $isBlocked && $this->blockStatusByUid[$row->user_id] == 1 ) {
			$item = "<span class=\"deleted\">$item</span>";
		}
		$count = $this->msg( 'activeusers-count' )->numParams( $row->recentedits )
			->params( $userName )->numParams( $this->RCMaxAge )->escaped();
		$blocked = $isBlocked ? ' ' . $this->msg( 'listusers-blocked', $userName )->escaped() : '';

		return Html::rawElement( 'li', array(), "{$item} [{$count}]{$blocked}" );
	}

	function getPageHeader() {
		global $wgScript;

		$self = $this->getTitle();
		$limit = $this->mLimit ? Html::hidden( 'limit', $this->mLimit ) : '';

		$out = Xml::openElement( 'form', array( 'method' => 'get', 'action' => $wgScript ) ); # Form tag
		$out .= Xml::fieldset( $this->msg( 'activeusers' )->text() ) . "\n";
		$out .= Html::hidden( 'title', $self->getPrefixedDBkey() ) . $limit . "\n";

		$out .= Xml::inputLabel( $this->msg( 'activeusers-from' )->text(),
			'username', 'offset', 20, $this->requestedUser, array( 'tabindex' => 1 ) ) . '<br />';# Username field

		$out .= Xml::checkLabel( $this->msg( 'activeusers-hidebots' )->text(),
			'hidebots', 'hidebots', $this->opts->getValue( 'hidebots' ), array( 'tabindex' => 2 ) );

		$out .= Xml::checkLabel( $this->msg( 'activeusers-hidesysops' )->text(),
			'hidesysops', 'hidesysops', $this->opts->getValue( 'hidesysops' ), array( 'tabindex' => 3 ) ) . '<br />';

		$out .= Xml::submitButton( $this->msg( 'allpagessubmit' )->text(), array( 'tabindex' => 4 ) ) . "\n";# Submit button and form bottom
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

		$out->addHTML( $up->getPageHeader() );
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

	protected function getGroupName() {
		return 'users';
	}
}
