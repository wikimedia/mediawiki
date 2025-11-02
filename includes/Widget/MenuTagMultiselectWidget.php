<?php

namespace MediaWiki\Widget;

/**
 * Widget to select multiple options.
 *
 * @copyright 2024 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license MIT
 */
class MenuTagMultiselectWidget extends TagMultiselectWidget {

	private array $mOptions;
	private array $mFallback;

	/**
	 * @param array $config Configuration options
	 *   - array $config['options'] Grouped options for the dropdown menu
	 *   - \OOUI\Widget[] $config['noJsFallback'] Fallback implementation for no-js clients
	 */
	public function __construct( $config ) {
		$this->mOptions = $config['options'];
		$this->mFallback = $config['noJsFallback'];

		parent::__construct( $config );
	}

	/** @inheritDoc */
	public function getConfig( &$config ) {
		$config['options'] = $this->mOptions;

		return parent::getConfig( $config );
	}

	/** @inheritDoc */
	protected function getNoJavaScriptFallback() {
		return $this->mFallback;
	}

	/** @inheritDoc */
	protected function getJavaScriptClassName() {
		return 'mw.widgets.MenuTagMultiselectWidget';
	}
}
