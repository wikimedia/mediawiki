<?php
/*
 * @file
 * @ingroup SpecialPage
 */

/**
 * Querypage that lists the most wanted files - implements Special:Wantedfiles
 *
 * @ingroup SpecialPage
 *
 * @author Soxred93 <soxred93@gmail.com>
 * @copyright Copyright © 2008, Soxred93
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */
class WantedFilesPage extends QueryPage {

	function getName() {
		return 'Wantedfiles';
	}

	function isExpensive() {
		return true;
	}

	function isSyndicated() {
		return false;
	}

	function getSQL() {
		$dbr = wfGetDB( DB_SLAVE );
		list( $imagelinks, $page ) = $dbr->tableNamesN( 'imagelinks', 'page' );
		$name = $dbr->addQuotes( $this->getName() );
		return
			"
			SELECT
				$name as type,
				" . NS_FILE . " as namespace,
				il_to as title,
				COUNT(*) as value
			FROM $imagelinks
			LEFT JOIN $page ON il_to = page_title AND page_namespace = ". NS_FILE ."
			WHERE page_title IS NULL
			GROUP BY il_to
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
function wfSpecialWantedFiles() {
	list( $limit, $offset ) = wfCheckLimits();

	$wpp = new WantedFilesPage();

	$wpp->doQuery( $offset, $limit );
}
