<?php

namespace MediaWiki\Search\SearchWidgets;

use Html;
use ILanguageConverter;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Widget\SearchInputWidget;
use NamespaceInfo;
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
	/** @var HookContainer */
	private $hookContainer;
	/** @var HookRunner */
	private $hookRunner;
	/** @var ILanguageConverter */
	private $languageConverter;
	/** @var NamespaceInfo */
	private $namespaceInfo;

	/**
	 * @param SpecialSearch $specialSearch
	 * @param SearchEngineConfig $searchConfig
	 * @param HookContainer $hookContainer
	 * @param ILanguageConverter $languageConverter
	 * @param NamespaceInfo $namespaceInfo
	 * @param array $profiles
	 */
	public function __construct(
		SpecialSearch $specialSearch,
		SearchEngineConfig $searchConfig,
		HookContainer $hookContainer,
		ILanguageConverter $languageConverter,
		NamespaceInfo $namespaceInfo,
		array $profiles
	) {
		$this->specialSearch = $specialSearch;
		$this->searchConfig = $searchConfig;
		$this->hookContainer = $hookContainer;
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->languageConverter = $languageConverter;
		$this->namespaceInfo = $namespaceInfo;
		$this->profiles = $profiles;
	}

	/**
	 * @param string $profile The current search profile
	 * @param string $term The current search term
	 * @param int $numResults The number of results shown
	 * @param int $totalResults The total estimated results found
	 * @param int $offset Current offset in search results
	 * @param bool $isPowerSearch Is the 'advanced' section open?
	 * @param array $options Widget options
	 * @return string HTML
	 */
	public function render(
		$profile,
		$term,
		$numResults,
		$totalResults,
		$offset,
		$isPowerSearch,
		array $options = []
	) {
		$user = $this->specialSearch->getUser();

		$form = Xml::openElement(
				'form',
				[
					'id' => $isPowerSearch ? 'powersearch' : 'search',
					// T151903: default to POST in case JS is disabled
					'method' => ( $isPowerSearch && $user->isRegistered() ) ? 'post' : 'get',
					'action' => wfScript(),
				]
			) .
				Html::rawElement(
					'div',
					[ 'id' => 'mw-search-top-table' ],
					$this->shortDialogHtml( $profile, $term, $numResults, $totalResults, $offset, $options )
				) .
				Html::rawElement( 'div', [ 'class' => 'mw-search-visualclear' ] ) .
				Html::rawElement(
					'div',
					[ 'class' => 'mw-search-profile-tabs' ],
					$this->profileTabsHtml( $profile, $term ) .
						Html::rawElement( 'div', [ 'style' => 'clear:both' ] )
				) .
				$this->optionsHtml( $term, $isPowerSearch, $profile ) .
			Xml::closeElement( 'form' );

		return Html::rawElement( 'div', [ 'class' => 'mw-search-form-wrapper' ], $form );
	}

	/**
	 * @param string $profile The current search profile
	 * @param string $term The current search term
	 * @param int $numResults The number of results shown
	 * @param int $totalResults The total estimated results found
	 * @param int $offset Current offset in search results
	 * @param array $options Widget options
	 * @return string HTML
	 */
	protected function shortDialogHtml(
		$profile,
		$term,
		$numResults,
		$totalResults,
		$offset,
		array $options = []
	) {
		$searchWidget = new SearchInputWidget( $options + [
			'id' => 'searchText',
			'name' => 'search',
			'autofocus' => trim( $term ) === '',
			'title' => $this->specialSearch->msg( 'searchsuggest-search' )->text(),
			'value' => $term,
			'dataLocation' => 'content',
			'infusable' => true,
		] );

		$html = new \OOUI\ActionFieldLayout( $searchWidget, new \OOUI\ButtonInputWidget( [
			'type' => 'submit',
			'label' => $this->specialSearch->msg( 'searchbutton' )->text(),
			'flags' => [ 'progressive', 'primary' ],
		] ), [
			'align' => 'top',
		] );

		if ( $this->specialSearch->getPrefix() !== '' ) {
			$html .= Html::hidden( 'prefix', $this->specialSearch->getPrefix() );
		}

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

		return Html::rawElement(
			'div',
			[ 'class' => 'search-types' ],
			Html::rawElement( 'ul', [], implode( '', $items ) )
		);
	}

	/**
	 * Check if query starts with image: prefix
	 *
	 * @param string $term The string to check
	 * @return bool
	 */
	protected function startsWithImage( $term ) {
		$parts = explode( ':', $term );
		return count( $parts ) > 1
			&& $this->specialSearch->getContentLanguage()->getNsIndex( $parts[0] ) === NS_FILE;
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
		if ( $isPowerSearch ) {
			$html = $this->powerSearchBox( $term, [] );
		} else {
			$html = '';
			$this->getHookRunner()->onSpecialSearchProfileForm(
				$this->specialSearch, $html, $profile, $term, []
			);
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
		$namespaceTables =
			[ 'namespaceTables' => $this->createCheckboxesForEverySearchableNamespace() ];
		$this->getHookRunner()->onSpecialSearchPowerBox( $namespaceTables, $term, $opts );

		$outputHtml = '';
		$outputHtml .= $this->createSearchBoxHeadHtml();
		$outputHtml .= $this->searchFilterSeparatorHtml();
		$outputHtml .= implode( $this->searchFilterSeparatorHtml(), $namespaceTables );
		$outputHtml .= $this->createHiddenOptsHtml( $opts );

		// Stuff to feed SpecialSearch::saveNamespaces()
		if ( $this->specialSearch->getUser()->isRegistered() ) {
			$outputHtml .= $this->searchFilterSeparatorHtml();
			$outputHtml .= $this->createPowerSearchRememberCheckBoxHtml();
		}

		return Html::rawElement( 'fieldset', [ 'id' => 'mw-searchoptions' ], $outputHtml );
	}

	/**
	 * @return HookContainer
	 * @since 1.35
	 */
	protected function getHookContainer() {
		return $this->hookContainer;
	}

	/**
	 * @return HookRunner
	 * @since 1.35
	 * @internal This is for use by core only. Hook interfaces may be removed
	 *   without notice.
	 */
	protected function getHookRunner() {
		return $this->hookRunner;
	}

	private function searchFilterSeparatorHtml(): string {
		return Html::rawElement( 'div', [ 'class' => 'divider' ], '' );
	}

	private function createPowerSearchRememberCheckBoxHtml(): string {
		return new \OOUI\FieldLayout(
			new \OOUI\CheckboxInputWidget( [
				'name' => 'nsRemember',
				'selected' => false,
				'inputId' => 'mw-search-powersearch-remember',
				// The token goes here rather than in a hidden field so it
				// is only sent when necessary (not every form submission)
				'value' => $this->specialSearch->getUser()
					->getEditToken( 'searchnamespace', $this->specialSearch->getRequest() )
			] ),
			[
			'label' => $this->specialSearch->msg( 'powersearch-remember' )->text(),
			'align' => 'inline'
			]
		);
	}

	private function createNamespaceToggleBoxHtml(): string {
		$toggleBoxContents = "";
		$toggleBoxContents .= Html::rawElement( 'label', [],
				$this->specialSearch->msg( 'powersearch-togglelabel' )->escaped() );
		$toggleBoxContents .= Html::rawElement( 'input', [
					'type' => 'button',
					'id' => 'mw-search-toggleall',
					'value' => $this->specialSearch->msg( 'powersearch-toggleall' )->text(),
				] );
		$toggleBoxContents .= Html::rawElement( 'input', [
					'type' => 'button',
					'id' => 'mw-search-togglenone',
					'value' => $this->specialSearch->msg( 'powersearch-togglenone' )->text(),
				] );

		// Handled by JavaScript if available
		return Html::rawElement( 'div', [ 'id' => 'mw-search-togglebox' ], $toggleBoxContents );
	}

	private function createSearchBoxHeadHtml(): string {
		return Html::rawElement( 'legend', [],
				$this->specialSearch->msg( 'powersearch-legend' )->escaped() ) .
			Html::rawElement( 'h4', [], $this->specialSearch->msg( 'powersearch-ns' )->parse() ) .
			$this->createNamespaceToggleBoxHtml();
	}

	private function createNamespaceCheckbox( int $namespace, array $activeNamespaces ): string {
		$namespaceDisplayName = $this->getNamespaceDisplayName( $namespace );

		return new \OOUI\FieldLayout(
			new \OOUI\CheckboxInputWidget( [
				'name' => "ns{$namespace}",
				'selected' => in_array( $namespace, $activeNamespaces ),
				'inputId' => "mw-search-ns{$namespace}",
				'value' => '1'
			] ),
			[
			'label' => $namespaceDisplayName,
			'align' => 'inline'
			]
		);
	}

	private function getNamespaceDisplayName( int $namespace ): string {
		$name = $this->languageConverter->convertNamespace( $namespace );
		if ( $name === '' ) {
			$name = $this->specialSearch->msg( 'blanknamespace' )->text();
		}

		return $name;
	}

	private function createCheckboxesForEverySearchableNamespace(): string {
		$rows = [];
		$activeNamespaces = $this->specialSearch->getNamespaces();
		foreach ( $this->searchConfig->searchableNamespaces() as $namespace => $name ) {
			$subject = $this->namespaceInfo->getSubject( $namespace );
			if ( !isset( $rows[$subject] ) ) {
				$rows[$subject] = "";
			}

			$rows[$subject] .= $this->createNamespaceCheckbox( $namespace, $activeNamespaces );
		}

		$namespaceTables = [];
		foreach ( array_chunk( $rows, 4 ) as $chunk ) {
			$namespaceTables[] = implode( '', $chunk );
		}

		return '<div class="checkbox-container">' .
			implode( '</div><div class="checkbox-container">', $namespaceTables ) . '</div>';
	}

	private function createHiddenOptsHtml( array $opts ): string {
		$hidden = '';
		foreach ( $opts as $key => $value ) {
			$hidden .= Html::hidden( $key, $value );
		}

		return $hidden;
	}
}
