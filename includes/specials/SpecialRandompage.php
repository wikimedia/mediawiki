<?php

/**
 * Special page to direct the user to a random page
 *
 * @ingroup SpecialPage
 * @author Rob Church <robchur@gmail.com>, Ilmari Karonen
 * @license GNU General Public Licence 2.0 or later
 */
class RandomPage extends SpecialPage {
	private $namespaces;  // namespaces to select pages from
	protected $isRedir = false; // should the result be a redirect?
	protected $extra = array(); // Extra SQL statements

	public function __construct( $name = 'Randompage' ){
		global $wgContentNamespaces;
		$this->namespaces = $wgContentNamespaces;
		parent::__construct( $name );
	}

	public function getNamespaces() {
		return $this->namespaces;
	}

	public function setNamespace ( $ns ) {
		if( !$ns || $ns < NS_MAIN ) $ns = NS_MAIN;
		$this->namespaces = array( $ns );
	}

	// select redirects instead of normal pages?
	public function isRedirect(){
		return $this->isRedir;
	}

	public function execute( $par ) {
		global $wgOut, $wgContLang;

		if ($par) {
			$this->setNamespace( $wgContLang->getNsIndex( $par ) );
		}

		$title = $this->getRandomTitle();

		if( is_null( $title ) ) {
			$this->setHeaders();
			$wgOut->addWikiMsg( strtolower( $this->mName ) . '-nopages', 
				$this->getNsList(), count( $this->namespaces ) );
			return;
		}

		$query = $this->isRedirect() ? 'redirect=no' : '';
		$wgOut->redirect( $title->getFullUrl( $query ) );
	}

	/**
	 * Get a comma-delimited list of namespaces we don't have
	 * any pages in
	 * @return String
	 */
	private function getNsList() {
		global $wgContLang;
		$nsNames = array();
		foreach( $this->namespaces as $n ) {
			if( $n === NS_MAIN )
				$nsNames[] = wfMsgForContent( 'blanknamespace' );
			else
				$nsNames[] = $wgContLang->getNsText( $n );
		}
		return $wgContLang->commaList( $nsNames );
	}


	/**
	 * Choose a random title.
	 * @return Title object (or null if nothing to choose from)
	 */
	public function getRandomTitle() {
		$randstr = wfRandom();
		$title = null;
		if ( !wfRunHooks( 'SpecialRandomGetRandomTitle', array( &$randstr, &$this->isRedir, &$this->namespaces, &$this->extra, &$title ) ) ) {
			return $title;
		}
		$row = $this->selectRandomPageFromDB( $randstr );

		/* If we picked a value that was higher than any in
		 * the DB, wrap around and select the page with the
		 * lowest value instead!  One might think this would
		 * skew the distribution, but in fact it won't cause
		 * any more bias than what the page_random scheme
		 * causes anyway.  Trust me, I'm a mathematician. :)
		 */
		if( !$row )
			$row = $this->selectRandomPageFromDB( "0" );

		if( $row )
			return Title::makeTitleSafe( $row->page_namespace, $row->page_title );
		else
			return null;
	}

	private function selectRandomPageFromDB( $randstr ) {
		global $wgExtraRandompageSQL;
		$dbr = wfGetDB( DB_SLAVE );

		$use_index = $dbr->useIndexClause( 'page_random' );
		$page = $dbr->tableName( 'page' );

		$ns = implode( ",", $this->namespaces );
		$redirect = $this->isRedirect() ? 1 : 0;
		
		if ( $wgExtraRandompageSQL ) {
			$this->extra[] = $wgExtraRandompageSQL;
		}
		if ( $this->addExtraSQL() ) {
			$this->extra[] = $this->addExtraSQL();
		}
		$extra = '';
		if ( $this->extra ) {
			$extra = 'AND (' . implode( ') AND (', $this->extra ) . ')';
		}
		$sql = "SELECT page_title, page_namespace
			FROM $page $use_index
			WHERE page_namespace IN ( $ns )
			AND page_is_redirect = $redirect
			AND page_random >= $randstr
			$extra
			ORDER BY page_random";

		$sql = $dbr->limitResult( $sql, 1, 0 );
		$res = $dbr->query( $sql, __METHOD__ );
		return $dbr->fetchObject( $res );
	}

	/* an alternative to $wgExtraRandompageSQL so subclasses
	 * can add their own SQL by overriding this function
	 * @deprecated, append to $this->extra instead
	 */
	public function addExtraSQL() {
		return '';
	}
}
