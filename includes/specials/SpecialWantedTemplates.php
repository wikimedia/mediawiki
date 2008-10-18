<?php
/**
 * @file
 * @ingroup SpecialPage
 */

/**
 * A querypage to list the most wanted templates - implements Special:Wantedtemplates
 * based on SpecialWantedcategories.php by Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 * makeWlhLink() taken from SpecialMostlinkedtemplates by Rob Church <robchur@gmail.com>
 *
 * @ingroup SpecialPage
 *
 * @author Danny B.
 * @copyright Copyright © 2008, Danny B.
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */
class WantedTemplatesPage extends QueryPage {

	function getName() {
		return 'Wantedtemplates';
	}

	function isExpensive() {
		return true;
	}

	function isSyndicated() {
		return false;
	}

	function getSQL() {
		$dbr = wfGetDB( DB_SLAVE );
		list( $templatelinks, $page ) = $dbr->tableNamesN( 'templatelinks', 'page' );
		$name = $dbr->addQuotes( $this->getName() );
		return
			"
			  SELECT $name as type," . 
			         NS_TEMPLATE . " as namespace,
			         tl_title as title,
			         COUNT(*) as value
			    FROM $templatelinks LEFT JOIN
			         $page ON tl_title = page_title AND page_namespace = ". NS_TEMPLATE ."
			   WHERE page_title IS NULL
			GROUP BY tl_title
			";
	}

	function sortDescending() { return true; }

	/**
	 * Fetch user page links and cache their existence
	 */
	function preprocessResults( $db, $res ) {
		$batch = new LinkBatch;
		while ( $row = $db->fetchObject( $res ) )
			$batch->add( $row->namespace, $row->title );
		$batch->execute();

		// Back to start for display
		if ( $db->numRows( $res ) > 0 )
			// If there are no rows we get an error seeking.
			$db->dataSeek( $res, 0 );
	}

	function formatResult( $skin, $result ) {
		global $wgLang, $wgContLang;

		$nt = Title::makeTitle( $result->namespace, $result->title );
		$text = $wgContLang->convert( $nt->getText() );

		$plink = $this->isCached() ?
			$skin->makeLinkObj( $nt, htmlspecialchars( $text ) ) :
			$skin->makeBrokenLinkObj( $nt, htmlspecialchars( $text ) );

		$nlinks = wfMsgExt( 'nmembers', array( 'parsemag', 'escape'),
			$wgLang->formatNum( $result->value ) );
		return wfSpecialList(
			$plink,
			$this->makeWlhLink( $nt, $skin, $result )
		);
	}

	/**
	 * Make a "what links here" link for a given title
	 *
	 * @param Title $title Title to make the link for
	 * @param Skin $skin Skin to use
	 * @param object $result Result row
	 * @return string
	 */
	private function makeWlhLink( $title, $skin, $result ) {
		global $wgLang;
		$wlh = SpecialPage::getTitleFor( 'Whatlinkshere' );
		$label = wfMsgExt( 'nlinks', array( 'parsemag', 'escape' ),
		$wgLang->formatNum( $result->value ) );
		return $skin->link( $wlh, $label, array(), array( 'target' => $title->getPrefixedText() ) );
	}
}

/**
 * constructor
 */
function wfSpecialWantedTemplates() {
	list( $limit, $offset ) = wfCheckLimits();

	$wpp = new WantedTemplatesPage();

	$wpp->doQuery( $offset, $limit );
}
