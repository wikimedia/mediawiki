<?php

require_once( "QueryPage.php" );

class DeadendPagesPage extends PageQueryPage {

    function getName( ) {
	return "Deadendpages";
    }

    # LEFT JOIN is expensive
    
    function isExpensive( ) {
	return 1;
    }
    
    function getSQL( $offset, $limit ) {
	return "SELECT cur_title as title, 0 as value " . 
	  "FROM cur LEFT JOIN links ON cur_id = l_from " .
	  "WHERE l_from IS NULL " .
	  "AND cur_namespace = 0 " .
	  "AND cur_is_redirect = 0";
    }
}

function wfSpecialDeadendpages() {
    
    list( $limit, $offset ) = wfCheckLimits();

    $depp = new DeadendPagesPage();
    
    return $depp->doQuery( $offset, $limit );
}

?>
