<?php
/**
 * @package MediaWiki
 * @subpackage SpecialPage
 *
 * @author Rob Church <robchur@gmail.com>
 * @copyright © 2006 Rob Church
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */

/* */
require_once 'QueryPage.php';

/**
 * @package MediaWiki
 * @subpackage SpecialPage
 */

class ListredirectsPage extends QueryPage {

	function getName() { return( 'listredirects' ); }
	function isExpensive() { return( true ); }
	function isSyndicated() { return( false ); }
	function sortDescending() { return( false ); }

	function getSQL() {
		$dbr =& wfGetDB( DB_SLAVE );
		extract( $dbr->tableNames( 'page' ) );
		return( 'SELECT page_title AS title, page_namespace AS namespace, page_namespace AS value FROM ' . $page . ' WHERE page_is_redirect = 1' );
	}

	function formatResult( $skin, $result ) {
		global $wgContLang;

		# Make a link to the redirect itself
		$rd_title = Title::makeTitle( $result->namespace, $result->title );
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
		return( $rd_link . ' &rarr; ' . $targetLink );

	}

}

function wfSpecialListredirects() {
	list( $limit, $offset ) = wfCheckLimits();
	$lrp = new ListredirectsPage();
	$lrp->doQuery( $offset, $limit );
}

?>