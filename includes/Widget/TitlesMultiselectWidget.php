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
	/** @var int|null */
	protected $namespace = null;
	/** @var bool|null */
	protected $relative = null;
	/** @var bool|null */
	protected $allowEditTags = null;
	/** @var bool|null */
	protected $creatable = null;

	/**
	 * @param array $config Configuration options
	 *   - bool $config['showMissing'] Show missing pages in the typeahead dropdown
	 *     (ie. allow adding pages that don't exist)
	 *   - bool $config['namespace'] Shows pages only from the specified namespace
	 *   - bool $config['relative'] Include namespace names in form data, if 'namespace' config is used
	 *   - bool $config['allowEditTags'] Allow editing of the tags by clicking them
	 *   - bool $config['creatable'] Whether to validate the title  is creatable
	 *     (not a special page)
	 */
	public function __construct( array $config = [] ) {
		parent::__construct( $config );

		// Properties
		if ( isset( $config['showMissing'] ) ) {
			$this->showMissing = $config['showMissing'];
		}
		if ( isset( $config['namespace'] ) ) {
			$this->namespace = $config['namespace'];
		}
		if ( isset( $config['relative'] ) ) {
			$this->relative = $config['relative'];
		}
		if ( isset( $config['allowEditTags'] ) ) {
			$this->allowEditTags = $config['allowEditTags'];
		}
		if ( isset( $config['creatable'] ) ) {
			$this->creatable = $config['creatable'];
		}

		$this->addClasses( [ 'mw-widgets-titlesMultiselectWidget' ] );
	}

	/** @inheritDoc */
	protected function getJavaScriptClassName() {
		return 'mw.widgets.TitlesMultiselectWidget';
	}

	/** @inheritDoc */
	public function getConfig( &$config ) {
		if ( $this->showMissing !== null ) {
			$config['showMissing'] = $this->showMissing;
		}
		if ( $this->namespace !== null ) {
			$config['namespace'] = $this->namespace;
		}
		if ( $this->relative !== null ) {
			$config['relative'] = $this->relative;
		}
		if ( $this->allowEditTags !== null ) {
			$config['allowEditTags'] = $this->allowEditTags;
		}
		if ( $this->creatable !== null ) {
			$config['creatable'] = $this->creatable;
		}

		return parent::getConfig( $config );
	}

}
