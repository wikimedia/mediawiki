<?php

namespace OOUI;

/**
 * Label widget.
 */
class LabelWidget extends Widget {

	/* Static Properties */

	public static $tagName = 'span';

	/* Properties */

	/**
	 * Associated input element.
	 *
	 * @var InputWidget|null
	 */
	protected $input;

	/**
	 * @param array $config Configuration options
	 * @param InputWidget $config['input'] Input widget this label is for
	 */
	public function __construct( array $config = array() ) {
		// Parent constructor
		parent::__construct( $config );

		// Mixins
		$this->mixin( new LabelElement( $this,
			array_merge( $config, array( 'labelElement' => $this ) ) ) );

		// Properties
		$this->input = isset( $config['input'] ) ? $config['input'] : null;

		// Initialization
		$this->addClasses( array( 'oo-ui-labelWidget' ) );
	}

	public function getConfig( &$config ) {
		if ( $this->input !== null ) {
			$config['input'] = $this->input;
		}
		return parent::getConfig( $config );
	}
}
