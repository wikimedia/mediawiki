<?php

namespace MediaWiki\Widget\Search;

use HtmlArmor;
use SearchResultSet;
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
	 * @param SearchResultSet $resultSet
	 * @return string HTML
	 */
	public function render( $term, SearchResultSet $resultSet ) {
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
	 * @param SearchResultSet $resultSet The response to the search request
	 * @return string HTML Links the user to their original $term query, and the
	 *  one suggested by $resultSet
	 */
	protected function rewrittenHtml( $term, SearchResultSet $resultSet ) {
		$params = [
			'search' => $resultSet->getQueryAfterRewrite(),
			// Don't magic this link into a 'go' link, it should always
			// show search results.
			'fultext' => 1,
		];
		$stParams = array_merge( $params, $this->specialSearch->powerSearchOptions() );

		$linkRenderer = $this->specialSearch->getLinkRenderer();
		$snippet = $resultSet->getQueryAfterRewriteSnippet();
		$rewritten = $linkRenderer->makeKnownLink(
			$this->specialSearch->getPageTitle(),
			$snippet ? new HtmlArmor( $snippet ) : null,
			[ 'id' => 'mw-search-DYM-rewritten' ],
			$stParams
		);

		$stParams['search'] = $term;
		$stParams['runsuggestion'] = 0;
		$original = $linkRenderer->makeKnownLink(
			$this->specialSearch->getPageTitle(),
			$term,
			[ 'id' => 'mwsearch-DYM-original' ],
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
	 * @param SearchResultSet $resultSet
	 * @return string HTML
	 */
	protected function suggestionHtml( SearchResultSet $resultSet ) {
		$params = [
			'search' => $resultSet->getSuggestionQuery(),
			'fulltext' => 1,
		];
		$stParams = array_merge( $params, $this->specialSearch->powerSearchOptions() );

		$snippet = $resultSet->getSuggestionSnippet();
		$suggest = $this->specialSearch->getLinkRenderer()->makeKnownLink(
			$this->specialSearch->getPageTitle(),
			$snippet ? new HtmlArmor( $snippet ) : null,
			[ 'id' => 'mw-search-DYM-suggestion' ],
			$stParams
		);

		return $this->specialSearch->msg( 'search-suggest' )
			->rawParams( $suggest )->parse();
	}
}
