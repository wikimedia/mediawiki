<?php

namespace OOUI;

/**
 * Base class for input widgets.
 *
 * @abstract
 */
class InputWidget extends Widget {

	/* Static Properties */

	public static $supportsSimpleLabel = true;

	/* Properties */

	/**
	 * Input element.
	 *
	 * @var Tag
	 */
	protected $input;

	/**
	 * Input value.
	 *
	 * @var string
	 */
	protected $value = '';

	/**
	 * @param array $config Configuration options
	 * @param string $config['name'] HTML input name (default: '')
	 * @param string $config['value'] Input value (default: '')
	 */
	public function __construct( array $config = array() ) {
		// Parent constructor
		parent::__construct( $config );

		// Properties
		$this->input = $this->getInputElement( $config );

		// Mixins
		$this->mixin( new FlaggedElement( $this,
			array_merge( $config, array( 'flagged' => $this ) ) ) );
		$this->mixin( new TabIndexedElement( $this,
			array_merge( $config, array( 'tabIndexed' => $this->input ) ) ) );
		$this->mixin( new TitledElement( $this,
			array_merge( $config, array( 'titled' => $this->input ) ) ) );
		$this->mixin( new AccessKeyedElement( $this,
			array_merge( $config, array( 'accessKeyed' => $this->input ) ) ) );

		// Initialization
		if ( isset( $config['name'] ) ) {
			$this->input->setAttributes( array( 'name' => $config['name'] ) );
		}
		if ( $this->isDisabled() ) {
			$this->input->setAttributes( array( 'disabled' => 'disabled' ) );
		}
		$this
			->addClasses( array( 'oo-ui-inputWidget' ) )
			->appendContent( $this->input );
		$this->input->addClasses( array( 'oo-ui-inputWidget-input' ) );
		$this->setValue( isset( $config['value'] ) ? $config['value'] : null );
	}

	/**
	 * Get input element.
	 *
	 * @param array $config Configuration options
	 * @return Tag Input element
	 */
	protected function getInputElement( $config ) {
		return new Tag( 'input' );
	}

	/**
	 * Get the value of the input.
	 *
	 * @return string Input value
	 */
	public function getValue() {
		return $this->value;
	}

	/**
	 * Sets the direction of the current input, either RTL or LTR
	 *
	 * @param boolean $isRTL
	 */
	public function setRTL( $isRTL ) {
		if ( $isRTL ) {
			$this->input->removeClasses( array( 'oo-ui-ltr' ) );
			$this->input->addClasses( array( 'oo-ui-rtl' ) );
		} else {
			$this->input->removeClasses( array( 'oo-ui-rtl' ) );
			$this->input->addClasses( array( 'oo-ui-ltr' ) );
		}
	}

	/**
	 * Set the value of the input.
	 *
	 * @param string $value New value
	 * @chainable
	 */
	public function setValue( $value ) {
		$this->value = $this->cleanUpValue( $value );
		$this->input->setValue( $this->value );
		return $this;
	}

	/**
	 * Clean up incoming value.
	 *
	 * Ensures value is a string, and converts null to empty string.
	 *
	 * @param string $value Original value
	 * @return string Cleaned up value
	 */
	protected function cleanUpValue( $value ) {
		if ( $value === null ) {
			return '';
		} else {
			return (string)$value;
		}
	}

	public function setDisabled( $state ) {
		parent::setDisabled( $state );
		if ( isset( $this->input ) ) {
			if ( $this->isDisabled() ) {
				$this->input->setAttributes( array( 'disabled' => 'disabled' ) );
			} else {
				$this->input->removeAttributes( array( 'disabled' ) );
			}
		}
		return $this;
	}

	public function getConfig( &$config ) {
		$name = $this->input->getAttribute( 'name' );
		if ( $name !== null ) {
			$config['name'] = $name;
		}
		if ( $this->value !== '' ) {
			$config['value'] = $this->value;
		}
		return parent::getConfig( $config );
	}
}
