<?php

namespace OOUI;

/**
 * Container for elements.
 *
 * @abstract
 */
class Layout extends Element {
	/**
	 * @param array $config Configuration options
	 */
	public function __construct( array $config = array() ) {
		// Parent constructor
		parent::__construct( $config );

		// Initialization
		$this->addClasses( array( 'oo-ui-layout' ) );
	}
}
