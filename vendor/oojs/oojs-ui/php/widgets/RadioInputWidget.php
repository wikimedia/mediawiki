<?php

namespace OOUI;

/**
 * Radio input widget.
 */
class RadioInputWidget extends InputWidget {

	/**
	 * @param array $config Configuration options
	 * @param boolean $config['selected'] Whether the radio button is initially selected
	 *   (default: false)
	 */
	public function __construct( array $config = array() ) {
		// Parent constructor
		parent::__construct( $config );

		// Initialization
		$this->addClasses( array( 'oo-ui-radioInputWidget' ) );
		// Required for pretty styling in MediaWiki theme
		$this->appendContent( new Tag( 'span' ) );
		$this->setSelected( isset( $config['selected'] ) ? $config['selected'] : false );
	}

	protected function getInputElement( $config ) {
		$input = new Tag( 'input' );
		$input->setAttributes( array( 'type' => 'radio' ) );
		return $input;
	}

	/**
	 * Set selection state of this radio button.
	 *
	 * @param boolean $state Whether the button is selected
	 */
	public function setSelected( $state ) {
		// RadioInputWidget doesn't track its state.
		if ( $state ) {
			$this->input->setAttributes( array( 'checked' => 'checked' ) );
		} else {
			$this->input->removeAttributes( array( 'checked' ) );
		}
		return $this;
	}

	/**
	 * Check if this radio button is selected.
	 *
	 * @return boolean Radio is selected
	 */
	public function isSelected() {
		return $this->input->getAttribute( 'checked' ) === 'checked';
	}

	public function getConfig( &$config ) {
		if ( $this->isSelected() ) {
			$config['selected'] = true;
		}
		return parent::getConfig( $config );
	}
}
