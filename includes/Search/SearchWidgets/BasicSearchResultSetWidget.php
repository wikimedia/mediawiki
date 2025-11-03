<?php

namespace MediaWiki\Search\SearchWidgets;

use ISearchResultSet;
use MediaWiki\MediaWikiServices;
use MediaWiki\Message\Message;
use MediaWiki\Specials\SpecialSearch;
use MediaWiki\Status\Status;

/**
 * Renders the search result area. Handles Title and Full-Text search results,
 * along with inline and sidebar secondary (interwiki) results.
 */
class BasicSearchResultSetWidget {
	protected SpecialSearch $specialPage;
	protected SearchResultWidget $resultWidget;
	protected SearchResultSetWidget $sidebarWidget;

	public function __construct(
		SpecialSearch $specialPage,
		SearchResultWidget $resultWidget,
		SearchResultSetWidget $sidebarWidget
	) {
		$this->specialPage = $specialPage;
		$this->resultWidget = $resultWidget;
		$this->sidebarWidget = $sidebarWidget;
	}

	/**
	 * @param string $term The search term to highlight
	 * @param int $offset The offset of the first result in the result set
	 * @param ISearchResultSet|null $titleResultSet Results of searching only page titles
	 * @param ISearchResultSet|null $textResultSet Results of general full text search.
	 * @return string HTML
	 */
	public function render(
		$term,
		$offset,
		?ISearchResultSet $titleResultSet = null,
		?ISearchResultSet $textResultSet = null
	) {
		$hasTitle = $titleResultSet && $titleResultSet->numRows() > 0;
		$hasText = $textResultSet && $textResultSet->numRows() > 0;
		$hasSecondary =
			$textResultSet &&
			$textResultSet->hasInterwikiResults( ISearchResultSet::SECONDARY_RESULTS );
		$hasSecondaryInline =
			$textResultSet &&
			$textResultSet->hasInterwikiResults( ISearchResultSet::INLINE_RESULTS );

		if ( !$hasTitle && !$hasText && !$hasSecondary && !$hasSecondaryInline ) {
			return '';
		}

		$out = '<div class="mw-search-results-container">';

		if ( $hasTitle ) {
			$out .= $this->header( $this->specialPage->msg( 'titlematches' ) )
				// @phan-suppress-next-line PhanTypeMismatchArgumentNullable titleResultSet is set when used here
				. $this->renderResultSet( $titleResultSet, $offset );
		}

		if ( $hasText ) {
			if ( $hasTitle ) {
				$out .= "<div class='mw-search-visualclear'></div>" .
					$this->header( $this->specialPage->msg( 'textmatches' ) );
			}
			// @phan-suppress-next-line PhanTypeMismatchArgumentNullable textResultSet is set when used
			$out .= $this->renderResultSet( $textResultSet, $offset );
		}

		if ( $hasSecondaryInline ) {
			$iwResults = $textResultSet->getInterwikiResults( ISearchResultSet::INLINE_RESULTS );
			foreach ( $iwResults as $interwiki => $results ) {
				if ( $results instanceof Status || $results->numRows() === 0 ) {
					// ignore bad interwikis for now
					continue;
				}
				$out .=
					"<h2 class='mw-search-interwiki-header mw-search-visualclear'>" .
						$this->specialPage->msg( "search-interwiki-results-{$interwiki}" )->parse() .
					"</h2>";
				$out .=
					"<div class='mw-search-interwiki-results'>" .
						$this->renderResultSet( $results, $offset ) .
					"</div>";
			}
		}

		// Close <div class='mw-search-results-container'>
		$out .= '</div>';

		if ( $hasSecondary ) {
			$out .= $this->sidebarWidget->render(
				$term,
				$textResultSet->getInterwikiResults( ISearchResultSet::SECONDARY_RESULTS )
			);
		}

		// Convert the whole thing to desired language variant
		// TODO: Move this up to Special:Search?
		$converter = MediaWikiServices::getInstance()->getLanguageConverterFactory()
			->getLanguageConverter();
		return $converter->convert( $out );
	}

	/**
	 * Generate a headline for a section of the search results. In prior
	 * implementations this was rendering wikitext of '==$1==', but seems
	 * a waste to call the full parser to generate this tiny bit of html
	 *
	 * @param Message $msg i18n message to use as header
	 * @return string HTML
	 */
	protected function header( Message $msg ) {
		return "<h2>" . $msg->escaped() . "</h2>";
	}

	/**
	 * @param ISearchResultSet $resultSet The search results to render
	 * @param int $offset Offset of the first result in $resultSet
	 * @return string HTML
	 */
	protected function renderResultSet( ISearchResultSet $resultSet, $offset ) {
		$hits = [];
		foreach ( $resultSet as $result ) {
			$hits[] = $this->resultWidget->render( $result, $offset++ );
		}

		return "<ul class='mw-search-results'>" . implode( '', $hits ) . "</ul>";
	}
}
