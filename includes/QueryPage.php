<?php
/**
 * Contain a class for special pages
 */

/**
 * List of query page classes and their associated special pages, for periodic update purposes
 */
global $wgQueryPages; // not redundant
$wgQueryPages = array(
//         QueryPage subclass           Special page name         Limit (false for none, none for the default)
//----------------------------------------------------------------------------
	array( 'AncientPagesPage',		'Ancientpages'			),
	array( 'BrokenRedirectsPage',		'BrokenRedirects'		),
	array( 'CategoriesPage',		'Categories'			),
	array( 'DeadendPagesPage',		'Deadendpages'			),
	array( 'DisambiguationsPage',		'Disambiguations'		),
	array( 'DoubleRedirectsPage',		'DoubleRedirects'		),
	array( 'ListUsersPage',			'Listusers'			),
	array( 'ListredirectsPage', 'Listredirects' ),
	array( 'LonelyPagesPage',		'Lonelypages'			),
	array( 'LongPagesPage',			'Longpages'			),
	array( 'MostcategoriesPage',		'Mostcategories'		),
	array( 'MostimagesPage',		'Mostimages'			),
	array( 'MostlinkedCategoriesPage',	'Mostlinkedcategories'		),
	array( 'MostlinkedPage',		'Mostlinked'			),
	array( 'MostrevisionsPage',		'Mostrevisions'			),
	array( 'NewPagesPage',			'Newpages'			),
	array( 'ShortPagesPage',		'Shortpages'			),
	array( 'UncategorizedCategoriesPage',	'Uncategorizedcategories'	),
	array( 'UncategorizedPagesPage',	'Uncategorizedpages'		),
	array( 'UncategorizedImagesPage', 'Uncategorizedimages' ),
	array( 'UnusedCategoriesPage',		'Unusedcategories'		),
	array( 'UnusedimagesPage',		'Unusedimages'			),
	array( 'WantedCategoriesPage',		'Wantedcategories'		),
	array( 'WantedPagesPage',		'Wantedpages'			),
	array( 'UnwatchedPagesPage',		'Unwatchedpages'		),
	array( 'UnusedtemplatesPage', 'Unusedtemplates' ),
);
wfRunHooks( 'wgQueryPages', array( &$wgQueryPages ) );

global $wgDisableCounters;
if ( !$wgDisableCounters )
	$wgQueryPages[] = array( 'PopularPagesPage',		'Popularpages'		);


/**
 * This is a class for doing query pages; since they're almost all the same,
 * we factor out some of the functionality into a superclass, and let
 * subclasses derive from it.
 *
 */
class QueryPage {
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
	 * A mutator for $this->listoutput;
	 *
	 * @param bool $bool
	 */
	function setListoutput( $bool ) {
		$this->listoutput = $bool;
	}

	/**
	 * Subclasses return their name here. Make sure the name is also
	 * specified in SpecialPage.php and in Language.php as a language message
	 * param.
	 */
	function getName() {
		return '';
	}

	/**
	 * Return title object representing this page
	 *
	 * @return Title
	 */
	function getTitle() {
		return SpecialPage::getTitleFor( $this->getName() );
	}

	/**
	 * Subclasses return an SQL query here.
	 *
	 * Note that the query itself should return the following four columns:
	 * 'type' (your special page's name), 'namespace', 'title', and 'value'
	 * *in that order*. 'value' is used for sorting.
	 *
	 * These may be stored in the querycache table for expensive queries,
	 * and that cached data will be returned sometimes, so the presence of
	 * extra fields can't be relied upon. The cached 'value' column will be
	 * an integer; non-numeric values are useful only for sorting the initial
	 * query.
	 *
	 * Don't include an ORDER or LIMIT clause, this will be added.
	 */
	function getSQL() {
		return "SELECT 'sample' as type, 0 as namespace, 'Sample result' as title, 42 as value";
	}

	/**
	 * Override to sort by increasing values
	 */
	function sortDescending() {
		return true;
	}

	function getOrder() {
		return ' ORDER BY value ' .
			($this->sortDescending() ? 'DESC' : '');
	}

	/**
	 * Is this query expensive (for some definition of expensive)? Then we
	 * don't let it run in miser mode. $wgDisableQueryPages causes all query
	 * pages to be declared expensive. Some query pages are always expensive.
	 */
	function isExpensive( ) {
		global $wgDisableQueryPages;
		return $wgDisableQueryPages;
	}

	/**
	 * Whether or not the output of the page in question is retrived from
	 * the database cache.
	 *
	 * @return bool
	 */
	function isCached() {
		global $wgMiserMode;

		return $this->isExpensive() && $wgMiserMode;
	}

	/**
	 * Sometime we dont want to build rss / atom feeds.
	 */
	function isSyndicated() {
		return true;
	}

	/**
	 * Formats the results of the query for display. The skin is the current
	 * skin; you can use it for making links. The result is a single row of
	 * result data. You should be able to grab SQL results off of it.
	 * If the function return "false", the line output will be skipped.
	 */
	function formatResult( $skin, $result ) {
		return '';
	}

	/**
	 * The content returned by this function will be output before any result
	 */
	function getPageHeader( ) {
		return '';
	}

	/**
	 * If using extra form wheely-dealies, return a set of parameters here
	 * as an associative array. They will be encoded and added to the paging
	 * links (prev/next/lengths).
	 * @return array
	 */
	function linkParameters() {
		return array();
	}

	/**
	 * Some special pages (for example SpecialListusers) might not return the
	 * current object formatted, but return the previous one instead.
	 * Setting this to return true, will call one more time wfFormatResult to
	 * be sure that the very last result is formatted and shown.
	 */
	function tryLastResult( ) {
		return false;
	}

	/**
	 * Clear the cache and save new results
	 */
	function recache( $limit, $ignoreErrors = true ) {
		$fname = get_class($this) . '::recache';
		$dbw = wfGetDB( DB_MASTER );
		$dbr = wfGetDB( DB_SLAVE, array( $this->getName(), 'QueryPage::recache', 'vslow' ) );
		if ( !$dbw || !$dbr ) {
			return false;
		}

		$querycache = $dbr->tableName( 'querycache' );

		if ( $ignoreErrors ) {
			$ignoreW = $dbw->ignoreErrors( true );
			$ignoreR = $dbr->ignoreErrors( true );
		}

		# Clear out any old cached data
		$dbw->delete( 'querycache', array( 'qc_type' => $this->getName() ), $fname );
		# Do query
		$sql = $this->getSQL() . $this->getOrder();
		if ($limit !== false)
			$sql = $dbr->limitResult($sql, $limit, 0);
		$res = $dbr->query($sql, $fname);
		$num = false;
		if ( $res ) {
			$num = $dbr->numRows( $res );
			# Fetch results
			$insertSql = "INSERT INTO $querycache (qc_type,qc_namespace,qc_title,qc_value) VALUES ";
			$first = true;
			while ( $res && $row = $dbr->fetchObject( $res ) ) {
				if ( $first ) {
					$first = false;
				} else {
					$insertSql .= ',';
				}
				if ( isset( $row->value ) ) {
					$value = $row->value;
				} else {
					$value = '';
				}

				$insertSql .= '(' .
					$dbw->addQuotes( $row->type ) . ',' .
					$dbw->addQuotes( $row->namespace ) . ',' .
					$dbw->addQuotes( $row->title ) . ',' .
					$dbw->addQuotes( $value ) . ')';
			}

			# Save results into the querycache table on the master
			if ( !$first ) {
				if ( !$dbw->query( $insertSql, $fname ) ) {
					// Set result to false to indicate error
					$dbr->freeResult( $res );
					$res = false;
				}
			}
			if ( $res ) {
				$dbr->freeResult( $res );
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
	 * This is the actual workhorse. It does everything needed to make a
	 * real, honest-to-gosh query page.
	 *
	 * @param $offset database query offset
	 * @param $limit database query limit
	 * @param $shownavigation show navigation like "next 200"?
	 */
	function doQuery( $offset, $limit, $shownavigation=true ) {
		global $wgUser, $wgOut, $wgLang, $wgContLang;

		$this->offset = $offset;
		$this->limit = $limit;

		$sname = $this->getName();
		$fname = get_class($this) . '::doQuery';
		$dbr = wfGetDB( DB_SLAVE );

		$wgOut->setSyndicated( $this->isSyndicated() );

		if ( !$this->isCached() ) {
			$sql = $this->getSQL();
		} else {
			# Get the cached result
			$querycache = $dbr->tableName( 'querycache' );
			$type = $dbr->strencode( $sname );
			$sql =
				"SELECT qc_type as type, qc_namespace as namespace,qc_title as title, qc_value as value
				 FROM $querycache WHERE qc_type='$type'";

			if( !$this->listoutput ) {

				# Fetch the timestamp of this update
				$tRes = $dbr->select( 'querycache_info', array( 'qci_timestamp' ), array( 'qci_type' => $type ), $fname );
				$tRow = $dbr->fetchObject( $tRes );
				
				if( $tRow ) {
					$updated = $wgLang->timeAndDate( $tRow->qci_timestamp, true, true );
					$cacheNotice = wfMsg( 'perfcachedts', $updated );
					$wgOut->addMeta( 'Data-Cache-Time', $tRow->qci_timestamp );
					$wgOut->addScript( '<script language="JavaScript">var dataCacheTime = \'' . $tRow->qci_timestamp . '\';</script>' );
				} else {
					$cacheNotice = wfMsg( 'perfcached' );
				}
	
				$wgOut->addWikiText( $cacheNotice );
				
				# If updates on this page have been disabled, let the user know
				# that the data set won't be refreshed for now
				global $wgDisableQueryPageUpdate;
				if( is_array( $wgDisableQueryPageUpdate ) && in_array( $this->getName(), $wgDisableQueryPageUpdate ) ) {
					$wgOut->addWikiText( wfMsg( 'querypage-no-updates' ) );
				}
				
			}

		}

		$sql .= $this->getOrder();
		$sql = $dbr->limitResult($sql, $limit, $offset);
		$res = $dbr->query( $sql );
		$num = $dbr->numRows($res);

		$this->preprocessResults( $dbr, $res );

		$sk = $wgUser->getSkin( );

		if($shownavigation) {
			$wgOut->addHTML( $this->getPageHeader() );
			$top = wfShowingResults( $offset, $num);
			$wgOut->addHTML( "<p>{$top}\n" );

			# often disable 'next' link when we reach the end
			$atend = $num < $limit;

			$sl = wfViewPrevNext( $offset, $limit ,
				$wgContLang->specialPage( $sname ),
				wfArrayToCGI( $this->linkParameters() ), $atend );
			$wgOut->addHTML( "<br />{$sl}</p>\n" );
		}
		if ( $num > 0 ) {
			$s = array();
			if ( ! $this->listoutput )
				$s[] = $this->openList( $offset );

			# Only read at most $num rows, because $res may contain the whole 1000
			for ( $i = 0; $i < $num && $obj = $dbr->fetchObject( $res ); $i++ ) {
				$format = $this->formatResult( $sk, $obj );
				if ( $format ) {
					$attr = ( isset ( $obj->usepatrol ) && $obj->usepatrol &&
										$obj->patrolled == 0 ) ? ' class="not-patrolled"' : '';
					$s[] = $this->listoutput ? $format : "<li{$attr}>{$format}</li>\n";
				}
			}

			if($this->tryLastResult()) {
				// flush the very last result
				$obj = null;
				$format = $this->formatResult( $sk, $obj );
				if( $format ) {
					$attr = ( isset ( $obj->usepatrol ) && $obj->usepatrol &&
										$obj->patrolled == 0 ) ? ' class="not-patrolled"' : '';
					$s[] = "<li{$attr}>{$format}</li>\n";
				}
			}

			$dbr->freeResult( $res );
			if ( ! $this->listoutput )
				$s[] = $this->closeList();
			$str = $this->listoutput ? $wgContLang->listToText( $s ) : implode( '', $s );
			$wgOut->addHTML( $str );
		}
		if($shownavigation) {
			$wgOut->addHTML( "<p>{$sl}</p>\n" );
		}
		return $num;
	}
	
	function openList( $offset ) {
		return "<ol start='" . ( $offset + 1 ) . "' class='special'>";
	}
	
	function closeList() {
		return '</ol>';
	}

	/**
	 * Do any necessary preprocessing of the result object.
	 * You should pass this by reference: &$db , &$res  [although probably no longer necessary in PHP5]
	 */
	function preprocessResults( &$db, &$res ) {}

	/**
	 * Similar to above, but packaging in a syndicated feed instead of a web page
	 */
	function doFeed( $class = '', $limit = 50 ) {
		global $wgFeedClasses;

		if( isset($wgFeedClasses[$class]) ) {
			$feed = new $wgFeedClasses[$class](
				$this->feedTitle(),
				$this->feedDesc(),
				$this->feedUrl() );
			$feed->outHeader();

			$dbr = wfGetDB( DB_SLAVE );
			$sql = $this->getSQL() . $this->getOrder();
			$sql = $dbr->limitResult( $sql, $limit, 0 );
			$res = $dbr->query( $sql, 'QueryPage::doFeed' );
			while( $obj = $dbr->fetchObject( $res ) ) {
				$item = $this->feedResult( $obj );
				if( $item ) $feed->outItem( $item );
			}
			$dbr->freeResult( $res );

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
		if( !isset( $row->title ) ) {
			return NULL;
		}
		$title = Title::MakeTitle( intval( $row->namespace ), $row->title );
		if( $title ) {
			$date = isset( $row->timestamp ) ? $row->timestamp : '';
			$comments = '';
			if( $title ) {
				$talkpage = $title->getTalkPage();
				$comments = $talkpage->getFullURL();
			}

			return new FeedItem(
				$title->getPrefixedText(),
				$this->feedItemDesc( $row ),
				$title->getFullURL(),
				$date,
				$this->feedItemAuthor( $row ),
				$comments);
		} else {
			return NULL;
		}
	}

	function feedItemDesc( $row ) {
		return isset( $row->comment ) ? htmlspecialchars( $row->comment ) : '';
	}

	function feedItemAuthor( $row ) {
		return isset( $row->user_text ) ? $row->user_text : '';
	}

	function feedTitle() {
		global $wgContLanguageCode, $wgSitename;
		$page = SpecialPage::getPage( $this->getName() );
		$desc = $page->getDescription();
		return "$wgSitename - $desc [$wgContLanguageCode]";
	}

	function feedDesc() {
		return wfMsg( 'tagline' );
	}

	function feedUrl() {
		$title = SpecialPage::getTitleFor( $this->getName() );
		return $title->getFullURL();
	}
}

/**
 * This is a subclass for very simple queries that are just looking for page
 * titles that match some criteria. It formats each result item as a link to
 * that page.
 *
 */
class PageQueryPage extends QueryPage {

	function formatResult( $skin, $result ) {
		global $wgContLang;
		$nt = Title::makeTitle( $result->namespace, $result->title );
		return $skin->makeKnownLinkObj( $nt, htmlspecialchars( $wgContLang->convert( $nt->getPrefixedText() ) ) );
	}
}

?>
