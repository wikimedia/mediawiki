<?php

namespace MediaWiki\Widget;

/**
 * Widget to select multiple titles.
 *
 * @copyright 2017 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license MIT
 */
class TitlesMultiselectWidget extends TagMultiselectWidget {

	/** @var bool|null */
	protected $showMissing = null;
	/** @var bool|null */
	protected $excludeDynamicNamespaces = null;
	/** @var bool|null */
	protected $allowEditTags = null;

	/**
	 * @param array $config Configuration options
	 *   - bool $config['showMissing'] Show missing pages in the typeahead dropdown
	 *     (ie. allow adding pages that don't exist)
	 *   - bool $config['excludeDynamicNamespaces'] Exclude pages in negative namespaces
	 *   - bool $config['allowEditTags'] Allow editing of the tags by clicking them
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
		if ( isset( $config['allowEditTags'] ) ) {
			$this->allowEditTags = $config['allowEditTags'];
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
		if ( $this->allowEditTags !== null ) {
			$config['allowEditTags'] = $this->allowEditTags;
		}

		return parent::getConfig( $config );
	}

}
