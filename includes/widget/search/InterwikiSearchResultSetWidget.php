<?php

namespace MediaWiki\Widget\Search;

use Linker;
use SpecialSearch;
use Title;

class InterwikiSearchResultSetWidget {
	/** @var SpecialSearch */
	protected $specialSearch;
	/** @var SearchResultWidget */
	protected $resultWidget;
	/** @var string[]|null */
	protected $customCaptions;

	public function __construct( SpecialSearch $specialSearch, SearchResultWidget $resultWidget ) {
		$this->specialSearch = $specialSearch;
		$this->resultWidget = $resultWidget;
	}

	/**
	 * @var string $term
	 * @var SearchResultSet|SearchResultSet[] $resultSets
	 */
	public function render( $term, $resultSets ) {
		global $wgContLang;

		if ( !is_array( $resultSets ) ) {
			$resultSets = [$resultSets];
		}

		$this->loadCustomCaptions();

		$iwResults = [];
		foreach ( $resultSets as $resultSet ) {
			$result = $resultSet->next();
			while ( $result ) {
				if ( !$result->isBrokenTitle() ) {
					$iwResults[$result->getTitle()->getInterwiki()][] = $result;
				}
				$result = $resultSet->next();
			}
		}

		$out = '';
		foreach ( $iwResults as $iwPrefix => $results ) {
			$out .= $this->headerHtml( $iwPrefix );
			$out .= "<ul class='mw-search-iwresults'>";
			foreach ( $results as $result ) {
				$out .= $this->resultWidget->render( $result );
			}
			$out .= "</ul>";
		}

		return
			"<div id='mw-search-interwiki'>" .
				"<div id='mw-search-interwiki-caption'>" .
					$this->specialSearch->msg( 'search-interwiki-caption' )->text() .
				'</div>' .
				$out .
			"</div>";
	}

	protected function headerHtml( $iwPrefix ) {
		if ( isset( $this->customCaptions[$iwPrefix] ) ) {
			$caption = $this->customCaptions[$iwPrefix];
		} else {
			$iwLookup = \MediaWiki\MediaWikiServices::getInstance()->getInterwikiLookup();
			$interwiki = $iwLookup->fetch( $iwPrefix );
			$parsed = wfParseUrl( wfExpandUrl( $interwiki ? $interwiki->getURL() : '/' ) );
			$caption = $this->specialSearch->msg( 'search-interwiki-default', $parsed['host'] )->text();
		}
		$searchLink = Linker::linkKnown(
			Title::newFromText( "$iwPrefix:Special:Search" ),
			$this->specialSearch->msg( 'search-interwiki-more' )->text(),
			[],
			[
				'search' => $query,
				'fulltext' => 1,
			]
		);
		return
			"<div class='mw-search-interwiki-project'>" .
				"<span class='mw-search-interwiki-more'>{$searchLink}</span>" .
				$caption .
	        "</div>";
	}

	protected function loadCustomCaptions() {
		if ( $this->customCaptions !== null ) {
			return;
		}

		$this->customCaptions = [];
		$customLines = explode( "\n", $this->specialSearch->msg( 'search-interwiki-custom' )->text() );
		foreach ( $customLines as $line ) {
			$parts = explode( ':', $line, 2 );
			if ( count( $parts ) === 2 ) {
				$this->customCaptions[$parts[0]] = $parts[1];
			}
		}
	}
}
