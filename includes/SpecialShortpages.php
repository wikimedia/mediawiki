<?php

include_once("QueryPage.php");

class ShortPagesPage extends QueryPage {

	function getName() {
		return "Shortpages";
	}

	function isExpensive() {
		return 1;
	}

	function getSQL( $offset, $limit ) {
		return "SELECT cur_title, LENGTH(cur_text) AS len FROM cur " .
		  "WHERE cur_namespace=0 AND cur_is_redirect=0 ORDER BY len " .
		  "LIMIT {$offset}, {$limit}";
	}

	function formatResult( $skin, $result ) {
		$nb = wfMsg( "nbytes", $result->len );
		$link = $skin->makeKnownLink( $result->cur_title, "" );
		return "{$link} ({$nb})";
	}
}

function wfSpecialShortpages()
{
	list( $limit, $offset ) = wfCheckLimits();

	$spp = new ShortPagesPage();

	return $spp->doQuery( $offset, $limit );
}

?>
