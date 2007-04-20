<?php
/**
 *
 * @addtogroup SpecialPage
 */

/**
 *
 */
require_once( "SpecialUncategorizedpages.php" );

/**
 * implements Special:Uncategorizedcategories
 * @addtogroup SpecialPage
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
