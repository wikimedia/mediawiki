<?php

namespace MediaWiki\Widget;

/**
 * Widget to select multiple namespaces.
 *
 * @copyright 2017 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license MIT
 */
class NamespacesMultiselectWidget extends TagMultiselectWidget {

	/** @var bool|null */
	protected $allowEditTags = null;

	/**
	 * @param array $config Configuration options
	 *   - bool $config['allowEditTags'] Allow editing of the tags by clicking them
	 */
	public function __construct( array $config = [] ) {
		parent::__construct( $config );

		if ( isset( $config['allowEditTags'] ) ) {
			$this->allowEditTags = $config['allowEditTags'];
		}

		$this->addClasses( [ 'mw-widgets-namespacesMultiselectWidget' ] );
	}

	/** @inheritDoc */
	protected function getJavaScriptClassName() {
		return 'mw.widgets.NamespacesMultiselectWidget';
	}

	/** @inheritDoc */
	public function getConfig( &$config ) {
		if ( $this->allowEditTags !== null ) {
			$config['allowEditTags'] = $this->allowEditTags;
		}

		return parent::getConfig( $config );
	}

}
