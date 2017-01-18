<?php

namespace MediaWiki\Widget\Search;

use Linker;
use SearchResult;
use SpecialSearch;
use Title;

/**
 * Renders a simple one-line result
 */
class InterwikiSearchResultWidget implements SearchResultWidget {
	/** @var SpecialSearch */
	protected $specialSearch;

	public function __construct( SpecialSearch $specialSearch ) {
		$this->specialSearch = $specialSearch;
	}

	/**
	 * @param SearchResult $result The result to render
	 * @param string $terms Terms to be highlighted (@see SearchResult::getTextSnippet)
	 * @param int $position The result position, including offset
	 * @return string HTML
	 */
	public function render( SearchResult $result, $terms, $position, $iwPrefix ) {

		$title = $result->getTitle();
		$titleSnippet = $result->getTitleSnippet();
		$snippet = $result->getTextSnippet( $terms[0] );

		if ( $titleSnippet === '' ) {
			$titleSnippet = null;
		}

		$link = Linker::linkKnown( $title, $titleSnippet );

		$redirectTitle = $result->getRedirectTitle();
		$redirect = '';
		if ( $redirectTitle !== null ) {
			$redirectText = $result->getRedirectSnippet() ?: null;
			$redirect =
				"<span class='iw-result__redirect'>" .
					$this->specialSearch->msg( 'search-redirect' )->rawParams(
						Linker::linkKnown( $redirectTitle, $redirectText )
					)->text() .
				"</span>";
		}

		switch ( $iwPrefix ):
			case 'wikt':
				return 	"<div class='iw-result__content'>" .
							"<span class='iw-result__title'>{$link} {$redirect}: </span>" .
							$snippet .
						"</div>";
			case 'q':
				return 	"<div class='iw-result__content'>{$snippet}</div>" .
						"<div class='iw-result__title'>{$link} {$redirect}</div>";
			default:
				return 	"<div class='iw-result__title'>{$link} {$redirect}</div>" .
						"<div class='iw-result__content'>{$snippet}</div>";
		endswitch;


	}
}
