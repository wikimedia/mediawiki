<?php

include_once( "QueryPage.php" );

class DeadendPagesPage extends PageQueryPage {

    function getName( ) {
	return "Deadendpages";
    }

    # LEFT JOIN is expensive
    
    function isExpensive( ) {
	return 1;
    }
    
    function getSQL( $offset, $limit ) {
	return "SELECT cur_title " . 
	  "FROM cur LEFT JOIN links ON cur_title = l_from " .
	  "WHERE l_from IS NULL " .
	  "AND cur_namespace = 0 " .
	  "ORDER BY cur_title " . 
	  "LIMIT {$offset}, {$limit}";
    }
}

function wfSpecialDeadendpages() {
    
    list( $limit, $offset ) = wfCheckLimits();

    $depp = new DeadendPagesPage();
    
    return $depp->doQuery( $offset, $limit );
}

?>
