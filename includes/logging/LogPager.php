<?php
/**
 * Contain classes to list log entries
 *
 * Copyright © 2004 Brooke Vibber <bvibber@wikimedia.org>
 * https://www.mediawiki.org/
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
 */

namespace MediaWiki\Pager;

use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\Logging\DatabaseLogEntry;
use MediaWiki\Logging\LogEventsList;
use MediaWiki\Logging\LogFormatterFactory;
use MediaWiki\Logging\LogPage;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageReference;
use MediaWiki\Title\Title;
use MediaWiki\User\ActorNormalization;
use MediaWiki\User\UserIdentityValue;
use Wikimedia\Rdbms\IExpression;
use Wikimedia\Rdbms\LikeValue;
use Wikimedia\Rdbms\Platform\ISQLPlatform;

/**
 * @ingroup Pager
 */
class LogPager extends ReverseChronologicalPager {
	/** @var array Log types */
	private $types = [];

	/** @var string Events limited to those by performer when set */
	private $performer = '';

	/**
	 * @var string Events limited to those about this page when set.
	 *  Only exists to support deprecated method getPage().
	 */
	private $page = '';

	/** @var bool */
	private $pattern = false;

	/** @var string */
	private $typeCGI = '';

	/** @var string */
	private $action = '';

	/** @var bool */
	private $performerRestrictionsEnforced = false;

	/** @var bool */
	private $actionRestrictionsEnforced = false;

	/** @var array */
	private $mConds;

	/** @var string */
	private $mTagFilter;

	/** @var bool */
	private $mTagInvert;

	/** @var LogEventsList */
	public $mLogEventsList;

	/** @var LinkBatchFactory */
	private $linkBatchFactory;

	/** @var ActorNormalization */
	private $actorNormalization;
	private LogFormatterFactory $logFormatterFactory;

	/**
	 * @param LogEventsList $list
	 * @param string|array $types Log types to show
	 * @param string $performer The user who made the log entries
	 * @param string|PageReference|(string|PageReference)[] $pages The page(s) the log entries are for
	 * @param bool $pattern Do a prefix search rather than an exact title match
	 * @param array $conds Extra conditions for the query
	 * @param int|bool $year The year to start from. Default: false
	 * @param int|bool $month The month to start from. Default: false
	 * @param int|bool $day The day to start from. Default: false
	 * @param string $tagFilter Tag
	 * @param string $action Specific action (subtype) requested
	 * @param int $logId Log entry ID, to limit to a single log entry.
	 * @param LinkBatchFactory|null $linkBatchFactory
	 * @param ActorNormalization|null $actorNormalization
	 * @param LogFormatterFactory|null $logFormatterFactory
	 * @param bool $tagInvert whether tags are filtered for (false) or out (true)
	 */
	public function __construct( $list, $types = [], $performer = '', $pages = '',
		$pattern = false, $conds = [], $year = false, $month = false, $day = false,
		$tagFilter = '', $action = '', $logId = 0,
		?LinkBatchFactory $linkBatchFactory = null,
		?ActorNormalization $actorNormalization = null,
		?LogFormatterFactory $logFormatterFactory = null,
		$tagInvert = false
	) {
		parent::__construct( $list->getContext() );

		$services = MediaWikiServices::getInstance();
		$this->mConds = $conds;
		$this->mLogEventsList = $list;

		// Class is used directly in extensions - T266480
		$this->linkBatchFactory = $linkBatchFactory ?? $services->getLinkBatchFactory();
		$this->actorNormalization = $actorNormalization ?? $services->getActorNormalization();
		$this->logFormatterFactory = $logFormatterFactory ?? $services->getLogFormatterFactory();

		$this->limitLogId( $logId ); // set before types per T269761
		$this->limitType( $types ); // also excludes hidden types
		$this->limitFilterTypes();
		$this->limitPerformer( $performer );
		$this->limitTitles( $pages, $pattern );
		$this->limitAction( $action );
		$this->getDateCond( $year, $month, $day );
		$this->mTagFilter = (string)$tagFilter;
		$this->mTagInvert = (bool)$tagInvert;
	}

	/** @inheritDoc */
	public function getDefaultQuery() {
		$query = parent::getDefaultQuery();
		$query['type'] = $this->typeCGI; // arrays won't work here
		$query['user'] = $this->performer;
		$query['day'] = $this->mDay;
		$query['month'] = $this->mMonth;
		$query['year'] = $this->mYear;

		return $query;
	}

	private function limitFilterTypes() {
		if ( $this->hasEqualsClause( 'log_id' ) ) { // T220834
			return;
		}
		$filterTypes = $this->getFilterParams();
		$excludeTypes = [];
		foreach ( $filterTypes as $type => $hide ) {
			if ( $hide ) {
				$excludeTypes[] = $type;
			}
		}
		if ( $excludeTypes ) {
			$this->mConds[] = $this->mDb->expr( 'log_type', '!=', $excludeTypes );
		}
	}

	public function getFilterParams(): array {
		$filters = [];
		if ( count( $this->types ) ) {
			return $filters;
		}

		// FIXME: This is broken, values from HTMLForm should be used.
		$wpfilters = $this->getRequest()->getArray( "wpfilters" );
		$filterLogTypes = $this->getConfig()->get( MainConfigNames::FilterLogTypes );

		foreach ( $filterLogTypes as $type => $default ) {
			// Back-compat: Check old URL params if the new param wasn't passed
			if ( $wpfilters === null ) {
				$hide = $this->getRequest()->getBool( "hide_{$type}_log", $default );
			} else {
				$hide = !in_array( $type, $wpfilters );
			}

			$filters[$type] = $hide;
		}

		return $filters;
	}

	/**
	 * Set the log reader to return only entries of the given type.
	 * Type restrictions enforced here
	 *
	 * @param string|array $types Log types ('upload', 'delete', etc);
	 *   empty string means no restriction
	 */
	private function limitType( $types ) {
		$restrictions = $this->getConfig()->get( MainConfigNames::LogRestrictions );
		// If $types is not an array, make it an array
		$types = ( $types === '' ) ? [] : (array)$types;
		// Don't even show header for private logs; don't recognize it...
		$needReindex = false;
		foreach ( $types as $type ) {
			if ( isset( $restrictions[$type] )
				&& !$this->getAuthority()->isAllowed( $restrictions[$type] )
			) {
				$needReindex = true;
				$types = array_diff( $types, [ $type ] );
			}
		}
		if ( $needReindex ) {
			// Lots of this code makes assumptions that
			// the first entry in the array is $types[0].
			$types = array_values( $types );
		}
		$this->types = $types;
		// Don't show private logs to unprivileged users.
		// Also, only show them upon specific request to avoid surprises.
		// Exception: if we are showing only a single log entry based on the log id,
		// we don't require that "specific request" so that the links-in-logs feature
		// works. See T269761
		$audience = ( $types || $this->hasEqualsClause( 'log_id' ) ) ? 'user' : 'public';
		$hideLogs = LogEventsList::getExcludeClause( $this->mDb, $audience, $this->getAuthority() );
		if ( $hideLogs !== false ) {
			$this->mConds[] = $hideLogs;
		}
		if ( count( $types ) ) {
			$this->mConds['log_type'] = $types;
			// Set typeCGI; used in url param for paging
			if ( count( $types ) == 1 ) {
				$this->typeCGI = $types[0];
			}
		}
	}

	/**
	 * Set the log reader to return only entries by the given user.
	 *
	 * @param string $name (In)valid user name
	 * @return void
	 */
	private function limitPerformer( $name ) {
		if ( $name == '' ) {
			return;
		}

		$actorId = $this->actorNormalization->findActorIdByName( $name, $this->mDb );

		if ( !$actorId ) {
			// Unknown user, match nothing.
			$this->mConds[] = '1 = 0';
			return;
		}

		$this->mConds[ 'log_actor' ] = $actorId;

		$this->enforcePerformerRestrictions();

		$this->performer = $name;
	}

	/**
	 * Set the log reader to return only entries affecting the given pages.
	 * (For the block and rights logs, these are user pages.)
	 *
	 * @param string|PageReference|(string|PageReference)[] $pages
	 * @param bool $pattern
	 * @return void
	 */
	private function limitTitles( $pages, $pattern ) {
		if ( !is_array( $pages ) ) {
			$pages = [ $pages ];
		}
		$orConds = [];
		$pattern = $pattern && !$this->getConfig()->get( MainConfigNames::MiserMode );
		foreach ( $pages as $page ) {
			if ( (string)$page === '' ) {
				continue;
			}
			if ( !$page instanceof PageReference ) {
				// NOTE: For some types of logs, the title may be something strange, like "User:#12345"!
				$page = Title::newFromText( $page );
				if ( !$page ) {
					continue;
				}
			}
			$titleFormatter = MediaWikiServices::getInstance()->getTitleFormatter();
			$this->page = $titleFormatter->getPrefixedDBkey( $page );
			$orConds[] = $this->mDb->makeList(
				$this->getTitleConds( $page, $pattern ),
				ISQLPlatform::LIST_AND
			);
		}
		if ( $orConds ) {
			$this->mConds[] = $this->mDb->makeList( $orConds, ISQLPlatform::LIST_OR );
			$this->pattern = $pattern;
			$this->enforceActionRestrictions();
		}
	}

	/**
	 * Get conditions applying to a given page
	 *
	 * @param PageReference $page
	 * @param bool $pattern Whether to use a prefix search (pre-validated)
	 * @return array
	 */
	private function getTitleConds( $page, $pattern ) {
		$conds = [];
		$ns = $page->getNamespace();
		$db = $this->mDb;

		$interwikiDelimiter = $this->getConfig()->get( MainConfigNames::UserrightsInterwikiDelimiter );

		$doUserRightsLogLike = false;
		if ( $this->types == [ 'rights' ] ) {
			$parts = explode( $interwikiDelimiter, $page->getDBkey() );
			if ( count( $parts ) == 2 ) {
				[ $name, $database ] = array_map( 'trim', $parts );
				if ( str_contains( $database, '*' ) ) {
					$doUserRightsLogLike = true;
				}
			}
		}

		/**
		 * Using the (log_namespace, log_title, log_timestamp) index with a
		 * range scan (LIKE) on the first two parts, instead of simple equality,
		 * makes it unusable for sorting.  Sorted retrieval using another index
		 * would be possible, but then we might have to scan arbitrarily many
		 * nodes of that index. Therefore, we need to avoid this if $wgMiserMode
		 * is on.
		 *
		 * This is not a problem with simple title matches, because then we can
		 * use the log_page_time index.  That should have no more than a few hundred
		 * log entries for even the busiest pages, so it can be safely scanned
		 * in full to satisfy an impossible condition on user or similar.
		 */
		$conds['log_namespace'] = $ns;
		if ( $doUserRightsLogLike ) {
			// @phan-suppress-next-line PhanPossiblyUndeclaredVariable $name is set when reached here
			$params = [ $name . $interwikiDelimiter ];
			// @phan-suppress-next-next-line PhanPossiblyUndeclaredVariable $database is set when reached here
			// @phan-suppress-next-line PhanTypeMismatchArgumentNullableInternal $database is set when reached here
			$databaseParts = explode( '*', $database );
			$databasePartCount = count( $databaseParts );
			foreach ( $databaseParts as $i => $databasepart ) {
				$params[] = $databasepart;
				if ( $i < $databasePartCount - 1 ) {
					$params[] = $db->anyString();
				}
			}
			$conds[] = $db->expr( 'log_title', IExpression::LIKE, new LikeValue( ...$params ) );
		} elseif ( $pattern ) {
			$conds[] = $db->expr(
				'log_title',
				IExpression::LIKE,
				new LikeValue( $page->getDBkey(), $db->anyString() )
			);
		} else {
			$conds['log_title'] = $page->getDBkey();
		}
		return $conds;
	}

	/**
	 * Set the log_action field to a specified value (or values)
	 *
	 * @param string $action
	 */
	private function limitAction( $action ) {
		// Allow to filter the log by actions
		$type = $this->typeCGI;
		if ( $type === '' ) {
			// nothing to do
			return;
		}
		$actions = $this->getConfig()->get( MainConfigNames::ActionFilteredLogs );
		if ( isset( $actions[$type] ) ) {
			// log type can be filtered by actions
			if ( $action !== '' && isset( $actions[$type][$action] ) ) {
				// add condition to query
				$this->mConds['log_action'] = $actions[$type][$action];
				$this->action = $action;
			}
		}
	}

	/**
	 * Limit to the (single) specified log ID.
	 * @param int $logId The log entry ID.
	 */
	protected function limitLogId( $logId ) {
		if ( !$logId ) {
			return;
		}
		$this->mConds['log_id'] = $logId;
	}

	/**
	 * Constructs the most part of the query. Extra conditions are sprinkled in
	 * all over this class.
	 * @return array
	 */
	public function getQueryInfo() {
		$queryBuilder = DatabaseLogEntry::newSelectQueryBuilder( $this->mDb )
			->where( $this->mConds );

		# Add log_search table if there are conditions on it.
		# This filters the results to only include log rows that have
		# log_search records with the specified ls_field and ls_value values.
		if ( array_key_exists( 'ls_field', $this->mConds ) ) {
			$queryBuilder->join( 'log_search', null, 'ls_log_id=log_id' );
			$queryBuilder->ignoreIndex( [ 'log_search' => 'ls_log_id' ] );
			$queryBuilder->useIndex( [ 'logging' => 'PRIMARY' ] );
			if ( !$this->hasEqualsClause( 'ls_field' )
				|| !$this->hasEqualsClause( 'ls_value' )
			) {
				# Since (ls_field,ls_value,ls_logid) is unique, if the condition is
				# to match a specific (ls_field,ls_value) tuple, then there will be
				# no duplicate log rows. Otherwise, we need to remove the duplicates.
				$queryBuilder->distinct();
			}
		} elseif ( array_key_exists( 'log_actor', $this->mConds ) ) {
			// Optimizer doesn't pick the right index when a user has lots of log actions (T303089)
			$index = 'log_actor_time';
			foreach ( $this->getFilterParams() as $hide ) {
				if ( !$hide ) {
					$index = 'log_actor_type_time';
					break;
				}
			}
			$queryBuilder->useIndex( [ 'logging' => $index ] );
		}

		// T221458: MySQL/MariaDB (10.1.37) can sometimes irrationally decide that querying `actor` before
		// `logging` and filesorting is somehow better than querying $limit+1 rows from `logging`.
		// Tell it not to reorder the query. But not when tag filtering or log_search was used, as it
		// seems as likely to be harmed as helped in that case.
		if ( $this->mTagFilter === '' && !array_key_exists( 'ls_field', $this->mConds ) ) {
			$queryBuilder->straightJoinOption();
		}

		$maxExecTime = $this->getConfig()->get( MainConfigNames::MaxExecutionTimeForExpensiveQueries );
		if ( $maxExecTime ) {
			$queryBuilder->setMaxExecutionTime( $maxExecTime );
		}

		# Add ChangeTags filter query
		MediaWikiServices::getInstance()->getChangeTagsStore()->modifyDisplayQueryBuilder(
			$queryBuilder,
			'logging',
			$this->mTagFilter,
			$this->mTagInvert
		);

		return $queryBuilder->getQueryInfo();
	}

	/**
	 * Checks if $this->mConds has $field matched to a *single* value
	 * @param string $field
	 * @return bool
	 */
	protected function hasEqualsClause( $field ) {
		return (
			array_key_exists( $field, $this->mConds ) &&
			( !is_array( $this->mConds[$field] ) || count( $this->mConds[$field] ) == 1 )
		);
	}

	/** @inheritDoc */
	public function getIndexField() {
		return [ [ 'log_timestamp', 'log_id' ] ];
	}

	protected function doBatchLookups() {
		$lb = $this->linkBatchFactory->newLinkBatch()->setCaller( __METHOD__ );
		foreach ( $this->mResult as $row ) {
			$lb->add( $row->log_namespace, $row->log_title );
			$lb->addUser( new UserIdentityValue( (int)$row->log_user, $row->log_user_text ) );
			$formatter = $this->logFormatterFactory->newFromRow( $row );
			foreach ( $formatter->getPreloadTitles() as $title ) {
				$lb->addObj( $title );
			}
		}
		$lb->execute();
	}

	/** @inheritDoc */
	public function formatRow( $row ) {
		return $this->mLogEventsList->logLine( $row );
	}

	/**
	 * @deprecated since 1.45
	 * @return array
	 */
	public function getType() {
		wfDeprecated( __METHOD__, '1.45' );
		return $this->types;
	}

	/**
	 * Guaranteed to either return a valid title string or a Zero-Length String
	 *
	 * @return string
	 */
	public function getPerformer() {
		return $this->performer;
	}

	/**
	 * @deprecated since 1.45
	 * @return string
	 */
	public function getPage() {
		wfDeprecated( __METHOD__, '1.45' );
		return $this->page;
	}

	/**
	 * @deprecated since 1.45
	 * @return bool
	 */
	public function getPattern() {
		wfDeprecated( __METHOD__, '1.45' );
		return $this->pattern;
	}

	/**
	 * @deprecated since 1.45
	 * @return int
	 */
	public function getYear() {
		wfDeprecated( __METHOD__, '1.45' );
		return $this->mYear;
	}

	/**
	 * @deprecated since 1.45
	 * @return int
	 */
	public function getMonth() {
		wfDeprecated( __METHOD__, '1.45' );
		return $this->mMonth;
	}

	/**
	 * @deprecated since 1.45
	 * @return int
	 */
	public function getDay() {
		wfDeprecated( __METHOD__, '1.45' );
		return $this->mDay;
	}

	/**
	 * @deprecated since 1.45
	 * @return string
	 */
	public function getTagFilter() {
		return $this->mTagFilter;
	}

	/**
	 * @deprecated since 1.45
	 * @return bool
	 */
	public function getTagInvert() {
		wfDeprecated( __METHOD__, '1.45' );
		return $this->mTagInvert;
	}

	/**
	 * @deprecated since 1.45
	 * @return string
	 */
	public function getAction() {
		wfDeprecated( __METHOD__, '1.45' );
		return $this->action;
	}

	/**
	 * Paranoia: avoid brute force searches (T19342)
	 */
	private function enforceActionRestrictions() {
		if ( $this->actionRestrictionsEnforced ) {
			return;
		}
		$this->actionRestrictionsEnforced = true;
		if ( !$this->getAuthority()->isAllowed( 'deletedhistory' ) ) {
			$this->mConds[] = $this->mDb->bitAnd( 'log_deleted', LogPage::DELETED_ACTION ) . ' = 0';
		} elseif ( !$this->getAuthority()->isAllowedAny( 'suppressrevision', 'viewsuppressed' ) ) {
			$this->mConds[] = $this->mDb->bitAnd( 'log_deleted', LogPage::SUPPRESSED_ACTION ) .
				' != ' . LogPage::SUPPRESSED_ACTION;
		}
	}

	/**
	 * Paranoia: avoid brute force searches (T19342)
	 */
	private function enforcePerformerRestrictions() {
		// Same as enforceActionRestrictions(), except for _USER instead of _ACTION bits.
		if ( $this->performerRestrictionsEnforced ) {
			return;
		}
		$this->performerRestrictionsEnforced = true;
		if ( !$this->getAuthority()->isAllowed( 'deletedhistory' ) ) {
			$this->mConds[] = $this->mDb->bitAnd( 'log_deleted', LogPage::DELETED_USER ) . ' = 0';
		} elseif ( !$this->getAuthority()->isAllowedAny( 'suppressrevision', 'viewsuppressed' ) ) {
			$this->mConds[] = $this->mDb->bitAnd( 'log_deleted', LogPage::SUPPRESSED_USER ) .
				' != ' . LogPage::SUPPRESSED_USER;
		}
	}
}

/** @deprecated class alias since 1.41 */
class_alias( LogPager::class, 'LogPager' );
