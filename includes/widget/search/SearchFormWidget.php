<?php

namespace MediaWiki\Widget\Search;

use Hooks;
use Html;
use MediaWiki\Widget\SearchInputWidget;
use MWNamespace;
use SearchEngineConfig;
use SpecialSearch;
use Xml;

class SearchFormWidget {
	/** @var SpecialSearch */
	protected $specialSearch;
	/** @var SearchEngineConfig */
	protected $searchConfig;
	/** @var array */
	protected $profiles;

	/**
	 * @param SpecialSearch $specialSearch
	 * @param SearchEngineConfig $searchConfig
	 * @param array $profiles
	 */
	public function __construct(
		SpecialSearch $specialSearch,
		SearchEngineConfig $searchConfig,
		array $profiles
	) {
		$this->specialSearch = $specialSearch;
		$this->searchConfig = $searchConfig;
		$this->profiles = $profiles;
	}

	/**
	 * @param string $profile The current search profile
	 * @param string $term The current search term
	 * @param int $numResults The number of results shown
	 * @param int $totalResults The total estimated results found
	 * @param int $offset Current offset in search results
	 * @param bool $isPowerSearch Is the 'advanced' section open?
	 * @return string HTML
	 */
	public function render(
		$profile,
		$term,
		$numResults,
		$totalResults,
		$offset,
		$isPowerSearch
	) {
		return Xml::openElement(
				'form',
				[
					'id' => $isPowerSearch ? 'powersearch' : 'search',
					'method' => 'get',
					'action' => wfScript(),
				]
			) .
				'<div id="mw-search-top-table">' .
					$this->shortDialogHtml( $profile, $term, $numResults, $totalResults, $offset ) .
				'</div>' .
				"<div class='mw-search-visualclear'></div>" .
				"<div class='mw-search-profile-tabs'>" .
					$this->profileTabsHtml( $profile, $term ) .
					"<div style='clear:both'></div>" .
				"</div>" .
				$this->optionsHtml( $term, $isPowerSearch, $profile ) .
			'</form>';
	}

	/**
	 * @param string $profile The current search profile
	 * @param string $term The current search term
	 * @param int $numResults The number of results shown
	 * @param int $totalResults The total estimated results found
	 * @param int $offset Current offset in search results
	 * @return string HTML
	 */
	protected function shortDialogHtml( $profile, $term, $numResults, $totalResults, $offset ) {
		$html = '';

		$searchWidget = new SearchInputWidget( [
			'id' => 'searchText',
			'name' => 'search',
			'autofocus' => trim( $term ) === '',
			'value' => $term,
			'dataLocation' => 'content',
			'infusable' => true,
		] );

		$layout = new \OOUI\ActionFieldLayout( $searchWidget, new \OOUI\ButtonInputWidget( [
			'type' => 'submit',
			'label' => $this->specialSearch->msg( 'searchbutton' )->text(),
			'flags' => [ 'progressive', 'primary' ],
		] ), [
			'align' => 'top',
		] );

		$html .= $layout;

		if ( $totalResults > 0 && $offset < $totalResults ) {
			$html .= Xml::tags(
				'div',
				[
					'class' => 'results-info',
					'data-mw-num-results-offset' => $offset,
					'data-mw-num-results-total' => $totalResults
				],
				$this->specialSearch->msg( 'search-showingresults' )
					->numParams( $offset + 1, $offset + $numResults, $totalResults )
					->numParams( $numResults )
					->parse()
			);
		}

		$html .=
			Html::hidden( 'title', $this->specialSearch->getPageTitle()->getPrefixedText() ) .
			Html::hidden( 'profile', $profile ) .
			Html::hidden( 'fulltext', '1' );

		return $html;
	}

	/**
	 * Generates HTML for the list of available search profiles.
	 *
	 * @param string $profile The currently selected profile
	 * @param string $term The user provided search terms
	 * @return string HTML
	 */
	protected function profileTabsHtml( $profile, $term ) {
		$bareterm = $this->startsWithImage( $term )
			? substr( $term, strpos( $term, ':' ) + 1 )
			: $term;
		$lang = $this->specialSearch->getLanguage();
		$items = [];
		foreach ( $this->profiles as $id => $profileConfig ) {
			$profileConfig['parameters']['profile'] = $id;
			$tooltipParam = isset( $profileConfig['namespace-messages'] )
				? $lang->commaList( $profileConfig['namespace-messages'] )
				: null;
			$items[] = Xml::tags(
				'li',
				[ 'class' => $profile === $id ? 'current' : 'normal' ],
				$this->makeSearchLink(
					$bareterm,
					$this->specialSearch->msg( $profileConfig['message'] )->text(),
					$this->specialSearch->msg( $profileConfig['tooltip'], $tooltipParam )->text(),
					$profileConfig['parameters']
				)
			);
		}

		return "<div class='search-types'>" .
			"<ul>" . implode( '', $items ) . "</ul>" .
		"</div>";
	}

	/**
	 * Check if query starts with image: prefix
	 *
	 * @param string $term The string to check
	 * @return bool
	 */
	protected function startsWithImage( $term ) {
		global $wgContLang;

		$parts = explode( ':', $term );
		return count( $parts ) > 1
			? $wgContLang->getNsIndex( $parts[0] ) === NS_FILE
			: false;
	}

	/**
	 * Make a search link with some target namespaces
	 *
	 * @param string $term The term to search for
	 * @param string $label Link's text
	 * @param string $tooltip Link's tooltip
	 * @param array $params Query string parameters
	 * @return string HTML fragment
	 */
	protected function makeSearchLink( $term, $label, $tooltip, array $params = [] ) {
		$params += [
			'search' => $term,
			'fulltext' => 1,
		];

		return Xml::element(
			'a',
			[
				'href' => $this->specialSearch->getPageTitle()->getLocalURL( $params ),
				'title' => $tooltip,
			],
			$label
		);
	}

	/**
	 * Generates HTML for advanced options available with the currently
	 * selected search profile.
	 *
	 * @param string $term User provided search term
	 * @param bool $isPowerSearch Is the advanced search profile enabled?
	 * @param string $profile The current search profile
	 * @return string HTML
	 */
	protected function optionsHtml( $term, $isPowerSearch, $profile ) {
		$html = '';

		if ( $isPowerSearch ) {
			$html .= $this->powerSearchBox( $term, [] );
		} else {
			$form = '';
			Hooks::run( 'SpecialSearchProfileForm', [
				$this->specialSearch, &$form, $profile, $term, []
			] );
			$html .= $form;
		}

		return $html;
	}

	/**
	 * @param string $term The current search term
	 * @param array $opts Additional key/value pairs that will be submitted
	 *  with the generated form.
	 * @return string HTML
	 */
	protected function powerSearchBox( $term, array $opts ) {
		global $wgContLang;

		$rows = [];
		$activeNamespaces = $this->specialSearch->getNamespaces();
		foreach ( $this->searchConfig->searchableNamespaces() as $namespace => $name ) {
			$subject = MWNamespace::getSubject( $namespace );
			if ( !isset( $rows[$subject] ) ) {
				$rows[$subject] = "";
			}

			$name = $wgContLang->getConverter()->convertNamespace( $namespace );
			if ( $name === '' ) {
				$name = $this->specialSearch->msg( 'blanknamespace' )->text();
			}

			$rows[$subject] .=
				'<td>' .
					Xml::checkLabel(
						$name,
						"ns{$namespace}",
						"mw-search-ns{$namespace}",
						in_array( $namespace, $activeNamespaces )
					) .
				'</td>';
		}

		// Lays out namespaces in multiple floating two-column tables so they'll
		// be arranged nicely while still accomodating diferent screen widths
		$tableRows = [];
		foreach ( $rows as $row ) {
			$tableRows[] = "<tr>{$row}</tr>";
		}
		$namespaceTables = [];
		foreach ( array_chunk( $tableRows, 4 ) as $chunk ) {
			$namespaceTables[] = implode( '', $chunk );
		}

		$showSections = [
			'namespaceTables' => "<table>" . implode( '</table><table>', $namespaceTables ) . '</table>',
		];
		Hooks::run( 'SpecialSearchPowerBox', [ &$showSections, $term, $opts ] );

		$hidden = '';
		foreach ( $opts as $key => $value ) {
			$hidden .= Html::hidden( $key, $value );
		}

		$divider = "<div class='divider'></div>";

		// Stuff to feed SpecialSearch::saveNamespaces()
		$user = $this->specialSearch->getUser();
		$remember = '';
		if ( $user->isLoggedIn() ) {
			$remember = $divider . Xml::checkLabel(
				$this->specialSearch->msg( 'powersearch-remember' )->text(),
				'nsRemember',
				'mw-search-powersearch-remember',
				false,
				// The token goes here rather than in a hidden field so it
				// is only sent when necessary (not every form submission)
				[ 'value' => $user->getEditToken(
					'searchnamespace',
					$this->specialSearch->getRequest()
				) ]
			);
		}

		return "<fieldset id='mw-searchoptions'>" .
			"<legend>" . $this->specialSearch->msg( 'powersearch-legend' )->escaped() . '</legend>' .
			"<h4>" . $this->specialSearch->msg( 'powersearch-ns' )->parse() . '</h4>' .
			// populated by js if available
			"<div id='mw-search-togglebox'></div>" .
			$divider .
			implode(
				$divider,
				$showSections
			) .
			$hidden .
			$remember .
		"</fieldset>";
	}
}
