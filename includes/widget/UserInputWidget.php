<?php

namespace MediaWiki\Widget;

/**
 * User input widget.
 *
 * @license MIT
 */
class UserInputWidget extends \OOUI\TextInputWidget {

	/**
	 * @param array $config Configuration options
	 */
	public function __construct( array $config = [] ) {
		// Parent constructor
		parent::__construct( $config );

		// Initialization
		$this->addClasses( [ 'mw-widget-userInputWidget' ] );
	}

	protected function getJavaScriptClassName() {
		return 'mw.widgets.UserInputWidget';
	}
}
