<?php

/**
 * Special page lists pages without language links
 *
 * @package MediaWiki
 * @addtogroup SpecialPage
 * @author Rob Church <robchur@gmail.com>
 */
class WithoutInterwikiPage extends PageQueryPage {

	function getName() {
		return 'Withoutinterwiki';
	}
	
	function getPageHeader() {
		return '<p>' . wfMsgHtml( 'withoutinterwiki-header' ) . '</p>';
	}

	function sortDescending() {
		return false;
	}

	function isExpensive() {
		return true;
	}
	
	function isSyndicated() {
		return false;
	}

	function getSQL() {
		$dbr = wfGetDB( DB_SLAVE );
		list( $page, $langlinks ) = $dbr->tableNamesN( 'page', 'langlinks' );
		return
		  "SELECT 'Withoutinterwiki'  AS type,
		          page_namespace AS namespace,
		          page_title     AS title,
		          page_title     AS value
		     FROM $page
		LEFT JOIN $langlinks
		       ON ll_from = page_id
		    WHERE ll_title IS NULL
		      AND page_namespace=" . NS_MAIN . "
		      AND page_is_redirect = 0";
	}
	
}

function wfSpecialWithoutinterwiki() {
	list( $limit, $offset ) = wfCheckLimits();
	$wip = new WithoutInterwikiPage();
	$wip->doQuery( $offset, $limit );
}

?>
