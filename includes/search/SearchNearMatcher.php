<?php

use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\MediaWikiServices;

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

	/**
	 * Current language converter
	 * @var ILanguageConverter
	 */
	private $languageConverter;

	/**
	 * @var HookRunner
	 */
	private $hookRunner;

	/**
	 * SearchNearMatcher constructor.
	 * @param Config $config
	 * @param Language $lang
	 * @param HookContainer $hookContainer
	 */
	public function __construct( Config $config, Language $lang, HookContainer $hookContainer ) {
		$this->config = $config;
		$this->language = $lang;
		$this->languageConverter = MediaWikiServices::getInstance()->getLanguageConverterFactory()
			->getLanguageConverter( $lang );
		$this->hookRunner = new HookRunner( $hookContainer );
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

		$this->hookRunner->onSearchGetNearMatchComplete( $searchterm, $title );
		return $title;
	}

	/**
	 * Do a near match (see SearchEngine::getNearMatch) and wrap it into a
	 * ISearchResultSet.
	 *
	 * @param string $searchterm
	 * @return ISearchResultSet
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
		$allSearchTerms = [ $searchterm ];

		if ( $this->languageConverter->hasVariants() ) {
			$allSearchTerms = array_unique( array_merge(
				$allSearchTerms,
				$this->languageConverter->autoConvertToAllVariants( $searchterm )
			) );
		}

		$titleResult = null;
		if ( !$this->hookRunner->onSearchGetNearMatchBefore( $allSearchTerms, $titleResult ) ) {
			return $titleResult;
		}

		// Most of our handling here deals with finding a valid title for the search term,
		// but almost anything starting with '#' is "valid" and points to Main_Page#searchterm.
		// Rather than doing something completely wrong, do nothing.
		if ( $searchterm === '' || $searchterm[0] === '#' ) {
			return null;
		}

		foreach ( $allSearchTerms as $term ) {
			# Exact match? No need to look further.
			$title = Title::newFromText( $term );
			if ( $title === null ) {
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

			if ( !$this->hookRunner->onSearchAfterNoDirectMatch( $term, $title ) ) {
				return $title;
			}

			# Now try all lower case (i.e. first letter capitalized)
			$title = Title::newFromText( $this->language->lc( $term ) );
			if ( $title && $title->exists() ) {
				return $title;
			}

			# Now try capitalized string
			$title = Title::newFromText( $this->language->ucwords( $term ) );
			if ( $title && $title->exists() ) {
				return $title;
			}

			# Now try all upper case
			$title = Title::newFromText( $this->language->uc( $term ) );
			if ( $title && $title->exists() ) {
				return $title;
			}

			# Now try Word-Caps-Breaking-At-Word-Breaks, for hyphenated names etc
			$title = Title::newFromText( $this->language->ucwordbreaks( $term ) );
			if ( $title && $title->exists() ) {
				return $title;
			}

			// Give hooks a chance at better match variants
			$title = null;
			if ( !$this->hookRunner->onSearchGetNearMatch( $term, $title ) ) {
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
			$image = MediaWikiServices::getInstance()->getRepoGroup()->findFile( $title );
			if ( $image ) {
				return $title;
			}
		}

		# MediaWiki namespace? Page may be "implied" if not customized.
		# Just return it, with caps forced as the message system likes it.
		if ( $title->getNamespace() == NS_MEDIAWIKI ) {
			return Title::makeTitle( NS_MEDIAWIKI, $this->language->ucfirst( $title->getText() ) );
		}

		# Quoted term? Try without the quotes...
		$matches = [];
		if ( preg_match( '/^"([^"]+)"$/', $searchterm, $matches ) ) {
			return $this->getNearMatch( $matches[1] );
		}

		return null;
	}
}
