<?php

include_once ( "LogPage.php" ) ;
include_once ( "Feed.php" );

# This is a class for doing query pages; since they're almost all the same,
# we factor out some of the functionality into a superclass, and let
# subclasses derive from it.

class QueryPage {
	# Subclasses return their name here. Make sure the name is also
	# specified in Language.php, both in the $wgValidSpecialPagesEn
	# variable, and as a language message param.

	function getName() {
		return "";
	}

	# Subclasses return a SQL query here.

	function getSQL( $offset, $limit ) {
		return "";
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
		global $wgUser, $wgOut, $wgLang, $wgMiserMode;

		$sname = $this->getName();
		$fname = get_class($this) . "::doQuery";

		$wgOut->setSyndicated( true );
		
		if ( $this->isExpensive( ) ) {
			$vsp = $wgLang->getValidSpecialPages();
			$logpage = new LogPage( "!" . $vsp[$sname] );
			$logpage->mUpdateRecentChanges = false;

			if ( $wgMiserMode ) {
				$logpage->showAsDisabledPage();
				return;
			}
		}

		$sql = $this->getSQL( $offset, $limit );

		$res = wfQuery( $sql, DB_READ, $fname );

		$sk = $wgUser->getSkin( );

		$top = wfShowingResults( $offset, $limit );
		$wgOut->addHTML( "<p>{$top}\n" );

		$sl = wfViewPrevNext( $offset, $limit, $wgLang->specialPage( $sname ) );
		$wgOut->addHTML( "<br>{$sl}\n" );

		$s = "<ol start=" . ( $offset + 1 ) . ">";
		while ( $obj = wfFetchObject( $res ) ) {
			$format = $this->formatResult( $sk, $obj );
			$s .= "<li>{$format}</li>\n";
		}
		wfFreeResult( $res );
		$s .= "</ol>";
		$wgOut->addHTML( $s );
		$wgOut->addHTML( "<p>{$sl}\n" );

		# Saving cache

		if ( $this->isExpensive() && $offset == 0 && $limit >= 50 ) {
			$logpage->replaceContent( $s );
		}
	}
	
	# Similar to above, but packaging in a syndicated feed instead of a web page
	function doFeed( $class = "" ) {
		global $wgFeedClasses;
		global $wgOut, $wgLanguageCode, $wgLang;
		if( $class == "rss" ) {
			$wgOut->disable();
			
			$feed = new RSSFeed(
				$this->feedTitle(),
				$this->feedDesc(),
				$this->feedUrl() );
			$feed->outHeader();
			
			$sql = $this->getSQL( 0, 50 );
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
		if( isset( $row->cur_title ) ) {
			$title = Title::MakeTitle( $row->cur_namespace, $row->cur_title );
		} elseif( isset( $row->old_title ) ) {
			$title = Title::MakeTitle( $row->old_namespace, $row->old_title );
		} elseif( isset( $row->rc_title ) ) {
			$title = Title::MakeTitle( $row->rc_namespace, $row->rc_title );
		} else {
			return NULL;
		}
		if( $title ) {
			$date = "";
			if( isset( $row->cur_timestamp ) ) {
				$date = $row->cur_timestamp;
			} elseif( isset( $row->old_timestamp ) ) {
				$date = $row->old_timestamp;
			} elseif( isset( $row->rc_cur_timestamp ) ) {
				$date = $row->rc_cur_timestamp;
			}
			return new FeedItem(
				$title->getText(),
				$this->feedItemDesc( $row ),
				$title->getFullURL(),
				$date,
				$this->feedItemAuthor( $row ) );
		} else {
			return NULL;
		}
	}
	
	function feedItemDesc( $row ) {
		$text = "";
		if( isset( $row->cur_comment ) ) {
			$text = $row->cur_comment;
		} elseif( isset( $row->old_comment ) ) {
			$text = $row->old_comment;
		} elseif( isset( $row->rc_comment ) ) {
			$text = $row->rc_comment;
		}
		$text = htmlspecialchars( $text );
		
		if( isset( $row->cur_text ) ) {
			$text = "<p>" . htmlspecialchars( wfMsg( "summary" ) ) . ": " . $text . "</p>\n<hr />\n<div>" .
				nl2br( $row->cur_text ) . "</div>";;
		}
		return $text;
	}
	
	function feedItemAuthor( $row ) {
		$fields = array( "cur_user_text", "old_user_text", "rc_user_text" );
		foreach( $fields as $field ) {
			if( isset( $row->$field ) ) return $row->field;
		}
		return "";
	}
	
	function feedTitle() {
		global $wgLanguageCode, $wgSitename, $wgLang;
		$pages = $wgLang->getValidSpecialPages();
		$title = $pages[$this->getName()];
		return "$wgSitename - $title [$wgLanguageCode]";
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
		return $skin->makeKnownLink( $result->cur_title, "" );
	}
}

?>
