<?php

namespace MediaWiki\Widget;

use Linker;
use SearchResult;
use SpecialSearch;
use Title;

/**
 * Renders a simple one-line result
 */
class SimpleSearchResultWidget implements SearchResultWidget {
	/** @var SpecialSearch */
	protected $specialSearch;

	public function __construct( SpecialSearch $specialSearch ) {
		$this->specialSearch = $specialSearch;
	}

	public function render( SearchResult $result, $terms, $position ) {
		$title = $result->getTitle();
		$titleSnippet = $result->getTitleSnippet();
		if ( $titleSnippet === '' ) {
			$titleSnippet = null;
		}

		$link = Linker::linkKnown( $title, $titleSnippet );

		$redirectTitle = $result->getRedirectTitle();
		$redirect = '';
		if ( $redirectTitle !== null ) {
			$redirectText = $result->getRedirectSnippet() ?: null;
			$redirect =
				"<span class='searchalttitle'>" .
					$this->specialSearch->msg( 'search-redirect' )->rawParams(
						Linker::linkKnown( $redirectTitle, $redirectText )
					)->text() .
				"</span>";
		}

		return "<li>{$link} {$redirect}</li>";
	}
}
