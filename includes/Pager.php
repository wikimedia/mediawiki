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

/**
 * @defgroup Pager Pager
 */

/**
 * Basic pager interface.
 * @ingroup Pager
 */
interface Pager {
	function getNavigationBar();
	function getBody();
}

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
 *  simple, please see the examples in HistoryPage.php and
 *  SpecialBlockList.php. You just need to override formatRow(),
 *  getQueryInfo() and getIndexField(). Don't forget to call the parent
 *  constructor if you override it.
 *
 * @ingroup Pager
 */
abstract class IndexPager extends ContextSource implements Pager {
	public $mRequest;
	public $mLimitsShown = array( 20, 50, 100, 250, 500 );
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
	 * false for ascending, true for descending.  If $mIsBackwards is set, we
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
		# Let the subclass set the DB here; otherwise use a slave DB for the current wiki
		$this->mDb = $this->mDb ?: wfGetDB( DB_SLAVE );

		$index = $this->getIndexField(); // column to sort on
		$extraSort = $this->getExtraSortFields(); // extra columns to sort on for query planning
		$order = $this->mRequest->getVal( 'order' );
		if ( is_array( $index ) && isset( $index[$order] ) ) {
			$this->mOrderType = $order;
			$this->mIndexField = $index[$order];
			$this->mExtraSortFields = isset( $extraSort[$order] )
				? (array)$extraSort[$order]
				: array();
		} elseif ( is_array( $index ) ) {
			# First element is the default
			reset( $index );
			list( $this->mOrderType, $this->mIndexField ) = each( $index );
			$this->mExtraSortFields = isset( $extraSort[$this->mOrderType] )
				? (array)$extraSort[$this->mOrderType]
				: array();
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
	 * @return DatabaseBase
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
		$fname = __METHOD__ . ' (' . get_class( $this ) . ')';
		wfProfileIn( $fname );

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

		wfProfileOut( $fname );
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
	 * @param $offset Int|String
	 */
	function setOffset( $offset ) {
		$this->mOffset = $offset;
	}
	/**
	 * Set the limit from an other source than the request
	 *
	 * Verifies limit is between 1 and 5000
	 *
	 * @param $limit Int|String
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
	 * Set whether a row matching exactly the offset should be also included
	 * in the result or not. By default this is not the case, but when the
	 * offset is user-supplied this might be wanted.
	 *
	 * @param $include bool
	 */
	public function setIncludeOffset( $include ) {
		$this->mIncludeOffset = $include;
	}

	/**
	 * Extract some useful data from the result object for use by
	 * the navigation bar, put it into $this
	 *
	 * @param $isFirst bool: False if there are rows before those fetched (i.e.
	 *     if a "previous" link would make sense)
	 * @param $limit Integer: exact query limit
	 * @param $res ResultWrapper
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
	 * @return String
	 */
	function getSqlComment() {
		return get_class( $this );
	}

	/**
	 * Do a query with specified parameters, rather than using the object
	 * context
	 *
	 * @param string $offset index offset, inclusive
	 * @param $limit Integer: exact query limit
	 * @param $descending Boolean: query direction, false for ascending, true for descending
	 * @return ResultWrapper
	 */
	public function reallyDoQuery( $offset, $limit, $descending ) {
		list( $tables, $fields, $conds, $fname, $options, $join_conds ) = $this->buildQueryInfo( $offset, $limit, $descending );
		return $this->mDb->select( $tables, $fields, $conds, $fname, $options, $join_conds );
	}

	/**
	 * Build variables to use by the database wrapper.
	 *
	 * @param string $offset index offset, inclusive
	 * @param $limit Integer: exact query limit
	 * @param $descending Boolean: query direction, false for ascending, true for descending
	 * @return array
	 */
	protected function buildQueryInfo( $offset, $limit, $descending ) {
		$fname = __METHOD__ . ' (' . $this->getSqlComment() . ')';
		$info = $this->getQueryInfo();
		$tables = $info['tables'];
		$fields = $info['fields'];
		$conds = isset( $info['conds'] ) ? $info['conds'] : array();
		$options = isset( $info['options'] ) ? $info['options'] : array();
		$join_conds = isset( $info['join_conds'] ) ? $info['join_conds'] : array();
		$sortColumns = array_merge( array( $this->mIndexField ), $this->mExtraSortFields );
		if ( $descending ) {
			$options['ORDER BY'] = $sortColumns;
			$operator = $this->mIncludeOffset ? '>=' : '>';
		} else {
			$orderBy = array();
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
		return array( $tables, $fields, $conds, $fname, $options, $join_conds );
	}

	/**
	 * Pre-process results; useful for performing batch existence checks, etc.
	 *
	 * @param $result ResultWrapper
	 */
	protected function preprocessResults( $result ) {}

	/**
	 * Get the formatted result list. Calls getStartBody(), formatRow() and
	 * getEndBody(), concatenates the results and returns them.
	 *
	 * @return String
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
	 * @param string $text text displayed on the link
	 * @param array $query associative array of parameter to be in the query string
	 * @param string $type value of the "rel" attribute
	 *
	 * @return String: HTML fragment
	 */
	function makeLink( $text, array $query = null, $type = null ) {
		if ( $query === null ) {
			return $text;
		}

		$attrs = array();
		if ( in_array( $type, array( 'first', 'prev', 'next', 'last' ) ) ) {
			# HTML5 rel attributes
			$attrs['rel'] = $type;
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
	protected function doBatchLookups() {}

	/**
	 * Hook into getBody(), allows text to be inserted at the start. This
	 * will be called even if there are no rows in the result set.
	 *
	 * @return String
	 */
	protected function getStartBody() {
		return '';
	}

	/**
	 * Hook into getBody() for the end of the list
	 *
	 * @return String
	 */
	protected function getEndBody() {
		return '';
	}

	/**
	 * Hook into getBody(), for the bit between the start and the
	 * end when there are no rows
	 *
	 * @return String
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
	 * @return Integer
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
	 * @return Array
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
			$prev = array(
				'dir' => 'prev',
				'offset' => $this->mFirstShown,
				'limit' => $urlLimit
			);
			$first = array( 'limit' => $urlLimit );
		}
		if ( $this->mIsLast ) {
			$next = false;
			$last = false;
		} else {
			$next = array( 'offset' => $this->mLastShown, 'limit' => $urlLimit );
			$last = array( 'dir' => 'prev', 'limit' => $urlLimit );
		}
		return array(
			'prev' => $prev,
			'next' => $next,
			'first' => $first,
			'last' => $last
		);
	}

	/**
	 * Returns whether to show the "navigation bar"
	 *
	 * @return Boolean
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
	 * @param $linkTexts Array
	 * @param $disabledTexts Array
	 * @return Array
	 */
	function getPagingLinks( $linkTexts, $disabledTexts = array() ) {
		$queries = $this->getPagingQueries();
		$links = array();

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
		$links = array();
		if ( $this->mIsBackwards ) {
			$offset = $this->mPastTheEndIndex;
		} else {
			$offset = $this->mOffset;
		}
		foreach ( $this->mLimitsShown as $limit ) {
			$links[] = $this->makeLink(
				$this->getLanguage()->formatNum( $limit ),
				array( 'offset' => $offset, 'limit' => $limit ),
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
	 * @param $row Object: database row
	 * @return String
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
	 * @return Array
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
	 * @return string|Array
	 */
	abstract function getIndexField();

	/**
	 * This function should be overridden to return the names of secondary columns
	 * to order by in addition to the column in getIndexField(). These fields will
	 * not be used in the pager offset or in any links for users.
	 *
	 * If getIndexField() returns an array of 'querykey' => 'indexfield' pairs then
	 * this must return a corresponding array of 'querykey' => array( fields...) pairs
	 * in order for a request with &count=querykey to use array( fields...) to sort.
	 *
	 * This is useful for pagers that GROUP BY a unique column (say page_id)
	 * and ORDER BY another (say page_len). Using GROUP BY and ORDER BY both on
	 * page_len,page_id avoids temp tables (given a page_len index). This would
	 * also work if page_id was non-unique but we had a page_len,page_id index.
	 *
	 * @return Array
	 */
	protected function getExtraSortFields() {
		return array();
	}

	/**
	 * Return the default sorting direction: false for ascending, true for
	 * descending.  You can also have an associative array of ordertype => dir,
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
	 * @return Boolean
	 */
	protected function getDefaultDirections() {
		return false;
	}
}

/**
 * IndexPager with an alphabetic list and a formatted navigation bar
 * @ingroup Pager
 */
abstract class AlphabeticPager extends IndexPager {

	/**
	 * Shamelessly stolen bits from ReverseChronologicalPager,
	 * didn't want to do class magic as may be still revamped
	 *
	 * @return String HTML
	 */
	function getNavigationBar() {
		if ( !$this->isNavigationBarShown() ) {
			return '';
		}

		if ( isset( $this->mNavigationBar ) ) {
			return $this->mNavigationBar;
		}

		$linkTexts = array(
			'prev' => $this->msg( 'prevn' )->numParams( $this->mLimit )->escaped(),
			'next' => $this->msg( 'nextn' )->numParams( $this->mLimit )->escaped(),
			'first' => $this->msg( 'page_first' )->escaped(),
			'last' => $this->msg( 'page_last' )->escaped()
		);

		$lang = $this->getLanguage();

		$pagingLinks = $this->getPagingLinks( $linkTexts );
		$limitLinks = $this->getLimitLinks();
		$limits = $lang->pipeList( $limitLinks );

		$this->mNavigationBar = $this->msg( 'parentheses' )->rawParams(
			$lang->pipeList( array( $pagingLinks['first'],
			$pagingLinks['last'] ) ) )->escaped() . " " .
			$this->msg( 'viewprevnext' )->rawParams( $pagingLinks['prev'],
				$pagingLinks['next'], $limits )->escaped();

		if ( !is_array( $this->getIndexField() ) ) {
			# Early return to avoid undue nesting
			return $this->mNavigationBar;
		}

		$extra = '';
		$first = true;
		$msgs = $this->getOrderTypeMessages();
		foreach ( array_keys( $msgs ) as $order ) {
			if ( $first ) {
				$first = false;
			} else {
				$extra .= $this->msg( 'pipe-separator' )->escaped();
			}

			if ( $order == $this->mOrderType ) {
				$extra .= $this->msg( $msgs[$order] )->escaped();
			} else {
				$extra .= $this->makeLink(
					$this->msg( $msgs[$order] )->escaped(),
					array( 'order' => $order )
				);
			}
		}

		if ( $extra !== '' ) {
			$extra = ' ' . $this->msg( 'parentheses' )->rawParams( $extra )->escaped();
			$this->mNavigationBar .= $extra;
		}

		return $this->mNavigationBar;
	}

	/**
	 * If this supports multiple order type messages, give the message key for
	 * enabling each one in getNavigationBar.  The return type is an associative
	 * array whose keys must exactly match the keys of the array returned
	 * by getIndexField(), and whose values are message keys.
	 *
	 * @return Array
	 */
	protected function getOrderTypeMessages() {
		return null;
	}
}

/**
 * IndexPager with a formatted navigation bar
 * @ingroup Pager
 */
abstract class ReverseChronologicalPager extends IndexPager {
	public $mDefaultDirection = true;
	public $mYear;
	public $mMonth;

	function getNavigationBar() {
		if ( !$this->isNavigationBarShown() ) {
			return '';
		}

		if ( isset( $this->mNavigationBar ) ) {
			return $this->mNavigationBar;
		}

		$linkTexts = array(
			'prev' => $this->msg( 'pager-newer-n' )->numParams( $this->mLimit )->escaped(),
			'next' => $this->msg( 'pager-older-n' )->numParams( $this->mLimit )->escaped(),
			'first' => $this->msg( 'histlast' )->escaped(),
			'last' => $this->msg( 'histfirst' )->escaped()
		);

		$pagingLinks = $this->getPagingLinks( $linkTexts );
		$limitLinks = $this->getLimitLinks();
		$limits = $this->getLanguage()->pipeList( $limitLinks );
		$firstLastLinks = $this->msg( 'parentheses' )->rawParams( "{$pagingLinks['first']}" .
			$this->msg( 'pipe-separator' )->escaped() .
			"{$pagingLinks['last']}" )->escaped();

		$this->mNavigationBar = $firstLastLinks . ' ' .
			$this->msg( 'viewprevnext' )->rawParams(
				$pagingLinks['prev'], $pagingLinks['next'], $limits )->escaped();

		return $this->mNavigationBar;
	}

	function getDateCond( $year, $month ) {
		$year = intval( $year );
		$month = intval( $month );

		// Basic validity checks
		$this->mYear = $year > 0 ? $year : false;
		$this->mMonth = ( $month > 0 && $month < 13 ) ? $month : false;

		// Given an optional year and month, we need to generate a timestamp
		// to use as "WHERE rev_timestamp <= result"
		// Examples: year = 2006 equals < 20070101 (+000000)
		// year=2005, month=1    equals < 20050201
		// year=2005, month=12   equals < 20060101
		if ( !$this->mYear && !$this->mMonth ) {
			return;
		}

		if ( $this->mYear ) {
			$year = $this->mYear;
		} else {
			// If no year given, assume the current one
			$timestamp = MWTimestamp::getInstance();
			$year = $timestamp->format( 'Y' );
			// If this month hasn't happened yet this year, go back to last year's month
			if ( $this->mMonth > $timestamp->format( 'n' ) ) {
				$year--;
			}
		}

		if ( $this->mMonth ) {
			$month = $this->mMonth + 1;
			// For December, we want January 1 of the next year
			if ( $month > 12 ) {
				$month = 1;
				$year++;
			}
		} else {
			// No month implies we want up to the end of the year in question
			$month = 1;
			$year++;
		}

		// Y2K38 bug
		if ( $year > 2032 ) {
			$year = 2032;
		}

		$ymd = (int)sprintf( "%04d%02d01", $year, $month );

		if ( $ymd > 20320101 ) {
			$ymd = 20320101;
		}

		$this->mOffset = $this->mDb->timestamp( "${ymd}000000" );
	}
}

/**
 * Table-based display with a user-selectable sort order
 * @ingroup Pager
 */
abstract class TablePager extends IndexPager {
	var $mSort;
	var $mCurrentRow;

	public function __construct( IContextSource $context = null ) {
		if ( $context ) {
			$this->setContext( $context );
		}

		$this->mSort = $this->getRequest()->getText( 'sort' );
		if ( !array_key_exists( $this->mSort, $this->getFieldNames() )
			|| !$this->isFieldSortable( $this->mSort )
		) {
			$this->mSort = $this->getDefaultSort();
		}
		if ( $this->getRequest()->getBool( 'asc' ) ) {
			$this->mDefaultDirection = false;
		} elseif ( $this->getRequest()->getBool( 'desc' ) ) {
			$this->mDefaultDirection = true;
		} /* Else leave it at whatever the class default is */

		parent::__construct();
	}

	/**
	 * @protected
	 * @return string
	 */
	function getStartBody() {
		global $wgStylePath;
		$sortClass = $this->getSortHeaderClass();

		$s = '';
		$fields = $this->getFieldNames();

		# Make table header
		foreach ( $fields as $field => $name ) {
			if ( strval( $name ) == '' ) {
				$s .= Html::rawElement( 'th', array(), '&#160;' ) . "\n";
			} elseif ( $this->isFieldSortable( $field ) ) {
				$query = array( 'sort' => $field, 'limit' => $this->mLimit );
				if ( $field == $this->mSort ) {
					# This is the sorted column
					# Prepare a link that goes in the other sort order
					if ( $this->mDefaultDirection ) {
						# Descending
						$image = 'Arr_d.png';
						$query['asc'] = '1';
						$query['desc'] = '';
						$alt = $this->msg( 'descending_abbrev' )->escaped();
					} else {
						# Ascending
						$image = 'Arr_u.png';
						$query['asc'] = '';
						$query['desc'] = '1';
						$alt = $this->msg( 'ascending_abbrev' )->escaped();
					}
					$image = "$wgStylePath/common/images/$image";
					$link = $this->makeLink(
						Html::element( 'img', array( 'width' => 12, 'height' => 12,
							'alt' => $alt, 'src' => $image ) ) . htmlspecialchars( $name ), $query );
					$s .= Html::rawElement( 'th', array( 'class' => $sortClass ), $link ) . "\n";
				} else {
					$s .= Html::rawElement( 'th', array(),
						$this->makeLink( htmlspecialchars( $name ), $query ) ) . "\n";
				}
			} else {
				$s .= Html::element( 'th', array(), $name ) . "\n";
			}
		}

		$tableClass = $this->getTableClass();
		$ret = Html::openElement( 'table', array( 'style' => 'border:1px;', 'class' => "mw-datatable $tableClass" ) );
		$ret .= Html::rawElement( 'thead', array(), Html::rawElement( 'tr', array(), "\n" . $s . "\n" ) );
		$ret .= Html::openElement( 'tbody' ) . "\n";

		return $ret;
	}

	/**
	 * @protected
	 * @return string
	 */
	function getEndBody() {
		return "</tbody></table>\n";
	}

	/**
	 * @protected
	 * @return string
	 */
	function getEmptyBody() {
		$colspan = count( $this->getFieldNames() );
		$msgEmpty = $this->msg( 'table_pager_empty' )->text();
		return Html::rawElement( 'tr', array(),
			Html::element( 'td', array( 'colspan' => $colspan ), $msgEmpty ) );
	}

	/**
	 * @protected
	 * @param stdClass $row
	 * @return String HTML
	 */
	function formatRow( $row ) {
		$this->mCurrentRow = $row; // In case formatValue etc need to know
		$s = Html::openElement( 'tr', $this->getRowAttrs( $row ) ) . "\n";
		$fieldNames = $this->getFieldNames();

		foreach ( $fieldNames as $field => $name ) {
			$value = isset( $row->$field ) ? $row->$field : null;
			$formatted = strval( $this->formatValue( $field, $value ) );

			if ( $formatted == '' ) {
				$formatted = '&#160;';
			}

			$s .= Html::rawElement( 'td', $this->getCellAttrs( $field, $value ), $formatted ) . "\n";
		}

		$s .= Html::closeElement( 'tr' ) . "\n";

		return $s;
	}

	/**
	 * Get a class name to be applied to the given row.
	 *
	 * @protected
	 *
	 * @param $row Object: the database result row
	 * @return String
	 */
	function getRowClass( $row ) {
		return '';
	}

	/**
	 * Get attributes to be applied to the given row.
	 *
	 * @protected
	 *
	 * @param $row Object: the database result row
	 * @return Array of attribute => value
	 */
	function getRowAttrs( $row ) {
		$class = $this->getRowClass( $row );
		if ( $class === '' ) {
			// Return an empty array to avoid clutter in HTML like class=""
			return array();
		} else {
			return array( 'class' => $this->getRowClass( $row ) );
		}
	}

	/**
	 * Get any extra attributes to be applied to the given cell. Don't
	 * take this as an excuse to hardcode styles; use classes and
	 * CSS instead.  Row context is available in $this->mCurrentRow
	 *
	 * @protected
	 *
	 * @param string $field The column
	 * @param string $value The cell contents
	 * @return Array of attr => value
	 */
	function getCellAttrs( $field, $value ) {
		return array( 'class' => 'TablePager_col_' . $field );
	}

	/**
	 * @protected
	 * @return string
	 */
	function getIndexField() {
		return $this->mSort;
	}

	/**
	 * @protected
	 * @return string
	 */
	function getTableClass() {
		return 'TablePager';
	}

	/**
	 * @protected
	 * @return string
	 */
	function getNavClass() {
		return 'TablePager_nav';
	}

	/**
	 * @protected
	 * @return string
	 */
	function getSortHeaderClass() {
		return 'TablePager_sort';
	}

	/**
	 * A navigation bar with images
	 * @return String HTML
	 */
	public function getNavigationBar() {
		global $wgStylePath;

		if ( !$this->isNavigationBarShown() ) {
			return '';
		}

		$path = "$wgStylePath/common/images";
		$labels = array(
			'first' => 'table_pager_first',
			'prev' => 'table_pager_prev',
			'next' => 'table_pager_next',
			'last' => 'table_pager_last',
		);
		$images = array(
			'first' => 'arrow_first_25.png',
			'prev' => 'arrow_left_25.png',
			'next' => 'arrow_right_25.png',
			'last' => 'arrow_last_25.png',
		);
		$disabledImages = array(
			'first' => 'arrow_disabled_first_25.png',
			'prev' => 'arrow_disabled_left_25.png',
			'next' => 'arrow_disabled_right_25.png',
			'last' => 'arrow_disabled_last_25.png',
		);
		if ( $this->getLanguage()->isRTL() ) {
			$keys = array_keys( $labels );
			$images = array_combine( $keys, array_reverse( $images ) );
			$disabledImages = array_combine( $keys, array_reverse( $disabledImages ) );
		}

		$linkTexts = array();
		$disabledTexts = array();
		foreach ( $labels as $type => $label ) {
			$msgLabel = $this->msg( $label )->escaped();
			$linkTexts[$type] = Html::element( 'img', array( 'src' => "$path/{$images[$type]}",
				'alt' => $msgLabel ) ) . "<br />$msgLabel";
			$disabledTexts[$type] = Html::element( 'img', array( 'src' => "$path/{$disabledImages[$type]}",
				'alt' => $msgLabel ) ) . "<br />$msgLabel";
		}
		$links = $this->getPagingLinks( $linkTexts, $disabledTexts );

		$s = Html::openElement( 'table', array( 'class' => $this->getNavClass() ) );
		$s .= Html::openElement( 'tr' ) . "\n";
		$width = 100 / count( $links ) . '%';
		foreach ( $labels as $type => $label ) {
			$s .= Html::rawElement( 'td', array( 'style' => "width:$width;" ), $links[$type] ) . "\n";
		}
		$s .= Html::closeElement( 'tr' ) . Html::closeElement( 'table' ) . "\n";
		return $s;
	}

	/**
	 * Get a "<select>" element which has options for each of the allowed limits
	 *
	 * @param $attribs String: Extra attributes to set
	 * @return String: HTML fragment
	 */
	public function getLimitSelect( $attribs = array() ) {
		$select = new XmlSelect( 'limit', false, $this->mLimit );
		$select->addOptions( $this->getLimitSelectList() );
		foreach ( $attribs as $name => $value ) {
			$select->setAttribute( $name, $value );
		}
		return $select->getHTML();
	}

	/**
	 * Get a list of items to show in a "<select>" element of limits.
	 * This can be passed directly to XmlSelect::addOptions().
	 *
	 * @since 1.22
	 * @return array
	 */
	public function getLimitSelectList() {
		# Add the current limit from the query string
		# to avoid that the limit is lost after clicking Go next time
		if ( !in_array( $this->mLimit, $this->mLimitsShown ) ) {
			$this->mLimitsShown[] = $this->mLimit;
			sort( $this->mLimitsShown );
		}
		$ret = array();
		foreach ( $this->mLimitsShown as $key => $value ) {
			# The pair is either $index => $limit, in which case the $value
			# will be numeric, or $limit => $text, in which case the $value
			# will be a string.
			if ( is_int( $value ) ) {
				$limit = $value;
				$text = $this->getLanguage()->formatNum( $limit );
			} else {
				$limit = $key;
				$text = $value;
			}
			$ret[$text] = $limit;
		}
		return $ret;
	}

	/**
	 * Get \<input type="hidden"\> elements for use in a method="get" form.
	 * Resubmits all defined elements of the query string, except for a
	 * blacklist, passed in the $blacklist parameter.
	 *
	 * @param array $blacklist parameters from the request query which should not be resubmitted
	 * @return String: HTML fragment
	 */
	function getHiddenFields( $blacklist = array() ) {
		$blacklist = (array)$blacklist;
		$query = $this->getRequest()->getQueryValues();
		foreach ( $blacklist as $name ) {
			unset( $query[$name] );
		}
		$s = '';
		foreach ( $query as $name => $value ) {
			$s .= Html::hidden( $name, $value ) . "\n";
		}
		return $s;
	}

	/**
	 * Get a form containing a limit selection dropdown
	 *
	 * @return String: HTML fragment
	 */
	function getLimitForm() {
		global $wgScript;

		return Html::rawElement(
			'form',
			array(
				'method' => 'get',
				'action' => $wgScript
			),
			"\n" . $this->getLimitDropdown()
		) . "\n";
	}

	/**
	 * Gets a limit selection dropdown
	 *
	 * @return string
	 */
	function getLimitDropdown() {
		# Make the select with some explanatory text
		$msgSubmit = $this->msg( 'table_pager_limit_submit' )->escaped();

		return $this->msg( 'table_pager_limit' )
			->rawParams( $this->getLimitSelect() )->escaped() .
			"\n<input type=\"submit\" value=\"$msgSubmit\"/>\n" .
			$this->getHiddenFields( array( 'limit' ) );
	}

	/**
	 * Return true if the named field should be sortable by the UI, false
	 * otherwise
	 *
	 * @param $field String
	 */
	abstract function isFieldSortable( $field );

	/**
	 * Format a table cell. The return value should be HTML, but use an empty
	 * string not &#160; for empty cells. Do not include the <td> and </td>.
	 *
	 * The current result row is available as $this->mCurrentRow, in case you
	 * need more context.
	 *
	 * @protected
	 *
	 * @param string $name the database field name
	 * @param string $value the value retrieved from the database
	 */
	abstract function formatValue( $name, $value );

	/**
	 * The database field name used as a default sort order.
	 *
	 * @protected
	 *
	 * @return string
	 */
	abstract function getDefaultSort();

	/**
	 * An array mapping database field names to a textual description of the
	 * field name, for use in the table header. The description should be plain
	 * text, it will be HTML-escaped later.
	 *
	 * @return Array
	 */
	abstract function getFieldNames();
}
