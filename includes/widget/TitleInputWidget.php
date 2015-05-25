<?php

namespace MediaWiki\Widget;

use OOUI\TextInputWidget;
/**
 * Title input widget.
 */
class TitleInputWidget extends TextInputWidget {

	protected $namespace = null;

	/**
	 * @param array $config Configuration options
	 * @param number|null $config['namespace'] Namespace to prepend to queries
	 */
	public function __construct( array $config = array() ) {
		// Parent constructor
		parent::__construct( array_merge( $config, array( 'infusable' => true ) ) );

		// Properties
		if ( isset( $config['namespace'] ) ) {
			// Actually ignored in PHP, we just ship it back to JS
			$this->namespace = $config['namespace'];
		}

		// Initialization
		$this->addClasses( array( 'mw-widget-TitleInputWidget' ) );
	}

	public function getConfig( &$config ) {
		if ( $this->namespace !== null ) {
			$config['namespace'] = $this->namespace;
		}
		return parent::getConfig( $config );
	}
}
