<?php

namespace MediaWiki\Widget;

/**
 * User input widget.
 *
 * @copyright 2011-2015 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license MIT
 */
class UserInputWidget extends \OOUI\TextInputWidget {

	/**
	 * @param array $config Configuration options
	 */
	public function __construct( array $config = [] ) {
		parent::__construct( $config );

		// Initialization
		$this->addClasses( [ 'mw-widget-userInputWidget' ] );
	}

	protected function getJavaScriptClassName() {
		return 'mw.widgets.UserInputWidget';
	}

	public function getConfig( &$config ) {
		$config['$overlay'] = true;
		return parent::getConfig( $config );
	}
}
