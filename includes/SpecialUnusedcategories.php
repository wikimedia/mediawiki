<?php
/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/** */
require_once('QueryPage.php');

/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */
class UnusedCategoriesPage extends QueryPage {

	function getName() {
		return 'Unusedcategories';
	}

	function getPageHeader() {
		return '<p>'.wfMsg('unusedcategoriestext')."</p><br />\n";
	}

	function getSQL() {
		$NScat = NS_CATEGORY;
		$dbr =& wfGetDB( DB_SLAVE );
		extract( $dbr->tableNames( 'categorylinks','page' ));
		return "SELECT DISTINCT 'Unusedcategories' as type,
				{$NScat} as namespace, page_title as title, 1 as value
				FROM $page
				LEFT JOIN $categorylinks ON page_title=cl_to
				WHERE cl_from IS NULL
				AND page_namespace = {$NScat}
				AND page_is_redirect = 0";
	}

	function formatResult( $skin, $result ) {
		global $wgLang;
		$title = Title::makeTitle( NS_CATEGORY, $result->title );
		return $skin->makeLinkObj( $title, $title->getText() );
	}
}

/** constructor */
function wfSpecialUnusedCategories() {
	list( $limit, $offset ) = wfCheckLimits();
	$uc = new UnusedCategoriesPage();
	return $uc->doQuery( $offset, $limit );
}
?>
