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
class PrefixSearch {
	/**
	 * Do a prefix search of titles and return a list of matching page names.
	 *
	 * @param $search String
	 * @param $limit Integer
	 * @param array $namespaces used if query is not explicitly prefixed
	 * @return Array of strings
	 */
	public static function titleSearch( $search, $limit, $namespaces = array() ) {
		$search = trim( $search );
		if ( $search == '' ) {
			return array(); // Return empty result
		}
		$namespaces = self::validateNamespaces( $namespaces );

		// Find a Title which is not an interwiki and is in NS_MAIN
		$title = Title::newFromText( $search );
		if ( $title && $title->getInterwiki() == '' ) {
			$ns = array( $title->getNamespace() );
			if ( $ns[0] == NS_MAIN ) {
				$ns = $namespaces; // no explicit prefix, use default namespaces
			}
			return self::searchBackend(
				$ns, $title->getText(), $limit );
		}

		// Is this a namespace prefix?
		$title = Title::newFromText( $search . 'Dummy' );
		if ( $title && $title->getText() == 'Dummy'
			&& $title->getNamespace() != NS_MAIN
			&& $title->getInterwiki() == '' ) {
			return self::searchBackend(
				array( $title->getNamespace() ), '', $limit );
		}

		return self::searchBackend( $namespaces, $search, $limit );
	}

	/**
	 * Do a prefix search of titles and return a list of matching page names.
	 * @param $namespaces Array
	 * @param $search String
	 * @param $limit Integer
	 * @return Array of strings
	 */
	protected static function searchBackend( $namespaces, $search, $limit ) {
		if ( count( $namespaces ) == 1 ) {
			$ns = $namespaces[0];
			if ( $ns == NS_MEDIA ) {
				$namespaces = array( NS_FILE );
			} elseif ( $ns == NS_SPECIAL ) {
				return self::specialSearch( $search, $limit );
			}
		}
		$srchres = array();
		if ( wfRunHooks( 'PrefixSearchBackend', array( $namespaces, $search, $limit, &$srchres ) ) ) {
			return self::defaultSearchBackend( $namespaces, $search, $limit );
		}
		return $srchres;
	}

	/**
	 * Prefix search special-case for Special: namespace.
	 *
	 * @param string $search term
	 * @param $limit Integer: max number of items to return
	 * @return Array
	 */
	protected static function specialSearch( $search, $limit ) {
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
				wfSuppressWarnings();
				// bug 27671: Don't use SpecialPage::getTitleFor() here because it
				// localizes its input leading to searches for e.g. Special:All
				// returning Spezial:MediaWiki-Systemnachrichten and returning
				// Spezial:Alle_Seiten twice when $wgLanguageCode == 'de'
				$srchres[] = Title::makeTitleSafe( NS_SPECIAL, $page )->getPrefixedText();
				wfRestoreWarnings();
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
	 * @return Array of title strings
	 */
	protected static function defaultSearchBackend( $namespaces, $search, $limit ) {
		$ns = array_shift( $namespaces ); // support only one namespace
		if ( in_array( NS_MAIN, $namespaces ) ) {
			$ns = NS_MAIN; // if searching on many always default to main
		}

		// Prepare nested request
		$req = new FauxRequest( array(
			'action' => 'query',
			'list' => 'allpages',
			'apnamespace' => $ns,
			'aplimit' => $limit,
			'apprefix' => $search
		));

		// Execute
		$module = new ApiMain( $req );
		$module->execute();

		// Get resulting data
		$data = $module->getResultData();

		// Reformat useful data for future printing by JSON engine
		$srchres = array();
		foreach ( (array)$data['query']['allpages'] as $pageinfo ) {
			// Note: this data will no be printable by the xml engine
			// because it does not support lists of unnamed items
			$srchres[] = $pageinfo['title'];
		}

		return $srchres;
	}

	/**
	 * Validate an array of numerical namespace indexes
	 *
	 * @param $namespaces Array
	 * @return Array (default: contains only NS_MAIN)
	 */
	protected static function validateNamespaces( $namespaces ) {
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
