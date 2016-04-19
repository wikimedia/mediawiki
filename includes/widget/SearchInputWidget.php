<?php
/**
 * MediaWiki Widgets – SearchInputWidget class.
 *
 * @copyright 2011-2015 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */
namespace MediaWiki\Widget;

/**
 * Search input widget.
 */
class SearchInputWidget extends TitleInputWidget {

	protected $pushPending = false;
	protected $performSearchOnClick = true;
	protected $validateTitle = false;
	protected $highlightFirst = false;
	protected $dataLocation = 'header';

	/**
	 * @param array $config Configuration options
	 * @param int|null $config['pushPending'] Whether the input should be visually marked as
	 *  "pending", while requesting suggestions (default: true)
	 * @param boolean|null $config['performSearchOnClick'] If true, the script will start a search
	 *  whenever a user hits a suggestion. If false, the text of the suggestion is inserted into the
	 *  text field only (default: true)
	 * @param string $config['dataLocation'] Where the search input field will be
	 *  used (header or content, default: header)
	 */
	public function __construct( array $config = [] ) {
		$config = array_merge( [
			'infusable' => true,
			'maxLength' => null,
			'type' => 'search',
			'icon' => 'search',
		], $config );

		// Parent constructor
		parent::__construct( $config );

		// Properties, which are ignored in PHP and just shipped back to JS
		if ( isset( $config['pushPending'] ) ) {
			$this->pushPending = $config['pushPending'];
		}

		if ( isset( $config['performSearchOnClick'] ) ) {
			$this->performSearchOnClick = $config['performSearchOnClick'];
		}

		if ( $config['dataLocation'] ) {
			// identifies the location of the search bar for tracking purposes
			$this->dataLocation = $config['dataLocation'];
		}

		// Initialization
		$this->addClasses( [ 'mw-widget-searchInputWidget' ] );
	}

	protected function getJavaScriptClassName() {
		return 'mw.widgets.SearchInputWidget';
	}

	public function getConfig( &$config ) {
		$config['pushPending'] = $this->pushPending;
		$config['performSearchOnClick'] = $this->performSearchOnClick;
		if ( $this->dataLocation ) {
			$config['dataLocation'] = $this->dataLocation;
		}
		return parent::getConfig( $config );
	}
}
