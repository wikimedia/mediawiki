<?php

/**
 * Basic pager interface.
 */
interface Pager {
	function getNavigationBar();
	function getBody();
}

/**
 * IndexPager is an efficient pager which uses a (roughly unique) index in the 
 * data set to implement paging, rather than a "LIMIT offset,limit" clause. 
 * In MySQL, such a limit/offset clause requires counting through the specified number
 * of offset rows to find the desired data, which can be expensive for large offsets.
 * 
 * ReverseChronologicalPager is a child class of the abstract IndexPager, and contains 
 * some formatting and display code which is specific to the use of timestamps as 
 * indexes. Here is a synopsis of its operation:
 * 
 *    * The query is specified by the offset, limit and direction (dir) parameters, in 
 *      addition to any subclass-specific parameters. 
 *
 *    * The offset is the non-inclusive start of the DB query. A row with an index value 
 *      equal to the offset will never be shown.
 *
 *    * The query may either be done backwards, where the rows are returned by the database
 *      in the opposite order to which they are displayed to the user, or forwards. This is
 *      specified by the "dir" parameter, dir=prev means backwards, anything else means 
 *      forwards. The offset value specifies the start of the database result set, which 
 *      may be either the start or end of the displayed data set. This allows "previous" 
 *      links to be implemented without knowledge of the index value at the start of the 
 *      previous page. 
 *
 *    * An additional row beyond the user-specified limit is always requested. This allows
 *      us to tell whether we should display a "next" link in the case of forwards mode,
 *      or a "previous" link in the case of backwards mode. Determining whether to 
 *      display the other link (the one for the page before the start of the database
 *      result set) can be done heuristically by examining the offset. 
 *
 *    * An empty offset indicates that the offset condition should be omitted from the query.
 *      This naturally produces either the first page or the last page depending on the 
 *      dir parameter. 
 *
 *  Subclassing the pager to implement concrete functionality should be fairly simple, 
 *  please see the examples in PageHistory.php and SpecialIpblocklist.php. You just need 
 *  to override formatRow(), getQueryInfo() and getIndexField(). Don't forget to call the 
 *  parent constructor if you override it.
 */
abstract class IndexPager implements Pager {
	public $mRequest;
	public $mLimitsShown = array( 20, 50, 100, 250, 500 );
	public $mDefaultLimit = 50;
	public $mOffset, $mLimit;
	public $mQueryDone = false;
	public $mDb;
	public $mPastTheEndRow;

	protected $mIndexField;

	/**
	 * Default query direction. false for ascending, true for descending
	 */
	public $mDefaultDirection = false;

	/**
	 * Result object for the query. Warning: seek before use.
	 */
	public $mResult;

	function __construct() {
		global $wgRequest;
		$this->mRequest = $wgRequest;

		# NB: the offset is quoted, not validated. It is treated as an arbitrary string
		# to support the widest variety of index types. Be careful outputting it into 
		# HTML!
		$this->mOffset = $this->mRequest->getText( 'offset' );
		$this->mLimit = $this->mRequest->getInt( 'limit', $this->mDefaultLimit );
		if ( $this->mLimit <= 0 ) {
			$this->mLimit = $this->mDefaultLimit;
		}
		$this->mIsBackwards = ( $this->mRequest->getVal( 'dir' ) == 'prev' );
		$this->mIndexField = $this->getIndexField();
		$this->mDb = wfGetDB( DB_SLAVE );
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

		$this->mResult = $this->reallyDoQuery( $this->mOffset, $queryLimit, $descending );
		$this->extractResultInfo( $this->mOffset, $queryLimit, $this->mResult );
		$this->mQueryDone = true;

		wfProfileOut( $fname );
	}

	/**
	 * Extract some useful data from the result object for use by 
	 * the navigation bar, put it into $this
	 */
	function extractResultInfo( $offset, $limit, ResultWrapper $res ) {
		$numRows = $res->numRows();
		if ( $numRows ) {
			$row = $res->fetchRow();
			$firstIndex = $row[$this->mIndexField];

			# Discard the extra result row if there is one
			if ( $numRows > $this->mLimit && $numRows > 1 ) {
				$res->seek( $numRows - 1 );
				$this->mPastTheEndRow = $res->fetchObject();
				$indexField = $this->mIndexField;
				$this->mPastTheEndIndex = $this->mPastTheEndRow->$indexField;
				$res->seek( $numRows - 2 );
				$row = $res->fetchRow();
				$lastIndex = $row[$this->mIndexField];
			} else {
				$this->mPastTheEndRow = null;
				# Setting indexes to an empty string means that they will be omitted
				# if they would otherwise appear in URLs. It just so happens that this 
				# is the right thing to do in the standard UI, in all the relevant cases.
				$this->mPastTheEndIndex = '';
				$res->seek( $numRows - 1 );
				$row = $res->fetchRow();
				$lastIndex = $row[$this->mIndexField];
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
	 * Do a query with specified parameters, rather than using the object context
	 *
	 * @param string $offset Index offset, inclusive
	 * @param integer $limit Exact query limit
	 * @param boolean $descending Query direction, false for ascending, true for descending
	 * @return ResultWrapper
	 */
	function reallyDoQuery( $offset, $limit, $ascending ) {
		$fname = __METHOD__ . ' (' . get_class( $this ) . ')';
		$info = $this->getQueryInfo();
		$tables = $info['tables'];
		$fields = $info['fields'];
		$conds = isset( $info['conds'] ) ? $info['conds'] : array();
		$options = isset( $info['options'] ) ? $info['options'] : array();
		if ( $ascending ) {
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
		$res = $this->mDb->select( $tables, $fields, $conds, $fname, $options );
		return new ResultWrapper( $this->mDb, $res );
	}

	/**
	 * Get the formatted result list. Calls getStartBody(), formatRow() and 
	 * getEndBody(), concatenates the results and returns them.
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
	 */
	function makeLink($text, $query = NULL) {
		if ( $query === null ) {
			return $text;
		} else {
			return $this->getSkin()->makeKnownLinkObj( $this->getTitle(), $text,
				wfArrayToCGI( $query, $this->getDefaultQuery() ) );
		}
	}

	/**
	 * Hook into getBody(), allows text to be inserted at the start. This 
	 * will be called even if there are no rows in the result set.
	 */
	function getStartBody() {
		return '';
	}

	/**
	 * Hook into getBody() for the end of the list
	 */
	function getEndBody() {
		return '';
	}

	/**
	 * Hook into getBody(), for the bit between the start and the 
	 * end when there are no rows
	 */
	function getEmptyBody() {
		return '';
	}
	
	/**
	 * Title used for self-links. Override this if you want to be able to 
	 * use a title other than $wgTitle
	 */
	function getTitle() {
		return $GLOBALS['wgTitle'];
	}

	/**
	 * Get the current skin. This can be overridden if necessary.
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
	 */
	function getDefaultQuery() {
		if ( !isset( $this->mDefaultQuery ) ) {
			$this->mDefaultQuery = $_GET;
			unset( $this->mDefaultQuery['title'] );
			unset( $this->mDefaultQuery['dir'] );
			unset( $this->mDefaultQuery['offset'] );
			unset( $this->mDefaultQuery['limit'] );
		}
		return $this->mDefaultQuery;
	}

	/**
	 * Get the number of rows in the result set
	 */
	function getNumRows() {
		if ( !$this->mQueryDone ) {
			$this->doQuery();
		}
		return $this->mResult->numRows();
	}

	/**
	 * Get a query array for the prev, next, first and last links.
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
			$prev = array( 'dir' => 'prev', 'offset' => $this->mFirstShown, 'limit' => $urlLimit );
			$first = array( 'limit' => $urlLimit );
		}
		if ( $this->mIsLast ) {
			$next = false;
			$last = false;
		} else {
			$next = array( 'offset' => $this->mLastShown, 'limit' => $urlLimit );
			$last = array( 'dir' => 'prev', 'limit' => $urlLimit );
		}
		return compact( 'prev', 'next', 'first', 'last' );
	}

	/**
	 * Get paging links. If a link is disabled, the item from $disabledTexts will 
	 * be used. If there is no such item, the unlinked text from $linkTexts will 
	 * be used. Both $linkTexts and $disabledTexts are arrays of HTML.
	 */
	function getPagingLinks( $linkTexts, $disabledTexts = array() ) {
		$queries = $this->getPagingQueries();
		$links = array();
		foreach ( $queries as $type => $query ) {
			if ( $query !== false ) {
				$links[$type] = $this->makeLink( $linkTexts[$type], $queries[$type] );
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
			$links[] = $this->makeLink( $wgLang->formatNum( $limit ),
				array( 'offset' => $offset, 'limit' => $limit ) );
		}
		return $links;
	}

	/**
	 * Abstract formatting function. This should return an HTML string 
	 * representing the result row $row. Rows will be concatenated and
	 * returned by getBody()
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
	 */
	abstract function getQueryInfo();

	/**
	 * This function should be overridden to return the name of the 
	 * index field.
	 */
	abstract function getIndexField();
}

/**
 * IndexPager with a formatted navigation bar
 */
abstract class ReverseChronologicalPager extends IndexPager {
	public $mDefaultDirection = true;

	function __construct() {
		parent::__construct();
	}

	function getNavigationBar() {
		global $wgLang;

		if ( isset( $this->mNavigationBar ) ) {
			return $this->mNavigationBar;
		}
		$linkTexts = array(
			'prev' => wfMsgHtml( "prevn", $this->mLimit ),
			'next' => wfMsgHtml( 'nextn', $this->mLimit ),
			'first' => wfMsgHtml('histlast'),
			'last' => wfMsgHtml( 'histfirst' )
		);

		$pagingLinks = $this->getPagingLinks( $linkTexts );
		$limitLinks = $this->getLimitLinks();
		$limits = implode( ' | ', $limitLinks );
		
		$this->mNavigationBar = "({$pagingLinks['first']} | {$pagingLinks['last']}) " . wfMsgHtml("viewprevnext", $pagingLinks['prev'], $pagingLinks['next'], $limits);
		return $this->mNavigationBar;
	}
}

/**
 * Table-based display with a user-selectable sort order
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
		$s = "<table border='1' class=\"TablePager\"><thead><tr>\n";
		$fields = $this->getFieldNames();
		foreach ( $fields as $field => $name ) {
			if ( strval( $name ) == '' ) {
				$s .= "<th>&nbsp;</th>\n";
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
					$s .= "<th class=\"TablePager_sort\">$link</th>\n";
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
		return '</tbody></table>';
	}

	function getEmptyBody() {
		$colspan = count( $this->getFieldNames() );
		$msgEmpty = wfMsgHtml( 'table_pager_empty' );
		return "<tr><td colspan=\"$colspan\">$msgEmpty</td></tr>\n";
	}

	function formatRow( $row ) {
		$s = "<tr>\n";
		$fieldNames = $this->getFieldNames();
		$this->mCurrentRow = $row;  # In case formatValue needs to know
		foreach ( $fieldNames as $field => $name ) {
			$value = isset( $row->$field ) ? $row->$field : null;
			$formatted = strval( $this->formatValue( $field, $value ) );
			if ( $formatted == '' ) {
				$formatted = '&nbsp;';
			}
			$s .= "<td>$formatted</td>\n";
		}
		$s .= "</tr>\n";
		return $s;
	}

	function getIndexField() {
		return $this->mSort;
	}

	/**
	 * A navigation bar with images
	 */
	function getNavigationBar() {
		global $wgStylePath, $wgContLang;
		$path = "$wgStylePath/common/images";
		$labels = array(
			'first' => 'table_pager_first',
			'prev' => 'table_pager_prev',
			'next' => 'table_pager_next',
			'last' => 'table_pager_last',
		);
		$images = array(
			'first' => $wgContLang->isRTL() ? 'arrow_last_25.png' : 'arrow_first_25.png',
			'prev' =>  $wgContLang->isRTL() ? 'arrow_right_25.png' : 'arrow_left_25.png',
			'next' =>  $wgContLang->isRTL() ? 'arrow_left_25.png' : 'arrow_right_25.png',
			'last' =>  $wgContLang->isRTL() ? 'arrow_first_25.png' : 'arrow_last_25.png',
		);
		$disabledImages = array(
			'first' => $wgContLang->isRTL() ? 'arrow_disabled_last_25.png' : 'arrow_disabled_first_25.png',
			'prev' =>  $wgContLang->isRTL() ? 'arrow_disabled_right_25.png' : 'arrow_disabled_left_25.png',
			'next' =>  $wgContLang->isRTL() ? 'arrow_disabled_left_25.png' : 'arrow_disabled_right_25.png',
			'last' =>  $wgContLang->isRTL() ? 'arrow_disabled_first_25.png' : 'arrow_disabled_last_25.png',
		);
			
		$linkTexts = array();
		$disabledTexts = array();
		foreach ( $labels as $type => $label ) {
			$msgLabel = wfMsgHtml( $label );
			$linkTexts[$type] = "<img src=\"$path/{$images[$type]}\" alt=\"$msgLabel\"/><br/>$msgLabel";
			$disabledTexts[$type] = "<img src=\"$path/{$disabledImages[$type]}\" alt=\"$msgLabel\"/><br/>$msgLabel";
		}
		$links = $this->getPagingLinks( $linkTexts, $disabledTexts );

		$s = '<table class="TablePager_nav" align="center" cellpadding="3"><tr>';
		$cellAttrs = 'valign="top" align="center" width="' . 100 / count( $links ) . '%"';
		foreach ( $labels as $type => $label ) {
			$s .= "<td $cellAttrs>{$links[$type]}</td>\n";
		}
		$s .= '</tr></table>';
		return $s;
	}

	/**
	 * Get a <select> element which has options for each of the allowed limits
	 */
	function getLimitSelect() {
		global $wgLang;
		$s = "<select name=\"limit\">";
		foreach ( $this->mLimitsShown as $limit ) {
			$selected = $limit == $this->mLimit ? 'selected="selected"' : '';
			$formattedLimit = $wgLang->formatNum( $limit );
			$s .= "<option value=\"$limit\" $selected>$formattedLimit</option>\n";
		}
		$s .= "</select>";
		return $s;
	}

	/**
	 * Get <input type="hidden"> elements for use in a method="get" form. 
	 * Resubmits all defined elements of the $_GET array, except for a 
	 * blacklist, passed in the $blacklist parameter.
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
	 */
	function getLimitForm() {
		# Make the select with some explanatory text
		$url = $this->getTitle()->escapeLocalURL();
		$msgSubmit = wfMsgHtml( 'table_pager_limit_submit' );
		return
			"<form method=\"get\" action=\"$url\">" . 
			wfMsgHtml( 'table_pager_limit', $this->getLimitSelect() ) . 
			"\n<input type=\"submit\" value=\"$msgSubmit\"/>\n" .
			$this->getHiddenFields( 'limit' ) . 
			"</form>\n";
	}

	/**
	 * Return true if the named field should be sortable by the UI, false otherwise
	 * @param string $field
	 */
	abstract function isFieldSortable( $field );

	/**
	 * Format a table cell. The return value should be HTML, but use an empty string
	 * not &nbsp; for empty cells. Do not include the <td> and </td>. 
	 *
	 * @param string $name The database field name
	 * @param string $value The value retrieved from the database
	 *
	 * The current result row is available as $this->mCurrentRow, in case you need 
	 * more context.
	 */
	abstract function formatValue( $name, $value );

	/**
	 * The database field name used as a default sort order
	 */
	abstract function getDefaultSort();

	/**
	 * An array mapping database field names to a textual description of the field 
	 * name, for use in the table header. The description should be plain text, it 
	 * will be HTML-escaped later.
	 */
	abstract function getFieldNames();
}
?>
