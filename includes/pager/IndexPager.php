<?php
/**
 * Efficient paging for SQL queries.
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

use Wikimedia\Rdbms\ResultWrapper;
use Wikimedia\Rdbms\IDatabase;

/**
 * IndexPager is an efficient pager which uses a (roughly unique) index in the
 * data set to implement paging, rather than a "LIMIT offset,limit" clause.
 * In MySQL, such a limit/offset clause requires counting through the
 * specified number of offset rows to find the desired data, which can be
 * expensive for large offsets.
 *
 * ReverseChronologicalPager is a child class of the abstract IndexPager, and
 * contains  some formatting and display code which is specific to the use of
 * timestamps as  indexes. Here is a synopsis of its operation:
 *
 *    * The query is specified by the offset, limit and direction (dir)
 *      parameters, in addition to any subclass-specific parameters.
 *    * The offset is the non-inclusive start of the DB query. A row with an
 *      index value equal to the offset will never be shown.
 *    * The query may either be done backwards, where the rows are returned by
 *      the database in the opposite order to which they are displayed to the
 *      user, or forwards. This is specified by the "dir" parameter, dir=prev
 *      means backwards, anything else means forwards. The offset value
 *      specifies the start of the database result set, which may be either
 *      the start or end of the displayed data set. This allows "previous"
 *      links to be implemented without knowledge of the index value at the
 *      start of the previous page.
 *    * An additional row beyond the user-specified limit is always requested.
 *      This allows us to tell whether we should display a "next" link in the
 *      case of forwards mode, or a "previous" link in the case of backwards
 *      mode. Determining whether to display the other link (the one for the
 *      page before the start of the database result set) can be done
 *      heuristically by examining the offset.
 *
 *    * An empty offset indicates that the offset condition should be omitted
 *      from the query. This naturally produces either the first page or the
 *      last page depending on the dir parameter.
 *
 *  Subclassing the pager to implement concrete functionality should be fairly
 *  simple, please see the examples in HistoryAction.php and
 *  SpecialBlockList.php. You just need to override formatRow(),
 *  getQueryInfo() and getIndexField(). Don't forget to call the parent
 *  constructor if you override it.
 *
 * @ingroup Pager
 */
abstract class IndexPager extends ContextSource implements Pager {
	/**
	 * Constants for the $mDefaultDirection field.
	 *
	 * These are boolean for historical reasons and should stay boolean for backwards-compatibility.
	 */
	const DIR_ASCENDING = false;
	const DIR_DESCENDING = true;

	public $mRequest;
	public $mLimitsShown = [ 20, 50, 100, 250, 500 ];
	public $mDefaultLimit = 50;
	public $mOffset, $mLimit;
	public $mQueryDone = false;
	public $mDb;
	public $mPastTheEndRow;

	/**
	 * The index to actually be used for ordering. This is a single column,
	 * for one ordering, even if multiple orderings are supported.
	 */
	protected $mIndexField;
	/**
	 * An array of secondary columns to order by. These fields are not part of the offset.
	 * This is a column list for one ordering, even if multiple orderings are supported.
	 */
	protected $mExtraSortFields;
	/** For pages that support multiple types of ordering, which one to use.
	 */
	protected $mOrderType;
	/**
	 * $mDefaultDirection gives the direction to use when sorting results:
	 * DIR_ASCENDING or DIR_DESCENDING.  If $mIsBackwards is set, we
	 * start from the opposite end, but we still sort the page itself according
	 * to $mDefaultDirection.  E.g., if $mDefaultDirection is false but we're
	 * going backwards, we'll display the last page of results, but the last
	 * result will be at the bottom, not the top.
	 *
	 * Like $mIndexField, $mDefaultDirection will be a single value even if the
	 * class supports multiple default directions for different order types.
	 */
	public $mDefaultDirection;
	public $mIsBackwards;

	/** True if the current result set is the first one */
	public $mIsFirst;
	public $mIsLast;

	protected $mLastShown, $mFirstShown, $mPastTheEndIndex, $mDefaultQuery, $mNavigationBar;

	/**
	 * Whether to include the offset in the query
	 */
	protected $mIncludeOffset = false;

	/**
	 * Result object for the query. Warning: seek before use.
	 *
	 * @var ResultWrapper
	 */
	public $mResult;

	public function __construct( IContextSource $context = null ) {
		if ( $context ) {
			$this->setContext( $context );
		}

		$this->mRequest = $this->getRequest();

		# NB: the offset is quoted, not validated. It is treated as an
		# arbitrary string to support the widest variety of index types. Be
		# careful outputting it into HTML!
		$this->mOffset = $this->mRequest->getText( 'offset' );

		# Use consistent behavior for the limit options
		$this->mDefaultLimit = $this->getUser()->getIntOption( 'rclimit' );
		if ( !$this->mLimit ) {
			// Don't override if a subclass calls $this->setLimit() in its constructor.
			list( $this->mLimit, /* $offset */ ) = $this->mRequest->getLimitOffset();
		}

		$this->mIsBackwards = ( $this->mRequest->getVal( 'dir' ) == 'prev' );
		# Let the subclass set the DB here; otherwise use a replica DB for the current wiki
		$this->mDb = $this->mDb ?: wfGetDB( DB_REPLICA );

		$index = $this->getIndexField(); // column to sort on
		$extraSort = $this->getExtraSortFields(); // extra columns to sort on for query planning
		$order = $this->mRequest->getVal( 'order' );
		if ( is_array( $index ) && isset( $index[$order] ) ) {
			$this->mOrderType = $order;
			$this->mIndexField = $index[$order];
			$this->mExtraSortFields = isset( $extraSort[$order] )
				? (array)$extraSort[$order]
				: [];
		} elseif ( is_array( $index ) ) {
			# First element is the default
			reset( $index );
			list( $this->mOrderType, $this->mIndexField ) = each( $index );
			$this->mExtraSortFields = isset( $extraSort[$this->mOrderType] )
				? (array)$extraSort[$this->mOrderType]
				: [];
		} else {
			# $index is not an array
			$this->mOrderType = null;
			$this->mIndexField = $index;
			$this->mExtraSortFields = (array)$extraSort;
		}

		if ( !isset( $this->mDefaultDirection ) ) {
			$dir = $this->getDefaultDirections();
			$this->mDefaultDirection = is_array( $dir )
				? $dir[$this->mOrderType]
				: $dir;
		}
	}

	/**
	 * Get the Database object in use
	 *
	 * @return IDatabase
	 */
	public function getDatabase() {
		return $this->mDb;
	}

	/**
	 * Do the query, using information from the object context. This function
	 * has been kept minimal to make it overridable if necessary, to allow for
	 * result sets formed from multiple DB queries.
	 */
	public function doQuery() {
		# Use the child class name for profiling
		$fname = __METHOD__ . ' (' . static::class . ')';
		$section = Profiler::instance()->scopedProfileIn( $fname );

		// @todo This should probably compare to DIR_DESCENDING and DIR_ASCENDING constants
		$descending = ( $this->mIsBackwards == $this->mDefaultDirection );
		# Plus an extra row so that we can tell the "next" link should be shown
		$queryLimit = $this->mLimit + 1;

		if ( $this->mOffset == '' ) {
			$isFirst = true;
		} else {
			// If there's an offset, we may or may not be at the first entry.
			// The only way to tell is to run the query in the opposite
			// direction see if we get a row.
			$oldIncludeOffset = $this->mIncludeOffset;
			$this->mIncludeOffset = !$this->mIncludeOffset;
			$isFirst = !$this->reallyDoQuery( $this->mOffset, 1, !$descending )->numRows();
			$this->mIncludeOffset = $oldIncludeOffset;
		}

		$this->mResult = $this->reallyDoQuery(
			$this->mOffset,
			$queryLimit,
			$descending
		);

		$this->extractResultInfo( $isFirst, $queryLimit, $this->mResult );
		$this->mQueryDone = true;

		$this->preprocessResults( $this->mResult );
		$this->mResult->rewind(); // Paranoia
	}

	/**
	 * @return ResultWrapper The result wrapper.
	 */
	function getResult() {
		return $this->mResult;
	}

	/**
	 * Set the offset from an other source than the request
	 *
	 * @param int|string $offset
	 */
	function setOffset( $offset ) {
		$this->mOffset = $offset;
	}

	/**
	 * Set the limit from an other source than the request
	 *
	 * Verifies limit is between 1 and 5000
	 *
	 * @param int|string $limit
	 */
	function setLimit( $limit ) {
		$limit = (int)$limit;
		// WebRequest::getLimitOffset() puts a cap of 5000, so do same here.
		if ( $limit > 5000 ) {
			$limit = 5000;
		}
		if ( $limit > 0 ) {
			$this->mLimit = $limit;
		}
	}

	/**
	 * Get the current limit
	 *
	 * @return int
	 */
	function getLimit() {
		return $this->mLimit;
	}

	/**
	 * Set whether a row matching exactly the offset should be also included
	 * in the result or not. By default this is not the case, but when the
	 * offset is user-supplied this might be wanted.
	 *
	 * @param bool $include
	 */
	public function setIncludeOffset( $include ) {
		$this->mIncludeOffset = $include;
	}

	/**
	 * Extract some useful data from the result object for use by
	 * the navigation bar, put it into $this
	 *
	 * @param bool $isFirst False if there are rows before those fetched (i.e.
	 *     if a "previous" link would make sense)
	 * @param int $limit Exact query limit
	 * @param ResultWrapper $res
	 */
	function extractResultInfo( $isFirst, $limit, ResultWrapper $res ) {
		$numRows = $res->numRows();
		if ( $numRows ) {
			# Remove any table prefix from index field
			$parts = explode( '.', $this->mIndexField );
			$indexColumn = end( $parts );

			$row = $res->fetchRow();
			$firstIndex = $row[$indexColumn];

			# Discard the extra result row if there is one
			if ( $numRows > $this->mLimit && $numRows > 1 ) {
				$res->seek( $numRows - 1 );
				$this->mPastTheEndRow = $res->fetchObject();
				$this->mPastTheEndIndex = $this->mPastTheEndRow->$indexColumn;
				$res->seek( $numRows - 2 );
				$row = $res->fetchRow();
				$lastIndex = $row[$indexColumn];
			} else {
				$this->mPastTheEndRow = null;
				# Setting indexes to an empty string means that they will be
				# omitted if they would otherwise appear in URLs. It just so
				# happens that this  is the right thing to do in the standard
				# UI, in all the relevant cases.
				$this->mPastTheEndIndex = '';
				$res->seek( $numRows - 1 );
				$row = $res->fetchRow();
				$lastIndex = $row[$indexColumn];
			}
		} else {
			$firstIndex = '';
			$lastIndex = '';
			$this->mPastTheEndRow = null;
			$this->mPastTheEndIndex = '';
		}

		if ( $this->mIsBackwards ) {
			$this->mIsFirst = ( $numRows < $limit );
			$this->mIsLast = $isFirst;
			$this->mLastShown = $firstIndex;
			$this->mFirstShown = $lastIndex;
		} else {
			$this->mIsFirst = $isFirst;
			$this->mIsLast = ( $numRows < $limit );
			$this->mLastShown = $lastIndex;
			$this->mFirstShown = $firstIndex;
		}
	}

	/**
	 * Get some text to go in brackets in the "function name" part of the SQL comment
	 *
	 * @return string
	 */
	function getSqlComment() {
		return static::class;
	}

	/**
	 * Do a query with specified parameters, rather than using the object
	 * context
	 *
	 * @param string $offset Index offset, inclusive
	 * @param int $limit Exact query limit
	 * @param bool $descending Query direction, false for ascending, true for descending
	 * @return ResultWrapper
	 */
	public function reallyDoQuery( $offset, $limit, $descending ) {
		list( $tables, $fields, $conds, $fname, $options, $join_conds ) =
			$this->buildQueryInfo( $offset, $limit, $descending );

		return $this->mDb->select( $tables, $fields, $conds, $fname, $options, $join_conds );
	}

	/**
	 * Build variables to use by the database wrapper.
	 *
	 * @param string $offset Index offset, inclusive
	 * @param int $limit Exact query limit
	 * @param bool $descending Query direction, false for ascending, true for descending
	 * @return array
	 */
	protected function buildQueryInfo( $offset, $limit, $descending ) {
		$fname = __METHOD__ . ' (' . $this->getSqlComment() . ')';
		$info = $this->getQueryInfo();
		$tables = $info['tables'];
		$fields = $info['fields'];
		$conds = isset( $info['conds'] ) ? $info['conds'] : [];
		$options = isset( $info['options'] ) ? $info['options'] : [];
		$join_conds = isset( $info['join_conds'] ) ? $info['join_conds'] : [];
		$sortColumns = array_merge( [ $this->mIndexField ], $this->mExtraSortFields );
		if ( $descending ) {
			$options['ORDER BY'] = $sortColumns;
			$operator = $this->mIncludeOffset ? '>=' : '>';
		} else {
			$orderBy = [];
			foreach ( $sortColumns as $col ) {
				$orderBy[] = $col . ' DESC';
			}
			$options['ORDER BY'] = $orderBy;
			$operator = $this->mIncludeOffset ? '<=' : '<';
		}
		if ( $offset != '' ) {
			$conds[] = $this->mIndexField . $operator . $this->mDb->addQuotes( $offset );
		}
		$options['LIMIT'] = intval( $limit );
		return [ $tables, $fields, $conds, $fname, $options, $join_conds ];
	}

	/**
	 * Pre-process results; useful for performing batch existence checks, etc.
	 *
	 * @param ResultWrapper $result
	 */
	protected function preprocessResults( $result ) {
	}

	/**
	 * Get the formatted result list. Calls getStartBody(), formatRow() and
	 * getEndBody(), concatenates the results and returns them.
	 *
	 * @return string
	 */
	public function getBody() {
		if ( !$this->mQueryDone ) {
			$this->doQuery();
		}

		if ( $this->mResult->numRows() ) {
			# Do any special query batches before display
			$this->doBatchLookups();
		}

		# Don't use any extra rows returned by the query
		$numRows = min( $this->mResult->numRows(), $this->mLimit );

		$s = $this->getStartBody();
		if ( $numRows ) {
			if ( $this->mIsBackwards ) {
				for ( $i = $numRows - 1; $i >= 0; $i-- ) {
					$this->mResult->seek( $i );
					$row = $this->mResult->fetchObject();
					$s .= $this->formatRow( $row );
				}
			} else {
				$this->mResult->seek( 0 );
				for ( $i = 0; $i < $numRows; $i++ ) {
					$row = $this->mResult->fetchObject();
					$s .= $this->formatRow( $row );
				}
			}
		} else {
			$s .= $this->getEmptyBody();
		}
		$s .= $this->getEndBody();
		return $s;
	}

	/**
	 * Make a self-link
	 *
	 * @param string $text Text displayed on the link
	 * @param array $query Associative array of parameter to be in the query string
	 * @param string $type Link type used to create additional attributes, like "rel", "class" or
	 *  "title". Valid values (non-exhaustive list): 'first', 'last', 'prev', 'next', 'asc', 'desc'.
	 * @return string HTML fragment
	 */
	function makeLink( $text, array $query = null, $type = null ) {
		if ( $query === null ) {
			return $text;
		}

		$attrs = [];
		if ( in_array( $type, [ 'prev', 'next' ] ) ) {
			$attrs['rel'] = $type;
		}

		if ( in_array( $type, [ 'asc', 'desc' ] ) ) {
			$attrs['title'] = wfMessage( $type == 'asc' ? 'sort-ascending' : 'sort-descending' )->text();
		}

		if ( $type ) {
			$attrs['class'] = "mw-{$type}link";
		}

		return Linker::linkKnown(
			$this->getTitle(),
			$text,
			$attrs,
			$query + $this->getDefaultQuery()
		);
	}

	/**
	 * Called from getBody(), before getStartBody() is called and
	 * after doQuery() was called. This will be called only if there
	 * are rows in the result set.
	 *
	 * @return void
	 */
	protected function doBatchLookups() {
	}

	/**
	 * Hook into getBody(), allows text to be inserted at the start. This
	 * will be called even if there are no rows in the result set.
	 *
	 * @return string
	 */
	protected function getStartBody() {
		return '';
	}

	/**
	 * Hook into getBody() for the end of the list
	 *
	 * @return string
	 */
	protected function getEndBody() {
		return '';
	}

	/**
	 * Hook into getBody(), for the bit between the start and the
	 * end when there are no rows
	 *
	 * @return string
	 */
	protected function getEmptyBody() {
		return '';
	}

	/**
	 * Get an array of query parameters that should be put into self-links.
	 * By default, all parameters passed in the URL are used, except for a
	 * short blacklist.
	 *
	 * @return array Associative array
	 */
	function getDefaultQuery() {
		if ( !isset( $this->mDefaultQuery ) ) {
			$this->mDefaultQuery = $this->getRequest()->getQueryValues();
			unset( $this->mDefaultQuery['title'] );
			unset( $this->mDefaultQuery['dir'] );
			unset( $this->mDefaultQuery['offset'] );
			unset( $this->mDefaultQuery['limit'] );
			unset( $this->mDefaultQuery['order'] );
			unset( $this->mDefaultQuery['month'] );
			unset( $this->mDefaultQuery['year'] );
		}
		return $this->mDefaultQuery;
	}

	/**
	 * Get the number of rows in the result set
	 *
	 * @return int
	 */
	function getNumRows() {
		if ( !$this->mQueryDone ) {
			$this->doQuery();
		}
		return $this->mResult->numRows();
	}

	/**
	 * Get a URL query array for the prev, next, first and last links.
	 *
	 * @return array
	 */
	function getPagingQueries() {
		if ( !$this->mQueryDone ) {
			$this->doQuery();
		}

		# Don't announce the limit everywhere if it's the default
		$urlLimit = $this->mLimit == $this->mDefaultLimit ? null : $this->mLimit;

		if ( $this->mIsFirst ) {
			$prev = false;
			$first = false;
		} else {
			$prev = [
				'dir' => 'prev',
				'offset' => $this->mFirstShown,
				'limit' => $urlLimit
			];
			$first = [ 'limit' => $urlLimit ];
		}
		if ( $this->mIsLast ) {
			$next = false;
			$last = false;
		} else {
			$next = [ 'offset' => $this->mLastShown, 'limit' => $urlLimit ];
			$last = [ 'dir' => 'prev', 'limit' => $urlLimit ];
		}
		return [
			'prev' => $prev,
			'next' => $next,
			'first' => $first,
			'last' => $last
		];
	}

	/**
	 * Returns whether to show the "navigation bar"
	 *
	 * @return bool
	 */
	function isNavigationBarShown() {
		if ( !$this->mQueryDone ) {
			$this->doQuery();
		}
		// Hide navigation by default if there is nothing to page
		return !( $this->mIsFirst && $this->mIsLast );
	}

	/**
	 * Get paging links. If a link is disabled, the item from $disabledTexts
	 * will be used. If there is no such item, the unlinked text from
	 * $linkTexts will be used. Both $linkTexts and $disabledTexts are arrays
	 * of HTML.
	 *
	 * @param array $linkTexts
	 * @param array $disabledTexts
	 * @return array
	 */
	function getPagingLinks( $linkTexts, $disabledTexts = [] ) {
		$queries = $this->getPagingQueries();
		$links = [];

		foreach ( $queries as $type => $query ) {
			if ( $query !== false ) {
				$links[$type] = $this->makeLink(
					$linkTexts[$type],
					$queries[$type],
					$type
				);
			} elseif ( isset( $disabledTexts[$type] ) ) {
				$links[$type] = $disabledTexts[$type];
			} else {
				$links[$type] = $linkTexts[$type];
			}
		}

		return $links;
	}

	function getLimitLinks() {
		$links = [];
		if ( $this->mIsBackwards ) {
			$offset = $this->mPastTheEndIndex;
		} else {
			$offset = $this->mOffset;
		}
		foreach ( $this->mLimitsShown as $limit ) {
			$links[] = $this->makeLink(
				$this->getLanguage()->formatNum( $limit ),
				[ 'offset' => $offset, 'limit' => $limit ],
				'num'
			);
		}
		return $links;
	}

	/**
	 * Abstract formatting function. This should return an HTML string
	 * representing the result row $row. Rows will be concatenated and
	 * returned by getBody()
	 *
	 * @param array|stdClass $row Database row
	 * @return string
	 */
	abstract function formatRow( $row );

	/**
	 * This function should be overridden to provide all parameters
	 * needed for the main paged query. It returns an associative
	 * array with the following elements:
	 *    tables => Table(s) for passing to Database::select()
	 *    fields => Field(s) for passing to Database::select(), may be *
	 *    conds => WHERE conditions
	 *    options => option array
	 *    join_conds => JOIN conditions
	 *
	 * @return array
	 */
	abstract function getQueryInfo();

	/**
	 * This function should be overridden to return the name of the index fi-
	 * eld.  If the pager supports multiple orders, it may return an array of
	 * 'querykey' => 'indexfield' pairs, so that a request with &count=querykey
	 * will use indexfield to sort.  In this case, the first returned key is
	 * the default.
	 *
	 * Needless to say, it's really not a good idea to use a non-unique index
	 * for this!  That won't page right.
	 *
	 * @return string|array
	 */
	abstract function getIndexField();

	/**
	 * This function should be overridden to return the names of secondary columns
	 * to order by in addition to the column in getIndexField(). These fields will
	 * not be used in the pager offset or in any links for users.
	 *
	 * If getIndexField() returns an array of 'querykey' => 'indexfield' pairs then
	 * this must return a corresponding array of 'querykey' => [ fields... ] pairs
	 * in order for a request with &count=querykey to use [ fields... ] to sort.
	 *
	 * This is useful for pagers that GROUP BY a unique column (say page_id)
	 * and ORDER BY another (say page_len). Using GROUP BY and ORDER BY both on
	 * page_len,page_id avoids temp tables (given a page_len index). This would
	 * also work if page_id was non-unique but we had a page_len,page_id index.
	 *
	 * @return array
	 */
	protected function getExtraSortFields() {
		return [];
	}

	/**
	 * Return the default sorting direction: DIR_ASCENDING or DIR_DESCENDING.
	 * You can also have an associative array of ordertype => dir,
	 * if multiple order types are supported.  In this case getIndexField()
	 * must return an array, and the keys of that must exactly match the keys
	 * of this.
	 *
	 * For backward compatibility, this method's return value will be ignored
	 * if $this->mDefaultDirection is already set when the constructor is
	 * called, for instance if it's statically initialized.  In that case the
	 * value of that variable (which must be a boolean) will be used.
	 *
	 * Note that despite its name, this does not return the value of the
	 * $this->mDefaultDirection member variable.  That's the default for this
	 * particular instantiation, which is a single value.  This is the set of
	 * all defaults for the class.
	 *
	 * @return bool
	 */
	protected function getDefaultDirections() {
		return IndexPager::DIR_ASCENDING;
	}
}
