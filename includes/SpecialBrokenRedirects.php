<?php
/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/**
 *
 */
require_once('QueryPage.php');

/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */
class BrokenRedirectsPage extends PageQueryPage {

	function getName() {
		return 'BrokenRedirects';
	}
	
	function isExpensive( ) { return true; }
	function isSyndicated() { return false; }

	function getPageHeader( ) {
		#FIXME : probably need to add a backlink to the maintenance page.
		return '<p>'.wfMsg('brokenredirectstext')."</p><br />\n";
	}

	function getSQL() {
		$dbr =& wfGetDB( DB_SLAVE );
		extract( $dbr->tableNames( 'page', 'brokenlinks' ) );

		$sql = "SELECT bl_to,page_title FROM $brokenlinks,$page " .
		       "WHERE page_is_redirect=1 AND page_namespace=0 AND bl_from=page_id ";
		return $sql;
	}

	function getOrder() {
		return '';
	}
	
	function formatResult( $skin, $result ) {
		global $wgContLang ;
		
		$ns = $wgContLang->getNamespaces() ; /* not used, why bother? */
		$from = $skin->makeKnownLink( $result->page_title ,'', 'redirect=no' );
		$edit = $skin->makeBrokenLink( $result->page_title , "(".wfMsg("qbedit").")" , 'redirect=no');
		$to   = $skin->makeBrokenLink( $result->bl_to );
				
		return "$from $edit => $to";
	}
}

/**
 * constructor
 */
function wfSpecialBrokenRedirects() {
	list( $limit, $offset ) = wfCheckLimits();
	
	$sbr = new BrokenRedirectsPage();
	
	return $sbr->doQuery( $offset, $limit );

}
?>
