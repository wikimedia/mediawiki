<?php
/**
 * Contain a class for special pages
 * @file
 * @ingroup SpecialPages
 */

/**
 * List of query page classes and their associated special pages,
 * for periodic updates.
 *
 * DO NOT CHANGE THIS LIST without testing that
 * maintenance/updateSpecialPages.php still works.
 */
global $wgQueryPages; // not redundant
$wgQueryPages = array(
//         QueryPage subclass           Special page name         Limit (false for none, none for the default)
// ----------------------------------------------------------------------------
	array( 'AncientPagesPage',              'Ancientpages'                  ),
	array( 'BrokenRedirectsPage',           'BrokenRedirects'               ),
	array( 'DeadendPagesPage',              'Deadendpages'                  ),
	array( 'DisambiguationsPage',           'Disambiguations'               ),
	array( 'DoubleRedirectsPage',           'DoubleRedirects'               ),
	array( 'FileDuplicateSearchPage',       'FileDuplicateSearch'           ),
	array( 'LinkSearchPage',                'LinkSearch'                    ),
	array( 'ListredirectsPage',             'Listredirects'                 ),
	array( 'LonelyPagesPage',               'Lonelypages'                   ),
	array( 'LongPagesPage',                 'Longpages'                     ),
	array( 'MIMEsearchPage',                'MIMEsearch'                    ),
	array( 'MostcategoriesPage',            'Mostcategories'                ),
	array( 'MostimagesPage',                'Mostimages'                    ),
	array( 'MostlinkedCategoriesPage',      'Mostlinkedcategories'          ),
	array( 'MostlinkedtemplatesPage',       'Mostlinkedtemplates'           ),
	array( 'MostlinkedPage',                'Mostlinked'                    ),
	array( 'MostrevisionsPage',             'Mostrevisions'                 ),
	array( 'FewestrevisionsPage',           'Fewestrevisions'               ),
	array( 'ShortPagesPage',                'Shortpages'                    ),
	array( 'UncategorizedCategoriesPage',   'Uncategorizedcategories'       ),
	array( 'UncategorizedPagesPage',        'Uncategorizedpages'            ),
	array( 'UncategorizedImagesPage',       'Uncategorizedimages'           ),
	array( 'UncategorizedTemplatesPage',    'Uncategorizedtemplates'        ),
	array( 'UnusedCategoriesPage',          'Unusedcategories'              ),
	array( 'UnusedimagesPage',              'Unusedimages'                  ),
	array( 'WantedCategoriesPage',          'Wantedcategories'              ),
	array( 'WantedFilesPage',               'Wantedfiles'                   ),
	array( 'WantedPagesPage',               'Wantedpages'                   ),
	array( 'WantedTemplatesPage',           'Wantedtemplates'               ),
	array( 'UnwatchedPagesPage',            'Unwatchedpages'                ),
	array( 'UnusedtemplatesPage',           'Unusedtemplates'               ),
	array( 'WithoutInterwikiPage',          'Withoutinterwiki'              ),
);
wfRunHooks( 'wgQueryPages', array( &$wgQueryPages ) );

global $wgDisableCounters;
if ( !$wgDisableCounters )
	$wgQueryPages[] = array( 'PopularPagesPage', 'Popularpages' );


/**
 * This is a class for doing query pages; since they're almost all the same,
 * we factor out some of the functionality into a superclass, and let
 * subclasses derive from it.
 * @ingroup SpecialPage
 */
abstract class QueryPage extends SpecialPage {
	/**
	 * Whether or not we want plain listoutput rather than an ordered list
	 *
	 * @var bool
	 */
	var $listoutput = false;

	/**
	 * The offset and limit in use, as passed to the query() function
	 *
	 * @var integer
	 */
	var $offset = 0;
	var $limit = 0;

	/**
	 * The number of rows returned by the query. Reading this variable
	 * only makes sense in functions that are run after the query has been
	 * done, such as preprocessResults() and formatRow().
	 */
	protected $numRows;

	/**
	 * Wheter to show prev/next links
	 */
	protected $shownavigation = true;

	/**
	 * A mutator for $this->listoutput;
	 *
	 * @param $bool Boolean
	 */
	function setListoutput( $bool ) {
		$this->listoutput = $bool;
	}

	/**
	 * Subclasses return an SQL query here, formatted as an array with the
	 * following keys:
	 *    tables => Table(s) for passing to Database::select()
	 *    fields => Field(s) for passing to Database::select(), may be *
	 *    conds => WHERE conditions
	 *    options => options
	 *    join_conds => JOIN conditions
	 *
	 * Note that the query itself should return the following three columns:
	 * 'namespace', 'title', and 'value'
	 * *in that order*. 'value' is used for sorting.
	 *
	 * These may be stored in the querycache table for expensive queries,
	 * and that cached data will be returned sometimes, so the presence of
	 * extra fields can't be relied upon. The cached 'value' column will be
	 * an integer; non-numeric values are useful only for sorting the
	 * initial query (except if they're timestamps, see usesTimestamps()).
	 *
	 * Don't include an ORDER or LIMIT clause, they will be added.
	 *
	 * If this function is not overridden or returns something other than
	 * an array, getSQL() will be used instead. This is for backwards
	 * compatibility only and is strongly deprecated.
	 * @return array
	 * @since 1.18
	 */
	function getQueryInfo() {
		return null;
	}
	
	/**
	 * For back-compat, subclasses may return a raw SQL query here, as a string.
	 * This is stronly deprecated; getQueryInfo() should be overridden instead.
	 * @return string
	 * @deprecated since 1.18
	 */
	function getSQL() {
		throw new MWException( "Bug in a QueryPage: doesn't implement getQueryInfo() nor getQuery() properly" );
	}

	/**
	 * Subclasses return an array of fields to order by here. Don't append
	 * DESC to the field names, that'll be done automatically if
	 * sortDescending() returns true.
	 * @return array
	 * @since 1.18
	 */
	function getOrderFields() {
		return array( 'value' );
	}

	/**
	 * Does this query return timestamps rather than integers in its
	 * 'value' field? If true, this class will convert 'value' to a
	 * UNIX timestamp for caching.
	 * NOTE: formatRow() may get timestamps in TS_MW (mysql), TS_DB (pgsql)
	 *       or TS_UNIX (querycache) format, so be sure to always run them
	 *       through wfTimestamp()
	 * @return bool
	 * @since 1.18
	 */
	function usesTimestamps() {
		return false;
	}

	/**
	 * Override to sort by increasing values
	 *
	 * @return Boolean
	 */
	function sortDescending() {
		return true;
	}

	/**
	 * Is this query expensive (for some definition of expensive)? Then we
	 * don't let it run in miser mode. $wgDisableQueryPages causes all query
	 * pages to be declared expensive. Some query pages are always expensive.
	 *
	 * @return Boolean
	 */
	function isExpensive() {
		global $wgDisableQueryPages;
		return $wgDisableQueryPages;
	}

	/**
	 * Is the output of this query cacheable? Non-cacheable expensive pages
	 * will be disabled in miser mode and will not have their results written
	 * to the querycache table.
	 * @return Boolean
	 * @since 1.18
	 */
	public function isCacheable() {
		return true;
	}

	/**
	 * Whether or not the output of the page in question is retrieved from
	 * the database cache.
	 *
	 * @return Boolean
	 */
	function isCached() {
		global $wgMiserMode;

		return $this->isExpensive() && $wgMiserMode;
	}

	/**
	 * Sometime we dont want to build rss / atom feeds.
	 *
	 * @return Boolean
	 */
	function isSyndicated() {
		return true;
	}

	/**
	 * Formats the results of the query for display. The skin is the current
	 * skin; you can use it for making links. The result is a single row of
	 * result data. You should be able to grab SQL results off of it.
	 * If the function returns false, the line output will be skipped.
	 * @param $skin Skin
	 * @param $result object Result row
	 * @return mixed String or false to skip
	 *
	 * @param $skin Skin object
	 * @param $result Object: database row
	 */
	abstract function formatResult( $skin, $result );

	/**
	 * The content returned by this function will be output before any result
	 *
	 * @return String
	 */
	function getPageHeader() {
		return '';
	}

	/**
	 * If using extra form wheely-dealies, return a set of parameters here
	 * as an associative array. They will be encoded and added to the paging
	 * links (prev/next/lengths).
	 *
	 * @return Array
	 */
	function linkParameters() {
		return array();
	}

	/**
	 * Some special pages (for example SpecialListusers) might not return the
	 * current object formatted, but return the previous one instead.
	 * Setting this to return true will ensure formatResult() is called
	 * one more time to make sure that the very last result is formatted
	 * as well.
	 */
	function tryLastResult() {
		return false;
	}

	/**
	 * Clear the cache and save new results
	 *
	 * @param $limit Integer: limit for SQL statement
	 * @param $ignoreErrors Boolean: whether to ignore database errors
	 */
	function recache( $limit, $ignoreErrors = true ) {
		if ( !$this->isCacheable() ) {
			return 0;
		}
		
		$fname = get_class( $this ) . '::recache';
		$dbw = wfGetDB( DB_MASTER );
		$dbr = wfGetDB( DB_SLAVE, array( $this->getName(), __METHOD__, 'vslow' ) );
		if ( !$dbw || !$dbr ) {
			return false;
		}

		if ( $ignoreErrors ) {
			$ignoreW = $dbw->ignoreErrors( true );
			$ignoreR = $dbr->ignoreErrors( true );
		}

		# Clear out any old cached data
		$dbw->delete( 'querycache', array( 'qc_type' => $this->getName() ), $fname );
		# Do query
		$res = $this->reallyDoQuery( $limit, false );
		$num = false;
		if ( $res ) {
			$num = $dbr->numRows( $res );
			# Fetch results
			$vals = array();
			while ( $res && $row = $dbr->fetchObject( $res ) ) {
				if ( isset( $row->value ) ) {
					if ( $this->usesTimestamps() ) {
						$value = wfTimestamp( TS_UNIX,
							$row->value );
					} else {
						$value = intval( $row->value ); // @bug 14414
					}
				} else {
					$value = 0;
				}

				$vals[] = array( 'qc_type' => $this->getName(),
						'qc_namespace' => $row->namespace,
						'qc_title' => $row->title,
						'qc_value' => $value );
			}

			# Save results into the querycache table on the master
			if ( count( $vals ) ) {
				if ( !$dbw->insert( 'querycache', $vals, __METHOD__ ) ) {
					// Set result to false to indicate error
					$num = false;
				}
			}
			if ( $ignoreErrors ) {
				$dbw->ignoreErrors( $ignoreW );
				$dbr->ignoreErrors( $ignoreR );
			}

			# Update the querycache_info record for the page
			$dbw->delete( 'querycache_info', array( 'qci_type' => $this->getName() ), $fname );
			$dbw->insert( 'querycache_info', array( 'qci_type' => $this->getName(), 'qci_timestamp' => $dbw->timestamp() ), $fname );

		}
		return $num;
	}

	/**
	 * Run the query and return the result
	 * @param $limit mixed Numerical limit or false for no limit
	 * @param $offset mixed Numerical offset or false for no offset
	 * @return ResultWrapper
	 * @since 1.18
	 */
	function reallyDoQuery( $limit, $offset = false ) {
		$fname = get_class( $this ) . "::reallyDoQuery";
		$query = $this->getQueryInfo();
		$order = $this->getOrderFields();
		if ( $this->sortDescending() ) {
			foreach ( $order as &$field ) {
				$field .= ' DESC';
			}
		}
		if ( is_array( $query ) ) {
			$tables = isset( $query['tables'] ) ? (array)$query['tables'] : array();
			$fields = isset( $query['fields'] ) ? (array)$query['fields'] : array();
			$conds = isset( $query['conds'] ) ? (array)$query['conds'] : array();
			$options = isset( $query['options'] ) ? (array)$query['options'] : array();
			$join_conds = isset( $query['join_conds'] ) ? (array)$query['join_conds'] : array();
			if ( count( $order ) ) {
				$options['ORDER BY'] = implode( ', ', $order );
			}
			if ( $limit !== false ) {
				$options['LIMIT'] = intval( $limit );
			}
			if ( $offset !== false ) {
				$options['OFFSET'] = intval( $offset );
			}

			$dbr = wfGetDB( DB_SLAVE );
			$res = $dbr->select( $tables, $fields, $conds, $fname,
					$options, $join_conds
			);
		} else {
			// Old-fashioned raw SQL style, deprecated
			$sql = $this->getSQL();
			$sql .= ' ORDER BY ' . implode( ', ', $order );
			$sql = $dbr->limitResult( $sql, $limit, $offset );
			$res = $dbr->query( $sql );
		}
		return $dbr->resultObject( $res );
	}

	/**
	 * Parameters and order changed in 1.18
	 */
	function doQuery( $limit, $offset = false ) {
		if ( $this->isCached() && $this->isCacheable() ) {
			return $this->fetchFromCache( $limit, $offset );
		} else {
			return $this->reallyDoQuery( $limit, $offset );
		}
	}

	/**
	 * Fetch the query results from the query cache
	 * @param $limit mixed Numerical limit or false for no limit
	 * @param $offset mixed Numerical offset or false for no offset
	 * @return ResultWrapper
	 * @since 1.18
	 */
	function fetchFromCache( $limit, $offset = false ) {
		$dbr = wfGetDB( DB_SLAVE );
		$options = array ();
		if ( $limit !== false ) {
			$options['LIMIT'] = intval( $limit );
		}
		if ( $offset !== false ) {
			$options['OFFSET'] = intval( $offset );
		}
		$res = $dbr->select( 'querycache', array( 'qc_type',
				'qc_namespace AS namespace',
				'qc_title AS title',
				'qc_value AS value' ),
				array( 'qc_type' => $this->getName() ),
				__METHOD__, $options
		);
		return $dbr->resultObject( $res );
	}

	/**
	 * This is the actual workhorse. It does everything needed to make a
	 * real, honest-to-gosh query page.
	 */
	function execute( $par ) {
		global $wgUser, $wgOut, $wgLang;

		if ( !$this->userCanExecute( $wgUser ) ) {
			$this->displayRestrictionError();
			return;
		}

		if ( $this->limit == 0 && $this->offset == 0 )
			list( $this->limit, $this->offset ) = wfCheckLimits();
		$sname = $this->getName();
		$fname = get_class( $this ) . '::doQuery';
		$dbr = wfGetDB( DB_SLAVE );

		$this->setHeaders();
		$wgOut->setSyndicated( $this->isSyndicated() );

		if ( $this->isCached() && !$this->isCacheable() ) {
			$wgOut->setSyndicated( false );
			$wgOut->addWikiMsg( 'querypage-disabled' );
			return 0;
		}

		// TODO: Use doQuery()
		// $res = null;
		if ( !$this->isCached() ) {
			$res = $this->reallyDoQuery( $this->limit, $this->offset );
		} else {
			# Get the cached result
			$res = $this->fetchFromCache( $this->limit, $this->offset );
			if ( !$this->listoutput ) {

				# Fetch the timestamp of this update
				$tRes = $dbr->select( 'querycache_info', array( 'qci_timestamp' ), array( 'qci_type' => $sname ), $fname );
				$tRow = $dbr->fetchObject( $tRes );

				if ( $tRow ) {
					$updated = $wgLang->timeanddate( $tRow->qci_timestamp, true, true );
					$updateddate = $wgLang->date( $tRow->qci_timestamp, true, true );
					$updatedtime = $wgLang->time( $tRow->qci_timestamp, true, true );
					$wgOut->addMeta( 'Data-Cache-Time', $tRow->qci_timestamp );
					$wgOut->addInlineScript( "var dataCacheTime = '{$tRow->qci_timestamp}';" );
					$wgOut->addWikiMsg( 'perfcachedts', $updated, $updateddate, $updatedtime );
				} else {
					$wgOut->addWikiMsg( 'perfcached' );
				}

				# If updates on this page have been disabled, let the user know
				# that the data set won't be refreshed for now
				global $wgDisableQueryPageUpdate;
				if ( is_array( $wgDisableQueryPageUpdate ) && in_array( $this->getName(), $wgDisableQueryPageUpdate ) ) {
					$wgOut->addWikiMsg( 'querypage-no-updates' );
				}

			}

		}

		$this->numRows = $dbr->numRows( $res );

		$this->preprocessResults( $dbr, $res );

		$wgOut->addHTML( Xml::openElement( 'div', array( 'class' => 'mw-spcontent' ) ) );

		# Top header and navigation
		if ( $this->shownavigation ) {
			$wgOut->addHTML( $this->getPageHeader() );
			if ( $this->numRows > 0 ) {
				$wgOut->addHTML( '<p>' . wfShowingResults( $this->offset, $this->numRows ) . '</p>' );
				# Disable the "next" link when we reach the end
				$paging = wfViewPrevNext( $this->offset, $this->limit,
					$this->getTitle( $par ),
					wfArrayToCGI( $this->linkParameters() ), ( $this->numRows < $this->limit ) );
				$wgOut->addHTML( '<p>' . $paging . '</p>' );
			} else {
				# No results to show, so don't bother with "showing X of Y" etc.
				# -- just let the user know and give up now
				$wgOut->addHTML( '<p>' . wfMsgHtml( 'specialpage-empty' ) . '</p>' );
				$wgOut->addHTML( Xml::closeElement( 'div' ) );
				return;
			}
		}

		# The actual results; specialist subclasses will want to handle this
		# with more than a straight list, so we hand them the info, plus
		# an OutputPage, and let them get on with it
		$this->outputResults( $wgOut,
			$wgUser->getSkin(),
			$dbr, # Should use a ResultWrapper for this
			$res,
			$this->numRows,
			$this->offset );

		# Repeat the paging links at the bottom
		if ( $this->shownavigation ) {
			$wgOut->addHTML( '<p>' . $paging . '</p>' );
		}

		$wgOut->addHTML( Xml::closeElement( 'div' ) );

		return $this->numRows;
	}

	/**
	 * Format and output report results using the given information plus
	 * OutputPage
	 *
	 * @param $out OutputPage to print to
	 * @param $skin Skin: user skin to use
	 * @param $dbr Database (read) connection to use
	 * @param $res Integer: result pointer
	 * @param $num Integer: number of available result rows
	 * @param $offset Integer: paging offset
	 */
	protected function outputResults( $out, $skin, $dbr, $res, $num, $offset ) {
		global $wgContLang;

		if ( $num > 0 ) {
			$html = array();
			if ( !$this->listoutput )
				$html[] = $this->openList( $offset );

			# $res might contain the whole 1,000 rows, so we read up to
			# $num [should update this to use a Pager]
			for ( $i = 0; $i < $num && $row = $dbr->fetchObject( $res ); $i++ ) {
				$line = $this->formatResult( $skin, $row );
				if ( $line ) {
					$attr = ( isset( $row->usepatrol ) && $row->usepatrol && $row->patrolled == 0 )
						? ' class="not-patrolled"'
						: '';
					$html[] = $this->listoutput
						? $line
						: "<li{$attr}>{$line}</li>\n";
				}
			}

			# Flush the final result
			if ( $this->tryLastResult() ) {
				$row = null;
				$line = $this->formatResult( $skin, $row );
				if ( $line ) {
					$attr = ( isset( $row->usepatrol ) && $row->usepatrol && $row->patrolled == 0 )
						? ' class="not-patrolled"'
						: '';
					$html[] = $this->listoutput
						? $line
						: "<li{$attr}>{$line}</li>\n";
				}
			}

			if ( !$this->listoutput )
				$html[] = $this->closeList();

			$html = $this->listoutput
				? $wgContLang->listToText( $html )
				: implode( '', $html );

			$out->addHTML( $html );
		}
	}

	function openList( $offset ) {
		return "\n<ol start='" . ( $offset + 1 ) . "' class='special'>\n";
	}

	function closeList() {
		return "</ol>\n";
	}

	/**
	 * Do any necessary preprocessing of the result object.
	 */
	function preprocessResults( $db, $res ) {}

	/**
	 * Similar to above, but packaging in a syndicated feed instead of a web page
	 */
	function doFeed( $class = '', $limit = 50 ) {
		global $wgFeed, $wgFeedClasses;

		if ( !$wgFeed ) {
			global $wgOut;
			$wgOut->addWikiMsg( 'feed-unavailable' );
			return;
		}

		global $wgFeedLimit;
		if ( $limit > $wgFeedLimit ) {
			$limit = $wgFeedLimit;
		}

		if ( isset( $wgFeedClasses[$class] ) ) {
			$feed = new $wgFeedClasses[$class](
				$this->feedTitle(),
				$this->feedDesc(),
				$this->feedUrl() );
			$feed->outHeader();

			$dbr = wfGetDB( DB_SLAVE );
			$res = $this->reallyDoQuery( $limit, 0 );
			foreach ( $res as $obj ) {
				$item = $this->feedResult( $obj );
				if ( $item ) {
					$feed->outItem( $item );
				}
			}

			$feed->outFooter();
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Override for custom handling. If the titles/links are ok, just do
	 * feedItemDesc()
	 */
	function feedResult( $row ) {
		if ( !isset( $row->title ) ) {
			return null;
		}
		$title = Title::MakeTitle( intval( $row->namespace ), $row->title );
		if ( $title ) {
			$date = isset( $row->timestamp ) ? $row->timestamp : '';
			$comments = '';
			if ( $title ) {
				$talkpage = $title->getTalkPage();
				$comments = $talkpage->getFullURL();
			}

			return new FeedItem(
				$title->getPrefixedText(),
				$this->feedItemDesc( $row ),
				$title->getFullURL(),
				$date,
				$this->feedItemAuthor( $row ),
				$comments );
		} else {
			return null;
		}
	}

	function feedItemDesc( $row ) {
		return isset( $row->comment ) ? htmlspecialchars( $row->comment ) : '';
	}

	function feedItemAuthor( $row ) {
		return isset( $row->user_text ) ? $row->user_text : '';
	}

	function feedTitle() {
		global $wgLanguageCode, $wgSitename;
		$page = SpecialPage::getPage( $this->getName() );
		$desc = $page->getDescription();
		return "$wgSitename - $desc [$wgLanguageCode]";
	}

	function feedDesc() {
		return wfMsgExt( 'tagline', 'parsemag' );
	}

	function feedUrl() {
		$title = SpecialPage::getTitleFor( $this->getName() );
		return $title->getFullURL();
	}
}

/**
 * Class definition for a wanted query page like
 * WantedPages, WantedTemplates, etc
 */
abstract class WantedQueryPage extends QueryPage {

	function isExpensive() {
		return true;
	}

	function isSyndicated() {
		return false;
	}

	/**
	 * Cache page existence for performance
	 */
	function preprocessResults( $db, $res ) {
		$batch = new LinkBatch;
		foreach ( $res as $row ) {
			$batch->add( $row->namespace, $row->title );
		}
		$batch->execute();

		// Back to start for display
		if ( $db->numRows( $res ) > 0 )
			// If there are no rows we get an error seeking.
			$db->dataSeek( $res, 0 );
	}

	/**
	 * Should formatResult() always check page existence, even if
	 * the results are fresh?  This is a (hopefully temporary)
	 * kluge for Special:WantedFiles, which may contain false
	 * positives for files that exist e.g. in a shared repo (bug
	 * 6220).
	 */
	function forceExistenceCheck() {
		return false;
	}

	/**
	 * Format an individual result
	 *
	 * @param $skin Skin to use for UI elements
	 * @param $result Result row
	 * @return string
	 */
	public function formatResult( $skin, $result ) {
		$title = Title::makeTitleSafe( $result->namespace, $result->title );
		if ( $title instanceof Title ) {
			if ( $this->isCached() || $this->forceExistenceCheck() ) {
				$pageLink = $title->isKnown()
					? '<del>' . $skin->link( $title ) . '</del>'
					: $skin->link(
						$title,
						null,
						array(),
						array(),
						array( 'broken' )
					);
			} else {
				$pageLink = $skin->link(
					$title,
					null,
					array(),
					array(),
					array( 'broken' )
				);
			}
			return wfSpecialList( $pageLink, $this->makeWlhLink( $title, $skin, $result ) );
		} else {
			$tsafe = htmlspecialchars( $result->title );
			return wfMsgHtml( 'wantedpages-badtitle', $tsafe );
		}
	}

	/**
	 * Make a "what links here" link for a given title
	 *
	 * @param $title Title to make the link for
	 * @param $skin Skin object to use
	 * @param $result Object: result row
	 * @return string
	 */
	private function makeWlhLink( $title, $skin, $result ) {
		global $wgLang;
		$wlh = SpecialPage::getTitleFor( 'Whatlinkshere' );
		$label = wfMsgExt( 'nlinks', array( 'parsemag', 'escape' ),
		$wgLang->formatNum( $result->value ) );
		return $skin->link( $wlh, $label, array(), array( 'target' => $title->getPrefixedText() ) );
	}
}
