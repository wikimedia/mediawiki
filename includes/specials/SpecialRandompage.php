<?php
/**
 * Implements Special:Randompage
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup SpecialPage
 * @author Rob Church <robchur@gmail.com>, Ilmari Karonen
 */

/**
 * Special page to direct the user to a random page
 *
 * @ingroup SpecialPage
 */
class RandomPage extends SpecialPage {
	private $namespaces; // namespaces to select pages from
	protected $isRedir = false; // should the result be a redirect?
	protected $extra = array(); // Extra SQL statements

	public function __construct( $name = 'Randompage' ) {
		$this->namespaces = MWNamespace::getContentNamespaces();
		parent::__construct( $name );
	}

	public function getNamespaces() {
		return $this->namespaces;
	}

	public function setNamespace( $ns ) {
		if ( !$ns || $ns < NS_MAIN ) {
			$ns = NS_MAIN;
		}
		$this->namespaces = array( $ns );
	}

	// select redirects instead of normal pages?
	public function isRedirect() {
		return $this->isRedir;
	}

	public function execute( $par ) {
		global $wgContLang;

		if ( is_string( $par ) ) {
			// Testing for stringiness since we want to catch
			// the empty string to mean main namespace only.
			$this->setNamespace( $wgContLang->getNsIndex( $par ) );
		}

		$title = $this->getRandomTitle();

		if ( is_null( $title ) ) {
			$this->setHeaders();
			// Message: randompage-nopages, randomredirect-nopages
			$this->getOutput()->addWikiMsg( strtolower( $this->getName() ) . '-nopages',
				$this->getNsList(), count( $this->namespaces ) );

			return;
		}

		$redirectParam = $this->isRedirect() ? array( 'redirect' => 'no' ) : array();
		$query = array_merge( $this->getRequest()->getValues(), $redirectParam );
		unset( $query['title'] );
		$this->getOutput()->redirect( $title->getFullURL( $query ) );
	}

	/**
	 * Get a comma-delimited list of namespaces we don't have
	 * any pages in
	 * @return string
	 */
	private function getNsList() {
		global $wgContLang;
		$nsNames = array();
		foreach ( $this->namespaces as $n ) {
			if ( $n === NS_MAIN ) {
				$nsNames[] = $this->msg( 'blanknamespace' )->plain();
			} else {
				$nsNames[] = $wgContLang->getNsText( $n );
			}
		}

		return $wgContLang->commaList( $nsNames );
	}

	/**
	 * Choose a random title.
	 * @return Title|null Title object (or null if nothing to choose from)
	 */
	public function getRandomTitle() {
		$randstr = wfRandom();
		$title = null;

		if ( !Hooks::run(
			'SpecialRandomGetRandomTitle',
			array( &$randstr, &$this->isRedir, &$this->namespaces, &$this->extra, &$title )
		) ) {
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
		if ( !$row ) {
			$row = $this->selectRandomPageFromDB( "0" );
		}

		if ( $row ) {
			return Title::makeTitleSafe( $row->page_namespace, $row->page_title );
		}

		return null;
	}

	protected function getQueryInfo( $randstr ) {
		$redirect = $this->isRedirect() ? 1 : 0;

		return array(
			'tables' => array( 'page' ),
			'fields' => array( 'page_title', 'page_namespace' ),
			'conds' => array_merge( array(
				'page_namespace' => $this->namespaces,
				'page_is_redirect' => $redirect,
				'page_random >= ' . $randstr
			), $this->extra ),
			'options' => array(
				'ORDER BY' => 'page_random',
				'LIMIT' => 1,
			),
			'join_conds' => array()
		);
	}

	private function selectRandomPageFromDB( $randstr, $fname = __METHOD__ ) {
		$dbr = wfGetDB( DB_SLAVE );

		$query = $this->getQueryInfo( $randstr );
		$res = $dbr->select(
			$query['tables'],
			$query['fields'],
			$query['conds'],
			$fname,
			$query['options'],
			$query['join_conds']
		);

		return $dbr->fetchObject( $res );
	}

	protected function getGroupName() {
		return 'redirects';
	}
}
