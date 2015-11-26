<?php
/**
 * Base code for "query" special pages.
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
 * This is a class for doing query pages; since they're almost all the same,
 * we factor out some of the functionality into a superclass, and let
 * subclasses derive from it.
 * @ingroup SpecialPage
 */
abstract class QueryPage extends SpecialPage {
	/** @var bool Whether or not we want plain listoutput rather than an ordered list */
	protected $listoutput = false;

	/** @var int The offset and limit in use, as passed to the query() function */
	protected $offset = 0;

	/** @var int */
	protected $limit = 0;

	/**
	 * The number of rows returned by the query. Reading this variable
	 * only makes sense in functions that are run after the query has been
	 * done, such as preprocessResults() and formatRow().
	 */
	protected $numRows;

	protected $cachedTimestamp = null;

	/**
	 * Whether to show prev/next links
	 */
	protected $shownavigation = true;

	/**
	 * Get a list of query page classes and their associated special pages,
	 * for periodic updates.
	 *
	 * DO NOT CHANGE THIS LIST without testing that
	 * maintenance/updateSpecialPages.php still works.
	 * @return array
	 */
	public static function getPages() {
		static $qp = null;

		if ( $qp === null ) {
			// QueryPage subclass, Special page name
			$qp = array(
				array( 'AncientPagesPage', 'Ancientpages' ),
				array( 'BrokenRedirectsPage', 'BrokenRedirects' ),
				array( 'DeadendPagesPage', 'Deadendpages' ),
				array( 'DoubleRedirectsPage', 'DoubleRedirects' ),
				array( 'FileDuplicateSearchPage', 'FileDuplicateSearch' ),
				array( 'ListDuplicatedFilesPage', 'ListDuplicatedFiles' ),
				array( 'LinkSearchPage', 'LinkSearch' ),
				array( 'ListredirectsPage', 'Listredirects' ),
				array( 'LonelyPagesPage', 'Lonelypages' ),
				array( 'LongPagesPage', 'Longpages' ),
				array( 'MediaStatisticsPage', 'MediaStatistics' ),
				array( 'MIMEsearchPage', 'MIMEsearch' ),
				array( 'MostcategoriesPage', 'Mostcategories' ),
				array( 'MostimagesPage', 'Mostimages' ),
				array( 'MostinterwikisPage', 'Mostinterwikis' ),
				array( 'MostlinkedCategoriesPage', 'Mostlinkedcategories' ),
				array( 'MostlinkedTemplatesPage', 'Mostlinkedtemplates' ),
				array( 'MostlinkedPage', 'Mostlinked' ),
				array( 'MostrevisionsPage', 'Mostrevisions' ),
				array( 'FewestrevisionsPage', 'Fewestrevisions' ),
				array( 'ShortPagesPage', 'Shortpages' ),
				array( 'UncategorizedCategoriesPage', 'Uncategorizedcategories' ),
				array( 'UncategorizedPagesPage', 'Uncategorizedpages' ),
				array( 'UncategorizedImagesPage', 'Uncategorizedimages' ),
				array( 'UncategorizedTemplatesPage', 'Uncategorizedtemplates' ),
				array( 'UnusedCategoriesPage', 'Unusedcategories' ),
				array( 'UnusedimagesPage', 'Unusedimages' ),
				array( 'WantedCategoriesPage', 'Wantedcategories' ),
				array( 'WantedFilesPage', 'Wantedfiles' ),
				array( 'WantedPagesPage', 'Wantedpages' ),
				array( 'WantedTemplatesPage', 'Wantedtemplates' ),
				array( 'UnwatchedpagesPage', 'Unwatchedpages' ),
				array( 'UnusedtemplatesPage', 'Unusedtemplates' ),
				array( 'WithoutInterwikiPage', 'Withoutinterwiki' ),
			);
			Hooks::run( 'wgQueryPages', array( &$qp ) );
		}

		return $qp;
	}

	/**
	 * A mutator for $this->listoutput;
	 *
	 * @param bool $bool
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
	 * 'namespace', 'title', and 'value'. 'value' is used for sorting.
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
	public function getQueryInfo() {
		return null;
	}

	/**
	 * For back-compat, subclasses may return a raw SQL query here, as a string.
	 * This is strongly deprecated; getQueryInfo() should be overridden instead.
	 * @throws MWException
	 * @return string
	 */
	function getSQL() {
		/* Implement getQueryInfo() instead */
		throw new MWException( "Bug in a QueryPage: doesn't implement getQueryInfo() nor "
			. "getQuery() properly" );
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
	public function usesTimestamps() {
		return false;
	}

	/**
	 * Override to sort by increasing values
	 *
	 * @return bool
	 */
	function sortDescending() {
		return true;
	}

	/**
	 * Is this query expensive (for some definition of expensive)? Then we
	 * don't let it run in miser mode. $wgDisableQueryPages causes all query
	 * pages to be declared expensive. Some query pages are always expensive.
	 *
	 * @return bool
	 */
	public function isExpensive() {
		return $this->getConfig()->get( 'DisableQueryPages' );
	}

	/**
	 * Is the output of this query cacheable? Non-cacheable expensive pages
	 * will be disabled in miser mode and will not have their results written
	 * to the querycache table.
	 * @return bool
	 * @since 1.18
	 */
	public function isCacheable() {
		return true;
	}

	/**
	 * Whether or not the output of the page in question is retrieved from
	 * the database cache.
	 *
	 * @return bool
	 */
	public function isCached() {
		return $this->isExpensive() && $this->getConfig()->get( 'MiserMode' );
	}

	/**
	 * Sometime we don't want to build rss / atom feeds.
	 *
	 * @return bool
	 */
	function isSyndicated() {
		return true;
	}

	/**
	 * Formats the results of the query for display. The skin is the current
	 * skin; you can use it for making links. The result is a single row of
	 * result data. You should be able to grab SQL results off of it.
	 * If the function returns false, the line output will be skipped.
	 * @param Skin $skin
	 * @param object $result Result row
	 * @return string|bool String or false to skip
	 */
	abstract function formatResult( $skin, $result );

	/**
	 * The content returned by this function will be output before any result
	 *
	 * @return string
	 */
	function getPageHeader() {
		return '';
	}

	/**
	 * Outputs some kind of an informative message (via OutputPage) to let the
	 * user know that the query returned nothing and thus there's nothing to
	 * show.
	 *
	 * @since 1.26
	 */
	protected function showEmptyText() {
		$this->getOutput()->addWikiMsg( 'specialpage-empty' );
	}

	/**
	 * If using extra form wheely-dealies, return a set of parameters here
	 * as an associative array. They will be encoded and added to the paging
	 * links (prev/next/lengths).
	 *
	 * @return array
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
	 * @return bool
	 */
	function tryLastResult() {
		return false;
	}

	/**
	 * Clear the cache and save new results
	 *
	 * @param int|bool $limit Limit for SQL statement
	 * @param bool $ignoreErrors Whether to ignore database errors
	 * @throws DBError|Exception
	 * @return bool|int
	 */
	public function recache( $limit, $ignoreErrors = true ) {
		if ( !$this->isCacheable() ) {
			return 0;
		}

		$fname = get_class( $this ) . '::recache';
		$dbw = wfGetDB( DB_MASTER );
		if ( !$dbw ) {
			return false;
		}

		try {
			# Do query
			$res = $this->reallyDoQuery( $limit, false );
			$num = false;
			if ( $res ) {
				$num = $res->numRows();
				# Fetch results
				$vals = array();
				foreach ( $res as $row ) {
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

				$dbw->startAtomic( __METHOD__ );
				# Clear out any old cached data
				$dbw->delete( 'querycache', array( 'qc_type' => $this->getName() ), $fname );
				# Save results into the querycache table on the master
				if ( count( $vals ) ) {
					$dbw->insert( 'querycache', $vals, __METHOD__ );
				}
				# Update the querycache_info record for the page
				$dbw->delete( 'querycache_info', array( 'qci_type' => $this->getName() ), $fname );
				$dbw->insert( 'querycache_info',
					array( 'qci_type' => $this->getName(), 'qci_timestamp' => $dbw->timestamp() ),
					$fname );
				$dbw->endAtomic( __METHOD__ );
			}
		} catch ( DBError $e ) {
			if ( !$ignoreErrors ) {
				throw $e; // report query error
			}
			$num = false; // set result to false to indicate error
		}

		return $num;
	}

	/**
	 * Get a DB connection to be used for slow recache queries
	 * @return IDatabase
	 */
	function getRecacheDB() {
		return wfGetDB( DB_SLAVE, array( $this->getName(), 'QueryPage::recache', 'vslow' ) );
	}

	/**
	 * Run the query and return the result
	 * @param int|bool $limit Numerical limit or false for no limit
	 * @param int|bool $offset Numerical offset or false for no offset
	 * @return ResultWrapper
	 * @since 1.18
	 */
	public function reallyDoQuery( $limit, $offset = false ) {
		$fname = get_class( $this ) . "::reallyDoQuery";
		$dbr = $this->getRecacheDB();
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
				$options['ORDER BY'] = $order;
			}

			if ( $limit !== false ) {
				$options['LIMIT'] = intval( $limit );
			}

			if ( $offset !== false ) {
				$options['OFFSET'] = intval( $offset );
			}

			$res = $dbr->select( $tables, $fields, $conds, $fname,
					$options, $join_conds
			);
		} else {
			// Old-fashioned raw SQL style, deprecated
			$sql = $this->getSQL();
			$sql .= ' ORDER BY ' . implode( ', ', $order );
			$sql = $dbr->limitResult( $sql, $limit, $offset );
			$res = $dbr->query( $sql, $fname );
		}

		return $res;
	}

	/**
	 * Somewhat deprecated, you probably want to be using execute()
	 * @param int|bool $offset
	 * @param int|bool $limit
	 * @return ResultWrapper
	 */
	public function doQuery( $offset = false, $limit = false ) {
		if ( $this->isCached() && $this->isCacheable() ) {
			return $this->fetchFromCache( $limit, $offset );
		} else {
			return $this->reallyDoQuery( $limit, $offset );
		}
	}

	/**
	 * Fetch the query results from the query cache
	 * @param int|bool $limit Numerical limit or false for no limit
	 * @param int|bool $offset Numerical offset or false for no offset
	 * @return ResultWrapper
	 * @since 1.18
	 */
	public function fetchFromCache( $limit, $offset = false ) {
		$dbr = wfGetDB( DB_SLAVE );
		$options = array();
		if ( $limit !== false ) {
			$options['LIMIT'] = intval( $limit );
		}
		if ( $offset !== false ) {
			$options['OFFSET'] = intval( $offset );
		}
		if ( $this->sortDescending() ) {
			$options['ORDER BY'] = 'qc_value DESC';
		} else {
			$options['ORDER BY'] = 'qc_value ASC';
		}
		$res = $dbr->select( 'querycache', array( 'qc_type',
				'namespace' => 'qc_namespace',
				'title' => 'qc_title',
				'value' => 'qc_value' ),
				array( 'qc_type' => $this->getName() ),
				__METHOD__, $options
		);
		return $dbr->resultObject( $res );
	}

	public function getCachedTimestamp() {
		if ( is_null( $this->cachedTimestamp ) ) {
			$dbr = wfGetDB( DB_SLAVE );
			$fname = get_class( $this ) . '::getCachedTimestamp';
			$this->cachedTimestamp = $dbr->selectField( 'querycache_info', 'qci_timestamp',
				array( 'qci_type' => $this->getName() ), $fname );
		}
		return $this->cachedTimestamp;
	}

	/**
	 * Returns limit and offset, as returned by $this->getRequest()->getLimitOffset().
	 * Subclasses may override this to further restrict or modify limit and offset.
	 *
	 * @since 1.26
	 *
	 * @return int[] list( $limit, $offset )
	 */
	protected function getLimitOffset() {
		return $this->getRequest()->getLimitOffset();
	}

	/**
	 * This is the actual workhorse. It does everything needed to make a
	 * real, honest-to-gosh query page.
	 * @param string $par
	 */
	public function execute( $par ) {
		$user = $this->getUser();
		if ( !$this->userCanExecute( $user ) ) {
			$this->displayRestrictionError();
			return;
		}

		$this->setHeaders();
		$this->outputHeader();

		$out = $this->getOutput();

		if ( $this->isCached() && !$this->isCacheable() ) {
			$out->addWikiMsg( 'querypage-disabled' );
			return;
		}

		$out->setSyndicated( $this->isSyndicated() );

		if ( $this->limit == 0 && $this->offset == 0 ) {
			list( $this->limit, $this->offset ) = $this->getLimitOffset();
		}

		// @todo Use doQuery()
		if ( !$this->isCached() ) {
			# select one extra row for navigation
			$res = $this->reallyDoQuery( $this->limit + 1, $this->offset );
		} else {
			# Get the cached result, select one extra row for navigation
			$res = $this->fetchFromCache( $this->limit + 1, $this->offset );
			if ( !$this->listoutput ) {

				# Fetch the timestamp of this update
				$ts = $this->getCachedTimestamp();
				$lang = $this->getLanguage();
				$maxResults = $lang->formatNum( $this->getConfig()->get( 'QueryCacheLimit' ) );

				if ( $ts ) {
					$updated = $lang->userTimeAndDate( $ts, $user );
					$updateddate = $lang->userDate( $ts, $user );
					$updatedtime = $lang->userTime( $ts, $user );
					$out->addMeta( 'Data-Cache-Time', $ts );
					$out->addJsConfigVars( 'dataCacheTime', $ts );
					$out->addWikiMsg( 'perfcachedts', $updated, $updateddate, $updatedtime, $maxResults );
				} else {
					$out->addWikiMsg( 'perfcached', $maxResults );
				}

				# If updates on this page have been disabled, let the user know
				# that the data set won't be refreshed for now
				if ( is_array( $this->getConfig()->get( 'DisableQueryPageUpdate' ) )
					&& in_array( $this->getName(), $this->getConfig()->get( 'DisableQueryPageUpdate' ) )
				) {
					$out->wrapWikiMsg(
						"<div class=\"mw-querypage-no-updates\">\n$1\n</div>",
						'querypage-no-updates'
					);
				}
			}
		}

		$this->numRows = $res->numRows();

		$dbr = $this->getRecacheDB();
		$this->preprocessResults( $dbr, $res );

		$out->addHTML( Xml::openElement( 'div', array( 'class' => 'mw-spcontent' ) ) );

		# Top header and navigation
		if ( $this->shownavigation ) {
			$out->addHTML( $this->getPageHeader() );
			if ( $this->numRows > 0 ) {
				$out->addHTML( $this->msg( 'showingresultsinrange' )->numParams(
					min( $this->numRows, $this->limit ), # do not show the one extra row, if exist
					$this->offset + 1, ( min( $this->numRows, $this->limit ) + $this->offset ) )->parseAsBlock() );
				# Disable the "next" link when we reach the end
				$paging = $this->getLanguage()->viewPrevNext( $this->getPageTitle( $par ), $this->offset,
					$this->limit, $this->linkParameters(), ( $this->numRows <= $this->limit ) );
				$out->addHTML( '<p>' . $paging . '</p>' );
			} else {
				# No results to show, so don't bother with "showing X of Y" etc.
				# -- just let the user know and give up now
				$this->showEmptyText();
				$out->addHTML( Xml::closeElement( 'div' ) );
				return;
			}
		}

		# The actual results; specialist subclasses will want to handle this
		# with more than a straight list, so we hand them the info, plus
		# an OutputPage, and let them get on with it
		$this->outputResults( $out,
			$this->getSkin(),
			$dbr, # Should use a ResultWrapper for this
			$res,
			min( $this->numRows, $this->limit ), # do not format the one extra row, if exist
			$this->offset );

		# Repeat the paging links at the bottom
		if ( $this->shownavigation ) {
			$out->addHTML( '<p>' . $paging . '</p>' );
		}

		$out->addHTML( Xml::closeElement( 'div' ) );
	}

	/**
	 * Format and output report results using the given information plus
	 * OutputPage
	 *
	 * @param OutputPage $out OutputPage to print to
	 * @param Skin $skin User skin to use
	 * @param IDatabase $dbr Database (read) connection to use
	 * @param ResultWrapper $res Result pointer
	 * @param int $num Number of available result rows
	 * @param int $offset Paging offset
	 */
	protected function outputResults( $out, $skin, $dbr, $res, $num, $offset ) {
		global $wgContLang;

		if ( $num > 0 ) {
			$html = array();
			if ( !$this->listoutput ) {
				$html[] = $this->openList( $offset );
			}

			# $res might contain the whole 1,000 rows, so we read up to
			# $num [should update this to use a Pager]
			// @codingStandardsIgnoreStart Generic.CodeAnalysis.ForLoopWithTestFunctionCall.NotAllowed
			for ( $i = 0; $i < $num && $row = $res->fetchObject(); $i++ ) {
				// @codingStandardsIgnoreEnd
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

			if ( !$this->listoutput ) {
				$html[] = $this->closeList();
			}

			$html = $this->listoutput
				? $wgContLang->listToText( $html )
				: implode( '', $html );

			$out->addHTML( $html );
		}
	}

	/**
	 * @param int $offset
	 * @return string
	 */
	function openList( $offset ) {
		return "\n<ol start='" . ( $offset + 1 ) . "' class='special'>\n";
	}

	/**
	 * @return string
	 */
	function closeList() {
		return "</ol>\n";
	}

	/**
	 * Do any necessary preprocessing of the result object.
	 * @param IDatabase $db
	 * @param ResultWrapper $res
	 */
	function preprocessResults( $db, $res ) {
	}

	/**
	 * Similar to above, but packaging in a syndicated feed instead of a web page
	 * @param string $class
	 * @param int $limit
	 * @return bool
	 */
	function doFeed( $class = '', $limit = 50 ) {
		if ( !$this->getConfig()->get( 'Feed' ) ) {
			$this->getOutput()->addWikiMsg( 'feed-unavailable' );
			return false;
		}

		$limit = min( $limit, $this->getConfig()->get( 'FeedLimit' ) );

		$feedClasses = $this->getConfig()->get( 'FeedClasses' );
		if ( isset( $feedClasses[$class] ) ) {
			/** @var RSSFeed|AtomFeed $feed */
			$feed = new $feedClasses[$class](
				$this->feedTitle(),
				$this->feedDesc(),
				$this->feedUrl() );
			$feed->outHeader();

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
	 * @param object $row
	 * @return FeedItem|null
	 */
	function feedResult( $row ) {
		if ( !isset( $row->title ) ) {
			return null;
		}
		$title = Title::makeTitle( intval( $row->namespace ), $row->title );
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
		$desc = $this->getDescription();
		$code = $this->getConfig()->get( 'LanguageCode' );
		$sitename = $this->getConfig()->get( 'Sitename' );
		return "$sitename - $desc [$code]";
	}

	function feedDesc() {
		return $this->msg( 'tagline' )->text();
	}

	function feedUrl() {
		return $this->getPageTitle()->getFullURL();
	}
}
