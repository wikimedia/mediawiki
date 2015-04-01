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
		return 'qcc_title';
	}

	function getQueryInfo() {
		$dbr = $this->getDatabase();

		$conds = array(
			'qcc_type' => 'activeusers',
			'qcc_namespace' => NS_USER,
			'user_name = qcc_title',
			'rc_user_text = qcc_title',
			'rc_type != ' . $dbr->addQuotes( RC_EXTERNAL ) // Don't count wikidata.
		);
		if ( $this->requestedUser != '' ) {
			$conds[] = 'qcc_title >= ' . $dbr->addQuotes( $this->requestedUser );
		}
		if ( !$this->getUser()->isAllowed( 'hideuser' ) ) {
			$conds[] = 'NOT EXISTS (' . $dbr->selectSQLText(
				'ipblocks', '1', array( 'ipb_user=user_id', 'ipb_deleted' => 1 )
			) . ')';
		}

		if ( $dbr->implicitGroupby() ) {
			$options = array( 'GROUP BY' => array( 'qcc_title' ) );
		} else {
			$options = array( 'GROUP BY' => array( 'user_name', 'user_id', 'qcc_title' ) );
		}

		return array(
			'tables' => array( 'querycachetwo', 'user', 'recentchanges' ),
			'fields' => array( 'user_name', 'user_id', 'recentedits' => 'COUNT(*)', 'qcc_title' ),
			'options' => $options,
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

		// Occasionally merge in new updates
		$seconds = min( self::mergeActiveUsers( 600 ), $wgActiveUserDays * 86400 );
		// Mention the level of staleness
		$out->addWikiMsg( 'cachedspecial-viewing-cached-ttl',
			$this->getLanguage()->formatDuration( $seconds ) );

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

	/**
	 * @param integer $period Seconds (do updates no more often than this)
	 * @return integer How many seconds old the cache is
	 */
	public static function mergeActiveUsers( $period ) {
		global $wgActiveUserDays;

		$dbr = wfGetDB( DB_SLAVE );
		$cTime = $dbr->selectField( 'querycache_info',
			'qci_timestamp',
			array( 'qci_type' => 'activeusers' )
		);
		if ( !wfReadOnly() ) {
			if ( !$cTime || ( time() - wfTimestamp( TS_UNIX, $cTime ) ) > $period ) {
				$dbw = wfGetDB( DB_MASTER );
				if ( $dbw->estimateRowCount( 'recentchanges' ) <= 10000 ) {
					$window = $wgActiveUserDays * 86400; // small wiki
				} else {
					$window = $period * 2;
				}
				self::doQueryCacheUpdate( $dbw, $window );
			}
		}

		return ( time() -
			( $cTime ? wfTimestamp( TS_UNIX, $cTime ) : $wgActiveUserDays * 86400 ) );
	}

	/**
	 * @param DatabaseBase $dbw Passed in from updateSpecialPages.php
	 * @return void
	 */
	public static function cacheUpdate( DatabaseBase $dbw ) {
		global $wgActiveUserDays;

		self::doQueryCacheUpdate( $dbw, $wgActiveUserDays * 86400 );
	}

	/**
	 * Update the query cache as needed
	 *
	 * @param DatabaseBase $dbw
	 * @param integer $window Maximum time range of new data to scan (in seconds)
	 * @return bool Success
	 */
	protected static function doQueryCacheUpdate( DatabaseBase $dbw, $window ) {
		global $wgActiveUserDays;

		$lockKey = wfWikiID() . '-activeusers';
		if ( !$dbw->lock( $lockKey, __METHOD__, 1 ) ) {
			return false; // exclusive update (avoids duplicate entries)
		}

		$now = time();
		$cTime = $dbw->selectField( 'querycache_info',
			'qci_timestamp',
			array( 'qci_type' => 'activeusers' )
		);
		$cTimeUnix = $cTime ? wfTimestamp( TS_UNIX, $cTime ) : 1;

		// Pick the date range to fetch from. This is normally from the last
		// update to till the present time, but has a limited window for sanity.
		// If the window is limited, multiple runs are need to fully populate it.
		$sTimestamp = max( $cTimeUnix, $now - $wgActiveUserDays * 86400 );
		$eTimestamp = min( $sTimestamp + $window, $now );

		// Get all the users active since the last update
		$res = $dbw->select(
			array( 'recentchanges' ),
			array( 'rc_user_text', 'lastedittime' => 'MAX(rc_timestamp)' ),
			array(
				'rc_user > 0', // actual accounts
				'rc_type != ' . $dbw->addQuotes( RC_EXTERNAL ), // no wikidata
				'rc_log_type IS NULL OR rc_log_type != ' . $dbw->addQuotes( 'newusers' ),
				'rc_timestamp >= ' . $dbw->addQuotes( $dbw->timestamp( $sTimestamp ) ),
				'rc_timestamp <= ' . $dbw->addQuotes( $dbw->timestamp( $eTimestamp ) )
			),
			__METHOD__,
			array(
				'GROUP BY' => array( 'rc_user_text' ),
				'ORDER BY' => 'NULL' // avoid filesort
			)
		);
		$names = array();
		foreach ( $res as $row ) {
			$names[$row->rc_user_text] = $row->lastedittime;
		}

		// Rotate out users that have not edited in too long (according to old data set)
		$dbw->delete( 'querycachetwo',
			array(
				'qcc_type' => 'activeusers',
				'qcc_value < ' . $dbw->addQuotes( $now - $wgActiveUserDays * 86400 ) // TS_UNIX
			),
			__METHOD__
		);

		// Find which of the recently active users are already accounted for
		if ( count( $names ) ) {
			$res = $dbw->select( 'querycachetwo',
				array( 'user_name' => 'qcc_title' ),
				array(
					'qcc_type' => 'activeusers',
					'qcc_namespace' => NS_USER,
					'qcc_title' => array_keys( $names ) ),
				__METHOD__
			);
			foreach ( $res as $row ) {
				unset( $names[$row->user_name] );
			}
		}

		// Insert the users that need to be added to the list (which their last edit time
		if ( count( $names ) ) {
			$newRows = array();
			foreach ( $names as $name => $lastEditTime ) {
				$newRows[] = array(
					'qcc_type' => 'activeusers',
					'qcc_namespace' => NS_USER,
					'qcc_title' => $name,
					'qcc_value' => wfTimestamp( TS_UNIX, $lastEditTime ),
					'qcc_namespacetwo' => 0, // unused
					'qcc_titletwo' => '' // unused
				);
			}
			foreach ( array_chunk( $newRows, 500 ) as $rowBatch ) {
				$dbw->insert( 'querycachetwo', $rowBatch, __METHOD__ );
				wfWaitForSlaves();
			}
		}

		// Touch the data freshness timestamp
		$dbw->replace( 'querycache_info',
			array( 'qci_type' ),
			array( 'qci_type' => 'activeusers',
				'qci_timestamp' => $dbw->timestamp( $eTimestamp ) ), // not always $now
			__METHOD__
		);

		$dbw->unlock( $lockKey, __METHOD__ );

		return true;
	}
}
