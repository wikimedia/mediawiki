<?php

/**
 * Special page lists images which haven't been categorised
 *
 * @package MediaWiki
 * @subpackage Special pages
 * @author Rob Church <robchur@gmail.com>
 */

class UncategorizedImagesPage extends QueryPage {

	function getName() {
		return 'Uncategorizedimages';
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
		$dbr =& wfGetDB( DB_SLAVE );
		list( $page, $categorylinks ) = $dbr->tableNamesN( 'page', 'categorylinks' );
		$ns = NS_IMAGE;

		return "SELECT 'Uncategorizedimages' AS type, page_namespace AS namespace,
				page_title AS title, page_title AS value
				FROM {$page} LEFT JOIN {$categorylinks} ON page_id = cl_from
				WHERE cl_from IS NULL AND page_namespace = {$ns} AND page_is_redirect = 0";
	}

	function formatResult( $skin, $row ) {
		global $wgContLang;
		$title = Title::makeTitleSafe( NS_IMAGE, $row->title );
		$label = htmlspecialchars( $wgContLang->convert( $title->getText() ) );
		return $skin->makeKnownLinkObj( $title, $label );
	}
}

function wfSpecialUncategorizedimages() {
	$uip = new UncategorizedImagesPage();
	list( $limit, $offset ) = wfCheckLimits();
	return $uip->doQuery( $offset, $limit );
}

?>
