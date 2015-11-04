<?php

class HTMLHiddenField extends HTMLFormField {
	protected $outputAsDefault = true;

	public function __construct( $params ) {
		parent::__construct( $params );

		if ( isset( $this->mParams['output-as-default'] ) ) {
			$this->outputAsDefault = (bool)$this->mParams['output-as-default'];
		}

		# Per HTML5 spec, hidden fields cannot be 'required'
		# http://www.w3.org/TR/html5/forms.html#hidden-state-%28type=hidden%29
		unset( $this->mParams['required'] );

		list( $name, $value, $params ) = $this->getHiddenFieldData( $this->mDefault );
		$this->mParent->addHiddenField( $name, $value, $params );
	}

	public function getHiddenFieldData( $value ) {
		$params = array();
		if ( $this->mID ) {
			$params['id'] = $this->mID;
		}

		if ( $this->outputAsDefault ) {
			$value = $this->mDefault;
		}

		return array( $this->mName, $value, $params );
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
