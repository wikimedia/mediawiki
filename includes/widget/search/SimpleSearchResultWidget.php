<?php

namespace MediaWiki\Widget\Search;

use HtmlArmor;
use MediaWiki\Linker\LinkRenderer;
use SearchResult;
use SpecialSearch;

/**
 * Renders a simple one-line result
 *
 * @deprecated since 1.31. Use other result widgets.
 */
class SimpleSearchResultWidget implements SearchResultWidget {
	/** @var SpecialSearch */
	protected $specialSearch;
	/** @var LinkRenderer */
	protected $linkRenderer;

	public function __construct( SpecialSearch $specialSearch, LinkRenderer $linkRenderer ) {
		wfDeprecated( __METHOD__, '1.31' );
		$this->specialSearch = $specialSearch;
		$this->linkRenderer = $linkRenderer;
	}

	/**
	 * @param SearchResult $result The result to render
	 * @param int $position The result position, including offset
	 * @return string HTML
	 */
	public function render( SearchResult $result, $position ) {
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
					)->parse() .
				"</span>";
		}

		return "<li>{$link} {$redirect}</li>";
	}
}
