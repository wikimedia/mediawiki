<?php

require_once( "QueryPage.php" );

class LonelyPagesPage extends PageQueryPage {
	
	function getName() {
		return "Lonelypages";
	}
	
	function sortDescending() {
		return false;
	}

	function isExpensive() {
		return true;
	}
	
	function getSQL() {
		return "SELECT 'Lonelypages' as type, cur_namespace AS namespace, cur_title AS title, cur_title AS value " .
			"FROM cur LEFT JOIN links ON cur_id=l_to ".
			"WHERE l_to IS NULL AND cur_namespace=0 AND cur_is_redirect=0";
	}
}

function wfSpecialLonelypages() {
	list( $limit, $offset ) = wfCheckLimits();
	
	$lpp = new LonelyPagesPage();
	
	return $lpp->doQuery( $offset, $limit );
}

?>
