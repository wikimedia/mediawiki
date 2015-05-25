<?php

namespace OOUI;

/**
 * Title input widget.
 */
class MWTitleInputWidget extends TextInputWidget {

	/**
	 * @param array $config Configuration options
	 */
	public function __construct( array $config = array() ) {
		// Parent constructor
		parent::__construct( array_merge( $config, array( 'infusable' => true ) ) );

		// Initialization
		$this->addClasses( array( 'oo-ui-mwTitleInputWidget' ) );
	}
}
