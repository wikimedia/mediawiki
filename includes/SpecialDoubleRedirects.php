<?php
/**
 *
 */

/**
 * 
 */
require_once('QueryPage.php');

/**
 *
 */
class DoubleRedirectsPage extends PageQueryPage {

	function getName() {
		return 'doubleredirects';
	}
	
	function isExpensive( ) { return true; }

	function getPageHeader( ) {
		#FIXME : probably need to add a backlink to the maintenance page.
		return '<p>'.wfMsg("doubleredirectstext")."</p><br>\n";
	}

	function getSQL() {
		$dbr =& wfGetDB( DB_SLAVE );
		extract( $dbr->tableNames( 'cur', 'links' ) );

		$sql = "SELECT ca.cur_namespace as ns_a, ca.cur_title as title_a," . 
			   "  cb.cur_namespace as ns_b, cb.cur_title as title_b," .
			   "  cb.cur_text AS rt " . 
			   "FROM $links,$cur AS ca,$cur AS cb ". 
			   "WHERE ca.cur_is_redirect=1 AND cb.cur_is_redirect=1 AND l_to=cb.cur_id " .
			   "  AND l_from=ca.cur_id " ;
		return $sql;
	}

	function getOrder() {
		return '';
	}
	
	function formatResult( $skin, $result ) {
		global $wgLang ;
		$ns = $wgLang->getNamespaces() ;
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
