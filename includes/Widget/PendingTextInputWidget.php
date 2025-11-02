<?php

namespace MediaWiki\Widget;

use OOUI\TextInputWidget;

/**
 * Text input widget that displays pending animation.
 */
class PendingTextInputWidget extends TextInputWidget {

	/**
	 * @param array $config Configuration options
	 */
	public function __construct( array $config = [] ) {
		$config = array_merge( [
			'disabled' => true,
		], $config );

		parent::__construct( $config );

		// Initialization
		$this->input->addClasses( [ 'mw-widgets-pendingTextInputWidget oo-ui-pendingElement-pending' ] );
	}
}
