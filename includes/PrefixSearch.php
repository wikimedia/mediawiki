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
	 * @deprecated Since 1.23, use TitlePrefixSearch or StringPrefixSearch classes
	 *
	 * @param string $search
	 * @param int $limit
	 * @param array $namespaces Used if query is not explicitly prefixed
	 * @param int $offset How many results to offset from the beginning
	 * @return array Array of strings
	 */
	public static function titleSearch( $search, $limit, $namespaces = array(), $offset = 0 ) {
		$prefixSearch = new StringPrefixSearch;
		return $prefixSearch->search( $search, $limit, $namespaces, $offset );
	}

	/**
	 * Do a prefix search of titles and return a list of matching page names.
	 *
	 * @param string $search
	 * @param int $limit
	 * @param array $namespaces Used if query is not explicitly prefixed
	 * @param int $offset How many results to offset from the beginning
	 * @return array Array of strings or Title objects
	 */
	public function search( $search, $limit, $namespaces = array(), $offset = 0 ) {
		$search = trim( $search );
		if ( $search == '' ) {
			return array(); // Return empty result
		}
		$namespaces = $this->validateNamespaces( $namespaces );

		// Find a Title which is not an interwiki and is in NS_MAIN
		$title = Title::newFromText( $search );
		if ( $title && !$title->isExternal() ) {
			$ns = array( $title->getNamespace() );
			$search = $title->getText();
			if ( $ns[0] == NS_MAIN ) {
				$ns = $namespaces; // no explicit prefix, use default namespaces
				Hooks::run( 'PrefixSearchExtractNamespace', array( &$ns, &$search ) );
			}
			return $this->searchBackend( $ns, $search, $limit, $offset );
		}

		// Is this a namespace prefix?
		$title = Title::newFromText( $search . 'Dummy' );
		if ( $title && $title->getText() == 'Dummy'
			&& $title->getNamespace() != NS_MAIN
			&& !$title->isExternal() )
		{
			$namespaces = array( $title->getNamespace() );
			$search = '';
		} else {
			Hooks::run( 'PrefixSearchExtractNamespace', array( &$namespaces, &$search ) );
		}

		return $this->searchBackend( $namespaces, $search, $limit, $offset );
	}

	/**
	 * Do a prefix search for all possible variants of the prefix
	 * @param string $search
	 * @param int $limit
	 * @param array $namespaces
	 * @param int $offset How many results to offset from the beginning
	 *
	 * @return array
	 */
	public function searchWithVariants( $search, $limit, array $namespaces, $offset = 0 ) {
		$searches = $this->search( $search, $limit, $namespaces, $offset );

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
		return $searches;
	}

	/**
	 * When implemented in a descendant class, receives an array of Title objects and returns
	 * either an unmodified array or an array of strings corresponding to titles passed to it.
	 *
	 * @param array $titles
	 * @return array
	 */
	abstract protected function titles( array $titles );

	/**
	 * When implemented in a descendant class, receives an array of titles as strings and returns
	 * either an unmodified array or an array of Title objects corresponding to strings received.
	 *
	 * @param array $strings
	 *
	 * @return array
	 */
	abstract protected function strings( array $strings );

	/**
	 * Do a prefix search of titles and return a list of matching page names.
	 * @param array $namespaces
	 * @param string $search
	 * @param int $limit
	 * @param int $offset How many results to offset from the beginning
	 * @return array Array of strings
	 */
	protected function searchBackend( $namespaces, $search, $limit, $offset ) {
		if ( count( $namespaces ) == 1 ) {
			$ns = $namespaces[0];
			if ( $ns == NS_MEDIA ) {
				$namespaces = array( NS_FILE );
			} elseif ( $ns == NS_SPECIAL ) {
				return $this->titles( $this->specialSearch( $search, $limit, $offset ) );
			}
		}
		$srchres = array();
		if ( Hooks::run(
			'PrefixSearchBackend',
			array( $namespaces, $search, $limit, &$srchres, $offset )
		) ) {
			return $this->titles( $this->defaultSearchBackend( $namespaces, $search, $limit, $offset ) );
		}
		return $this->strings( $this->handleResultFromHook( $srchres, $namespaces, $search, $limit ) );
	}

	/**
	 * Default search backend does proper prefix searching, but custom backends
	 * may sort based on other algorythms that may cause the exact title match
	 * to not be in the results or be lower down the list.
	 * @param array $srchres results from the hook
	 * @return array munged results from the hook
	 */
	private function handleResultFromHook( $srchres, $namespaces, $search, $limit ) {
		// Pick namespace (based on PrefixSearch::defaultSearchBackend)
		$ns = in_array( NS_MAIN, $namespaces ) ? NS_MAIN : $namespaces[0];
		$t = Title::newFromText( $search, $ns );
		if ( !$t || !$t->exists() ) {
			// No exact match so just return the search results
			return $srchres;
		}
		$string = $t->getPrefixedText();
		$key = array_search( $string, $srchres );
		if ( $key !== false ) {
			// Exact match was in the results so just move it to the front
			return $this->pullFront( $key, $srchres );
		}
		// Exact match not in the search results so check for some redirect handling cases
		if ( $t->isRedirect() ) {
			$target = $this->getRedirectTarget( $t );
			$key = array_search( $target, $srchres );
			if ( $key !== false ) {
				// Exact match is a redirect to one of the returned matches so pull the
				// returned match to the front.  This might look odd but the alternative
				// is to put the redirect in front and drop the match.  The name of the
				// found match is often more descriptive/better formed than the name of
				// the redirect AND by definition they share a prefix.  Hopefully this
				// choice is less confusing and more helpful.  But it might not be.  But
				// it is the choice we're going with for now.
				return $this->pullFront( $key, $srchres );
			}
			$redirectTargetsToRedirect = $this->redirectTargetsToRedirect( $srchres );
			if ( isset( $redirectTargetsToRedirect[$target] ) ) {
				// The exact match and something in the results list are both redirects
				// to the same thing!  In this case we'll pull the returned match to the
				// top following the same logic above.  Again, it might not be a perfect
				// choice but it'll do.
				return $this->pullFront( $redirectTargetsToRedirect[$target], $srchres );
			}
		} else {
			$redirectTargetsToRedirect = $this->redirectTargetsToRedirect( $srchres );
			if ( isset( $redirectTargetsToRedirect[$string] ) ) {
				// The exact match is the target of a redirect already in the results list so remove
				// the redirect from the results list and push the exact match to the front
				array_splice( $srchres, $redirectTargetsToRedirect[$string], 1 );
				array_unshift( $srchres, $string );
				return $srchres;
			}
		}

		// Exact match is totally unique from the other results so just add it to the front
		array_unshift( $srchres, $string );
		// And roll one off the end if the results are too long
		if ( count( $srchres ) > $limit ) {
			array_pop( $srchres );
		}
		return $srchres;
	}

	/**
	 * @param Array(string) $titles as strings
	 * @return Array(string => int) redirect target prefixedText to index of title in titles
	 *   that is a redirect to it.
	 */
	private function redirectTargetsToRedirect( $titles ) {
		$result = array();
		foreach ( $titles as $key => $titleText ) {
			$title = Title::newFromText( $titleText );
			if ( !$title || !$title->isRedirect() ) {
				continue;
			}
			$target = $this->getRedirectTarget( $title );
			if ( !$target ) {
				continue;
			}
			$result[$target] = $key;
		}
		return $result;
	}

	/**
	 * @param int $key key to pull to the front
	 * @return array $array with the item at $key pulled to the front
	 */
	private function pullFront( $key, $array ) {
		$cut = array_splice( $array, $key, 1 );
		array_unshift( $array, $cut[0] );
		return $array;
	}

	private function getRedirectTarget( $title ) {
		$page = WikiPage::factory( $title );
		if ( !$page->exists() ) {
			return null;
		}
		return $page->getRedirectTarget()->getPrefixedText();
	}

	/**
	 * Prefix search special-case for Special: namespace.
	 *
	 * @param string $search Term
	 * @param int $limit Max number of items to return
	 * @param int $offset Number of items to offset
	 * @return array
	 */
	protected function specialSearch( $search, $limit, $offset ) {
		global $wgContLang;

		$searchParts = explode( '/', $search, 2 );
		$searchKey = $searchParts[0];
		$subpageSearch = isset( $searchParts[1] ) ? $searchParts[1] : null;

		// Handle subpage search separately.
		if ( $subpageSearch !== null ) {
			// Try matching the full search string as a page name
			$specialTitle = Title::makeTitleSafe( NS_SPECIAL, $searchKey );
			if ( !$specialTitle ) {
				return array();
			}
			$special = SpecialPageFactory::getPage( $specialTitle->getText() );
			if ( $special ) {
				$subpages = $special->prefixSearchSubpages( $subpageSearch, $limit, $offset );
				return array_map( function ( $sub ) use ( $specialTitle ) {
					return $specialTitle->getSubpage( $sub );
				}, $subpages );
			} else {
				return array();
			}
		}

		# normalize searchKey, so aliases with spaces can be found - bug 25675
		$searchKey = str_replace( ' ', '_', $searchKey );
		$searchKey = $wgContLang->caseFold( $searchKey );

		// Unlike SpecialPage itself, we want the canonical forms of both
		// canonical and alias title forms...
		$keys = array();
		foreach ( SpecialPageFactory::getNames() as $page ) {
			$keys[$wgContLang->caseFold( $page )] = $page;
		}

		foreach ( $wgContLang->getSpecialPageAliases() as $page => $aliases ) {
			if ( !in_array( $page, SpecialPageFactory::getNames() ) ) {# bug 20885
				continue;
			}

			foreach ( $aliases as $alias ) {
				$keys[$wgContLang->caseFold( $alias )] = $alias;
			}
		}
		ksort( $keys );

		$srchres = array();
		$skipped = 0;
		foreach ( $keys as $pageKey => $page ) {
			if ( $searchKey === '' || strpos( $pageKey, $searchKey ) === 0 ) {
				// bug 27671: Don't use SpecialPage::getTitleFor() here because it
				// localizes its input leading to searches for e.g. Special:All
				// returning Spezial:MediaWiki-Systemnachrichten and returning
				// Spezial:Alle_Seiten twice when $wgLanguageCode == 'de'
				if ( $offset > 0 && $skipped < $offset ) {
					$skipped++;
					continue;
				}
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
	 * @param array $namespaces Namespaces to search in
	 * @param string $search Term
	 * @param int $limit Max number of items to return
	 * @param int $offset Number of items to skip
	 * @return array Array of Title objects
	 */
	protected function defaultSearchBackend( $namespaces, $search, $limit, $offset ) {
		$ns = array_shift( $namespaces ); // support only one namespace
		if ( in_array( NS_MAIN, $namespaces ) ) {
			$ns = NS_MAIN; // if searching on many always default to main
		}

		$t = null;
		if ( is_string( $search ) ) {
			$t = Title::newFromText( $search, $ns );
		}

		$prefix = $t ? $t->getDBkey() : '';
		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->select( 'page',
			array( 'page_id', 'page_namespace', 'page_title' ),
			array(
				'page_namespace' => $ns,
				'page_title ' . $dbr->buildLike( $prefix, $dbr->anyString() )
			),
			__METHOD__,
			array(
				'LIMIT' => $limit,
				'ORDER BY' => 'page_title',
				'OFFSET' => $offset
			)
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
	 * @param array $namespaces
	 * @return array (default: contains only NS_MAIN)
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
		return array_map( function ( Title $t ) {
			return $t->getPrefixedText();
		}, $titles );
	}

	protected function strings( array $strings ) {
		return $strings;
	}
}
