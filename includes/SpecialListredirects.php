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
		$rd_link_text = htmlspecialchars( $wgContLang->convert( $rd_title->getPrefixedText() ) );
		$rd_link = $skin->makeKnownLink( $rd_title->getPrefixedText(), $rd_link_text, 'redirect=no' );
		
		# Find out where the redirect leads
		$rd_page = new Article( &$rd_title, 0 );
		$rd_text = $rd_page->getContent( true ); # Don't follow the redirect
		
		# Make a link to the destination page
		$tp_title = Title::newFromRedirect( $rd_text );
		$tp_link_text = htmlspecialchars( $tp_title->getPrefixedText() );
		$tp_link = $skin->makeKnownLink( $tp_title->getPrefixedText(), $tp_link_text );
		
		# Format the whole thing and return it
		return( $rd_link . ' &rarr; ' . $tp_link );
	}

}

function wfSpecialListredirects() {
	list( $limit, $offset ) = wfCheckLimits();
	$lrp = new ListredirectsPage();
	$lrp->doQuery( $offset, $limit );
}

?>