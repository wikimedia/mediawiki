<?php

require_once("QueryPage.php");

class CategoriesPage extends QueryPage {

	function getName() {
		return "Categories";
	}

	function isExpensive() {
		return false;
	}

	function getSQL() {
		$NScat = Namespace::getCategory();
		return "SELECT DISTINCT 'Categories' as type, 
				{$NScat} as namespace,
				cl_to as title,
				1 as value
			   FROM categorylinks";
	}
	
	function sortDescending() {
		return false;
	}

	function formatResult( $skin, $result ) {
		global $wgLang;
		return $skin->makeLink( $wgLang->getNsText( NS_CATEGORY ).":".$result->title, $result->title );
	}
}

function wfSpecialCategories()
{
	list( $limit, $offset ) = wfCheckLimits();

	$cap = new CategoriesPage();

	return $cap->doQuery( $offset, $limit );
}

?>
