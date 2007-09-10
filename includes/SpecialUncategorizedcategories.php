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
	function UncategorizedCategoriesPage() {
		$this->requestedNamespace = NS_CATEGORY;
	}

	function getName() {
		return "Uncategorizedcategories";
	}
}

/**
 * constructor
 */
function wfSpecialUncategorizedcategories() {
	list( $limit, $offset ) = wfCheckLimits();

	$lpp = new UncategorizedCategoriesPage();

	return $lpp->doQuery( $offset, $limit );
}

?>
