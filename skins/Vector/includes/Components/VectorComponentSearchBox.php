<?php
namespace MediaWiki\Skins\Vector\Components;

use Config;
use Linker;
use MessageLocalizer;
use Title;

/**
 * VectorSearchBox component
 */
class VectorComponentSearchBox implements VectorComponent {
	/** @var MessageLocalizer */
	private $localizer;
	/** @var array */
	private $searchBoxData;
	/** @var bool */
	private $isCollapsible;
	/** @var bool */
	private $isPrimary;
	/** @var string */
	private $formId;
	/** @var bool */
	private $autoExpandWidth;
	/** @var string */
	private $location;
	/** @var Config */
	private $config;
	private const SEARCH_SHOW_THUMBNAIL_CLASS = 'vector-search-box-show-thumbnail';
	private const SEARCH_AUTO_EXPAND_WIDTH_CLASS = 'vector-search-box-auto-expand-width';

	/**
	 * @return Config
	 */
	private function getConfig(): Config {
		return $this->config;
	}

	/**
	 * Returns `true` if Vue search is enabled to show thumbnails and `false` otherwise.
	 * Note this is only relevant for Vue search experience (not legacy search).
	 *
	 * @return bool
	 */
	private function doesSearchHaveThumbnails(): bool {
		return $this->getConfig()->get( 'VectorWvuiSearchOptions' )['showThumbnail'];
	}

	/**
	 * Gets the value of the "input-location" parameter for the SearchBox Mustache template.
	 *
	 * @return string Either `Constants::SEARCH_BOX_INPUT_LOCATION_DEFAULT` or
	 *  `Constants::SEARCH_BOX_INPUT_LOCATION_MOVED`
	 */
	private function getSearchBoxInputLocation(): string {
		return $this->location;
	}

	/**
	 * Annotates search box with Vector-specific information
	 *
	 * @param array $searchBoxData
	 * @param bool $isCollapsible
	 * @param bool $isPrimary
	 * @param string $formId
	 * @param bool $autoExpandWidth
	 * @return array modified version of $searchBoxData
	 */
	private function getSearchData(
		array $searchBoxData,
		bool $isCollapsible,
		bool $isPrimary,
		string $formId,
		bool $autoExpandWidth
	) {
		$searchClass = 'vector-search-box-vue ';

		if ( $isCollapsible ) {
			$searchClass .= ' vector-search-box-collapses ';
		}

		if ( $this->doesSearchHaveThumbnails() ) {
			$searchClass .= ' ' . self::SEARCH_SHOW_THUMBNAIL_CLASS .
				( $autoExpandWidth ? ' ' . self::SEARCH_AUTO_EXPAND_WIDTH_CLASS : '' );
		}

		// Annotate search box with a component class.
		$searchBoxData['class'] = trim( $searchClass );
		$searchBoxData['is-collapsible'] = $isCollapsible;
		$searchBoxData['is-primary'] = $isPrimary;
		$searchBoxData['form-id'] = $formId;
		$searchBoxData['input-location'] = $this->getSearchBoxInputLocation();

		// At lower resolutions the search input is hidden search and only the submit button is shown.
		// It should behave like a form submit link (e.g. submit the form with no input value).
		// We'll wire this up in a later task T284242.
		$collapseIconAttrs = Linker::tooltipAndAccesskeyAttribs( 'search' );
		$searchButton = new VectorComponentButton(
			$this->localizer->msg( 'search' ),
			'search',
			'',
			'search-toggle',
			$collapseIconAttrs,
			'quiet',
			'default',
			true,
			Title::newFromText( $searchBoxData['page-title'] )->getLocalURL()
		);
		$searchBoxData['data-collapsed-search-button'] = $searchButton->getTemplateData();

		return $searchBoxData;
	}

	/**
	 * @param array $searchBoxData
	 * @param bool $isCollapsible
	 * @param bool $isPrimary
	 * @param string $formId
	 * @param bool $autoExpandWidth
	 * @param Config $config
	 * @param string $location
	 * @param MessageLocalizer $localizer
	 */
	public function __construct(
		array $searchBoxData,
		bool $isCollapsible,
		bool $isPrimary,
		string $formId,
		bool $autoExpandWidth,
		Config $config,
		string $location,
		MessageLocalizer $localizer
	) {
		$this->searchBoxData = $searchBoxData;
		$this->isCollapsible = $isCollapsible;
		$this->isPrimary = $isPrimary;
		$this->formId = $formId;
		$this->autoExpandWidth = $autoExpandWidth;
		$this->location = $location;
		$this->config = $config;
		$this->localizer = $localizer;
	}

	/**
	 * @inheritDoc
	 */
	public function getTemplateData(): array {
		return $this->getSearchData(
			$this->searchBoxData,
			$this->isCollapsible,
			$this->isPrimary,
			$this->formId,
			$this->autoExpandWidth
		);
	}
}
