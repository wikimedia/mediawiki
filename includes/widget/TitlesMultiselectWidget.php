<?php

namespace MediaWiki\Widget;

/**
 * Widget to select multiple titles.
 *
 * @copyright 2017 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license MIT
 */
class TitlesMultiselectWidget extends TagMultiselectWidget {

	protected $showMissing = null;
	protected $excludeDynamicNamespaces = null;

	/**
	 * @param array $config Configuration options
	 *   - bool $config['showMissing'] Show missing pages in the typeahead dropdown
	 *     (ie. allow adding pages that don't exist)
	 *   - bool $config['excludeDynamicNamespaces'] Exclude pages in negative namespaces
	 */
	public function __construct( array $config = [] ) {
		parent::__construct( $config );

		// Properties
		if ( isset( $config['showMissing'] ) ) {
			$this->showMissing = $config['showMissing'];
		}
		if ( isset( $config['excludeDynamicNamespaces'] ) ) {
			$this->excludeDynamicNamespaces = $config['excludeDynamicNamespaces'];
		}

		$this->addClasses( [ 'mw-widgets-titlesMultiselectWidget' ] );
	}

	protected function getJavaScriptClassName() {
		return 'mw.widgets.TitlesMultiselectWidget';
	}

	public function getConfig( &$config ) {
		if ( $this->showMissing !== null ) {
			$config['showMissing'] = $this->showMissing;
		}
		if ( $this->excludeDynamicNamespaces !== null ) {
			$config['excludeDynamicNamespaces'] = $this->excludeDynamicNamespaces;
		}

		return parent::getConfig( $config );
	}

}
