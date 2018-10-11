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

	/**
	 * @param IContextSource|null $context
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

	function getQueryInfo() {
		$dbr = $this->getDatabase();

		$rcQuery = ActorMigration::newMigration()->getJoin( 'rc_user' );

		$activeUserSeconds = $this->getConfig()->get( 'ActiveUserDays' ) * 86400;
		$timestamp = $dbr->timestamp( wfTimestamp( TS_UNIX ) - $activeUserSeconds );
		$tables = [ 'querycachetwo', 'user', 'rc' => [ 'recentchanges' ] + $rcQuery['tables'] ];
		$jconds = [
			'user' => [ 'JOIN', 'user_name = qcc_title' ],
			'rc' => [ 'JOIN', $rcQuery['fields']['rc_user_text'] . ' = qcc_title' ],
		] + $rcQuery['joins'];
		$conds = [
			'qcc_type' => 'activeusers',
			'qcc_namespace' => NS_USER,
			'rc_type != ' . $dbr->addQuotes( RC_EXTERNAL ), // Don't count wikidata.
			'rc_type != ' . $dbr->addQuotes( RC_CATEGORIZE ), // Don't count categorization changes.
			'rc_log_type IS NULL OR rc_log_type != ' . $dbr->addQuotes( 'newusers' ),
			'rc_timestamp >= ' . $dbr->addQuotes( $timestamp ),
		];
		if ( $this->requestedUser != '' ) {
			$conds[] = 'qcc_title >= ' . $dbr->addQuotes( $this->requestedUser );
		}
		if ( $this->groups !== [] ) {
			$tables[] = 'user_groups';
			$jconds['user_groups'] = [ 'JOIN', [ 'ug_user = user_id' ] ];
			$conds['ug_group'] = $this->groups;
			$conds[] = 'ug_expiry IS NULL OR ug_expiry >= ' . $dbr->addQuotes( $dbr->timestamp() );
		}
		if ( $this->excludegroups !== [] ) {
			foreach ( $this->excludegroups as $group ) {
				$conds[] = 'NOT EXISTS (' . $dbr->selectSQLText(
					'user_groups', '1', [
						'ug_user = user_id',
						'ug_group' => $group,
						'ug_expiry IS NULL OR ug_expiry >= ' . $dbr->addQuotes( $dbr->timestamp() )
					]
				) . ')';
			}
		}
		if ( !$this->getUser()->isAllowed( 'hideuser' ) ) {
			$conds[] = 'NOT EXISTS (' . $dbr->selectSQLText(
					'ipblocks', '1', [ 'ipb_user=user_id', 'ipb_deleted' => 1 ]
				) . ')';
		}

		return [
			'tables' => $tables,
			'fields' => [
				'qcc_title',
				'user_name' => 'qcc_title',
				'user_id' => 'MAX(user_id)',
				'recentedits' => 'COUNT(*)'
			],
			'options' => [ 'GROUP BY' => [ 'qcc_title' ] ],
			'conds' => $conds,
			'join_conds' => $jconds,
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

		$ugms = self::getGroupMemberships( intval( $row->user_id ), $this->userGroupCache );
		foreach ( $ugms as $ugm ) {
			$list[] = $this->buildGroupLink( $ugm, $userName );
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
