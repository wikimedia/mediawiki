<?php

require_once( "QueryPage.php" );

class UncategorizedPagesPage extends PageQueryPage {

	function getName() {
		return "Uncategorizedpages";
	}

	function sortDescending() {
		return false;
	}

	function isExpensive() {
		return true;
	}

	function getSQL() {
		return "SELECT 'Uncategorizedpages' as type, cur_namespace AS namespace, cur_title AS title, 0 AS value " .
			"FROM cur LEFT JOIN categorylinks ON cur_id=cl_from ".
			"WHERE cl_from IS NULL AND cur_namespace=0 AND cur_is_redirect=0";
	}
}

function wfSpecialUncategorizedpages() {
	list( $limit, $offset ) = wfCheckLimits();

	$lpp = new UncategorizedPagesPage();

	return $lpp->doQuery( $offset, $limit );
}

?>
