<?php

namespace MediaWiki\Search\SearchWidgets;

use Html;
use ISearchResultSet;
use MediaWiki\Interwiki\InterwikiLookup;
use MediaWiki\Linker\LinkRenderer;
use SpecialSearch;
use Title;

/**
 * Renders one or more ISearchResultSets into a sidebar grouped by
 * interwiki prefix. Includes a per-wiki header indicating where
 * the results are from.
 *
 * @deprecated since 1.31. Use InterwikiSearchResultSetWidget
 */
class SimpleSearchResultSetWidget implements SearchResultSetWidget {
	/** @var SpecialSearch */
	protected $specialSearch;
	/** @var SearchResultWidget */
	protected $resultWidget;
	/** @var string[]|null */
	protected $customCaptions;
	/** @var LinkRenderer */
	protected $linkRenderer;
	/** @var InterwikiLookup */
	protected $iwLookup;

	public function __construct(
		SpecialSearch $specialSearch,
		SearchResultWidget $resultWidget,
		LinkRenderer $linkRenderer,
		InterwikiLookup $iwLookup
	) {
		wfDeprecated( __METHOD__, '1.31' );
		$this->specialSearch = $specialSearch;
		$this->resultWidget = $resultWidget;
		$this->linkRenderer = $linkRenderer;
		$this->iwLookup = $iwLookup;
	}

	/**
	 * @param string $term User provided search term
	 * @param ISearchResultSet|ISearchResultSet[] $resultSets List of interwiki
	 *  results to render.
	 * @return string HTML
	 */
	public function render( $term, $resultSets ) {
		if ( !is_array( $resultSets ) ) {
			$resultSets = [ $resultSets ];
		}

		$this->loadCustomCaptions();

		$iwResults = [];
		foreach ( $resultSets as $resultSet ) {
			foreach ( $resultSet as $result ) {
				if ( !$result->isBrokenTitle() ) {
					$iwResults[$result->getTitle()->getInterwiki()][] = $result;
				}
			}
		}

		$out = '';
		foreach ( $iwResults as $iwPrefix => $results ) {
			$out .= $this->headerHtml( $iwPrefix, $term );
			$out .= "<ul class='mw-search-iwresults'>";
			// TODO: Assumes interwiki results are never paginated
			$position = 0;
			foreach ( $results as $result ) {
				$out .= $this->resultWidget->render( $result, $position++ );
			}
			$out .= "</ul>";
		}

		return "<div id='mw-search-interwiki'>" .
			"<div id='mw-search-interwiki-caption'>" .
				$this->specialSearch->msg( 'search-interwiki-caption' )->parse() .
			'</div>' .
			$out .
		"</div>";
	}

	/**
	 * Generates an appropriate HTML header for the given interwiki prefix
	 *
	 * @param string $iwPrefix Interwiki prefix of wiki to show header for
	 * @param string $term User provided search term
	 * @return string HTML
	 */
	protected function headerHtml( $iwPrefix, $term ) {
		if ( isset( $this->customCaptions[$iwPrefix] ) ) {
			$caption = $this->customCaptions[$iwPrefix];
		} else {
			$interwiki = $this->iwLookup->fetch( $iwPrefix );
			$parsed = wfParseUrl( wfExpandUrl( $interwiki ? $interwiki->getURL() : '/' ) );
			$caption = $this->specialSearch->msg( 'search-interwiki-default', $parsed['host'] )->escaped();
		}

		$href = Title::makeTitle( NS_SPECIAL, 'Search', null, $iwPrefix )->getLocalURL(
			[ 'search' => $term, 'fulltext' => 1 ]
		);
		$searchLink = Html::rawElement(
			'a',
			[ 'href' => $href ],
			$this->specialSearch->msg( 'search-interwiki-more' )->escaped()
		);

		return "<div class='mw-search-interwiki-project'>" .
			"<span class='mw-search-interwiki-more'>{$searchLink}</span>" .
			$caption .
		"</div>";
	}

	protected function loadCustomCaptions() {
		if ( $this->customCaptions !== null ) {
			return;
		}

		$this->customCaptions = [];
		$customLines = explode( "\n", $this->specialSearch->msg( 'search-interwiki-custom' )->escaped() );
		foreach ( $customLines as $line ) {
			$parts = explode( ':', $line, 2 );
			if ( count( $parts ) === 2 ) {
				$this->customCaptions[$parts[0]] = $parts[1];
			}
		}
	}
}
