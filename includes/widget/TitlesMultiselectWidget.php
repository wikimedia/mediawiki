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

	/**
	 * @param array $config Configuration options
	 *   - bool $config['showMissing'] Show missing pages
	 */
	public function __construct( array $config = [] ) {
		parent::__construct( $config );

		// Properties
		if ( isset( $config['showMissing'] ) ) {
			$this->showMissing = $config['showMissing'];
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

		return parent::getConfig( $config );
	}

}
