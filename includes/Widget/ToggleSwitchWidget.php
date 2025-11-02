<?php

namespace MediaWiki\Widget;

use OOUI\CheckboxInputWidget;

/**
 * Base class for widgets to warp a checkbox into toggle widget.
 *
 * @copyright 2017 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license MIT
 */
class ToggleSwitchWidget extends CheckboxInputWidget {

	public function __construct( array $config = [] ) {
		parent::__construct( $config );

		$this->setValue( $config['selected'] );
		$this->addClasses( [ 'mw-widgets-toggleSwitchWidget' ] );
	}

	/** @inheritDoc */
	protected function getJavaScriptClassName() {
		return 'mw.widgets.ToggleSwitchWidget';
	}
}
