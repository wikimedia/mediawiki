<?php
/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/**
 *
 */
require_once( "SpecialUncategorizedpages.php" );

/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */
class UncategorizedCategoriesPage extends UncategorizedPagesPage {
	function getName() {
		return "Uncategorizedcategories";
	}
}

/**
 * constructor
 */
function wfSpecialUncategorizedcategories() {
	list( $limit, $offset ) = wfCheckLimits();

	$lpp = new UncategorizedPagesPage();
	$lpp->requestedNamespace = NS_CATEGORY;

	return $lpp->doQuery( $offset, $limit );
}

?>