<?php

/**
 * @package MediaWiki
 * @subpackage Special pages
 *
 * @author Rob Church <robchur@gmail.com>
 * @copyright © 2006 Rob Church
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */

/**
 * @package MediaWiki
 * @subpackage SpecialPage
 */

class UnusedtemplatesPage extends QueryPage {

	function getName() { return( 'Unusedtemplates' ); }
	function isExpensive() { return true; }
	function isSyndicated() { return false; }
	function sortDescending() { return false; }

	function getSQL() {
		$dbr =& wfGetDB( DB_SLAVE );
		extract( $dbr->tableNames( 'page', 'templatelinks' ) );
		$sql = "SELECT 'Unusedtemplates' AS type, page_title AS title,
			page_namespace AS namespace, 0 AS value
			FROM $page
			LEFT JOIN $templatelinks
			ON page_namespace = tl_namespace AND page_title = tl_title
			WHERE page_namespace = 10 AND tl_from IS NULL";
		return $sql;
	}

	function formatResult( $skin, $result ) {
		$title = Title::makeTitle( NS_TEMPLATE, $result->title );
		$pageLink = $skin->makeKnownLinkObj( $title, '', 'redirect=no' );
		$wlhLink = $skin->makeKnownLinkObj(
			Title::makeTitle( NS_SPECIAL, 'Whatlinkshere' ),
			wfMsgHtml( 'unusedtemplateswlh' ),
			'target=' . $title->getPrefixedUrl() );
		return wfSpecialList( $pageLink, $wlhLink );
	}

	function getPageHeader() {
		global $wgOut;
		return $wgOut->parse( wfMsg( 'unusedtemplatestext' ) );
	}

}

function wfSpecialUnusedtemplates() {
	list( $limit, $offset ) = wfCheckLimits();
	$utp = new UnusedtemplatesPage();
	$utp->doQuery( $offset, $limit );
}

?>
