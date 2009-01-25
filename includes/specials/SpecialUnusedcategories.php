<?php
/**
 * @file
 * @ingroup SpecialPage
 */

/**
 * @ingroup SpecialPage
 */
class UnusedCategoriesPage extends QueryPage {

	function isExpensive() { return true; }

	function getName() {
		return 'Unusedcategories';
	}

	function getPageHeader() {
		return wfMsgExt( 'unusedcategoriestext', array( 'parse' ) );
	}

	function getSQL() {
		$NScat = NS_CATEGORY;
		$dbr = wfGetDB( DB_SLAVE );
		list( $categorylinks, $page, $page_props ) = $dbr->tableNamesN( 'categorylinks', 'page', 'page_props' );
		return "SELECT 'Unusedcategories' as type,
				{$NScat} as namespace, page_title as title, page_title as value
				FROM $page
				LEFT JOIN $categorylinks ON page_title=cl_to
				LEFT JOIN $page_props ON (pp_page=page_id AND pp_propname = 'ignoreunused')
				WHERE cl_from IS NULL
				AND page_namespace = {$NScat}
				AND page_is_redirect = 0
				AND pp_propname IS NULL";
	}

	function formatResult( $skin, $result ) {
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
