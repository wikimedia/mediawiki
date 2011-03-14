<?php
/**
 * @defgroup Pager Pager
 *
 * @file
 * @ingroup Pager
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
abstract class IndexPager implements Pager {
	public $mRequest;
	public $mLimitsShown = array( 20, 50, 100, 250, 500 );
	public $mDefaultLimit = 50;
	public $mOffset, $mLimit;
	public $mQueryDone = false;
	public $mDb;
	public $mPastTheEndRow;

	/**
	 * The index to actually be used for ordering.  This is a single string
	 * even if multiple orderings are supported.
	 */
	protected $mIndexField;
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

	/**
	 * Result object for the query. Warning: seek before use.
	 */
	public $mResult;

	public function __construct() {
		global $wgRequest, $wgUser;
		$this->mRequest = $wgRequest;

		# NB: the offset is quoted, not validated. It is treated as an
		# arbitrary string to support the widest variety of index types. Be
		# careful outputting it into HTML!
		$this->mOffset = $this->mRequest->getText( 'offset' );

		# Use consistent behavior for the limit options
		$this->mDefaultLimit = intval( $wgUser->getOption( 'rclimit' ) );
		list( $this->mLimit, /* $offset */ ) = $this->mRequest->getLimitOffset();

		$this->mIsBackwards = ( $this->mRequest->getVal( 'dir' ) == 'prev' );
		$this->mDb = wfGetDB( DB_SLAVE );

		$index = $this->getIndexField();
		$order = $this->mRequest->getVal( 'order' );
		if( is_array( $index ) && isset( $index[$order] ) ) {
			$this->mOrderType = $order;
			$this->mIndexField = $index[$order];
		} elseif( is_array( $index ) ) {
			# First element is the default
			reset( $index );
			list( $this->mOrderType, $this->mIndexField ) = each( $index );
		} else {
			# $index is not an array
			$this->mOrderType = null;
			$this->mIndexField = $index;
		}

		if( !isset( $this->mDefaultDirection ) ) {
			$dir = $this->getDefaultDirections();
			$this->mDefaultDirection = is_array( $dir )
				? $dir[$this->mOrderType]
				: $dir;
		}
	}

	/**
	 * Do the query, using information from the object context. This function
	 * has been kept minimal to make it overridable if necessary, to allow for
	 * result sets formed from multiple DB queries.
	 */
	function doQuery() {
		# Use the child class name for profiling
		$fname = __METHOD__ . ' (' . get_class( $this ) . ')';
		wfProfileIn( $fname );

		$descending = ( $this->mIsBackwards == $this->mDefaultDirection );
		# Plus an extra row so that we can tell the "next" link should be shown
		$queryLimit = $this->mLimit + 1;

		$this->mResult = $this->reallyDoQuery(
			$this->mOffset,
			$queryLimit,
			$descending
		);
		$this->extractResultInfo( $this->mOffset, $queryLimit, $this->mResult );
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
	 * Set the offset from an other source than $wgRequest
	 */
	function setOffset( $offset ) {
		$this->mOffset = $offset;
	}
	/**
	 * Set the limit from an other source than $wgRequest
	 */
	function setLimit( $limit ) {
		$this->mLimit = $limit;
	}

	/**
	 * Extract some useful data from the result object for use by
	 * the navigation bar, put it into $this
	 *
	 * @param $offset String: index offset, inclusive
	 * @param $limit Integer: exact query limit
	 * @param $res ResultWrapper
	 */
	function extractResultInfo( $offset, $limit, ResultWrapper $res ) {
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
				$indexField = $this->mIndexField;
				$this->mPastTheEndIndex = $this->mPastTheEndRow->$indexField;
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
			$this->mIsLast = ( $offset == '' );
			$this->mLastShown = $firstIndex;
			$this->mFirstShown = $lastIndex;
		} else {
			$this->mIsFirst = ( $offset == '' );
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
	 * @param $offset String: index offset, inclusive
	 * @param $limit Integer: exact query limit
	 * @param $descending Boolean: query direction, false for ascending, true for descending
	 * @return ResultWrapper
	 */
	function reallyDoQuery( $offset, $limit, $descending ) {
		$fname = __METHOD__ . ' (' . $this->getSqlComment() . ')';
		$info = $this->getQueryInfo();
		$tables = $info['tables'];
		$fields = $info['fields'];
		$conds = isset( $info['conds'] ) ? $info['conds'] : array();
		$options = isset( $info['options'] ) ? $info['options'] : array();
		$join_conds = isset( $info['join_conds'] ) ? $info['join_conds'] : array();
		if ( $descending ) {
			$options['ORDER BY'] = $this->mIndexField;
			$operator = '>';
		} else {
			$options['ORDER BY'] = $this->mIndexField . ' DESC';
			$operator = '<';
		}
		if ( $offset != '' ) {
			$conds[] = $this->mIndexField . $operator . $this->mDb->addQuotes( $offset );
		}
		$options['LIMIT'] = intval( $limit );
		$res = $this->mDb->select( $tables, $fields, $conds, $fname, $options, $join_conds );
		return new ResultWrapper( $this->mDb, $res );
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
	function getBody() {
		if ( !$this->mQueryDone ) {
			$this->doQuery();
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
	 * @param $text String: text displayed on the link
	 * @param $query Array: associative array of paramter to be in the query string
	 * @param $type String: value of the "rel" attribute
	 * @return String: HTML fragment
	 */
	function makeLink($text, $query = null, $type=null) {
		if ( $query === null ) {
			return $text;
		}

		$attrs = array();
		if( in_array( $type, array( 'first', 'prev', 'next', 'last' ) ) ) {
			# HTML5 rel attributes
			$attrs['rel'] = $type;
		}

		if( $type ) {
			$attrs['class'] = "mw-{$type}link";
		}
		return $this->getSkin()->link(
			$this->getTitle(),
			$text,
			$attrs,
			$query + $this->getDefaultQuery(),
			array( 'noclasses', 'known' )
		);
	}

	/**
	 * Hook into getBody(), allows text to be inserted at the start. This
	 * will be called even if there are no rows in the result set.
	 *
	 * @return String
	 */
	function getStartBody() {
		return '';
	}

	/**
	 * Hook into getBody() for the end of the list
	 *
	 * @return String
	 */
	function getEndBody() {
		return '';
	}

	/**
	 * Hook into getBody(), for the bit between the start and the
	 * end when there are no rows
	 *
	 * @return String
	 */
	function getEmptyBody() {
		return '';
	}

	/**
	 * Title used for self-links. Override this if you want to be able to
	 * use a title other than $wgTitle
	 *
	 * @return Title object
	 */
	function getTitle() {
		return $GLOBALS['wgTitle'];
	}

	/**
	 * Get the current skin. This can be overridden if necessary.
	 *
	 * @return Skin object
	 */
	function getSkin() {
		if ( !isset( $this->mSkin ) ) {
			global $wgUser;
			$this->mSkin = $wgUser->getSkin();
		}
		return $this->mSkin;
	}

	/**
	 * Get an array of query parameters that should be put into self-links.
	 * By default, all parameters passed in the URL are used, except for a
	 * short blacklist.
	 *
	 * @return Associative array
	 */
	function getDefaultQuery() {
		if ( !isset( $this->mDefaultQuery ) ) {
			$this->mDefaultQuery = $_GET;
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
		$urlLimit = $this->mLimit == $this->mDefaultLimit ? '' : $this->mLimit;

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
		return !($this->mIsFirst && $this->mIsLast);
	}

	/**
	 * Get paging links. If a link is disabled, the item from $disabledTexts
	 * will be used. If there is no such item, the unlinked text from
	 * $linkTexts will be used. Both $linkTexts and $disabledTexts are arrays
	 * of HTML.
	 *
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
		global $wgLang;
		$links = array();
		if ( $this->mIsBackwards ) {
			$offset = $this->mPastTheEndIndex;
		} else {
			$offset = $this->mOffset;
		}
		foreach ( $this->mLimitsShown as $limit ) {
			$links[] = $this->makeLink(
				$wgLang->formatNum( $limit ),
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
	 */
	abstract function getIndexField();

	/**
	 * Return the default sorting direction: false for ascending, true for de-
	 * scending.  You can also have an associative array of ordertype => dir,
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
	protected function getDefaultDirections() { return false; }
}


/**
 * IndexPager with an alphabetic list and a formatted navigation bar
 * @ingroup Pager
 */
abstract class AlphabeticPager extends IndexPager {
	/**
	 * Shamelessly stolen bits from ReverseChronologicalPager,
	 * didn't want to do class magic as may be still revamped
	 */
	function getNavigationBar() {
		global $wgLang;

		if ( !$this->isNavigationBarShown() ) return '';

		if( isset( $this->mNavigationBar ) ) {
			return $this->mNavigationBar;
		}

		$opts = array( 'parsemag', 'escapenoentities' );
		$linkTexts = array(
			'prev' => wfMsgExt(
				'prevn',
				$opts,
				$wgLang->formatNum( $this->mLimit )
			),
			'next' => wfMsgExt(
				'nextn',
				$opts,
				$wgLang->formatNum($this->mLimit )
			),
			'first' => wfMsgExt( 'page_first', $opts ),
			'last' => wfMsgExt( 'page_last', $opts )
		);

		$pagingLinks = $this->getPagingLinks( $linkTexts );
		$limitLinks = $this->getLimitLinks();
		$limits = $wgLang->pipeList( $limitLinks );

		$this->mNavigationBar =
			"(" . $wgLang->pipeList(
				array( $pagingLinks['first'],
				$pagingLinks['last'] )
			) . ") " .
			wfMsgHtml( 'viewprevnext', $pagingLinks['prev'],
			$pagingLinks['next'], $limits );

		if( !is_array( $this->getIndexField() ) ) {
			# Early return to avoid undue nesting
			return $this->mNavigationBar;
		}

		$extra = '';
		$first = true;
		$msgs = $this->getOrderTypeMessages();
		foreach( array_keys( $msgs ) as $order ) {
			if( $first ) {
				$first = false;
			} else {
				$extra .= wfMsgExt( 'pipe-separator' , 'escapenoentities' );
			}

			if( $order == $this->mOrderType ) {
				$extra .= wfMsgHTML( $msgs[$order] );
			} else {
				$extra .= $this->makeLink(
					wfMsgHTML( $msgs[$order] ),
					array( 'order' => $order )
				);
			}
		}

		if( $extra !== '' ) {
			$this->mNavigationBar .= " ($extra)";
		}

		return $this->mNavigationBar;
	}

	/**
	 * If this supports multiple order type messages, give the message key for
	 * enabling each one in getNavigationBar.  The return type is an associa-
	 * tive array whose keys must exactly match the keys of the array returned
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

	function __construct() {
		parent::__construct();
	}

	function getNavigationBar() {
		global $wgLang;

		if ( !$this->isNavigationBarShown() ) return '';

		if ( isset( $this->mNavigationBar ) ) {
			return $this->mNavigationBar;
		}
		$nicenumber = $wgLang->formatNum( $this->mLimit );
		$linkTexts = array(
			'prev' => wfMsgExt(
				'pager-newer-n',
				array( 'parsemag', 'escape' ),
				$nicenumber
			),
			'next' => wfMsgExt(
				'pager-older-n',
				array( 'parsemag', 'escape' ),
				$nicenumber
			),
			'first' => wfMsgHtml( 'histlast' ),
			'last' => wfMsgHtml( 'histfirst' )
		);

		$pagingLinks = $this->getPagingLinks( $linkTexts );
		$limitLinks = $this->getLimitLinks();
		$limits = $wgLang->pipeList( $limitLinks );

		$this->mNavigationBar = "({$pagingLinks['first']}" .
			wfMsgExt( 'pipe-separator' , 'escapenoentities' ) .
			"{$pagingLinks['last']}) " .
			wfMsgHTML(
				'viewprevnext',
				$pagingLinks['prev'], $pagingLinks['next'],
				$limits
			);
		return $this->mNavigationBar;
	}

	function getDateCond( $year, $month ) {
		$year = intval($year);
		$month = intval($month);
		// Basic validity checks
		$this->mYear = $year > 0 ? $year : false;
		$this->mMonth = ($month > 0 && $month < 13) ? $month : false;
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
			$year = gmdate( 'Y' );
			// If this month hasn't happened yet this year, go back to last year's month
			if( $this->mMonth > gmdate( 'n' ) ) {
				$year--;
			}
		}
		if ( $this->mMonth ) {
			$month = $this->mMonth + 1;
			// For December, we want January 1 of the next year
			if ($month > 12) {
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

	function __construct() {
		global $wgRequest;
		$this->mSort = $wgRequest->getText( 'sort' );
		if ( !array_key_exists( $this->mSort, $this->getFieldNames() ) ) {
			$this->mSort = $this->getDefaultSort();
		}
		if ( $wgRequest->getBool( 'asc' ) ) {
			$this->mDefaultDirection = false;
		} elseif ( $wgRequest->getBool( 'desc' ) ) {
			$this->mDefaultDirection = true;
		} /* Else leave it at whatever the class default is */

		parent::__construct();
	}

	function getStartBody() {
		global $wgStylePath;
		$tableClass = htmlspecialchars( $this->getTableClass() );
		$sortClass = htmlspecialchars( $this->getSortHeaderClass() );

		$s = "<table border='1' class=\"$tableClass\"><thead><tr>\n";
		$fields = $this->getFieldNames();

		# Make table header
		foreach ( $fields as $field => $name ) {
			if ( strval( $name ) == '' ) {
				$s .= "<th>&#160;</th>\n";
			} elseif ( $this->isFieldSortable( $field ) ) {
				$query = array( 'sort' => $field, 'limit' => $this->mLimit );
				if ( $field == $this->mSort ) {
					# This is the sorted column
					# Prepare a link that goes in the other sort order
					if ( $this->mDefaultDirection ) {
						# Descending
						$image = 'Arr_u.png';
						$query['asc'] = '1';
						$query['desc'] = '';
						$alt = htmlspecialchars( wfMsg( 'descending_abbrev' ) );
					} else {
						# Ascending
						$image = 'Arr_d.png';
						$query['asc'] = '';
						$query['desc'] = '1';
						$alt = htmlspecialchars( wfMsg( 'ascending_abbrev' ) );
					}
					$image = htmlspecialchars( "$wgStylePath/common/images/$image" );
					$link = $this->makeLink(
						"<img width=\"12\" height=\"12\" alt=\"$alt\" src=\"$image\" />" .
						htmlspecialchars( $name ), $query );
					$s .= "<th class=\"$sortClass\">$link</th>\n";
				} else {
					$s .= '<th>' . $this->makeLink( htmlspecialchars( $name ), $query ) . "</th>\n";
				}
			} else {
				$s .= '<th>' . htmlspecialchars( $name ) . "</th>\n";
			}
		}
		$s .= "</tr></thead><tbody>\n";
		return $s;
	}

	function getEndBody() {
		return "</tbody></table>\n";
	}

	function getEmptyBody() {
		$colspan = count( $this->getFieldNames() );
		$msgEmpty = wfMsgHtml( 'table_pager_empty' );
		return "<tr><td colspan=\"$colspan\">$msgEmpty</td></tr>\n";
	}

	function formatRow( $row ) {
		$this->mCurrentRow = $row;  	# In case formatValue etc need to know
		$s = Xml::openElement( 'tr', $this->getRowAttrs($row) );
		$fieldNames = $this->getFieldNames();
		foreach ( $fieldNames as $field => $name ) {
			$value = isset( $row->$field ) ? $row->$field : null;
			$formatted = strval( $this->formatValue( $field, $value ) );
			if ( $formatted == '' ) {
				$formatted = '&#160;';
			}
			$s .= Xml::tags( 'td', $this->getCellAttrs( $field, $value ), $formatted );
		}
		$s .= "</tr>\n";
		return $s;
	}

	/**
	 * Get a class name to be applied to the given row.
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
	 * @param $row Object: the database result row
	 * @return Associative array
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
	 * @param $field The column
	 * @param $value The cell contents
	 * @return Associative array
	 */
	function getCellAttrs( $field, $value ) {
		return array( 'class' => 'TablePager_col_' . $field );
	}

	function getIndexField() {
		return $this->mSort;
	}

	function getTableClass() {
		return 'TablePager';
	}

	function getNavClass() {
		return 'TablePager_nav';
	}

	function getSortHeaderClass() {
		return 'TablePager_sort';
	}

	/**
	 * A navigation bar with images
	 */
	function getNavigationBar() {
		global $wgStylePath, $wgContLang;

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
		if( $wgContLang->isRTL() ) {
			$keys = array_keys( $labels );
			$images = array_combine( $keys, array_reverse( $images ) );
			$disabledImages = array_combine( $keys, array_reverse( $disabledImages ) );
		}

		$linkTexts = array();
		$disabledTexts = array();
		foreach ( $labels as $type => $label ) {
			$msgLabel = wfMsgHtml( $label );
			$linkTexts[$type] = "<img src=\"$path/{$images[$type]}\" alt=\"$msgLabel\"/><br />$msgLabel";
			$disabledTexts[$type] = "<img src=\"$path/{$disabledImages[$type]}\" alt=\"$msgLabel\"/><br />$msgLabel";
		}
		$links = $this->getPagingLinks( $linkTexts, $disabledTexts );

		$navClass = htmlspecialchars( $this->getNavClass() );
		$s = "<table class=\"$navClass\" align=\"center\" cellpadding=\"3\"><tr>\n";
		$cellAttrs = 'valign="top" align="center" width="' . 100 / count( $links ) . '%"';
		foreach ( $labels as $type => $label ) {
			$s .= "<td $cellAttrs>{$links[$type]}</td>\n";
		}
		$s .= "</tr></table>\n";
		return $s;
	}

	/**
	 * Get a <select> element which has options for each of the allowed limits
	 *
	 * @return String: HTML fragment
	 */
	function getLimitSelect() {
		global $wgLang;
		
		# Add the current limit from the query string
		# to avoid that the limit is lost after clicking Go next time
		if ( !in_array( $this->mLimit, $this->mLimitsShown ) ) {
			$this->mLimitsShown[] = $this->mLimit;
			sort( $this->mLimitsShown );
		}
		$s = Html::openElement( 'select', array( 'name' => 'limit' ) ) . "\n";
		foreach ( $this->mLimitsShown as $key => $value ) {
			# The pair is either $index => $limit, in which case the $value
			# will be numeric, or $limit => $text, in which case the $value
			# will be a string.
			if( is_int( $value ) ){
				$limit = $value;
				$text = $wgLang->formatNum( $limit );
			} else {
				$limit = $key;
				$text = $value;
			}
			$s .= Xml::option( $text, $limit, $limit == $this->mLimit ) . "\n";
		}
		$s .= Html::closeElement( 'select' );
		return $s;
	}

	/**
	 * Get <input type="hidden"> elements for use in a method="get" form.
	 * Resubmits all defined elements of the $_GET array, except for a
	 * blacklist, passed in the $blacklist parameter.
	 *
	 * @return String: HTML fragment
	 */
	function getHiddenFields( $blacklist = array() ) {
		$blacklist = (array)$blacklist;
		$query = $_GET;
		foreach ( $blacklist as $name ) {
			unset( $query[$name] );
		}
		$s = '';
		foreach ( $query as $name => $value ) {
			$encName = htmlspecialchars( $name );
			$encValue = htmlspecialchars( $value );
			$s .= "<input type=\"hidden\" name=\"$encName\" value=\"$encValue\"/>\n";
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

		return Xml::openElement(
			'form',
			array(
				'method' => 'get',
				'action' => $wgScript
			) ) . "\n" . $this->getLimitDropdown() . "</form>\n";
	}

	/**
	 * Gets a limit selection dropdown
	 *
	 * @return string
	 */
	function getLimitDropdown() {
		# Make the select with some explanatory text
		$msgSubmit = wfMsgHtml( 'table_pager_limit_submit' );

		return wfMsgHtml( 'table_pager_limit', $this->getLimitSelect() ) .
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
	 * @param $name String: the database field name
	 * @param $value String: the value retrieved from the database
	 */
	abstract function formatValue( $name, $value );

	/**
	 * The database field name used as a default sort order
	 */
	abstract function getDefaultSort();

	/**
	 * An array mapping database field names to a textual description of the
	 * field name, for use in the table header. The description should be plain
	 * text, it will be HTML-escaped later.
	 */
	abstract function getFieldNames();
}
