<?php
/**
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
 * @ingroup Pager
 */

/**
 * This class is used to get a list of active users. The ones with specials
 * rights (sysop, bureaucrat, developer) will have them displayed
 * next to their names.
 *
 * @ingroup Pager
 */
class ActiveUsersPager extends UsersPager {

	/**
	 * @var FormOptions
	 */
	protected $opts;

	/**
	 * @var array
	 */
	protected $hideGroups = [];

	/**
	 * @var array
	 */
	protected $hideRights = [];

	/**
	 * @var array
	 */
	private $blockStatusByUid;

	/**
	 * @param IContextSource $context
	 * @param FormOptions $opts
	 */
	function __construct( IContextSource $context = null, FormOptions $opts ) {
		parent::__construct( $context );

		$this->RCMaxAge = $this->getConfig()->get( 'ActiveUserDays' );
		$this->requestedUser = '';

		$un = $opts->getValue( 'username' );
		if ( $un != '' ) {
			$username = Title::makeTitleSafe( NS_USER, $un );
			if ( !is_null( $username ) ) {
				$this->requestedUser = $username->getText();
			}
		}

		if ( $opts->getValue( 'hidebots' ) == 1 ) {
			$this->hideRights[] = 'bot';
		}
		if ( $opts->getValue( 'hidesysops' ) == 1 ) {
			$this->hideGroups[] = 'sysop';
		}
	}

	function getIndexField() {
		return 'qcc_title';
	}

	function getQueryInfo() {
		$dbr = $this->getDatabase();

		$activeUserSeconds = $this->getConfig()->get( 'ActiveUserDays' ) * 86400;
		$timestamp = $dbr->timestamp( wfTimestamp( TS_UNIX ) - $activeUserSeconds );
		$conds = [
			'qcc_type' => 'activeusers',
			'qcc_namespace' => NS_USER,
			'user_name = qcc_title',
			'rc_user_text = qcc_title',
			'rc_type != ' . $dbr->addQuotes( RC_EXTERNAL ), // Don't count wikidata.
			'rc_type != ' . $dbr->addQuotes( RC_CATEGORIZE ), // Don't count categorization changes.
			'rc_log_type IS NULL OR rc_log_type != ' . $dbr->addQuotes( 'newusers' ),
			'rc_timestamp >= ' . $dbr->addQuotes( $timestamp ),
		];
		if ( $this->requestedUser != '' ) {
			$conds[] = 'qcc_title >= ' . $dbr->addQuotes( $this->requestedUser );
		}
		if ( !$this->getUser()->isAllowed( 'hideuser' ) ) {
			$conds[] = 'NOT EXISTS (' . $dbr->selectSQLText(
					'ipblocks', '1', [ 'ipb_user=user_id', 'ipb_deleted' => 1 ]
				) . ')';
		}

		if ( $dbr->implicitGroupby() ) {
			$options = [ 'GROUP BY' => [ 'qcc_title' ] ];
		} else {
			$options = [ 'GROUP BY' => [ 'user_name', 'user_id', 'qcc_title' ] ];
		}

		return [
			'tables' => [ 'querycachetwo', 'user', 'recentchanges' ],
			'fields' => [ 'user_name', 'user_id', 'recentedits' => 'COUNT(*)', 'qcc_title' ],
			'options' => $options,
			'conds' => $conds
		];
	}

	function doBatchLookups() {
		parent::doBatchLookups();

		$uids = [];
		foreach ( $this->mResult as $row ) {
			$uids[] = $row->user_id;
		}
		// Fetch the block status of the user for showing "(blocked)" text and for
		// striking out names of suppressed users when privileged user views the list.
		// Although the first query already hits the block table for un-privileged, this
		// is done in two queries to avoid huge quicksorts and to make COUNT(*) correct.
		$dbr = $this->getDatabase();
		$res = $dbr->select( 'ipblocks',
			[ 'ipb_user', 'MAX(ipb_deleted) AS block_status' ],
			[ 'ipb_user' => $uids ],
			__METHOD__,
			[ 'GROUP BY' => [ 'ipb_user' ] ]
		);
		$this->blockStatusByUid = [];
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

		$list = [];
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
		$groups_list = self::getGroups( intval( $row->user_id ), $this->userGroupCache );
		foreach ( $groups_list as $group ) {
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

		return Html::rawElement( 'li', [], "{$item} [{$count}]{$blocked}" );
	}

}
