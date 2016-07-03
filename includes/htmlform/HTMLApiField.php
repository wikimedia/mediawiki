<?php

class HTMLApiField extends HTMLFormField {
	public function getTableRow( $value ) {
		return '';
	}

	public function getDiv( $value ) {
		return $this->getTableRow( $value );
	}

	public function getRaw( $value ) {
		return $this->getTableRow( $value );
	}

	public function getInputHTML( $value ) {
		return '';
	}

	public function hasVisibleOutput() {
		return false;
	}
}
