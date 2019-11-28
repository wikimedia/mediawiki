<?php
/**
 * Rescores results from a prefix search/opensearch to make sure the
 * exact match is the first result.
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
 * An utility class to rescore search results by looking for an exact match
 * in the db and add the page found to the first position.
 *
 * NOTE: extracted from TitlePrefixSearch
 * @ingroup Search
 */
class SearchExactMatchRescorer {
	/**
	 * Default search backend does proper prefix searching, but custom backends
	 * may sort based on other algorithms that may cause the exact title match
	 * to not be in the results or be lower down the list.
	 * @param string $search the query
	 * @param int[] $namespaces
	 * @param string[] $srchres results
	 * @param int $limit the max number of results to return
	 * @return string[] munged results
	 */
	public function rescore( $search, $namespaces, $srchres, $limit ) {
		// Pick namespace (based on PrefixSearch::defaultSearchBackend)
		$ns = in_array( NS_MAIN, $namespaces ) ? NS_MAIN : reset( $namespaces );
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
	 * @param string[] $titles
	 * @return array redirect target prefixedText to index of title in titles
	 *   that is a redirect to it.
	 */
	private function redirectTargetsToRedirect( array $titles ) {
		$result = [];
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
	 * Returns an array where the element of $array at index $key becomes
	 * the first element.
	 * @param int $key key to pull to the front
	 * @param array $array
	 * @return array $array with the item at $key pulled to the front
	 */
	private function pullFront( $key, array $array ) {
		$cut = array_splice( $array, $key, 1 );
		array_unshift( $array, $cut[0] );
		return $array;
	}

	/**
	 * Get a redirect's destination from a title
	 * @param Title $title A title to redirect. It may not redirect or even exist
	 * @return null|string If title exists and redirects, get the destination's prefixed name
	 */
	private function getRedirectTarget( $title ) {
		$page = WikiPage::factory( $title );
		if ( !$page->exists() ) {
			return null;
		}
		$redir = $page->getRedirectTarget();
		return $redir ? $redir->getPrefixedText() : null;
	}
}
