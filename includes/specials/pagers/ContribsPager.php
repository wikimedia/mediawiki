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
 * Pager for Special:Contributions
 * @ingroup Pager
 */
use MediaWiki\MediaWikiServices;
use Wikimedia\Rdbms\IResultWrapper;
use Wikimedia\Rdbms\FakeResultWrapper;
use Wikimedia\Rdbms\IDatabase;

class ContribsPager extends RangeChronologicalPager {

	public $mDefaultDirection = IndexPager::DIR_DESCENDING;
	public $messages;
	public $target;
	public $namespace = '';
	public $mDb;
	public $preventClickjacking = false;

	/** @var IDatabase */
	public $mDbSecondary;

	/**
	 * @var array
	 */
	protected $mParentLens;

	/**
	 * @var TemplateParser
	 */
	protected $templateParser;

	function __construct( IContextSource $context, array $options ) {
		parent::__construct( $context );

		$msgs = [
			'diff',
			'hist',
			'pipe-separator',
			'uctop'
		];

		foreach ( $msgs as $msg ) {
			$this->messages[$msg] = $this->msg( $msg )->escaped();
		}

		$this->target = $options['target'] ?? '';
		$this->contribs = $options['contribs'] ?? 'users';
		$this->namespace = $options['namespace'] ?? '';
		$this->tagFilter = $options['tagfilter'] ?? false;
		$this->nsInvert = $options['nsInvert'] ?? false;
		$this->associated = $options['associated'] ?? false;

		$this->deletedOnly = !empty( $options['deletedOnly'] );
		$this->topOnly = !empty( $options['topOnly'] );
		$this->newOnly = !empty( $options['newOnly'] );
		$this->hideMinor = !empty( $options['hideMinor'] );

		// Date filtering: use timestamp if available
		$startTimestamp = '';
		$endTimestamp = '';
		if ( $options['start'] ) {
			$startTimestamp = $options['start'] . ' 00:00:00';
		}
		if ( $options['end'] ) {
			$endTimestamp = $options['end'] . ' 23:59:59';
		}
		$this->getDateRangeCond( $startTimestamp, $endTimestamp );

		// This property on IndexPager is set by $this->getIndexField() in parent::__construct().
		// We need to reassign it here so that it is used when the actual query is ran.
		$this->mIndexField = $this->getIndexField();

		// Most of this code will use the 'contributions' group DB, which can map to replica DBs
		// with extra user based indexes or partioning by user. The additional metadata
		// queries should use a regular replica DB since the lookup pattern is not all by user.
		$this->mDbSecondary = wfGetDB( DB_REPLICA ); // any random replica DB
		$this->mDb = wfGetDB( DB_REPLICA, 'contributions' );
		$this->templateParser = new TemplateParser();
	}

	function getDefaultQuery() {
		$query = parent::getDefaultQuery();
		$query['target'] = $this->target;

		return $query;
	}

	/**
	 * This method basically executes the exact same code as the parent class, though with
	 * a hook added, to allow extensions to add additional queries.
	 *
	 * @param string $offset Index offset, inclusive
	 * @param int $limit Exact query limit
	 * @param bool $descending Query direction, false for ascending, true for descending
	 * @return IResultWrapper
	 */
	function reallyDoQuery( $offset, $limit, $descending ) {
		list( $tables, $fields, $conds, $fname, $options, $join_conds ) = $this->buildQueryInfo(
			$offset,
			$limit,
			$descending
		);

		/*
		 * This hook will allow extensions to add in additional queries, so they can get their data
		 * in My Contributions as well. Extensions should append their results to the $data array.
		 *
		 * Extension queries have to implement the navbar requirement as well. They should
		 * - have a column aliased as $pager->getIndexField()
		 * - have LIMIT set
		 * - have a WHERE-clause that compares the $pager->getIndexField()-equivalent column to the offset
		 * - have the ORDER BY specified based upon the details provided by the navbar
		 *
		 * See includes/Pager.php buildQueryInfo() method on how to build LIMIT, WHERE & ORDER BY
		 *
		 * &$data: an array of results of all contribs queries
		 * $pager: the ContribsPager object hooked into
		 * $offset: see phpdoc above
		 * $limit: see phpdoc above
		 * $descending: see phpdoc above
		 */
		$data = [ $this->mDb->select(
			$tables, $fields, $conds, $fname, $options, $join_conds
		) ];
		Hooks::run(
			'ContribsPager::reallyDoQuery',
			[ &$data, $this, $offset, $limit, $descending ]
		);

		$result = [];

		// loop all results and collect them in an array
		foreach ( $data as $query ) {
			foreach ( $query as $i => $row ) {
				// use index column as key, allowing us to easily sort in PHP
				$result[$row->{$this->getIndexField()} . "-$i"] = $row;
			}
		}

		// sort results
		if ( $descending ) {
			ksort( $result );
		} else {
			krsort( $result );
		}

		// enforce limit
		$result = array_slice( $result, 0, $limit );

		// get rid of array keys
		$result = array_values( $result );

		return new FakeResultWrapper( $result );
	}

	function getQueryInfo() {
		$revQuery = Revision::getQueryInfo( [ 'page', 'user' ] );
		$queryInfo = [
			'tables' => $revQuery['tables'],
			'fields' => array_merge( $revQuery['fields'], [ 'page_is_new' ] ),
			'conds' => [],
			'options' => [],
			'join_conds' => $revQuery['joins'],
		];

		if ( $this->contribs == 'newbie' ) {
			$max = $this->mDb->selectField( 'user', 'max(user_id)', '', __METHOD__ );
			$queryInfo['conds'][] = $revQuery['fields']['rev_user'] . ' >' . (int)( $max - $max / 100 );
			# ignore local groups with the bot right
			# @todo FIXME: Global groups may have 'bot' rights
			$groupsWithBotPermission = User::getGroupsWithPermission( 'bot' );
			if ( count( $groupsWithBotPermission ) ) {
				$queryInfo['tables'][] = 'user_groups';
				$queryInfo['conds'][] = 'ug_group IS NULL';
				$queryInfo['join_conds']['user_groups'] = [
					'LEFT JOIN', [
						'ug_user = ' . $revQuery['fields']['rev_user'],
						'ug_group' => $groupsWithBotPermission,
						'ug_expiry IS NULL OR ug_expiry >= ' .
							$this->mDb->addQuotes( $this->mDb->timestamp() )
					]
				];
			}
			// (T140537) Disallow looking too far in the past for 'newbies' queries. If the user requested
			// a timestamp offset far in the past such that there are no edits by users with user_ids in
			// the range, we would end up scanning all revisions from that offset until start of time.
			$queryInfo['conds'][] = 'rev_timestamp > ' .
				$this->mDb->addQuotes( $this->mDb->timestamp( wfTimestamp() - 30 * 24 * 60 * 60 ) );
		} else {
			$user = User::newFromName( $this->target, false );
			$ipRangeConds = $user->isAnon() ? $this->getIpRangeConds( $this->mDb, $this->target ) : null;
			if ( $ipRangeConds ) {
				$queryInfo['tables'][] = 'ip_changes';
				$queryInfo['join_conds']['ip_changes'] = [
					'LEFT JOIN', [ 'ipc_rev_id = rev_id' ]
				];
				$queryInfo['conds'][] = $ipRangeConds;
			} else {
				// tables and joins are already handled by Revision::getQueryInfo()
				$conds = ActorMigration::newMigration()->getWhere( $this->mDb, 'rev_user', $user );
				$queryInfo['conds'][] = $conds['conds'];
				// Force the appropriate index to avoid bad query plans (T189026)
				if ( isset( $conds['orconds']['actor'] ) ) {
					// @todo: This will need changing when revision_comment_temp goes away
					$queryInfo['options']['USE INDEX']['temp_rev_user'] = 'actor_timestamp';
				} else {
					$queryInfo['options']['USE INDEX']['revision'] =
						isset( $conds['orconds']['userid'] ) ? 'user_timestamp' : 'usertext_timestamp';
				}
			}
		}

		if ( $this->deletedOnly ) {
			$queryInfo['conds'][] = 'rev_deleted != 0';
		}

		if ( $this->topOnly ) {
			$queryInfo['conds'][] = 'rev_id = page_latest';
		}

		if ( $this->newOnly ) {
			$queryInfo['conds'][] = 'rev_parent_id = 0';
		}

		if ( $this->hideMinor ) {
			$queryInfo['conds'][] = 'rev_minor_edit = 0';
		}

		$user = $this->getUser();
		$queryInfo['conds'] = array_merge( $queryInfo['conds'], $this->getNamespaceCond() );

		// Paranoia: avoid brute force searches (T19342)
		if ( !$user->isAllowed( 'deletedhistory' ) ) {
			$queryInfo['conds'][] = $this->mDb->bitAnd( 'rev_deleted', Revision::DELETED_USER ) . ' = 0';
		} elseif ( !$user->isAllowedAny( 'suppressrevision', 'viewsuppressed' ) ) {
			$queryInfo['conds'][] = $this->mDb->bitAnd( 'rev_deleted', Revision::SUPPRESSED_USER ) .
				' != ' . Revision::SUPPRESSED_USER;
		}

		// For IPv6, we use ipc_rev_timestamp on ip_changes as the index field,
		// which will be referenced when parsing the results of a query.
		if ( self::isQueryableRange( $this->target ) ) {
			$queryInfo['fields'][] = 'ipc_rev_timestamp';
		}

		ChangeTags::modifyDisplayQuery(
			$queryInfo['tables'],
			$queryInfo['fields'],
			$queryInfo['conds'],
			$queryInfo['join_conds'],
			$queryInfo['options'],
			$this->tagFilter
		);

		// Avoid PHP 7.1 warning from passing $this by reference
		$pager = $this;
		Hooks::run( 'ContribsPager::getQueryInfo', [ &$pager, &$queryInfo ] );

		return $queryInfo;
	}

	function getNamespaceCond() {
		if ( $this->namespace !== '' ) {
			$selectedNS = $this->mDb->addQuotes( $this->namespace );
			$eq_op = $this->nsInvert ? '!=' : '=';
			$bool_op = $this->nsInvert ? 'AND' : 'OR';

			if ( !$this->associated ) {
				return [ "page_namespace $eq_op $selectedNS" ];
			}

			$associatedNS = $this->mDb->addQuotes(
				MWNamespace::getAssociated( $this->namespace )
			);

			return [
				"page_namespace $eq_op $selectedNS " .
				$bool_op .
				" page_namespace $eq_op $associatedNS"
			];
		}

		return [];
	}

	/**
	 * Get SQL conditions for an IP range, if applicable
	 * @param IDatabase      $db
	 * @param string         $ip The IP address or CIDR
	 * @return string|false  SQL for valid IP ranges, false if invalid
	 */
	private function getIpRangeConds( $db, $ip ) {
		// First make sure it is a valid range and they are not outside the CIDR limit
		if ( !$this->isQueryableRange( $ip ) ) {
			return false;
		}

		list( $start, $end ) = IP::parseRange( $ip );

		return 'ipc_hex BETWEEN ' . $db->addQuotes( $start ) . ' AND ' . $db->addQuotes( $end );
	}

	/**
	 * Is the given IP a range and within the CIDR limit?
	 *
	 * @param string $ipRange
	 * @return bool True if it is valid
	 * @since 1.30
	 */
	public function isQueryableRange( $ipRange ) {
		$limits = $this->getConfig()->get( 'RangeContributionsCIDRLimit' );

		$bits = IP::parseCIDR( $ipRange )[1];
		if (
			( $bits === false ) ||
			( IP::isIPv4( $ipRange ) && $bits < $limits['IPv4'] ) ||
			( IP::isIPv6( $ipRange ) && $bits < $limits['IPv6'] )
		) {
			return false;
		}

		return true;
	}

	/**
	 * Override of getIndexField() in IndexPager.
	 * For IP ranges, it's faster to use the replicated ipc_rev_timestamp
	 * on the `ip_changes` table than the rev_timestamp on the `revision` table.
	 * @return string Name of field
	 */
	public function getIndexField() {
		if ( $this->isQueryableRange( $this->target ) ) {
			return 'ipc_rev_timestamp';
		} else {
			return 'rev_timestamp';
		}
	}

	function doBatchLookups() {
		# Do a link batch query
		$this->mResult->seek( 0 );
		$parentRevIds = [];
		$this->mParentLens = [];
		$batch = new LinkBatch();
		$isIpRange = $this->isQueryableRange( $this->target );
		# Give some pointers to make (last) links
		foreach ( $this->mResult as $row ) {
			if ( isset( $row->rev_parent_id ) && $row->rev_parent_id ) {
				$parentRevIds[] = $row->rev_parent_id;
			}
			if ( isset( $row->rev_id ) ) {
				$this->mParentLens[$row->rev_id] = $row->rev_len;
				if ( $this->contribs === 'newbie' ) { // multiple users
					$batch->add( NS_USER, $row->user_name );
					$batch->add( NS_USER_TALK, $row->user_name );
				} elseif ( $isIpRange ) {
					// If this is an IP range, batch the IP's talk page
					$batch->add( NS_USER_TALK, $row->rev_user_text );
				}
				$batch->add( $row->page_namespace, $row->page_title );
			}
		}
		# Fetch rev_len for revisions not already scanned above
		$this->mParentLens += Revision::getParentLengths(
			$this->mDbSecondary,
			array_diff( $parentRevIds, array_keys( $this->mParentLens ) )
		);
		$batch->execute();
		$this->mResult->seek( 0 );
	}

	/**
	 * @return string
	 */
	function getStartBody() {
		return "<ul class=\"mw-contributions-list\">\n";
	}

	/**
	 * @return string
	 */
	function getEndBody() {
		return "</ul>\n";
	}

	/**
	 * Check whether the revision associated is valid for formatting. If has no associated revision
	 * id then null is returned.
	 *
	 * @param object $row
	 * @return Revision|null
	 */
	public function tryToCreateValidRevision( $row ) {
		/*
		 * There may be more than just revision rows. To make sure that we'll only be processing
		 * revisions here, let's _try_ to build a revision out of our row (without displaying
		 * notices though) and then trying to grab data from the built object. If we succeed,
		 * we're definitely dealing with revision data and we may proceed, if not, we'll leave it
		 * to extensions to subscribe to the hook to parse the row.
		 */
		Wikimedia\suppressWarnings();
		try {
			$rev = new Revision( $row );
			$validRevision = (bool)$rev->getId();
		} catch ( Exception $e ) {
			$validRevision = false;
		}
		Wikimedia\restoreWarnings();
		return $validRevision ? $rev : null;
	}

	/**
	 * Generates each row in the contributions list.
	 *
	 * Contributions which are marked "top" are currently on top of the history.
	 * For these contributions, a [rollback] link is shown for users with roll-
	 * back privileges. The rollback link restores the most recent version that
	 * was not written by the target user.
	 *
	 * @todo This would probably look a lot nicer in a table.
	 * @param object $row
	 * @return string
	 */
	function formatRow( $row ) {
		$ret = '';
		$classes = [];
		$attribs = [];

		$linkRenderer = MediaWikiServices::getInstance()->getLinkRenderer();

		$rev = $this->tryToCreateValidRevision( $row );
		if ( $rev ) {
			$attribs['data-mw-revid'] = $rev->getId();

			$page = Title::newFromRow( $row );
			$link = $linkRenderer->makeLink(
				$page,
				$page->getPrefixedText(),
				[ 'class' => 'mw-contributions-title' ],
				$page->isRedirect() ? [ 'redirect' => 'no' ] : []
			);
			# Mark current revisions
			$topmarktext = '';
			$user = $this->getUser();

			if ( $row->rev_id === $row->page_latest ) {
				$topmarktext .= '<span class="mw-uctop">' . $this->messages['uctop'] . '</span>';
				$classes[] = 'mw-contributions-current';
				# Add rollback link
				if ( !$row->page_is_new && $page->quickUserCan( 'rollback', $user )
					&& $page->quickUserCan( 'edit', $user )
				) {
					$this->preventClickjacking();
					$topmarktext .= ' ' . Linker::generateRollback( $rev, $this->getContext() );
				}
			}
			# Is there a visible previous revision?
			if ( $rev->userCan( Revision::DELETED_TEXT, $user ) && $rev->getParentId() !== 0 ) {
				$difftext = $linkRenderer->makeKnownLink(
					$page,
					new HtmlArmor( $this->messages['diff'] ),
					[ 'class' => 'mw-changeslist-diff' ],
					[
						'diff' => 'prev',
						'oldid' => $row->rev_id
					]
				);
			} else {
				$difftext = $this->messages['diff'];
			}
			$histlink = $linkRenderer->makeKnownLink(
				$page,
				new HtmlArmor( $this->messages['hist'] ),
				[ 'class' => 'mw-changeslist-history' ],
				[ 'action' => 'history' ]
			);

			if ( $row->rev_parent_id === null ) {
				// For some reason rev_parent_id isn't populated for this row.
				// Its rumoured this is true on wikipedia for some revisions (T36922).
				// Next best thing is to have the total number of bytes.
				$chardiff = ' <span class="mw-changeslist-separator"></span> ';
				$chardiff .= Linker::formatRevisionSize( $row->rev_len );
				$chardiff .= ' <span class="mw-changeslist-separator"></span> ';
			} else {
				$parentLen = 0;
				if ( isset( $this->mParentLens[$row->rev_parent_id] ) ) {
					$parentLen = $this->mParentLens[$row->rev_parent_id];
				}

				$chardiff = ' <span class="mw-changeslist-separator"></span> ';
				$chardiff .= ChangesList::showCharacterDifference(
					$parentLen,
					$row->rev_len,
					$this->getContext()
				);
				$chardiff .= ' <span class="mw-changeslist-separator"></span> ';
			}

			$lang = $this->getLanguage();
			$comment = $lang->getDirMark() . Linker::revComment( $rev, false, true );
			$date = $lang->userTimeAndDate( $row->rev_timestamp, $user );
			if ( $rev->userCan( Revision::DELETED_TEXT, $user ) ) {
				$d = $linkRenderer->makeKnownLink(
					$page,
					$date,
					[ 'class' => 'mw-changeslist-date' ],
					[ 'oldid' => intval( $row->rev_id ) ]
				);
			} else {
				$d = htmlspecialchars( $date );
			}
			if ( $rev->isDeleted( Revision::DELETED_TEXT ) ) {
				$d = '<span class="history-deleted">' . $d . '</span>';
			}

			# Show user names for /newbies as there may be different users.
			# Note that only unprivileged users have rows with hidden user names excluded.
			# When querying for an IP range, we want to always show user and user talk links.
			$userlink = '';
			if ( ( $this->contribs == 'newbie' && !$rev->isDeleted( Revision::DELETED_USER ) )
				|| $this->isQueryableRange( $this->target ) ) {
				$userlink = ' <span class="mw-changeslist-separator"></span> '
					. $lang->getDirMark()
					. Linker::userLink( $rev->getUser(), $rev->getUserText() );
				$userlink .= ' ' . $this->msg( 'parentheses' )->rawParams(
					Linker::userTalkLink( $rev->getUser(), $rev->getUserText() ) )->escaped() . ' ';
			}

			$flags = [];
			if ( $rev->getParentId() === 0 ) {
				$flags[] = ChangesList::flag( 'newpage' );
			}

			if ( $rev->isMinor() ) {
				$flags[] = ChangesList::flag( 'minor' );
			}

			$del = Linker::getRevDeleteLink( $user, $rev, $page );
			if ( $del !== '' ) {
				$del .= ' ';
			}

			$diffHistLinks = $this->msg( 'parentheses' )
				->rawParams( $difftext . $this->messages['pipe-separator'] . $histlink )
				->escaped();

			# Tags, if any.
			list( $tagSummary, $newClasses ) = ChangeTags::formatSummaryRow(
				$row->ts_tags,
				'contributions',
				$this->getContext()
			);
			$classes = array_merge( $classes, $newClasses );

			Hooks::run( 'SpecialContributions::formatRow::flags', [ $this->getContext(), $row, &$flags ] );

			$templateParams = [
				'del' => $del,
				'timestamp' => $d,
				'diffHistLinks' => $diffHistLinks,
				'charDifference' => $chardiff,
				'flags' => $flags,
				'articleLink' => $link,
				'userlink' => $userlink,
				'logText' => $comment,
				'topmarktext' => $topmarktext,
				'tagSummary' => $tagSummary,
			];

			# Denote if username is redacted for this edit
			if ( $rev->isDeleted( Revision::DELETED_USER ) ) {
				$templateParams['rev-deleted-user-contribs'] =
					$this->msg( 'rev-deleted-user-contribs' )->escaped();
			}

			$ret = $this->templateParser->processTemplate(
				'SpecialContributionsLine',
				$templateParams
			);
		}

		// Let extensions add data
		Hooks::run( 'ContributionsLineEnding', [ $this, &$ret, $row, &$classes, &$attribs ] );
		$attribs = array_filter( $attribs,
			[ Sanitizer::class, 'isReservedDataAttribute' ],
			ARRAY_FILTER_USE_KEY
		);

		// TODO: Handle exceptions in the catch block above.  Do any extensions rely on
		// receiving empty rows?

		if ( $classes === [] && $attribs === [] && $ret === '' ) {
			wfDebug( "Dropping Special:Contribution row that could not be formatted\n" );
			return "<!-- Could not format Special:Contribution row. -->\n";
		}
		$attribs['class'] = $classes;

		// FIXME: The signature of the ContributionsLineEnding hook makes it
		// very awkward to move this LI wrapper into the template.
		return Html::rawElement( 'li', $attribs, $ret ) . "\n";
	}

	/**
	 * Overwrite Pager function and return a helpful comment
	 * @return string
	 */
	function getSqlComment() {
		if ( $this->namespace || $this->deletedOnly ) {
			// potentially slow, see CR r58153
			return 'contributions page filtered for namespace or RevisionDeleted edits';
		} else {
			return 'contributions page unfiltered';
		}
	}

	protected function preventClickjacking() {
		$this->preventClickjacking = true;
	}

	/**
	 * @return bool
	 */
	public function getPreventClickjacking() {
		return $this->preventClickjacking;
	}

	/**
	 * Set up date filter options, given request data.
	 *
	 * @param array $opts Options array
	 * @return array Options array with processed start and end date filter options
	 */
	public static function processDateFilter( array $opts ) {
		$start = $opts['start'] ?? '';
		$end = $opts['end'] ?? '';
		$year = $opts['year'] ?? '';
		$month = $opts['month'] ?? '';

		if ( $start !== '' && $end !== '' && $start > $end ) {
			$temp = $start;
			$start = $end;
			$end = $temp;
		}

		// If year/month legacy filtering options are set, convert them to display the new stamp
		if ( $year !== '' || $month !== '' ) {
			// Reuse getDateCond logic, but subtract a day because
			// the endpoints of our date range appear inclusive
			// but the internal end offsets are always exclusive
			$legacyTimestamp = ReverseChronologicalPager::getOffsetDate( $year, $month );
			$legacyDateTime = new DateTime( $legacyTimestamp->getTimestamp( TS_ISO_8601 ) );
			$legacyDateTime = $legacyDateTime->modify( '-1 day' );

			// Clear the new timestamp range options if used and
			// replace with the converted legacy timestamp
			$start = '';
			$end = $legacyDateTime->format( 'Y-m-d' );
		}

		$opts['start'] = $start;
		$opts['end'] = $end;

		return $opts;
	}
}
