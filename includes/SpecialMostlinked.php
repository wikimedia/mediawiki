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
			GROUP BY pl_namespace,pl_title
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
	 * @param $skin Skin to use
	 * @return string
	 */
	function makeWlhLink( &$title, $caption, &$skin ) {
		$wlh = Title::makeTitle( NS_SPECIAL, 'Whatlinkshere' );
		return $skin->makeKnownLinkObj( $wlh, $caption, 'target=' . $title->getPrefixedUrl() );
	}

	/**
	 * Make links to the page corresponding to the item, and the "what links here" page for it
	 *
	 * @param $skin Skin to be used
	 * @param $result Result row
	 * @return string
	 */
	function formatResult( $skin, $result ) {
		global $wgLang;
		$title = Title::makeTitleSafe( $result->namespace, $result->title );
		$link = $skin->makeLinkObj( $title );
		$wlh = $this->makeWlhLink( $title,
			wfMsgExt( 'nlinks', array( 'parsemag', 'escape'),
				$wgLang->formatNum( $result->value ) ), $skin );
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
