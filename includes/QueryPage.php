<?php
/**
 * Contain a class for special pages
 * @package MediaWiki
 */

/**
 *
 */
require_once ( 'Feed.php' );

/**
 * This is a class for doing query pages; since they're almost all the same,
 * we factor out some of the functionality into a superclass, and let
 * subclasses derive from it.
 *
 * @package MediaWiki
 */
class QueryPage {

	/**
	 * Subclasses return their name here. Make sure the name is also
	 * specified in SpecialPage.php and in Language.php as a language message
	 * param.
	 */
	function getName() {
		return '';
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
	 * Sometime we dont want to build rss / atom feeds.
	 */
	function isSyndicated() {
		return true;
	}

	/**
	 * Formats the results of the query for display. The skin is the current
	 * skin; you can use it for making links. The result is a single row of
	 * result data. You should be able to grab SQL results off of it.
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
	 * This is the actual workhorse. It does everything needed to make a
	 * real, honest-to-gosh query page.
	 *
	 * @param $offset database query offset
	 * @param $limit database query limit
	 */
	function doQuery( $offset, $limit ) {
		global $wgUser, $wgOut, $wgLang, $wgRequest;
		global $wgMiserMode;

		$sname = $this->getName();
		$fname = get_class($this) . '::doQuery';
		$sql = $this->getSQL();
		$dbr =& wfGetDB( DB_SLAVE );
		$dbw =& wfGetDB( DB_MASTER );
		$querycache = $dbr->tableName( 'querycache' );

		$wgOut->setSyndicated( $this->isSyndicated() );
		$res = false;

		if ( $this->isExpensive() ) {
			$recache = $wgRequest->getBool( 'recache' );
			if( $recache ) {
				# Clear out any old cached data
				$dbw->delete( 'querycache', array( 'qc_type' => $sname ), $fname );

				# Do query on the (possibly out of date) slave server
				$maxstored = 1000;
				$res = $dbr->query( $sql . $this->getOrder() . $dbr->limitResult( $maxstored,0 ), $fname );

				# Fetch results
				$insertSql = "INSERT INTO $querycache (qc_type,qc_namespace,qc_title,qc_value) VALUES ";
				$first = true;
				while ( $row = $dbr->fetchObject( $res ) ) {
					if ( $first ) {
						$first = false;
					} else {
						$insertSql .= ',';
					}
					$insertSql .= '(' .
						$dbw->addQuotes( $row->type ) . ',' .
						$dbw->addQuotes( $row->namespace ) . ',' .
						$dbw->addQuotes( $row->title ) . ',' .
						$dbw->addQuotes( $row->value ) . ')';
				}

				# Save results into the querycache table on the master
				$dbw->query( $insertSql, $fname );

				# Set result pointer to allow reading for display
				$numRows = $dbr->numRows( $res );
				if ( $numRows <= $offset ) {
					$num = 0;
				} else {
					$dbr->dataSeek( $res, $offset );
					$num = max( $limit, $numRows - $offset );
				}
			}
			if( $wgMiserMode || $recache ) {
				$type = $dbr->strencode( $sname );
				$sql =
					"SELECT qc_type as type, qc_namespace as namespace,qc_title as title, qc_value as value
					 FROM $querycache WHERE qc_type='$type'";
			}
			if( $wgMiserMode ) {
				$wgOut->addWikiText( wfMsg( 'perfcached' ) );
			}
		}
		if ( $res === false ) {
			$res = $dbr->query( $sql . $this->getOrder() .
					    $dbr->limitResult( $limit,$offset ), $fname );
			$num = $dbr->numRows($res);
		}


		$sk = $wgUser->getSkin( );

		$wgOut->addHTML( $this->getPageHeader() );

		$top = wfShowingResults( $offset, $num);
		$wgOut->addHTML( "<p>{$top}\n" );

		# often disable 'next' link when we reach the end
		if($num < $limit) { $atend = true; } else { $atend = false; }

		$sl = wfViewPrevNext( $offset, $limit , $wgLang->specialPage( $sname ), "" ,$atend );
		$wgOut->addHTML( "<br />{$sl}</p>\n" );

		$s = "<ol start='" . ( $offset + 1 ) . "' class='special'>";
		# Only read at most $num rows, because $res may contain the whole 1000
		for ( $i = 0; $i < $num && $obj = $dbr->fetchObject( $res ); $i++ ) {
			$format = $this->formatResult( $sk, $obj );
			$attr = ( isset ( $obj->usepatrol ) && $obj->usepatrol &&
			          $obj->patrolled == 0 ) ? ' class="not_patrolled"' : '';
			$s .= "<li{$attr}>{$format}</li>\n";
		}
		$dbr->freeResult( $res );
		$s .= '</ol>';
		$wgOut->addHTML( $s );
		$wgOut->addHTML( "<p>{$sl}</p>\n" );
	}

	/**
	 * Similar to above, but packaging in a syndicated feed instead of a web page
	 */
	function doFeed( $class = '' ) {
		global $wgFeedClasses;
		global $wgOut, $wgLanguageCode, $wgLang;
		if( isset($wgFeedClasses[$class]) ) {
			$feed = new $wgFeedClasses[$class](
				$this->feedTitle(),
				$this->feedDesc(),
				$this->feedUrl() );
			$feed->outHeader();

			$dbr =& wfGetDB( DB_SLAVE );
			$sql = $this->getSQL() . $this->getOrder().$dbr->limitResult( 50, 0 );
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
		$title = Title::MakeTitle( IntVal( $row->namespace ), $row->title );
		if( $title ) {
			if( isset( $row->timestamp ) ) {
				$date = $row->timestamp;
			} else {
				$date = '';
			}

			$comments = '';
			if( $title ) {
				$talkpage = $title->getTalkPage();
				$comments = $talkpage->getFullURL();
			}

			return new FeedItem(
				$title->getText(),
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
		$text = '';
		if( isset( $row->comment ) ) {
			$text = htmlspecialchars( $row->comment );
		} else {
			$text = '';
		}

		if( isset( $row->text ) ) {
			$text = '<p>' . htmlspecialchars( wfMsg( 'summary' ) ) . ': ' . $text . "</p>\n<hr />\n<div>" .
				nl2br( htmlspecialchars( $row->text ) ) . "</div>";;
		}
		return $text;
	}

	function feedItemAuthor( $row ) {
		if( isset( $row->user_text ) ) {
			return $row->user_text;
		} else {
			return '';
		}
	}

	function feedTitle() {
		global $wgLanguageCode, $wgSitename, $wgLang;
		$page = SpecialPage::getPage( $this->getName() );
		$desc = $page->getDescription();
		return "$wgSitename - $desc [$wgLanguageCode]";
	}

	function feedDesc() {
		return wfMsg( 'tagline' );
	}

	function feedUrl() {
		global $wgLang;
		$title = Title::MakeTitle( NS_SPECIAL, $this->getName() );
		return $title->getFullURL();
	}
}

/**
 * This is a subclass for very simple queries that are just looking for page
 * titles that match some criteria. It formats each result item as a link to
 * that page.
 *
 * @package MediaWiki
 */
class PageQueryPage extends QueryPage {

	function formatResult( $skin, $result ) {
		global $wgContLang;
		$nt = Title::makeTitle( $result->namespace, $result->title );
		return $skin->makeKnownLinkObj( $nt, $wgContLang->convert( $result->title ) );
	}
}

?>
