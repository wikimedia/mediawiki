<?php

class HTMLHiddenField extends HTMLFormField {
	public function __construct( $params ) {
		parent::__construct( $params );

		# Per HTML5 spec, hidden fields cannot be 'required'
		# http://dev.w3.org/html5/spec/states-of-the-type-attribute.html#hidden-state
		unset( $this->mParams['required'] );
	}

	public function getTableRow( $value ) {
		$params = array();
		if ( $this->mID ) {
			$params['id'] = $this->mID;
		}

		$this->mParent->addHiddenField( $this->mName, $this->mDefault, $params );

		return '';
	}

	/**
	 * @since 1.20
	 */
	public function getDiv( $value ) {
		return $this->getTableRow( $value );
	}

	/**
	 * @since 1.20
	 */
	public function getRaw( $value ) {
		return $this->getTableRow( $value );
	}

	public function getInputHTML( $value ) {
		return '';
	}
}
