<?php

namespace MediaWiki\Widget;

/**
 * Widget to select multiple options from a dropdown.
 *
 * @license MIT
 */
class OrderedMultiselectWidget extends TagMultiselectWidget {

	private array $mOptions;

	/**
	 * @param array $config Configuration options
	 *   - array $config['options'] Grouped options for the dropdown menu
	 */
	public function __construct( $config ) {
		$this->mOptions = $config['options'];
		parent::__construct( $config );
	}

	/** @inheritDoc */
	public function getConfig( &$config ) {
		$config['options'] = $this->mOptions;

		return parent::getConfig( $config );
	}

	/** @inheritDoc */
	protected function getJavaScriptClassName() {
		return 'mw.widgets.OrderedMultiselectWidget';
	}
}
