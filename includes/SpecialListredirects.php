<?php
/**
 * @addtogroup SpecialPage
 *
 * @author Rob Church <robchur@gmail.com>
 * @copyright © 2006 Rob Church
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */

/**
 * @addtogroup SpecialPage
 */

class ListredirectsPage extends QueryPage {

	function getName() { return( 'Listredirects' ); }
	function isExpensive() { return( true ); }
	function isSyndicated() { return( false ); }
	function sortDescending() { return( false ); }

	function getSQL() {
		$dbr =& wfGetDB( DB_SLAVE );
		$page = $dbr->tableName( 'page' );
		$sql = "SELECT 'Listredirects' AS type, page_title AS title, page_namespace AS namespace, 0 AS value FROM $page WHERE page_is_redirect = 1";
		return( $sql );
	}

	function formatResult( $skin, $result ) {
		global $wgContLang;
	
		# Make a link to the redirect itself
		$rd_title = Title::makeTitle( $result->namespace, $result->title );
		$arr = $wgContLang->getArrow() . $wgContLang->getDirMark();
		$rd_link = $skin->makeKnownLinkObj( $rd_title, '', 'redirect=no' );

		# Find out where the redirect leads
		$revision = Revision::newFromTitle( $rd_title );
		if( $revision ) {
			# Make a link to the destination page
			$target = Title::newFromRedirect( $revision->getText() );
			if( $target ) {
				$targetLink = $skin->makeLinkObj( $target );
			} else {
				/** @todo Put in some decent error display here */
				$targetLink = '*';
			}
		} else {
			/** @todo Put in some decent error display here */
			$targetLink = '*';
		}

		# Format the whole thing and return it
		return "$rd_link $arr $targetLink";

	}

}

function wfSpecialListredirects() {
	list( $limit, $offset ) = wfCheckLimits();
	$lrp = new ListredirectsPage();
	$lrp->doQuery( $offset, $limit );
}

?>
