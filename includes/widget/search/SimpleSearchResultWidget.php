<?php

namespace MediaWiki\Widget\Search;

use HtmlArmor;
use MediaWiki\Linker\LinkRenderer;
use SearchResult;
use SpecialSearch;
use Title;

/**
 * Renders a simple one-line result
 */
class SimpleSearchResultWidget implements SearchResultWidget {
	/** @var SpecialSearch */
	protected $specialSearch;
	/** @var LinkRenderer */
	protected $linkRenderer;

	public function __construct( SpecialSearch $specialSearch, LinkRenderer $linkRenderer ) {
		$this->specialSearch = $specialSearch;
		$this->linkRenderer = $linkRenderer;
	}

	/**
	 * @param SearchResult $result The result to render
	 * @param string $terms Terms to be highlighted (@see SearchResult::getTextSnippet)
	 * @param int $position The result position, including offset
	 * @return string HTML
	 */
	public function render( SearchResult $result, $terms, $position ) {
		$title = $result->getTitle();
		$titleSnippet = $result->getTitleSnippet();
		if ( $titleSnippet ) {
			$titleSnippet = new HtmlArmor( $titleSnippet );
		} else {
			$titleSnippet = null;
		}

		$link = $this->linkRenderer->makeLink( $title, $titleSnippet );

		$redirectTitle = $result->getRedirectTitle();
		$redirect = '';
		if ( $redirectTitle !== null ) {
			$redirectText = $result->getRedirectSnippet();
			if ( $redirectText ) {
				$redirectText = new HtmlArmor( $redirectText );
			} else {
				$redirectText = null;
			}
			$redirect =
				"<span class='searchalttitle'>" .
					$this->specialSearch->msg( 'search-redirect' )->rawParams(
						$this->linkRenderer->makeLink( $redirectTitle, $redirectText )
					)->text() .
				"</span>";
		}

		return "<li>{$link} {$redirect}</li>";
	}
}
