<?php

namespace MediaWiki\Widget\Search;

use HtmlArmor;
use MediaWiki\Linker\LinkRenderer;
use SearchResult;
use SpecialSearch;
use Html;

/**
 * Renders an enhanced interwiki result
 */
class InterwikiSearchResultWidget implements SearchResultWidget {
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
	 * @param int $position The result position, including offset
	 * @return string HTML
	 */
	public function render( SearchResult $result, $position ) {
		$title = $result->getTitle();
		$titleSnippet = $result->getTitleSnippet();
		$snippet = $result->getTextSnippet();

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

			$redirect = Html::rawElement( 'span', [ 'class' => 'iw-result__redirect' ],
				$this->specialSearch->msg( 'search-redirect' )->rawParams(
					$this->linkRenderer->makeLink( $redirectTitle, $redirectText )
				)->escaped()
			);
		}

		return Html::rawElement( 'div', [ 'class' => 'iw-result__title' ], $link . ' ' . $redirect ) .
			Html::rawElement( 'div', [ 'class' => 'iw-result__content' ], $snippet );
	}
}
