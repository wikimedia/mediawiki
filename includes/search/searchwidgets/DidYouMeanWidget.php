<?php

namespace MediaWiki\Search\SearchWidgets;

use ISearchResultSet;
use SpecialSearch;

/**
 * Renders a suggested search for the user, or tells the user
 * a suggested search was run instead of the one provided.
 */
class DidYouMeanWidget {
	/** @var SpecialSearch */
	protected $specialSearch;

	public function __construct( SpecialSearch $specialSearch ) {
		$this->specialSearch = $specialSearch;
	}

	/**
	 * @param string $term The user provided search term
	 * @param ISearchResultSet $resultSet
	 * @return string HTML
	 */
	public function render( $term, ISearchResultSet $resultSet ) {
		if ( $resultSet->hasRewrittenQuery() ) {
			$html = $this->rewrittenHtml( $term, $resultSet );
		} elseif ( $resultSet->hasSuggestion() ) {
			$html = $this->suggestionHtml( $resultSet );
		} else {
			return '';
		}

		return "<div class='searchdidyoumean'>$html</div>";
	}

	/**
	 * Generates HTML shown to user when their query has been internally
	 * rewritten, and the results of the rewritten query are being returned.
	 *
	 * @param string $term The users search input
	 * @param ISearchResultSet $resultSet The response to the search request
	 * @return string HTML Links the user to their original $term query, and the
	 *  one suggested by $resultSet
	 */
	protected function rewrittenHtml( $term, ISearchResultSet $resultSet ) {
		$params = [
			'search' => $resultSet->getQueryAfterRewrite(),
			// Don't magic this link into a 'go' link, it should always
			// show search results.
			'fulltext' => 1,
		];
		$stParams = array_merge( $params, $this->specialSearch->powerSearchOptions() );

		$linkRenderer = $this->specialSearch->getLinkRenderer();
		$snippet = $resultSet->getQueryAfterRewriteSnippet();
		if ( $snippet === '' || $snippet === null ) {
			// This should never happen. But if it did happen we would render
			// links as `Special:Search` which is even more useless. Since this
			// was only documented but not enforced previously emit a
			// deprecation warning and in the future we can simply fail on bad
			// inputs
			wfDeprecatedMsg(
				get_class( $resultSet ) . '::getQueryAfterRewriteSnippet returning empty snippet ' .
				'was deprecated in MediaWiki 1.35',
				'1.34', false, false
			);
			$snippet = $resultSet->getQueryAfterRewrite();
		}
		$rewritten = $linkRenderer->makeKnownLink(
			$this->specialSearch->getPageTitle(),
			$snippet,
			[ 'id' => 'mw-search-DYM-rewritten' ],
			$stParams
		);

		$stParams['search'] = $term;
		$stParams['runsuggestion'] = 0;
		$original = $linkRenderer->makeKnownLink(
			$this->specialSearch->getPageTitle(),
			$term,
			[ 'id' => 'mw-search-DYM-original' ],
			$stParams
		);

		return $this->specialSearch->msg( 'search-rewritten' )
			->rawParams( $rewritten, $original )
			->escaped();
	}

	/**
	 * Generates HTML shown to the user when we have a suggestion about
	 * a query that might give more/better results than their current
	 * query.
	 *
	 * @param ISearchResultSet $resultSet
	 * @return string HTML
	 */
	protected function suggestionHtml( ISearchResultSet $resultSet ) {
		$params = [
			'search' => $resultSet->getSuggestionQuery(),
			'fulltext' => 1,
		];
		$stParams = array_merge( $params, $this->specialSearch->powerSearchOptions() );

		$snippet = $resultSet->getSuggestionSnippet();
		if ( $snippet === '' || $snippet === null ) {
			// This should never happen. But if it did happen we would render
			// links as `Special:Search` which is even more useless. Since this
			// was only documented but not enforced previously emit a
			// deprecation warning and in the future we can simply fail on bad
			// inputs
			wfDeprecatedMsg(
				get_class( $resultSet ) . '::getSuggestionSnippet returning empty snippet ' .
				'was deprecated in MediaWiki 1.35',
				'1.34', false, false
			);
			$snippet = $resultSet->getSuggestionSnippet();
		}
		$suggest = $this->specialSearch->getLinkRenderer()->makeKnownLink(
			$this->specialSearch->getPageTitle(),
			$snippet,
			[ 'id' => 'mw-search-DYM-suggestion' ],
			$stParams
		);

		return $this->specialSearch->msg( 'search-suggest' )
			->rawParams( $suggest )->parse();
	}
}
