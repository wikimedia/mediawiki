<?php

namespace MediaWiki\Widget\Search;

use MediaWiki\Interwiki\InterwikiLookup;
use MediaWiki\Linker\LinkRenderer;
use SearchResultSet;
use SpecialSearch;
use Title;
use Html;

/**
 * Renders one or more SearchResultSets into a sidebar grouped by
 * interwiki prefix. Includes a per-wiki header indicating where
 * the results are from.
 */
class InterwikiSearchResultSetWidget implements SearchResultSetWidget {
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
	/** @var $output */
	protected $output;
	/** @var $iwPrefixDisplayTypes */
	protected $iwPrefixDisplayTypes;

	public function __construct(
		SpecialSearch $specialSearch,
		SearchResultWidget $resultWidget,
		LinkRenderer $linkRenderer,
		InterwikiLookup $iwLookup
	) {
		$this->specialSearch = $specialSearch;
		$this->resultWidget = $resultWidget;
		$this->linkRenderer = $linkRenderer;
		$this->iwLookup = $iwLookup;
		$this->output = $specialSearch->getOutput();
		$this->iwPrefixDisplayTypes = $specialSearch->getConfig()->get(
			'InterwikiPrefixDisplayTypes'
		);
	}
	/**
	 * @param string $term User provided search term
	 * @param SearchResultSet|SearchResultSet[] $resultSets List of interwiki
	 *  results to render.
	 * @return string HTML
	 */
	public function render( $term, $resultSets ) {
		if ( !is_array( $resultSets ) ) {
			$resultSets = [ $resultSets ];
		}

		$this->loadCustomCaptions();

		$this->output->addModules( 'mediawiki.special.search.commonsInterwikiWidget' );
		$this->output->addModuleStyles( 'mediawiki.special.search.interwikiwidget.styles' );

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

		$iwResultSetPos = 1;
		$iwResultListOutput = '';

		foreach ( $iwResults as $iwPrefix => $results ) {
			// TODO: Assumes interwiki results are never paginated
			$position = 0;
			$iwResultItemOutput = '';

			$iwDisplayType = isset( $this->iwPrefixDisplayTypes[$iwPrefix] )
				? $this->iwPrefixDisplayTypes[$iwPrefix]
				: "";

			foreach ( $results as $result ) {
				$iwResultItemOutput .= $this->resultWidget->render( $result, $term, $position++ );
			}

			$headerHtml = $this->headerHtml( $term, $iwPrefix );
			$footerHtml = $this->footerHtml( $term, $iwPrefix );
			$iwResultListOutput .= Html::rawElement( 'li',
				[
					'class' => 'iw-resultset iw-resultset--' . $iwDisplayType,
					'data-iw-resultset-pos' => $iwResultSetPos
				],
				$headerHtml .
				$iwResultItemOutput .
				$footerHtml
			);

			$iwResultSetPos++;
		}

		return Html::rawElement(
			'div',
			[ 'id' => 'mw-interwiki-results' ],
			Html::rawElement(
				'p',
				[ 'class' => 'iw-headline' ],
				$this->specialSearch->msg( 'search-interwiki-caption' )->parse()
			) .
			Html::rawElement(
				'ul', [ 'class' => 'iw-results', ], $iwResultListOutput
			)
		);
	}

	/**
	 * Generates an appropriate HTML header for the given interwiki prefix
	 *
	 * @param string $term User provided search term
	 * @param string $iwPrefix Interwiki prefix of wiki to show header for
	 * @return string HTML
	 */
	protected function headerHtml( $term, $iwPrefix ) {

		$iwDisplayType = isset( $this->iwPrefixDisplayTypes[$iwPrefix] )
			? $this->iwPrefixDisplayTypes[$iwPrefix]
			: "";

		if ( isset( $this->customCaptions[$iwPrefix] ) ) {
			/* customCaptions composed by loadCustomCaptions() with pre-escaped content. */
			$caption = $this->customCaptions[$iwPrefix];
		} else {
			$interwiki = $this->iwLookup->fetch( $iwPrefix );
			$parsed = wfParseUrl( wfExpandUrl( $interwiki ? $interwiki->getURL() : '/' ) );
			$caption = $this->specialSearch->msg( 'search-interwiki-default', $parsed['host'] )->escaped();
		}

		return Html::rawElement( 'div', [ 'class' => 'iw-result__header' ],
			Html::rawElement( 'span', [ 'class' => 'iw-result__icon iw-result__icon--' . $iwDisplayType ] )
			. $caption
		);
	}

	/**
	 * Generates an HTML footer for the given interwiki prefix
	 *
	 * @param string $term User provided search term
	 * @param string $iwPrefix Interwiki prefix of wiki to show footer for
	 * @return string HTML
	 */
	protected function footerHtml( $term, $iwPrefix ) {

		$href = Title::makeTitle( NS_SPECIAL, 'Search', null, $iwPrefix )->getLocalURL(
			[ 'search' => $term, 'fulltext' => 1 ]
		);

		$searchLink = Html::rawElement(
			'a',
			[ 'href' => $href ],
			$this->specialSearch->msg( 'search-interwiki-more-results' )->escaped()
		);

		return Html::rawElement( 'div', [ 'class' => 'iw-result__footer' ], $searchLink );
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
