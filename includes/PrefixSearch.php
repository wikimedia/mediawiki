<?php
/**
 * Prefix search of page names.
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
 */

/**
 * Handles searching prefixes of titles and finding any page
 * names that match. Used largely by the OpenSearch implementation.
 *
 * @ingroup Search
 */
abstract class PrefixSearch {
	/**
	 * Do a prefix search of titles and return a list of matching page names.
	 * @deprecated: Since 1.23, use TitlePrefixSearch or StringPrefixSearch classes
	 *
	 * @param $search String
	 * @param $limit Integer
	 * @param array $namespaces used if query is not explicitly prefixed
	 * @return Array of strings
	 */
	public static function titleSearch( $search, $limit, $namespaces = array() ) {
		$search = new StringPrefixSearch;
		return $search->search( $search, $limit, $namespaces );
	}

	/**
	 * Do a prefix search of titles and return a list of matching page names.
	 *
	 * @param $search String
	 * @param $limit Integer
	 * @param array $namespaces used if query is not explicitly prefixed
	 * @return Array of strings or Title objects
	 */
	public function search( $search, $limit, $namespaces = array() ) {
		$search = trim( $search );
		if ( $search == '' ) {
			return array(); // Return empty result
		}
		$namespaces = $this->validateNamespaces( $namespaces );

		// Find a Title which is not an interwiki and is in NS_MAIN
		$title = Title::newFromText( $search );
		if ( $title && !$title->isExternal() ) {
			$ns = array( $title->getNamespace() );
			if ( $ns[0] == NS_MAIN ) {
				$ns = $namespaces; // no explicit prefix, use default namespaces
			}
			return $this->searchBackend(
				$ns, $title->getText(), $limit );
		}

		// Is this a namespace prefix?
		$title = Title::newFromText( $search . 'Dummy' );
		if ( $title && $title->getText() == 'Dummy'
			&& $title->getNamespace() != NS_MAIN
			&& !$title->isExternal() )
		{
			$namespaces = array( $title->getNamespace() );
			$search = '';
		}

		return $this->searchBackend( $namespaces, $search, $limit );
	}

	/**
	 * Do a prefix search for all possible variants of the prefix
	 * @param $search String
	 * @param $limit Integer
	 * @param array $namespaces
	 *
	 * @return array
	 */
	public function searchWithVariants( $search, $limit, array $namespaces ) {
		wfProfileIn( __METHOD__ );
		$searches = $this->search( $search, $limit, $namespaces );

		// if the content language has variants, try to retrieve fallback results
		$fallbackLimit = $limit - count( $searches );
		if ( $fallbackLimit > 0 ) {
			global $wgContLang;

			$fallbackSearches = $wgContLang->autoConvertToAllVariants( $search );
			$fallbackSearches = array_diff( array_unique( $fallbackSearches ), array( $search ) );

			foreach ( $fallbackSearches as $fbs ) {
				$fallbackSearchResult = $this->search( $fbs, $fallbackLimit, $namespaces );
				$searches = array_merge( $searches, $fallbackSearchResult );
				$fallbackLimit -= count( $fallbackSearchResult );

				if ( $fallbackLimit == 0 ) {
					break;
				}
			}
		}
		wfProfileOut( __METHOD__ );
		return $searches;
	}

	/**
	 * When implemented in a descendant class, receives an array of Title objects and returns
	 * either an unmodified array or an array of strings corresponding to titles passed to it.
	 *
	 * @param array $titles
	 * @return array
	 */
	protected abstract function titles( array $titles );

	/**
	 * When implemented in a descendant class, receives an array of titles as strings and returns
	 * either an unmodified array or an array of Title objects corresponding to strings received.
	 *
	 * @param array $strings
	 *
	 * @return array
	 */
	protected abstract function strings( array $strings );

	/**
	 * Do a prefix search of titles and return a list of matching page names.
	 * @param $namespaces Array
	 * @param $search String
	 * @param $limit Integer
	 * @return Array of strings
	 */
	protected function searchBackend( $namespaces, $search, $limit ) {
		if ( count( $namespaces ) == 1 ) {
			$ns = $namespaces[0];
			if ( $ns == NS_MEDIA ) {
				$namespaces = array( NS_FILE );
			} elseif ( $ns == NS_SPECIAL ) {
				return $this->titles( $this->specialSearch( $search, $limit ) );
			}
		}
		$srchres = array();
		if ( wfRunHooks( 'PrefixSearchBackend', array( $namespaces, $search, $limit, &$srchres ) ) ) {
			return $this->titles( $this->defaultSearchBackend( $namespaces, $search, $limit ) );
		}
		return $this->strings( $srchres );
	}

	/**
	 * Prefix search special-case for Special: namespace.
	 *
	 * @param string $search term
	 * @param $limit Integer: max number of items to return
	 * @return Array
	 */
	protected function specialSearch( $search, $limit ) {
		global $wgContLang;

		# normalize searchKey, so aliases with spaces can be found - bug 25675
		$search = str_replace( ' ', '_', $search );

		$searchKey = $wgContLang->caseFold( $search );

		// Unlike SpecialPage itself, we want the canonical forms of both
		// canonical and alias title forms...
		$keys = array();
		foreach ( SpecialPageFactory::getList() as $page => $class ) {
			$keys[$wgContLang->caseFold( $page )] = $page;
		}

		foreach ( $wgContLang->getSpecialPageAliases() as $page => $aliases ) {
			if ( !array_key_exists( $page, SpecialPageFactory::getList() ) ) {# bug 20885
				continue;
			}

			foreach ( $aliases as $alias ) {
				$keys[$wgContLang->caseFold( $alias )] = $alias;
			}
		}
		ksort( $keys );

		$srchres = array();
		foreach ( $keys as $pageKey => $page ) {
			if ( $searchKey === '' || strpos( $pageKey, $searchKey ) === 0 ) {
				// bug 27671: Don't use SpecialPage::getTitleFor() here because it
				// localizes its input leading to searches for e.g. Special:All
				// returning Spezial:MediaWiki-Systemnachrichten and returning
				// Spezial:Alle_Seiten twice when $wgLanguageCode == 'de'
				$srchres[] = Title::makeTitleSafe( NS_SPECIAL, $page );
			}

			if ( count( $srchres ) >= $limit ) {
				break;
			}
		}

		return $srchres;
	}

	/**
	 * Unless overridden by PrefixSearchBackend hook...
	 * This is case-sensitive (First character may
	 * be automatically capitalized by Title::secureAndSpit()
	 * later on depending on $wgCapitalLinks)
	 *
	 * @param array $namespaces namespaces to search in
	 * @param string $search term
	 * @param $limit Integer: max number of items to return
	 * @return Array of Title objects
	 */
	protected function defaultSearchBackend( $namespaces, $search, $limit ) {
		$ns = array_shift( $namespaces ); // support only one namespace
		if ( in_array( NS_MAIN, $namespaces ) ) {
			$ns = NS_MAIN; // if searching on many always default to main
		}

		$t = Title::newFromText( $search, $ns );
		$prefix = $t ? $t->getDBkey() : '';
		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->select( 'page',
			array( 'page_id', 'page_namespace', 'page_title' ),
			array(
				'page_namespace' => $ns,
				'page_title ' . $dbr->buildLike( $prefix, $dbr->anyString() )
			),
			__METHOD__,
			array( 'LIMIT' => $limit, 'ORDER BY' => 'page_title' )
		);
		$srchres = array();
		foreach ( $res as $row ) {
			$srchres[] = Title::newFromRow( $row );
		}
		return $srchres;
	}

	/**
	 * Validate an array of numerical namespace indexes
	 *
	 * @param $namespaces Array
	 * @return Array (default: contains only NS_MAIN)
	 */
	protected function validateNamespaces( $namespaces ) {
		global $wgContLang;

		// We will look at each given namespace against wgContLang namespaces
		$validNamespaces = $wgContLang->getNamespaces();
		if ( is_array( $namespaces ) && count( $namespaces ) > 0 ) {
			$valid = array();
			foreach ( $namespaces as $ns ) {
				if ( is_numeric( $ns ) && array_key_exists( $ns, $validNamespaces ) ) {
					$valid[] = $ns;
				}
			}
			if ( count( $valid ) > 0 ) {
				return $valid;
			}
		}

		return array( NS_MAIN );
	}
}

/**
 * Performs prefix search, returning Title objects
 * @ingroup Search
 */
class TitlePrefixSearch extends PrefixSearch {

	protected function titles( array $titles ) {
		return $titles;
	}

	protected function strings( array $strings ) {
		$titles = array_map( 'Title::newFromText', $strings );
		$lb = new LinkBatch( $titles );
		$lb->setCaller( __METHOD__ );
		$lb->execute();
		return $titles;
	}
}

/**
 * Performs prefix search, returning strings
 * @ingroup Search
 */
class StringPrefixSearch extends PrefixSearch {

	protected function titles( array $titles ) {
		return array_map( function( Title $t ) { return $t->getPrefixedText(); }, $titles );
	}

	protected function strings( array $strings ) {
		return $strings;
	}
}
