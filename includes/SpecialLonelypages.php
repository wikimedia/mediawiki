<?php

include_once( "QueryPage.php" );

class LonelyPagesPage extends PageQueryPage {

    function getName() {
	return "Lonelypages";
    }
    
    function isExpensive() {
	return 1;
    }
    
    function getSQL( $offset, $limit ) {
	
	return "SELECT cur_title FROM cur LEFT JOIN links ON " .
	  "cur_id=l_to WHERE l_to IS NULL AND cur_namespace=0 AND " .
	  "cur_is_redirect=0 ORDER BY cur_title LIMIT {$offset}, {$limit}";
    }
}

function wfSpecialLonelypages()
{
    list( $limit, $offset ) = wfCheckLimits();
    
    $lpp = new LonelyPagesPage();
    
    return $lpp->doQuery( $offset, $limit );
}

?>
