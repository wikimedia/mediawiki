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

	/**
	 * @var LangLinkConverter
	 */
	protected $langLinkConverter;

	/**
	 * @var LangLinkLoader
	 */
	protected $loader;

	/**
	 * @var string
	 */
	protected $hook;

	public function __construct(
		LangLinkLoader $loader,
		$hook = 'LanguageLinks'
	) {
		$this->langLinkConverter = new LangLinkConverter();

		$this->loader = $loader;
		$this->hook = $hook;
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
			// nothing to do here, move along
			return array();
		}

		if ( !Hooks::isRegistered( $this->hook ) ) {
			// if there are no hook handlers registered, bypass all the fancy logic

			$links = $this->loader->loadLanguageLinks(
				$fromPageIds,
				$dir,
				$limit,
				$forLang,
				$forTitle,
				$continueFrom,
				$continueLang );

			return $links;
		}

		wfProfileIn( __METHOD__ );

		// There is a handler registered for the given hook,
		// so we need to run the hook and consider the results.
		// To do that, we query the links for each page separately,
		// then call the hook for each page, and finally merge and
		// filter the result.

		// make sure IDs are in the required order.
		sort( $fromPageIds );

		if ( $dir === 'descending' ) {
			$fromPageIds = array_reverse( $fromPageIds );
		}

		$links = array();
		foreach ( $fromPageIds as $pageId ) {
			if ( count( $links ) >= $limit ) {
				// we have enough now
				break;
			}

			if ( $continueFrom !== null ) {
				if ( ( $dir === 'ascending' && ( intval( $pageId ) < intval( $continueFrom ) ) )
					|| ( $dir === 'descending' && ( intval( $pageId ) > intval( $continueFrom ) ) ) ) {
					// not at continuation point yet
					continue;
				}
			}

			$linksForPage = $this->loadLanguageLinksForPage(
				$pageId,
				$dir
			);

			// Filter the effective links by language and target title, if desired.
			// Filter for the continuation point only if we are looking at the
			// first page in the continuation.
			$linksForPage = $this->filterLinks(
				$linksForPage,
				$forLang,
				$forTitle,
				$dir,
				( intval( $pageId ) === intval( $continueFrom ) ) ? $continueLang : null
			);

			$links = array_merge( $links, $linksForPage );
		}

		$links = array_slice( $links, 0, $limit );
		wfProfileOut( __METHOD__ );

		return $links;
	}

	public function loadLanguageLinksForPage( $pageId, $dir ) {
		wfProfileIn( __METHOD__ );

		// load all links for the given page
		$links = $this->loader->loadLanguageLinks(
			array( $pageId ),
			$dir,
			5000, //XXX: magic hard limit of links per page
			null, // ignore $forLang so we always get a full set of links for each page.
			null, // ignore $forTitle so we always get a full set of links for each page.
			null, // pointless, we are fetching a single page anyway.
			null  // ignore $continueLang so we always get a full set of links for each page.
		);

		$title = Title::newFromID( $pageId ); //XXX: can we avoid this?

		// convert links into a form the hook can handle
		$linkStrings = $this->langLinkConverter->rowsToLinks( $links );
		$linkFlags = array();

		// call the hook to manipulate the links
		wfRunHooks( $this->hook, array( $title, &$linkStrings, &$linkFlags ) );

		// sort the resulting list of links, which are of the form "language:title"
		sort( $linkStrings );

		if ( $dir === 'descending' ) {
			$linkStrings = array_reverse( $linkStrings );
		}

		$links = $this->langLinkConverter->linksToRows( $pageId, $linkStrings );

		//flag the links with these languages
		$links = $this->langLinkConverter->flagRows( $links, $linkFlags );

		wfProfileOut( __METHOD__ );
		return $links;
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

				if ( $title !== null && $row->ll_title !== $title ) {
					// wrong title
					continue;
				}
			}

			$filteredLinks[] = $row;
		}

		return $filteredLinks;
	}
}