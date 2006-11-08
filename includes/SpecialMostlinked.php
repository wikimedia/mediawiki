<?php

/**
 * A special page to show pages ordered by the number of pages linking to them
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 *
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 * @author Rob Church <robchur@gmail.com>
 * @copyright Copyright © 2005, Ævar Arnfjörð Bjarmason
 * @copyright © 2006 Rob Church
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */

/**
 * @package MediaWiki
 * @subpackage SpecialPage
 */
class MostlinkedPage extends QueryPage {

	function getName() { return 'Mostlinked'; }
	function isExpensive() { return true; }
	function isSyndicated() { return false; }

	/**
	 * Note: Getting page_namespace only works if $this->isCached() is false
	 */
	function getSQL() {
		$dbr =& wfGetDB( DB_SLAVE );
		extract( $dbr->tableNames( 'pagelinks', 'page' ) );
		return
			"SELECT 'Mostlinked' AS type,
				pl_namespace AS namespace,
				pl_title AS title,
				COUNT(*) AS value,
				page_namespace
			FROM $pagelinks
			LEFT JOIN $page ON pl_namespace=page_namespace AND pl_title=page_title
			GROUP BY 1,2,3,5
			HAVING COUNT(*) > 1";
	}

	/**
	 * Pre-fill the link cache
	 */
	function preprocessResults( &$dbr, $res ) {
		if( $dbr->numRows( $res ) > 0 ) {
			$linkBatch = new LinkBatch();
			while( $row = $dbr->fetchObject( $res ) )
				$linkBatch->addObj( Title::makeTitleSafe( $row->namespace, $row->title ) );
			$dbr->dataSeek( $res, 0 );
			$linkBatch->execute();
		}
	}

	/**
	 * Make a link to "what links here" for the specified title
	 *
	 * @param $title Title being queried
	 * @return string
	 */
	function makeWlhLink( &$title, $caption ) {
		$wlh = SpecialPage::getTitleFor( 'Whatlinkshere', $title->getPrefixedDBkey() );
		return Linker::makeKnownLinkObj( $wlh, $caption );
	}

	/**
	 * Make links to the page corresponding to the item, and the "what links here" page for it
	 *
	 * @param $result Result row
	 * @return string
	 */
	function formatResult( $result ) {
		global $wgLang;
		$title = Title::makeTitleSafe( $result->namespace, $result->title );
		$link = Linker::makeLinkObj( $title );
		$wlh = $this->makeWlhLink( $title,
			wfMsgExt( 'nlinks', array( 'parsemag', 'escape'),
				$wgLang->formatNum( $result->value ) ) );
		return wfSpecialList( $link, $wlh );
	}
}

/**
 * constructor
 */
function wfSpecialMostlinked() {
	list( $limit, $offset ) = wfCheckLimits();

	$wpp = new MostlinkedPage();

	$wpp->doQuery( $offset, $limit );
}

?>
