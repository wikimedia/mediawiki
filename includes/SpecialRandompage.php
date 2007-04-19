<?php

/**
 * Special page to direct the user to a random page
 *
 * @addtogroup SpecialPage
 * @author Rob Church <robchur@gmail.com>, Ilmari Karonen
 * @license GNU General Public Licence 2.0 or later
 */

/**
 * Main execution point
 * @param $par Namespace to select the page from
 */
function wfSpecialRandompage( $par = null ) {
	global $wgOut, $wgContLang;

	$rnd = new RandomPage();
	$rnd->setNamespace( $wgContLang->getNsIndex( $par ) );
	$rnd->setRedirect( false );

	$title = $rnd->getRandomTitle();

	if( is_null( $title ) ) {
		$wgOut->addWikiText( wfMsg( 'randompage-nopages' ) );
		return;
	}

	$wgOut->reportTime();
	$wgOut->redirect( $title->getFullUrl() );
}


class RandomPage {
	private $namespace = NS_MAIN;  // namespace to select pages from
	private $redirect = false;     // select redirects instead of normal pages?

	public function getNamespace ( ) {
		return $this->namespace;
	}
	public function setNamespace ( $ns ) {
		if( $ns < NS_MAIN ) $ns = NS_MAIN;
		$this->namespace = $ns;
	}
	public function getRedirect ( ) {
		return $this->redirect;
	}
	public function setRedirect ( $redirect ) {
		$this->redirect = $redirect;
	}

	/**
	 * Choose a random title.
	 * @return Title object (or null if nothing to choose from)
	 */
	public function getRandomTitle ( ) {
		$randstr = wfRandom();
		$row = $this->selectRandomPageFromDB( $randstr );

		if( !$row ) {
			// Try again with a normalized value
			$randstr = wfRandom( $this->getMaxPageRandom() );
			$row = $this->selectRandomPageFromDB( $randstr );
		}

		if( $row )
			return Title::makeTitleSafe( $this->namespace, $row->page_title );
		else
			return null;
	}

	private function selectRandomPageFromDB ( $randstr ) {
		global $wgExtraRandompageSQL;
		$fname = 'RandomPage::selectRandomPageFromDB';

		$dbr = wfGetDB( DB_SLAVE );

		$from = $this->getSQLFrom( $dbr );
		$where = $this->getSQLWhere( $dbr );

		$sql = "SELECT page_title FROM $from
			WHERE $where AND page_random > $randstr
			ORDER BY page_random";

		$sql = $dbr->limitResult( $sql, 1, 0 );
		$res = $dbr->query( $sql, $fname );
		return $dbr->fetchObject( $res );
	}

	private function getMaxPageRandom () {
		$fname = 'RandomPage::getMaxPageRandom';

		$dbr = wfGetDB( DB_SLAVE );

		$from = $this->getSQLFrom( $dbr );
		$where = $this->getSQLWhere( $dbr );

		$sql = "SELECT MAX(page_random) AS max FROM $from WHERE $where";

		$sql = $dbr->limitResult( $sql, 1, 0 );
		$res = $dbr->query( $sql, $fname );
		$row = $dbr->fetchObject( $res );

		return $row ? $row->max : 0;
	}

	private function getSQLFrom ( $dbr ) {
		$use_index = $dbr->useIndexClause( 'page_random' );
		$page = $dbr->tableName( 'page' );
		return "$page $use_index";
	}

	private function getSQLWhere ( $dbr ) {
		global $wgExtraRandompageSQL;
		$ns = (int) $this->namespace;
		$redirect = $this->redirect ? 1 : 0;
		$extra = $wgExtraRandompageSQL ? " AND ($wgExtraRandompageSQL)" : "";
		return "page_namespace = $ns AND page_is_redirect = $redirect" . $extra;
	}
}

?>
