<?php

include_once( "QueryPage.php" );

class LongPagesPage extends QueryPage {

	function getName() {
		return "Longpages";
	}

	function isExpensive() {
		return 1;
	}

	function getSQL( $offset, $limit ) {
		return "SELECT cur_title, LENGTH(cur_text) AS len FROM cur " .
		  "WHERE cur_namespace=0 AND cur_is_redirect=0 ORDER BY len DESC " .
		  "LIMIT {$offset}, {$limit}";
	}

	function formatResult( $skin, $result ) {
		$nb = wfMsg( "nbytes", $result->len );
		$link = $skin->makeKnownLink( $result->cur_title, "" );
		return "{$link} ({$nb})";
	}
}

function wfSpecialLongpages()
{
    list( $limit, $offset ) = wfCheckLimits();

    $lpp = new LongPagesPage( );
    
    $lpp->doQuery( $offset, $limit );
}

?>
