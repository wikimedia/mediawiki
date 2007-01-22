<?php
/**
 *
 * @addtogroup SpecialPage
 */

/**
 *
 * @addtogroup SpecialPage
 */
class ProtectedPagesPage extends PageQueryPage {

	function getName( ) {
		return "Protectedpages";
	}

	function getPageHeader() {
		return '<p>' . wfMsg('protectedpagestext') . '</p>';
	}

	/**
	 * LEFT JOIN is expensive
	 *
	 * @return true
	 */
	function isExpensive( ) {
		return 1;
	}

	function isSyndicated() { return false; }

	/**
	 * @return false
	 */
	function sortDescending() {
		return false;
	}

    /**
	 * @return string an sqlquery
	 */
	function getSQL() {
		$dbr =& wfGetDB( DB_SLAVE );
		list( $page, $page_restrictions ) = $dbr->tableNamesN( 'page', 'page_restrictions' );
		return "SELECT DISTINCT page_id, 'Protectedpages' as type, page_namespace AS namespace, page_title as title, " .
			"page_title AS value, pr_level, pr_expiry " .
			"FROM $page LEFT JOIN $page_restrictions ON page_id = pr_page WHERE pr_level IS NOT NULL AND pr_user IS NULL ";
    }

	/**
	 * Make link to the page, and add the protection levels.
	 *
	 * @param $skin Skin to be used
	 * @param $result Result row
	 * @return string
	 */
	function formatResult( $skin, $result ) {
		global $wgLang;
		$title = Title::makeTitleSafe( $result->namespace, $result->title );
		$link = $skin->makeLinkObj( $title );

		$description_items = array ();

		$protType = wfMsg( 'restriction-level-' . $result->pr_level );

		$description_items[] = $protType;

		$expiry_description = '';

		if ( $result->pr_expiry != 'infinity' && strlen($result->pr_expiry) ) {
			$expiry = Block::decodeExpiry( $result->pr_expiry );
	
			$expiry_description = wfMsgForContent( 'protect-expiring', $wgLang->timeanddate( $expiry ) );

			$description_items[] = $expiry_description;
		}

		return wfSpecialList( $link, implode( $description_items, ', ' ) );
	}
}

/**
 * Constructor
 */
function wfSpecialProtectedpages() {

	list( $limit, $offset ) = wfCheckLimits();

	$depp = new ProtectedPagesPage();

	Title::purgeExpiredRestrictions();

	return $depp->doQuery( $offset, $limit );
}

?>
