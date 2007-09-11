<?php
/**
 *
 * @addtogroup SpecialPage
 */

/**
 * A special page looking for page without any category.
 * @addtogroup SpecialPage
 */
class UncategorizedPagesPage extends PageQueryPage {
	var $requestedNamespace = NS_MAIN;

	function getName() {
		return "Uncategorizedpages";
	}

	function sortDescending() {
		return false;
	}

	function isExpensive() {
		return false;
	}
	function isSyndicated() { return false; }

	function getSQL() {
		$dbr = wfGetDB( DB_SLAVE );
		list( $page, $categorylinks ) = $dbr->tableNamesN( 'page', 'categorylinks' );
		$name = $dbr->addQuotes( $this->getName() );

		return
			"
			SELECT
				$name as type,
				page.page_namespace AS namespace,
				page.page_title AS title,
				page.page_title AS value
			FROM $page,$categorylinks
			WHERE page_id=cl_from AND page_namespace={$this->requestedNamespace} AND page_is_redirect=0 AND cl_to=''
			";
	}
}

/**
 * constructor
 */
function wfSpecialUncategorizedpages() {
	list( $limit, $offset ) = wfCheckLimits();

	$lpp = new UncategorizedPagesPage();

	return $lpp->doQuery( $offset, $limit );
}


