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
class DisambiguationsPage extends PageQueryPage {

	function getName() {
		return 'disambiguations';
	}
	
	function isExpensive( ) { return true; }

	function getPageHeader( ) {
		global $wgUser;
		$sk = $wgUser->getSkin();
		
		#FIXME : probably need to add a backlink to the maintenance page.
		return '<p>'.wfMsg("disambiguationstext", $sk->makeKnownLink(wfMsgForContent('disambiguationspage')) )."</p><br>\n";
	}

	function getSQL() {
		$dbr =& wfGetDB( DB_SLAVE );
		extract( $dbr->tableNames( 'cur', 'links' ) );
		
		$dp = Title::newFromText(wfMsgForContent("disambiguationspage"));
		$dpid = $dp->getArticleID();
			
		$sql = "SELECT ca.cur_namespace AS ns_art, ca.cur_title AS title_art,"
			.        " cb.cur_namespace AS ns_dis, cb.cur_title AS title_dis"
		    . " FROM links as la, links as lb, cur as ca, cur as cb"
		    . " WHERE la.l_to = '{$dpid}'"
		    . " AND la.l_from = lb.l_to"
		    . " AND ca.cur_id = lb.l_from"
		    . " AND cb.cur_id = lb.l_to";

		return $sql;
	}

	function getOrder() {
		return '';
	}
	
	function formatResult( $skin, $result ) {
		global $wgLang ;
		$ns = $wgLang->getNamespaces() ;

		$from = $skin->makeKnownLink( $ns[$result->ns_art].':'.$result->title_art ,'');
		$edit = $skin->makeBrokenLink( $ns[$result->ns_art].':'.$result->title_art , "(".wfMsg("qbedit").")" , 'redirect=no');
		$to   = $skin->makeKnownLink( $ns[$result->ns_dis].':'.$result->title_dis ,'');
		
		return "$from $edit => $to";
	}
}

/**
 * Constructor
 */
function wfSpecialDisambiguations() {
	list( $limit, $offset ) = wfCheckLimits();
	
	$sd = new DisambiguationsPage();
	
	return $sd->doQuery( $offset, $limit );

}
?>
