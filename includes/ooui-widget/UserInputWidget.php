<?php
/**
 * MediaWiki Widgets – UserInputWidget class.
 *
 * @copyright 2011-2015 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */
namespace MediaWiki\Widget;

/**
 * User input widget.
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
