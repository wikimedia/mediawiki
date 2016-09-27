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
 * @deprecated Since 1.27, Use SearchEngine::prefixSearchSubpages or SearchEngine::completionSearch
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
	public static function titleSearch( $search, $limit, $namespaces = [], $offset = 0 ) {
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
	public function search( $search, $limit, $namespaces = [], $offset = 0 ) {
		$search = trim( $search );
		if ( $search == '' ) {
			return []; // Return empty result
		}

		$hasNamespace = $this->extractNamespace( $search );
		if ( $hasNamespace ) {
			list( $namespace, $search ) = $hasNamespace;
			$namespaces = [ $namespace ];
		} else {
			$namespaces = $this->validateNamespaces( $namespaces );
			Hooks::run( 'PrefixSearchExtractNamespace', [ &$namespaces, &$search ] );
		}

		return $this->searchBackend( $namespaces, $search, $limit, $offset );
	}

	/**
	 * Figure out if given input contains an explicit namespace.
	 *
	 * @param string $input
	 * @return false|array Array of namespace and remaining text, or false if no namespace given.
	 */
	protected function extractNamespace( $input ) {
		if ( strpos( $input, ':' ) === false ) {
			return false;
		}

		// Namespace prefix only
		$title = Title::newFromText( $input . 'Dummy' );
		if (
			$title &&
			$title->getText() === 'Dummy' &&
			!$title->inNamespace( NS_MAIN ) &&
			!$title->isExternal()
		) {
			return [ $title->getNamespace(), '' ];
		}

		// Namespace prefix with additional input
		$title = Title::newFromText( $input );
		if (
			$title &&
			!$title->inNamespace( NS_MAIN ) &&
			!$title->isExternal()
		) {
			// getText provides correct capitalization
			return [ $title->getNamespace(), $title->getText() ];
		}

		return false;
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
			$fallbackSearches = array_diff( array_unique( $fallbackSearches ), [ $search ] );

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
				$namespaces = [ NS_FILE ];
			} elseif ( $ns == NS_SPECIAL ) {
				return $this->titles( $this->specialSearch( $search, $limit, $offset ) );
			}
		}
		$srchres = [];
		if ( Hooks::run(
			'PrefixSearchBackend',
			[ $namespaces, $search, $limit, &$srchres, $offset ]
		) ) {
			return $this->titles( $this->defaultSearchBackend( $namespaces, $search, $limit, $offset ) );
		}
		return $this->strings(
			$this->handleResultFromHook( $srchres, $namespaces, $search, $limit, $offset ) );
	}

	private function handleResultFromHook( $srchres, $namespaces, $search, $limit, $offset ) {
		if ( $offset === 0 ) {
			// Only perform exact db match if offset === 0
			// This is still far from perfect but at least we avoid returning the
			// same title afain and again when the user is scrolling with a query
			// that matches a title in the db.
			$rescorer = new SearchExactMatchRescorer();
			$srchres = $rescorer->rescore( $search, $namespaces, $srchres, $limit );
		}
		return $srchres;
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
				return [];
			}
			$special = SpecialPageFactory::getPage( $specialTitle->getText() );
			if ( $special ) {
				$subpages = $special->prefixSearchSubpages( $subpageSearch, $limit, $offset );
				return array_map( function ( $sub ) use ( $specialTitle ) {
					return $specialTitle->getSubpage( $sub );
				}, $subpages );
			} else {
				return [];
			}
		}

		# normalize searchKey, so aliases with spaces can be found - bug 25675
		$searchKey = str_replace( ' ', '_', $searchKey );
		$searchKey = $wgContLang->caseFold( $searchKey );

		// Unlike SpecialPage itself, we want the canonical forms of both
		// canonical and alias title forms...
		$keys = [];
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

		$srchres = [];
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
	 * @param array|null $namespaces Namespaces to search in
	 * @param string $search Term
	 * @param int $limit Max number of items to return
	 * @param int $offset Number of items to skip
	 * @return Title[] Array of Title objects
	 */
	public function defaultSearchBackend( $namespaces, $search, $limit, $offset ) {
		// Backwards compatability with old code. Default to NS_MAIN if no namespaces provided.
		if ( $namespaces === null ) {
			$namespaces = [];
		}
		if ( !$namespaces ) {
			$namespaces[] = NS_MAIN;
		}

		// Construct suitable prefix for each namespace. They differ in cases where
		// some namespaces always capitalize and some don't.
		$prefixes = [];
		foreach ( $namespaces as $namespace ) {
			// For now, if special is included, ignore the other namespaces
			if ( $namespace == NS_SPECIAL ) {
				return $this->specialSearch( $search, $limit, $offset );
			}

			$title = Title::makeTitleSafe( $namespace, $search );
			// Why does the prefix default to empty?
			$prefix = $title ? $title->getDBkey() : '';
			$prefixes[$prefix][] = $namespace;
		}

		$dbr = wfGetDB( DB_REPLICA );
		// Often there is only one prefix that applies to all requested namespaces,
		// but sometimes there are two if some namespaces do not always capitalize.
		$conds = [];
		foreach ( $prefixes as $prefix => $namespaces ) {
			$condition = [
				'page_namespace' => $namespaces,
				'page_title' . $dbr->buildLike( $prefix, $dbr->anyString() ),
			];
			$conds[] = $dbr->makeList( $condition, LIST_AND );
		}

		$table = 'page';
		$fields = [ 'page_id', 'page_namespace', 'page_title' ];
		$conds = $dbr->makeList( $conds, LIST_OR );
		$options = [
			'LIMIT' => $limit,
			'ORDER BY' => [ 'page_title', 'page_namespace' ],
			'OFFSET' => $offset
		];

		$res = $dbr->select( $table, $fields, $conds, __METHOD__, $options );

		return iterator_to_array( TitleArray::newFromResult( $res ) );
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
			$valid = [];
			foreach ( $namespaces as $ns ) {
				if ( is_numeric( $ns ) && array_key_exists( $ns, $validNamespaces ) ) {
					$valid[] = $ns;
				}
			}
			if ( count( $valid ) > 0 ) {
				return $valid;
			}
		}

		return [ NS_MAIN ];
	}
}

/**
 * Performs prefix search, returning Title objects
 * @deprecated Since 1.27, Use SearchEngine::prefixSearchSubpages or SearchEngine::completionSearch
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
 * @deprecated Since 1.27, Use SearchEngine::prefixSearchSubpages or SearchEngine::completionSearch
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
