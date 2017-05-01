<?php

class HTMLHiddenField extends HTMLFormField {
	protected $outputAsDefault = true;

	public function __construct( $params ) {
		parent::__construct( $params );

		if ( isset( $this->mParams['output-as-default'] ) ) {
			$this->outputAsDefault = (bool)$this->mParams['output-as-default'];
		}

		# Per HTML5 spec, hidden fields cannot be 'required'
		# https://www.w3.org/TR/html5/forms.html#hidden-state-%28type=hidden%29
		unset( $this->mParams['required'] );
	}

	public function getHiddenFieldData( $value ) {
		$params = [];
		if ( $this->mID ) {
			$params['id'] = $this->mID;
		}

		if ( $this->outputAsDefault ) {
			$value = $this->mDefault;
		}

		return [ $this->mName, $value, $params ];
	}

	public function getTableRow( $value ) {
		list( $name, $value, $params ) = $this->getHiddenFieldData( $value );
		$this->mParent->addHiddenField( $name, $value, $params );
		return '';
	}

	/**
	 * @param string $value
	 * @return string
	 * @since 1.20
	 */
	public function getDiv( $value ) {
		return $this->getTableRow( $value );
	}

	/**
	 * @param string $value
	 * @return string
	 * @since 1.20
	 */
	public function getRaw( $value ) {
		return $this->getTableRow( $value );
	}

	public function getInputHTML( $value ) {
		return '';
	}

	public function canDisplayErrors() {
		return false;
	}

	public function hasVisibleOutput() {
		return false;
	}
}
