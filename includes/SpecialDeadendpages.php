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

	function sortDescending() {
		return false;
	}
    
	function getSQL() {
		return "SELECT 'Deadendpages' as type, cur_namespace AS namespace, cur_title as title, cur_title AS value " . 
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
