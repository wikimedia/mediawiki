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
	function isSyndicated() { return false; }

	function getPageHeader( ) {
		global $wgUser;
		$sk = $wgUser->getSkin();
		
		#FIXME : probably need to add a backlink to the maintenance page.
		return '<p>'.wfMsg("disambiguationstext", $sk->makeKnownLink(wfMsgForContent('disambiguationspage')) )."</p><br />\n";
	}

	function getSQL() {
		$dbr =& wfGetDB( DB_SLAVE );
		extract( $dbr->tableNames( 'cur', 'links' ) );

		$dp = Title::newFromText(wfMsgForContent("disambiguationspage"));
		$dns = $dp->getNamespace();
		$dtitle = $dbr->addQuotes( $dp->getDBkey() );

		$sql = "SELECT 'Disambiguations' as type,"
			.   " ca.cur_namespace AS namespace, ca.cur_title AS title"
			. " FROM {$links} as la, {$links} as lb, {$cur} as ca, {$cur} as cb"
			. " WHERE cb.cur_namespace = $dns"
			. " AND cb.cur_title = $dtitle"
			. " AND la.l_from = lb.l_to"
			. " AND ca.cur_id = lb.l_from"
			. " AND cb.cur_id = lb.l_to" ;

		return $sql;
	}

	function getOrder() {
		return '';
	}
	
	function formatResult( $skin, $result ) {
		global $wgContLang ;
		$dp = Title::newFromText(wfMsgForContent("disambiguationspage"));
        $title = Title::makeTitle( $result->namespace, $result->title );

		$from = $skin->makeKnownLinkObj( $title,'');
		$edit = $skin->makeBrokenLinkObj( $title, "(".wfMsg("qbedit").")" , 'redirect=no');
		$to   = $skin->makeKnownLinkObj( $dp,'');
		
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
