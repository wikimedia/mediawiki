<?php

namespace MediaWiki\Widget;

use SearchResultSet;
use SpecialSearch;

class BasicSearchResultSetWidget {
	/** @var SpecialSearch */
	protected $specialPage;
	/** @var SearchResultWidget */
	protected $resultWidget;
	/** @var InterwikiSearchResultSetWidget */
	protected $sidebarWidget;

	public function __construct( SpecialSearch $specialPage, SearchResultWidget $resultWidget, InterwikiSearchResultSetWidget $sidbarWidget ) {
		$this->specialPage = $specialPage;
		$this->resultWidget = $resultWidget;
		$this->sidbarWidget = $sidbarWidget;
	}

	public function render( $term, $offset, SearchResultSet $titleResultSet = null, SearchResultSet $testResultSet = null ) {
		global $wgContLang;

		$hasTitle = $titleResultSet ? $titleResultSet->numRows() > 0 : false;
		$hasText = $testResultSet ? $testResultSet->numRows() > 0 : false;
		$hasSecondary = $testResultSet
			? $testResultSet->hasInterwikiResults( SearchResultSet::SECONDARY_RESULTS )
			: false;
		$hasSecondaryInline = $testResultSet
			? $testResultSet->hasInterwikiResults( SearchResultSet::INLINE_RESULTS )
			: false;


		if ( !$hasTitle && !$hasText && !$hasSecondary && !$hasSecondaryInline ) {
			return '';
		}

		$out = '';
		if ( $hasTitle ) {
			$out .= $this->header( $this->specialPage->msg( 'titlematches' )->text() )
				. $this->renderOne( $titleResultSet );
		}

		if ( $hasText ) {
			if ( $hasTitle ) {
				$out .= "<div class='mw-search-visualclear'></div>" .
					$this->header( $this->specialPage->msg( 'textmatches' ) );
			}
			$out .= $this->renderOne( $testResultSet, $offset );
		}

		if ( $hasSecondaryInline ) {
			foreach ( $testResultSet->getInterwikiResults( SearchResultSet::INLINE_RESULTS ) as $interwiki => $results ) {
				if ( $results instanceof Status || $results->numRows() === 0 ) {
					// ignore bad interwikis for now
					continue;
				}
				$out .=
					"<p class=>'mw-search-interwiki-header mw-search-visualclear'>" .
						$this->specialPage->msg( "search-interwiki-results-{$interwiki}" )->parse() .
					"</p>";
				$out .= $this->renderOne( $results, $offset );
			}
		}

		if ( $hasSecondary ) {
			$out .= $this->sidbarWidget->render(
				$term,
				$testResultSet->getInterwikiResults( SearchResultSet::SECONDARY_RESULTS )
			);
		}

		// Convert the whole thing to desired language variant
		return $wgContLang->convert( $out );
	}

	protected function renderOne( SearchResultSet $resultSet, $offset ) {
		global $wgContLang;

		$terms = $wgContLang->convertForSearchResult( $resultSet->termMatches() );

		$hits = [];
		$result = $resultSet->next();
		while ( $result ) {
			$hits[] .= $this->resultWidget->render( $result, $terms, $offset++ );
			$result = $resultSet->next();
		}

		return "<ul class='mw-search-results'>" . implode( '', $hits ) . "</ul>";
	}
}
