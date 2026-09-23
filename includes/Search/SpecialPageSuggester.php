<?php

namespace MediaWiki\Search;

use MediaWiki\Language\Language;
use MediaWiki\SpecialPage\SpecialPageFactory;
use MediaWiki\Title\Title;

/**
 * Handles searching prefixes of titles and finding any page
 * names that match.
 *
 * @since Since 1.47
 * @ingroup Search
 */
class SpecialPageSuggester {

	/**
	 * Build the suggester.
	 * Search clients should never use this class directly but rely on SearchEngine.
	 *
	 * @internal can only be built by {@link SearchEngine}
	 * @param SpecialPageFactory $specialPageFactory
	 * @param Language $contentLanguage
	 */
	public function __construct(
		private readonly SpecialPageFactory $specialPageFactory,
		private readonly Language $contentLanguage
	) {
	}

	/**
	 * Prefix search for Special: namespace.
	 *
	 * NOTE: The namespace prefix is expected to have been removed by the caller.
	 *
	 * @param string $search search term
	 * @param int $limit max number of items to return
	 * @param int $offset number of items to offset
	 * @return Title[]
	 */
	public function suggest( $search, $limit, $offset ): array {
		$searchParts = explode( '/', $search, 2 );
		$searchKey = $searchParts[0];
		$subpageSearch = $searchParts[1] ?? null;

		// Handle subpage search separately.
		if ( $subpageSearch !== null ) {
			// Try matching the full search string as a page name
			$specialTitle = Title::makeTitleSafe( NS_SPECIAL, $searchKey );
			if ( !$specialTitle ) {
				return [];
			}
			$special = $this->specialPageFactory->getPage( $specialTitle->getText() );
			if ( $special ) {
				$subpages = $special->prefixSearchSubpages( $subpageSearch, $limit, $offset );
				return array_map( $specialTitle->getSubpage( ... ), $subpages );
			} else {
				return [];
			}
		}

		# normalize searchKey, so aliases with spaces can be found - T27675
		$searchKey = str_replace( ' ', '_', $searchKey );
		$searchKey = $this->contentLanguage->caseFold( $searchKey );

		// Unlike SpecialPage itself, we want the canonical forms of both
		// canonical and alias title forms...
		$keys = [];
		$listedPages = $this->specialPageFactory->getListedPages();
		foreach ( $listedPages as $specialPage ) {
			$page = $specialPage->getLocalName();
			$keys[$this->contentLanguage->caseFold( $page )] = [ 'page' => $page, 'rank' => 0 ];
		}

		// Typing "special:" just lists all special pages by their primary name. No need to list the
		// same special pages again by all their aliases.
		if ( $searchKey !== '' ) {
			foreach ( $this->contentLanguage->getSpecialPageAliases() as $page => $aliases ) {
				// Exclude aliases for unlisted or undefined pages (T22885),
				// e.g. if an extension registers a page based on site configuration.
				if ( !isset( $listedPages[$page] ) ) {
					continue;
				}

				// No need to even consider aliases (and as a result list the same special page
				// multiple times) when the primary page name already matches.
				if ( str_starts_with( $this->contentLanguage->caseFold( $page ), $searchKey ) ) {
					continue;
				}

				foreach ( $aliases as $key => $alias ) {
					$pageKey = $this->contentLanguage->caseFold( $alias );
					$keys[$pageKey] = [ 'page' => $alias, 'rank' => $key ];
					// Stop considering later aliases (and as a result list the same special page
					// multiple times) when there was already a match.
					if ( str_starts_with( $pageKey, $searchKey ) ) {
						break;
					}
				}
			}
		}

		ksort( $keys );

		$matches = [];
		foreach ( $keys as $pageKey => $page ) {
			if ( $searchKey === '' || str_starts_with( $pageKey, $searchKey ) ) {
				// T29671: Don't use SpecialPage::getTitleFor() here because it
				// localizes its input leading to searches for e.g. Special:All
				// returning Spezial:MediaWiki-Systemnachrichten and returning
				// Spezial:Alle_Seiten twice when $wgLanguageCode == 'de'
				$matches[$page['rank']][] = Title::makeTitleSafe( NS_SPECIAL, $page['page'] );

				if ( isset( $matches[0] ) && count( $matches[0] ) >= $limit + $offset ) {
					// We have enough items in primary rank, no use to continue
					break;
				}
			}

		}

		// Ensure keys are in order
		ksort( $matches );
		// Flatten the array
		$matches = array_reduce( $matches, 'array_merge', [] );

		return array_slice( $matches, $offset, $limit );
	}
}
