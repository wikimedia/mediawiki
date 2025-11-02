<?php

namespace MediaWiki\Widget;

use OOUI\Tag;

/**
 * Search input widget.
 *
 * @copyright 2011-2015 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license MIT
 */
class SearchInputWidget extends TitleInputWidget {

	/** @var bool */
	protected $performSearchOnClick = true;
	/** @var bool */
	protected $validateTitle = false;
	/** @var bool */
	protected $highlightFirst = false;
	/** @var string */
	protected $dataLocation = 'header';
	/** @var bool */
	protected $showDescriptions = false;

	/**
	 * @param array $config Configuration options
	 *   - bool|null $config['performSearchOnClick'] If true, the script will start a search
	 *     whenever a user hits a suggestion. If false, the text of the suggestion is inserted into
	 *     the text field only (default: true)
	 *   - string $config['dataLocation'] Where the search input field will be
	 *     used (header or content, default: header)
	 */
	public function __construct( array $config = [] ) {
		$config = array_merge( [
			'maxLength' => null,
			'icon' => 'search',
		], $config );
		'@phan-var array $config';

		parent::__construct( $config );

		// Properties, which are ignored in PHP and just shipped back to JS
		if ( isset( $config['performSearchOnClick'] ) ) {
			$this->performSearchOnClick = $config['performSearchOnClick'];
		}

		if ( isset( $config['dataLocation'] ) ) {
			// identifies the location of the search bar for tracking purposes
			$this->dataLocation = $config['dataLocation'];
		}

		if ( !empty( $config['showDescriptions'] ) ) {
			$this->showDescriptions = true;
		}

		// Perhaps should be upstreamed to TextInputWidget?
		if ( isset( $config['autocapitalize'] ) ) {
			$this->input->setAttributes( [ 'autocapitalize' => $config['autocapitalize'] ] );
		}

		// Initialization
		$this->addClasses( [ 'mw-widget-searchInputWidget' ] );
	}

	/** @inheritDoc */
	protected function getInputElement( $config ) {
		return ( new Tag( 'input' ) )->setAttributes( [ 'type' => 'search' ] );
	}

	/** @inheritDoc */
	protected function getJavaScriptClassName() {
		return 'mw.widgets.SearchInputWidget';
	}

	/** @inheritDoc */
	public function getConfig( &$config ) {
		$config['performSearchOnClick'] = $this->performSearchOnClick;
		if ( $this->dataLocation ) {
			$config['dataLocation'] = $this->dataLocation;
		}
		if ( $this->showDescriptions ) {
			$config['showDescriptions'] = true;
		}
		$config['$overlay'] = true;
		return parent::getConfig( $config );
	}
}
