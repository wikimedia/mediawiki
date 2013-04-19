<?php
/**
 *
 * Copyright © 19.04.13 by the authors listed below.
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
 * with this program; if not, write to the	protected $hook;
 Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @license GPL 2+
 * @file
 *
 * @author daniel
 */

/**
 * Augments language links by calling a hook after loading them
 * from another LangLinkLoader.
 *
 * @class
 */
class HookedLangLinkLoader implements LangLinkLoader {

	protected $langLinkConverter;

	protected $loader;

	protected $hook;

	protected $internalBufferLimit;

	public function __construct(
		LangLinkLoader $loader,
		$hook = 'LanguageLinks',
		$internalBufferLimit = 10000
	) {
		$this->langLinkConverter = new LangLinkConverter();

		$this->loader = $loader;
		$this->hook = $hook;

		// Impose a hard limit on the number of links to load, to avoid database overload.
		// We later check whether we hit that limit, and die if we did.
		$this->internalBufferLimit = $internalBufferLimit;
	}

	/**
	 * Loads a list of language links.
	 *
	 * @see LangLinkLoader::loadLanguageLinks
	 *
	 * @param int[]  $fromPageIds Load links from these pages.
	 * @param string $dir Sort order, either 'ascending' or 'descending'.
	 * @param int    $limit The maximum number of links to return.
	 * @param string $forLang Only links to that language
	 * @param string $forTitle Only links to that title (required $forLang)
	 * @param int    $continueFrom Start from links on this page (requires $continueLang)
	 * @param string $continueLang Start from links to this language (requires $continueFrom)
	 *
	 * @return object[] a list of stdClass objects with at least the members ll_from,
	 *         ll_lang, and ll_title. The objects are sorted by ll_from and ll_lang,
	 *         in ascending or descending order according to $dir.
	 */
	public function loadLanguageLinks(
		$fromPageIds,
		$dir,
		$limit = null,
		$forLang = null,
		$forTitle = null,
		$continueFrom = null,
		$continueLang = null
	) {
		if ( empty( $fromPageIds ) ) {
			return array();
		}

		if ( Hooks::isRegistered( 'LanguageLinks' ) ) {
			// There is a handler registered for the 'LanguageLinks' hook,
			// so we need to run the hook and consider the results.
			// To do that, we load all links for all pages, then call the hook for each page,
			// and integrate the result. Finally, filtering and paging is applied.

			$links = $this->loader->loadLanguageLinks(
				$fromPageIds,
				$dir,
				$this->internalBufferLimit, // "normal" limit is imposed later
				$forLang,
				$forTitle,
				$continueFrom,
				$continueLang );

			if ( count( $links ) >= $this->internalBufferLimit ) {
				// XXX: we could re-try, cutting the current list in half, recursively...
				//FIXME: the API needs to issue the appropriate error code.
				throw new MWException(
					"The query returned too many links for internal processing. "
						. "Try fewer pages at once or disable 'effective' mode.");
			}

			$this->buildEffectiveLinks(
				$links,
				$dir,
				$limit,
				$forLang,
				$forTitle,
				$continueFrom,
				$continueLang );
		} else {
			$links = $this->loader->loadLanguageLinks(
				$fromPageIds,
				$dir,
				$limit,
				$forLang,
				$forTitle,
				$continueFrom,
				$continueLang );
		}

		return $links;
	}

	public function buildEffectiveLinks(
		$links,
		$dir, $limit = null,
		$forLang = null, $forTitle = null,
		$continueFrom = null, $continueLang = null
	) {
		wfProfileIn( __METHOD__ );

		// Find continuation page.
		// Note that for the first remaining page, we keep links that
		// lie before the continuation point, for consistency.
		if ( $continueFrom !== null ) {
			$offset = 0;
			foreach ( $links as $row ) {
				if ( ( $dir == 'ascending' && $row->ll_from >= $continueFrom )
					|| ( $dir == 'descending' && $row->ll_from <= $continueFrom ) ) {
					// reached continuation point
					break;
				}
				$offset++;
			}

			// remove all links before the continuation point
			$links = array_slice( $links, $offset );
		}

		$effectiveLinks = array();
		$currentPageId = null;
		$currentPageLinks = array();

		foreach ( $links as $row ) {
			if ( count( $effectiveLinks ) >= $limit ) {
				// we got enough, quit.
				break;
			}

			// detect next chunk
			if ( $currentPageId !== $row->ll_from ) {
				// handle this chunk
				$currentPageEffectiveLinks = $this->getEffectiveLinksFor(
					$currentPageId,
					$currentPageLinks,
					$dir );

				// Filter the effective links by language and target title, if desired.
				// Filter for the continuation point only if we are looking at the
				// first page in the continuation.
				$currentPageEffectiveLinks = $this->filterLinks(
					$currentPageEffectiveLinks,
					$forLang,
					$forTitle,
					$dir,
					( $row->ll_from === $continueFrom ) ? $continueLang : null
				);

				$effectiveLinks = array_merge( $effectiveLinks, $currentPageEffectiveLinks );

				// go to next chunk
				$currentPageId = $row->ll_from;
				$currentPageLinks = array();
			}

			$currentPageLinks[] = $row;
		}

		// enforce limit, we may have spilled a bit
		$effectiveLinks = array_slice( $effectiveLinks, 0, $limit );

		wfProfileOut( __METHOD__ );
		return $effectiveLinks;
	}

	public function getEffectiveLinksFor(
		$pageId,
		$links,
		$dir
	) {
		$titles = $this->getPageSet()->getGoodTitles();

		if ( !isset( $titles[$pageId] ) ) {
			throw new MWException( "Page ID $pageId not found in this request's page set." );
		}

		$title = $titles[$pageId];
		$linkStrings = $this->rowsToStrings( $links );
		$linkFlags = array();

		wfRunHooks( $this->hook, array( $title, &$linkStrings, &$linkFlags ) );

		sort( $linkStrings );

		if ( $dir === 'descending' ) {
			$linkStrings = array_reverse( $linkStrings );
		}

		$effectiveLinks = $this->stringsToRows( $pageId, $linkStrings );

		// ...and flag the links with these languages
		$effectiveLinks = $this->flagLinks( $effectiveLinks, $linkFlags );

		return $effectiveLinks;
	}

	public function filterLinks(
		$links,
		$language,
		$title,
		$dir = 'ascending',
		$fromLanguage = null
	) {
		$filteredLinks = array();

		foreach ( $links as $row ) {
			if ( $fromLanguage !== null ) {
				if ( ( $dir == 'ascending' && $row->ll_lang < $fromLanguage )
					|| ( $dir == 'descending' && $row->ll_lang > $fromLanguage ) ) {
					// not at continuation point yet
					continue;
				}
			}

			if ( $language !== null ) {
				if ( $row->ll_lang !== $language ) {
					// wrong language
					continue;
				}

				if ( $title !== null && $row->ll_lang !== $title ) {
					// wrong title
					continue;
				}
			}

			$filteredLinks[] = $row;
		}

		return $filteredLinks;
	}
}