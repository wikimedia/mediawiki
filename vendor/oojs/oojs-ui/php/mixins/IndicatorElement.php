<?php

namespace OOUI;

/**
 * Element containing an indicator.
 *
 * Indicators are graphics, smaller than normal text. They can be used to describe unique status or
 * behavior. Indicators should only be used in exceptional cases; such as a button that opens a menu
 * instead of performing an action directly, or an item in a list which has errors that need to be
 * resolved.
 *
 * @abstract
 */
class IndicatorElement extends ElementMixin {
	/**
	 * Symbolic indicator name
	 *
	 * @var string|null
	 */
	protected $indicator = null;

	public static $targetPropertyName = 'indicator';

	/**
	 * @param Element $element Element being mixed into
	 * @param array $config Configuration options
	 * @param string $config['indicator'] Symbolic indicator name
	 */
	public function __construct( Element $element, array $config = array() ) {
		// Parent constructor
		// FIXME 'indicatorElement' is a very stupid way to call '$indicator'
		$target = isset( $config['indicatorElement'] )
			? $config['indicatorElement']
			: new Tag( 'span' );
		parent::__construct( $element, $target, $config );

		// Initialization
		$this->target->addClasses( array( 'oo-ui-indicatorElement-indicator' ) );
		$this->setIndicator( isset( $config['indicator'] ) ? $config['indicator'] : null );
	}

	/**
	 * Set indicator name.
	 *
	 * @param string|null $indicator Symbolic name of indicator to use or null for no indicator
	 * @chainable
	 */
	public function setIndicator( $indicator = null ) {
		if ( $this->indicator !== null ) {
			$this->target->removeClasses( array( 'oo-ui-indicator-' . $this->indicator ) );
		}
		if ( $indicator !== null ) {
			$this->target->addClasses( array( 'oo-ui-indicator-' . $indicator ) );
		}

		$this->indicator = $indicator;
		$this->element->toggleClasses( array( 'oo-ui-indicatorElement' ), (bool)$this->indicator );

		return $this;
	}

	/**
	 * Get indicator name.
	 *
	 * @return string Symbolic name of indicator
	 */
	public function getIndicator() {
		return $this->indicator;
	}

	public function getConfig( &$config ) {
		if ( $this->indicator !== null ) {
			$config['indicator'] = $this->indicator;
		}
		return parent::getConfig( $config );
	}
}
