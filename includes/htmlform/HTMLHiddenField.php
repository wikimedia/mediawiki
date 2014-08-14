<?php

class HTMLHiddenField extends HTMLFormField {
	public function __construct( $params ) {
		parent::__construct( $params );

		# Per HTML5 spec, hidden fields cannot be 'required'
		# http://www.w3.org/TR/html5/forms.html#hidden-state-%28type=hidden%29
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
}
