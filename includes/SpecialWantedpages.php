<?php

include_once ( "QueryPage.php" ) ;

class WantedPagesPage extends QueryPage {

	function getName() {
		return "Wantedpages";
	}

	function isExpensive() {
		return 1;
	}

	function getSQL( $offset, $limit ) {
		return "SELECT bl_to, COUNT( DISTINCT bl_from ) as nlinks " .
		  "FROM brokenlinks GROUP BY bl_to HAVING nlinks > 1 " .
		  "ORDER BY nlinks DESC LIMIT {$offset}, {$limit}";
	}

	function formatResult( $skin, $result ) {
		global $wgLang;

		$nt = Title::newFromDBkey( $result->bl_to );

		$plink = $skin->makeBrokenLink( $nt->getPrefixedText(), "" );
		$nl = wfMsg( "nlinks", $result->nlinks );
		$nlink = $skin->makeKnownLink( $wgLang->specialPage( "Whatlinkshere" ), $nl,
		  "target=" . $nt->getPrefixedURL() );

		return "{$plink} ({$nlink})";
	}
}

function wfSpecialWantedpages()
{
	list( $limit, $offset ) = wfCheckLimits();

	$wpp = new WantedPagesPage();

	$wpp->doQuery( $offset, $limit );
}

?>
