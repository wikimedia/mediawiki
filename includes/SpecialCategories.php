<?php
/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/**
 *
 */
require_once("QueryPage.php");

/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */
class CategoriesPage extends QueryPage {

	function getName() {
		return "Categories";
	}

	function isExpensive() {
		return false;
	}

	function getSQL() {
		$NScat = NS_CATEGORY;
		$dbr =& wfGetDB( DB_SLAVE );
		$categorylinks = $dbr->tableName( 'categorylinks' );
		return "SELECT DISTINCT 'Categories' as type, 
				{$NScat} as namespace,
				cl_to as title,
				1 as value
			   FROM $categorylinks";
	}
	
	function sortDescending() {
		return false;
	}

	function formatResult( $skin, $result ) {
		global $wgLang;
		$title = Title::makeTitle( NS_CATEGORY, $result->title );
		return $skin->makeLinkObj( $title, $title->getText() );
	}
}

/**
 *
 */
function wfSpecialCategories() {
	list( $limit, $offset ) = wfCheckLimits();

	$cap = new CategoriesPage();

	return $cap->doQuery( $offset, $limit );
}

?>
