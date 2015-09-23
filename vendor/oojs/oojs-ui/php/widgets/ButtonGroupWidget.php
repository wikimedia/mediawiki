<?php

namespace OOUI;

/**
 * Group widget for multiple related buttons.
 *
 * Use together with ButtonWidget.
 */
class ButtonGroupWidget extends Widget {
	/**
	 * @param array $config Configuration options
	 * @param ButtonWidget[] $config['items'] Buttons to add
	 */
	public function __construct( array $config = array() ) {
		// Parent constructor
		parent::__construct( $config );

		// Mixins
		$this->mixin( new GroupElement( $this, array_merge( $config, array( 'group' => $this ) ) ) );

		// Initialization
		$this->addClasses( array( 'oo-ui-buttonGroupWidget' ) );
		if ( isset( $config['items'] ) ) {
			$this->addItems( $config['items'] );
		}
	}
}
