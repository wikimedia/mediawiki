<?php

include_once ( "LogPage.php" ) ;

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
