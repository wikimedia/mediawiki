<?php
/**
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

use MediaWiki\MediaWikiServices;

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
	 * @var string[]
	 */
	protected $groups;

	/**
	 * @var array
	 */
	private $blockStatusByUid;

	/** @var int */
	private $RCMaxAge;

	/** @var string[] */
	private $excludegroups;

	/**
	 * @param IContextSource|null $context
	 * @param FormOptions $opts
	 */
	public function __construct( IContextSource $context = null, FormOptions $opts ) {
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

		$this->groups = $opts->getValue( 'groups' );
		$this->excludegroups = $opts->getValue( 'excludegroups' );
		// Backwards-compatibility with old URLs
		if ( $opts->getValue( 'hidebots' ) ) {
			$this->excludegroups[] = 'bot';
		}
		if ( $opts->getValue( 'hidesysops' ) ) {
			$this->excludegroups[] = 'sysop';
		}
	}

	function getIndexField() {
		return 'qcc_title';
	}

	function getQueryInfo( $data = null ) {
		$dbr = $this->getDatabase();

		$activeUserSeconds = $this->getConfig()->get( 'ActiveUserDays' ) * 86400;
		$timestamp = $dbr->timestamp( wfTimestamp( TS_UNIX ) - $activeUserSeconds );
		$fname = __METHOD__ . ' (' . $this->getSqlComment() . ')';

		// Inner subselect to pull the active users out of querycachetwo
		$tables = [ 'querycachetwo', 'user', 'actor' ];
		$fields = [ 'qcc_title', 'user_id', 'actor_id' ];
		$jconds = [
			'user' => [ 'JOIN', 'user_name = qcc_title' ],
			'actor' => [ 'JOIN', 'actor_user = user_id' ],
		];
		$conds = [
			'qcc_type' => 'activeusers',
			'qcc_namespace' => NS_USER,
		];
		$options = [];
		if ( $data !== null ) {
			$options['ORDER BY'] = 'qcc_title ' . $data['order'];
			$options['LIMIT'] = $data['limit'];
			$conds = array_merge( $conds, $data['conds'] );
		}
		if ( $this->requestedUser != '' ) {
			$conds[] = 'qcc_title >= ' . $dbr->addQuotes( $this->requestedUser );
		}
		if ( $this->groups !== [] ) {
			$tables['ug1'] = 'user_groups';
			$jconds['ug1'] = [ 'JOIN', 'ug1.ug_user = user_id' ];
			$conds['ug1.ug_group'] = $this->groups;
			$conds[] = 'ug1.ug_expiry IS NULL OR ug1.ug_expiry >= ' . $dbr->addQuotes( $dbr->timestamp() );
		}
		if ( $this->excludegroups !== [] ) {
			$tables['ug2'] = 'user_groups';
			$jconds['ug2'] = [ 'LEFT JOIN', [
				'ug2.ug_user = user_id',
				'ug2.ug_group' => $this->excludegroups,
				'ug2.ug_expiry IS NULL OR ug2.ug_expiry >= ' . $dbr->addQuotes( $dbr->timestamp() ),
			] ];
			$conds['ug2.ug_user'] = null;
		}
		if ( !MediaWikiServices::getInstance()
				  ->getPermissionManager()
				  ->userHasRight( $this->getUser(), 'hideuser' )
		) {
			$conds[] = 'NOT EXISTS (' . $dbr->selectSQLText(
					'ipblocks', '1', [ 'ipb_user=user_id', 'ipb_deleted' => 1 ]
				) . ')';
		}
		$subquery = $dbr->buildSelectSubquery( $tables, $fields, $conds, $fname, $options, $jconds );

		// Outer query to select the recent edit counts for the selected active users
		$tables = [ 'qcc_users' => $subquery, 'recentchanges' ];
		$jconds = [ 'recentchanges' => [ 'LEFT JOIN', [
			'rc_actor = actor_id',
			'rc_type != ' . $dbr->addQuotes( RC_EXTERNAL ), // Don't count wikidata.
			'rc_type != ' . $dbr->addQuotes( RC_CATEGORIZE ), // Don't count categorization changes.
			'rc_log_type IS NULL OR rc_log_type != ' . $dbr->addQuotes( 'newusers' ),
			'rc_timestamp >= ' . $dbr->addQuotes( $timestamp ),
		] ] ];
		$conds = [];

		return [
			'tables' => $tables,
			'fields' => [
				'qcc_title',
				'user_name' => 'qcc_title',
				'user_id' => 'user_id',
				'recentedits' => 'COUNT(rc_id)'
			],
			'options' => [ 'GROUP BY' => [ 'qcc_title', 'user_id' ] ],
			'conds' => $conds,
			'join_conds' => $jconds,
		];
	}

	protected function buildQueryInfo( $offset, $limit, $order ) {
		$fname = __METHOD__ . ' (' . $this->getSqlComment() . ')';

		$sortColumns = array_merge( [ $this->mIndexField ], $this->mExtraSortFields );
		if ( $order === self::QUERY_ASCENDING ) {
			$dir = 'ASC';
			$orderBy = $sortColumns;
			$operator = $this->mIncludeOffset ? '>=' : '>';
		} else {
			$dir = 'DESC';
			$orderBy = [];
			foreach ( $sortColumns as $col ) {
				$orderBy[] = $col . ' DESC';
			}
			$operator = $this->mIncludeOffset ? '<=' : '<';
		}
		$info = $this->getQueryInfo( [
			'limit' => intval( $limit ),
			'order' => $dir,
			'conds' =>
				$offset != '' ? [ $this->mIndexField . $operator . $this->mDb->addQuotes( $offset ) ] : [],
		] );

		$tables = $info['tables'];
		$fields = $info['fields'];
		$conds = $info['conds'];
		$options = $info['options'];
		$join_conds = $info['join_conds'];
		$options['ORDER BY'] = $orderBy;
		return [ $tables, $fields, $conds, $fname, $options, $join_conds ];
	}

	protected function doBatchLookups() {
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
			[ 'ipb_user', 'MAX(ipb_deleted) AS deleted, MAX(ipb_sitewide) AS sitewide' ],
			[ 'ipb_user' => $uids ],
			__METHOD__,
			[ 'GROUP BY' => [ 'ipb_user' ] ]
		);
		$this->blockStatusByUid = [];
		foreach ( $res as $row ) {
			$this->blockStatusByUid[$row->ipb_user] = [
				'deleted' => $row->deleted,
				'sitewide' => $row->sitewide,
			];
		}
		$this->mResult->seek( 0 );
	}

	function formatRow( $row ) {
		$userName = $row->user_name;

		$ulinks = Linker::userLink( $row->user_id, $userName );
		$ulinks .= Linker::userToolLinks(
			$row->user_id,
			$userName,
			// Should the contributions link be red if the user has no edits (using default)
			false,
			// Customisation flags (using default 0)
			0,
			// User edit count (using default)
			null,
			// do not wrap the message in parentheses (CSS will provide these)
			false
		);

		$lang = $this->getLanguage();

		$list = [];

		$ugms = self::getGroupMemberships( intval( $row->user_id ), $this->userGroupCache );
		foreach ( $ugms as $ugm ) {
			$list[] = $this->buildGroupLink( $ugm, $userName );
		}

		$groups = $lang->commaList( $list );

		$item = $lang->specialList( $ulinks, $groups );

		// If there is a block, 'deleted' and 'sitewide' are both set on
		// $this->blockStatusByUid[$row->user_id].
		$blocked = '';
		$isBlocked = isset( $this->blockStatusByUid[$row->user_id] );
		if ( $isBlocked ) {
			if ( $this->blockStatusByUid[$row->user_id]['deleted'] == 1 ) {
				$item = "<span class=\"deleted\">$item</span>";
			}
			if ( $this->blockStatusByUid[$row->user_id]['sitewide'] == 1 ) {
				$blocked = ' ' . $this->msg( 'listusers-blocked', $userName )->escaped();
			}
		}
		$count = $this->msg( 'activeusers-count' )->numParams( $row->recentedits )
			->params( $userName )->numParams( $this->RCMaxAge )->escaped();

		return Html::rawElement( 'li', [], "{$item} [{$count}]{$blocked}" );
	}

}
