<?php

require_once ( "Feed.php" );

# This is a class for doing query pages; since they're almost all the same,
# we factor out some of the functionality into a superclass, and let
# subclasses derive from it.

class QueryPage {
	# Subclasses return their name here. Make sure the name is also
	# specified in SpecialPage.php and in Language.php as a language message param.

	function getName() {
		return "";
	}

	# Subclasses return an SQL query here.
	#
	# Note that the query itself should return the following four columns:
	# 'type' (your special page's name), 'namespace', 'title', and 'value'
	# *in that order*. 'value' is used for sorting.
	#
	# These may be stored in the querycache table for expensive queries,
	# and that cached data will be returned sometimes, so the presence of
	# extra fields can't be relied upon. The cached 'value' column will be
	# an integer; non-numeric values are useful only for sorting the initial
	# query.
	#
	# Don't include an ORDER or LIMIT clause, this will be added.

	function getSQL() {
		return "SELECT 'sample' as type, 0 as namespace, 'Sample result' as title, 42 as value";
	}
	
	# Override to sort by increasing values
	function sortDescending() {
		return true;
	}

	# Don't override this unless you're darn sure.
	function getOrderLimit( $offset, $limit ) {
		return " ORDER BY value " .
			($this->sortDescending() ? "DESC" : "")
			. wfLimitResult($limit,$offset);
	}

	# Is this query expensive (for some definition of expensive)? Then we
	# don't let it run in miser mode. $wgDisableQueryPages causes all query
	# pages to be declared expensive. Some query pages are always expensive.
	function isExpensive( ) {
		global $wgDisableQueryPages;
		return $wgDisableQueryPages;
	}

	# Formats the results of the query for display. The skin is the current
	# skin; you can use it for making links. The result is a single row of
	# result data. You should be able to grab SQL results off of it.

	function formatResult( $skin, $result ) {
		return "";
	}
	
	# This is the actual workhorse. It does everything needed to make a
	# real, honest-to-gosh query page.
	
	function doQuery( $offset, $limit ) {
		global $wgUser, $wgOut, $wgLang, $wgRequest;
		global $wgMiserMode;

		$sname = $this->getName();
		$fname = get_class($this) . "::doQuery";
		$sql = $this->getSQL( $offset, $limit );

		$wgOut->setSyndicated( true );
		
		if ( $this->isExpensive() ) {
			$type = wfStrencode( $sname );
			$recache = $wgRequest->getBool( "recache" );
			if( $recache && !$wgMiserMode)  {
				# Clear out any old cached data
				$res = wfQuery( "DELETE FROM querycache WHERE qc_type='$type'", DB_WRITE, $fname );

				# Save results into the querycache table
				$maxstored = 1000;
				$res = wfQuery(
					"INSERT INTO querycache(qc_type,qc_namespace,qc_title,qc_value) " .
					$this->getSQL() .
					$this->getOrderLimit( 0, $maxstored ),
					DB_WRITE, $fname );
			}
			if( $wgMiserMode || $recache ) {
				$sql =
					"SELECT qc_type as type, qc_namespace as namespace,qc_title as title, qc_value as value
					 FROM querycache WHERE qc_type='$type'";
			}
			if( $wgMiserMode ) {
				$wgOut->addWikiText( wfMsg( "perfcached" ) );
			}
		}

		$res = wfQuery( $sql . $this->getOrderLimit( $offset, $limit ), DB_READ, $fname );
		
		$num = wfNumRows($res);
		
		$sk = $wgUser->getSkin( );

		$top = wfShowingResults( $offset, $num);
		$wgOut->addHTML( "<p>{$top}\n" );

		# often disable 'next' link when we reach the end
		if($num < $limit) { $atend = true; } else { $atend = false; }
		
		$sl = wfViewPrevNext( $offset, $limit , $wgLang->specialPage( $sname ), "" ,$atend );
		$wgOut->addHTML( "<br />{$sl}</p>\n" );

		$s = "<ol start='" . ( $offset + 1 ) . "'>";
		while ( $obj = wfFetchObject( $res ) ) {
			$format = $this->formatResult( $sk, $obj );
			$s .= "<li>{$format}</li>\n";
		}
		wfFreeResult( $res );
		$s .= "</ol>";
		$wgOut->addHTML( $s );
		$wgOut->addHTML( "<p>{$sl}</p>\n" );
	}
	
	# Similar to above, but packaging in a syndicated feed instead of a web page
	function doFeed( $class = "" ) {
		global $wgFeedClasses;
		global $wgOut, $wgLanguageCode, $wgLang;
		if( isset($wgFeedClasses[$class]) ) {
			$feed = new $wgFeedClasses[$class](
				$this->feedTitle(),
				$this->feedDesc(),
				$this->feedUrl() );
			$feed->outHeader();
			
			$sql = $this->getSQL() . $this->getOrderLimit( 0, 50 );
			$res = wfQuery( $sql, DB_READ, "QueryPage::doFeed" );
			while( $obj = wfFetchObject( $res ) ) {
				$item = $this->feedResult( $obj );
				if( $item ) $feed->outItem( $item );
			}
			wfFreeResult( $res );

			$feed->outFooter();
			return true;
		} else {
			return false;
		}
	}

	# Override for custom handling. If the titles/links are ok, just do feedItemDesc()
	function feedResult( $row ) {
		if( !isset( $row->title ) ) {
			return NULL;
		}
		$title = Title::MakeTitle( IntVal( $row->namespace ), $row->title );
		if( $title ) {
			if( isset( $row->timestamp ) ) {
				$date = $row->timestamp;
			} else {
				$date = "";
			}
			
			$comments = "";
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
		$text = "";
		if( isset( $row->comment ) ) {
			$text = htmlspecialchars( $row->comment );
		} else {
			$text = "";
		}
		
		if( isset( $row->text ) ) {
			$text = "<p>" . htmlspecialchars( wfMsg( "summary" ) ) . ": " . $text . "</p>\n<hr />\n<div>" .
				nl2br( htmlspecialchars( $row->text ) ) . "</div>";;
		}
		return $text;
	}
	
	function feedItemAuthor( $row ) {
		if( isset( $row->user_text ) ) {
			return $row->user_text;
		} else {
			return "";
		}
	}
	
	function feedTitle() {
		global $wgLanguageCode, $wgSitename, $wgLang;
		$page = SpecialPage::getPage( $this->getName() );
		$desc = $page->getDescription();
		return "$wgSitename - $desc [$wgLanguageCode]";
	}
	
	function feedDesc() {
		return wfMsg( "fromwikipedia" );
	}
	
	function feedUrl() {
		global $wgLang;
		$title = Title::MakeTitle( NS_SPECIAL, $this->getName() );
		return $title->getFullURL();
	}
}

# This is a subclass for very simple queries that are just looking for page
# titles that match some criteria. It formats each result item as a link to
# that page.

class PageQueryPage extends QueryPage {

	function formatResult( $skin, $result ) {
		$nt = Title::makeTitle( $result->namespace, $result->title );
		return $skin->makeKnownLinkObj( $nt, "" );
	}
}

?>
