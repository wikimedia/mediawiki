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
class DoubleRedirectsPage extends PageQueryPage {

	function getName() {
		return 'DoubleRedirects';
	}
	
	function isExpensive( ) { return true; }
	function isSyndicated() { return false; }

	function getPageHeader( ) {
		#FIXME : probably need to add a backlink to the maintenance page.
		return '<p>'.wfMsg("doubleredirectstext")."</p><br>\n";
	}

	function getSQL() {
		$dbr =& wfGetDB( DB_SLAVE );
		extract( $dbr->tableNames( 'page', 'links', 'text' ) );

		$sql = "SELECT pa.page_namespace as ns_a, pa.page_title as title_a,
			     pb.page_namespace as ns_b, pb.page_title as title_b,
			     old_text AS rt 
			   FROM $text AS t, $links,$page AS pa,$page AS pb 
			   WHERE pa.page_is_redirect=1 AND pb.page_is_redirect=1 AND l_to=pb.page_id 
			     AND l_from=pa.page_id 
			     AND pb.page_latest=t.old_id" ;
		return $sql;
	}

	function getOrder() {
		return '';
	}
	
	function formatResult( $skin, $result ) {
		global $wgContLang ;
		$ns = $wgContLang->getNamespaces() ;
		$from = $skin->makeKnownLink( $ns[$result->ns_a].':'.$result->title_a ,'', 'redirect=no' );
		$edit = $skin->makeBrokenLink( $ns[$result->ns_a].':'.$result->title_a , "(".wfMsg("qbedit").")" , 'redirect=no');
		$to   = $skin->makeKnownLink( $ns[$result->ns_b].':'.$result->title_b ,'');
		$content = $result->rt;
		
		return "$from $edit => $to ($content)";
	}
}

/**
 * constructor
 */
function wfSpecialDoubleRedirects() {
	list( $limit, $offset ) = wfCheckLimits();
	
	$sdr = new DoubleRedirectsPage();
	
	return $sdr->doQuery( $offset, $limit );

}
?>
