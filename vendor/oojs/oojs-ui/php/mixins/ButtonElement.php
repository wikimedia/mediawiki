<?php

namespace OOUI;

/**
 * Element with a button.
 *
 * Buttons are used for controls which can be clicked. They can be configured to use tab indexing
 * and access keys for accessibility purposes.
 *
 * @abstract
 */
class ButtonElement extends ElementMixin {
	/**
	 * Button is framed.
	 *
	 * @var boolean
	 */
	protected $framed = false;

	public static $targetPropertyName = 'button';

	/**
	 * @param Element $element Element being mixed into
	 * @param array $config Configuration options
	 * @param boolean $config['framed'] Render button with a frame (default: true)
	 */
	public function __construct( Element $element, array $config = array() ) {
		// Parent constructor
		$target = isset( $config['button'] ) ? $config['button'] : new Tag( 'a' );
		parent::__construct( $element, $target, $config );

		// Initialization
		$this->element->addClasses( array( 'oo-ui-buttonElement' ) );
		$this->target->addClasses( array( 'oo-ui-buttonElement-button' ) );
		$this->toggleFramed( isset( $config['framed'] ) ? $config['framed'] : true );
		$this->target->setAttributes( array(
			'role' => 'button',
		) );
	}

	/**
	 * Toggle frame.
	 *
	 * @param boolean $framed Make button framed, omit to toggle
	 * @chainable
	 */
	public function toggleFramed( $framed = null ) {
		$this->framed = $framed !== null ? !!$framed : !$this->framed;
		$this->element->toggleClasses( array( 'oo-ui-buttonElement-framed' ), $this->framed );
		$this->element->toggleClasses( array( 'oo-ui-buttonElement-frameless' ), !$this->framed );
	}

	/**
	 * Check if button has a frame.
	 *
	 * @return boolean Button is framed
	 */
	public function isFramed() {
		return $this->framed;
	}

	public function getConfig( &$config ) {
		if ( $this->framed !== true ) {
			$config['framed'] = $this->framed;
		}
		return parent::getConfig( $config );
	}
}
