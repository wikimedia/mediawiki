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

    function getDisambiguationPageObj() {
        return Title::makeTitleSafe( NS_MEDIAWIKI, 'disambiguationspage');
    }
    
	function getPageHeader( ) {
		global $wgUser;
		$sk = $wgUser->getSkin();

		return '<p>'.wfMsg('disambiguationstext', $sk->makeKnownLinkObj($this->getDisambiguationPageObj()))."</p><br />\n";
	}

	function getSQL() {
		$dbr =& wfGetDB( DB_SLAVE );
		list( $page, $pagelinks, $templatelinks) =  $dbr->tableNamesN( 'page', 'pagelinks', 'templatelinks' );

        $dMsgText = wfMsgForContent('disambiguationspage');
		
		$linkBatch = new LinkBatch;
        
        # If the text can be treated as a title, use it verbatim.
        # Otherwise, pull the titles from the links table
        $dp = Title::newFromText($dMsgText);
        if( $dp ) {
    		if($dp->getNamespace() != NS_TEMPLATE) {
    			# FIXME we assume the disambiguation message is a template but
    			# the page can potentially be from another namespace :/
    			wfDebug("Mediawiki:disambiguationspage message does not refer to a template!\n");
    		}
            $linkBatch->addObj( $dp );
        } else {
            # Get all the templates linked from the Mediawiki:Disambiguationspage
            $disPageObj = $this->getDisambiguationPageObj();
			$res = $dbr->select(
				array('pagelinks', 'page'),
				'pl_title',
				array('page_id = pl_from', 'pl_namespace' => NS_TEMPLATE,
                      'page_namespace' => $disPageObj->getNamespace(), 'page_title' => $disPageObj->getDBkey()),
				'DisambiguationsPage::getSQL' );
            
    		while ( $row = $dbr->fetchObject( $res ) ) {
                $linkBatch->addObj( Title::makeTitle( NS_TEMPLATE, $row->pl_title ));
            }
    		$dbr->freeResult( $res );
        }
            
        $set = $linkBatch->constructSet( 'lb.tl', $dbr );
        if( $set === false ) {
            $set = 'FALSE';  # We must always return a valid sql query, but this way DB will always quicly return an empty result
            wfDebug("Mediawiki:disambiguationspage message does not link to any templates!\n");
        }
        
        $sql = "SELECT 'Disambiguations' AS \"type\", pb.page_namespace AS namespace,"
             ." pb.page_title AS title, la.pl_from AS value"
             ." FROM {$templatelinks} AS lb, {$page} AS pb, {$pagelinks} AS la, {$page} AS pa"
             ." WHERE $set"  # disambiguation template(s)
             .' AND pa.page_id = la.pl_from'
             .' AND pa.page_namespace = ' . NS_MAIN  # Limit to just articles in the main namespace
             .' AND pb.page_id = lb.tl_from'
             .' AND pb.page_namespace = la.pl_namespace'
             .' AND pb.page_title = la.pl_title'
			 .' ORDER BY lb.tl_namespace, lb.tl_title';

        return $sql;
	}

	function getOrder() {
		return '';
	}

	function formatResult( $skin, $result ) {
		global $wgContLang;
		$title = Title::newFromId( $result->value );
		$dp = Title::makeTitle( $result->namespace, $result->title );

		$from = $skin->makeKnownLinkObj( $title,'');
		$edit = $skin->makeBrokenLinkObj( $title, "(".wfMsg("qbedit").")" , 'redirect=no');
		$arr  = $wgContLang->getArrow();
		$to   = $skin->makeKnownLinkObj( $dp,'');

		return "$from $edit $arr $to";
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
