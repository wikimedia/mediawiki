<?php

namespace MediaWiki\Widget;

use Linker;
use SearchResult;
use SpecialSearch;
use Title;

class InterwikiSearchResultWidget {
	/** @var SpecialSearch */
	protected $specialSearch;

	public function __construct( SpecialSearch $specialSearch ) {
		$this->specialSearch = $specialSearch;
	}

	public function render( SearchResult $result, $lastInterwiki ) {
		$title = $result->getTitle();
		$titleSnippet = $result->getTitleSnippet();
		if ( $titleSnippet === '' ) {
			$titleSnippet = null;
		}

		$link = Linker::linkKnown( $title, $titleSnippet );

		$redirectTitle = $result->getRedirectTitle();
		$redirectText = $result->getRedirectSnippet();
		$redirect = '';
		if ( $redirectTitle === null ) {
			if ( $redirectText === '' ) {
				$redirectText = null;
			}

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
