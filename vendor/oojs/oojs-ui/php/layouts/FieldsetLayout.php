<?php

namespace OOUI;

/**
 * Layout made of a fieldset and optional legend.
 *
 * Just add FieldLayout items.
 */
class FieldsetLayout extends Layout {
	/**
	 * @param array $config Configuration options
	 * @param FieldLayout[] $config['items'] Items to add
	 */
	public function __construct( array $config = array() ) {
		// Parent constructor
		parent::__construct( $config );

		// Mixins
		$this->mixin( new IconElement( $this, $config ) );
		$this->mixin( new LabelElement( $this, $config ) );
		$this->mixin( new GroupElement( $this, $config ) );

		// Initialization
		$this
			->addClasses( array( 'oo-ui-fieldsetLayout' ) )
			->prependContent( $this->icon, $this->label, $this->group );
		if ( isset( $config['items'] ) ) {
			$this->addItems( $config['items'] );
		}
	}
}
