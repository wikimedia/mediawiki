<?php
/**
 * @file
 * @ingroup SpecialPage
 */

/**
 * A special page looking for articles with no article linking to them,
 * thus being lonely.
 * @ingroup SpecialPage
 */
class LonelyPagesPage extends PageQueryPage {

	function getName() {
		return "Lonelypages";
	}
	function getPageHeader() {
		return wfMsgExt( 'lonelypagestext', array( 'parse' ) );
	}

	function sortDescending() {
		return false;
	}

	function isExpensive() {
		return true;
	}
	function isSyndicated() { return false; }

	function getSQL() {
		$dbr = wfGetDB( DB_SLAVE );
		list( $page, $pagelinks, $templatelinks ) = $dbr->tableNamesN( 'page', 'pagelinks', 'templatelinks' );

		return
		  "SELECT 'Lonelypages'  AS type,
		          page_namespace AS namespace,
		          page_title     AS title,
		          page_title     AS value
		     FROM $page
		LEFT JOIN $pagelinks
		       ON page_namespace=pl_namespace AND page_title=pl_title
		LEFT JOIN $templatelinks
				ON page_namespace=tl_namespace AND page_title=tl_title
		    WHERE pl_namespace IS NULL
		      AND page_namespace=".NS_MAIN."
		      AND page_is_redirect=0
			  AND tl_namespace IS NULL";

	}
}

/**
 * Constructor
 */
function wfSpecialLonelypages() {
	list( $limit, $offset ) = wfCheckLimits();

	$lpp = new LonelyPagesPage();

	return $lpp->doQuery( $offset, $limit );
}
