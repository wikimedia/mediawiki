<?php

include_once( "QueryPage.php" );

class PopularPagesPage extends QueryPage {

	function getName() {
		return "Popularpages";
	}

	function isExpensive() {
		return 1;
	}

	function getSQL( $offset, $limit ) {
		return "SELECT cur_title, cur_counter FROM cur " .
		  "WHERE cur_namespace=0 AND cur_is_redirect=0 ORDER BY " .
		  "cur_counter DESC LIMIT {$offset}, {$limit}";
	}

	function formatResult( $skin, $result ) {
		$link = $skin->makeKnownLink( $result->cur_title, "" );
		$nv = wfMsg( "nviews", $result->cur_counter );
		return "{$link} ({$nv})";
	}
}

function wfSpecialPopularpages()
{
    list( $limit, $offset ) = wfCheckLimits();
    
    $ppp = new PopularPagesPage();
    
    return $ppp->doQuery( $offset, $limit );
}

?>
