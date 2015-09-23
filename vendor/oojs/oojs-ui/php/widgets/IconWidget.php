<?php

namespace OOUI;

/**
 * Icon widget.
 *
 * See IconElement for more information.
 */
class IconWidget extends Widget {

	/* Static Properties */

	public static $tagName = 'span';

	/**
	 * @param array $config Configuration options
	 */
	public function __construct( array $config = array() ) {
		// Parent constructor
		parent::__construct( $config );

		// Mixins
		$this->mixin( new IconElement( $this,
			array_merge( $config, array( 'iconElement' => $this ) ) ) );
		$this->mixin( new TitledElement( $this,
			array_merge( $config, array( 'titled' => $this ) ) ) );
		$this->mixin( new FlaggedElement( $this,
			array_merge( $config, array( 'flagged' => $this ) ) ) );

		// Initialization
		$this->addClasses( array( 'oo-ui-iconWidget' ) );
	}
}
