<?php

/**
 * Special page to direct the user to a random page in specified category
 *
 * @addtogroup SpecialPage
 * @author VasilievVV <vasilvv@gmail.com>, based on SpecialRandompage.php code
 * @license GNU General Public Licence 2.0 or later
 */

/**
 * Main execution point
 * @param $par Category to select the page from
 */
function wfSpecialRandomincategory( $par = null ) {
	global $wgOut, $wgRequest;

	if( is_null( $par ) ) {
		if ( $requestCategory = $wgRequest->getVal( 'category' ) ) {
			$par = $requestCategory;
		}
		else {
			$wgOut->addHTML( RandomPageInCategory::getForm() );
			return;
		}
	}

	$rnd = new RandomPageInCategory();
	$rnd->setCategory( $par );

	$title = $rnd->getRandomTitle();

	if( is_null( $title ) ) {
		$wgOut->addWikiText( wfMsg( 'randomincategory-nocategory', $par ) );
		$wgOut->addHTML( RandomPageInCategory::getForm( $par ) );
		return;
	}

	$wgOut->reportTime();
	$wgOut->redirect( $title->getFullUrl() );
}


/**
 * Special page to direct the user to a random page in specified category
 *
 * @addtogroup SpecialPage
 */
class RandomPageInCategory {
	private $category = null;

	public function getCategory ( ) {
		return $this->namespace;
	}
	public function setCategory ( $cat ) {
		$category = Title::makeTitleSafe( NS_CATEGORY, $cat );
		$this->category = $category->getDBKey();
	}

	/**
	 * Choose a random title. Based on Special:Random
	 * @return Title object (or null if nothing to choose from)
	 */
	public function getRandomTitle ( ) {
		$randstr = wfRandom();
		$row = $this->selectRandomPageFromDB( $randstr );

		if( !$row )
			$row = $this->selectRandomPageFromDB( "0" );

		if( $row )
			return Title::newFromText( $row->page_title, $row->page_namespace );
		else
			return null;
	}

	private function selectRandomPageFromDB ( $randstr ) {
		global $wgExtraRandompageSQL, $wgOut;
		$fname = 'RandomPageInCategory::selectRandomPageFromDB';

		$dbr = wfGetDB( DB_SLAVE );

		$use_index = $dbr->useIndexClause( 'page_random' );
		$page = $dbr->tableName( 'page' );
		$categorylinks = $dbr->tableName( 'categorylinks' );
		$category = $dbr->addQuotes( $this->category );

		$extra = $wgExtraRandompageSQL ? "AND ($wgExtraRandompageSQL)" : "";
		$sql = "SELECT page_namespace, page_title
			FROM $page $use_index JOIN $categorylinks ON page_id = cl_from
			WHERE page_is_redirect = 0
			AND page_random >= $randstr
			AND cl_to = $category
			$extra
			ORDER BY page_random";

		$sql = $dbr->limitResult( $sql, 1, 0 );
		$res = $dbr->query( $sql, $fname );
		return $dbr->fetchObject( $res );
	}
	
	public static function getForm( $par = null ) {
		global $wgScript, $wgTitle, $wgRequest;

		if( !( $category = $par ) ) {
			$category = $wgRequest->getVal( 'category' );
		}

		$f =
			Xml::openElement( 'form', array( 'method' => 'get', 'action' => $wgScript ) ) .
				Xml::openElement( 'fieldset' ) .
					Xml::element( 'legend', array(), wfMsg( 'randomincategory' ) ) .
					Xml::hidden( 'title', $wgTitle->getPrefixedText() ) .
					Xml::openElement( 'p' ) .
						Xml::label( wfMsg( 'randomincategory-label' ), 'category' ) . ' ' .
						Xml::input( 'category', null, $category, array( 'id' => 'category' ) ) . ' ' .
						Xml::submitButton( wfMsg( 'randomincategory-submit' ) ) .
					Xml::closeElement( 'p' ) .
				Xml::closeElement( 'fieldset' ) .
			Xml::closeElement( 'form' );
		return $f;
	}
}



