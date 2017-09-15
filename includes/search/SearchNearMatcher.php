<?php

/**
 * Implementation of near match title search.
 * TODO: split into service/implementation.
 */
class SearchNearMatcher {
	/**
	 * @var Config
	 */
	protected $config;

	/**
	 * Current language
	 * @var Language
	 */
	private $language;

	public function __construct( Config $config, Language $lang ) {
		$this->config = $config;
		$this->language = $lang;
	}

	/**
	 * If an exact title match can be found, or a very slightly close match,
	 * return the title. If no match, returns NULL.
	 *
	 * @param string $searchterm
	 * @return Title
	 */
	public function getNearMatch( $searchterm ) {
		$title = $this->getNearMatchInternal( $searchterm );

		Hooks::run( 'SearchGetNearMatchComplete', [ $searchterm, &$title ] );
		return $title;
	}

	/**
	 * Do a near match (see SearchEngine::getNearMatch) and wrap it into a
	 * SearchResultSet.
	 *
	 * @param string $searchterm
	 * @return SearchResultSet
	 */
	public function getNearMatchResultSet( $searchterm ) {
		return new SearchNearMatchResultSet( $this->getNearMatch( $searchterm ) );
	}

	/**
	 * Really find the title match.
	 * @param string $searchterm
	 * @return null|Title
	 */
	protected function getNearMatchInternal( $searchterm ) {
		$lang = $this->language;

		$allSearchTerms = [ $searchterm ];

		if ( $lang->hasVariants() ) {
			$allSearchTerms = array_unique( array_merge(
				$allSearchTerms,
				$lang->autoConvertToAllVariants( $searchterm )
			) );
		}

		$titleResult = null;
		if ( !Hooks::run( 'SearchGetNearMatchBefore', [ $allSearchTerms, &$titleResult ] ) ) {
			return $titleResult;
		}

		foreach ( $allSearchTerms as $term ) {
			# Exact match? No need to look further.
			$title = Title::newFromText( $term );
			if ( is_null( $title ) ) {
				return null;
			}

			# Try files if searching in the Media: namespace
			if ( $title->getNamespace() == NS_MEDIA ) {
				$title = Title::makeTitle( NS_FILE, $title->getText() );
			}

			if ( $title->isSpecialPage() || $title->isExternal() || $title->exists() ) {
				return $title;
			}

			# See if it still otherwise has content is some sane sense
			$page = WikiPage::factory( $title );
			if ( $page->hasViewableContent() ) {
				return $title;
			}

			if ( !Hooks::run( 'SearchAfterNoDirectMatch', [ $term, &$title ] ) ) {
				return $title;
			}

			# Now try all lower case (i.e. first letter capitalized)
			$title = Title::newFromText( $lang->lc( $term ) );
			if ( $title && $title->exists() ) {
				return $title;
			}

			# Now try capitalized string
			$title = Title::newFromText( $lang->ucwords( $term ) );
			if ( $title && $title->exists() ) {
				return $title;
			}

			# Now try all upper case
			$title = Title::newFromText( $lang->uc( $term ) );
			if ( $title && $title->exists() ) {
				return $title;
			}

			# Now try Word-Caps-Breaking-At-Word-Breaks, for hyphenated names etc
			$title = Title::newFromText( $lang->ucwordbreaks( $term ) );
			if ( $title && $title->exists() ) {
				return $title;
			}

			// Give hooks a chance at better match variants
			$title = null;
			if ( !Hooks::run( 'SearchGetNearMatch', [ $term, &$title ] ) ) {
				return $title;
			}
		}

		$title = Title::newFromText( $searchterm );

		# Entering an IP address goes to the contributions page
		if ( $this->config->get( 'EnableSearchContributorsByIP' ) ) {
			if ( ( $title->getNamespace() == NS_USER && User::isIP( $title->getText() ) )
				|| User::isIP( trim( $searchterm ) ) ) {
				return SpecialPage::getTitleFor( 'Contributions', $title->getDBkey() );
			}
		}

		# Entering a user goes to the user page whether it's there or not
		if ( $title->getNamespace() == NS_USER ) {
			return $title;
		}

		# Go to images that exist even if there's no local page.
		# There may have been a funny upload, or it may be on a shared
		# file repository such as Wikimedia Commons.
		if ( $title->getNamespace() == NS_FILE ) {
			$image = wfFindFile( $title );
			if ( $image ) {
				return $title;
			}
		}

		# MediaWiki namespace? Page may be "implied" if not customized.
		# Just return it, with caps forced as the message system likes it.
		if ( $title->getNamespace() == NS_MEDIAWIKI ) {
			return Title::makeTitle( NS_MEDIAWIKI, $lang->ucfirst( $title->getText() ) );
		}

		# Quoted term? Try without the quotes...
		$matches = [];
		if ( preg_match( '/^"([^"]+)"$/', $searchterm, $matches ) ) {
			return self::getNearMatch( $matches[1] );
		}

		return null;
	}
}
