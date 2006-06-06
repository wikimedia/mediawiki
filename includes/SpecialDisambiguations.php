<?php
/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */
class DisambiguationsPage extends PageQueryPage {

	function getName() {
		return 'Disambiguations';
	}

	function isExpensive( ) { return true; }
	function isSyndicated() { return false; }

	function getPageHeader( ) {
		global $wgUser;
		$sk = $wgUser->getSkin();

		#FIXME : probably need to add a backlink to the maintenance page.
		return '<p>'.wfMsg('disambiguationstext', $sk->makeKnownLink(wfMsgForContent('disambiguationspage')) )."</p><br />\n";
	}

	function getSQL() {
		$dbr =& wfGetDB( DB_SLAVE );
		extract( $dbr->tableNames( 'page', 'pagelinks', 'templatelinks' ) );

		$dp = Title::newFromText(wfMsgForContent('disambiguationspage'));
		$id = $dp->getArticleId();
		$dns = $dp->getNamespace();
		$dtitle = $dbr->addQuotes( $dp->getDBkey() );

		if($dns != NS_TEMPLATE) {
			# FIXME we assume the disambiguation message is a template but
			# the page can potentially be from another namespace :/
			wfDebug("Mediawiki:disambiguationspage message does not refer to a template!\n");
		}

		$sql = "SELECT 'Disambiguations' AS \"type\", pa.page_namespace AS namespace,"
			 ." pa.page_title AS title, la.pl_from AS value"
			 ." FROM {$templatelinks} AS lb, {$page} AS pa, {$pagelinks} AS la"
			 ." WHERE lb.tl_namespace = $dns AND lb.tl_title = $dtitle" # disambiguation template
			 .' AND pa.page_id = lb.tl_from'
			 .' AND pa.page_namespace = la.pl_namespace'
			 .' AND pa.page_title = la.pl_title';
		return $sql;
	}

	function getOrder() {
		return '';
	}

	function formatResult( $skin, $result ) {
		$title = Title::newFromId( $result->value );
		$dp = Title::makeTitle( $result->namespace, $result->title );

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
